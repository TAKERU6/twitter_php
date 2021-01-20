<?php
session_start();
require('dbconnect.php');

if ($_COOKIE['email'] !== '') {
    $email = $_COOKIE['email'];
}

if (!empty($_POST)) {
    $email = $_POST['email'];
    $login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');
    $login->execute(array($_POST['email'], sha1($_POST['password'])));
    $user = $login->fetch();
    if ($user) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['time'] = time();
        if ($_POST['save'] === 'on') {
            setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 14);
        }
        header('Location: index.php');
        exit();
    } else {
        $error['login'] = 'failed';
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Log In</title>
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>Let's Login</h1>
        </div>
        <div id="content">
            <div id="lead">
                <p>Please input your Name and your E-Mail</p>
                <p>Aren't you our member?</p>
                <p>&raquo;<a href="join/">Click here to register.</a></p>
            </div>
            <?php if ($error['login'] === 'failed') : ?>
                <p class="error">Failed login. Please try again.</p>
            <?php endif; ?>
            <form action="" method="post">
                <dl>
                    <dt>E-Mail</dt>
                    <dd>
                        <input type="email" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email, ENT_QUOTES)) ?>" required />
                    </dd>
                    <dt>Password</dt>
                    <dd>
                        <input type="password" name="password" size="35" minlnength="4" maxlength="50" value="" required />
                    </dd>
                    <dt></dt>
                    <dd>
                        <input id="save" type="checkbox" name="save" value="on">
                        <label for="save">Do you want to stay login?</label>
                    </dd>
                </dl>
                <div>
                    <input type="submit" value="Login" />
                </div>
            </form>
        </div>
        <div id="foot">
            <p><img src="images/txt_copyright.png" width="136" height="15" alt="(C) H2O Space. MYCOM" /></p>
        </div>
    </div>
</body>

</html>