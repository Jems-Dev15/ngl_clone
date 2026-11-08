
<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>JM.NGL - Anonymous Messages</title>

<meta name="description" content="Your space for anonymous conversations. Send and receive anonymous messages with JM.NGL.">

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

/* ==============================
   GLOBAL SETTINGS
============================== */

:root {
    --bg-main: #15121F;
    --bg-card: #211D30;
    --bg-input: #191625;

    --pink: #E94BAA;
    --purple: #9238E6;

    --text-main: #F8F2FA;
    --text-muted: #B9A9DC;

    --border: #393047;

    --gradient: linear-gradient(
        120deg,
        #E94BAA,
        #9238E6
    );
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'DM Sans', sans-serif;
}

html {
    scroll-behavior: smooth;
}

body {
    background: var(--bg-main);
    color: var(--text-main);
    overflow-x: hidden;
    transition: background .4s, color .4s;
}

h1, h2, h3, h4 {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

::selection {
    background: var(--pink);
    color: white;
}


/* ==============================
   LIGHT MODE
============================== */

body.light {
    --bg-main: #F8F5FC;
    --bg-card: #FFFFFF;
    --bg-input: #F1ECF7;

    --text-main: #211D30;
    --text-muted: #776A91;

    --border: #E7DDED;
}

body.light .navbar {
    background: rgba(248,245,252,.85);
}

body.light .feature-card,
body.light .message-card {
    background: #FFFFFF;
    border-color: #E7DDED;
}

body.light .message-card {
    color: #211D30;
}

body.light .message-card .message-meta {
    color: #776A91;
}

body.light .theme-button {
    background: #FFFFFF;
    color: #9238E6;
    border-color: #E7DDED;
}

body.light .ticker {
    background: #211D30;
}

body.light .hero {
    background: #211D30;
}

body.light .nav-links a {
    color: #211D30;
}

body.light .logo {
    color: #211D30;
}

body.light .section-description {
    color: #776A91;
}

body.light .footer {
    border-color: #E7DDED;
}


/* ==============================
   ANIMATED BACKGROUND
============================== */

.background-effects {
    position: fixed;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 0;
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    opacity: .16;
    animation: orbFloat 12s ease-in-out infinite alternate;
}

.orb-one {
    width: 400px;
    height: 400px;
    background: #E94BAA;
    top: -180px;
    left: -120px;
}

.orb-two {
    width: 450px;
    height: 450px;
    background: #9238E6;
    right: -180px;
    top: 35%;
    animation-delay: 2s;
}

.orb-three {
    width: 350px;
    height: 350px;
    background: #7138C9;
    left: 20%;
    bottom: -200px;
    animation-delay: 4s;
}

@keyframes orbFloat {
    from {
        transform: translate(0, 0) scale(1);
    }

    to {
        transform: translate(70px, -50px) scale(1.2);
    }
}


/* ==============================
   NAVBAR
============================== */

.navbar {
    position: relative;
    z-index: 50;
    max-width: 1250px;
    margin: auto;
    padding: 22px 25px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    transition: .3s;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;

    color: white;
    text-decoration: none;

    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 23px;
    font-weight: 800;
    letter-spacing: -1px;
}

.logo-icon {
    width: 43px;
    height: 43px;

    background: var(--gradient);

    border-radius: 14px;

    display: flex;
    justify-content: center;
    align-items: center;

    color: white;
    font-size: 18px;

    box-shadow: 0 0 25px rgba(233,75,170,.25);

    transition: .4s;
}

.logo:hover .logo-icon {
    transform: rotate(-12deg) scale(1.1);
}

.logo span {
    background: var(--gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 35px;
}

.nav-links a {
    color: var(--text-muted);
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: .3s;
    position: relative;
}

.nav-links a::after {
    content: '';
    position: absolute;
    bottom: -7px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--pink);
    transition: .3s;
}

.nav-links a:hover {
    color: var(--pink);
}

.nav-links a:hover::after {
    width: 100%;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.theme-button {
    width: 43px;
    height: 43px;
    border-radius: 50%;

    background: var(--bg-card);
    border: 1px solid var(--border);

    color: var(--text-muted);

    cursor: pointer;
    transition: .3s;
}

.theme-button:hover {
    color: var(--pink);
    transform: rotate(25deg);
    border-color: var(--pink);
}

.nav-cta {
    background: var(--gradient);
    padding: 13px 22px;
    border-radius: 30px;

    color: white;
    text-decoration: none;

    font-size: 12px;
    font-weight: 800;

    transition: .3s;
    box-shadow: 0 5px 20px rgba(146,56,230,.2);
}

.nav-cta:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(233,75,170,.35);
}


/* ==============================
   MOBILE MENU
============================== */

.menu-button {
    display: none;
    width: 43px;
    height: 43px;
    border-radius: 50%;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-main);
    font-size: 17px;
    cursor: pointer;
}

