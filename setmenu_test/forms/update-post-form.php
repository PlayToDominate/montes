<?
session_start();
if(!isset($_SESSION['Admin_check']))
{
	header("location: ../admin.php");
}
//Check that they have the security permission to change the menu
//Connect to db
include('../dbconnect.php');
?>
<?php
	// Get values from form
	$post_id = mysql_real_escape_string($_POST['post_id']);
	$title = mysql_real_escape_string($_POST['title']);
	$active = mysql_real_escape_string($_POST['active']);
	$sort = mysql_real_escape_string($_POST['sort']);
	$description = mysql_real_escape_string($_POST['description']);

if($post_id){
	$sql="UPDATE posts SET title='".$title."', active='".$active."', sort='".$sort."', description='".$description."' WHERE post_id=".$post_id."";
	//Put into Database
	if(!mysql_query($sql))	{	//Issue error if one happens
		echo "There was a problem. Please try again.";
		echo $sql;
	}else	{
		echo "<p id=\"response\" style=\"color:red;\">post sucessfully updated.</p>";
	}
} else {
	$sql="INSERT INTO posts(title, description)VALUES('".$title."','".$description."')";
	//Put into Database
	if(!mysql_query($sql))	{	//Issue error if one happens
		echo "There was a problem. Please try again.";
		echo $sql;
	}else	{
		$post_id = mysql_insert_id();

	$daily_posts = mysql_query("
SELECT
posts.description,
posts.title,
posts.active,
posts.sort
FROM posts
WHERE posts.post_id = $post_id
		")
		or die(mysql_error());
		while($info = mysql_fetch_array( $daily_posts)) {
			$description=$info['description'];
			$description = str_replace("\\", "", $description );
			$title = $info['title'];
			$active = $info['active'];
			$sort = $info['sort'];
		}

?>

<tr bgcolor="#D3D3D3">
	<td id="name-<?=$post_id?>"><?=$title?></td>
	<td id="desc-<?=$post_id?>"><?=$description?></td>
	<td id="desc-<?=$post_id?>"><?=$active?></td>
	<td id="desc-<?=$post_id?>"><?=$sort?></td>
</tr>
<tr>
	<td colspan="5">
		<div id="update-post-<?=$post_id?>" class="add-update">
			<form name="update-post-form<?=$post_id?>" id="update-post-form<?=$post_id?>">
			<input type="hidden" name="post_id" value="<?=$post_id?>"/>
			<table>
				<tr>
					<td>
						<label>Title</label><br /><input type="text" size="25" name="title" size="100" value="<?=$title?>"/>
						<br /><br /><label>Description</label><br /><textarea name="description"><?=$description?></textarea>
						<br /><br /><label>Active</label><select name="active">
									<option value="Y"<?if(($active) == "Y"){?>selected="selected"<?}?>>Yes</option>
									<option value="N"<?if(($active) == "N"){?>selected="selected"<?}?>>No</option>
								</select>
						<br /><br /><label>Sort</label><br /><input type="text" size="5" name="sort" value="<?=$sort?>"/>
						<br /><br /><input type="submit" id="update-post-submit<?=$post_id?>" name="Submit" value="Save"/>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</td>
</tr>
<?}}?>