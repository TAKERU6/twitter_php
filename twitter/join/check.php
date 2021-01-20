<?php
session_start();
require('../dbconnect.php');

if (empty($_SESSION['join'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST)) {
    $statement = $db->prepare('INSERT INTO users SET name=?, email=?, password=?, picture=?, created_at=NOW()');
    $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
    ));
    unset($_SESSION['join']);

    header('Location: thanks.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirm</title>

    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>Confirm</h1>
        </div>

        <div id="content">
            <p>Please check your information and submit</p>
            <form action="" method="post">
                <input type="hidden" name="action" value="submit" />
                <dl>
                    <dt>Name</dt>
                    <dd>
                        <?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
                    </dd>
                    <dt>E-Mail</dt>
                    <dd>
                        <?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>
                    </dd>
                    <dt>Password</dt>
                    <dd>
                        【No Display】
                    </dd>
                    <dt>Picture</dt>
                    <dd>
                        <?php if ($_SESSION['join']['image'] !== '') : ?>
                            <img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>" width="300" height="200">
                        <?php endif ?>
                    </dd>
                </dl>
                <div><a href="index.php?action=rewrite">&laquo;&nbsp;rewrite</a> | <input type="submit" value="regist" /></div>
            </form>
        </div>

    </div>
</body>

</html>