function chooseFile() {
      $(".image-upload").click();
   }

function checkValidEmail(emailAddress, allowed, id)
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
                console.log("response: "+response+" email: "+emailAddress+" allowed: "+allowed);
                if(email!=null){
                    email.destroy();
                }
                
                
                var failureMessage;
                if(allowed=='true'){
                    failureMessage = "Dit emailadres is nog niet geregistreerd";
                }
                else{
                    failureMessage = "Dit emailadres is al geregistreerd";
                }
                if(response=='true' && allowed==true){
                    console.log("correct=truetrue");
                     correct=true;
                }
                else if(response=='false' && allowed ==false){
                    console.log("correct=true");
                    correct=true;
                    
                }
                else{
                    console.log("correct=false");
                    correct=false;
                }
                var nietLeeg = "Dit veld is verplicht!";
                var email = new LiveValidation(id, {validMessage:" "});
                email.add(Validate.Presence,{failureMessage:nietLeeg});
                email.add(Validate.Length, {maximum:50, tooLongMessage: "Maximum 50 tekens lang!"});
                email.add(Validate.Email, {failureMessage: "Moet een geldig emailadres zijn!"});
                email.add(Validate.Custom, {against: function checkEmail(value){
                        return correct;        
                    }, failureMessage:failureMessage});
	            return response;
	        },
	        error : function(error)
	        {
	            return 'failed';
	        }
	    });
    }
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
                    $('#projectMemberSubmit').toggleClass("red-button");
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
                    $('#projectMemberSubmit').toggleClass("red-button");
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
                     $('#projectMemberSubmit').toggleClass("red-button");
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

		if($('#eventMemberSubmit').val() == 'Toevoegen aan kalender')
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
		        	$('#eventMemberSubmit').val("Verwijderen uit kalender");
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
		        	$('#eventMemberSubmit').val('Toevoegen aan kalender');
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
	            	$('#eventMemberSubmit').attr('value', 'Verwijderen uit kalender');
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
				}, 250, function() 
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
			}, 500, function()
			{});
		}
	}

	/*
		Delete members of project
	*/

	$(document).on('click', '.projectMemberDelete', function(e)
	{
		var targetId = e.target.id;
	    var data = $('#' + targetId).attr('data');
	    var dataArray = data.split(';');
	    var tempProjectId = dataArray[0];
	    var tempUserId = dataArray[1];
	    deleteProjectMember(tempProjectId, tempUserId);
	});	

	function deleteProjectMember(tempProjectId, tempUserId)
	{
		jQuery.ajax(
	    {
	    	url : plugin.ajax_url,
	    	type : 'post',
	    	data : 
	    	{
	    		action : 'delete_project_member',
	    		projectId : tempProjectId,
	    		userId : tempUserId
	    	},
	    	success : function(response)
	    	{
	    		if(checkNumberOfMembers(tempUserId))
				{
					var element = $("#projectMemberContainer" + tempUserId);
					var parentElement = element.parent();

					parentElement.animate(
		    		{
					    height: 'toggle'
					}, 250, function() 
					{
						parentElement.remove();
					});
				}

				else
				{
					$("#projectMemberContainer" + tempUserId).animate(
		    		{
					    height: 'toggle'
					}, 250, function() 
					{
						$("#projectMemberContainer" + tempUserId).remove();
					});
				}
	    	}
	    });
	}

	function checkNumberOfMembers(tempUserId)
	{
		var element = $("#projectMemberContainer" + tempUserId);
		var parentElement = element.parent();
		var parentElementId = parentElement.attr("id");
		var number = $("#" + parentElementId + " > section").length;
		
		if(number == 1)
		{
			return true;
		}

		else
		{
			return false;
		}
	}

	/*
		Add members to project
	*/

	var addMembersExpanded = false;
	var newMembers = false;

	$(document).on('click', '#projectMemberSubmitMember', function(e)
	{
		if(!addMembersExpanded)
		{
			jQuery.ajax(
		    {
		    	url : plugin.ajax_url,
		    	type : 'post',
		    	data : 
		    	{
		    		action : 'get_add_form'
		    	},
		    	success : function(response)
		    	{
		    		addMembersExpanded = true;
		    		$('#projectMemberSubmitContainer').prepend(response);
		    		$('#projectMemberSubmitContainer form').hide();
		    		$('#projectMemberSubmitContainer form').slideToggle(250);
		    		$('#projectMemberSubmitMember').val('Lid toevoegen');
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
		    		action : 'add_project_member',
		    		username : $('#projectMemberAddUsername').val(),
		    		projectId : $('#projectMemberSubmitMember').attr('data')
		    	},
		    	success : function(response)
		    	{
		    		if(response == 'success')
		    		{
		    			if($('.error-message'))
		    			{
		    				$('.error-message').fadeOut();
		    				$('.error-message').remove();
		    			}

		    			$('#projectMemberSubmitMember').val('Toegevoegd');
		    			$('#projectMemberSubmitContainer form').slideToggle(250);

		    			setTimeout(function()
		    			{
		    				if(!newMembers)
				    		{
				    			$("#projectMemberSubmitContainer").before('<p class="newProjectMemberIntro">Nieuwe projectleden:</p>');
				    			$(".newProjectMemberIntro").hide();
				    			$(".newProjectMemberIntro").slideToggle(250);
				    		}

				    		newMembers = true;

		    				$('#projectMemberSubmitMember').val('Leden toevoegen');

		    				var tempUsername = $('#projectMemberAddUsername').val();
		    				var tempProjectId = $('#projectMemberSubmitMember').attr('data');

		    				$('#projectMemberSubmitContainer form').remove();
		    				addMembersExpanded = false;

		    				jQuery.ajax(
						    {
						    	url : plugin.ajax_url,
						    	type : 'post',
						    	data : 
						    	{
						    		action : 'show_updated_project_members',
						    		username : tempUsername,
		    						projectId : tempProjectId
						    	},
						    	success : function(response)
						    	{
						    		$("#projectMemberSubmitContainer").before(response);
						    		$(".newProjectMember:last-of-type").hide();
						    		$(".newProjectMember:last-of-type").slideToggle(250);
						    	}
						    });
						}, 1500);
		    		}

		    		else
		    		{
		    			$("#projectMemberSubmitContainer").before('<p class="error-message">De ingevoerde gebruiker kon niet gevonden worden.</p>');
		    		}
		    	}
		    });
		}
	});	

	/*
		Load new posts
	*/

	var scrolled = false;
	var moreElements = false;

	$(window).scroll(function()
	{
		var containerElement; 
		var data;
		var pageType;


		if($('.main').length)
		{
			containerElement = $('.main');
			data = containerElement.attr('data');
			pageType = data.split(';')[0];
		}

		else if($('.container').length)
		{
			containerElement = $('.container');
			data = containerElement.attr('data');
			pageType = data.split(';')[0];
		}

		checkNumberOfPosts(containerElement, pageType);

		var lastPosition = $('footer');

		var scrollPosition = $(window).scrollTop();
		var lastPositionTop = lastPosition.offset().top;

		if(scrollPosition >= (lastPositionTop - 2000))
		{
			if(scrolled == false)
			{
				loadPosts(containerElement);
				scrolled = true;
			}
		}

		else if(scrollPosition <= (lastPositionTop - 2000))
		{
			if(scrolled == true)
			{
				scrolled = false;
			}
		}
	});

	function checkNumberOfPosts(containerElement, pageType)
	{
		if(pageType == 'singleProjecten')
		{
			if(((containerElement.children('section:not(loadMorePostsWarning)').length) % 9) == 0 && containerElement.children('section:not(loadMorePostsWarning)').length != 0)
			{
				if(moreElements == false)
				{
					containerElement.append('<section class="loadMorePostsWarning list-item normalize-text post" style="background-color:#FFFFFF;"><h2 class="normalize-text center">Meer posts worden geladen...</h2></section>');
					moreElements = true;
				}
			}
		}

		else if(pageType == 'projecten')
		{
			if((containerElement.children('a').length % 9) == 0 && containerElement.children('a').length != 0)
			{
				if(moreElements == false)
				{
					containerElement.append('<section class="loadMorePostsWarning" style="background-color:#FFFFFF;"><h2 class="normalize-text center">Meer projecten worden geladen...</h2></section>');
					moreElements = true;
				}
			}
		}

		else if(pageType == 'events')
		{
			if((containerElement.children('a').length % 9) == 0 && containerElement.children('a').length != 0)
			{
				if(moreElements == false)
				{
					containerElement.append('<section class="loadMorePostsWarning list-item normalize-text post" style="background-color:#FFFFFF;"><h2 class="normalize-text center">Meer events worden geladen...</h2></section>');
					moreElements = true;
				}
			}
		}

		else if(pageType == 'zoekertjes')
		{
			if((containerElement.children('a').length % 9) == 0 && containerElement.children('a').length != 0)
			{
				if(moreElements == false)
				{
					containerElement.append('<section class="loadMorePostsWarning list-item normalize-text post" style="background-color:#FFFFFF;"><h2 class="normalize-text center">Meer zoekertjes worden geladen...</h2></section>');
					moreElements = true;
				}
			}
		}

		else if(pageType == 'artikels')
		{
			if((containerElement.children('section').length % 9) == 0 && containerElement.children('section').length != 0)
			{
				if(moreElements == false)
				{
					containerElement.append('<section class="loadMorePostsWarning list-item normalize-text post" style="background-color:#FFFFFF;"><h2 class="normalize-text center">Meer artikels worden geladen...</h2></section>');
					moreElements = true;
				}
			}
		}

		else if(pageType == 'profiel')
		{
			if(((containerElement.children('section:not(loadMorePostsWarning)').length) % 9) == 0 && containerElement.children('section:not(loadMorePostsWarning)').length != 0)
			{
				if(moreElements == false)
				{
					containerElement.append('<section class="loadMorePostsWarning list-item normalize-text post" style="background-color:#FFFFFF;"><h2 class="normalize-text center">Meer posts worden geladen...</h2></section>');
					moreElements = true;
				}
			}
		}

		else
		{
			$('.loadMorePostsWarning').remove();
			moreElements = false;
		}
	}

	function loadPosts(containerElement)
	{
		var tempData = containerElement.attr('data');
		var res = tempData.split(';');
		var tempPageType = res[0]
		var tempPage = parseInt(res[1]) + 1;
		var tempProject = '';
		var tempSearch = '';
		var tempCats = [];

		if(tempPageType == 'singleProjecten')
		{
			tempProject = res[2];
			containerElement.attr('data', tempPageType + ';' + tempPage + ';' + tempProject);
		}

		else
		{
			containerElement.attr('data', tempPageType + ';' + tempPage);
		}

		if(tempPageType != 'singleProjecten' && tempPageType != 'profiel' && res.length == 3)
		{
			tempSearch = res[2];
			containerElement.attr('data', tempPageType + ';' + tempPage + ';' + tempSearch);
		}

		if(tempPageType == 'artikels' && res.length > 3)
		{
			tempSearch = res[2];

			for(var i = 3; i < (res.length); i++)
			{
				tempCats.push(res[i]);
			}
		}

		if(tempSearch == 'none')
		{
			tempSearch = '';
		}
		
		jQuery.ajax(
		{
		   	url : plugin.ajax_url,
		   	type : 'post',
		   	data : 
		  	{
		   		action : 'load_new_posts',
		   		pageType : tempPageType,
		   		page : tempPage,
		   		projectId : tempProject,
		   		search : tempSearch,
		   		cats : tempCats
		   	},
		   	success : function(response)
		   	{
		   		console.log(response);
		   		$('.loadMorePostsWarning').before(response);
		   		checkNumberOfPosts(containerElement);
		   	}
		});
	}
});