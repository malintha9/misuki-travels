<?php
// Initialize WordPress
require_once(__DIR__ . '/../../../wp-load.php');

// Redirect if already logged in
if (is_user_logged_in()) {
    $user = wp_get_current_user();
    $allowed = get_user_meta($user->ID, 'can_access_admin_portal', true) 
             || in_array('tour_manager', $user->roles);

    if ($allowed) {
        wp_redirect('dashboard.php');
        exit;
    }
}
// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $creds = array(
        'user_login'    => sanitize_user($_POST['username']),
        'user_password' => $_POST['password'],
        'remember'      => true
    );
    
    $user = wp_signon($creds, false);
    
 if (!is_wp_error($user)) {
        $allowed = get_user_meta($user->ID, 'can_access_admin_portal', true) 
                 || in_array('tour_manager', $user->roles);
        
        if ($allowed) {
            wp_redirect('dashboard.php');
            exit;
        } else {
            wp_logout();
            $error = "Access restricted to authorized users only";
        }
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Portal Login</title>
    <style>
     * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg,rgb(235, 242, 248),rgb(238, 241, 243));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #fd7e14;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-box button {
            width: 100%;
            background-color: #fd7e14;
            color: black;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-box button:hover {
            background-color:#fd7e14;
        }

        .error-message {
            color: red;
            margin-bottom: 16px;
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Portal Login</h2>
        <?php if (!empty($error)) : ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="footer">© <?php echo date('Y'); ?> Misuki Travel Portal</div>
    </div>
</body>
</html>