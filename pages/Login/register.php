<form id="register" method="post" action="index.php?page=login&function=register">

	<section class="input"><label for="firstname">First Name: </label><input type="text" name="firstname" id="firstname" /></section>
	<section class="input"><label for="lastname">Last Name: </label><input type="text" name="lastname" id="lastname" /></section>
	<section class="input"><section id="unique"></section><label for="username">Username: </label><input type="text" name="username" id="username" /></section>
	<section class="input"><label for="password">Password: </label><input type="password" name="password" id="password" /></section>
	<input type="submit" id="submit" name="submit" />
	<div class="clear"></div>

</form>


<script type="text/javascript">

var unique = false;
$(document).ready(function()
{
	$("#username").on('blur',function()
	{
		if($("#username").val() != "")
			checkUnique($("#username").val());

	});

	$("#submit").click(function()
	{
		return Validate();
	});

});

function Validate()
{
	errors= new Array();
	if($("#firstname").val() == "")
		errors.push("Your first name is required");
	if($("#lastname").val() == "")
		errors.push("Your last name is required");
	if($("#password").val() == "")
		errors.push("A password is required");
	
	if(unique == false)
	{
		errors.push("Your username must be unique");
	}

	if(errors.length == 0)
		return true;
	else
	{
		alert(errors.join("\n"));
		return false;
	}
}


function checkUnique(username)
{

	$.ajax({
        url:'index.php?page=login&function=ajaxCheckUserName',
        type:'POST',
        data: 'username='+username,
        async: 'false',
        success: function(result)
        {
        	result = $.trim(result);

        	if(result == 'unique')
        	{
        		$("#unique").html('That username is unique').show();	
        		unique = true;
        	}
        	else
        	{

        		$("#unique").html('That username is not unique.').show();
        		unique = false;
        	}
        	
        }

    });
}

</script>