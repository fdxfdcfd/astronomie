<?php
ob_start();
session_start();
$url = include 'config.php';
define('ADMIN_URL', $url);
if (!isset($_SESSION['username'])) {
    header("Location: " . ADMIN_URL . "login.php");
    die;
}
if (isset($_POST['submit']) && isset($_POST['title']) && isset($_POST['short_description']) && !empty($_POST['content'])) {
    include '../app/code/Post/Model/Post.php';
    $postModel = new \Post\Model\Post();
    if (isset($_FILES['image'])) {
        $imgName = uploadImage($_FILES['image'], '..'.DIRECTORY_SEPARATOR . 'img' .DIRECTORY_SEPARATOR, basename($_FILES['image']["name"]));
        if ($imgName) {
            $_POST['image'] = $imgName;
        }
    }
    $postModel->save($_POST);
}
if (isset($_POST['post_id']) && $_POST['post_id']) {
    header("Location: " . ADMIN_URL . "editPost.php?id=".$_POST['post_id']);
    die;
} else {
    header("Location: " . ADMIN_URL . "post.php");
    die;
}

function uploadImage($file, $dir, $filename) {
    $imgExtension = strtolower(pathinfo(basename($file["name"]), PATHINFO_EXTENSION));

    // Check file size
    if ($file["size"] > 20000000) {
        echo "Sorry, your file is too large.";
        return false;
    }

    // Allow certain file formats
    $allowImgType = ['jpg', 'png', 'jpeg', 'gif'];
    if (!in_array($imgExtension, $allowImgType)) {
        echo "Sorry, only JPG, JPEG, PNG, GIF & MP$ files are allowed.";
        return false;
    }
    if (move_uploaded_file($file["tmp_name"], $dir.$filename)) {
        return basename($file["name"]);
    } else {
        return false;
    }
}