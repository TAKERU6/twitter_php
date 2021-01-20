<?php
session_start();
require('dbconnect.php');

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $posts = $db->prepare('SELECT * FROM microposts WHERE id=?');
    $posts->execute(array($id));
    $post = $posts->fetch();

    if ($post['user_id'] === $_SESSION['id']) {
        $delete = $db->prepare('DELETE FROM microposts WHERE id=?');
        $delete->execute(array($id));
    }

    header('Location: index.php');
    exit();
}
