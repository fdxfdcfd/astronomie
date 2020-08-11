<?php
$url = include 'config.php';
define('ADMIN_URL', $url);
ob_start();
session_start();
const USERNAME = 'weathervn.com';
const PASSWORD = 'vietcad@sg123';
if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password'])) {
    $msg = '';
    if ($_POST['username'] == USERNAME &&
        $_POST['password'] == PASSWORD) {
        $_SESSION['valid'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = USERNAME;
        header("Location: " . ADMIN_URL . "post.php");
    }else {
        $_SESSION['error'] = 'Wrong username or password';
        header("Location: " . ADMIN_URL . "login.php");
    }
}