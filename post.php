<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$file = 'posts.json';
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$posts = json_decode(file_get_contents($file), true);
$username = $_SESSION['username'];
$content = $_POST['content'] ?? '';
$uploadedFile = '';

if (!empty($_FILES['file']['name'])) {
    $targetDir = 'uploads/';
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $uploadedFile = $targetDir . basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile);
}

$newPost = [
    'username' => $username,
    'content' => $content,
    'file' => $uploadedFile
];

$posts[] = $newPost;
file_put_contents($file, json_encode($posts));

header("Location: dashboard.php");
exit;