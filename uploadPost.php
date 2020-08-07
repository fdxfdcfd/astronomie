<?php
const INPUT_IMG = 'bloomSkyImg';
const INPUT_VIDEO = 'bloomSkyVideo';
const IMG_DIR = 'img/';
const VIDEO_DIR = 'video/';
const IMG_NAME = 'bloomsky.jpg';
const VIDEO_NAME = 'bloomsky.mp4';
const USERNAME = 'weathervn.com';
const PASSWORD = 'vietcad@sg123';
$data = $_POST;
if (validate($data) && authorise($data['username'], $data['password'])) {
    if (isset($_FILES[INPUT_IMG]) && $_FILES[INPUT_IMG]['size']) {
        uploadImage($_FILES[INPUT_IMG], IMG_DIR, IMG_NAME);
    }

    if (isset($_FILES[INPUT_VIDEO]) && $_FILES[INPUT_VIDEO]['size']) {
        uploadVideo($_FILES[INPUT_VIDEO], VIDEO_DIR, VIDEO_NAME);
    }

    if (isset($_FILES['postVideo']) && $_FILES['postVideo']['size']) {
        uploadVideo($_FILES['postVideo'], VIDEO_DIR, 'postVideo.mp4');

    }
    if (isset($_FILES['postImage']) && $_FILES['postImage']['size']) {
        uploadImage($_FILES['postImage'], IMG_DIR, 'postImage.jpg');
    }
    if (isset($data['post']) &&  isset($data['postType']) && isset($data['postTitle'])) {
        writeDataToFile($data['postType'], $data['postTitle'], $data['post']);
    }
    header("Location: http://weathervn.com/upload.php");
    die();
}

function writeDataToFile($type, $postTitle, $text) {
    $myFile = fopen("postData.txt", "w") or die("Unable to open file!");
    fwrite($myFile, $type . "\n" . $postTitle . "\n" . $text);
    fclose($myFile);
}

function validate($data) {
    if (isset($data['submit']) && isset($data['username']) && isset($data['password'])) {
        return true;
    }
    return false;
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
        echo "The file " . basename($file["name"]) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

function uploadVideo($file, $dir, $filename) {
    $videoExtension = strtolower(pathinfo(basename($file["name"]), PATHINFO_EXTENSION));

    // Check file size
    if ($file["size"] > 20000000) {
        echo "Sorry, your file is too large.";
        return false;
    }
    $allowVideoType = ['mp4'];
    if (!in_array($videoExtension, $allowVideoType)) {
        echo "Sorry, only MP4 files are allowed.";
        return false;
    }
    if (move_uploaded_file($file["tmp_name"], $dir.$filename)) {
        echo "The file " . basename($file["name"]) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

function authorise($username, $password) {
    return $username == USERNAME && $password == PASSWORD;
}
?>