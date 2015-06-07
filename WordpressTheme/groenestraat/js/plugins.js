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

	/*
		Add event to personal calendar
	*/

	checkEventMember();
	$('#eventMemberSubmit').css('visibility', 'hidden');

	$('#eventMemberForm').submit(function(e)
	{
		e.preventDefault();
		makeEventMember();
	});

	function makeEventMember()
	{
		var tempUser_id = $('#eventMemberId').val();
		var tempEvent_id = $('#eventMemberProjectId').val();

		if($('#eventMemberSubmit').val() == 'Toevoegen aan persoonlijke kalender')
		{
			jQuery.ajax(
		    {
		        url : plugin.ajax_url,
		        type : 'post',
		        data : 
		        {
		            action : 'link_event_user',
		            user_id : tempUser_id,
		            event_id : tempEvent_id,
		            todo : 'subscribe'
		        },
		        success : function(response)
		        {
		        	$('#eventMemberSubmit').val("Verwijderen uit persoonlijke kalender");
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
		            action : 'link_event_user',
		            user_id : tempUser_id,
		            event_id : tempEvent_id,
		            todo : 'unsubscribe'
		        },
		        success : function(response)
		        {
		        	$('#eventMemberSubmit').val('Toevoegen aan persoonlijke kalender');
		            return response;
		        },
		        error : function(error)
		        {
		            return 'failed';
		        }
		    });
		}
	}

	function checkEventMember()
	{
		var tempUser_id = $('#eventMemberId').val();
		var tempEvent_id = $('#eventMemberProjectId').val();

		jQuery.ajax(
	    {
	        url : plugin.ajax_url,
	        type : 'post',
	        data : 
	        {
	            action : 'check_event_user',
	            user_id : tempUser_id,
	            event_id : tempEvent_id
	        },
	        success : function(response)
	        {
	            if(response == 'true')
	            {
	            	$('#eventMemberSubmit').attr('value', 'Verwijderen uit persoonlijke kalender');
	            }
	            $('#eventMemberSubmit').css('visibility', 'visible');
	        },
	        error : function(error)
	        {
	            return 'failed';
	        }
	    });
	}

	/*
		Accept articles in project
	*/

	$(document).on('click', '.projectArticleSubmit', function(e)
	{
		var targetId = e.target.id;
	    var data = $('#' + targetId).attr('data');
	    processArticle(data, 'add');
	});

	$(document).on('click', '.projectArticleDelete', function(e)
	{
		var targetId = e.target.id;
	    var data = $('#' + targetId).attr('data');
	    processArticle(data, 'delete');
	});

	checkNumberOfArticles();

	function processArticle(tempArticleId, tempArticleAction)
	{
		jQuery.ajax(
	    {
	    	url : plugin.ajax_url,
	    	type : 'post',
	    	data : 
	    	{
	    		action : 'add_project_article',
	    		articleId : tempArticleId,
	    		articleAction : tempArticleAction
	    	},
	    	success : function(response)
	    	{
	    		$("#projectArticleContainer" + tempArticleId).animate(
	    		{
				    height: 'toggle'
				}, 500, function() 
				{
					$("#projectArticleContainer" + tempArticleId).remove();
					checkNumberOfArticles();
				});
	    	}
	    });
	}

	function checkNumberOfArticles()
	{
		var number = $("#projectArticleMainContainer > article").length;

		if(number == 0)
		{
			var data = '<p style="opacity:0">Alle artikels werden behandeld.</p>';
			$("#projectArticleMainContainer").append(data);

			$("#projectArticleMainContainer p").animate(
			{
				opacity: '100'
			}, 5000, function()
			{});
		}
	}
});