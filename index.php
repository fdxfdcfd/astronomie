<?php
$type = '';
$postContent = '';
if (file_exists("postData.txt")) {
    if ($myFile = fopen("postData.txt", "r")) {
        $type = fgets($myFile);
        $postContent = fgets($myFile);
        fclose($myFile);
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Vietnam Weather</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet/less" type="text/css" href="css/styles.less" />
</head>
<body>
<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
    <div class="mb-3">Notice: Vietnam Weather Page - Under Construction</div>
    <div class="cover"></div>
    <header class="masthead mb-5 mt-5">
        <div class="inner">
			<!--Đổi dòng tiêu đề to của page-->
            <h3 class="masthead-brand">Vietnam Weather <br/>for Amateur Astronomy Observatory</h3>
            <nav class="nav nav-masthead justify-content-center">
                <a class="nav-link active" href="#">Home</a>
                <a class="nav-link" href="#">Features</a>
                <a class="nav-link" href="#">Contact</a>
            </nav>
        </div>
    </header>
    <main role="main" class="text-center">
        <div class="cover-top">
            <div class="cover mb-4"></div>
			<!--Chỉnh sửa câu chú thích Page-->
            <p class="mt-2 mb-4">Trang web cung cấp dự báo thời tiết dành cho quan sát thiên văn nghiệp dư tại Việt nam</p>
            <div class="cover"></div>
        </div>

<!--Code chia thành 2 hình lớn width=100-->
        <div class="d-flex flex-row flex-wrap mt-2 mb-2">
            <div class="p-4 w-50">
                <img class="w-100" src="https://api.sat24.com/animated/TH/visual/3/SE%20Asia%20Standard%20Time/7560706" alt="">
                <div class="fb-share-button"
                     data-href="https://api.sat24.com/animated/TH/visual/3/SE%20Asia%20Standard%20Time/7560706"
                     data-layout="button">
                </div>
            </div>
            <div class="p-4 w-50">
                <img class="w-100" src="https://api.sat24.com/animated/TH/infraPolair/3/SE%20Asia%20Standard%20Time/7417866" alt="">
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
                <P>Realtime BloomSky Image</P>
                <img class="w-100 h-100" src="./img/bloomsky.jpg" type="video/mp4"alt="real time bllomsky">
            </div>
            <div class="p-4 col-sm-6" >
                <P>Realtime BloomSky Time-lapse</P>
                <video class="w-100 h-100 border border-white" src="./video/bloomsky.mp4" type="video/mp4" alt="" controls></video>
            </div>
            <div class="p-4 col-sm-6">
                <div class="small">Update every 10 minutes</div>
                <div class="fb-share-button"
                     data-href="./img/bloomsky.jpg"
                     data-layout="button">
                </div>
            </div>
            <div class="p-4 col-sm-6">
                <div class="small">Update every 10 minutes</div>
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
        <div class="post">
            <?php if ($postContent != ''): ?>
                <p class="mb-5 mt-3"><?= $postContent ?></p>
            <?php endif;?>
            <?php if ($type != ''): ?>
                <?php if ($type != 'image'): ?>
                    <img style="max-height:315px" src="./img/postImage.jpg" alt="">
                <?php else: ?>
                    <video class="w-100 h-100 border border-white" src="./video/postVideo.mp4" type="video/mp4" alt="" controls></video>
                <?php endif;?>
            <?php endif;?>
        </div>
        <div class="cover mt-3 mb-3"></div>
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
        <div class="cover mt-3 mb-3"></div>
        <div class="mb-5">
            <p class="mt-5 mb-5">Seeing conditions Saigon, Vietnam</p>
			
			<!--Link tọa độ cũ là https://clearoutside.com/forecast/51.17/4.31-->
			
            <a href="https://clearoutside.com/forecast/10.81/106.68"><img src="https://clearoutside.com/forecast_image_large/10.81/106.68/forecast.png"></a>
        </div>
		<div class="cover mt-3 mb-3"></div>
		<div class="fb-share-button" data-href="http://weathervn.com/" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fweathervn.com%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Chia sẻ</a></div>
        <div class="mt-5 d-flex flex-row text-left contact p-2">
            <div class="w-50">
                <p>Active</p>
                <div class="cover"></div>
                <ul class="">
                    <li class="">Event</li>
                    <!--<li class="">Cras justo odio</li>
                    <li class="">Cras justo odio</li>
                    <li class="">Cras justo odio</li>-->
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
<script src="//cdn.jsdelivr.net/npm/less" ></script>
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
	$('img').click(function(){
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