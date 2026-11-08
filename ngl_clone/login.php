
<?php
session_start();
require_once 'database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$err = "";
$mode = $_GET['mode'] ?? 'login';

if (!in_array($mode, ['login', 'register'], true)) {
    $mode = 'login';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['login'])) {

        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$u, $u]);

        $user = $stmt->fetch();

        if ($user && password_verify($p, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard.php");
            exit();

        } else {
            $err = "Invalid username or password!";
        }
    }

    if (isset($_POST['register'])) {

        $u = trim($_POST['username'] ?? '');
        $e = trim($_POST['email'] ?? '');
        $p = $_POST['password'] ?? '';

        if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $u)) {

            $err = "Username must be 3-30 characters and contain only letters, numbers, or underscores!";

        } elseif (!filter_var($e, FILTER_VALIDATE_EMAIL)) {

            $err = "Please enter a valid email address!";

        } elseif (strlen($p) < 6) {

            $err = "Password must be at least 6 characters!";

        } else {

            $chk = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $chk->execute([$u, $e]);

            if ($chk->fetch()) {

                $err = "Username or email already exists!";

            } else {

                $hash = password_hash($p, PASSWORD_DEFAULT);

                $ins = $pdo->prepare(
                    "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
                );

                $ins->execute([$u, $e, $hash]);

                header("Location: login.php?registered=1");
                exit();
            }
        }

        $mode = 'register';
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $mode === 'register' ? 'Create Account' : 'Login' ?> | JM.NGL</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

* {
    font-family: 'DM Sans', sans-serif;
}

