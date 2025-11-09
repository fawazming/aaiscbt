<?php
 
namespace App\Controllers\Api;
 
use App\Controllers\BaseController;
use App\Models\ScoreModel;
use App\Models\QuizModel;
 
class ScoreController extends BaseController
{
     protected $aiScorerUrl = "https://openrouter.ai/api/v1/chat/completions";
    protected $aiApiKey   = "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"; // Replace with your actual AI key

    public function submi()
    {
        $data = $this->request->getJSON(true);
 
        if (!$data || !isset($data['candidate']) || !isset($data['access_code'])) {
            return $this->response->setJSON(['error' => 'Invalid request data']);
        }
 
        $quizModel = new QuizModel();
        $quiz = $quizModel->where('code', $data['access_code'])->first();
 
        if (!$quiz) {
            return $this->response->setJSON(['error' => 'Quiz not found']);
        }
 
        $scoreModel = new ScoreModel();
        $scoreModel->insert([
            'user' => $data['candidate'],
            'quiz' => $quiz['id'],
            'score' => $data['score'],
            'sent' => 1,
            'answers' => json_encode($data['answers'])
        ]);
 
        return $this->response->setJSON(['success' => true, 'message' => 'Score saved successfully']);
    }

    public function submit() {
        try {
            $data = $this->request->getJSON(true);
            $accessCode = $data['access_code'] ?? null;
            $candidateName = $data['candidate'] ?? null;
            $answers = $data['answers'] ?? [];

            if (!$accessCode || !$candidateName) {
                return $this->fail("Missing required parameters");
            }

            // 🔹 1. Fetch quiz & correct answers from DB
            $quizModel = new QuizModel();
            $quiz = $quizModel->where('code', $accessCode)
                              ->where('published', 1)
                              ->first();

            if (!$quiz) {
                return $this->failNotFound("Quiz not found or unpublished");
            }

            $questions = json_decode($quiz['questions'], true); // optional if stored as JSON
            $correctAnswersCache = json_decode($quiz['answers'], true); // [{id:1, ans:"C"}, ...]

            // Build a map for quick lookup
            $correctMap = [];
            foreach ($correctAnswersCache as $a) {
                $correctMap[$a['id']] = $a['ans'];
            }

            // 🔹 2. Score test + prepare CSV-like rows
            $totalScore = 0;
            $maxScore = count($questions);
            $rows = [['Question', 'Your Answer', 'Correct Answer', 'Score']];

            foreach ($questions as $q) {
                $qid = $q['id'];
                $userAns = $answers[$qid] ?? '';
                $correctAns = $correctMap[$qid] ?? '';
                $scoreMark = '❌';
                $correctText = '';

                if ($q['type'] === 'mcq') {
                    $optionMap = [
                        'A' => $q['1'] ?? '',
                        'B' => $q['2'] ?? '',
                        'C' => $q['3'] ?? '',
                        'D' => $q['4'] ?? ''
                    ];
                    $correctText = $optionMap[$correctAns] ?? '';
                    if (trim($userAns) === trim($correctText)) {
                        $scoreMark = '✅';
                        $totalScore++;
                    }
                } elseif ($q['type'] === 'fill') {
                    $correctText = $correctAns ?? '';
                    if (strtolower(trim($userAns)) === strtolower(trim($correctText))) {
                        $scoreMark = '✅';
                        $totalScore++;
                    } else {
                        if ($this->checkWithAI($correctText, $userAns)) {
                            $scoreMark = '✅';
                            $totalScore++;
                        }
                    }
                }

                $rows[] = [$q['question'] ?? '', $userAns, $correctText, $scoreMark];
            }

            $avgScore = round(($totalScore / $maxScore) * 100, 2);

            // 🔹 3. Store results
            $scoreModel = new ScoreModel();
            $scoreModel->insert([
                'candidate' => $candidateName,
                'access_code' => $accessCode,
                'answers' => json_encode($rows),
                'score' => $totalScore,
            ]);

            // Return JSON (score + CSV rows)
            return $this->response->setJSON([
                'total_score' => $totalScore,
                'max_score' => $maxScore,
                'avg_score' => $avgScore,
                'csv_rows' => $rows
            ]);

        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    
    private function checkWithAI($correctAnswer, $userAnswer)
    {
        if (!$userAnswer) return false;

        $client = \Config\Services::curlrequest();
        $prompt = "Compare the user's answer '{$userAnswer}' to the correct answer '{$correctAnswer}' for a fill-in-the-gap question. Treat the answers as equivalent if they refer to the same main concept, ignoring case, leading/trailing spaces, minor punctuation, common articles ('a', 'an', 'the'), extra descriptive words (like 'fast', 'large', 'desert'), minor word order differences, and simple plural/singular forms.  Respond with only '1' if they match or '0' if they do not.";

        try {
            $res = $client->post($this->aiScorerUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->aiApiKey
                ],
                'json' => [
                    'model' => 'deepseek/deepseek-chat-v3.1',
                    'messages' => [['role' => 'user', 'content' => $prompt]]
                ]
            ]);

            $data = json_decode($res->getBody(), true);
            $answer = $data['choices'][0]['message']['content'] ?? '0';
            return trim($answer) === '1';
        } catch (\Exception $e) {
            log_message('error', 'AI scorer error: ' . $e->getMessage());
            return false;
        }
    }
}