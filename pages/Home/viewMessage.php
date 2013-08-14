

<section id="title"><?php echo $message['title']; ?></section>
<section id="from"><?php echo $message['friend']['first_name']." ".$message['friend']['last_name']; ?></section>
<section class="message_date">Sent on <?php echo date('F j, Y',strtotime($message['created']) );?> at <?php echo date('h:i a',strtotime($message['created']));?></section>


<section id="body"><p><?php echo $message['message']; ?></p></section>