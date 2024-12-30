<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elpiss – Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #1e1e1e;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 400px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-switch {
            text-align: center;
            margin-top: 15px;
            color: #aaa;
            cursor: pointer;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-' . $_SESSION['type'] . ' alert-dismissible fade show" role="alert">';
            echo htmlspecialchars($_SESSION['message']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['message'], $_SESSION['type']);
        }
        ?>
        <h2 id="form-title" class="text-center mb-4">Login</h2>
        <form action="auth.php" method="POST" id="auth-form">
            <div id="form-content">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
            </div>
            <button type="submit" name="action" value="login" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="form-switch" onclick="toggleForm()">Não tem uma conta? <strong>Crie uma!</strong></div>
    </div>

    <script>
        function toggleForm() {
            const formTitle = document.getElementById('form-title');
            const formContent = document.getElementById('form-content');
            const switchText = document.querySelector('.form-switch');
            const submitButton = document.querySelector('#auth-form button');

            if (formTitle.textContent === "Login") {
                formTitle.textContent = "Criar Conta";
                switchText.innerHTML = "Já tem uma conta? <strong>Faça login!</strong>";
                submitButton.textContent = "Criar Conta";
                submitButton.value = "register";
            } else {
                formTitle.textContent = "Login";
                switchText.innerHTML = "Não tem uma conta? <strong>Crie uma!</strong>";
                submitButton.textContent = "Login";
                submitButton.value = "login";
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>