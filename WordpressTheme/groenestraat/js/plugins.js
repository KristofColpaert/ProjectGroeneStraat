$(document).ready(function()
{
	/*
		Registration mail check
	*/
	$(document).on('click', '.test-button', function()
	{
	    var emailAddress = jQuery('#user_email').val();
	    checkValidEmail(emailAddress);
	});

	function checkValidEmail(emailAddress)
	{
	    jQuery.ajax(
	    {
	        url : plugin.ajax_url,
	        type : 'post',
	        data : 
	        {
	            action : 'check_email',
	            email : emailAddress
	        },
	        success : function(response)
	        {
	            return response;
	        },
	        error : function(error)
	        {
	            return 'failed';
	        }
	    });
	}

	/*
		Become member of a project
	*/

	checkProjectMember();
	$('#projectMemberSubmit').css('visibility', 'hidden');

	$('#projectMemberForm').submit(function(e)
	{
		e.preventDefault();
		makeProjectMember();
	});

	function makeProjectMember()
	{
		var tempUser_id = $('#projectMemberId').val();
		var tempProject_id = $('#projectMemberProjectId').val();

		if($('#projectMemberSubmit').val() == 'Inschrijven')
		{
			jQuery.ajax(
		    {
		        url : plugin.ajax_url,
		        type : 'post',
		        data : 
		        {
		            action : 'link_project_user',
		            user_id : tempUser_id,
		            project_id : tempProject_id,
		            todo : 'subscribe'
		        },
		        success : function(response)
		        {
		        	$('#projectMemberSubmit').val("Uitschrijven");
		            return response;
		        },
		        error : function(error)
		        {
		            return 'failed';
		        }
		    });
		}

		else
		{
			jQuery.ajax(
		    {
		        url : plugin.ajax_url,
		        type : 'post',
		        data : 
		        {
		            action : 'link_project_user',
		            user_id : tempUser_id,
		            project_id : tempProject_id,
		            todo : 'unsubscribe'
		        },
		        success : function(response)
		        {
		        	$('#projectMemberSubmit').val("Inschrijven");
		            return response;
		        },
		        error : function(error)
		        {
		            return 'failed';
		        }
		    });
		}
	}

	function checkProjectMember()
	{
		var tempUser_id = $('#projectMemberId').val();
		var tempProject_id = $('#projectMemberProjectId').val();

		jQuery.ajax(
	    {
	        url : plugin.ajax_url,
	        type : 'post',
	        data : 
	        {
	            action : 'check_project_user',
	            user_id : tempUser_id,
	            project_id : tempProject_id
	        },
	        success : function(response)
	        {
	            if(response == 'true')
	            {
	            	$('#projectMemberSubmit').attr('value', 'Uitschrijven');
	            }
	            $('#projectMemberSubmit').css('visibility', 'visible');
	        },
	        error : function(error)
	        {
	            return 'failed';
	        }
	    });
	}
});