$(document).ready(function()
{

	$("#search").keyup(function(){

		searchTerm = $("#search").val();
		
		if(searchTerm == '')
		{
			$("#friends").html('Search for your friends and add them!');
			return false;
		}
			

		$.ajax({
        url:'http://lamp.cse.fau.edu/~kshambli/MP3/index.php?page=friend&function=ajaxSearch',
        type:'POST',
        data: 'searchTerm='+searchTerm,
        async: 'false',
        success: function(result)
        {
        	
        	friendContainer = $("#friends");
    		if(result.trim() == 'empty')
    		{

    			friendContainer.html('Your search returned no results');
    			return false;
    		}

    		friends = JSON.parse(result);
    		className = "friend";
    		html = "";
    		$.each(friends, function(index, friend)
    		{
    			images_path = "http://lamp.cse.fau.edu/~kshambli/MP3/images/";
    			if(friend.image_path == "" || friend.image_path == null)
    				image_path = images_path+"noavatar.png";
    			else
    				image_path = images_path+friend.image_path;

    			html += "<section class='"+className+"'>";
    			html += "<section class='image'><img src='"+image_path+"' /></section>";
    			html += "<section class='name'>Name: "+friend.first_name+" "+friend.last_name+"</section>";
    			html += "<section class='username'>Username: "+friend.username+"</section>";
    			if(friend.isFriend === null)
    				html += "<section class='addUser'><button class='add' value='"+friend.id+"'>Add Friend</button></section>";
    			else if(friend.isFriend == 1)
    				html += "<section class='isFriend'>Status: Friends</section>";
    			else if(friend.isFriend == 0)
    				html += "<section class='waiting'>Status: Awaiting Request</section>";

    			
    			
    			html += "</section>";
    		});
            
            

    		friendContainer.html(html);
    		updateEvents();
	       
        }
    	});



	});

});

function updateEvents()
{
    $(".addUser .add").unbind();
    $(".addUser .add").click(function()
    {
        parent = $(this).parent().parent();
        
        sendRequest($(this).val(),parent);
        
    });

}

function sendRequest(id,parent)
{
	$.ajax({
        url:'http://lamp.cse.fau.edu/~kshambli/MP3/index.php?page=friend&function=ajaxSendRequest',
        type:'POST',
        data: 'id='+id,
        async: 'false',
        success: function(result)
        {
            console.log(result);
        	if(result.trim() == 'sent')
            {
                parent.children('.addUser').fadeOut(800).remove();
                parent.append('<section class="waiting">Status: Awaiting Request</section>').hide().fadeIn(800);
            }
    		
	       
        }
    	});
}
