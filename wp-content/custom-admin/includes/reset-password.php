<?php
require_once('../wp-load.php');

// Check for reset key
if (!isset($_GET['key']) || !isset($_GET['login'])) {
    wp_redirect('login.php');
    exit;
}

$user = check_password_reset_key($_GET['key'], $_GET['login']);

if (is_wp_error($user)) {
    wp_redirect('login.php?message=invalid_key');
    exit;
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] !== $_POST['password_confirm']) {
        $error = "Passwords don't match";
    } else {
        reset_password($user, $_POST['password']);
        wp_redirect('login.php?message=password_reset');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Set New Password</title>
  <style>
        body { font-family: Arial, sans-serif; background: #f1f1f1; }
        .login-container { max-width: 400px; margin: 100px auto; padding: 20px; background: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .error { color: red; margin-bottom: 15px; }
        input { width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box; }
        button { background: #2271b1; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #135e96; }
    </style>
</head>
<body>
    <div class="login-container">
        <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
        <form method="POST">
            <h2>Set New Password</h2>
            <input type="password" name="password" placeholder="New Password" required>
            <input type="password" name="password_confirm" placeholder="Confirm Password" required>
            <button type="submit">Update Password</button>
        </form>
    </div>
</body>
</html>