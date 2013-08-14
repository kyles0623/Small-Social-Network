<?php
if($userUpdated)
{
	?>
	<h3>Your profile has been updated!</h3>
<?php
}
else
{
?>
<form method="post" enctype="multipart/form-data" action="index.php?page=home&function=profile" id="profile-update">

	<section class="image" id="image_upload">
		<img src="<?php echo $defaultImage; ?>" />
		<?php if ($noDefault){ ?>
			<section id="no_image_txt">Click Here to upload a profile picture</section>
		<?php } ?>
		<section id="image_name"></section>
	</section>
	<label for="first_name" class="col-lg-2 control-label">First Name:</label><input type="text" class="form-control" name="first_name"  value="<?php echo $user->first_name; ?>" />
	<label for="last_name" class="col-lg-2 control-label">Last Name:</label><input type="text" class="form-control" name="last_name"  value="<?php echo $user->last_name; ?>" />
	<label for="last_name" class="col-lg-2 control-label">Email:</label><input type="text" class="form-control" name="email"  value="<?php echo $user->email; ?>" />
	<label for="phone" class="col-lg-2 control-label">Phone:</label><input type="tel" name="phone" class="form-control"  value="<?php echo $user->phone; ?>" />
	<label for="about" class="col-lg-2 control-label">About You:</label><textarea name="about" class="form-control"><?php echo html_entity_decode($user->about); ?></textarea>
	<label for="nothing" class="col-lg-2 control-label">Joined On:</label><?php echo date('F jS Y',strtotime($user->created)); ?>
	<input type="file" name="image" id="image_hidden_upload" />
	<br /><br />
	<input type="submit" id="submit" value="Update Profile" class="btn btn-default"/>
	


</form>



<script type="text/javascript">
	
	$("#image_upload").click(function()
	{
		$("#image_hidden_upload").trigger('click');
	});
	$("#image_hidden_upload").change(function()
	{
		
		$("#image_name").html($(this).val());
	});

</script>
<?php
}
?>