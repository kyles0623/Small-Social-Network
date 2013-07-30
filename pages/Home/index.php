<section class="left" id="myInfo">

	<section id="profile">
		<section class="infopiece">
			<img src="<?php echo $defaultImage; ?>" class="home_image"/>
		</section>
		<section class="infopiece">
			<h3 class="label">Name:</h3> <?php echo $user->first_name." ".$user->last_name; ?>
		</section>
		<section class="infopiece">
			<h3 class="label">Email:</h3> <?php echo $user->email; ?>
		</section>
		<section class="infopiece">
			<h3 class="label">Phone:</h3> <?php echo $user->phone; ?>
		</section>

	</section>
	<section id="options">
		
		<a href="index.php?page=friend&function=view&id=<?php echo $user->id; ?>">View <?php echo $friendCount; ?> Friends</a>

	</section>	





	<section id="myStatuses">
		<form id="addStatus" action="index.php?page=home" method="post">
			<label for="status" class="label-status">Status</label><input type="text" name="status" class="form-control" />
			<input type="submit" value="Add Status" name="addStatus" class="btn btn-default"/>
		</form>
		<?php
			if(empty($statuses))
			{
				?>
				<h3>You haven't posted any statuses</h3>
				<?php
			}
			else
				foreach($statuses as $status)
				{
					?>
					<section class="status">
						<h3 class="text"><?php echo $status['text']; ?></h3>
						<h5>written at <?php echo date('g:i a M jS, Y',strtotime($status['created'])); ?>

					</section>


					<?php
				}
		?>

	</section>
</section>
<section class="right" id="statuses">
	<section id="friendsStatuses">
		
		<?php
			if($friendCount == 0)
			{
				?>
				<h3>You don't have any friends. Go and find some!</h3>
				<?php
			}
			else if(empty($friendsStatuses))
			{
				?>
				<h3>Your friends haven't posted any statuses</h3>
				<?php
			}
			else
				foreach($statuses as $status)
				{
					?>
					<section class="status">
						<h3 class="text"><?php echo $status['text']; ?></h3>
						<h5>written at <?php echo date('g:i a M jS, Y',strtotime($status['created'])); ?>

					</section>


					<?php
				}
		?>

	</section>
</section>