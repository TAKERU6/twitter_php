<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
    $users = $db->prepare('SELECT * FROM users WHERE id=?');
    $users->execute(array($_SESSION['id']));
    $user = $users->fetch();
} else {
    header('Location: login.php');
    exit();
}

if (!empty($_POST)) {
    $posts = $db->prepare('INSERT INTO microposts SET content=?, user_id=?, reply_content_id=?, created_at=NOW()');
    $posts->execute(array($_POST['message'], $user['id'], $_REQUEST['reply']));
    header('Location: index.php');
    exit();
}

$page = $_REQUEST['page'];
if ($page === '') {
    $page = 1;
}
$page = max($page, 1);
$counts = $db->query('SELECT COUNT(*) FROM microposts');
$count = $counts->fetch();
$maxPage = ceil($count['COUNT(*)'] / 5);
$page = min($page, $maxPage);

$start = ($page - 1) * 5;

$posts = $db->prepare('SELECT u.name, u.picture, m.* FROM users u,
microposts m WHERE u.id=m.user_id ORDER BY m.created_at DESC LIMIT ?,5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

if (isset($_REQUEST['reply'])) {
    $response = $db->prepare('SELECT u.name, u.picture, m.* FROM users u, microposts m WHERE u.id=m.user_id AND m.id=?');
    $response->execute(array($_REQUEST['reply']));
    $table = $response->fetch();
    $content = '@' . $table['name'] . ' ' . $table['content'];
}
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
            <div style="text-align: right"><a href="logout.php">Logout</a></div>
            <form action="" method="post">
                <dl>
                    <dt>Hello, <?php print(htmlspecialchars($user['name'], ENT_QUOTES)); ?> Let's tweet!!</dt>
                    <dd>
                        <textarea name="message" cols="50" rows="5" required><?php print(htmlspecialchars($content, ENT_QUOTES)); ?></textarea>
                        <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['reply'], ENT_QUOTES)); ?>" />
                    </dd>
                </dl>
                <div>
                    <p>
                        <input type="submit" value="tweet" />
                    </p>
                </div>
            </form>

            <?php foreach ($posts as $post) : ?>
                <div class="msg">
                    <img src="member_picture/<?php print(htmlspecialchars($post[1], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>" />
                    <p><?php print(htmlspecialchars($post['content'], ENT_QUOTES)); ?><span class="name">（<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>)
                        </span>[<a href="index.php?reply=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">Re</a>]</p>
                    <p class="day"><a href="view.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"><?php print(htmlspecialchars($post['created_at'], ENT_QUOTES)); ?></a>
                        <?php if ($post['reply_content_id'] > 0) : ?>
                            <a href="view.php?id=<?php print(htmlspecialchars($post["reply_content_id"], ENT_QUOTES)); ?>">the original message</a>
                        <?php endif; ?>
                        <?php if ($_SESSION["id"] === $post["user_id"]) : ?>
                            <a href="delete.php?id=<?php print(htmlspecialchars($post["id"], ENT_QUOTES)); ?>" style="color:red;">[Delete]</a>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endforeach; ?>

            <ul class="paging">
                <?php if ($page > 1) : ?>
                    <li><a href="index.php?page=<?php print($page - 1) ?>">before</a></li>
                <?php else : ?>
                    <li>before</li>
                <?php endif; ?>
                <?php if ($page < $maxPage) : ?>
                    <li><a href="index.php?page=<?php print($page + 1) ?>">next</a></li>
                <?php else : ?>
                    <li>next</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>

</html>