
<html>

<head>
<title>Login</title>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/blank.css" type="text/css" >
<script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.js" ></script>

</head>

<body>
	<section id="header">
		<nav>
			<ul>
				<li><a href="index.php">Login</a></li>
				<li><a href="index.php?page=login&function=register">Register</a></li>
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
<?php } ?>
<?php require_once(BASE_PATH.'pages'.DS.$this->page.DS.$this->function.".php"); ?>
</section>

</body>



</html>