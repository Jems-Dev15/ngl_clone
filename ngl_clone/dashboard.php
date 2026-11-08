
<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_GET['del'])) {
    $id = intval($_GET['del']);

    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);

    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['update_prompt'])) {
    $q = trim($_POST['prompt_question']);

    $stmt = $pdo->prepare("UPDATE users SET prompt_question = ? WHERE id = ?");
    $stmt->execute([$q, $user_id]);

    header("Location: dashboard.php?saved=1");
    exit();
}

if (isset($_GET['open'])) {
    $id = intval($_GET['open']);

    $stmt = $pdo->prepare("UPDATE messages SET is_opened = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);

    header("Location: dashboard.php");
    exit();
}

$stmt = $pdo->prepare("SELECT prompt_question FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$me = $stmt->fetch();

$msgs = $pdo->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at DESC");
$msgs->execute([$user_id]);

$all = $msgs->fetchAll();

$total = count($all);
$unread = count(array_filter($all, fn($m) => $m['is_opened'] == 0));
$read = $total - $unread;

$link = "https://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/profile.php?u=" . urlencode($username);

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard | JM.NGL</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
* {
    font-family: 'DM Sans', sans-serif;
}

body {
    background: #f8f7fc;
    color: #242136;
    transition: .3s;
}

h1, h2, h3 {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, #f973a5, #a855f7, #6366f1);
}

.card {
    background: white;
    border: 1px solid #eeeaf5;
    border-radius: 22px;
    box-shadow: 0 8px 30px rgba(40, 25, 70, .04);
    transition: .25s ease;
}

.card:hover {
    box-shadow: 0 12px 35px rgba(40, 25, 70, .08);
}

.stat-card {
    position: relative;
    overflow: hidden;
}

.stat-card::after {
    content: '';
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    right: -35px;
    bottom: -45px;
    background: currentColor;
    opacity: .06;
}

.input-field {
    background: #f8f7fb;
    border: 1px solid #ece8f4;
    transition: .2s;
}

.input-field:focus {
    border-color: #d946ef;
    background: white;
    outline: none;
    box-shadow: 0 0 0 3px rgba(217, 70, 239, .1);
}

.primary-btn {
    background: linear-gradient(135deg, #ec4899, #9333ea);
    transition: .2s;
}

.primary-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(217, 70, 239, .25);
}

.message-card {
    transition: .25s ease;
    animation: fadeUp .4s ease both;
}

.message-card:hover {
    transform: translateY(-3px);
}

.unread-dot {
    width: 9px;
    height: 9px;
    background: #ec4899;
    border-radius: 50%;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(10px);
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

.dark .card {
    background: #211e30;
    border-color: #332e45;
}

.dark .input-field {
    background: #171525;
    color: white;
    border-color: #39334d;
}

.dark .input-field:focus {
    background: #211e30;
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

.dark .bg-gray-50 {
    background: #171525 !important;
}

.dark .bg-gray-100 {
    background: #302a42 !important;
}

.dark .border-gray-100 {
    border-color: #39334d !important;
}

.dark .border-gray-200 {
    border-color: #39334d !important;
}

.dark .bg-white {
    background: #211e30 !important;
}

.dark .text-black {
    color: white !important;
}

@media(max-width: 640px) {
    .card {
        border-radius: 18px;
    }
}
</style>
</head>

<body>

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-10">

    <!-- HEADER -->

    <header class="flex items-center justify-between mb-8">

        <div class="flex items-center gap-3">

            <div class="w-12 h-12 gradient-bg rounded-2xl flex items-center justify-center text-white shadow-lg">
                <i class="fa-solid fa-envelope text-xl"></i>
            </div>

            <div>
                <h1 class="text-xl font-extrabold tracking-tight">JM.NGL</h1>
                <p class="text-xs text-gray-500">Your anonymous inbox</p>
            </div>

        </div>

        <div class="flex items-center gap-2">

            <button onclick="toggleTheme()" class="w-10 h-10 rounded-full card flex items-center justify-center text-gray-600 hover:text-purple-500">
                <i id="themeIcon" class="fa-solid fa-moon"></i>
            </button>

            <a href="?logout=1" class="px-4 py-2.5 rounded-full bg-red-50 text-red-500 text-sm font-bold hover:bg-red-100 transition">
                <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i>
                <span class="hidden sm:inline">Logout</span>
            </a>

        </div>

    </header>


    <!-- WELCOME BANNER -->

    <section class="gradient-bg rounded-3xl p-6 sm:p-8 text-white mb-7 relative overflow-hidden">

        <div class="absolute w-64 h-64 bg-white/10 rounded-full -right-20 -top-28"></div>
        <div class="absolute w-40 h-40 bg-white/10 rounded-full -right-5 -bottom-28"></div>

        <div class="relative z-10">

            <div class="flex items-center gap-2 text-white/80 text-sm mb-3">
                <i class="fa-solid fa-circle-check"></i>
                <span>Your inbox is ready</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold mb-2">
                Hey, @<?= e($username) ?>!
            </h2>

            <p class="text-white/85 text-sm sm:text-base">
                See what people are saying about you anonymously.
            </p>

        </div>

    </section>


    <!-- NOTIFICATION -->

    <?php if (isset($_GET['saved'])): ?>

    <div class="bg-green-100 text-green-700 rounded-2xl p-4 mb-6 flex items-center gap-3 text-sm font-bold">
        <i class="fa-solid fa-circle-check text-lg"></i>
        Your anonymous question has been updated successfully!
    </div>

    <?php endif; ?>


    <!-- STATISTICS -->

    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-7">

        <div class="card stat-card p-5 text-purple-600">
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-semibold text-gray-500">Total Messages</span>
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-envelope"></i>
                </div>
            </div>

            <h3 class="text-3xl font-extrabold text-gray-800"><?= $total ?></h3>
            <p class="text-xs text-gray-400 mt-1">All received messages</p>
        </div>

        <div class="card stat-card p-5 text-pink-600">
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-semibold text-gray-500">Unread</span>
                <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
            </div>

            <h3 class="text-3xl font-extrabold text-gray-800"><?= $unread ?></h3>
            <p class="text-xs text-gray-400 mt-1">Waiting to be opened</p>
        </div>

        <div class="card stat-card p-5 text-indigo-600">
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-semibold text-gray-500">Read</span>
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-check-double"></i>
                </div>
            </div>

            <h3 class="text-3xl font-extrabold text-gray-800"><?= $read ?></h3>
            <p class="text-xs text-gray-400 mt-1">Messages you've read</p>
        </div>

    </section>


    <!-- SHARE LINK -->

    <section class="card p-5 sm:p-7 mb-7">

        <div class="flex items-center gap-3 mb-5">

            <div class="w-11 h-11 gradient-bg rounded-xl flex items-center justify-center text-white">
                <i class="fa-solid fa-link"></i>
            </div>

            <div>
                <h2 class="font-extrabold text-lg">Your anonymous link</h2>
                <p class="text-xs text-gray-500">Share it and receive anonymous messages.</p>
            </div>

        </div>

        <div class="flex flex-col sm:flex-row gap-2">

            <input
                id="linkInput"
                readonly
                value="<?= e($link) ?>"
                class="input-field rounded-xl px-4 py-3 text-sm flex-1 min-w-0"
            >

            <button
                onclick="copyLink()"
                id="copyBtn"
                class="primary-btn text-white rounded-xl px-6 py-3 font-bold text-sm"
            >
                <i class="fa-regular fa-copy mr-2"></i>
                Copy Link
            </button>

        </div>

        <div class="border-t border-gray-100 mt-6 pt-5">

            <form method="POST">

                <label class="block text-sm font-bold mb-2">
                    <i class="fa-solid fa-comment-dots text-pink-500 mr-1"></i>
                    Customize your question
                </label>

                <div class="flex flex-col sm:flex-row gap-2">

                    <input
                        type="text"
                        name="prompt_question"
                        maxlength="255"
                        value="<?= e($me['prompt_question'] ?? '') ?>"
                        placeholder="Send me anonymous messages..."
                        class="input-field flex-1 min-w-0 rounded-xl px-4 py-3 text-sm"
                    >

                    <button
                        type="submit"
                        name="update_prompt"
                        class="bg-gray-900 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-purple-700 transition"
                    >
                        Save Prompt
                        <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>

                </div>

            </form>

        </div>

        <a
            href="profile.php?u=<?= urlencode($username) ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="block text-center mt-5 text-sm text-purple-600 font-bold hover:text-pink-500 transition"
        >
            Preview My Profile
            <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
        </a>

    </section>


    <!-- MESSAGES -->

    <section class="mb-6">

        <div class="flex justify-between items-center mb-5">

            <div>
                <h2 class="text-xl font-extrabold">Your Messages</h2>
                <p class="text-sm text-gray-500 mt-1">Your latest anonymous messages</p>
            </div>

            <span class="bg-purple-100 text-purple-600 px-4 py-2 rounded-full text-xs font-bold">
                <?= $total ?> Messages
            </span>

        </div>


        <?php if (empty($all)): ?>

            <div class="card p-10 text-center">

                <div class="w-20 h-20 gradient-bg rounded-3xl flex items-center justify-center mx-auto mb-5 text-white text-3xl">
                    <i class="fa-regular fa-envelope"></i>
                </div>

                <h3 class="font-extrabold text-xl mb-2">No messages yet!</h3>

                <p class="text-gray-500 text-sm max-w-xs mx-auto">
                    Your inbox is waiting. Share your anonymous link with friends and see what they have to say.
                </p>

                <button onclick="copyLink()" class="primary-btn text-white px-6 py-3 rounded-xl font-bold text-sm mt-6">
                    <i class="fa-solid fa-share-nodes mr-2"></i>
                    Share My Link
                </button>

            </div>

        <?php else: ?>

            <div class="space-y-4">

                <?php foreach ($all as $m): ?>

                    <article class="card message-card p-5 sm:p-6 <?= $m['is_opened'] ? 'opacity-75' : '' ?>">

                        <div class="flex items-start justify-between gap-3">

                            <div class="flex items-center gap-3">

                                <div class="w-11 h-11 rounded-2xl flex items-center justify-center <?= $m['is_opened'] ? 'bg-gray-100 text-gray-500' : 'bg-pink-100 text-pink-600' ?>">
                                    <i class="fa-solid fa-user-secret text-lg"></i>
                                </div>

                                <div>

                                    <div class="flex items-center gap-2">

                                        <h3 class="font-extrabold text-sm">
                                            Anonymous
                                        </h3>

                                        <?php if (!$m['is_opened']): ?>
                                            <span class="unread-dot"></span>
                                            <span class="text-pink-500 text-xs font-bold">NEW</span>
                                        <?php endif; ?>

                                    </div>

                                    <p class="text-xs text-gray-400 mt-1">
                                        <?= e($m['device_hint'] ?? 'Unknown device') ?>
                                        ·
                                        <?= date("M d, Y • h:i A", strtotime($m['created_at'])) ?>
                                    </p>

                                </div>

                            </div>

                            <a
                                href="?del=<?= (int)$m['id'] ?>"
                                onclick="return confirm('Are you sure you want to delete this message?')"
                                class="text-gray-400 hover:text-red-500 transition text-sm"
                                title="Delete message"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                            </a>

                        </div>


                        <div class="bg-gray-50 rounded-2xl p-4 mt-5">

                            <p class="text-gray-800 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                                <?= e($m['message_text']) ?>
                            </p>

                        </div>


                        <div class="flex items-center justify-between mt-4">

                            <span class="text-xs font-semibold <?= $m['is_opened'] ? 'text-gray-400' : 'text-pink-500' ?>">
                                <i class="fa-solid <?= $m['is_opened'] ? 'fa-check-double' : 'fa-envelope' ?> mr-1"></i>

                                <?= $m['is_opened'] ? 'Read' : 'Unread message' ?>
                            </span>

                            <?php if (!$m['is_opened']): ?>

                                <a
                                    href="?open=<?= (int)$m['id'] ?>"
                                    class="bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition"
                                >
                                    <i class="fa-solid fa-check mr-1"></i>
                                    Mark as Read
                                </a>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>


    <!-- FOOTER -->

    <footer class="text-center py-8 text-xs text-gray-400">

        <div class="flex justify-center items-center gap-2 mb-2">
            <i class="fa-solid fa-heart text-pink-400"></i>
            <span class="font-bold">JM.NGL</span>
        </div>

        <p>Made for anonymous conversations.</p>

        <p class="mt-1">© <?= date('Y') ?> All rights reserved.</p>

    </footer>

</div>


<script>

// COPY LINK

function copyLink() {

    const input = document.getElementById('linkInput');
    const button = document.getElementById('copyBtn');

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(input.value).then(() => {
            showCopied(button);
        }).catch(() => {
            fallbackCopy(input, button);
        });
    } else {
        fallbackCopy(input, button);
    }
}

function fallbackCopy(input, button) {
    input.select();
    input.setSelectionRange(0, 99999);

    try {
        document.execCommand('copy');
        showCopied(button);
    } catch (error) {
        alert('Unable to copy link. Please copy it manually.');
    }
}

function showCopied(button) {
    if (!button) return;

    const original = button.innerHTML;

    button.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Copied!';
    
    setTimeout(() => {
        button.innerHTML = original;
    }, 2000);
}


// DARK MODE

function toggleTheme() {

    document.body.classList.toggle('dark');

    const isDark = document.body.classList.contains('dark');

    localStorage.setItem('jmngl-theme', isDark ? 'dark' : 'light');

    updateThemeIcon();
}

function updateThemeIcon() {

    const icon = document.getElementById('themeIcon');

    if (!icon) return;

    icon.className = document.body.classList.contains('dark')
        ? 'fa-solid fa-sun'
        : 'fa-solid fa-moon';
}

(function() {

    const savedTheme = localStorage.getItem('jmngl-theme');

    if (savedTheme === 'dark') {
        document.body.classList.add('dark');
    }

    updateThemeIcon();

})();

</script>

</body>
</html>
