<section class="left" id="myInfo">

	<section id="profile">
		<section class="image">
			<img src="<?php echo $defaultImage; ?>" class="home_image"/>
		</section>
		<section class="infopiece">
			<h3 class="label">Name:</h3> <?php echo $this->user->first_name." ".$this->user->last_name; ?>
		</section>
		<section class="infopiece">
			<h3 class="label">Email:</h3> <?php echo $this->user->email; ?>
		</section>
		<section class="infopiece">
			<h3 class="label">Phone:</h3> <?php echo $this->user->phone; ?>
		</section>

	</section>
	<section id="options">
		
		<a href="index.php?page=friend&function=viewAll">View <?php echo $friendCount; ?> Friends</a>

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
				
				</form>
				<br />
				<h3>You don't have any statuses</h3>
				<?php
			}
			else
				foreach($statuses as $status)
				{
					?>
					<section class="status">
						<h4 class="text"><?php echo $status['text']; ?></h4>
						<h5>written at <?php echo date('g:i a M jS, Y',strtotime($status['created'])); ?>
						<a href="index.php?page=home&function=deleteStatus&status=<?php echo $status['id']; ?> " class="delete">Delete</a>
					</section>


					<?php
				}
		?>

	</section>
</section>
<section class="right" id="statuses">
	<section id="friendsStatuses">
		<h3>Friend's Statuses</h3>
		<?php
			if($friendCount == 0)
			{
				?>
				<h4>You don't have any friends. Go and find some!</h4>
				<?php
			}
			else if(empty($friendsStatuses))
			{
				?>
				<h4>Your friends haven't posted any statuses</h4>
				<?php
			}
			else
				foreach($friendsStatuses as $status)
				{
					?>
					<section class="status">
						<h4 class="text"><?php echo $status['text']; ?></h4>
						<h5>written at <?php echo date('g:i a M jS, Y',strtotime($status['created'])); ?>
						<h6>By <a href="index.php?page=friend&function=profile&id=<?php echo $status['user_id']; ?>"><?php echo $status['first_name']." ".$status['last_name']; ?></a></h6>
					</section>


					<?php
				}
		?>

	</section>
</section>

<div class="clear"></div>

<script type="text/javascript">

$(".delete").click(function()
{
	return confirm('Are you sure you want to delete this status?');
});
</script>