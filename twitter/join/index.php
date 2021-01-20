<?php
session_start();
require('../dbconnect.php');

$user = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
$user->execute(array($_POST['email']));
$record = $user->fetch();
if ($record['cnt'] > 0) {
    $error['email'] = 'deplicate';
}

if (!empty($_POST) && empty($error)) {
    $image = date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
    $_SESSION['join'] = $_POST;
    $_SESSION['join']['image'] = $image;
    header('Location: check.php');
    exit();
}

if ($_REQUEST['action'] === 'rewrite' && isset($_SESSION['join'])) {
    $_POST = $_SESSION['join'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>

    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>Registration</h1>
        </div>

        <div id="content">
            <p>Please input all forms</p>
            <form action="" method="post" enctype="multipart/form-data">
                <dl>
                    <dt>Name<span class="required">required</span></dt>
                    <dd>
                        <input type="text" name="name" size="35" maxlength="255" value="" required />
                    </dd>
                    <dt>E-mail<span class="required">required</span></dt>
                    <dd>
                        <input type="email" name="email" size="35" maxlength="255" value="" required />
                        <?php if ($error['email'] === 'deplicate') : ?>
                            <p class="error"> This email is already saved.</p>
                        <?php endif; ?>
                    <dt>Password<span class="required">required</span></dt>
                    <dd>
                        <input type="password" name="password" size="10" maxlength="20" minlength="4" value="" required />
                    </dd>
                    <dt>Pictures</dt>
                    <dd>
                        <input type="file" name="image" size="35" value="test" accept="image/png,image/jpeg,image/gif" />
                    </dd>
                </dl>
                <div><input type="submit" value="Submit" /></div>
            </form>
        </div>
</body>

</html>