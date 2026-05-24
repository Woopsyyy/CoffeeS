<?php
/**
 * Cafe Espresso - Authentication Registration Page
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

// Redirect logged-in users
if (isLoggedIn()) {
    header("Location: " . BASE_URL . "/pages/home.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyPostCsrf();
    
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    // Validations
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = 'Please fill out all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Your password must be at least 6 characters long.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $register = registerUser($username, $email, $password);
        if ($register === true) {
            setFlashMessage('success', 'Registration completed successfully! Please sign in.');
            header("Location: " . BASE_URL . "/pages/login.php");
            exit;
        } else {
            $error = $register;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Espresso - Join Us</title>
    
    <!-- CSS Dependencies -->
    <link class="stylesheet" rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/base.css">
    <link class="stylesheet" rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/utilities.css">
    <link class="stylesheet" rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/layout.css">
    <link class="stylesheet" rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components.css">
    <link class="stylesheet" rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/pages.css">
</head>
<body class="app-wrapper">

    <!-- Reusable Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="auth-split-grid" style="grid-template-columns: 1fr 1fr;">
        <!-- Left Side: Graphic Visual Welcomer -->
        <div class="auth-welcome-side" style="background: linear-gradient(135deg, var(--coffee-dark) 0%, var(--primary-dark) 100%);">
            <div class="auth-welcome-content">
                <i class="fa-solid fa-seedling" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 20px;"></i>
                <h3>Join Our Coffee Community</h3>
                <p>
                    Create an account to gain exclusive access to micro-lot releases, earn reward tokens, receive seasonal roastery updates, and manage your shipping details.
                </p>
                <a href="<?php echo BASE_URL; ?>/pages/login.php" class="btn btn-outline" style="border-color:var(--cream-light); color:var(--cream-light);">Sign In Instead</a>
            </div>
            
            <!-- Absolute decoration overlays -->
            <div class="hero-frame-accent" style="border-color: rgba(253,251,247,0.15); width: 220px; height: 220px; bottom: -50px; right: -50px;"></div>
        </div>

        <!-- Right Side: Form Credentials fields -->
        <div class="auth-form-side">
            <div class="auth-form-card">
                <span class="section-pretitle">Patron Signup</span>
                <h1 class="color-coffee font-semibold mb-3">Join Cafe Espresso</h1>
                
                <?php if (!empty($error)): ?>
                    <div class="badge badge-danger w-full p-2 mb-3" style="font-size:0.85rem; border-radius:var(--border-radius-md); justify-content:center;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <?php csrfField(); ?>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="reg-username" class="form-label">Username:</label>
                        <input type="text" id="reg-username" name="username" class="form-control" placeholder="Select username..." required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="reg-email" class="form-label">Email Address:</label>
                        <input type="email" id="reg-email" name="email" class="form-control" placeholder="e.g. hello@domain.com" required>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="reg-password" class="form-label">Password (Min 6 chars):</label>
                        <input type="password" id="reg-password" name="password" class="form-control" placeholder="Enter secure password..." required>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="reg-confirm" class="form-label">Confirm Password:</label>
                        <input type="password" id="reg-confirm" name="confirm_password" class="form-control" placeholder="Re-enter password..." required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block shadow-sm shadow-glow">Create Account</button>
                </form>

                <p class="color-muted text-sm mt-3 font-light">
                    Already a registered coffee patron? <a href="<?php echo BASE_URL; ?>/pages/login.php" class="font-semibold color-gold">Sign In</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Reusable Footer -->
    <?php include __DIR__ . '/../components/footer.php'; ?>

</body>
</html>
