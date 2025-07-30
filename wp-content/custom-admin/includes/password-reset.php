<?php
require_once('../wp-load.php');

// Redirect if logged in
if (is_user_logged_in()) {
    wp_redirect('dashboard.php');
    exit;
}

// Handle password reset request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_email($_POST['email']);
    $user = get_user_by('email', $email);
    
    if ($user) {
        // Generate reset key
        $key = get_password_reset_key($user);
        
        // Create reset link
        $reset_link = site_url('admin-portal/reset-password.php?key='.$key.'&login='.$user->user_login);
        
        // Send email
        $subject = 'Password Reset Request';
        $message = "Someone requested a password reset for your account.\n\n";
        $message .= "If this was a mistake, ignore this email.\n\n";
        $message .= "To reset your password, visit: ".$reset_link;
        
        wp_mail($email, $subject, $message);
        
        $message = "Password reset link sent to your email";
    } else {
        $error = "No user found with that email address";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
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
        <?php 
        if (isset($error)) echo '<div class="error">'.$error.'</div>';
        if (isset($message)) echo '<div class="success">'.$message.'</div>';
        ?>
        <form method="POST">
            <h2>Reset Password</h2>
            <input type="email" name="email" placeholder="Your Email" required>
            <button type="submit">Request Reset Link</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>