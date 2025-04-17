<?php

session_start();
include 'connectDB.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    $message = "❌ All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "❌ Invalid email format.";
  } elseif (!preg_match('/^(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
    $message = "❌ Password incorrect";
  } else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user['password'])) {
        $_SESSION['user_name'] = $user['name'];
        header("Location: Homepage.php");
        exit();
      } else {
        $message = "❌ Incorrect password.";
      }
    } else {
      $message = "⚠️ No account found with that email.";
    }

    $stmt->close();
  }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    :root {
      --primary: #ff4e50;
      --secondary: #f9d423;
      --text-dark: #222;
      --text-light: #fff;
      --font: 'Poppins', sans-serif;
    }

    body {
      margin: 0;
      font-family: var(--font);
      background: linear-gradient(to right, #ffe259, #ffa751);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    nav {
      background: var(--primary);
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 2rem;
      font-weight: bold;
      color: var(--text-light);
      text-decoration: none;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    nav ul li a {
      color: var(--text-light);
      text-decoration: none;
      font-weight: 500;
    }

    .container {
      max-width: 400px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    h1 {
      text-align: center;
      color: var(--primary);
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 6px;
      font-weight: 500;
    }

    input {
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    button {
      padding: 12px;
      font-size: 1rem;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #e03e45;
    }

    .footer {
      text-align: center;
      margin-top: auto;
      padding: 20px;
      background: var(--primary);
      color: white;
    }

    .switch-link {
      text-align: center;
      margin-top: 10px;
      font-size: 0.9rem;
    }

    .switch-link a {
      color: var(--primary);
      text-decoration: none;
      font-weight: bold;
    }

    .message {
      text-align: center;
      color: red;
      font-weight: bold;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <nav>
    <a class="logo" href="homepage.html">Quizzlet</a>
    <ul>
      <li><a href="signup.php">Sign Up</a></li>
    </ul>
  </nav>

  <div class="container">
    <h1>Login</h1>
    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
    <form method="POST" onsubmit="return validateLoginForm()">
      <label>Email</label>
      <input type="email" name="email" id="email" required>

      <label>Password</label>
      <input type="password" name="password" id="password" required>
      <div id="password-error" class="message"></div> <!-- Placeholder for the error message -->

      <button type="submit">Login</button>
    </form>
    <div class="switch-link">
      Don't have an account? <a href="signup.php">Register</a>
    </div>
  </div>

  <div class="footer">
    &copy; <?php echo date("Y"); ?> Quizzlet. All rights reserved.
  </div>

  <script>
    function validateLoginForm() {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;
      const passwordError = document.getElementById('password-error'); // Get the error message container

      const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
      const passwordRegex = /^(?=.*[0-9])(?=.*[\W_]).{8,}$/;

      // Clear any previous error messages
      passwordError.innerHTML = '';

      // Validate email
      if (!emailPattern.test(email)) {
        alert("Please enter a valid email.");
        return false;
      }

      // Validate password
      if (!passwordRegex.test(password)) {
        passwordError.innerHTML = "enter a valid password";
        return false;
      }

      return true;
    }
  </script>
</body>
</html>