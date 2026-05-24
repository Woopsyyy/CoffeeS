<?php
/**
 * Cafe Espresso - Authentication Login Page
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Handle logout action
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    logoutUser();
    header("Location: " . BASE_URL . "/pages/login.php");
    exit;
}

// Redirect already logged-in users
if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: " . BASE_URL . "/admin/dashboard.php");
    } else {
        header("Location: " . BASE_URL . "/pages/home.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyPostCsrf();
    
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please fill out all required credentials.';
    } else {
        $login = loginUser($username, $password);
        if ($login) {
            setFlashMessage('success', "Welcome back, {$login['username']}!");
            
            // Redirect based on role
            if ($login['role'] === 'admin') {
                header("Location: " . BASE_URL . "/admin/dashboard.php");
            } else {
                header("Location: " . BASE_URL . "/pages/menu.php");
            }
            exit;
        } else {
            $error = 'Invalid credentials. Please verify your login details.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Espresso - Sign In</title>
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/utilities.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/pages.css">
</head>
<body class="app-wrapper">

    <!-- Reusable Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="auth-split-grid">
        <!-- Left Side: Form Credentials fields -->
        <div class="auth-form-side">
            <div class="auth-form-card">
                <span class="section-pretitle">Welcome Back</span>
                <h1 class="color-coffee font-semibold mb-3">Sign In to Cafe</h1>
                
                <?php if (!empty($error)): ?>
                    <div class="badge badge-danger w-full p-2 mb-3" style="font-size:0.85rem; border-radius:var(--border-radius-md); justify-content:center;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <?php csrfField(); ?>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="login-username" class="form-label">Username or Email Address:</label>
                        <input type="text" id="login-username" name="username" class="form-control" placeholder="Enter your credentials..." required>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="login-password" class="form-label">Account Password:</label>
                        <input type="password" id="login-password" name="password" class="form-control" placeholder="Enter password..." required>
                    </div>

                    <button type="submit" class="btn btn-secondary btn-block shadow-sm">Sign In</button>
                </form>

                <p class="color-muted text-sm mt-3 font-light">
                    Forgot your credentials? Reach out to support. Or <a href="<?php echo BASE_URL; ?>/pages/register.php" class="font-semibold color-gold">Create an Account</a>
                </p>
            </div>
        </div>

        <!-- Right Side: Graphic Visual Welcomer -->
        <div class="auth-welcome-side">
            <div class="auth-welcome-content">
                <i class="fa-solid fa-mug-hot" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 20px;"></i>
                <h3>Freshly Roasted Cafe Moments</h3>
                <p>
                    Log in to synchronize your favorites list, review active roastery shipping logs, earn bean loyalty tokens, and save custom options configurations.
                </p>
                <a href="<?php echo BASE_URL; ?>/pages/register.php" class="btn btn-outline" style="border-color:var(--cream-light); color:var(--cream-light);">Create Account</a>
            </div>
            
            <!-- Absolute decoration overlays -->
            <div class="hero-frame-accent" style="border-color: rgba(253,251,247,0.15); width: 220px; height: 220px; top: -50px; right: -50px;"></div>
            <div class="hero-frame-accent" style="border-color: rgba(253,251,247,0.1); width: 140px; height: 140px; bottom: -30px; left: -30px;"></div>
        </div>
    </main>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
