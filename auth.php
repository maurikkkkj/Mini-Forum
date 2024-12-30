<?php
session_start();
$file = 'users.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$users = json_decode(file_get_contents($file), true);
$action = $_POST['action'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

function redirectWithMessage($location, $message, $type = 'info') {
    $_SESSION['message'] = $message;
    $_SESSION['type'] = $type;
    header("Location: $location");
    exit;
}

if ($action === 'register') {
    if (isset($users[$username])) {
        redirectWithMessage('index.php', "Usuário já existe!", "danger");
    } else {
        $users[$username] = password_hash($password, PASSWORD_BCRYPT);
        file_put_contents($file, json_encode($users));
        redirectWithMessage('index.php', "Conta criada com sucesso!", "success");
    }
} elseif ($action === 'login') {
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['username'] = $username;
        redirectWithMessage('dashboard.php', "Bem-vindo, $username!", "success");
    } else {
        redirectWithMessage('index.php', "Usuário ou senha incorretos!", "danger");
    }
} else {
    redirectWithMessage('index.php', "Ação inválida!", "warning");
}