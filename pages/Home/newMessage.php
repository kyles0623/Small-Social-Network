<?php

if($messageSent)
{
?>
<h1>Your message has been sent!</h1>
<?php
}
else
{
	?>


<form id="newMessage" action ="index.php?page=home&function=newMessage" method="post">
	<section class="select-box">
	<label for="to_users">Sending To: </label><br />
	<select  id="to_users" name="to_users[]" multiple='multiple'>
		<?php
		foreach($friends as $friend)
		{
			$selected = '';
			if(in_array($friend['id'],$to_users))
				$selected = 'selected';
			?>
			<option value="<?php echo $friend['id']; ?>" <?php echo $selected; ?> ><?php echo $friend['first_name']." ".$friend['last_name']; ?></option>
			<?php
		}
		?>

	</select>
	</section>
	<br />
	<section class="input">
	<label class="col-lg-2 control-label" for="first_name">Title:</label><br />
	<input type="text" class="form-control" name="title" id="message_title" value="<?php echo $title; ?>" />
	</section>
	<br /><br />
	<section class="input">
	<label for="body" class="col-lg-2 control-label">Body: </label>
	<textarea name="body" class="form-control" id="message_body"><?php echo $body; ?></textarea>
	</section>
	<br /><br />
	<input type="submit" name="submit" id="submit" value="Send Message" />


</form>


<script type="text/javascript">
$('#to_users').multiSelect();
</script>

<?php } ?>