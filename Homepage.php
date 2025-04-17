<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quizzlet | Home</title>
<!-- these are for the main color palette and font family for the whole platform -->
<style>
:root {
--primary: #ff4e50;
--secondary: #f9d423;
--highlight: #ff6f61;
--text-dark: #222;
--text-light: #fff;
--font: 'Poppins', sans-serif;}
/* Resetting default browser styles + setting the main font */
* {
box-sizing: border-box;
margin: 0;
padding: 0;
font-family: var(--font);}
body {
background: linear-gradient(135deg, #ffe259, #ffa751);
color: var(--text-dark);
overflow-x: hidden;} /*no horizontal chaos allowed*/
nav {
display: flex;
justify-content: space-between;
align-items: center;
padding: 20px 40px;
background: var(--primary);
flex-wrap: wrap;}
.logo {
font-size: 2rem;
color: var(--text-light);
font-weight: bold;}
nav ul {
display: flex;
gap: 25px;
list-style: none;}
nav ul li a {
color: var(--text-light);
text-decoration: none;
font-weight: 500;
transition: color 0.3s;}
nav ul li a:hover {
color: var(--secondary);}
.auth-buttons button {
background: var(--highlight);
color: white;
border: none;
padding: 8px 16px;
margin-left: 10px;
border-radius: 20px;
cursor: pointer;}
.hero {
text-align: center;
padding: 80px 20px 50px;
background: linear-gradient(to right, #f9d423, #ff4e50);}
.hero h1 {
font-size: 3rem;
color: #fff;}
.hero p {
font-size: 1.2rem;
color: #fff9f4;
max-width: 600px;
margin: auto;}
.hero .cta button {
margin-top: 25px;
padding: 14px 36px;
font-size: 1rem;
background: #fff;
color: var(--primary);
border-radius: 30px;
border: none;
cursor: pointer;}
.section-title {
text-align: center;
font-size: 2rem;
margin: 60px 0 20px;
color: var(--primary);}
.featured-quizzes {
display: flex;
justify-content: center;
gap: 25px;
flex-wrap: wrap;
padding: 0 40px;}
.quiz-card {
background: white;
border-radius: 20px;
padding: 25px;
width: 220px;
text-align: center;
box-shadow: 0 4px 12px rgba(0,0,0,0.1);
transition: 0.3s;}
.quiz-card:hover {
transform: scale(1.05);}
.quiz-card span {
font-size: 2.2rem;
display: block;
margin-bottom: 10px;}
.quiz-meta {
font-size: 0.9rem;
color: #666;
margin-top: 8px;}
.quiz-meta .level {
background: #ffe082;
padding: 2px 6px;
border-radius: 10px;
font-size: 0.75rem;
margin-left: 5px;}
.quiz-card button {
margin-top: 10px;
background: var(--highlight);
color: white;
border: none;
padding: 8px 12px;
border-radius: 10px;
cursor: pointer;}
.why-section {
display: flex;
justify-content: center;
gap: 40px;
padding: 40px;
flex-wrap: wrap;}
.why-box {
background: #fff;
border-radius: 15px;
padding: 30px;
flex: 1;
min-width: 250px;
max-width: 300px;
box-shadow: 0 4px 10px rgba(0,0,0,0.1);
text-align: center;}
footer {
text-align: center;
padding: 40px 20px;
background: var(--primary);
color: white;}

.welcome-message {
  font-weight: bold;
  color: white;
  font-size: 1.2rem;
  margin-right: 15px;
}
</style>
</head>
<body>

<nav>
  <div class="logo">Quizzlet</div>
  <ul>
    <li><a href="#featured">Featured</a></li>
    <li><a href="DisplayQuizzes.html">Browse Quizzes</a></li>
    <li><a href="about.html">About</a></li>
  </ul>
  <div class="auth-buttons">
    <!-- Add class 'welcome-message' to apply styling -->
    <span class="welcome-message">Welcome, <?= $_SESSION['user_name']; ?>!</span>
    <a href="logout.php"><button>Logout</button></a>
  </div>
</nav>

<section class="hero">
  <h1>Make Learning Fun Again!</h1>
  <p>Make studying exciting with playful quizzes in every subject.</p>
  <div class="cta">
    <a href="DisplayQuizzes.html"><button>Start Playing</button></a>
  </div>
</section>

<h2 class="section-title" id="featured">Featured Quizzes</h2>
<div class="featured-quizzes">
  <div class="quiz-card">
    <span>🔥</span>
    Algebra Essentials
    <div class="quiz-meta">12 Questions <span class="level">Beginner</span></div>
    <button class="nav-button" onclick="window.location.href='fetchQuiz.php?quiz_id=7'">Start</button>
  </div>
  <div class="quiz-card">
    <span>🚀</span>
    Space Science
    <div class="quiz-meta">14 Questions <span class="level">Intermediate</span></div>
    <button class="nav-button" onclick="window.location.href='fetchQuiz.php?quiz_id=8'">Start</button>
  </div>
  <div class="quiz-card">
    <span>🎤</span>
    Pop Music Legends
    <div class="quiz-meta">10 Questions <span class="level">Fun</span></div>
    <button class="nav-button" onclick="window.location.href='fetchQuiz.php?quiz_id=9'">Start</button>
  </div>
</div>

<h2 class="section-title">Why Choose Quizzlet?</h2>
<div class="why-section">
  <div class="why-box">✨ Real-time Feedback</div>
  <div class="why-box">🎨 Fun & Modern UI</div>
  <div class="why-box">🛠️ Custom Quiz Builder</div>
</div>

<footer>
  Let’s make learning playful & powerful 💡
</footer>

</body>
</html>