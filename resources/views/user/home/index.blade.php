
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $title }}</title>
    <link rel="icon" href="./img/favicon.ico"/>
    <meta name="description" content="Thông tin khách hàng bình luận gồm : Tên, Uid, Số điện thoại, nội dung bình luận, thời gian bình luận, link Facebook....Hệ thống hoạt động xuyên suốt 24/24h "/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Phần mềm quét Comment ẩn bài quảng cáo trên Facebook"/>
    <meta property="og:description" content="Thông tin khách hàng bình luận gồm : Tên, Uid, Số điện thoại, nội dung bình luận, thời gian bình luận, link Facebook....Hệ thống hoạt động xuyên suốt 24/24h"/>
    <meta property="og:image" content="https://laysdt.top/img/fdata-facebook-ads.png"/>
    <meta property="og:image:alt" content="Phần mềm quét Comment ẩn bài quảng cáo trên Facebook" />
</head>
<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;700;800&display=swap');
        *{padding:0;margin:0;box-sizing:border-box;}
        img{vertical-align:middle;}
        .clear{clear:both;}
        #container{width:100%;margin:0 auto;}
        .content_one{background-image:url(https://toolquet.com/images/bg1.png);background-position:-111px 900px;}
        .contenta{max-width:90%;margin:0 auto;padding:80px 10px 10px 10px;}
        .div50{width:45%;}
        .div50Left{float:left;}
        .div50right{width:55%;float:right;padding-left:50px;}
        .div50Left h1{font-family:'Montserrat',sans-serif;font-weight:800;color:#fff;font-size:50px;}
        .div50Left h2{font-family:'Montserrat',sans-serif;font-weight:700;color:#fff;font-size:22px;padding:20px 0px 45px 0px;line-height:35px;}
        .div50Left p{font-family:'Montserrat',sans-serif;font-weight:700;font-size:15px;color:#fff;margin-bottom:12px;}
        .adangkyngay{font-family:"Montserrat", Sans-serif;font-size:20px;font-weight:700;text-transform:uppercase;text-decoration:none;fill:#FFFFFF;color:#FFFFFF;background-color:#FF2E48;border-radius:50px 50px 50px 50px;padding:20px 50px 20px 50px;margin-top:20px;display:inline-block;}
        .content_tow{max-width:1300px;margin:0 auto;padding:50px 10px;text-align:center;}
        .content_tow h1{font-family:'Montserrat',sans-serif;font-weight:800;color:#ff6c14;font-size:40px;text-align:center;padding:15px 10px 30px 10px;}
        .content_tow h2{font-family:'Montserrat',sans-serif;font-weight:700;color:#000;font-size:30px;text-align:center;}
        .ptietkiem{font-size:20px;margin-bottom:30px;font-family:'Montserrat',sans-serif;font-weight:700;line-height:35px;}
        .divGoi{width:30%;display:inline-block;margin:0px 10px;padding:20px 10px 30px 10px;text-align:center;box-shadow:0px -15px 35px 5px #d9d9db;border-radius:10px;}
        .divGoi p.p1{font-family:'Montserrat',sans-serif;font-weight:800;font-size:20px;padding:10px 5px;}
        .divGoi p.p2{font-family:'Montserrat',sans-serif;font-weight:800;font-size:30px;color:#f00;padding-bottom:20px;}
        .divGoi p.p3{text-align:left;font-size:16px;font-family:'Montserrat',sans-serif;font-weight:700;padding-bottom:10px;}
        .divGoiCamket{max-width:1000px;display:inline-block;margin:0px 10px;padding:20px 20px 30px 20px;text-align:center;box-shadow:0px -15px 35px 5px #d9d9db;border-radius:10px;}
        .divGoiCamket .img{margin-bottom:20px;}
        .divGoiCamket p.p1{font-family:'Montserrat',sans-serif;font-weight:800;font-size:35px;padding:30px 5px;}
        .divGoiCamket p.p3{text-align:left;font-size:16px;font-family:'Montserrat',sans-serif;font-weight:700;padding-bottom:10px;line-height:30px;}
        .footer{text-align:center;font-family:'Montserrat',sans-serif;font-weight:700;font-size:20px;line-height:35px;background:#009688;color:#fff;padding:30px 20px;}
        .container{position:relative;}
        .mySlides{display:none;border:10px solid #0f9d63;}
        .cursor{cursor:pointer;}
        .prev,.next{cursor:pointer;position:absolute;top:40%;width:auto;padding:16px;margin-top:-50px;color:white;font-weight:bold;font-size:20px;border-radius:0 3px 3px 0;user-select:none;-webkit-user-select:none;background-color:#0f9d63;}
        .prev{left:0;}
        .next{right:0;}
        .numbertext{background:#0f9d63;;color:#fff;font-size:12px;padding:15px 15px;position:absolute;top:0;font-family:'Montserrat',sans-serif;font-weight:700;}
        .caption-container{text-align:center;background-color:#0f9d63;padding:10px 15px;color:white;font-family:'Montserrat',sans-serif;font-weight:700;}
        .row{text-align:center;}
        .row:after{content:"";display:table;clear:both;}
        .column{display:inline-block;width:100px;border:1px solid #4CAF50;}
        .demo{opacity:0.6;}
        .active,.demo:hover{opacity:1;}
        @media only screen and (max-width:1000px){
            .div50{width:100%;}
            .div50right{padding-left:0;padding-top:50px;}
            .div50Left h1,.div50Left h2{text-align:center;}
            .adangkyngay{margin:0 auto;display:table;}
            .content_tow{padding:30px 10px;}
            .content_tow h1{font-size:25px;}
            .mySlides{border:2px solid #0f9d63;}
            .ptietkiem{font-size:16px;}
            .content_tow h1{padding:15px 10px 15px 10px;}
            .divGoi{width:90%;margin-bottom:20px;}
            #iframeDangky{width:350px!important;}
        }
        @media only screen and (max-width:550px){
            .div50Left h1{font-size:22px;}
            .div50Left h2{font-size:15px;line-height:23px;padding-bottom: 30px;}
            .div50Left p{font-size:14px;margin-bottom:6px;font-weight:100;}
            img[alt="sign-check-icon"]{width: 20px;}
            .adangkyngay{font-size: 14px;padding: 15px 20px;margin-top: 20px;}
        }
    </style>
	<div id="container">
        <div class="content_one">
            <div class="contenta">
                <div class="div50 div50Left">
                    <h1>QUÉT COMMENT TỪ QUẢNG CÁO FACEBOOK</h1>
                    <h2>GIẢI PHÁP TIẾP CẬN KHÁCH HÀNG MỤC TIÊU CHÍNH XÁC, MARKETING 0 ĐỒNG TRÊN FACEBOOK</h2>
					<p><img src="https://laysdt.top/img/sign-check-icon.png" width="30" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Có ngay khách hàng khi họ Comment vào bài quảng cáo Facebook</p>
                    <p><img src="https://laysdt.top/img/sign-check-icon.png" width="30" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Quét được tất cả những Comment ẩn, nhanh nhất</p>
                    <p><img src="https://laysdt.top/img/sign-check-icon.png" width="30" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Có thể chủ động khách hàng từ bài quảng cáo bất kỳ</p>
                    <p><img src="https://laysdt.top/img/sign-check-icon.png" width="30" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Thông tin khách hàng đầy đủ và chi tiết, có thể gọi điện tư vấn hay nhắn tin</p>
                    <p><img src="https://laysdt.top/img/sign-check-icon.png" width="30" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Hệ thống  hoạt động xuyên suốt 24/24 nên tất cả khách hàng tiềm năng không bị sót</p>
                    <p><img src="https://laysdt.top/img/sign-check-icon.png" width="30" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Hỗ trợ bất kỳ nghành nghề, lĩnh vực nào</p>
                    <p><img src="https://laysdt.top/img/sign-check-icon.png" width="30" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Đặc biệt không phải mất chi phí và thời gian chạy quảng cáo Facebook</p>
                    <a class="adangkyngay" target="_blank" href="https://toolquet.com/user/login">Đăng ký dùng thử</a>
                </div>
                <div class="div50 div50right">
                    <img src="https://laysdt.top/img/fdata-facebook-ads.png" width="100%" alt="GIẢI PHÁP TIẾP CẬN KHÁCH HÀNG CHÍNH XÁC TỪ QUẢNG CÁO FACEBOO">
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content_tow">
            <h1>QUÉT COMMENT TỪ QUẢNG CÁO FACEBOOK Là gì?</h1>
            <div style="    font-family: arial;line-height: 35px;font-size: 18px;max-width: 1000px;margin: 0 auto;padding: 30px 20px;box-shadow: 5px 5px 0px 0px #2196F3;text-align:justify;">
                <b>QUÉT COMMENT TỪ QUẢNG CÁO FACEBOOK</b> là hệ thống có thể giúp bạn lấy những khách hàng Comment vào bài viết quảng cáo trên Facebook, bài viết đó có thể là Page của bạn hoặc của Page khác bất kỳ
                </br><b>1.</b> Chỉ cần bạn lấy link của những bài viết thêm vào hệ thống, khi nào khách hàng Comment vào bài viết bạn đã thêm, thì hệ thống sẽ trả dữ liệu khách hàng đó xuống, bạn sẽ có ngay thông tin về khách hàng đó
                </br><b>2.</b> Hệ thống này sẽ theo dõi tất các bài viết đã thêm hoàn toàn tự động suốt 24/24h, bất kể thời gian nào
                </br><b>3.</b> Có thể truy cập bằng bất kỳ thiết bị nào
            </div>
            <div class="clear"></div>
        </div>
        <div class="content_tow">
            <h1 style="margin-bottom:30px;">Nắm bắt được khó khăn mà quý khách đang gặp</h1>
            <div class="divGoi">
                <p class="p1" style="margin-bottom:20px;">Vấn đề hiện tại</p>
                <p class="p3"><img src="https://laysdt.top/img/icon-close.png" width="20" alt="icon-close" style="vertical-align:middle;margin-right:10px;">Không biết tìm khách hàng từ đâu</p>
                <p class="p3"><img src="https://laysdt.top/img/icon-close.png" width="20" alt="icon-close" style="vertical-align:middle;margin-right:10px;">Chi nhiều tiền cho quảng cáo Facebook</p>
                <p class="p3"><img src="https://laysdt.top/img/icon-close.png" width="20" alt="icon-close" style="vertical-align:middle;margin-right:10px;">Không biết target quảng cáo Facebook</p>
                <p class="p3"><img src="https://laysdt.top/img/icon-close.png" width="20" alt="icon-close" style="vertical-align:middle;margin-right:10px;">Ít hoặc không có khách hàng quan tâm</p>
                <p class="p3"><img src="https://laysdt.top/img/icon-close.png" width="20" alt="icon-close" style="vertical-align:middle;margin-right:10px;">Khách hàng không để lại số điện thoại</p>
            </div>
            <div class="divGoi">
                <p class="p1" style="margin-bottom:20px;">Giải pháp khắc phục</p>
                <p class="p3"><img src="https://laysdt.top/img/sign-check-icon.png" width="20" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Tìm khách hàng quá đơn giản</p>
                <p class="p3"><img src="https://laysdt.top/img/sign-check-icon.png" width="20" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Không cần chạy quảng cáo Facebook</p>
                <p class="p3"><img src="https://laysdt.top/img/sign-check-icon.png" width="20" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Người có kinh nghiệm đã target sẵn</p>
                <p class="p3"><img src="https://laysdt.top/img/sign-check-icon.png" width="20" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Khách hàng quan tâm đổ về liên tục</p>
                <p class="p3"><img src="https://laysdt.top/img/sign-check-icon.png" width="20" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Có số điện thoại của khách hàng</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content_tow">
            <h2>Quy trình</h2>
            <h1>Hoạt động của hệ thống QUÉT COMMENT TỪ QUẢNG CÁO FACEBOOK</h1>
            <img src="https://laysdt.top/img/quy-trinh.gif" width="100%" style="margin:0 auto;" alt="quy trình hoạt động hệ thống cmsfb">
            <div class="clear"></div>
        </div>
        <div class="content_tow">
            <h1>Hình ảnh dữ liệu được theo dõi trên hệ thống</h1>
            <div class="container">
                <div class="mySlides">
                    <div class="numbertext">1 / 6</div>
                    <img src="https://laysdt.top/img/fdata-facebook-ads.png" style="width:100%">
                </div>
                <div class="mySlides">
                    <div class="numbertext">2 / 6</div>
                    <img src="https://laysdt.top/img/hinh2.png" style="width:100%">
                </div>
                <div class="mySlides">
                    <div class="numbertext">3 / 6</div>
                    <img src="https://laysdt.top/img/hinh3.png" style="width:100%">
                </div>
                <div class="mySlides">
                    <div class="numbertext">4 / 6</div>
                    <img src="https://laysdt.top/img/hinh4.png" style="width:100%">
                </div>
                <div class="mySlides">
                    <div class="numbertext">5 / 6</div>
                    <img src="https://laysdt.top/img/hinh2.png" style="width:100%">
                </div>
                <div class="mySlides">
                    <div class="numbertext">6 / 6</div>
                    <img src="https://laysdt.top/img/hinh3.png" style="width:100%">
                </div>
                <a class="prev" onclick="plusSlides(-1)">❮</a>
                <a class="next" onclick="plusSlides(1)">❯</a>
                <div class="caption-container">
                    <p id="caption"></p>
                </div>
                <div class="row">
                    <div class="column"><img class="demo cursor" src="https://laysdt.top/img/fdata-facebook-ads.png" style="width:100%" onclick="currentSlide(1)" alt="Khách hàng thời trang"></div>
                    <div class="column"><img class="demo cursor" src="https://laysdt.top/img/hinh2.png" style="width:100%" onclick="currentSlide(2)" alt="Khách hàng Căn hộ khu tây"></div>
                    <div class="column"><img class="demo cursor" src="https://laysdt.top/img/hinh3.png" style="width:100%" onclick="currentSlide(3)" alt="Khách hàng Đất nền Bình Dương"></div>
                    <div class="column"><img class="demo cursor" src="https://laysdt.top/img/hinh4.png" style="width:100%" onclick="currentSlide(4)" alt="Khách hàng Đất nền Bình Phước"></div>
                    <div class="column"><img class="demo cursor" src="https://laysdt.top/img/hinh2.png" style="width:100%" onclick="currentSlide(5)" alt="Khách hàng Đất nền Bảo Lộc"></div>
                    <div class="column"><img class="demo cursor" src="https://laysdt.top/img/hinh3.png" style="width:100%" onclick="currentSlide(6)" alt="Khách hàng Căn hộ Quận 2"></div>
                </div>
            </div>
            <script>
                var slideIndex = 1;
                showSlides(slideIndex);

                function plusSlides(n) {
                showSlides(slideIndex += n);
                }

                function currentSlide(n) {
                showSlides(slideIndex = n);
                }

                function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName("mySlides");
                var dots = document.getElementsByClassName("demo");
                var captionText = document.getElementById("caption");
                if (n > slides.length) {slideIndex = 1}
                if (n < 1) {slideIndex = slides.length}
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                for (i = 0; i < dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" active", "");
                }
                slides[slideIndex-1].style.display = "block";
                dots[slideIndex-1].className += " active";
                captionText.innerHTML = dots[slideIndex-1].alt;
                }
            </script>
            <div class="clear"></div>
        </div>
        <div id="dangkyngay" class="content_tow">
            <h1>Đăng ký sử dụng giải pháp</h1>
            <p class="ptietkiem">
                Quý khách nhập đầy đủ thông tin để đăng ký tài khoản
                </br>Sau khi đăng ký thành công, quý khách đăng nhập vào hệ thống để test thử trước khi thanh toán</p>
                <video controls style="display:block;margin:0 auto;max-width:980px;width:100%;">
                    <source src="img/hướng dẫn đang ký và đăng nhập.mp4" type="video/mp4">
                </video>
            <a class="adangkyngay" target="_blank" href="https://toolquet.com/user/login">Đăng ký dùng thử</a>
            <div class="clear"></div>
        </div>
        <div class="content_tow">
            <div class="divGoiCamket">
                <p class="p1">Cam Kết Của Chúng Tôi !!!</p>
                <img class="img" src="https://laysdt.top/img/cam-ket-chat-luong.png" alt="cam kết chất lượng">
                <p class="p3"><img src="https://laysdt.top/img/sign-check-icon.png" width="20" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Cam kết hiệu quả cao hơn Quảng cáo Facebook đang áp dụng</p>
                <p class="p3"><img src="https://laysdt.top/img/sign-check-icon.png" width="20" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Cam kết tư vấn, hỗ trợ tận tình mọi thắc mắc của khách hàng</p>
                <p class="p3"><img src="https://laysdt.top/img/sign-check-icon.png" width="20" alt="sign-check-icon" style="vertical-align:middle;margin-right:10px;">Liên tục cải tiến sản phẩm, giúp khách hàng có trải nghiệm tốt nhất</p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="footer">
            <p>GIẢI PHÁP KHAI THÁC KHÁCH HÀNG TIỀM NĂNG</p>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
	<style>
		#messZalo{animation: 1s ease-in-out 0s normal none infinite running ring-messZalo;}
		@keyframes ring-messZalo {
			0% {transform: rotate(0deg) scale(1) skew(1deg);}
			10% {transform: rotate(-25deg) scale(1) skew(1deg);}
			20% {transform: rotate(25deg) scale(1) skew(1deg);}
			30% {transform: rotate(-25deg) scale(1) skew(1deg);}
			40% {transform: rotate(25deg) scale(1) skew(1deg);}
			50% {transform: rotate(0deg) scale(1) skew(1deg);}
			100% {transform: rotate(0deg) scale(1) skew(1deg);}
		}
	</style>
	<!-- <a href="https://t.me/mkttrung" target="_blank">
		<img src="https://uidtophone.top/img/icon_telegram.png" id="messZalo" style="position:fixed;z-index:997;bottom:170px;right:20px;width:60px;border-radius:100%;border:1px solid #0000002e;padding:8px;background-color: #fff;"/>
		<span style="position:fixed;z-index:997;bottom:220px;right:25px;color:#fff;background:#ff3d3b;font-weight:bold;font-size:18px;border-radius:100px;padding:2px 7px;font-family: arial;">1</span>
	</a>
	<a href="https://m.me/127701493756324" target="_blank">
		<img src="img/icon-messenger-nbk.png" id="messZalo" style="position:fixed;z-index:997;bottom:80px;right:20px;width:65px;"/>
		<span style="position:fixed;z-index:997;bottom:130px;right:25px;color:#fff;background:#ff3d3b;font-weight:bold;font-size:18px;border-radius:100px;padding:2px 7px;font-family: arial;">2</span>
	</a> -->
</body>
</html>