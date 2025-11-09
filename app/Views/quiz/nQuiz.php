<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CBT Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex items-center justify-center">

    <!-- Main Container -->
    <div id="app" class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8 fade-in">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">Smart CBT System</h1>

        <!-- Access Code Form -->
        <div id="access-form">
            <p class="mb-4 text-gray-600 text-center">Enter your access code to start the test</p>
            <input id="accessCode" class="w-full p-3 border rounded mb-4 focus:ring focus:ring-blue-200"
                placeholder="Access Code" />
            <input id="candidateName" class="w-full p-3 border rounded mb-4 focus:ring focus:ring-blue-200"
                placeholder="Your Full Name" />
            <button id="startBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded">Start
                Test</button>
        </div>

        <!-- Quiz Section -->
        <div id="quiz-section" class="hidden fade-in">
            <div class="flex justify-between items-center mb-4">
                <h2 id="question-number" class="text-lg font-semibold text-blue-600"></h2>
                <div id="timer" class="font-bold text-red-500"></div>
            </div>
            <p id="question-text" class="mb-4 text-gray-800"></p>
            <div id="options-container" class="space-y-2 mb-6"></div>

            <div class="flex justify-between">
                <button id="prevBtn"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded disabled:opacity-50">Prev</button>
                <button id="nextBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Next</button>
            </div>
        </div>

        <!-- Result Section -->
        <div id="result-section" class="hidden text-center fade-in">
            <h2 class="text-xl font-bold text-green-600 mb-4">Test Completed!</h2>
            <p id="score-display" class="mb-2 text-lg"></p>
            <p id="analytics" class="text-sm text-gray-500 mb-6"></p>
            <button id="exportCSV" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mr-2">Export
                CSV</button>
            <button id="exportPDF" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Export
                PDF</button>
        </div>
    </div>

    <script>
        const API_FETCH_URL = "<?=base_url()?>api/fetch-test"; // Replace with real endpoint
        const API_SUBMIT_URL = "<?=base_url()?>api/submit-results"; // Replace with real endpoint
        const AI_SCORER_URL = "https://openrouter.ai/api/v1/chat/completions";

        let questions = [];
        let currentIndex = 0;
        let answers = {};
        let timerDuration = 300; // seconds (5 min)
        let timerInterval;

        // DOM Elements
        const accessForm = document.getElementById('access-form');
        const quizSection = document.getElementById('quiz-section');
        const resultSection = document.getElementById('result-section');
        const questionNumber = document.getElementById('question-number');
        const questionText = document.getElementById('question-text');
        const optionsContainer = document.getElementById('options-container');
        const timerDisplay = document.getElementById('timer');
        const scoreDisplay = document.getElementById('score-display');
        const analytics = document.getElementById('analytics');

        document.getElementById('startBtn').addEventListener('click', async () => {
            const accessCode = document.getElementById('accessCode').value.trim();
            const candidateName = document.getElementById('candidateName').value.trim();
            if (!accessCode || !candidateName) return alert('Please fill all fields.');

            // Fetch questions from API
            const response = await fetch(`${API_FETCH_URL}?access_code=${accessCode}`);
            questions = await response.json();
            if (!questions || !questions.length) return alert('Invalid access code or no questions found.');

            // Shuffle questions and their options (if MCQ)
            questions = shuffleArray(
                questions.map(q => {
                    if (q.type === 'mcqRandomO') {
                        // Extract MCQ options (keys "1"–"4")
                        const options = [q["1"], q["2"], q["3"], q["4"]];
                        const shuffled = shuffleArray(options);

                        // Return the question with shuffled options reassigned
                        return {
                            ...q,
                            "1": shuffled[0],
                            "2": shuffled[1],
                            "3": shuffled[2],
                            "4": shuffled[3]
                        };
                    } else {
                        // For fill-type questions, leave options empty
                        return { ...q };
                    }
                })
            );

            localStorage.setItem('questions', JSON.stringify(questions));
            localStorage.setItem('candidateName', candidateName);
            localStorage.setItem('accessCode', accessCode);

            accessForm.classList.add('hidden');
            quizSection.classList.remove('hidden');
            startTimer();
            renderQuestion();
        });

        function renderQuestion() {
            const q = questions[currentIndex];
            questionNumber.textContent = `Question ${currentIndex + 1}/${questions.length}`;
            questionText.textContent = q["0"]; // question text
            optionsContainer.innerHTML = '';
            console.log(answers)

            if (q.type === 'mcq') {
                // Get available options (keys "1"–"4")
                const options = [q["1"], q["2"], q["3"], q["4"]].filter(Boolean);

                options.forEach(opt => {
                    const btn = document.createElement('button');
                    btn.className = `w-full p-3 border rounded hover:bg-blue-100 text-left ${answers[q.id] === opt ? 'bg-blue-50 border-blue-400' : ''
                        }`;
                    btn.textContent = opt;

                    // When clicked, store selected answer and re-render
                    btn.onclick = () => {
                        answers[q.id] = opt;
                        renderQuestion();
                    };

                    optionsContainer.appendChild(btn);
                });
            } else {
                // For fill-type questions
                const input = document.createElement('input');
                input.type = 'text';
                input.placeholder = 'Type your answer';
                input.value = answers[q.id] || '';
                input.className = 'w-full p-3 border rounded';
                input.oninput = e => (answers[q.id] = e.target.value.trim());
                optionsContainer.appendChild(input);
            }

            // Navigation button logic
            document.getElementById('prevBtn').disabled = currentIndex === 0;
            document.getElementById('nextBtn').textContent =
                currentIndex === questions.length - 1 ? 'Submit' : 'Next';
        }


        document.getElementById('prevBtn').onclick = () => { currentIndex--; renderQuestion(); };
        document.getElementById('nextBtn').onclick = async () => {
            if (currentIndex < questions.length - 1) currentIndex++;
            else await submitTest();
            renderQuestion();
        };

        function startTimer() {
            timerInterval = setInterval(() => {
                if (timerDuration <= 0) { clearInterval(timerInterval); submitTest(); return; }
                timerDuration--;
                timerDisplay.textContent = `Time: ${Math.floor(timerDuration / 60)}:${(timerDuration % 60).toString().padStart(2, '0')}`;
            }, 1000);
        }

        // async function submitTest() {
        //   clearInterval(timerInterval);
        //   quizSection.classList.add('hidden');
        //   resultSection.classList.remove('hidden');

        //   // Score MCQs locally
        //   let totalScore = 0;
        //   let maxScore = questions.length;
        //   for (let q of questions) {
        //     if (q.type === 'mcq' && answers[q.id] === q.correct) totalScore++;
        //     else if (q.type === 'fill') {
        //       const aiScore = await checkWithAI(q.correct, answers[q.id]);
        //       totalScore += aiScore;
        //     }
        //   }

        //   // Analytics
        //   const avgScore = (totalScore / maxScore * 100).toFixed(2);
        //   scoreDisplay.textContent = `Your Score: ${totalScore}/${maxScore} (${avgScore}%)`;
        //   analytics.textContent = `Average accuracy: ${avgScore}%`;

        //   // Send results to API
        //   const payload = {
        //     candidate: localStorage.getItem('candidateName'),
        //     access_code: localStorage.getItem('accessCode'),
        //     answers,
        //     score: totalScore,
        //   };
        //   await fetch(API_SUBMIT_URL, {
        //     method: 'POST',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify(payload)
        //   });
        // }

        // Global cache to avoid multiple API calls
        let correctAnswersCache = [];
        let csvRowsCache = [];

        async function submitTest() {
            try {
                clearInterval(timerInterval);
                quizSection.classList.add('hidden');
                resultSection.classList.remove('hidden');

                const accessCode = localStorage.getItem('accessCode');
                const candidateName = localStorage.getItem('candidateName');

                // 🔹 4. Send results to API
                const payload = {
                    candidate: candidateName,
                    access_code: accessCode,
                    answers
                };

                // await fetch(API_SUBMIT_URL, {
                //     method: "POST",
                //     headers: { "Content-Type": "application/json" },
                //     body: JSON.stringify(payload),
                // });
                await fetch(API_SUBMIT_URL, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload),
                })
                    .then(response => response.json())
                    .then(data => {
                        // Example response: { total_score: 10, max_score: 10, avg_score: 100 }

                        // Display the score
                        scoreDisplay.textContent = `Your Score: ${data.total_score} / ${data.max_score}`;

                        // Display analytics or extra info
                        analytics.textContent = `Average Score: ${data.avg_score}%`;

                        // Optionally, adjust style for perfect score
                        if (data.total_score === data.max_score) {
                            scoreDisplay.style.color = "green";
                            analytics.textContent += " 🎉 Excellent work!";
                        } else {
                            scoreDisplay.style.color = "orange";
                        }
                    })
                    .catch(error => {
                        console.error("Error submitting score:", error);
                        scoreDisplay.textContent = "Error submitting score. Please try again.";
                        analytics.textContent = "";
                    });


                // Now results are shown — user can choose to export later
                document.getElementById("exportCSV").disabled = false;

            } catch (err) {
                console.error("Error submitting test:", err);
                alert("An error occurred while submitting your test. Please try again.");
            }
        }


        // 🔹 Separate CSV export — only runs when user clicks "Export Results"
        document.getElementById('exportCSV').onclick = () => {
            if (!csvRowsCache.length) {
                alert("No results available to export yet. Please submit your test first.");
                return;
            }

            const candidateName = localStorage.getItem('candidateName');
            const accessCode = localStorage.getItem('accessCode');

            const csvContent =
                "data:text/csv;charset=utf-8," +
                csvRowsCache
                    .map(row =>
                        row.map(value => `"${String(value).replace(/"/g, '""')}"`).join(",")
                    )
                    .join("\n");

            const link = document.createElement("a");
            link.href = encodeURI(csvContent);
            link.download = `${candidateName || "results"}_${accessCode}.csv`;
            link.click();
        };

        function shuffleArray(arr) {
            const copy = [...arr];
            for (let i = copy.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [copy[i], copy[j]] = [copy[j], copy[i]];
            }
            return copy;
        }

        document.getElementById('exportPDF').onclick = () => {
            window.print(); // Simple PDF export
        };
    </script>
</body>

</html>