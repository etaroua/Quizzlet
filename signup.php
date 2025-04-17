<?php
include 'connectDB.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate server-side
    if (empty($name) || empty($email) || empty($password)) {
        $message = "❌ All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Invalid email format.";
    } elseif (!preg_match('/^(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
        $message = "❌ Password must be at least 8 characters long and include a number and a special character.";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "⚠️ Email already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $passwordHash);
            if ($stmt->execute()) {
                $message = "✅ Registration successful. <a href='login.php'>Login here</a>";
            } else {
                $message = "❌ Error: " . $stmt->error;
            }
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
  <title>Sign Up</title>
 
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
    <a class="logo" href="#">Quizzlet</a>
    <ul>
      <li><a href="login.php">Login</a></li>
    </ul>
  </nav>

  <div class="container">
    <h1>Register</h1>
    <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
    <form method="POST" onsubmit="return validateSignUpForm()">
      <label>Name</label>
      <input type="text" name="name" required>

      <label>Email</label>
      <input type="email" name="email" id="signup-email" required>

      <label>Password</label>
      <input type="password" name="password" id="signup-password" required>

      <button type="submit">Sign Up</button>
    </form>
    <div class="switch-link">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </div>

  <div class="footer">
    &copy; <?php echo date("Y"); ?> Quizzlet. All rights reserved.
  </div>

  <script>
    function validateSignUpForm() {
      var email = document.getElementById('signup-email').value.trim();
      var password = document.getElementById('signup-password').value;
      var emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
      var passwordRegex = /^(?=.*[0-9])(?=.*[\W_]).{8,}$/;

      if (!emailPattern.test(email)) {
        alert("Please enter a valid email.");
        return false;
      }

      if (!passwordRegex.test(password)) {
        alert("Password must be at least 8 characters long and include a number and a special character.");
        return false;
      }

      return true;
    }
  </script>
</body>
</html>