h1, h2, h3 {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

body {
    min-height: 100vh;
    background: #f8f7fc;
    transition: .3s ease;
}

.gradient-text {
    background: linear-gradient(120deg, #ec4899, #9333ea);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.gradient-bg {
    background: linear-gradient(135deg, #f973a5, #a855f7, #6366f1);
}

.auth-card {
    background: white;
    border: 1px solid #f0ebf7;
    box-shadow: 0 25px 80px rgba(48, 25, 80, .10);
    border-radius: 28px;
}

.input-box {
    background: #f8f7fb;
    border: 1.5px solid #eeeaf4;
    transition: .25s ease;
}

.input-box:focus-within {
    background: white;
    border-color: #d946ef;
    box-shadow: 0 0 0 4px rgba(217, 70, 239, .09);
}

.input-box input {
    background: transparent;
    outline: none;
    width: 100%;
    color: #242136;
}

.submit-btn {
    background: linear-gradient(120deg, #ec4899, #9333ea);
    transition: .25s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(217, 70, 239, .25);
}

.decor-circle {
    position: absolute;
    border-radius: 50%;
    filter: blur(2px);
    pointer-events: none;
}

.auth-form {
    animation: fadeIn .45s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(12px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* DARK MODE */

body.dark {
    background: #151320;
    color: #f4f0ff;
}

.dark .auth-card {
    background: #211e30;
    border-color: #332e45;
}

.dark .input-box {
    background: #171525;
    border-color: #39334d;
}

.dark .input-box:focus-within {
    background: #211e30;
}

.dark .input-box input {
    color: white;
}

.dark .text-gray-800 {
    color: #f4f0ff !important;
}

.dark .text-gray-700 {
    color: #ded8ed !important;
}

.dark .text-gray-600 {
    color: #c1b8d4 !important;
}

.dark .text-gray-500 {
    color: #a49bb8 !important;
}

.dark .text-gray-400 {
    color: #9188a7 !important;
}

.dark .bg-red-100 {
    background: #44202c !important;
}

.dark .text-red-600 {
    color: #fca5a5 !important;
}

.dark .bg-green-100 {
    background: #183b30 !important;
}

.dark .text-green-700 {
    color: #86efac !important;
}

.dark .bg-white {
    background: #211e30 !important;
}

@media(max-width: 640px) {
    .auth-card {
        border-radius: 23px;
    }
}

</style>

</head>

<body class="relative overflow-x-hidden">

<!-- BACKGROUND DECORATIONS -->

<div class="decor-circle w-72 h-72 bg-pink-200/50 -top-32 -left-32"></div>
<div class="decor-circle w-96 h-96 bg-purple-200/40 -bottom-40 -right-32"></div>
<div class="decor-circle w-40 h-40 bg-orange-200/40 top-1/2 right-10"></div>


<!-- THEME BUTTON -->

<button
    onclick="toggleTheme()"
    class="fixed top-5 right-5 z-50 w-11 h-11 rounded-full bg-white shadow-lg flex items-center justify-center text-gray-600 hover:text-purple-600 transition"
>
    <i id="themeIcon" class="fa-solid fa-moon"></i>
</button>


<!-- MAIN CONTAINER -->

<main class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">


        <!-- BRAND -->

        <div class="text-center mb-8">

            <div class="gradient-bg w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl mx-auto mb-4 shadow-xl">
                <i class="fa-solid fa-envelope"></i>
            </div>

            <h1 class="text-3xl font-extrabold tracking-tight">
                JM<span class="gradient-text">.NGL</span>
            </h1>

            <p class="text-gray-500 text-sm mt-2">
                Your space for anonymous conversations.
            </p>

        </div>


        <!-- AUTH CARD -->

        <section class="auth-card p-6 sm:p-9">

            <!-- FORM HEADING -->

            <div class="text-center mb-7">

                <div class="inline-flex items-center gap-2 bg-pink-50 text-pink-600 px-4 py-2 rounded-full text-xs font-bold mb-4">
                    <i class="fa-solid fa-sparkles"></i>
                    <?= $mode === 'register' ? 'JOIN THE COMMUNITY' : 'WELCOME BACK' ?>
                </div>

                <h2 class="text-2xl font-extrabold text-gray-800">
                    <?= $mode === 'register' ? 'Create your account' : 'Welcome back!' ?>
                </h2>

                <p class="text-gray-500 text-sm mt-2">
                    <?= $mode === 'register'
                        ? 'Get your own anonymous message link.'
                        : 'Login to discover your anonymous messages.' ?>
                </p>

            </div>


            <!-- ERROR MESSAGE -->

            <?php if ($err): ?>

                <div class="bg-red-100 text-red-600 p-3.5 rounded-xl text-sm font-semibold mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= e($err) ?>
                </div>

            <?php endif; ?>


            <!-- SUCCESS MESSAGE -->

            <?php if (isset($_GET['registered'])): ?>

                <div class="bg-green-100 text-green-700 p-3.5 rounded-xl text-sm font-semibold mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    Account created successfully! Please login.
                </div>

            <?php endif; ?>


            <!-- REGISTER FORM -->

            <?php if ($mode === 'register'): ?>

                <form method="POST" class="space-y-4 auth-form">

                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Username
                        </label>

                        <div class="input-box rounded-xl px-4 py-3.5 flex items-center gap-3">

                            <i class="fa-regular fa-user text-gray-400"></i>

                            <input
                                type="text"
                                name="username"
                                placeholder="Choose a username"
                                minlength="3"
                                maxlength="30"
                                pattern="[a-zA-Z0-9_]+"
                                autocomplete="username"
                                required
                            >

                        </div>

                    </div>


                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Email Address
                        </label>

                        <div class="input-box rounded-xl px-4 py-3.5 flex items-center gap-3">

                            <i class="fa-regular fa-envelope text-gray-400"></i>

                            <input
                                type="email"
                                name="email"
                                placeholder="Enter your email"
                                autocomplete="email"
                                required
                            >

                        </div>

                    </div>


                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Password
                        </label>

                        <div class="input-box rounded-xl px-4 py-3.5 flex items-center gap-3">

                            <i class="fa-solid fa-lock text-gray-400"></i>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Create a password"
                                minlength="6"
                                autocomplete="new-password"
                                required
                            >

                            <button
                                type="button"
                                onclick="togglePassword('password', 'eyeIcon')"
                                class="text-gray-400 hover:text-purple-600"
                            >
                                <i id="eyeIcon" class="fa-regular fa-eye"></i>
                            </button>

                        </div>

                        <p class="text-xs text-gray-400 mt-2">
                            Minimum 6 characters.
                        </p>

                    </div>


                    <button
                        type="submit"
                        name="register"
                        class="submit-btn w-full py-4 rounded-xl text-white font-extrabold text-sm mt-3"
                    >
                        Create Account
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>

                </form>


                <div class="text-center mt-7">

                    <p class="text-sm text-gray-500">
                        Already have an account?

                        <a href="login.php" class="font-extrabold gradient-text hover:opacity-70">
                            Login
                        </a>
                    </p>

                </div>


            <!-- LOGIN FORM -->

            <?php else: ?>

                <form method="POST" class="space-y-4 auth-form">

                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Username or Email
                        </label>

                        <div class="input-box rounded-xl px-4 py-3.5 flex items-center gap-3">

                            <i class="fa-regular fa-user text-gray-400"></i>

                            <input
                                type="text"
                                name="username"
                                placeholder="Enter username or email"
                                autocomplete="username"
                                required
                            >

                        </div>

                    </div>


                    <div>

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Password
                        </label>

                        <div class="input-box rounded-xl px-4 py-3.5 flex items-center gap-3">

                            <i class="fa-solid fa-lock text-gray-400"></i>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                onclick="togglePassword('password', 'eyeIcon')"
                                class="text-gray-400 hover:text-purple-600"
                            >
                                <i id="eyeIcon" class="fa-regular fa-eye"></i>
                            </button>

                        </div>

                    </div>


                    <button
                        type="submit"
                        name="login"
                        class="submit-btn w-full py-4 rounded-xl text-white font-extrabold text-sm mt-4"
                    >
                        Login to Account
                        <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>

                </form>


                <div class="text-center mt-7">

                    <p class="text-sm text-gray-500">
                        Don't have an account?

                        <a href="login.php?mode=register" class="font-extrabold gradient-text hover:opacity-70">
                            Sign Up
                        </a>
                    </p>

                </div>

            <?php endif; ?>


            <!-- DIVIDER -->

            <div class="flex items-center gap-4 my-6">

                <div class="flex-1 h-px bg-gray-100"></div>

                <span class="text-xs text-gray-400 font-semibold">
                    JM.NGL
                </span>

                <div class="flex-1 h-px bg-gray-100"></div>

            </div>


            <!-- BACK HOME -->

            <a
                href="index.php"
                class="block text-center text-sm text-gray-500 hover:text-purple-600 font-semibold transition"
            >
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to Home
            </a>

        </section>


        <!-- FOOTER -->

        <footer class="text-center mt-7 text-xs text-gray-400">

            <p>
                Made for anonymous conversations.
            </p>

            <p class="mt-2">
                © <?= date('Y') ?> JM.NGL. All rights reserved.
            </p>

        </footer>

    </div>

</main>


<!-- JAVASCRIPT -->

<script>

// SHOW / HIDE PASSWORD

function togglePassword(inputId, iconId) {

    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === 'password') {

        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';

    } else {

        input.type = 'password';
        icon.className = 'fa-regular fa-eye';

    }
}


// DARK MODE

function toggleTheme() {

    document.body.classList.toggle('dark');

    const dark = document.body.classList.contains('dark');

    localStorage.setItem('jmngl-theme', dark ? 'dark' : 'light');

    updateThemeIcon();
}


function updateThemeIcon() {

    const icon = document.getElementById('themeIcon');

    if (!icon) return;

    icon.className = document.body.classList.contains('dark')
        ? 'fa-solid fa-sun'
        : 'fa-solid fa-moon';

}


// LOAD SAVED THEME

(function() {

    if (localStorage.getItem('jmngl-theme') === 'dark') {

        document.body.classList.add('dark');

    }

    updateThemeIcon();

})();

</script>

</body>
</html>
