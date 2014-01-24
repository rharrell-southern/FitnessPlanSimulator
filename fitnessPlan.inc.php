<html>
<head>
	<link rel="stylesheet" type="text/css" href="http://marr.southern.edu/forms/assets/snippets/fitnessPlanSimulator/main.css" media="screen" />
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.core.js"></script>
	<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.fade.js"></script>
  	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

  	<script type='text/javascript' src="http://marr.southern.edu/forms/assets/snippets/fitnessPlanSimulator/fullcalendar-1.6.4/fullcalendar/fullcalendar.js"></script>
  	<link rel="stylesheet" href="http://marr.southern.edu/forms/assets/snippets/fitnessPlanSimulator/fullcalendar-1.6.4/fullcalendar/fullcalendar.css" />


  	<script type='text/javascript' src="http://marr.southern.edu/forms/assets/snippets/fitnessPlanSimulator/timepicker/jquery.timepicker.js"></script>
  	<link rel="stylesheet" href="http://marr.southern.edu/forms/assets/snippets/fitnessPlanSimulator/timepicker/jquery.timepicker.css" />
  	<script type='text/javascript' src="http://marr.southern.edu/forms/assets/snippets/fitnessPlanSimulator/timepicker/lib/base.js"></script>
	<title>Southern Adventist University: Fitness Plan Simulator</title>
	<script type="text/javascript">

		//global user info vars
		var username = "";
		var isLoaded = false;
		var charID = -1;
		var activity = "";
		var assess = "";
		var dateSelect = new Date();

		$(function() {
			//Get username from url var
			window.onload=function(){
				username = QueryString.name;
				if(username) {
					username = username.replace('%20', ' ');
				}
				console.log("Username: " + username);
			};

			$('#startTime').timepicker({
			    'timeFormat': 'H:i'
			});
			$('#endTime').timepicker({
			    'timeFormat': 'H:i'
			});
			$('#EstartTime').timepicker({
			    'timeFormat': 'H:i'
			});
			$('#EendTime').timepicker({
			    'timeFormat': 'H:i'
			});

			$( document ).tooltip();


			$('.benchTip').on('click',function(){ 
				window.open('http://www.bodybuilding.com/exercises/detail/view/name/bench-press-powerlifting');
			});

			$('.militaryTip').on('click',function(){ 
				window.open('http://www.bodybuilding.com/exercises/detail/view/name/seated-barbell-military-press');
			});

			$('.squatTip').on('click',function(){ 
				window.open('http://www.bodybuilding.com/exercises/detail/view/name/barbell-full-squat');
			});

			$('.deadliftTip').on('click',function(){ 
				window.open('http://www.bodybuilding.com/exercises/detail/view/name/barbell-deadlift');
			});

			$('.romDeadliftTip').on('click',function(){ 
				window.open('http://www.bodybuilding.com/exercises/detail/view/name/romanian-deadlift');
			});


			//page navs
			$('.next,.prev').on('click',function(){ 
				//if on "p2", ensure user has created/loaded a profile, else instruct user
				if(($(this).parent().parent().attr("id")) == "p1"){
					if(isLoaded == true){
						checkAssess();
					}else {
						alert("You must create or load a profile!");
					}
				}else if(($(this).parent().parent().attr("id")) == "p3"){
					//if on p3, check for all inputs before submitting to database
					//if not empty
					if($("#chestPress").val() !="" && $("#shoulderPress").val() !="" && $("#tricepPress").val() !="" && $("#armCurl").val() !="" && $("#legPress").val() !="" && $("#legExtension").val() !="" && $("#legCurl").val() !="" && $("#hipAdductor").val() !=""){
						//if numeric
						if((isNaN($("#chestPress").val() / 1) == false) && (isNaN($("#shoulderPress").val() / 1) == false) && (isNaN($("#tricepPress").val() / 1) == false) && (isNaN($("#armCurl").val() / 1) == false) && (isNaN($("#legPress").val() / 1) == false) && 
							(isNaN($("#legExtension").val() / 1) == false) && (isNaN($("#legCurl").val() / 1) == false) && (isNaN($("#hipAdductor").val() / 1) == false)){

							assess = $("#chestPress").val() + "," + $("#shoulderPress").val() + "," + $("#tricepPress").val() + "," + $("#armCurl").val() + "," + $("#legPress").val() + "," + $("#legExtension").val() + "," + $("#legCurl").val() + "," + $("#hipAdductor").val();
							assess.split(",");

							submitAssessment(assess);

							console.log("Assessment: ", assess);

							go($(this));

						}else{
							alert("Please enter a number value for each exercise!")
						}
					}else{
						alert("Please enter a number value for each exercise!")
					}
				}else{
					go($(this));
					}
			});

			//modal for caldate click
			$( "#dateDialog" ).dialog({
				autoOpen: false,
		      	height: 570,
		      	width: 480,
		      	position: {
                        my: "center center", 
                        at: "center center",
                        of: window
                    },
                buttons: {
		        "Create": function() {
		        	//on Create
		        	dateSelect += "";
		        	var pieces= dateSelect.split(" ");
		        	var month = "";
		        	if(pieces[1] == "Jan"){
		        		month = 0;
		        	}else if(pieces[1] == "Feb"){
		        		month = 1;
		        	}else if(pieces[1] == "Mar"){
		        		month = 2;
		        	}else if(pieces[1] == "Apr"){
		        		month = 3;
		        	}else if(pieces[1] == "May"){
		        		month = 4;
		        	}else if(pieces[1] == "Jun"){
		        		month = 5;
		        	}else if(pieces[1] == "Jul"){
		        		month = 6;
		        	}else if(pieces[1] == "Aug"){
		        		month = 7;
		        	}else if(pieces[1] == "Sep"){
		        		month = 8;
		        	}else if(pieces[1] == "Oct"){
		        		month = 9;	
		        	}else if(pieces[1] == "Nov"){
		        		month = 10;
		        	}else if(pieces[1] == "Dec"){
		        		month = 11;
		        	}

		        	var timeS = $("#startTime").val().split(":");
		        	var timeE = $("#endTime").val().split(":");
		        	var valid = true;


				    var daySource = new Object();
				    daySource.id = (($("#calendar").fullCalendar( 'clientEvents' ).length) + parseInt("1"));
				    daySource.title = 'Routine';
				    daySource.start = new Date(pieces[3], month, pieces[2], timeS[0], timeS[1], "00", "00");
				    daySource.end = new Date(pieces[3], month, pieces[2], timeE[0], timeE[1], "00", "00");
				    daySource.allDay = false;
				    daySource.description = $("#dateChestPressWeight").val() + "," + $("#dateChestPressReps").val() + "," + $("#dateChestPressSets").val() + "," + $("#dateShoulderPressWeight").val() + "," + $("#dateShoulderPressReps").val() + "," + 
				    	$("#dateShoulderPressSets").val() + "," + $("#dateTricepPressWeight").val() + "," + $("#dateTricepPressReps").val() + "," + $("#dateTricepPressSets").val() + "," + $("#dateArmCurlWeight").val() + "," + $("#dateArmCurlReps").val() + "," + $("#dateArmCurlSets").val() + "," + 
				    	$("#dateLegPressWeight").val() + "," + $("#dateLegPressReps").val() + "," + $("#dateLegPressSets").val() + "," + $("#dateLegExtensionWeight").val() + "," + $("#dateLegExtensionReps").val() + "," + $("#dateLegExtensionSets").val() + "," + $("#dateLegCurlWeight").val() + "," +
				    	 $("#dateLegCurlReps").val() + "," + $("#dateLegCurlSets").val() + "," + $("#dateHipAdductorWeight").val() + "," + $("#dateHipAdductorReps").val() + "," + $("#dateHipAdductorSets").val();


				    var day = new Array();
				    day[0] = daySource;

				   if($("#startTime").val().length == 0 || $("#endTime").val().length == 0 || $("#dateChestPressWeight").val().length == 0 || $("#dateChestPressReps").val().length == 0 || $("#dateChestPressSets").val().length == 0 || $("#dateShoulderPressWeight").val().length == 0 || $("#dateShoulderPressReps").val().length == 0 ||
				    	$("#dateShoulderPressSets").val() .length == 0 || $("#dateTricepPressWeight").val().length == 0 || $("#dateTricepPressReps").val().length == 0 || $("#dateTricepPressSets").val().length == 0 || $("#dateArmCurlWeight").val().length == 0 || $("#dateArmCurlReps").val().length == 0 || $("#dateArmCurlSets").val().length == 0 || 
				    	$("#dateLegPressWeight").val().length == 0 || $("#dateLegPressReps").val().length == 0 || $("#dateLegPressSets").val().length == 0 || $("#dateLegExtensionWeight").val().length == 0 || $("#dateLegExtensionReps").val().length == 0 || $("#dateLegExtensionSets").val().length == 0 || $("#dateLegCurlWeight").val().length == 0 ||
				    	 $("#dateLegCurlReps").val().length == 0 || $("#dateLegCurlSets").val().length == 0 || $("#dateHipAdductorWeight").val().length == 0 || $("#dateHipAdductorReps").val().length == 0 || $("#dateHipAdductorSets").val().length == 0){

				    	alert("All fields must be filled out!");
				      	valid = false;
				    }
				    if(timeE[0] < timeS[0]){
				    	alert("Starting time must come before ending time!");
				    	valid = false;
				    }

				    if(valid == true){
				    	$("#calendar").fullCalendar('addEventSource', day);
	    				$('#calendar').fullCalendar('rerenderEvents');

	    				var source = $("#calendar").fullCalendar( 'clientEvents' );

	    				submitSource(source);

	    				$("#startTime").val("");
	    				$("#endTime").val("");
	    				$("#dateChestPressWeight").val("");
	    				$("#dateChestPressReps").val("");
	    				$("#dateChestPressSets").val("");
	    				$("#dateShoulderPressWeight").val("");
	    				$("#dateShoulderPressReps").val("");
				    	$("#dateShoulderPressSets").val("");
	    				$("#dateTricepPressWeight").val("");
	    				$("#dateTricepPressReps").val("");
	    				$("#dateTricepPressSets").val("");
	    				$("#dateArmCurlWeight").val("");
	    				$("#dateArmCurlReps").val("");
	    				$("#dateArmCurlSets").val("");
				    	$("#dateLegPressWeight").val("");
	    				$("#dateLegPressReps").val("");
	    				$("#dateLegPressSets").val("");
	    				$("#dateLegExtensionWeight").val("");
	    				$("#dateLegExtensionReps").val("");
	    				$("#dateLegExtensionSets").val("");
	    				$("#dateLegCurlWeight").val("");
	    				$("#dateLegCurlReps").val("");
	    				$("#dateLegCurlSets").val("");
	    				$("#dateHipAdductorWeight").val("");
	    				$("#dateHipAdductorReps").val("");
	    				$("#dateHipAdductorSets").val("");

			          	$( this ).dialog( "close" );
			          	simulate();
				    }
		        },
		        Cancel: function() {
		        	//On cancel: 
		          	$( this ).dialog( "close" );
		        },
		      },
      			modal: true
		    });

			//modal for on calevent click
		    $( "#eventDialog" ).dialog({
				autoOpen: false,
		      	height: 570,
		      	width: 480,
		      	position: {
                        my: "center center", 
                        at: "center center",
                        of: window
                    },
                buttons: {
		        "Save": function() {
				    date = dateEvent.start + "";
				    var pieces = date.split(" ");

				    var month = "";
		        	if(pieces[1] == "Jan"){
		        		month = 0;
		        	}else if(pieces[1] == "Feb"){
		        		month = 1;
		        	}else if(pieces[1] == "Mar"){
		        		month = 2;
		        	}else if(pieces[1] == "Apr"){
		        		month = 3;
		        	}else if(pieces[1] == "May"){
		        		month = 4;
		        	}else if(pieces[1] == "Jun"){
		        		month = 5;
		        	}else if(pieces[1] == "Jul"){
		        		month = 6;
		        	}else if(pieces[1] == "Aug"){
		        		month = 7;
		        	}else if(pieces[1] == "Sep"){
		        		month = 8;
		        	}else if(pieces[1] == "Oct"){
		        		month = 9;	
		        	}else if(pieces[1] == "Nov"){
		        		month = 10;
		        	}else if(pieces[1] == "Dec"){
		        		month = 11;
		        	}

		        	var valid = true;
		        	var timeS = $("#EstartTime").val().split(":");
		        	var timeE = $("#EendTime").val().split(":");

				    dateEvent.start = new Date(pieces[3], month, pieces[2], timeS[0], timeS[1], "00", "00");
				    dateEvent.end = new Date(pieces[3], month, pieces[2], timeE[0], timeE[1], "00", "00");



				    dateEvent.description = $("#EdateChestPressWeight").val() + "," + $("#EdateChestPressReps").val() + "," + $("#EdateChestPressSets").val() + "," + $("#EdateShoulderPressWeight").val() + "," + $("#EdateShoulderPressReps").val() + "," + 
				    	$("#EdateShoulderPressSets").val() + "," + $("#EdateTricepPressWeight").val() + "," + $("#EdateTricepPressReps").val() + "," + $("#EdateTricepPressSets").val() + "," + $("#EdateArmCurlWeight").val() + "," + $("#EdateArmCurlReps").val() + "," + $("#EdateArmCurlSets").val() + "," + 
				    	$("#EdateLegPressWeight").val() + "," + $("#EdateLegPressReps").val() + "," + $("#EdateLegPressSets").val() + "," + $("#EdateLegExtensionWeight").val() + "," + $("#EdateLegExtensionReps").val() + "," + $("#EdateLegExtensionSets").val() + "," + $("#EdateLegCurlWeight").val() + "," +
				    	 $("#EdateLegCurlReps").val() + "," + $("#EdateLegCurlSets").val() + "," + $("#EdateHipAdductorWeight").val() + "," + $("#EdateHipAdductorReps").val() + "," + $("#EdateHipAdductorSets").val();


				    if($("#EstartTime").val().length == 0 || $("#EendTime").val().length == 0 || $("#EdateChestPressWeight").val().length == 0 || $("#EdateChestPressReps").val().length == 0 || $("#EdateChestPressSets").val().length == 0 || $("#EdateShoulderPressWeight").val().length == 0 || $("#EdateShoulderPressReps").val().length == 0 ||
				    	$("#EdateShoulderPressSets").val().length == 0 || $("#EdateTricepPressWeight").val().length == 0 || $("#EdateTricepPressReps").val().length == 0 || $("#EdateTricepPressSets").val().length == 0 || $("#EdateArmCurlWeight").val().length == 0 || $("#EdateArmCurlReps").val().length == 0 || $("#EdateArmCurlSets").val().length == 0 || 
				    	$("#EdateLegPressWeight").val().length == 0 || $("#EdateLegPressReps").val().length == 0 || $("#EdateLegPressSets").val().length == 0 || $("#EdateLegExtensionWeight").val().length == 0 || $("#EdateLegExtensionReps").val().length == 0 || $("#EdateLegExtensionSets").val().length == 0 || $("#EdateLegCurlWeight").val().length == 0 ||
				    	 $("#EdateLegCurlReps").val().length == 0 || $("#EdateLegCurlSets").val().length == 0 || $("#EdateHipAdductorWeight").val().length == 0 || $("#EdateHipAdductorReps").val().length == 0 || $("#EdateHipAdductorSets").val().length == 0){

				    	alert("All fields must be filled out!");
				    	valid = false;
				    }
				    if(timeE[0] < timeS[0]){
				    	alert("Starting time must come before ending time!");
				    	valid = false;
				    }

				    if(valid == true){
	    				$('#calendar').fullCalendar('rerenderEvents');

	    				var source = $("#calendar").fullCalendar( 'clientEvents' );

	    				submitSource(source);

	    				$("#EstartTime").val("");
	    				$("#EendTime").val("");
	    				$("#EdateChestPressWeight").val("");
	    				$("#EdateChestPressReps").val("");
	    				$("#EdateChestPressSets").val("");
	    				$("#EdateShoulderPressWeight").val("");
	    				$("#EdateShoulderPressReps").val("");
				    	$("#EdateShoulderPressSets").val("");
	    				$("#EdateTricepPressWeight").val("");
	    				$("#EdateTricepPressReps").val("");
	    				$("#EdateTricepPressSets").val("");
	    				$("#EdateArmCurlWeight").val("");
	    				$("#EdateArmCurlReps").val("");
	    				$("#EdateArmCurlSets").val("");
				    	$("#EdateLegPressWeight").val("");
	    				$("#EdateLegPressReps").val("");
	    				$("#EdateLegPressSets").val("");
	    				$("#EdateLegExtensionWeight").val("");
	    				$("#EdateLegExtensionReps").val("");
	    				$("#EdateLegExtensionSets").val("");
	    				$("#EdateLegCurlWeight").val("");
	    				$("#EdateLegCurlReps").val("");
	    				$("#EdateLegCurlSets").val("");
	    				$("#EdateHipAdductorWeight").val("");
	    				$("#EdateHipAdductorReps").val("");
	    				$("#EdateHipAdductorSets").val("");


			          	$( this ).dialog( "close" );

			          	simulate();
			          }

		        },
		        "Delete": function() {

				    $('#calendar').fullCalendar('removeEvents', dateEvent.id)
    				
				    var source = $("#calendar").fullCalendar( 'clientEvents' );

    				submitSource(source);

		          	$( this ).dialog( "close" );
		          	simulate();
		        },
		        Cancel: function() {
		        	//On cancel: 
		          	$( this ).dialog( "close" );
		        },
		      },
      			modal: true
		    });


			//On selection of "New profile" on div id="p2"
		    $( "#newChar" ).on('click',function() {
		      $( "#newDialog" ).dialog( "open" );
		    });


		    //modal for "New profile" on div id="p2"
			$( "#newDialog" ).dialog({
				autoOpen: false,
		      	height: 409,
		      	width: 506,
		      	position: {
                        my: "center top", 
                        at: "center top",
                        of: window
                    },
                buttons: {
		        "Create": function() {
		        	//if activity is already loaded, unload for reselection
					if(activity != ""){
							$("#"+activity).hide();
						}

		        	//track selected activity and gender
		        	activity = $("#charCreate :selected").val();
		        	var gender = $("input[name=gender]:checked", "#selGender").val();

		        	//print selected data
		        	//console.log("Selected activty:" + activity);
		        	//console.log("Selected gender:" + gender);

		        	//ensure user has selected BOTH an activity and gender
		        	if(activity != "default" && gender != null){
		          		$( this ).dialog( "close" );

		          		//submit data to database
		          		submitChar(activity, gender);

		          		$("#"+activity).show();

		          		//print selected data
		        		//console.log("Selected Activity: " + activity);


		      		}else{
		      				//instruct user
		        			alert("All fields must be completed!");
		        		}
		        },
		        Cancel: function() {
		        	//On cancel: reset inputs/vars, clear status div, hide shown activty div on next p, and finally close dialog
		        	$('#charCreate option[value=default]').attr('selected', 'selected');
		        	isLoaded=false;
		        	$("#charLoad").html("");
		          	$( this ).dialog( "close" );
		        },
		      },
      			modal: true
		    });

		 
			//On selection of "Load profile" on div id="p2"
		    $( "#loadChar" ).on('click',function() {
		    	//clear in case of new append loop
		    	$("#loadDialog").html("");
		    	//fetch records
		    	loadChar();
		    	//display dialog
		      $( "#loadDialog" ).dialog( "open" );
		    });


		    //modal for "Load profile" on div id="p2"
		     $( "#loadDialog" ).dialog({
				autoOpen: false,
		      	height: 409,
		      	width: 506,
		      	position: {
                        my: "center top", 
                        at: "center top",
                        of: window
                    },
                buttons: {
		        "Select": function() {
		        	//if activity is already loaded, unload for reselection
		        	if(activity != ""){
							$("#"+activity).hide();
						}


		        	//track selected ID and activity
					var idAct = $("input[name=character]:checked", "#loadDialog").val();
					idAct = idAct.split(",");
		        	charID = idAct[0];

		        	activity = idAct[1];

		        	//print selected data
		        	console.log("Selected charID: " + charID);
		        	console.log("Selected Activity: " + activity);

		        	//ensure user has selected a profile
		        	if(charID != null){
		        		//show requested activity for next p, close dialog, display status div
		        		$("#"+activity).show();
		          		$( this ).dialog( "close" );
		          		$("#charLoad").html("profile load successful. . .")
		      		}else{
		        			alert("Could not load profile");
		        		}
		        },
		        Cancel: function() {
		        	//on cancel: clear status div, hide shown activty div for next p, reset vars, and close
		        	$("#charLoad").html("");
		        	isLoaded=false;
		          	$( this ).dialog( "close" );
		        },
		      },
      			modal: true
		    });

		});

		//check for assessment, and add previously entered data
		function checkAssess(){
					var dataObject = {
					'type' : "checkAssess",
					'charID': charID
				}

				$.ajax({
					type: "POST",
				     url: "assets/snippets/fitnessPlanSimulator/char.modify.php",
				     data: dataObject,
				     dataType: 'json',
				     success: function(ndata) {
				     	console.log("Data: ", ndata);

				     	/*$.each(ndata.Entries, function (){
							console.log(this.id + " " + this.date);
						});*/

				     	//if ndata.Assessment is NOT empty, user has already done assessment, and needs to skip it.
				     	if(!jQuery.isEmptyObject(ndata.Assessment)) {
							//populate self assessment data
							assess = ndata.Assessment.AssessmentData.split(',');
							var substr = ndata.Assessment.AssessmentData.split(',');

							console.log("Assessment: ", assess);

							$("#chestPress").val(substr[0]);
							$("#shoulderPress").val(substr[1]); 
							$("#tricepPress").val(substr[2]);
							$("#armCurl").val(substr[3]);

							$("#legPress").val(substr[4]);
							$("#legExtension").val(substr[4]);
							$("#legCurl").val(substr[4]);
							$("#hipAdductor").val(substr[4]);


							$('#p1').hide('fade', 700, function(){
								$('#p4').show('fade', 700, function(){

									if ( $('#calendar').children().length <= 0 ) {
										loadCalendar();
										simulate();
									}


								});
							});

						} else {
							$('#p1').hide('fade', 700, function(){
								$('#p2').show('fade', 700, function(){

									$("#chestPress").val("");
									$("#shoulderPress").val(""); 
									$("#tricepPress").val("");
									$("#armCurl").val("");

									$("#legPress").val("");
									$("#legExtension").val("");
									$("#legCurl").val("");
									$("#hipAdductor").val("");

								});
							});
						}
				     },
				     error: function(xhr, textStatus, error){
				     	//alert user, reset vars, update status div
				     	alert("error in POST")
				     	$("#charLoad").html("Assessment check failed. . .")
				     	console.log("xhr.statusText: " + xhr.statusText);
				     	console.log("textStatus: " + textStatus);
				     	console.log("error: " + error);
				     	isLoaded = false;
				     }
				 });
				}

		//Write profile information to database
		function submitChar(activity, gender){
			var dataObject = {
				'type': "new",
				'username': username,
				'activity': activity,
				'gender': gender
			}


			$.ajax({
			type: "POST",
			     url: "assets/snippets/fitnessPlanSimulator/char.modify.php",
			     data: dataObject,
			     success: function(ndata) {
			     	//console.log("charID: " + ndata);

			     	//track charID
			     	charID = ndata;

			     	//display status div
			     	$("#charLoad").html("profile creation successful. . .")
			     	isLoaded=true;
			     },
			     error: function(xhr, textStatus, error){
			     	//alert user, reset vars, update status div
			     	alert("error in POST")
			     	$("#charLoad").html("profile creation failed. . .")
			     	console.log("xhr.statusText: " + xhr.statusText);
			     	console.log("textStatus: " + textStatus);
			     	console.log("error: " + error);
			     	isLoaded = false;
			     }
			 });

		}

	function deleteChar(id){
			if (confirm('Are you sure you want to delete this profile?')) {
			//log id to delete
			console.log("Deleting ID#" + id);

			var dataObject = {
				'type': "delete",
				'ID': id,
			}

			$.ajax({
			type: "POST",
			     url: "assets/snippets/fitnessPlanSimulator/char.modify.php",
			     data: dataObject,
			     success: function(ndata) {
			     	console.log("Profile ID" + id + " deleted successfully");

			     	//refresh char data
			     	$("#loadDialog").html("");
			     	loadChar();
			     },
			     error: function(xhr, textStatus, error){
			     	//alert user, reset vars, update status div
			     	alert("error in POST")
			     	$("#charLoad").html("profile deletion failed. . .")
			     	console.log("xhr.statusText: " + xhr.statusText);
			     	console.log("textStatus: " + textStatus);
			     	console.log("error: " + error);
			     	isLoaded = false;
			     }
			 });
		}

	}

	//load profile from db
	function loadChar(){
		var dataObject = {
				'type': "load",
				'username': username,
			}

		$.ajax({
			type: "POST",
		    url: "assets/snippets/fitnessPlanSimulator/char.modify.php",
		    data: dataObject,
		    dataType: 'json',
		    success: function(ldata) {
		     	console.log(ldata);

		     	//for each entry in json, print data, with span around this.activity to select later on
		     	$.each(ldata, function (){

		     		$("#loadDialog").append(
		     			"<input type='radio' name='character' id='" + this.id + "' value='" + this.id + "," + this.activity + "'> <label for='" + this.id + "' class='radioWrapper'><b>Username:</b> " + this.username + "<br />&nbsp;&nbsp;&nbsp;&nbsp; <b>Gender:</b> "
		     			+ this.gender + "<button type='button' style='float: right;' onclick='deleteChar("+ this.id + ")'>Delete</button><br />&nbsp;&nbsp;&nbsp;&nbsp; <b>Activity:</b> " + this.activity+ "<br /><br /> </label>" 
		     			);

		     	})
		     	isLoaded = true;
		    },
		    error: function(xhr, textStatus, error){
		     	alert("profile load failed. . . ")
		     	console.log("xhr.statusText: " + xhr.statusText);
		     	console.log("textStatus: " + textStatus);
		     	console.log("error: " + error);
		     	isLoaded = false;
		    }
		});
	}

	//write assessment to db
	function submitAssessment(assessmentCSL){

		var dataObject = {
				'type'					: "submitAssess",
				'ID'					: charID,
				'personalAssessment'	: assessmentCSL
			}

		$.ajax({
			type: "POST",
		    url: "assets/snippets/fitnessPlanSimulator/char.modify.php",
		    data: dataObject,
		    success: function(ldata) {
		     	//console.log(ldata);

		     
		    },
		    error: function(xhr, textStatus, error){
		     	alert("Assessment submission failed. . . ")
		     	console.log("xhr.statusText: " + xhr.statusText);
		     	console.log("textStatus: " + textStatus);
		     	console.log("error: " + error);
		    }
		});
	}

	function submitSource(source){
		var sourceObj = [];
		for(i=0;i<source.length;i++) {
			sourceObj[i] = new Object(); 
			sourceObj[i].title = source[i].title + "";
			sourceObj[i].start = source[i].start + "";
			sourceObj[i].end = source[i].end + "";
			sourceObj[i].allDay = source[i].allDay + "";
			sourceObj[i].description = source[i].description + "";
			sourceObj[i].id = i;
		}

		console.log("newsrc: V");
		console.log(sourceObj);

		//remove circular references generated by fullcalendar for some ungodly reason.
		for(i=0;i<sourceObj.length;i++) {

			delete sourceObj[i].source;
			delete sourceObj[i].className;
			
		}
		//console.log(sourceObj);

		sourceString = JSON.stringify(sourceObj);

		var dataObject = {
			'type'		: "eventAdd",
			'UID'		: charID,
			'source'	: sourceString
		}
		//console.log(dataObject);

		$.ajax({
			type: "POST",
		    url: "assets/snippets/fitnessPlanSimulator/char.modify.php",
		    data: dataObject,
		    success: function(ndata) {
		     	//console.log("successfully written");
		     	//console.log(ndata);
		     	$('#calendar').fullCalendar('removeEvents');
		     	$('#calendar').fullCalendar('refetchEvents');
	    		$('#calendar').fullCalendar('rerenderEvents');
		    },
		    error: function(xhr, textStatus, error){
		     	//alert user, reset vars, update status div
		     	alert("error in POST")
		     	console.log("xhr.statusText: " + xhr.statusText);
		     	console.log("textStatus: " + textStatus);
		     	console.log("error: " + error);
		    }
		});
	}

		//get vars from url string
		var QueryString = function () {
			// This function is anonymous, is executed immediately and 
			// the return value is assigned to QueryString!
			var query_string = {};
			var query = window.location.search.substring(1);
			var vars = query.split("&");
			for (var i=0;i<vars.length;i++) {
				var pair = vars[i].split("=");
		    	// If first entry with this name
		    	if (typeof query_string[pair[0]] === "undefined") {
		    		query_string[pair[0]] = pair[1];
		    	// If second entry with this name
		    } else if (typeof query_string[pair[0]] === "string") {
		    	var arr = [ query_string[pair[0]], pair[1] ];
		    	query_string[pair[0]] = arr;
		    	// If third or later entry with this name
		    } else {
		    	query_string[pair[0]].push(pair[1]);
		    }
		} 
		return query_string;
	} ();


	//nav function
	function go(object) {
		var navTo;
		var target = object.parent().parent().attr('id');
		console.log(target);
		var current = parseInt(target.charAt(1));
		if (object.hasClass('next')) {
			navTo = current + 1;
		}
		if (object.hasClass('prev')){
			navTo = current - 1;
		}
		console.log('Current: ' + current + ' - Nav To: ' + navTo);
		$('#p'+current).hide('fade', 700, function(){
			$('#p'+ navTo).show('fade', 700, function(){
				console.log(navTo);
				switch(navTo) {
				case 1:
					$('#header').html("Southern's fitness plan simulator");
					break;
				case 2:
					$('#header').html("Plan Overview");
					break;
				case 3:
					$('#header').html("Personal Assessment");
					break;
				case 4:
					if ( $('#calendar').children().length <= 0 ) {
						loadCalendar();
						simulate();
					}
					break;
				default:
					$('#header').html("Southern's fitness plan simulator");
					break;
			}
				});
		});
	}

	function loadCalendar(){
		$('#calendar').fullCalendar({
    		events: {
	        url: 'assets/snippets/fitnessPlanSimulator/char.modify.php',
	        type: 'POST',
	        data: {
				'type'		: "eventLoad",
				'UID'		: charID
	        },
	        error: function() {
	            //alert('there was an error while fetching events!');
	        }
	    },
        header: {
		left: 'prev,next today',
		center: 'title',
		right: 'month'
		},
		editable: false,
		dayClick: function(date, allDay, jsEvent, view) {
			console.log(date);
			dateSelect = date;
	        $( "#dateDialog" ).dialog( "open" );
	    },
	    eventClick: function(event) {
	    	console.log(event.title);
	    	dateEvent = event;

	    	Stime = event.start + "";
	    	Stime = Stime.split(" ");

	    	Etime = event.end + "";
	    	Etime = Etime.split(" ");

	    	var desc = event.description.split(',');
	    	console.log(desc);

			$("#EstartTime").val(Stime[4]);
			$("#EendTime").val(Etime[4]);
			$("#EdateChestPressWeight").val(desc[0]);
			$("#EdateChestPressReps").val(desc[1]);
			$("#EdateChestPressSets").val(desc[2]);
			$("#EdateShoulderPressWeight").val(desc[3]);
			$("#EdateShoulderPressReps").val(desc[4]);
	    	$("#EdateShoulderPressSets").val(desc[5]);
			$("#EdateTricepPressWeight").val(desc[6]);
			$("#EdateTricepPressReps").val(desc[7]);
			$("#EdateTricepPressSets").val(desc[8]);
			$("#EdateArmCurlWeight").val(desc[9]);
			$("#EdateArmCurlReps").val(desc[10]);
			$("#EdateArmCurlSets").val(desc[11]);
	    	$("#EdateLegPressWeight").val(desc[12]);
			$("#EdateLegPressReps").val(desc[13]);
			$("#EdateLegPressSets").val(desc[14]);
			$("#EdateLegExtensionWeight").val(desc[15]);
			$("#EdateLegExtensionReps").val(desc[16]);
			$("#EdateLegExtensionSets").val(desc[17]);
			$("#EdateLegCurlWeight").val(desc[18]);
			$("#EdateLegCurlReps").val(desc[19]);
			$("#EdateLegCurlSets").val(desc[20]);
			$("#EdateHipAdductorWeight").val(desc[21]);
			$("#EdateHipAdductorReps").val(desc[22]);
			$("#EdateHipAdductorSets").val(desc[23]);

			$( "#eventDialog" ).dialog( "open" );
		}

    });
	$('#calendar').fullCalendar('render');
	}

	function censor(censor) {
	  return (function() {
	    var i = 0;

	    return function(key, value) {
	      if(i !== 0 && typeof(censor) === 'object' && typeof(value) == 'object' && censor == value) 
	        return '[Circular]'; 

	      if(i >= 29) // seems to be a harded maximum of 30 serialized objects?
	        return '[Unknown]';

	      ++i; // so we know we aren't using the original object anymore

	      return value;  
	    }
	  })(censor);
	}


	function simulate(){ 

		//Get current and (visible) prev/next months
		var currMonth = $('#calendar').fullCalendar('getView').start + "";
		var prevMonth = $('#calendar').fullCalendar('getView').visStart + "";
		var nextMonth = $('#calendar').fullCalendar('getView').visEnd + "";

		currMonth = currMonth.split(" ");
		var n =  currMonth[1];

		prevMonth = prevMonth.split(" ");
		var pn =  prevMonth[1];

		nextMonth = nextMonth.split(" ");
		var nn =  nextMonth[1];

		var src = $("#calendar").fullCalendar( 'clientEvents' );

		var monthSrc = new Array();
		var index = 0;
		for( $i = 0; $i < src.length; $i++){
			var start = src[$i].start + "";
			start = start.split(" ");

			//only evaluate events of the visible month +/- 1 week
			if ( start[1] == n || (start[1] == pn && start[2] >= prevMonth[2]) || (start[1] == nn && start[2] <= nextMonth[2])){
				monthSrc[index] = src[$i];
				index++;
			}
		}

		monthSrc.sort(function(a, b){
		 var dateA=new Date(a.start), dateB=new Date(b.start)
		 return dateA-dateB //sort by date ascending
		});

		//array formed, now perform logic checks

		console.log("Sorted src: ", monthSrc);

		var statusLog = "";

		for($i = 0; $i<monthSrc.length; $i++){

			console.log("------------");
			console.log("I CYCLE: " + $i);
			console.log("------------");

				//get our limits (sunday, sat, next sat)

				//get first sunday
				//console.log("Date: " + monthSrc[$i].start);
				var initialDate = monthSrc[$i].start;
				closestPrevSunday = initialDate.getDay();
				var sundayDate = new Date(initialDate.toISOString());
				sundayDate.setDate(sundayDate.getDate() - closestPrevSunday);

				//get saturday
				var dateOneWeek = new Date(initialDate.toISOString());
				closestSat = dateOneWeek.getDay();
				dateOneWeek.setDate(dateOneWeek.getDate() + (6 - closestSat));

				//get next saturday
				var dateTwoWeek = new Date(sundayDate.toISOString());
				dateTwoWeek.setDate(dateTwoWeek.getDate() + 13);

				//first sunday for statuslog use
				var selSun = sundayDate + "";

				//second sunday for statuslog use
				var sel2Sun = new Date(sundayDate.toISOString());
				sel2Sun.setDate(sel2Sun.getDate() + 7);

				sel2Sun = sel2Sun + "";

				selSun = selSun.split(" ");
				sel2Sun = sel2Sun.split(" ");

				selSun = selSun[0] + " " + selSun[1] + " " + selSun[2] + " " + selSun[3];
				sel2Sun = sel2Sun[0] + " " + sel2Sun[1] + " " + sel2Sun[2] + " " + sel2Sun[3];

				
				console.log("Range: " + sundayDate + " - " + dateOneWeek + " - " + dateTwoWeek);
				
				var lastAccepted = 0;
				var counterW1 = 0;
				var counterW2 = 0;
				var lbCountW1 = 0;
				var lbCountW2 = 0;
				var ubCountW1 = 0;
				var ubCountW2 = 0;
				for($j = 0; $j<monthSrc.length; $j++){
				
					var tmpDate = monthSrc[$j].start;
					//console.log("(J) trying: " + tmpDate);

					//First sunday to saturday
					if(tmpDate >= sundayDate && tmpDate <= dateOneWeek){
						counterW1++;
						lastAccepted = $j;
						console.log("ACCEPTED W1");
						console.log("counterW1: " + counterW1);
						
						//current exer data
						var excer = monthSrc[$j].description.split(",");

						//next/prev data
						var excer2;
						var excer0;

						var next = $j + 1;
						var prev = $j - 1;


						if(excer[0] > 0 && excer[1] > 0 && excer[2] > 0 && excer[3] > 0 && excer[4] > 0 && excer[5] > 0 && excer[6] > 0 && excer[7] > 0 && excer[8] > 0 && excer[9] > 0 && excer[10] > 0 && excer[11] > 0){
							ubCountW1++;



							//if previous isn't one before the first item, and next isn't one before the last item
							if(next >= 0 && next < monthSrc.length){
								//next excer data
								var excer2 = monthSrc[next].description.split(",");

								if(excer2[0] > 0 && excer2[1] > 0 && excer2[2] > 0 && excer2[3] > 0 && excer2[4] > 0 && excer2[5] > 0 && excer2[6] > 0 && excer2[7] > 0 && excer2[8] > 0 && excer2[9] > 0 && excer2[10] > 0 && excer2[11] > 0){

									var day = monthSrc[next].start + " ";
									day = day.split(" ");
									day = day[0] + " " + day[1] + " " + day[2] + " " + day[3];

									if(statusLog.indexOf("Two or more sequential upper body exercise days starting on " + day + "<br /><br/>") == -1){
										statusLog += "Two or more sequential upper body exercise days starting on " + day + "<br /><br/>";
										console.log(statusLog);
									}

								}
							}
						}

						if(excer[12] > 0 && excer[13] > 0 && excer[14] > 0 && excer[15] > 0 && excer[16] > 0 && excer[17] > 0 && excer[18] > 0 && excer[19] > 0 && excer[20] > 0 && excer[21] > 0 && excer[22] > 0 && excer[23] > 0){
							lbCountW1++;
							
							//if previous isn't one before the first item, and next isn't one before the last item
							if(next >= 0 && next < monthSrc.length){
								//next excer data
								var excer2 = monthSrc[next].description.split(",");

								if(excer2[12] > 0 && excer2[13] > 0 && excer2[14] > 0 && excer2[15] > 0 && excer2[16] > 0 && excer2[17] > 0 && excer2[18] > 0 && excer2[19] > 0 && excer2[20] > 0 && excer2[21] > 0 && excer2[22] > 0 && excer2[23] > 0){

									var day = monthSrc[next].start + " ";
									day = day.split(" ");
									day = day[0] + " " + day[1] + " " + day[2] + " " + day[3];

									if(statusLog.indexOf("Two or more sequential lower body exercise days starting on " + day + "<br /><br/>") == -1){
										statusLog += "Two or more sequential lower body exercise days starting on " + day + "<br /><br/>";
										console.log(statusLog);
									}

								}
							}
						}

						//console.log("counterUBW1: " + ubCountW1);
						console.log("counterLBW1: " + lbCountW1);		
					}

					//Past this saturday, before or equal to next saturday
					if(tmpDate > dateOneWeek && tmpDate <= dateTwoWeek){
						counterW2++;
						lastAccepted = $j;
						console.log("ACCEPTED W2");
						console.log("counterW2: " + counterW2);
						
						//current excer data
						var excer = monthSrc[$j].description.split(",");

						if(excer2[0] > 0 && excer2[1] > 0 && excer2[2] > 0 && excer2[3] > 0 && excer2[4] > 0 && excer2[5] > 0 && excer2[6] > 0 && excer2[7] > 0 && excer2[8] > 0 && excer2[9] > 0 && excer2[10] > 0 && excer2[11] > 0){
							ubCountW2++;
						}
						if(excer[12] > 0 && excer[13] > 0 && excer[14] > 0 && excer[15] > 0 && excer[16] > 0 && excer[17] > 0 && excer[18] > 0 && excer[19] > 0 && excer[20] > 0 && excer[21] > 0 && excer[22] > 0 && excer[23] > 0){
							lbCountW2++;
						}

						console.log("counterUBW2: " + ubCountW2);
						console.log("counterLBW2: " + lbCountW2);	
						
					}
			}

			console.log("LBW1: " + lbCountW1);
			console.log("LBW2: " + lbCountW2);
			console.log("UBW1: " + ubCountW1);
			console.log("UBW2: " + ubCountW2);

			//console.log("selSun: " + selSun + "AND sel2Sun: " + sel2Sun);

			//make sure 3 workouts per week
			if(counterW1 > 3){
				if(statusLog.indexOf("Too many workout periods( " + counterW1 + " ) within the week of " + selSun + "<br /><br/>") == -1){
					statusLog += "Too many workout periods( " + counterW1 + " ) within the week of " + selSun + "<br /><br/>";
					console.log(statusLog);
				}
			}else if(counterW2 > 3){
				if(statusLog.indexOf("Too many workout periods( " + counterW2 + " ) within the week of " + sel2Sun + "<br /><br/>") == -1){
					statusLog += "Too many workout periods( " + counterW2 + " ) within the week of " + sel2Sun + "<br /><br/>";
					console.log(statusLog);
				}
			}else if(ubCountW1 == 2 && lbCountW1 == 1){//THIS WEEK: 2UP 1LOW
				if(ubCountW2 == 2 && lbCountW2 == 1){//NEXT WEEK: 1UP 2LOW REPEATED
					if(statusLog.indexOf("Distribution repeated within the week of " + selSun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Distribution repeated within the week of " + selSun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 > 1 && lbCountW2 <= 2){//NEXT WEEK: >1UP 2LOW
					if(statusLog.indexOf("Too many upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too many upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 < 1 && lbCountW2 == 2){//NEXT WEEK: <1UP 2LOW
					if(statusLog.indexOf("Too few upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too few upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 == 1 && lbCountW2 > 2){//NEXT WEEK: 1UP >2LOW
					if(statusLog.indexOf("Too many lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too many lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 == 1 && lbCountW2 < 2){//NEXT WEEK: 1UP <2LOW
					if(statusLog.indexOf("Too few lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too few lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 > 2 && lbCountW2 > 2){//NEXT WEEK: <1UP 2LOW
					if(statusLog.indexOf("Too many upper and lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too many upper and lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}
			}else if(ubCountW1 == 1 && lbCountW1 == 2){//THIS WEEK: 1UP 2LOW
				if(ubCountW2 == 1 && lbCountW2 == 2){//NEXT WEEK: 1UP 2LOW REPEATED
					if(statusLog.indexOf("Distribution repeated within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Distribution repeated within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 > 2 && lbCountW2 <= 1){//NEXT WEEK: >1UP 2LOW
					if(statusLog.indexOf("Too many upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too many upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 < 2 && lbCountW2 == 1){//NEXT WEEK: <1UP 2LOW
					if(statusLog.indexOf("Too few upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too few upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 == 2 && lbCountW2 > 1){//NEXT WEEK: 1UP >2LOW
					if(statusLog.indexOf("Too many lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too many lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 == 2 && lbCountW2 < 1){//NEXT WEEK: 1UP <2LOW
					if(statusLog.indexOf("Too few lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too few lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}else if(ubCountW2 > 2 && lbCountW2 > 2){//NEXT WEEK: <1UP 2LOW
					if(statusLog.indexOf("Too many upper and lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
						statusLog += "Too many upper and lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					}
				}
			}else if(ubCountW1 >= 2 && lbCountW1 >= 2){//THIS WEEK: >=2UP AND >=2 LOW

				if(statusLog.indexOf("Too many upper and lower body days within the week of " + selSun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Too many upper and lower body days within the week of " + selSun + " compared to the surrounding weeks<br /><br/>";
				}

			}else if(ubCountW1 <= 1 && lbCountW1 <= 2){//THIS WEEK: <=1UP AND <=1 LOW

				if(statusLog.indexOf("Too few upper and lower body days within the week of " + selSun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Too few upper and lower body days within the week of " + selSun + " compared to the surrounding weeks<br /><br/>";
				}
			}else if(ubCountW1 <= 2 && lbCountW1 <= 1){//THIS WEEK: <=1UP AND <=1 LOW

				if(statusLog.indexOf("Too few upper and lower body days within the week of " + selSun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Too few upper and lower body days within the week of " + selSun + " compared to the surrounding weeks<br /><br/>";
				}
			}
		}

		//if errors exist, show div and display errors, else hide it
		if(statusLog != ""){
			$("#statusLog").show();
			$("#statusLog").html(statusLog);
			console.log(statusLog);
		}else{
			$("#statusLog").show();
			$("#statusLog").html("<p style='color:green';> Good distribution throughout this month!</p>");
			console.log(statusLog);
		}				
}
</script>
</head>
<div id="wrapper">
<div id="header" class="title">
	Southern's fitness plan simulator
</div>
	<div id="p1">
	<p>It doesn’t take someone smart to see that at our bodies were designed for movement. God did not only create us to move, but he gave our bodies the <em>need</em> to move. However, very little of what most of us have to do each day at college requires significant physical effort. 
		The role computers play in the responsibilities of college life doesn’t help the situation, either. </p>

	<p>Southern though has created a great environment to stay fit from Hulsey Wellness Center to your dorm’s basement gym. For this week, in PEAC 125 you will be learning about strength training by creating a fitness plan. This simulation will be set up in a three month time period, 
		but you will complete it in a week. The first month you will design your workout around building strength, the second month will be expanding strength, and the third month endurance. Begin by creating a new profile.</p>

		<div id="charLoad"><!--load reponse placed here on successful ajax post--></div>
		<p style="text-align:center;">

			<a id="newChar" href="#" class="pButton green">New Profile</a>&nbsp;&nbsp;&nbsp;<a id="loadChar" href="#" class="pButton green">Load Profile</a><br /><br />
		</p>

		<div id="newDialog" title="Create a new Profile">
			<p id="selGender">
				Select gender:<br />
				<input type="radio" name="gender" value="Male">Male<br />
				<input type="radio" name="gender" value="Female">Female
			</p>
			<hr>
			<p>
				Select an activity:<br />
				<select id="charCreate">
				  <option value="default"> </option>
				  <option value="Basketball">Basketball</option>
				  <option value="Soccer">Soccer</option>
				  <option value="Volleyball">Volleyball</option>
				  <option value="Hiking">Hiking</option>
				</select>
			</p>
		</div>

			<div id="loadDialog" title="Load a profile">
			</div>

	<div id="buttons" class="sel"><a href="#" class="pButton green next">Next ></a></div>
	</div>

	<div id="p2" style="display:none;">
		<!--Basketball Outline-->
		<div id="Basketball" style="display:none;">
			<b>Goal:</b> Be able to Dunk<br /><br />
			<b>Muscle Groups to be worked out:</b>
			<ul>
				<li>Chest</li>
				<li>Shoulder</li>
				<li>Triceps</li>
				<li>Abs and Biceps</li>
				<li>Glutes</li>
				<li>Quads and Hamstrings</li>
				<li>traps</li>
			</ul>

			You will first need to create a fitness plan for the next three months:<br />
			- 1st Month will focus on raw strength<br />
			- 2nd Month will focus on expanding strength<br />
			- 3rd Month will focus on endurance<br />
			<br />
			In following this plan, you will be working out three days a week, rotating each week.
		</div>

		<!--Basketball Outline-->
		<div id="Soccer" style="display:none;">
			<b>Goal:</b> Be able to run quicker, longer and kick harder.<br /><br />
			<b>Muscle Groups to be worked out:</b>
			<ul>
				<li>Chest</li>
				<li>Shoulder</li>
				<li>Triceps</li>
				<li>Abs and Biceps</li>
				<li>Glutes</li>
				<li>Quads and Hamstrings</li>
				<li>traps</li>
			</ul>

			You will first need to create a fitness plan for the next three months:<br />
			- 1st Month will focus on raw strength<br />
			- 2nd Month will focus on expanding strength<br />
			- 3rd Month will focus on endurance<br />
			<br />
			In following this plan, you will be working out three days a week, rotating each week.
		</div>
		<div id="Volleyball" style="display:none;">
			<b>Goal:</b> Be able to jump higher to spike the volleyball.<br /><br />
			<b>Muscle Groups to be worked out:</b>
			<ul>
				<li>Chest</li>
				<li>Shoulder</li>
				<li>Triceps</li>
				<li>Abs and Biceps</li>
				<li>Glutes</li>
				<li>Quads and Hamstrings</li>
				<li>traps</li>
			</ul>

			You will first need to create a fitness plan for the next three months:<br />
			- 1st Month will focus on raw strength<br />
			- 2nd Month will focus on expanding strength<br />
			- 3rd Month will focus on endurance<br />
			<br />
			In following this plan, you will be working out three days a week, rotating each week.
		</div>

		<div id="Hiking" style="display:none;">
			<b>Goal:</b> Increase endurance and speed. <br /><br />
			<b>Muscle Groups to be worked out:</b>
			<ul>
				<li>Chest</li>
				<li>Shoulder</li>
				<li>Triceps</li>
				<li>Abs and Biceps</li>
				<li>Glutes</li>
				<li>Quads and Hamstrings</li>
				<li>traps</li>
			</ul>

			You will first need to create a fitness plan for the next three months:<br />
			- 1st Month will focus on raw strength<br />
			- 2nd Month will focus on expanding strength<br />
			- 3rd Month will focus on endurance<br />
			<br />
			In following this plan, you will be working out three days a week, rotating each week.
		</div>


	<div id="buttons"><a href="#" class="pButton green prev">< Previous</a> <a href="#" class="pButton green next">Next ></a></div>
	</div>

	<div id="p3" style="display:none;">
		
		<strong><u>Step one</u></strong>: Go to the gym and work to find a weight where you can only do five consecutive reps, 
		where the fifth is a struggle to complete. Once you have found these values, enter them in the table below. <br /><br />

		<table id="personalAssessment">
			<tr>
				<th>Exercise</th>
				<th>Max Weight</th>
				<th>Reps</th>
			</tr>
			<tr>
				<td>
					<strong>Chest Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="chestPress">
				</td>
				<td>
					5
				</td>
			</tr>
			<tr>
				<td>
					<strong>Shoulder Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="shoulderPress">
				</td>
				<td>
					5
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Tricep Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="tricepPress">
				</td>
				<td>
					5
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Arm Curl Machine</strong>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="armCurl">
				</td>
				<td>
					5
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Leg Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="legPress">
				</td>
				<td>
					5
				</td>
			</tr>
			<tr>
				<td>
					<strong>Leg Extension Machine</strong>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="legExtension">
				</td>
				<td>
					5
				</td>
			</tr>
			<tr>
				<td>
					<strong>Leg Curl Machine</strong>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="legCurl">
				</td>
				<td>
					5
				</td>
			</tr>
			<tr>
				<td>
					<strong>Hip Adductor Machine</strong>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="hipAdductor">
				</td>
				<td>
					5
				</td>
			</tr>
		</table>

	<div id="buttons"><a href="#" class="pButton green prev">< Previous</a> <a href="#" class="pButton green next">Next ></a></div>
	</div>

	<div id="p4" style="display:none;">

		Scheduling Routines:
		<ul>
			<li>Choose three days for each week to workout.</li>
			<li>Weeks should be alternated between two upper body days and two lower body days. (i.e.: Work Upper body on Mon/Fri, and lower body on Wed. Flip for the following week)</li>
			<li>To add a workout day, click on any cell in the calendar below. A pop-up window will appear, with fields to enter in information such as time/weight/etc.
			<li>Hover over question marks in the popup windows for tooltips on the exercise terms, click for a more in-depth description.</li>
		</ul><br /> <br /><br /> <br />

		
		<div id='calendar'></div>

		<div id="dateDialog" title="Add Routine">

			Enter Start time: <input id="startTime" type="text" class="dateCreate" autocomplete="off"><br />
			Enter End time: <input id="endTime" type="text" class="dateCreate" autocomplete="off"><br /><br />
			<table id="dateExCreation">
			<tr>
				<th>Exercise</th>
				<th>Weight</th>
				<th>Reps</th>
				<th>Sets</th>
			</tr>
			<tr>
				<td>
					<strong>Chest Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateChestPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateChestPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateChestPressSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Shoulder Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateShoulderPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateShoulderPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateShoulderPressSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Tricep Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateTricepPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateTricepPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateTricepPressSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Arm Curl Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateArmCurlWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateArmCurlReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateArmCurlSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Leg Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateLegPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateLegPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateLegPressSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Leg Extension Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateLegExtensionWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateLegExtensionReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateLegExtensionSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Leg Curl Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateLegCurlWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateLegCurlReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateLegCurlSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Hip Adductor Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateHipAdductorWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateHipAdductorReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateHipAdductorSets">
				</td>
			</tr>
		</table>
		</div>
		<div id="eventDialog" title="Edit Routine">
			Enter Start time: <input id="EstartTime" type="text" class="dateCreate" autocomplete="off"><br />
			Enter End time: <input id="EendTime" type="text" class="dateCreate" autocomplete="off"><br /><br />
			<table id="dateExCreation">
			<tr>
				<th>Exercise</th>
				<th>Weight</th>
				<th>Reps</th>
				<th>Sets</th>
			</tr>
			<tr>
				<td>
					<strong>Chest Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateChestPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateChestPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateChestPressSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Shoulder Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateShoulderPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateShoulderPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateShoulderPressSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Tricep Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateTricepPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateTricepPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateTricepPressSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Arm Curl Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateArmCurlWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateArmCurlReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateArmCurlSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Leg Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateLegPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateLegPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateLegPressSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Leg Extension Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateLegExtensionWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateLegExtensionReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateLegExtensionSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Leg Curl Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateLegCurlWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateLegCurlReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateLegCurlSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Hip Adductor Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateHipAdductorWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateHipAdductorReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateHipAdductorSets">
				</td>
			</tr>
		</table>
		</div>

		<div id="statusLog"></div>

	<div id="buttons" class="sel"><a href="#" class="pButton green prev">< Previous</a> <a href="#" class="pButton green sim">Simulate</a></div>
	</div>

<!--END WRAPPER-->
</div>
</html>

<?php 


?>