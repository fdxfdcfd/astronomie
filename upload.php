<?php
const INPUT_IMG = 'bloomSkyImg';
const INPUT_VIDEO = 'bloomSkyVideo';
const IMG_DIR = 'img/';
const VIDEO_DIR = 'video/';
const IMG_NAME = 'bloomsky.jpg';
const VIDEO_NAME = 'bloomsky.mp4';
const USERNAME = 'weathervn.com';
const PASSWORD = 'Tai1WvnEmY2Ua49';
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
        writeDataToFile('video', $data['post']);
    }
    if (isset($_FILES['postImage']) && $_FILES['postImage']['size']) {
        uploadImage($_FILES['postImage'], IMG_DIR, 'postImage.jpg');
        writeDataToFile('image', $data['post']);
    }
}

function writeDataToFile($type, $text) {
    $myFile = fopen("postData.txt", "w") or die("Unable to open file!");
    fwrite($myFile, $type . "\n" . $text);
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
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        /* BASIC */

        html {
            background-color: #56baed;
        }

        body {
            font-family: "Poppins", sans-serif;
            height: 100vh;
            background-color: #56baed;
        }

        a {
            color: #92badd;
            display:inline-block;
            text-decoration: none;
            font-weight: 400;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            display:inline-block;
            margin: 40px 8px 10px 8px;
        }

        h1.active {
            color: #0d0d0d;
            border-bottom: 2px solid #5fbae9;
        }

        /* STRUCTURE */

        .wrapper {
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            min-height: 100%;
            padding: 20px;
        }

        #formContent {
            -webkit-border-radius: 10px 10px 10px 10px;
            border-radius: 10px 10px 10px 10px;
            background: #fff;
            margin: 0 auto;
            width: 90%;
            max-width: 800px;
            position: relative;
            -webkit-box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
            box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
            text-align: center;
            padding: 15px 32px;
        }

        /* FORM TYPOGRAPHY*/

        input[type=button], input[type=submit], input[type=reset]  {
            background-color: #56baed;
            border: none;
            color: white;
            padding: 15px 80px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            text-transform: uppercase;
            font-size: 13px;
            -webkit-box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
            box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
            -webkit-border-radius: 5px 5px 5px 5px;
            border-radius: 5px 5px 5px 5px;
            margin: 5px 20px 40px 20px;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            -ms-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover  {
            background-color: #39ace7;
        }

        input[type=button]:active, input[type=submit]:active, input[type=reset]:active  {
            -moz-transform: scale(0.95);
            -webkit-transform: scale(0.95);
            -o-transform: scale(0.95);
            -ms-transform: scale(0.95);
            transform: scale(0.95);
        }

        input[type=file] {
            background-color: #f6f6f6;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 5px;
            width: 85%;
        }
        select {
            background-color: #f6f6f6;
            width: 85%;
        }

        input[type=text],input[type=password] {
            background-color: #f6f6f6;
            color: #0d0d0d;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 5px;
            width: 85%;
            border: 2px solid #f6f6f6;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            -ms-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
            -webkit-border-radius: 5px 5px 5px 5px;
            border-radius: 5px 5px 5px 5px;
        }

        input[type=text]:focus, input[type=password]:focus {
            background-color: #fff;
            border-bottom: 2px solid #5fbae9;
        }

        /* ANIMATIONS */

        /* Simple CSS3 Fade-in-down Animation */
        .fadeInDown {
            -webkit-animation-name: fadeInDown;
            animation-name: fadeInDown;
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
        }

        @-webkit-keyframes fadeInDown {
            0% {
                opacity: 0;
                -webkit-transform: translate3d(0, -100%, 0);
                transform: translate3d(0, -100%, 0);
            }
            100% {
                opacity: 1;
                -webkit-transform: none;
                transform: none;
            }
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                -webkit-transform: translate3d(0, -100%, 0);
                transform: translate3d(0, -100%, 0);
            }
            100% {
                opacity: 1;
                -webkit-transform: none;
                transform: none;
            }
        }

        /* Simple CSS3 Fade-in Animation */
        @-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        @-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

        .fadeIn {
            opacity:0;
            -webkit-animation:fadeIn ease-in 1;
            -moz-animation:fadeIn ease-in 1;
            animation:fadeIn ease-in 1;

            -webkit-animation-fill-mode:forwards;
            -moz-animation-fill-mode:forwards;
            animation-fill-mode:forwards;

            -webkit-animation-duration:1s;
            -moz-animation-duration:1s;
            animation-duration:1s;
        }

        .fadeIn.first {
            -webkit-animation-delay: 0.4s;
            -moz-animation-delay: 0.4s;
            animation-delay: 0.4s;
        }

        .fadeIn.second {
            -webkit-animation-delay: 0.6s;
            -moz-animation-delay: 0.6s;
            animation-delay: 0.6s;
        }

        .fadeIn.third {
            -webkit-animation-delay: 0.8s;
            -moz-animation-delay: 0.8s;
            animation-delay: 0.8s;
        }

        .fadeIn.fourth {
            -webkit-animation-delay: 1s;
            -moz-animation-delay: 1s;
            animation-delay: 1s;
        }

        /* Simple CSS3 Fade-in Animation */
        .underlineHover:after {
            display: block;
            left: 0;
            bottom: -10px;
            width: 0;
            height: 2px;
            background-color: #56baed;
            content: "";
            transition: width 0.2s;
        }

        .underlineHover:hover {
            color: #0d0d0d;
        }

        .underlineHover:hover:after{
            width: 100%;
        }

        /* OTHERS */

        *:focus {
            outline: none;
        }
    </style>
</head>
<body>
<main class="container wrapper">
    <div id="formContent">
        <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="fadeIn first">
            <h1>Upload Image</h1>
        </div>
            <input type="text" id="login" class="fadeIn second" name="username" placeholder="username" required>
            <input type="password" id="password" class="fadeIn third" name="password" placeholder="password" required>
            <div class="form-group">
                <div class="form-group">
                    <label for="bloomSkyImg">Realtime BloomSky Image</label>
                    <input type="file" name="bloomSkyImg" accept="image/x-png,image/gif,image/jpeg" class="form-control-file" id="bloomSkyImg">
                </div>
                <div class="form-group">
                    <label for="bloomSkyVideo">Realtime BloomSky Time-lapse</label>
                    <input type="file" name="bloomSkyVideo" accept="video/mp4" class="form-control-file" id="bloomSkyVideo">
                </div>
                <div class="form-group">
                    <label for="postType">Post Type</label>
                    <select class="form-control" id="postType" name="postType">
                        <option value="image" selected>Image</option>
                        <option value="video">Video</option>
                    </select>
                    <input type="file" name="postVideo" id="postVideo" accept="video/mp4" class="form-control-file">
                    <input type="file" name="postImage" id="postImage" accept="image/x-png,image/gif,image/jpeg" class="form-control-file">
                    <textarea class="form-control" id="post" name="post" rows="3"></textarea>
                </div>
            </div>
            <input type="submit" class="fadeIn fourth" name="submit" value="Log In">
        </form>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    $( document ).ready(function() {
        var select = $('#postType'),
            image = $('#postImage'),
            video = $('#postVideo');
        select.change(function(){
            if ($(this).val() == 'image') {
                image.show();
                image.prop("disabled", false);
                video.hide();
                video.prop("disabled", true);
            } else {
                image.hide();
                image.prop("disabled", true);
                video.show();
                video.prop("disabled", false);
            }
        });
        select.change();
    });
</script>
</body>
</html>