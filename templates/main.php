<html>

<head>
<title>Main Page</title>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/main.css" type="text/css" >
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/bootstrap.min.css" type="text/css" >
<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/jquery.js" ></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/jquery.form.min.js" ></script>
<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/bootstrap.min.js" ></script>
</head>


<body>
	<section id="header">
		<nav>
			<ul>
				<li><a href="index.php?page=home">Home</a></li>
				<li><a href="index.php?page=home&function=profile">Edit Profile</a></li>
				<li><a href="index.php?page=friends&function=friends">My Friends</a></li>
				<li><a href="index.php?page=login&function=logout">Logout</a></li>
			</ul>
		</nav>
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