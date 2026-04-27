<?php
session_start();
require_once('../class/database.php');

// 1. Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: ../admins/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        try {
            $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // This is the critical check
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role']; 
                    
                    if ($_SESSION['role'] === 'admin') {
                        header("Location: ../admins/dashboard.php");
                    } else {
                        header("Location: ../index.php");
                    }
                    exit();
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "Username not found.";
            }
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login - WMSU Faculty Union</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Montserrat', sans-serif; background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; }
    .login-card { background: white; padding: 40px; width: 100%; max-width: 400px; border-top: 8px solid #8c1d1d; box-shadow: 0 15px 35px rgba(0,0,0,0.1); border-radius: 8px; }
    .login-header h2 { font-family: 'Playfair Display', serif; color: #8c1d1d; text-align: center; font-weight: 700; }
    .btn-login { background-color: #8c1d1d; color: white; font-weight: 600; text-transform: uppercase; border: none; padding: 12px; transition: 0.3s; }
    .btn-login:hover { background-color: #d4af37; color: black; }
    .alert-custom { background-color: #fdf2f2; color: #8c1d1d; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 20px; border-radius: 4px; text-align: center; }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-header">
      <h2>WMSU-FU</h2>
      <p class="text-center text-muted">Faculty Union Portal</p>
    </div>

    <?php if ($error): ?>
      <div class="alert-custom"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label class="small font-weight-bold">Username</label>
        <input type="text" name="username" class="form-control" required autofocus>
      </div>
      <div class="form-group">
        <label class="small font-weight-bold">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-login btn-block mt-4">Sign In</button>
    </form>
    <div class="text-center mt-3">
        <a href="../index.php" class="text-muted small">&larr; Return to Website</a>
    </div>
  </div>
</body>
</html>