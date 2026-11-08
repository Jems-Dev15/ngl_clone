
<?php
require_once 'database.php';

if (!isset($_GET['u']) || empty($_GET['u'])) {
    die("User profile not found.");
}

$username = trim($_GET['u']);

$stmt = $pdo->prepare('SELECT id, username, prompt_question FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    die("This NGL link does not exist.");
}

$success = isset($_GET['sent']) && $_GET['sent'] == 1;

$prompt = !empty($user['prompt_question'])
    ? $user['prompt_question']
    : "Send me something anonymously...";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>JM.NGL | @<?= htmlspecialchars($user['username']) ?></title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

/* =========================
   GLOBAL
========================= */

:root {
    --bg: #15121F;
    --card: #211D30;
    --input: #191625;

    --pink: #E94BAA;
    --purple: #9238E6;

    --text: #F8F2FA;
    --muted: #B9A9DC;

    --border: #393047;

    --gradient: linear-gradient(120deg, #E94BAA, #9238E6);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'DM Sans', sans-serif;
}

body {
    min-height: 100vh;
    background: var(--bg);
    color: var(--text);
    overflow-x: hidden;
    transition: .4s;
}

h1, h2, h3 {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

button, a {
    -webkit-tap-highlight-color: transparent;
}

::selection {
    background: var(--pink);
    color: white;
}


/* =========================
   LIGHT MODE
========================= */

body.light {
    --bg: #F8F5FC;
    --card: #FFFFFF;
    --input: #F1ECF7;

    --text: #211D30;
    --muted: #776A91;

    --border: #E7DDED;
}


/* =========================
   BACKGROUND
========================= */

.background {
    position: fixed;
    inset: 0;
    z-index: -1;
    overflow: hidden;
    pointer-events: none;
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    opacity: .18;
    animation: floatOrb 12s ease-in-out infinite alternate;
}

.orb1 {
    width: 350px;
    height: 350px;
    background: var(--pink);
    top: -150px;
    left: -100px;
}

.orb2 {
    width: 400px;
    height: 400px;
    background: var(--purple);
    right: -150px;
    bottom: -100px;
    animation-delay: 2s;
}

.orb3 {
    width: 180px;
    height: 180px;
    background: var(--pink);
    left: 15%;
    bottom: 20%;
    opacity: .08;
    animation-delay: 4s;
}

@keyframes floatOrb {
    from {
        transform: translate(0, 0);
    }

    to {
        transform: translate(60px, -50px);
    }
}


/* =========================
   NAVBAR
========================= */

.navbar {
    width: 100%;
    max-width: 1100px;
    margin: auto;
    padding: 23px 25px;

    display: flex;
    justify-content: space-between;
    align-items: center;
}

.brand {
    display: flex;
    align-items: center;
    gap: 10px;

    color: var(--text);
    text-decoration: none;

    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 20px;
    font-weight: 800;
}

.brand-icon {
    width: 39px;
    height: 39px;
    background: var(--gradient);

    display: flex;
    justify-content: center;
    align-items: center;

    border-radius: 13px;
    color: white;

    box-shadow: 0 5px 20px rgba(233,75,170,.2);
}

.brand span {
    background: var(--gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
}

.nav-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

.nav-label {
    color: var(--muted);
    font-size: 12px;
    font-weight: 700;
}

.theme-btn {
    width: 42px;
    height: 42px;
    border: 1px solid var(--border);
    border-radius: 50%;

    background: var(--card);
    color: var(--muted);

    cursor: pointer;
    transition: .3s;
}

.theme-btn:hover {
    color: var(--pink);
    border-color: var(--pink);
    transform: rotate(25deg);
}


/* =========================
   MAIN LAYOUT
========================= */

.main {
    min-height: calc(100vh - 100px);

    display: flex;
    justify-content: center;
    align-items: center;

    padding: 40px 20px 80px;
}

.container {
    width: 100%;
    max-width: 470px;
    position: relative;
    animation: containerEnter .8s ease both;
}

@keyframes containerEnter {
    from {
        opacity: 0;
        transform: translateY(35px) scale(.96);
        filter: blur(8px);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0);
    }
}


/* =========================
   PROFILE HEADER
========================= */

.profile-header {
    text-align: center;
    margin-bottom: 28px;
}

.profile-avatar {
    width: 83px;
    height: 83px;
    border-radius: 27px;

    background: var(--gradient);

    display: flex;
    align-items: center;
    justify-content: center;

    margin: 0 auto 20px;

    color: white;
    font-size: 32px;

    box-shadow:
        0 10px 35px rgba(233,75,170,.25),
        0 0 0 8px rgba(233,75,170,.07);

    animation: avatarFloat 4s ease-in-out infinite;
}

@keyframes avatarFloat {
    0%, 100% {
        transform: translateY(0) rotate(-3deg);
    }

    50% {
        transform: translateY(-9px) rotate(3deg);
    }
}

.brand-heading {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -1.5px;
}

.brand-heading span {
    background: var(--gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
}

.profile-subtitle {
    color: var(--muted);
    font-size: 13px;
    margin-top: 7px;
}

.profile-username {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    margin-top: 16px;
    padding: 8px 15px;

    background: rgba(233,75,170,.10);
    border: 1px solid rgba(233,75,170,.22);

    color: var(--pink);
    border-radius: 30px;

    font-size: 12px;
    font-weight: 800;
}


/* =========================
   MESSAGE CARD
========================= */

.message-container {
    position: relative;

    padding: 30px;

    background: var(--card);
    border: 1px solid var(--border);

    border-radius: 28px;

    box-shadow: 0 25px 80px rgba(0,0,0,.20);

    overflow: hidden;
}

.message-container::before {
    content: '';

    position: absolute;
    top: 0;
    left: 10%;
    right: 10%;

    height: 2px;
    background: var(--gradient);

    opacity: .9;
}

.card-heading {
    text-align: center;
    margin-bottom: 25px;
}

.card-heading h2 {
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -.5px;
}

.card-heading p {
    color: var(--muted);
    font-size: 12px;
    margin-top: 7px;
}


/* =========================
   PROMPT BOX
========================= */

.prompt-box {
    padding: 20px;

    background: var(--input);
    border: 1px solid var(--border);

    border-radius: 19px;

    margin-bottom: 23px;
    text-align: center;

    transition: .3s;
}

.prompt-icon {
    width: 39px;
    height: 39px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;
    background: rgba(233,75,170,.12);

    color: var(--pink);

    margin: 0 auto 13px;
    font-size: 16px;
}

.prompt-label {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.5px;
    color: var(--pink);
    text-transform: uppercase;
    margin-bottom: 9px;
}

.prompt-text {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 16px;
    font-weight: 800;
    line-height: 1.6;
    color: var(--text);
    overflow-wrap: anywhere;
}


/* =========================
   TEXTAREA
========================= */

.input-label {
    display: flex;
    justify-content: space-between;
    align-items: center;

    margin-bottom: 11px;

    font-size: 12px;
    font-weight: 800;
    color: var(--text);
}

.char-count {
    color: var(--muted);
    font-size: 10px;
    font-weight: 600;
}

.textarea-wrapper {
    position: relative;
}

textarea {
    display: block;
    width: 100%;
    height: 165px;

    padding: 18px;

    resize: none;

    background: var(--input);
    border: 1.5px solid var(--border);

    border-radius: 17px;

    color: var(--text);

    font-size: 13px;
    line-height: 1.7;

    outline: none;

    transition: .3s;
}

textarea::placeholder {
    color: var(--muted);
    opacity: .7;
}

textarea:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 4px rgba(233,75,170,.10);
}

.textarea-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;

    margin-top: 12px;
}

.anonymous-note {
    color: var(--muted);
    font-size: 10px;

    display: flex;
    align-items: center;
    gap: 6px;
}

.anonymous-note i {
    color: var(--pink);
}


/* =========================
   SEND BUTTON
========================= */

.send-btn {
    width: 100%;

    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;

    margin-top: 23px;
    padding: 17px;

    background: var(--gradient);
    background-size: 200% 200%;

    border: none;
    border-radius: 16px;

    color: white;

    font-size: 13px;
    font-weight: 800;

    cursor: pointer;

    box-shadow: 0 8px 25px rgba(146,56,230,.25);

    transition: .35s;
}

.send-btn:hover {
    transform: translateY(-4px);
    background-position: right center;
    box-shadow: 0 12px 35px rgba(233,75,170,.3);
}

.send-btn:active {
    transform: scale(.98);
}

.send-btn:disabled {
    opacity: .7;
    cursor: not-allowed;
    transform: none;
}

.send-btn i {
    transition: .3s;
}

.send-btn:hover i {
    transform: translateX(5px);
}


/* =========================
   SECURITY INFO
========================= */

.security-info {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;

    margin-top: 22px;

    color: var(--muted);
    font-size: 10px;
    text-align: center;
}

.security-info i {
    color: var(--pink);
}


/* =========================
   SUCCESS SCREEN
========================= */

.success-container {
    text-align: center;
    padding: 20px 0;
}

.success-icon {
    width: 90px;
    height: 90px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin: 0 auto 25px;

    background: rgba(233,75,170,.12);
    border: 1px solid rgba(233,75,170,.3);

    border-radius: 50%;

    color: var(--pink);
    font-size: 35px;

    animation: successPop .7s cubic-bezier(.2,.8,.2,1) both;
}

@keyframes successPop {
    0% {
        opacity: 0;
        transform: scale(.3) rotate(-30deg);
    }

    70% {
        transform: scale(1.15) rotate(8deg);
    }

    100% {
        opacity: 1;
        transform: scale(1) rotate(0);
    }
}

.success-container h2 {
    font-size: 25px;
    font-weight: 800;
    margin-bottom: 10px;
}

.success-container h2 span {
    background: var(--gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
}

.success-container p {
    font-size: 13px;
    color: var(--muted);
    line-height: 1.7;
    margin-bottom: 25px;
}

.success-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.success-primary,
.success-secondary {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;

    padding: 15px;
    border-radius: 15px;

    text-decoration: none;
    font-size: 12px;
    font-weight: 800;

    transition: .3s;
}

.success-primary {
    color: white;
    background: var(--gradient);
}

.success-secondary {
    color: var(--muted);
    border: 1px solid var(--border);
    background: var(--input);
}

.success-primary:hover,
.success-secondary:hover {
    transform: translateY(-3px);
}


/* =========================
   FOOTER
========================= */

.footer {
    text-align: center;
    padding: 25px 15px 35px;
}

.footer p {
    color: var(--muted);
    font-size: 10px;
}

.footer span {
    color: var(--pink);
    font-weight: 800;
}


/* =========================
   RESPONSIVE
========================= */

@media(max-width: 550px) {

    .navbar {
        padding: 17px 20px;
    }

    .nav-label {
        display: none;
    }

    .main {
        align-items: flex-start;
        padding: 40px 16px 50px;
    }

    .container {
        max-width: 430px;
    }

    .profile-avatar {
        width: 75px;
        height: 75px;
        font-size: 27px;
    }

    .brand-heading {
        font-size: 25px;
    }

    .message-container {
        padding: 23px 18px;
        border-radius: 23px;
    }

    .card-heading h2 {
        font-size: 20px;
    }

    .prompt-text {
        font-size: 15px;
    }

    textarea {
        height: 155px;
    }

}

@media(prefers-reduced-motion: reduce) {

    *,
    *::before,
    *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }

}

</style>

</head>

<body>


<!-- BACKGROUND -->

<div class="background">

    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>

</div>


<!-- NAVIGATION -->

<header class="navbar">

    <a href="index.php" class="brand">

        <div class="brand-icon">
            <i class="fa-solid fa-envelope"></i>
        </div>

        JM<span>.NGL</span>

    </a>


    <div class="nav-right">

        <span class="nav-label">
            Anonymous conversations
        </span>

        <button
            class="theme-btn"
            onclick="toggleTheme()"
            aria-label="Toggle theme"
        >
            <i id="themeIcon" class="fa-solid fa-sun"></i>
        </button>

    </div>

</header>


<!-- MAIN CONTENT -->

<main class="main">

    <div class="container">


        <!-- PROFILE HEADER -->

        <div class="profile-header">

            <div class="profile-avatar">
                <i class="fa-solid fa-envelope"></i>
            </div>

            <h1 class="brand-heading">
                JM<span>.NGL</span>
            </h1>

            <p class="profile-subtitle">
                Your space for anonymous conversations.
            </p>

            <div class="profile-username">

                <i class="fa-solid fa-user-secret"></i>

                @<?= htmlspecialchars($user['username']) ?>

            </div>

        </div>


        <!-- MESSAGE CARD -->

        <div class="message-container">


            <?php if ($success): ?>


                <!-- SUCCESS SCREEN -->

                <div class="success-container">

                    <div class="success-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <h2>
                        Message <span>sent!</span>
                    </h2>

                    <p>
                        Your anonymous message has been delivered.

                        Thanks for sharing your thoughts!
                    </p>

                    <div class="success-buttons">

                        <a
                            href="profile.php?u=<?= urlencode($user['username']) ?>"
                            class="success-primary"
                        >
                            <i class="fa-solid fa-paper-plane"></i>
                            Send Another Message
                        </a>

                        <a
                            href="index.php"
                            class="success-secondary"
                        >
                            <i class="fa-solid fa-link"></i>
                            Get Your Own Link
                        </a>

                    </div>

                </div>


            <?php else: ?>


                <!-- MESSAGE FORM -->

                <div class="card-heading">

                    <h2>
                        Send an anonymous message
                    </h2>

                    <p>
                        Say what you want. Keep your identity private.
                    </p>

                </div>


                <!-- USER PROMPT -->

                <div class="prompt-box">

                    <div class="prompt-icon">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>

                    <p class="prompt-label">
                        Their question
                    </p>

                    <h2 class="prompt-text">
                        <?= htmlspecialchars($prompt) ?>
                    </h2>

                </div>


                <!-- FORM -->

                <form
                    action="send.php"
                    method="POST"
                    id="messageForm"
                >

                    <input
                        type="hidden"
                        name="user_id"
                        value="<?= (int)$user['id'] ?>"
                    >

                    <input
                        type="hidden"
                        name="username"
                        value="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>"
                    >


                    <div class="input-label">

                        <label for="message">
                            Your message
                        </label>

                        <span class="char-count" id="charCount">
                            0 / 500
                        </span>

                    </div>


                    <div class="textarea-wrapper">

                        <textarea
                            name="message_text"
                            id="message"
                            maxlength="500"
                            placeholder="Write something nice, funny, or honest..."
                            required
                        ></textarea>

                    </div>


                    <div class="textarea-bottom">

                        <span class="anonymous-note">
                            <i class="fa-solid fa-lock"></i>
                            Your identity stays private
                        </span>

                        <span class="anonymous-note">
                            <i class="fa-solid fa-user-secret"></i>
                            Anonymous
                        </span>

                    </div>


                    <button
                        type="submit"
                        class="send-btn"
                        id="sendBtn"
                    >

                        <span>Send anonymously</span>

                        <i class="fa-solid fa-paper-plane"></i>

                    </button>

                </form>


                <div class="security-info">

                    <i class="fa-solid fa-shield-halved"></i>

                    No account required to send a message.

                </div>


            <?php endif; ?>


        </div>


        <!-- FOOTER -->

        <footer class="footer">

            <p>
                Made with <span>♥</span> by JM.NGL
            </p>

            <p style="margin-top: 7px;">
                © <?= date('Y') ?> JM.NGL. All rights reserved.
            </p>

        </footer>


    </div>

</main>


<!-- JAVASCRIPT -->

<script>

/* =========================
   THEME SWITCHER
========================= */

const themeIcon = document.getElementById('themeIcon');

function updateThemeIcon() {

    const light = document.body.classList.contains('light');

    themeIcon.className = light
        ? 'fa-solid fa-moon'
        : 'fa-solid fa-sun';

}

function toggleTheme() {

    document.body.classList.toggle('light');

    const theme = document.body.classList.contains('light')
        ? 'light'
        : 'dark';

    localStorage.setItem('jmngl-theme', theme);

    updateThemeIcon();

}


/* LOAD SAVED THEME */

(function() {

    if (localStorage.getItem('jmngl-theme') === 'light') {

        document.body.classList.add('light');

    }

    updateThemeIcon();

})();


/* =========================
   CHARACTER COUNTER
========================= */

const message = document.getElementById('message');
const charCount = document.getElementById('charCount');

if (message && charCount) {

    message.addEventListener('input', function() {

        const length = this.value.length;

        charCount.textContent = length + ' / 500';

        if (length >= 450) {

            charCount.style.color = '#E94BAA';

        } else {

            charCount.style.color = '';

        }

    });

}


/* =========================
   SEND BUTTON ANIMATION
========================= */

const messageForm = document.getElementById('messageForm');
const sendBtn = document.getElementById('sendBtn');

if (messageForm && sendBtn) {

    messageForm.addEventListener('submit', function(event) {

        if (!message.value.trim()) {

            event.preventDefault();

            message.focus();

            return;

        }

        sendBtn.disabled = true;

        sendBtn.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Sending your message...
        `;

    });

}


/* =========================
   TEXTAREA FOCUS EFFECT
========================= */

if (message) {

    message.addEventListener('focus', function() {

        this.parentElement.style.transform = 'translateY(-2px)';

    });

    message.addEventListener('blur', function() {

        this.parentElement.style.transform = 'translateY(0)';

    });

}

</script>

</body>
</html>
