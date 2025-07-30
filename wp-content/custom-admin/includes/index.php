<?php
require_once('../../wp-load.php'); // Connect to WordPress

// Redirect if already logged in
if (is_user_logged_in() && current_user_can('administrator')) {
    wp_redirect('dashboard.php');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $creds = array(
        'user_login'    => sanitize_user($_POST['username']),
        'user_password' => $_POST['password'],
        'remember'      => true
    );
    
    $user = wp_signon($creds, false);
    
    if (!is_wp_error($user) && current_user_can('administrator')) {
        wp_redirect('dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials or insufficient privileges';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Portal Login</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="login-container">
        <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
        <form method="POST">
            <h2>Admin Portal Login</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>