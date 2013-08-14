$(document).ready(function(){

	$(".accept_request").click(function(){
		id = $(this).val();

		acceptRequest(id);

	});

});

function acceptRequest(id)
{
	$.ajax({
        url:'http://lamp.cse.fau.edu/~kshambli/MP3/index.php?page=friend&function=ajaxAcceptRequest',
        type:'POST',
        data: 'id='+id,
        async: 'false',
        success: function(result)
        {
            
        	if(result.trim() == 'accepted')
            {

               parent = $(".accept_request").parent();
               $(".accept_request").remove();

               parent.append("<h4>You Accepted the Friend Request!</h4>");

            }
    		
	       
        }
    	});
}