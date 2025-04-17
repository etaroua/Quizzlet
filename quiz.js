document.addEventListener("DOMContentLoaded", function () {
    const questions = document.querySelectorAll('.question-wrapper');
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    let currentQuestion = 0;
    let timer;
    const TIMER_SECONDS = 30;

    let correctAnswers = 0;
    const totalQuestions = questions.length;
    const quizTitle = document.querySelector('.quiz-header h1').textContent;

    // Prevent form submission if wrapped in a form
    const quizForm = document.querySelector('form');
    if (quizForm) {
        quizForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Stop page refresh and "localhost says" popup
        });
    }

    function formatTime(seconds) {
        const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
        const secs = String(seconds % 60).padStart(2, '0');
        return `${mins}:${secs}`;
    }

    function startTimer(index) {
        let time = TIMER_SECONDS;
        const wrapper = questions[index];
        const timeDisplay = wrapper.querySelector('.time-remaining');
        const fill = wrapper.querySelector('.progress-fill');
        fill.style.width = '100%';

        clearInterval(timer);

        timer = setInterval(() => {
            time--;
            timeDisplay.textContent = formatTime(time);
            fill.style.width = `${(time / TIMER_SECONDS) * 100}%`;

            if (time <= 0) {
                clearInterval(timer);
                autoRevealAnswer(wrapper);
                setTimeout(() => {
                    if (currentQuestion < questions.length - 1) {
                        currentQuestion++;
                        showQuestion(currentQuestion);
                    } else {
                        finishQuiz();
                    }
                }, 1000);
            }
        }, 1000);
    }

    function autoRevealAnswer(question) {
        if (question.classList.contains('locked')) return;
        const options = question.querySelectorAll('.option');
        options.forEach(opt => {
            if (opt.getAttribute('data-correct') === 'Y') {
                opt.classList.add('correct');
            } else {
                opt.classList.add('incorrect');
            }
            opt.style.pointerEvents = 'none';
        });
        question.classList.add('locked');
    }

    function showQuestion(index) {
        questions.forEach((q, i) => {
            q.style.display = i === index ? 'block' : 'none';
        });

        prevBtn.disabled = index === 0;
        nextBtn.textContent = index === questions.length - 1 ? "Submit" : "Next →";
        startTimer(index);
    }

    function lockOptions(question) {
        const options = question.querySelectorAll('.option');
        options.forEach(opt => {
            opt.style.pointerEvents = 'none';
        });
    }

    function finishQuiz() {
        localStorage.setItem("quizScore", correctAnswers);
        localStorage.setItem("quizTotal", totalQuestions);
        localStorage.setItem("quizName", quizTitle);

        window.location.href = "feedback.html"; // ✅ Redirect
    }

    questions.forEach(question => {
        const options = question.querySelectorAll('.option');

        options.forEach(option => {
            option.addEventListener('click', () => {
                if (question.classList.contains('locked')) return;

                options.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');

                const isCorrect = option.getAttribute('data-correct') === 'Y';

                if (isCorrect) {
                    option.classList.add('correct');
                    correctAnswers++;
                } else {
                    option.classList.add('incorrect');
                    options.forEach(opt => {
                        if (opt.getAttribute('data-correct') === 'Y') {
                            opt.classList.add('correct');
                        }
                    });
                }

                question.classList.add('locked');
                lockOptions(question);
                clearInterval(timer);
            });
        });
    });

    nextBtn.addEventListener('click', function (event) {
        event.preventDefault(); // Important: blocks form submission if inside a form

        if (currentQuestion < questions.length - 1) {
            currentQuestion++;
            showQuestion(currentQuestion);
        } else {
            finishQuiz();
        }
    });

    prevBtn.addEventListener('click', function (event) {
        event.preventDefault(); // Just in case it's inside a form
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion(currentQuestion);
        }
    });

    showQuestion(currentQuestion);
});