.mobile-menu {
    display: none;
    position: absolute;
    top: 75px;
    left: 15px;
    right: 15px;
    padding: 20px;
    border-radius: 20px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    box-shadow: 0 15px 40px rgba(0,0,0,.3);
}

.mobile-menu.active {
    display: flex;
    flex-direction: column;
    gap: 18px;
    animation: menuOpen .3s ease;
}

.mobile-menu a {
    color: var(--text-main);
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
}

@keyframes menuOpen {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}


/* ==============================
   TICKER
============================== */

.ticker {
    position: relative;
    z-index: 5;
    overflow: hidden;
    white-space: nowrap;

    background: #211D30;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);

    padding: 12px 0;
}

.ticker-track {
    display: flex;
    width: max-content;
    animation: tickerMove 35s linear infinite;
}

.ticker:hover .ticker-track {
    animation-play-state: paused;
}

.ticker-track span {
    padding: 0 25px;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 800;
}

.ticker-track span:nth-child(even) {
    color: var(--pink);
}

@keyframes tickerMove {
    from {
        transform: translateX(0);
    }

    to {
        transform: translateX(-50%);
    }
}


/* ==============================
   HERO SECTION
============================== */

.hero {
    position: relative;
    z-index: 2;

    max-width: 1250px;
    min-height: 670px;

    margin: 20px auto 0;
    padding: 70px 35px;

    border-radius: 35px;
    overflow: hidden;

    background: var(--bg-card);
    border: 1px solid var(--border);

    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-glow {
    position: absolute;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    background: var(--gradient);
    filter: blur(140px);
    opacity: .15;
    right: -120px;
    top: -150px;
    animation: glowPulse 7s ease-in-out infinite alternate;
}

@keyframes glowPulse {
    from {
        opacity: .10;
        transform: scale(.8);
    }

    to {
        opacity: .25;
        transform: scale(1.2);
    }
}

.hero-content {
    position: relative;
    z-index: 5;

    max-width: 800px;
    text-align: center;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 9px;

    padding: 10px 18px;
    border-radius: 30px;

    color: var(--pink);
    background: rgba(233,75,170,.10);
    border: 1px solid rgba(233,75,170,.25);

    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;

    margin-bottom: 30px;

    animation: fadeUp .8s both;
}

.live-dot {
    width: 7px;
    height: 7px;
    background: #E94BAA;
    border-radius: 50%;
    box-shadow: 0 0 12px #E94BAA;
    animation: blink 1.5s infinite;
}

@keyframes blink {
    50% {
        opacity: .3;
        transform: scale(.7);
    }
}

.hero-title {
    font-family: 'Plus Jakarta Sans', sans-serif;

    font-size: clamp(55px, 8vw, 100px);
    line-height: 1.03;

    font-weight: 800;
    letter-spacing: -6px;

    color: var(--text-main);

    animation: heroEnter 1s cubic-bezier(.2,.8,.2,1) both;
}

.hero-title span {
    display: inline-block;

    background: var(--gradient);
    background-size: 200% 200%;

    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;

    animation: gradientMove 4s ease infinite;
}

@keyframes gradientMove {
    0%,100% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }
}

@keyframes heroEnter {
    from {
        opacity: 0;
        transform: translateY(45px) scale(.95);
        filter: blur(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0);
    }
}

.hero-description {
    max-width: 540px;
    margin: 25px auto 0;

    color: var(--text-muted);
    font-size: 15px;
    line-height: 1.8;

    animation: fadeUp 1.2s both;
}

.hero-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 13px;

    margin-top: 35px;

    animation: fadeUp 1.4s both;
}

.primary-btn,
.secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;

    padding: 16px 28px;
    border-radius: 50px;

    font-size: 13px;
    font-weight: 800;

    text-decoration: none;

    transition: .35s;
}

.primary-btn {
    background: var(--gradient);
    background-size: 200% 200%;
    color: white;

    box-shadow: 0 8px 30px rgba(146,56,230,.3);
}

