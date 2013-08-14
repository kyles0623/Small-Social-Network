<html>

<head>
<title>Main Page</title>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/main.css" type="text/css" >
<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/multi-select/css/multi-select.css" type="text/css" >
<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.js" ></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.form.min.js" ></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>js/bootstrap.min.js" ></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>js/scripts.js" ></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>plugins/multi-select/js/jquery.multi-select.js" ></script>

</head>


<body>
	<section id="header">
		<nav>
			<ul>
				<li><a href="index.php?page=home">Home</a></li>
				<li><a href="index.php?page=home&function=profile">Edit Profile</a></li>
				<li><a href="index.php?page=friend&function=viewAll">My Friends</a></li>
				<li><a href="index.php?page=friend&function=search">Search</a></li>
				<li><a href="index.php?page=login&function=logout">Logout</a></li>
			</ul>
		</nav>
		<section id="mail">
			<a href="index.php?page=home&function=checkMessages">
			<?php $count =  $this->user->getMessageCountNew(); 
			$mail_img = $count<=0?"mail-icon.png":"mail-new-icon.png";
			?>
			<img src="<?php echo BASE_URL; ?>images/<?php echo $mail_img; ?>" /> (<?php echo $count; ?>)
			</a>
		</section>
	</section>
<section id="container">
<?php if(isset($errors) && !empty($errors)){ ?>
<section id="errors">
	<ul>
	<?php foreach($errors as $e)
	{
		echo "<li>".$e."</li>";
	}
	?>
</ul>

</section>
<?php } ?><?php require_once(BASE_PATH.'pages'.DS.$this->page.DS.$this->function.".php"); ?>
</section>
</body>



</html>