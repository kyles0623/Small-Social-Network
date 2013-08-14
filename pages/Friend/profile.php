<?php
if($isFriend)
{
?>
<section class="left" id="myInfo">
<section id="profile">
		<section class="image">
			<img src="<?php echo $defaultImage; ?>" class="home_image"/>
		</section>
		<section class="infopiece">
			<h3 >Name</h3> <?php echo $friend->first_name." ".$friend->last_name; ?>
		</section>
		<section class="infopiece">
			<h3 >Email</h3> <?php echo $friend->email; ?>
		</section>
		<section class="infopiece">
			<h3 >Phone</h3> <?php echo $friend->phone; ?>
		</section>
		<section class="infopiece">
			<h3 >Created</h3> <?php echo date('F j, Y',strtotime($friend->created)); ?>
		</section>

	</section>






	<section id="myStatuses">
		
		<?php
			if(empty($statuses))
			{
				?>
				<h3><?php echo $friend->first_name." ".$friend->last_name; ?> hasn't posted anything.</h3>
				<?php
			}
			else
				foreach($statuses as $status)
				{
					?>
					<section class="status">
						<h4 class="text"><?php echo $status['text']; ?></h4>
						<h5>written at <?php echo date('g:i a M jS, Y',strtotime($status['created'])); ?></h5>
						
					</section>


					<?php
				}
		?>

	</section>
</section>

<section class="right" id="mutualFriends">
	<h3>Mutual Friends</h3>
	<?php 
	if(empty($mutualFriends))
		echo "<h4>You don't have any mutual friends.</h4>";
	foreach($mutualFriends as $mutualFriend)
	{
		$mFriend = new User();
		$mFriend->setFriendUser($mutualFriend['id']);
		?>
		<section class="mutual_friend">
			<section class="mutual_image"><img src="<?php echo $mFriend->getDefaultImage(); ?>" /></section>
			<section class="name"><a href="index.php?page=friend&function=profile&id=<?php echo $mFriend->id; ?>"><?php echo $mFriend->first_name." ".$mFriend->last_name; ?></section>
		</section>

		<?php
	}

	?>


</section>

<?php } ?>