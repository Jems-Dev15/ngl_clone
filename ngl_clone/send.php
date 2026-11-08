<?php
require_once 'database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
  $message_text = trim($_POST['message_text'] ?? '');
  if (!$user_id || empty($message_text) || empty($username)) die("Invalid submission details.");
  $bad_words = ['scam', 'hack', 'malware'];
  $message_text = str_ireplace($bad_words, '***', $message_text);
  $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
  $device_hint = 'Unknown Device';
  if (preg_match('/iphone/i', $ua)) $device_hint = 'iPhone';
  elseif (preg_match('/android/i', $ua)) $device_hint = 'Android Device';
  elseif (preg_match('/windows/i', $ua)) $device_hint = 'Windows PC';
  elseif (preg_match('/macintosh/i', $ua)) $device_hint = 'MacBook';
  $stmt = $pdo->prepare('INSERT INTO messages (user_id, message_text, device_hint) VALUES (?, ?, ?)');
  $stmt->execute([$user_id, $message_text, $device_hint]);
  header("Location: profile.php?u=" . urlencode($username) . "&sent=1");
  exit;
} else {
  header("Location: index.php");
  exit;
}
?>