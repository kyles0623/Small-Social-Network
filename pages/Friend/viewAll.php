<?php
if(empty($friends))
{
	//User has no friends
	?>
	<h2>You don't have any friends. Go out and make some!</h2>
	<?php
}
else{
?>
<section id="friends">
<?php
	foreach($friends as $friend)
	{
		if($friend['image_path'] == '' || $friend['image_path'] == null)
		{
			$image_path = 'http://lamp.cse.fau.edu/~kshambli/MP3/images/noavatar.png';
		}
		else
			$image_path = 'http://lamp.cse.fau.edu/~kshambli/MP3/images/'.$friend['image_path'];
		?>
		<section class="friend">
			<section class="image">
				<img src="<?php echo $image_path; ?>"/></section>
			<section class="name">Name: <?php echo $friend['first_name']." ".$friend['last_name']; ?></section>
			<section class="username">Username: <?php echo $friend['username']; ?></section>
			<section class="username"><a href="index.php?page=friend&function=profile&id=<?php echo $friend['id']; ?>">View Profile</a></section>
		</section>


		<?php

	}
?>
</section>

<?php

}
?>