<section id="actions">
<a href="index.php?page=home&function=newMessage">Create New Message</a>

</section>
<section id="messages">
<?php

foreach($messages as $message)
{
	?>
	<section class="message">
		<section class="message_title"><h4><?php echo $message['title']; ?></h4></section>
		<section class="viewButton"><a href="index.php?page=home&function=viewMessage&id=<?php echo $message['id']; ?>">View Message</a></section>
		<section class="from_user">From <?php echo $message['friend']['first_name']." ".$message['friend']['last_name']; ?></section>
		<section class="message_date">Sent on <?php echo date('F j, Y',strtotime($message['created']) );?> at <?php echo date('h:i a',strtotime($message['created']));?></section>
		<section class="isRead"><?php echo $message['isRead']?'Read':'Not Read';?></section>
	</section>

	<?php
}
if(empty($messages))
{
	?>
	<h1>There are no messages to display. </h1>
	<?php
}
?>
</section>