.primary-btn:hover {
    transform: translateY(-5px) scale(1.04);
    background-position: right center;
    box-shadow: 0 12px 35px rgba(233,75,170,.35);
}

.secondary-btn {
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text-main);
}

.secondary-btn:hover {
    border-color: var(--pink);
    color: var(--pink);
    transform: translateY(-5px);
}

.hero-note {
    color: var(--text-muted);
    font-size: 11px;
    margin-top: 25px;
    animation: fadeUp 1.6s both;
}


/* ==============================
   FLOATING DECORATIONS
============================== */

.float-item {
    position: absolute;
    z-index: 3;

    display: flex;
    justify-content: center;
    align-items: center;

    border-radius: 23px;
    background: var(--bg-card);
    border: 1px solid rgba(233,75,170,.3);

    box-shadow: 0 15px 40px rgba(0,0,0,.25);

    animation: floatItem 6s ease-in-out infinite;
}

.float-item i {
    font-size: 25px;
    background: var(--gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
}

.float-one {
    width: 75px;
    height: 75px;
    left: 9%;
    top: 20%;
    transform: rotate(-12deg);
}

.float-two {
    width: 65px;
    height: 65px;
    right: 10%;
    top: 22%;
    animation-delay: 1s;
}

.float-three {
    width: 80px;
    height: 80px;
    left: 15%;
    bottom: 12%;
    animation-delay: 2s;
}

.float-four {
    width: 65px;
    height: 65px;
    right: 15%;
    bottom: 14%;
    animation-delay: 3s;
}

@keyframes floatItem {
    0%,100% {
        translate: 0 0;
        rotate: -5deg;
    }

    50% {
        translate: 0 -20px;
        rotate: 8deg;
    }
}


/* ==============================
   MESSAGE PREVIEW
============================== */

.message-preview {
    position: relative;
    z-index: 5;

    max-width: 700px;
    margin: 30px auto 0;

    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.message-card {
    background: rgba(255,255,255,.045);
    border: 1px solid var(--border);

    border-radius: 18px;
    padding: 17px;

    text-align: left;

    transition: .4s;
    animation: messageAppear .8s both;
}

.message-card:nth-child(2) {
    animation-delay: .2s;
}

.message-card:nth-child(3) {
    animation-delay: .4s;
}

@keyframes messageAppear {
    from {
        opacity: 0;
        transform: translateY(25px) rotate(-3deg);
    }

    to {
        opacity: 1;
        transform: translateY(0) rotate(0);
    }
}

.message-card:hover {
    transform: translateY(-8px) rotate(1deg);
    border-color: var(--pink);
    box-shadow: 0 12px 35px rgba(233,75,170,.12);
}

.message-meta {
    color: var(--text-muted);
    font-size: 10px;
    margin-bottom: 12px;
    display: flex;
    gap: 7px;
    align-items: center;
}

.message-meta i {
    color: var(--pink);
}

.message-card p {
    font-size: 12px;
    font-weight: 600;
    line-height: 1.6;
    color: var(--text-main);
}


/* ==============================
   SECTION GENERAL
============================== */

.section {
    position: relative;
    z-index: 2;

    max-width: 1150px;
    margin: auto;
    padding: 110px 25px;
}

.section-heading {
    text-align: center;
    max-width: 650px;
    margin: 0 auto 50px;
}

.section-label {
    display: inline-block;

    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;

    color: var(--pink);
    margin-bottom: 15px;
}

.section-title {
    font-size: clamp(30px, 5vw, 47px);
    font-weight: 800;
    letter-spacing: -2px;
    color: var(--text-main);
    line-height: 1.2;
}

.section-title span {
    background: var(--gradient);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
}

.section-description {
    color: var(--text-muted);
    font-size: 14px;
    line-height: 1.8;
    margin-top: 15px;
}


/* ==============================
   FEATURE CARDS
============================== */

.feature-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.feature-card {
    position: relative;
    overflow: hidden;

    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 25px;

    padding: 30px;

    transition: .4s;
}

.feature-card::before {
    content: '';
    position: absolute;
    width: 150px;
    height: 150px;
    background: var(--pink);
    filter: blur(100px);
    opacity: 0;
    top: -60px;
    right: -60px;
    transition: .4s;
}

.feature-card:hover {
    transform: translateY(-10px);
    border-color: rgba(233,75,170,.5);
    box-shadow: 0 15px 45px rgba(0,0,0,.2);
}

.feature-card:hover::before {
    opacity: .3;
}

.feature-icon {
    position: relative;
    z-index: 1;

    width: 58px;
    height: 58px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 18px;
    background: linear-gradient(135deg, rgba(233,75,170,.18), rgba(146,56,230,.18));

    color: var(--pink);
    font-size: 22px;

    margin-bottom: 25px;

    transition: .4s;
}

.feature-card:hover .feature-icon {
    transform: rotate(-10deg) scale(1.1);
    background: var(--gradient);
    color: white;
}

.feature-card h3 {
    position: relative;
    z-index: 1;
    font-size: 17px;
    font-weight: 800;
    color: var(--text-main);
}

.feature-card p {
    position: relative;
    z-index: 1;
    font-size: 12px;
    line-height: 1.8;
    color: var(--text-muted);
    margin-top: 12px;
}


/* ==============================
   HOW IT WORKS
============================== */

.steps-container {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 30px;
    padding: 55px 35px;
    position: relative;
    overflow: hidden;
}

.steps-container::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: #9238E6;
    filter: blur(120px);
    opacity: .12;
    right: -100px;
    top: -100px;
}

.steps-grid {
    position: relative;
    z-index: 2;

    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 35px;
    text-align: center;
}

.step-number {
    width: 65px;
    height: 65px;

    display: flex;
    justify-content: center;
    align-items: center;

    border-radius: 22px;
    background: var(--gradient);

    color: white;
    font-size: 23px;

    margin: 0 auto 22px;

    box-shadow: 0 10px 25px rgba(146,56,230,.25);

    transition: .4s;
}

.step:hover .step-number {
    transform: translateY(-8px) rotate(10deg);
}

.step h3 {
    font-size: 16px;
    font-weight: 800;
    color: var(--text-main);
}

.step p {
    font-size: 12px;
    color: var(--text-muted);
    line-height: 1.8;
    margin-top: 12px;
}


/* ==============================
   CTA SECTION
============================== */

.cta-section {
    position: relative;
    z-index: 2;

    max-width: 900px;
    margin: 0 auto;

    padding: 80px 25px 110px;
    text-align: center;
}

.cta-icon {
    font-size: 42px;
    display: inline-block;
    color: var(--pink);
    animation: heartbeat 1.8s infinite;
}

@keyframes heartbeat {
    0%,100% {
        transform: scale(1);
    }

    15% {
        transform: scale(1.2);
    }

    30% {
        transform: scale(1);
    }

    45% {
        transform: scale(1.12);
    }
}


/* ==============================
   FOOTER
============================== */

.footer {
    position: relative;
    z-index: 2;

    border-top: 1px solid var(--border);
    padding: 30px 25px;
}

.footer-inner {
    max-width: 1150px;
    margin: auto;

    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.footer p {
    font-size: 11px;
    color: var(--text-muted);
}

.footer-links {
    display: flex;
    gap: 22px;
}

.footer-links a {
    color: var(--text-muted);
    font-size: 11px;
    text-decoration: none;
    transition: .3s;
}

.footer-links a:hover {
    color: var(--pink);
}


/* ==============================
   SCROLL REVEAL ANIMATION
============================== */

.reveal {
    opacity: 0;
    transform: translateY(40px);
    transition: opacity .8s ease, transform .8s ease;
}

.reveal.active {
    opacity: 1;
    transform: translateY(0);
}

.reveal-left {
    opacity: 0;
    transform: translateX(-40px);
    transition: .8s ease;
}

.reveal-left.active {
    opacity: 1;
    transform: translateX(0);
}

.reveal-right {
    opacity: 0;
    transform: translateX(40px);
    transition: .8s ease;
}

.reveal-right.active {
    opacity: 1;
    transform: translateX(0);
}


/* ==============================
   BACK TO TOP
============================== */

.back-top {
    position: fixed;
    bottom: 25px;
    right: 25px;

    width: 45px;
    height: 45px;

    border: none;
    border-radius: 15px;

    background: var(--gradient);
    color: white;

    cursor: pointer;
    z-index: 100;

    opacity: 0;
    visibility: hidden;
    transform: translateY(15px);

    transition: .3s;
    box-shadow: 0 5px 25px rgba(146,56,230,.3);
}

.back-top.visible {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.back-top:hover {
    transform: translateY(-5px);
}


/* ==============================
   RESPONSIVE DESIGN
============================== */

@media(max-width: 900px) {

    .hero {
        margin: 15px;
        min-height: 650px;
    }

    .float-one {
        left: 3%;
    }

    .float-two {
        right: 3%;
    }

    .feature-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media(max-width: 700px) {

    .navbar {
        padding: 17px 20px;
    }

    .nav-links {
        display: none;
    }

    .menu-button {
        display: block;
    }

    .nav-cta {
        display: none;
    }

    .hero {
        min-height: 650px;
        padding: 60px 20px;
        border-radius: 25px;
    }

    .hero-title {
        font-size: clamp(48px, 12vw, 75px);
        letter-spacing: -3px;
    }

    .hero-description {
        font-size: 13px;
    }

    .float-one {
        top: 5%;
        left: 5%;
        width: 50px;
        height: 50px;
    }

    .float-two {
        top: 7%;
        right: 5%;
        width: 50px;
        height: 50px;
    }

    .float-three,
    .float-four {
        display: none;
    }

    .message-preview {
        grid-template-columns: 1fr;
        max-width: 380px;
    }

    .message-card:nth-child(3) {
        display: none;
    }

    .section {
        padding: 80px 20px;
    }

    .feature-grid {
        grid-template-columns: 1fr;
    }

    .steps-container {
        padding: 40px 20px;
    }

    .steps-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .footer-inner {
        justify-content: center;
        text-align: center;
    }

}

@media(prefers-reduced-motion: reduce) {

    *,
    *::before,
    *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }

    .reveal,
    .reveal-left,
    .reveal-right {
        opacity: 1;
        transform: none;
    }

}

</style>
</head>

<body>

<!-- BACKGROUND EFFECTS -->

<div class="background-effects">
    <div class="orb orb-one"></div>
    <div class="orb orb-two"></div>
    <div class="orb orb-three"></div>
</div>


<!-- TICKER -->

<div class="ticker">
    <div class="ticker-track">

        <?php for($i = 0; $i < 2; $i++): ?>

            <span>✦ send me anything</span>
            <span>• not gonna lie</span>
            <span>• describe me in 3 words</span>
            <span>• send me your confessions</span>
            <span>• share an opinion</span>
            <span>• tell me a secret</span>
            <span>• who has a crush on me?</span>
            <span>• say something nice</span>

        <?php endfor; ?>

    </div>
</div>


<!-- NAVBAR -->

<header class="navbar">

    <a href="index.php" class="logo">

        <div class="logo-icon">
            <i class="fa-solid fa-envelope"></i>
        </div>

        JM<span>.NGL</span>

    </a>


    <div class="nav-links">

        <a href="#about">About</a>
        <a href="#features">Features</a>
        <a href="#how">How it works</a>
        <a href="#contact">Contact</a>

    </div>


    <div class="nav-actions">

        <button class="theme-button" onclick="toggleTheme()" aria-label="Toggle theme">
            <i id="themeIcon" class="fa-solid fa-sun"></i>
        </button>

        <?php if($isLoggedIn): ?>

            <a href="dashboard.php" class="nav-cta">
                My Dashboard <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>

        <?php else: ?>

            <a href="login.php" class="nav-cta">
                Get Started <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>

        <?php endif; ?>

        <button class="menu-button" onclick="toggleMenu()" aria-label="Open navigation menu">
            <i id="menuIcon" class="fa-solid fa-bars"></i>
        </button>

    </div>


    <div class="mobile-menu" id="mobileMenu">

        <a href="#about" onclick="closeMenu()">About</a>
        <a href="#features" onclick="closeMenu()">Features</a>
        <a href="#how" onclick="closeMenu()">How it works</a>
        <a href="#contact" onclick="closeMenu()">Contact</a>

        <?php if($isLoggedIn): ?>
            <a href="dashboard.php">My Dashboard</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="login.php?mode=register">Create Account</a>
        <?php endif; ?>

    </div>

</header>


<!-- HERO SECTION -->

<main>

<section class="hero">

    <div class="hero-glow"></div>


    <!-- FLOATING ICONS -->

    <div class="float-item float-one">
        <i class="fa-solid fa-heart"></i>
    </div>

    <div class="float-item float-two">
        <i class="fa-solid fa-paper-plane"></i>
    </div>

    <div class="float-item float-three">
        <i class="fa-solid fa-comment-dots"></i>
    </div>

    <div class="float-item float-four">
        <i class="fa-solid fa-envelope-open"></i>
    </div>


    <!-- HERO CONTENT -->

    <div class="hero-content">

        <div class="hero-badge">

            <span class="live-dot"></span>

            YOUR SPACE FOR ANONYMOUS CONVERSATIONS

        </div>


        <h1 class="hero-title">

            Say it<br>

            <span>without</span><br>

            saying who.

        </h1>


        <p class="hero-description">

            Get honest messages, funny confessions, and unexpected thoughts from the people around you.

            No names. Just messages.

        </p>


        <div class="hero-buttons">

            <?php if($isLoggedIn): ?>

                <a href="dashboard.php" class="primary-btn">
                    <i class="fa-solid fa-inbox"></i>
                    Go to My Inbox
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <a href="profile.php?u=<?= urlencode($username) ?>" class="secondary-btn">
                    <i class="fa-solid fa-link"></i>
                    View My Link
                </a>

            <?php else: ?>

                <a href="login.php?mode=register" class="primary-btn">
                    Get My Anonymous Link
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

                <a href="login.php" class="secondary-btn">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Login
                </a>

            <?php endif; ?>

        </div>


        <p class="hero-note">
            <i class="fa-solid fa-shield-halved"></i>
            Anonymous messaging with privacy in mind
        </p>


        <!-- MESSAGE PREVIEW -->

        <div class="message-preview">

            <div class="message-card">

                <div class="message-meta">
                    <i class="fa-solid fa-user-secret"></i>
                    Anonymous message
                </div>

                <p>
                    "You always make everyone's day better. Never change!"
                </p>

            </div>


            <div class="message-card">

                <div class="message-meta">
                    <i class="fa-solid fa-user-secret"></i>
                    Anonymous message
                </div>

                <p>
                    "I have something I've wanted to tell you for a while..."
                </p>

            </div>


            <div class="message-card">

                <div class="message-meta">
                    <i class="fa-solid fa-user-secret"></i>
                    Anonymous message
                </div>

                <p>
                    "You're one of the coolest people I know!"
                </p>

            </div>

        </div>

    </div>

</section>


<!-- ABOUT SECTION -->

<section id="about" class="section">

    <div class="section-heading reveal">

        <span class="section-label">Welcome to JM.NGL</span>

        <h2 class="section-title">
            Your friends have
            <span>something to say.</span>
        </h2>

        <p class="section-description">
            Give your friends a place to share thoughts, send confessions,
            and express themselves through anonymous messages.
        </p>

    </div>

</section>


<!-- FEATURES -->

<section id="features" class="section" style="padding-top: 20px;">

    <div class="section-heading reveal">

        <span class="section-label">What makes us different?</span>

        <h2 class="section-title">
            A better way to
            <span>connect.</span>
        </h2>

        <p class="section-description">
            Everything you need for anonymous conversations in one place.
        </p>

    </div>


    <div class="feature-grid">

        <div class="feature-card reveal">

            <div class="feature-icon">
                <i class="fa-solid fa-user-secret"></i>
            </div>

            <h3>Anonymous Messages</h3>

            <p>
                Let people send messages without displaying their account identity.
            </p>

        </div>


        <div class="feature-card reveal">

            <div class="feature-icon">
                <i class="fa-solid fa-inbox"></i>
            </div>

            <h3>Personal Inbox</h3>

            <p>
                Keep your received messages organized in one convenient dashboard.
            </p>

        </div>


        <div class="feature-card reveal">

            <div class="feature-icon">
                <i class="fa-solid fa-share-nodes"></i>
            </div>

            <h3>Share Anywhere</h3>

            <p>
                Share your personal link with friends through your favorite platforms.
            </p>

        </div>

    </div>

</section>


<!-- HOW IT WORKS -->

<section id="how" class="section" style="padding-top: 30px;">

    <div class="steps-container reveal">

        <div class="section-heading">

            <span class="section-label">Get started</span>

            <h2 class="section-title">
                How it <span>works.</span>
            </h2>

            <p class="section-description">
                Three simple steps to start receiving anonymous messages.
            </p>

        </div>


        <div class="steps-grid">

            <div class="step">

                <div class="step-number">
                    <i class="fa-solid fa-user-plus"></i>
                </div>

                <h3>01. Create Account</h3>

                <p>
                    Sign up and create your personal profile.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    <i class="fa-solid fa-link"></i>
                </div>

                <h3>02. Share Your Link</h3>

                <p>
                    Let your friends know where they can send messages.
                </p>

            </div>


            <div class="step">

                <div class="step-number">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>

                <h3>03. Receive Messages</h3>

                <p>
                    Open your dashboard and read your messages.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- FINAL CTA -->

<section id="contact" class="cta-section reveal">

    <div class="cta-icon">
        <i class="fa-solid fa-heart"></i>
    </div>

    <h2 class="section-title" style="margin-top: 25px;">

        Ready to hear what

        <span>people think?</span>

    </h2>

    <p class="section-description">

        Start your anonymous messaging journey today.

        Create your link and share it with your friends.

    </p>


    <div class="hero-buttons">

        <a
            href="<?= $isLoggedIn ? 'dashboard.php' : 'login.php?mode=register' ?>"
            class="primary-btn"
        >

            <?= $isLoggedIn ? 'Open My Dashboard' : 'Create My Account' ?>

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </div>

</section>

</main>


<!-- FOOTER -->

<footer class="footer">

    <div class="footer-inner">

        <a href="index.php" class="logo">

            <div class="logo-icon">
                <i class="fa-solid fa-envelope"></i>
            </div>

            JM<span>.NGL</span>

        </a>


        <p>
            © <?= date('Y') ?> JM.NGL. All rights reserved.
        </p>


        <div class="footer-links">

            <a href="#about">About</a>
            <a href="#features">Features</a>
            <a href="#how">How it works</a>

        </div>

    </div>

</footer>


<!-- BACK TO TOP -->

<button class="back-top" id="backTop" onclick="scrollToTop()" aria-label="Back to top">

    <i class="fa-solid fa-arrow-up"></i>

</button>


<!-- JAVASCRIPT -->

<script>

/* ==============================
   THEME SWITCHER
============================== */

const themeIcon = document.getElementById('themeIcon');

function updateThemeIcon() {

    const isLight = document.body.classList.contains('light');

    themeIcon.className = isLight
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

    const savedTheme = localStorage.getItem('jmngl-theme');

    if (savedTheme === 'light') {
        document.body.classList.add('light');
    }

    updateThemeIcon();

})();


/* ==============================
   MOBILE MENU
============================== */

const mobileMenu = document.getElementById('mobileMenu');

function toggleMenu() {

    mobileMenu.classList.toggle('active');

    const icon = document.getElementById('menuIcon');

    icon.className = mobileMenu.classList.contains('active')
        ? 'fa-solid fa-xmark'
        : 'fa-solid fa-bars';

}

function closeMenu() {

    mobileMenu.classList.remove('active');

    document.getElementById('menuIcon').className =
        'fa-solid fa-bars';

}


/* ==============================
   SCROLL REVEAL
============================== */

const revealElements = document.querySelectorAll(
    '.reveal, .reveal-left, .reveal-right'
);

const revealObserver = new IntersectionObserver((entries) => {

    entries.forEach(entry => {

        if (entry.isIntersecting) {

            entry.target.classList.add('active');

            revealObserver.unobserve(entry.target);

        }

    });

}, {
    threshold: 0.12
});

revealElements.forEach(element => {

    revealObserver.observe(element);

});


/* ==============================
   BACK TO TOP
============================== */

const backTop = document.getElementById('backTop');

window.addEventListener('scroll', () => {

    if (window.scrollY > 400) {

        backTop.classList.add('visible');

    } else {

        backTop.classList.remove('visible');

    }

});

function scrollToTop() {

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

}


/* ==============================
   NAVBAR SCROLL
============================== */

window.addEventListener('scroll', () => {

    const navbar = document.querySelector('.navbar');

    if (window.scrollY > 50) {

        navbar.style.paddingTop = '14px';
        navbar.style.paddingBottom = '14px';

    } else {

        navbar.style.paddingTop = '';
        navbar.style.paddingBottom = '';

    }

});


/* ==============================
   CLOSE MOBILE MENU ON OUTSIDE CLICK
============================== */

document.addEventListener('click', (event) => {

    const menuButton = document.querySelector('.menu-button');

    if (
        mobileMenu.classList.contains('active') &&
        !mobileMenu.contains(event.target) &&
        !menuButton.contains(event.target)
    ) {

        closeMenu();

    }

});

</script>

</body>
</html>
