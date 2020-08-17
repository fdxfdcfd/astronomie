<?php

use Post\Model\Category;

include_once 'app/code/Post/Model/Post.php';
$post = new Category();
$p = 1;
$posts = $post->getList('', 10, $p, 'DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Vietnam Weather</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/styles.css"/>
</head>
<body>
<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
    <div class="mb-3">Notice: Vietnam Weather Page - Under Construction</div>
    <div class="cover"></div>
    <header class="masthead mt-5">
        <div class="inner">
            <!--Đổi dòng tiêu đề to của page-->
            <h3 class="masthead-brand">Vietnam Weather <br/>for Amateur Astronomy Observatory</h3>
            <nav class="nav-masthead navbar navbar-expand-lg navbar-dark">
                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"> Stations </a>
                            <ul class="dropdown-menu bg-dark">
                                <li><a class="dropdown-item text-white" href="#"> AllSky</a></li>
                                <li><a class="dropdown-item text-white" href="#"> BloomSky </a></li>
                                <li><a class="dropdown-item text-white" href="#"> Ambient </a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"> Lab </a>
                            <ul class="dropdown-menu bg-dark">
                                <li><a class="dropdown-item text-white" href="#"> Apps </a></li>
                                <li><a class="dropdown-item text-white" href="#"> Books </a></li>
                                <li><a class="dropdown-item text-white" href="#"> Astro Basic</a></li>
                                <li><a class="dropdown-item text-white" href="#"> Weather Basic</a></li>
                            </ul>
                        </li>
                    </ul>
                </div> <!-- navbar-collapse.// -->
            </nav>
            <div class="masthead-brand">
                <!--Chỉnh sửa câu chú thích Page-->
                <p class="mt-2">Trang web cung cấp dự báo thời tiết dành cho quan sát thiên văn nghiệp dư tại Việt
                    nam</p>
            </div>
        </div>
    </header>
    <div class="cover mb-4"></div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="background-color:transparent">
            <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Posts</li>
        </ol>
    </nav>
    <main role="main">
        <h1 class="my-4 text-left">News</h1>
        <?php foreach ($posts as $post): ?>
            <?php
            $date = date('F j, Y', strtotime($post->updated_at));
            $time  = date('H:i', strtotime($post->updated_at));
            $subtitle = date('F j, Y | H:i', strtotime($post->updated_at));
            ?>
            <!-- Project One -->
            <div class="row">
                <div class="col-md-3">
                    <a href="./detail.php?id=<?= $post->post_id ?>">
                        <img class="img-fluid rounded mb-3 mb-md-0" src="./img/<?= $post->image ?>" alt="">
                    </a>
                </div>
                <div class="col-md-9">
                    <h3><a href="./detail.php?id=<?= $post->post_id ?>"><?= $post->title ?></a> <small style="color: #939393;"><?=$subtitle?></small></h3>
                    <p><?= $post->short_description ?></p>
                    <p><?= mb_strimwidth($post->content, 0, 300, "..."); ?></p>
                </div>
            </div>
            <!-- /.row -->

            <hr>
        <?php endforeach; ?>
        <div class="mt-5 d-flex flex-row text-left contact p-2">
            <div class="w-50" style="margin-right:2em;">
                <p style="color:white"><b>Lastest News</b></p>
                <div class="cover"></div>
                <ul class="text-white">
                    <?php foreach ($posts as $post): ?>
                        <?php
                        $time = strtotime($post->updated_at);
                        $post->updated_at = date('F j, Y | H:i', $time);
                        ?>
                        <li class="">
                            <a href="./detail.php?id=<?= $post->post_id ?>"><?= $post->updated_at ?> <br> <?= $post->title ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="w-50">
                <h2>Contact Us</h2>
                <form action="#" id='contactForm' method="post">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                               placeholder="Please type your name here">
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email address</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
                               placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.</small>
                    </div>
                    <div class="form-group">
                        <label for="inquiry">Your Inquiry</label>
                        <textarea class="form-control" id="inquiry" name="inquiry" rows="3"></textarea>
                    </div>
                    <button type="button" id='submitForm' class="btn btn-light mb-4">Submit</button>
                    <label style="display:none" id="success">Thank you for contacting us.<br>We will get back in touch
                        with you soon</label>
                </form>
            </div>
        </div>
        <div>
        </div>
    </main>

    <footer class="mastfoot mt-auto">
        <div class="text-center">
            <p class="small">Copyright © All rights reserved. Made by VietCAD Co., Ltd - info@vietcad.com<a href="#">.
                    Terms of use</a> | <a href="#">Privacy policy</a></p>
        </div>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v7.0&appId=163517574259703&autoLogAppEvents=1"
        nonce="IJeVUNEQ"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    $(document).ready(function () {
        $('#submitForm').click(function () {
            var url = 'http://weathervn.com/sendmail.php'
            var email = $('#email').val();
            var name = $('#name').val();
            var inquiry = $('#inquiry').val();
            $.ajax({
                method: "POST",
                url: url,
                data: {
                    "email": email,
                    "name": name,
                    "inquiry": inquiry,
                }
            })
                .done(function (msg) {
                    $('#success').show();
                }).always(function () {
                $('#contactForm').trigger("reset");
            });
        });
    });
</script>
</body>
</html>