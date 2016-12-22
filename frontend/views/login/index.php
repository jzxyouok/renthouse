<!DOCTYPE html>
<html>
<head>
<title>易租房</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="<?=FRONTEND?>/assets/frontend/css/bootstrap.css" rel='stylesheet' type='text/css'/>
<link href="<?=FRONTEND?>/assets/frontend/css/style.css" rel="stylesheet" type="text/css" media="all" />	    
<script src="<?=FRONTEND?>/weixin/js/jquery.min.js"></script>
 <!---- start-smoth-scrolling---->
<script type="text/javascript" src="<?=FRONTEND?>/assets/frontend/js/move-top.js"></script>
<script type="text/javascript" src="<?=FRONTEND?>/assets/frontend/js/easing.js"></script>
 <script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".scroll").click(function(event){		
				event.preventDefault();
				$('html,body').animate({scrollTop:$(this.hash).offset().top},1200);
			});
		});
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?2ed92d6dbf14abc53e22f0de42a77551";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();

	</script>
<!---End-smoth-scrolling---->
<script src="<?=FRONTEND?>/assets/frontend/js/responsiveslides.min.js"></script>
 <script>
    $(function () {
      $("#slider").responsiveSlides({
      	auto: true,
      	nav: true,
      	speed: 500,
        namespace: "callbacks",
        pager: true,
      });
    });
  </script>

</head>
<body>
		<div class="header">
			<div class="header-top">
				<div class="container">
				<div class="logo">
					<a href="<?=FRONTEND?>/index.html"><img src="<?=FRONTEND?>/assets/frontend/images/logo.jpg" height=64 width=200 ></a>
				</div>
				<div class="top-menu">
					 <span class="menu"> </span> 
					 <ul>
						<li><a href="#about" class="scroll">我们</a></li>
						<li><a href="#services" class="scroll">服务</a></li>
					</ul>
					</div>
					<div class="clearfix"> </div>
					 <!-- script-for-menu -->
		 <script>					
					$("span.menu").click(function(){
						$(".top-menu ul").slideToggle("slow" , function(){
						});
					});
		 </script>
		 <!-- script-for-menu -->

					</div>
				</div>
				</div>
				<div class="banner">
					<div class="container">
						<div class="banner-grids">
						<div class="col-md-6 left-grid">
							<img src="<?=FRONTEND?>/assets/frontend/images/qrcode.jpg" class="img-responsive" alt="/">
						</div>
						<div class="col-md-6 right-grid slider">
						<div class="callbacks_container">
			  		<ul class="rslides" id="slider">
					 <li>	 
					 <h1>快</h1>         
					<p>比传统更快的找到您所需要的房源，不用在数不清的房源中一页页翻到脱水啦！</p>
					<!-- <a href="#" class="button  hvr-shutter-in-horizontal">more info</a>					  -->
					</li>
				 	<li>	          
					<h1>准</h1>
					<p>最新匹配算法，首创评分制淘汰低精房源。一键精准定位，不再浪费时间！</p>
					<!-- <a href="#" class="button  hvr-shutter-in-horizontal">more info</a> -->
				 	</li>
				 	<li>	          
					<h1>狠</h1>				
					<p>我的房源我做主，无需手机号，沟通畅通。骚扰远离。芝麻找房让你做主！</p>
					<!-- <a href="#" class="button hvr-shutter-in-horizontal">more info</a>			 	 -->
					</li>
			  </ul>
		  </div>
		  </div>
		  <div class="clearfix"> </div>
		</div>
	</div>
	</div>			
			<div class="content">
				<div class="service-section" id="services">
					<div class="container">
						<h3> our services</h3>
						<div class="service-grids">
							<div class="col-md-4 service-grid">
								<img src="<?=FRONTEND?>/assets/frontend/images/icon1.png">
								<h4>IOS</h4>
								<p>筹备中 </p>
								<a href="#" class="button hvr-shutter-in-horizontal">Downlaod</a>
							</div>
							<div class="col-md-4 service-grid">
								<img src="<?=FRONTEND?>/assets/frontend/images/icon2.png">
								<h4>Android</h4>
								<p>筹备中 </p>
								<a href="#" class="button hvr-shutter-in-horizontal">Downlaod</a>
							</div>
							<div class="col-md-4 service-grid">
								<img src="<?=FRONTEND?>/assets/frontend/images/icon3.png">
								<h4>Wechat</h4>
								<p>扫描上方二维码 </p>
								<!-- <a href="#" class="button hvr-shutter-in-horizontal"></a> -->
							</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					<div class="about-section" id="about" >
					<div class="container">
						<h3>about</h3>
						<div class="about-grids">
							<div class="col-md-6 left-about">
								<div class="about-grid1">
									<h4>我们</h4>
									<p>芝麻找房是一个服务买家的精准找房平台， 微信扫描上方二维码关注</p>
									</div>
						    </div>
						   <div class="col-md-6 right-about">
								<div class="contact">
									<p>&nbsp;</p>
									<p>客服电话：400-889-1532 （周一~周日 9：00-17：00）</p>
									<p>邮箱：service@vanquio.com</p>

									<p>地址：上海市徐汇区田林路140号越界创意园16号东2楼</p>
								</div>
							</div>
						</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-section">
					<div class="container">
						<div class="footer-top">
					<p>Copyright &copy; 2015.芝麻找房 All rights reserved. - <a href="http://www.miitbeian.gov.cn/" target="_blank">沪ICP备14020180</a></p>
					</div>
					<script type="text/javascript">
						$(document).ready(function() {
							/*
							var defaults = {
					  			containerID: 'toTop', // fading element id
								containerHoverID: 'toTopHover', // fading element hover id
								scrollSpeed: 1200,
								easingType: 'linear' 
					 		};
							*/
							
							$().UItoTop({ easingType: 'easeOutQuart' });
							
						});
					</script>
				<a href="#" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>


					</div>
					</div>
</body>
</html>