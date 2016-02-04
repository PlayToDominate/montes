<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('dbconnect.php');
$sql_posts_Check = "SELECT * FROM Admin WHERE User='".mysql_real_escape_string($_SESSION['Admin_check'])."' AND Posts = '1'";
$rt_User_Check = mysql_query($sql_posts_Check);
if(!$User = mysql_fetch_array($rt_User_Check))
{
	$Error = urlencode("Sorry you don't have permissions to access this part of the site");
	header("location: admin-options.php?Error=".$Error);
}
$page_title="Update posts | Monte's Grill &amp; Pub";
$change = "List";  // This var will hold what the page should do.
					// List mean list the current posts with a button that says change me
					// ChangeMe Says print out the options for change on the id we have
					// Changed Says We have successfully changed the post

	$daily_posts = mysql_query("
SELECT
posts.post_id as post_id,
posts.title,
posts.description,
posts.location,
posts.active,
posts.sort
FROM posts
ORDER BY sort,date_added DESC
		")
		or die(mysql_error());


?>
<?include '../includes/header-admin.php';?>
<body>
<div id="posts" data-role="page" data-add-back-btn="true" data-theme="a">
	<?include '../includes/navigation-admin.php';?>
	<div id="loading" style="float:left;padding:3%;width:70%;"><h1>...Loading</h1></div>
	<div id="content" data-role="content">
		<?include '../includes/navigation-admin-posts.php';?>
		<h1>Update posts</h1>
		<?php
			$previous = '';
			$found = false;
		?>
		<div id="menu-nav-food-posts" rel="#food-posts" data-role="collapsible" data-collapsed="true" data-content-theme="a">
			<div id="food-posts" class="food-menu">
				<table id="posts-list">
					<tr>
						<td><label>Title</label></td>
						<td><label>Description</label></td>
						<td><label>Active</label></td>
						<td><label>Sort</label></td>
					</tr>
					<?
						while($info = mysql_fetch_array( $daily_posts)) {
						$post_description_bad=$info['description'];
						$post_description = str_replace("\\", "", $post_description_bad );
						$current_post = $info['post_id'];
						$post_name = $info['title'];
						$post_active = $info['active'];
						$post_sort = $info['sort'];
					?>
					<tr bgcolor="#D3D3D3">
						<td id="name-<?=$current_post?>" width="40%"><?=$post_name?></td>
						<td id="desc-<?=$current_post?>" width="45%"><?=$post_description?></td>
						<td id="desc-<?=$current_post?>" width="5%"><?=$post_active?></td>
						<td id="desc-<?=$current_post?>" width="5%"><?=$post_sort?></td>
						<td nowrap width="5%"><button id="add-post-<?=$current_post?>" class="update-post" value="<?=$current_post?>">Update post</button></td>
					</tr>
					<tr>
						<td colspan="5">
							<div id="update-post-<?=$current_post?>" class="add-update">
								<form name="update-post-form<?=$current_post?>" id="update-post-form<?=$current_post?>">
								<input type="hidden" name="post_id" value="<?=$current_post?>"/>
								<table>
									<tr>
										<td>
											<label>Title</label><br /><input type="text" size="100" name="title" value="<?=$post_name?>" maxlength="100"/>
											<br /><br /><label valign="middle">Description</label><br /><textarea name="description" cols="80" rows="10"><?=$post_description?></textarea>
											<br /><br /><label>Active</label><select name="active">
													<option value="Y"<?if(($post_active) == "Y"){?>selected="selected"<?}?>>Yes</option>
													<option value="N"<?if(($post_active) == "N"){?>selected="selected"<?}?>>No</option>
												</select>
											<br /><br /><label>Sort</label><br /><input type="text" size="5" name="sort" value="<?=$post_sort?>"/>
											<br /><input type="submit" id="update-post-submit<?=$current_post?>" name="Submit" value="Save"/>
										</td>
									</tr>
								</table>
								</form>
							</div>
						</td>
					</tr>
					<? } ?>
				</table>
				<button name="add-new-post" id="add-new-post">Add New Post</button>
				<div id="add-post" class="add-update">
					<form id="add-new-post-form" name="add-new-post-form">
					<table>
						<tr>
							<td><label>Title</label><br /><input type="text" size="100" name="title" value="" maxlength="100"/></td>
							<td><label>Description</label><br /><textarea name="description" cols="80" rows="10"></textarea></td>
							<td>Sort</label><br /><input type="text" size="5" name="sort" value="<?=$post_sort?>"/></td>
							<td><input type="submit" id="add-post-submit" name="Submit" value="Save"/></td>
						</tr>
					</table>
					</form>
				</div>
			</div><!-- end food-posts -->
			<?mysql_close();?>
		</div><!-- end menu-nav-food-posts -->
	</div><!-- end content -->
</div><!-- end posts -->
<script type="text/javascript">
<!--
$(window).load(function(){
  $("#loading").fadeOut("slow");
  $("#content").fadeIn("slow");
});

jQuery(document).ready(function($){
$("#content").hide();
var current_post='1';

 	$('.add-update').hide();
	$("#posts-list").delegate(".update-post", "click", function(){
		var postID = $(this).val();
		current_post = postID;
		//alert(current_post);
		$('#update-post-'+postID).toggle();
	});


	$('#add-new-post').click(function() {
		$('#add-post').toggle();
	});

//add dates
	$('form').submit(function(){
		var formID = $(this).closest('form').attr('id');
		if(formID.indexOf('add-new-post') > -1){
			$.ajax({
				url:'forms/update-post-form.php',
				type:'POST',
				data:$(this).serialize(),
				success: function(result){
					//alert('got into ajax success');
					$('#response').hide('slow');
					$('#posts-list').append(result);
					$('#add-new-post-form input[name=title]').val('');
					$('#add-new-post-form textarea[name=description]').val('');
					$('.add-update').hide();
				}
			});



		} else {
			var new_type_value = $('#update-post-form'+current_post+' input[name=title]').val();
			var new_name_value = $('#update-post-form'+current_post+' input[name=active]').val();
			var new_desc_value = $('#update-post-form'+current_post+' textarea[name=description]').val();
			$.ajax({
				url:'forms/update-post-form.php',
				type:'POST',
				data:$(this).serialize(),
				success: function(result){
					//alert('got into ajax success');
					$('#response').hide('slow');
					$('#posts-list').append(result);
					$('#title-'+current_post).text(new_type_value);
					$('#active-'+current_post).text(new_name_value);
					$('#desc-'+current_post).text(new_desc_value);
				}
			});
		}
		return false;
	});
});
//-->
</script>
</body>
</html>