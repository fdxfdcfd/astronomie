<?php
ob_start();
session_start();
$url = include 'config.php';
define('ADMIN_URL', $url);
if (!isset($_SESSION['username'])) {
    header("Location: " . ADMIN_URL . "login.php");
    die;
}
if (isset($_GET['post_id']) && $_GET['post_id']) {
    include '../app/code/Post/Model/Post.php';
    $postModel = new \Post\Model\Category();
    $postModel->delete($_GET['post_id']);

}
header("Location: " . ADMIN_URL . "post.php");
die;