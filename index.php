<?php
include_once 'app/code/Post/Model/Post.php';
$post = new \Post\Model\Category();
$p = 1;
$posts = $post->getList('', 10, $p, 'DESC');
$postData = reset($posts);
$time = date('F j, Y | H:i',strtotime($postData->updated_at));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Vietnam Weather</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
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
                        <li class="nav-item"> <a class="nav-link active" href="#">Home</a> </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">  Stations </a>
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
                <p class="mt-2">Trang web cung cấp dự báo thời tiết dành cho quan sát thiên văn nghiệp dư tại Việt nam</p>
            </div>
        </div>
    </header>
    <div class="cover mb-4"></div>
    <main role="main" class="text-center">
        <div class="row text-white p-4 text-left">
            <?php if ($postData): ?>
                <div class="col-md-12">
                    <div class="col-md-12" style="background: #333;">
                        <div class="card flex-md-row mb-4 box-shadow h-md-250 text-white" style="background: #333;">
                            <div class="card-body d-flex flex-column align-items-start w-50">
                                <strong class="d-inline-block mb-2 text-primary">Update News</strong>
                                <div class="mb-1 text-muted"><?= $time ?></div>
								<h3 class="mb-0">
                                    <a href="./detail.php?id=<?= $postData->post_id ?>"><?= $postData->title ?></a>
                                </h3>                                
                                <p class="card-text"><?= $postData->short_description ?></p>
                                <p class="card-text"><?= mb_strimwidth($postData->content, 0, 300, "..."); ?></p>
                                <!--<a href="#">Continue reading</a>-->
                            </div>
                            <div class="p-4 w-50 d-flex">
                                <div class="row align-self-center w-100">
                                    <img class="w-100" data-src="./img/<?=$postData->image ?>" alt="Thumbnail [200x250]" style="width: 200px; height: 250px;" src="./img/<?=$postData->image ?>" data-holder-rendered="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="text-center w-100">
                <div class="fb-share-button"
                     data-href="./img/<?=$postData->image ?>"
                     data-layout="button">
                </div>
            </div>
            <?php endif;?>
        </div>
<!--Code chia thành 2 hình lớn width=100-->
        <div class="d-flex flex-row flex-wrap mt-2 mb-2">
            <div class="p-4 w-50">
				<img style="margin-bottom:1.5em;" class="w-100" src="https://api.sat24.com/animated/TH/visual/3/SE%20Asia%20Standard%20Time/7560706" alt="">
				<br/>
				<div class="fb-share-button"
                     data-href="https://api.sat24.com/animated/TH/visual/3/SE%20Asia%20Standard%20Time/7560706"
                     data-layout="button">
				</div>
            </div>
            <div class="p-4 w-50">
				<img style="margin-bottom:1.5em;"class="w-100" src="https://api.sat24.com/animated/TH/infraPolair/3/SE%20Asia%20Standard%20Time/7417866" alt="">
				<br/>
                <div class="fb-share-button"
                     data-href="https://api.sat24.com/animated/TH/infraPolair/3/SE%20Asia%20Standard%20Time/7417866"
                     data-layout="button">
                </div>
            </div>
        </div>
        <div class="cover-top">
            <div class="cover mb-4"></div>
            <!--Chỉnh sửa câu chú thích Page-->
            <p class="mt-2 mb-4">Trang web cung cấp dự báo thời tiết dành cho quan sát thiên văn nghiệp dư tại Việt nam</p>
            <div class="cover"></div>
        </div>
<!--End of Code chia thành 2 hình lớn width=100-->
        <div class="p-4">
            <div class="embed-responsive embed-responsive-16by9 p-4">
                <iframe class="embed-responsive-item" src="https://embed.windy.com/embed2.html?lat=16.215&lon=106.743&detailLat=17.393&detailLon=106.479&width=650&height=650&zoom=5&level=surface&overlay=wind&product=ecmwf&menu=&message=&marker=&calendar=now&pressure=true&type=map&location=coordinates&detail=&metricWind=km%2Fh&metricTemp=%C2%B0C&radarRange=-1" frameborder="0">
				</iframe>
            </div>
			
            <div class="p-4">
                <div class="fb-share-button"
                     data-href="https://embed.windy.com/embed2.html?lat=16.215&lon=106.743&detailLat=17.393&detailLon=106.479&width=650&height=650&zoom=5&level=surface&overlay=wind&product=ecmwf&menu=&message=&marker=&calendar=now&pressure=true&type=map&location=coordinates&detail=&metricWind=km%2Fh&metricTemp=%C2%B0C&radarRange=-1"
                     data-layout="button">
                </div>
            </div>
        </div>
<!--Chỉnh sửa 2 hình RealTime BllomSky Camera-->
        <div class="cover-top">
            <div class="cover mb-4"></div>
            <!--Chỉnh sửa câu chú thích Page-->
            <p class="mt-2 mb-4">Trang web cung cấp dự báo thời tiết dành cho quan sát thiên văn nghiệp dư tại Việt nam</p>
            <div class="cover"></div>
        </div>
        <div class="d-flex flex-row mt-2 mb-2 flex-wrap">
            <div class="p-4 col-sm-6">
                <P>Realtime BloomSky Image: Go Vap, Saigon</P>
                <img class="w-100 h-100" src="./img/bloomsky.jpg" type="video/mp4"alt="real time bllomsky">
            </div>
            <div class="p-4 col-sm-6" >
                <P>Realtime BloomSky Time-lapse: Go Vap, Saigon</P>
                <video class="w-100 h-100 border border-white" src="./video/bloomsky.mp4" type="video/mp4" alt="" controls></video>
            </div>
            <div class="p-4 col-sm-6">
                <div class="small">Update every hour</div>
                <div class="fb-share-button"
                     data-href="./img/bloomsky.jpg"
                     data-layout="button">
                </div>
            </div>
            <div class="p-4 col-sm-6">
                <div class="small">Update every day</div>
                <div class="fb-share-button"
                     data-href="./video/bloomsky.mp4"
                     data-layout="button">
                </div>
            </div>
        </div>
        <div class="cover-top mb-4 mt-2">
            <div class="cover mb-4"></div>
            <!--Chỉnh sửa câu chú thích Page-->
            <p class="mt-2 mb-4">Trang web cung cấp dự báo thời tiết dành cho quan sát thiên văn nghiệp dư tại Việt nam</p>
            <div class="cover"></div>
        </div>
