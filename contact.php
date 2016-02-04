<?include('includes/dbconnect.php');?>
<?php
	$page_title="Contact | Monte's Grill &amp; Pub";

	$Emailed = false;
	if(isset($_POST['Submit']))
	{
		$to = "montegrillpub@gmail.com";
		$Subject = "Feedback from montesverona.com";
		$Body = "Name: ".$_POST['Name']."<br>Email: ".$_POST['Email']."<br> Comments: ".$_POST['Comments'];
		$Body = wordwrap($Body,70);
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: monte@montesverona.com \r\n";
		mail($to,$Subject,$Body,$headers);
		$Emailed = true;
	}
?>
<?include 'includes/header.php';?>
<body>
<div id="contact" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include 'includes/navigation.php';?>
	<div id="content" data-role="content">
	<?if($Emailed){?>
		<h2>Thank You!</h2>
		<p>Thank you for your comments.</p>
	</div>
	<?} else {?>
	<h2>Contact Us</h2>
	<p style="margin-bottom:2%;">(608) 845-9669</p>
	<p style="margin-bottom:5%;">608A West Verona Ave<br />Verona, WI 53593</p>
	<form id="form1" name="form1" method="post" action="<? echo $_SERVER['PHP_SELF'] ?>">
		<p>
		<label for="Name">Name:</label><br />
		<input type="text" name="Name" id="Name" tabindex="1" />
	</p>
	<p>
		<label for="Email">Email:</label><br />
		<input type="text" name="Email" id="Email" tabindex="2" />
	</p>
	<p>
		<label for="Comments">Comments:</label><br />
		<textarea name="Comments" id="Comments" cols="60" rows="10" tabindex="3"></textarea>
	</p>
	<p><input type="submit" name="Submit" id="Submit" value="Submit Comments" tabindex="4" /></p>
	</form>
	<?}?>
	</div>
	<?include 'includes/right_bar.php';?>
	<?include 'includes/footer.php';?>
</div>
</body>
</html>
