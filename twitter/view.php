<?php
require('dbconnect.php');

if (empty($_REQUEST['id'])) {
    header('Location: index.php');
    exit();
}

$posts = $db->prepare('SELECT u.name, u.picture, m.* FROM users u, microposts m WHERE u.id=m.user_id AND m.id=?');
$posts->execute(array($_REQUEST['id']));
$post = $posts->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter</title>

    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>Twitter</h1>
        </div>
        <div id="content">
            <p>&laquo;<a href="index.php">Back to news feed</a></p>

            <?php if (isset($post)) : ?>
                <div class="msg">
                    <img src="member_picture/<?php print(htmlspecialchars($post[1], ENT_QUOTES)); ?>" width="300" height="200" />
                    <p><?php print(htmlspecialchars($post['content'], ENT_QUOTES)); ?>
                        <span class="name">（<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>）</span></p>
                    <p class="day"><?php print(htmlspecialchars($post['created_at'], ENT_QUOTES)); ?></p>
                </div>
            <?php else : ?>
                <p>Failed. Maybe this post is deleted...</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>