<!--        <div class="">-->
<!--            <p>AAG-Cloudwatcher, Saigon Vietnam (coming soon)</p>-->
<!--            <iframe src="http://78.23.108.227:10800" width="800" height="1554" scrolling="NO" img="" border="0"> </iframe>-->
<!--        </div>-->
        
        <!-- An di AAG<div class="cover mt-3 mb-3"></div>		
        <div class="mb-2">
            <p class="mt-2">Magnitude of the sky background in Magn./Arc.sec²</p>
            <div class="small">Update every 15 minutes</div>
        </div>
        <div class="cover mt-3 mb-3"></div>
        <div class="text-center mt-3 mb-3">
            <p>AAG-Cloudwatcher</p>
            <div class="d-flex flex-row justify-content-center mt-2 w-100">
                <div class="p-2 w-25">
                    <img class="w-100 h-100" src="./img/AAG_ImageCloudCondition.png" alt="">
                </div>
                <div class="p-2 w-25">
                    <img class="w-100 h-100" src="./img/AAG_ImageDayCondition.png" alt="">
                </div>
            </div>
            <div class="d-flex flex-row justify-content-center mt-2 w-100">
                <div class="p-2 w-25">
                    <img class="w-100 h-100" src="./img/AAG_ImageRainCondition.png" alt="">
                </div>
                <div class="p-2 w-25">
                    <img class="w-100 h-100" src="./img/AAG_ImageTemperature.png" alt="">
                </div>
                <div class="p-2 w-25">
                    <img class="w-100 h-100" src="./img/AAG_ImageWindCond.png" alt="">
                </div>
            </div>
        </div>
        <div class="cover mt-3 mb-3"></div>-->
        <div class="mb-5">
            <p class="mt-5 mb-5">Seeing conditions Saigon, Vietnam</p>
			
			<!--Link tọa độ cũ là https://clearoutside.com/forecast/51.17/4.31-->			
            <a href="https://clearoutside.com/forecast/10.81/106.68"><img src="https://clearoutside.com/forecast_image_large/10.81/106.68/forecast.png"></a>			
        </div>
		<div class="fb-share-button" 
		data-href="https://clearoutside.com/forecast_image_large/10.81/106.68/forecast.png"
        data-layout="button">
         </div>
		 <div class="cover mt-3 mb-3"></div>
		
		
        <div class="mt-5 d-flex flex-row text-left contact p-2">
            <div class="w-50" style="margin-right:2em;">
                <p style="color:white"><b>Lastest News - <a href="http://weathervn.com/list.php" style="font-size:12px;">Click here for older news</a></b></p>
                <div class="cover"></div>
                <ul class="text-white">
                   <?php foreach ($posts as $post):?>
                       <?php
                       $time = strtotime($post->updated_at);
                       $post->updated_at = date('F j, Y | H:i',$time);
                       ?>
                       <li class="">
                           <a href="./detail.php?id=<?= $post->post_id ?>"><?= $post->updated_at ?> <br> <?= $post->title ?></a>
                       </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="w-50">
                <h2>Contact Us</h2>
                <form action="#" id='contactForm' method="post">
					<div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Please type your name here">
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email address</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="inquiry">Your Inquiry</label>
						<textarea class="form-control" id="inquiry" name="inquiry" rows="3"></textarea>
                    </div>
                    <button type="button" id='submitForm' class="btn btn-light mb-4">Submit</button>
					 <label style="display:none" id="success">Thank you for contacting us.<br>We will get back in touch with you soon</label>
                </form>
            </div>
        </div>
		<div>	
		</div>
    </main>

    <footer class="mastfoot mt-auto">
        <div class="text-center">
            <p class="small">Copyright © All rights reserved. Made by VietCAD Co., Ltd - info@vietcad.com<a href="#">. Terms of use</a> | <a href="#">Privacy policy</a></p>
        </div>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v7.0&appId=163517574259703&autoLogAppEvents=1" nonce="IJeVUNEQ"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
$( document ).ready(function() {
	$('#submitForm').click(function(){
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
		.done(function( msg ) {
			$('#success').show();
		}).always(function() {
			$('#contactForm').trigger("reset");
		});
	});
	// $('img').click(function(){
    //     fbShare(this.src, 'Fb Share', 'Facebook share popup', this.src, 520, 350);
    // });
});

// function fbShare(url, title, descr, image, winWidth, winHeight) {
//     var winTop = (screen.height / 2) - (winHeight / 2);
//     var winLeft = (screen.width / 2) - (winWidth / 2);
//     window.open('http://www.facebook.com/sharer.php?s=100&p[title]=' + title + '&p[summary]=' + descr + '&p[url]=' + url + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
// }
</script>
</body>
</html>