<?php

use Post\Model\Category;

include_once 'app/code/Post/Model/Post.php';
$postModel = new Category();
$p = 1;
$posts = $postModel->getList('', 10, $p, 'DESC');
if (isset($_GET['id'])) {
    $postData = $postModel->get($_GET['id']);
} else {
    $postData = reset($posts);
}
$time = date('F j, Y | H:i', strtotime($postData->updated_at));
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
            <li class="breadcrumb-item"><a href="./list.php">Posts</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$postData->title?>></li>
        </ol>
    </nav>
    <main role="main">
        <div class="container">

            <div class="row">

                <!-- Post Content Column -->
                <div class="col-lg-12">

                    <!-- Title -->
                    <h1 class="mt-4"><?= $postData->title ?></h1>

                    <!-- Author -->
                    <p class="lead">
                        by
                        <a href="#">Admin</a>
                    </p>

                    <hr>

                    <!-- Date/Time -->
                    <p>Posted on <?= $time ?></p>

                    <hr>

                    <!-- Preview Image -->
                    <img class="img-fluid rounded" src="./img/<?= $postData->image ?>" alt="">

                    <hr>

                    <!-- Post Content -->
                    <!--                    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero, obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia corporis eligendi eos magni recusandae laborum minus inventore?</p>-->
                    <!--                    <blockquote class="blockquote">-->
                    <!--                        <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>-->
                    <!--                        <footer class="blockquote-footer">Someone famous in-->
                    <!--                            <cite title="Source Title">Source Title</cite>-->
                    <!--                        </footer>-->
                    <!--                    </blockquote>-->

                    <p><?= $postData->content ?></p>

                    <hr>
                </div>

            </div>
            <!-- /.container -->


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
        $('img').click(function () {
            fbShare(this.src, 'Fb Share', 'Facebook share popup', this.src, 520, 350);
        });
    });

    function fbShare(url, title, descr, image, winWidth, winHeight) {
        var winTop = (screen.height / 2) - (winHeight / 2);
        var winLeft = (screen.width / 2) - (winWidth / 2);
        window.open('http://www.facebook.com/sharer.php?s=100&p[title]=' + title + '&p[summary]=' + descr + '&p[url]=' + url + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
    }
</script>
</body>
</html>