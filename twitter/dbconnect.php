<?php
try {
    $db = new PDO('mysql:dbname=twitter;host=localhost;charset=utf8', 'root', 'root');
} catch (PDOException $e) {
    print('DB connect error:' . $e->getMessage());
}
