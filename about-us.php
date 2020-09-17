<?
include("includes/dbconnect.php");
?>
<?php
	$page_title="About Us | Monte's Grill &amp; Pub";
?>
<?include 'includes/header.php';?>
<body>
<div id="about-us" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include 'includes/navigation.php';?>
	<div id="content" data-role="content">
		<p>this is only a test</p>
		<h2>About Monte's Grill &amp; Pub</h2>
		<p>In 1996 I had the opportunity of purchasing Stampfl's Shortstop from Linus Stampfl, it was the best decision I ever made. I renamed it Short Stop Grill &amp; Pub and proceeded to make it the best food destination in Verona and the West Madison Area. We serve a full menu starting with breakfast at 6:00 am Mon-Fri and 7:00 am on Sat &amp; Sun's. We pride ourselves with our lunch specials and homemade soups daily. Our 10 hr simmered BBQ Ribs are the best in Dane County and our Friday night Fish Fry is the most popular in the area.</p>
		<p>I have worked hard at making Monte's Grill &amp; Pub (renamed a few years ago) the best &amp; friendliest place in town. With a hometown feeling, all sports fans enjoy being here with 4 plasma TV's, 10 TV's all together, and lots of fun. You will fall in love with the oldest Verona owned Grill &amp; Pub, so please check us out and ask for Monte so I can meet you.</p>
		<!--
		<p>
			<a href="Flash/Monte.swf?width=600&height=440" class="prettyPhoto[flash]" title="About Monte's"><img src="images/montes-logo.png" alt="About Monte's" /></a>
		</p>
		-->
		<p class="non-mobile">
				<script language="javascript">
					if (AC_FL_RunContent == 0) {
						alert("This page requires AC_RunActiveContent.js.");
					} else {
						AC_FL_RunContent(
						'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0',
						'width','600',
						'height','440',
						'src','Flash/Monte',
						'align','middle',
						'play','true',
						'quality','high',
						'bgcolor','#ffffff',
						'id','./Flash/Monte',
						'name','Flash/Monte',
						'allowscriptaccess','sameDomain',
						'allowfullscreen','false',
						'pluginspage','http://www.macromedia.com/go/getflashplayer',
						'movie','Flash/Monte' ); //end AC code
					}
				</script>
				<noscript>
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="600" height="440" id="./Flash/Monte" align="middle">
					<param name="allowScriptAccess" value="sameDomain" />
					<param name="allowFullScreen" value="false" />
					<param name="movie" value="Flash/Monte.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" /><param name="PLAY" value="false" />	<embed src="Flash/Monte.swf" width="600" height="440" align="middle" quality="high" bgcolor="#ffffff" name="Flash/Monte" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" play="false" />
					</object>
				</noscript>
		</p>
	</div>
	<?include 'includes/right_bar.php';?>
	<?include 'includes/footer.php';?>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
    $("a[class^='prettyPhoto']").prettyPhoto();
  });
</script>
</div>
</body>
</html>
