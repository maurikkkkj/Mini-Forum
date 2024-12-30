<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

date_default_timezone_set('America/Sao_Paulo');

$postsFile = 'posts.json';

if (!file_exists($postsFile)) {
    file_put_contents($postsFile, json_encode([]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $content = htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8');
    $uploadedFile = $_FILES['media'] ?? null;

    if (!empty($content) || ($uploadedFile && $uploadedFile['size'] > 0)) {
        $newPost = [
            'username' => $username,
            'content' => $content,
            'date' => date('d/m/Y H:i:s'),
        ];

        if ($uploadedFile && $uploadedFile['size'] > 0) {
            $uploadsDir = 'uploads/';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }

            $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('post_', true) . '.' . $fileExtension;
            $filePath = $uploadsDir . $newFileName;

            if (move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
                $newPost['media'] = $filePath;
            }
        }

        $posts = json_decode(file_get_contents($postsFile), true);
        $posts[] = $newPost;
        file_put_contents($postsFile, json_encode($posts, JSON_PRETTY_PRINT));
    }
}

$posts = json_decode(file_get_contents($postsFile), true);
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
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #ffffff;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }
        header h1 {
            color: #e91e63;
        }
        .logout-button {
            padding: 8px 16px;
            background-color: #e91e63;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .logout-button:hover {
            background-color: #c2185b;
        }
        .create-post {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #292929;
            border-radius: 8px;
        }
        .create-post textarea {
            width: 100%;
            height: 80px;
            margin-bottom: 10px;
            border: none;
            padding: 10px;
            border-radius: 4px;
            background-color: #1e1e1e;
            color: #fff;
        }
        .custom-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .custom-upload input[type="file"] {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .custom-upload-btn,
        .custom-submit-btn {
            display: block;
            padding: 10px 20px;
            background-color: #e91e63;
            color: #ffffff;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            margin-bottom: 10px;
            border: none;
        }
        .posts {
            margin-top: 20px;
        }
        .post-card {
            background-color: #292929;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .post-card img, .post-card video {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 10px;
        }
        .post-card .date {
            font-size: 12px;
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Elpiss</h1>
            <a href="logout.php" class="logout-button">Sair</a>
        </header>
        <section class="create-post">
            <h2>Criar uma nova postagem:</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <textarea name="content" placeholder="Escreva algo..."></textarea>
                <div class="custom-upload mt-3">
                    <div class="custom-upload-btn">Escolher mídia</div>
                    <input type="file" name="media" accept="image/*,video/*">
                </div>
                <button type="submit" class="custom-submit-btn">Postar</button>
            </form>
        </section>
        <section class="posts">
            <h2>Todas as postagens:</h2>
            <?php if (!empty($posts)): ?>
                <?php foreach (array_reverse($posts) as $post): ?>
                    <div class="post-card">
                        <p><strong><?= htmlspecialchars($post['username']); ?></strong></p>
                        <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>
                        <?php if (isset($post['media'])): ?>
                            <?php if (preg_match('/\\.(jpg|jpeg|png|gif)$/i', $post['media'])): ?>
                                <img src="<?= htmlspecialchars($post['media']); ?>" alt="Media">
                            <?php elseif (preg_match('/\\.(mp4|webm|ogg)$/i', $post['media'])): ?>
                                <video controls>
                                    <source src="<?= htmlspecialchars($post['media']); ?>" type="video/mp4">
                                </video>
                            <?php endif; ?>
                        <?php endif; ?>
                        <span class="date"><?= htmlspecialchars($post['date']); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Sem posts no momento.</p>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>