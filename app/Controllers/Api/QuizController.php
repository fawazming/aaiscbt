<?php
 
namespace App\Controllers\Api;
 
use App\Controllers\BaseController;
use App\Models\QuizModel;
 
class QuizController extends BaseController
{
    public function fetch()
    {
        $code = $this->request->getGet('access_code');
        if (!$code) {
            return $this->response->setJSON(['error' => 'Access code required']);
        }
 
        $quizModel = new QuizModel();
        $quiz = $quizModel->where('code', $code)->where('published', 1)->first();
 
        if (!$quiz) {
            return $this->response->setJSON(['error' => 'Quiz not found or unpublished']);
        }
 
        // Decode stored JSON questions
        // $questions = json_decode($quiz['questions'], true);
 
        return $this->response->setJSON($quiz['questions']);
    }

    public function fetcha()
    {
        $code = $this->request->getGet('access_code');
        if (!$code) {
            return $this->response->setJSON(['error' => 'Access code required']);
        }
 
        $quizModel = new QuizModel();
        $quiz = $quizModel->where('code', $code)->where('published', 1)->first();
        // dd($quiz['answers']);
        if (!$quiz) {
            return $this->response->setJSON(['error' => 'Quiz not found or unpublished']);
        }
 
        return $this->response->setJSON($quiz['answers']);
    }
}