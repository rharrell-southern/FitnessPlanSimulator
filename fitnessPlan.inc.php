<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.core.js"></script>
	<script src="//jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.fade.js"></script>
  	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css" />

  	<script type='text/javascript' src="/forms/assets/snippets/fitnessPlanSimulator/fullcalendar-1.6.4/fullcalendar/fullcalendar.js"></script>
  	<link rel="stylesheet" href="/forms/assets/snippets/fitnessPlanSimulator/fullcalendar-1.6.4/fullcalendar/fullcalendar.css" />


  	<script type='text/javascript' src="/forms/assets/snippets/fitnessPlanSimulator/timepicker/jquery-ui-timepicker-addon.js"></script>
	<link rel="stylesheet" type="text/css" href="/forms/assets/snippets/fitnessPlanSimulator/main.css" media="screen" />
	<title>Southern Adventist University: Fitness Plan Simulator</title>
	<script type="text/javascript">

		//global user info vars
		var enabled = false;
		var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
		var pointsArray = new Array();
		var username = "";
		var isLoaded = false;
		var charID = -1;
		var activity = "";
		var startMonthRange = "";
		var assess = "";
		var dateSelect = new Date();
		var statusLog = "";
		var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Nov","Dec"];
		var tmpInput = "";

		var myControl={
			create: function(tp_inst, obj, unit, val, min, max, step){
				$('<input class="ui-timepicker-input" value="'+val+'" style="width:25px">')
					.appendTo(obj)
					.spinner({
						min: min,
						max: max,
						step: step,
						change: function(e,ui){ // key events
								// don't call if api was used and not key press
								if(e.originalEvent !== undefined)
									tp_inst._onTimeChange();
								tp_inst._onSelectHandler();
							},
						spin: function(e,ui){ // spin events
								tp_inst.control.value(tp_inst, obj, unit, ui.value);
								tp_inst._onTimeChange();
								tp_inst._onSelectHandler();
							}
					});
				return obj;
			},
			options: function(tp_inst, obj, unit, opts, val){
				if(typeof(opts) == 'string' && val !== undefined)
					return obj.find('.ui-timepicker-input').spinner(opts, val);
				return obj.find('.ui-timepicker-input').spinner(opts);
			},
			value: function(tp_inst, obj, unit, val){
				if(val !== undefined)
					return obj.find('.ui-timepicker-input').spinner('value', val);
				return obj.find('.ui-timepicker-input').spinner('value');
			}
		};

		function resetDateInfo(preserveDate) {
			if(preserveDate == null) {
				$("#startTime").val("");
				$("#endTime").val("");
			}
			$("#dateChestPressWeight").val("0");
			$("#dateChestPressReps").val("0");
			$("#dateChestPressSets").val("0");
			$("#dateShoulderPressWeight").val("0");
			$("#dateShoulderPressReps").val("0");
	    	$("#dateShoulderPressSets").val("0");
			$("#dateTricepPressWeight").val("0");
			$("#dateTricepPressReps").val("0");
			$("#dateTricepPressSets").val("0");
			$("#dateArmCurlWeight").val("0");
			$("#dateArmCurlReps").val("0");
			$("#dateArmCurlSets").val("0");
	    	$("#dateLegPressWeight").val("0");
			$("#dateLegPressReps").val("0");
			$("#dateLegPressSets").val("0");
			$("#dateLegExtensionWeight").val("0");
			$("#dateLegExtensionReps").val("0");
			$("#dateLegExtensionSets").val("0");
			$("#dateLegCurlWeight").val("0");
			$("#dateLegCurlReps").val("0");
			$("#dateLegCurlSets").val("0");
			$("#dateHipAdductorWeight").val("0");
			$("#dateHipAdductorReps").val("0");
			$("#dateHipAdductorSets").val("0");

		}
		function resetEDateInfo(preserveDate) {
			if(preserveDate == null) {
				$("#EstartTime").val("");
				$("#EendTime").val("");
			}
			$("#EdateChestPressWeight").val("0");
			$("#EdateChestPressReps").val("0");
			$("#EdateChestPressSets").val("0");
			$("#EdateShoulderPressWeight").val("0");
			$("#EdateShoulderPressReps").val("0");
	    	$("#EdateShoulderPressSets").val("0");
			$("#EdateTricepPressWeight").val("0");
			$("#EdateTricepPressReps").val("0");
			$("#EdateTricepPressSets").val("0");
			$("#EdateArmCurlWeight").val("0");
			$("#EdateArmCurlReps").val("0");
			$("#EdateArmCurlSets").val("0");
	    	$("#EdateLegPressWeight").val("0");
			$("#EdateLegPressReps").val("0");
			$("#EdateLegPressSets").val("0");
			$("#EdateLegExtensionWeight").val("0");
			$("#EdateLegExtensionReps").val("0");
			$("#EdateLegExtensionSets").val("0");
			$("#EdateLegCurlWeight").val("0");
			$("#EdateLegCurlReps").val("0");
			$("#EdateLegCurlSets").val("0");
			$("#EdateHipAdductorWeight").val("0");
			$("#EdateHipAdductorReps").val("0");
			$("#EdateHipAdductorSets").val("0");
		}
		$(function() {
			//Get username from url var
			username = QueryString.name;
			if(username) {
				username = username.replace('%20', ' ');
				enabled = true;
			} else {
				alert("User not specified, please visit this page via eClass to ensure your information is entered correctly.");
			}

			$('#startTime').timepicker({
			    'timeFormat': 'HH:mm',
			    'defaultValue': '08:00',
				'controlType': myControl,
				'hourText':'Select',
				'minuteText':':',
				'stepMinute': 15,
			    onClose: function(dateText, inst) {
			    	var endTime = parseInt($('#endTime').val().replace(":",""));
			    	var startTime = parseInt($('#startTime').val().replace(":",""));
					if ( endTime > 0) {
						if (startTime > endTime) {
							$('#endTime').val($('#startTime').val());
						}
					}
					else {
						$('#endTime').val(dateText);
					}
				},
			    onSelect: function(dateText, inst) {
			    	var startTime = $('#startTime').val().split(":");
			    	$('#endTime').timepicker('option', 'minHour', startTime[0]);
			    	$('#endTime').timepicker('option', 'minMin',  startTime[1]);
			    }
			});
			$('#endTime').timepicker({
			    'timeFormat': 'HH:mm',
			    'defaultValue': '08:00',
				'controlType': myControl,
				'hourText':'Select',
				'minuteText':':',
				'stepMinute': 15,
			    onClose: function(dateText, inst) {
			    	var endTime = parseInt($('#endTime').val().replace(":",""));
			    	var startTime = parseInt($('#startTime').val().replace(":",""));
					if ( startTime > 0) {
						if (startTime > endTime) {
							$('#startTime').val($('#endTime').val());
						}
					}
					else {
						$('#endTime').val(dateText);
					}
				},
			    onSelect: function(dateText, inst) {
			    	var endTime = $('#endTime').val().split(":");
			    	$('#startTime').timepicker('option', 'maxHour', endTime[0]);
			    	$('#startTime').timepicker('option', 'maxMin',  endTime[1]);
			    }
			});
			$('#EstartTime').timepicker({
			    'timeFormat': 'HH:mm',
			    'defaultValue': '08:00',
				'controlType': myControl,
				'hourText':'Select',
				'minuteText':':',
				'stepMinute': 15,
			    onClose: function(dateText, inst) {
			    	var endTime = parseInt($('#EendTime').val().replace(":",""));
			    	var startTime = parseInt($('#EstartTime').val().replace(":",""));
					if ( endTime > 0) {
						if (startTime > endTime) {
							$('#EendTime').val($('#EstartTime').val());
						}
					}
					else {
						$('#EendTime').val(dateText);
					}
				},
			    onSelect: function(dateText, inst) {
			    	var startTime = $('#EstartTime').val().split(":");
			    	$('#EendTime').timepicker('option', 'minHour', startTime[0]);
			    	$('#EendTime').timepicker('option', 'minMin',  startTime[1]);
			    }
			});
			$('#EendTime').timepicker({
			    'timeFormat': 'HH:mm',
			    'defaultValue': '08:00',
				'controlType': myControl,
				'hourText':'Select',
				'minuteText':':',
				'stepMinute': 15,
			    onClose: function(dateText, inst) {
			    	var endTime = parseInt($('#EendTime').val().replace(":",""));
			    	var startTime = parseInt($('#EstartTime').val().replace(":",""));
					if ( startTime > 0) {
						if (startTime > endTime) {
							$('#EstartTime').val($('#EendTime').val());
						}
					}
					else {
						$('#EendTime').val(dateText);
					}
				},
			    onSelect: function(dateText, inst) {
			    	var endTime = $('#eEndTime').val().split(":");
			    	$('#EstartTime').timepicker('option', 'maxHour', endTime[0]);
			    	$('#EstartTime').timepicker('option', 'maxMin',  endTime[1]);
			    }
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
                create: function(event, u) {
                	$(this).parent().css("position","fixed");
                },
                buttons: {
		        "Create": function() {
		        	//on Create
		        	dateSelect += "";
		        	var pieces= dateSelect.split(" ");
		        	var month = "";
		        	month = months.indexOf(pieces[1]);

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
	    				resetDateInfo();

			          	$( this ).dialog( "close" );
			          	simulate();
				    }
		        },
		        "Clear": function() {
		        	resetDateInfo(1);
		        },
		        Cancel: function() {
		        	//On cancel: 
		        	resetDateInfo();
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
                create: function(event, u) {
                	$(this).parent().css("position","fixed");
                },
                buttons: {
		        "Save": function() {
				    date = dateEvent.start + "";
				    var pieces = date.split(" ");

				    var month = "";
		        	month = months.indexOf(pieces[1]);

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

	    				resetEDateInfo();


			          	$( this ).dialog( "close" );

			          	simulate();
			          }

		        },
		        "Clear": function() {
		        	resetEDateInfo(1);
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
		          	resetEDateInfo();
		          	$( this ).dialog( "close" );

		        },
		      },
      			modal: true
		    });

			$( ".dateExInput" ).on('blur',function(event) {
				var thisInput = $(this).val();
				if (thisInput.length < 1) {
					if(tmpInput.length > 0 ) {
						$(this).val(tmpInput);
					} else {
						$(this).val("0");
					}
				}
			});

			$( ".dateExInput" ).on('focus',function(event) {
				var thisInput = $(this).val();
				if(thisInput == "0") {
					$(this).val("");
				}
			});

			//On selection of "New profile" on div id="p2"
		    $( "#newChar" ).on('click',function() {
		    	if(enabled) {
		      		$( "#newDialog" ).dialog( "open" );
		  		} else {
					alert("User not specified, please visit this page via eClass to ensure your information is entered correctly.");
				}
		    });


		    //modal for "New profile" on div id="p2"
			$( "#newDialog" ).dialog({
				autoOpen: false,
		      	height: 409,
		      	width: 506,
		      	position: {
                        my: "center center", 
                        at: "center center",
                        of: window
                },
                create: function(event, u) {
                	$(this).parent().css("position","fixed");
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

		        	//ensure user has selected BOTH an activity and gender
		        	if(activity != "default" && gender != null){
		          		$( this ).dialog( "close" );

		          		//submit data to database
		          		submitChar(activity, gender);

		          		$("#"+activity).show();

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
		    	if (enabled) {
				    //clear in case of new append loop
				    $("#loadDialog").html("");
				    //fetch records
				    loadChar();
				    //display dialog
				    $( "#loadDialog" ).dialog( "open" );
				} else {
					alert("User not specified, please visit this page via eClass to ensure your information is entered correctly.");
				}
		    });


		    //modal for "Load profile" on div id="p2"
		     $( "#loadDialog" ).dialog({
				autoOpen: false,
		      	height: 409,
		      	width: 506,
		      	position: {
                        my: "center center", 
                        at: "center center",
                        of: window
                    },
                create: function(event, u) {
                	$(this).parent().css("position","fixed");
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
		        	startMonthRange = idAct[2];


		        	//ensure user has selected a profile
		        	if(charID != null){
		        		//show requested activity for next p, close dialog, display status div
		        		$("#"+activity).show();
		          		$( this ).dialog( "close" );
		          		$("#charLoad").html("profile load successful. . .");
		          		checkAssess();
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

				     	//if ndata.Assessment is NOT empty, user has already done assessment, and needs to skip it.
				     	if(!jQuery.isEmptyObject(ndata.Assessment)) {
							//populate self assessment data
							assess = ndata.Assessment.AssessmentData.split(',');
							var substr = ndata.Assessment.AssessmentData.split(',');


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
				     	$("#charLoad").html("Assessment check failed. . .");
				     	isLoaded = false;
				     }
				 });
				}

		//Write profile information to database
		function submitChar(activity, gender){
			var thisDate = new Date();
			startMonthRange = thisDate.getMonth() + 1;

			var dataObject = {
				'type': "new",
				'username': username,
				'activity': activity,
				'gender': gender,
				'startMonth' : startMonthRange
			};


			$.ajax({
			type: "POST",
			     url: "assets/snippets/fitnessPlanSimulator/char.modify.php",
			     data: dataObject,
			     success: function(ndata) {

			     	//track charID
			     	charID = ndata;

			     	//display status div
			     	$("#charLoad").html("profile creation successful. . .");
			     	isLoaded=true;
			     	checkAssess();
			     },
			     error: function(xhr, textStatus, error){
			     	//alert user, reset vars, update status div
			     	alert("error in POST");
			     	$("#charLoad").html("profile creation failed. . .");
			     	isLoaded = false;
			     }
			 });

		}

	function deleteChar(id){
			if (confirm('Are you sure you want to delete this profile?')) {
			//log id to delete

			var dataObject = {
				'type': "delete",
				'ID': id,
			}

			$.ajax({
			type: "POST",
			     url: "assets/snippets/fitnessPlanSimulator/char.modify.php",
			     data: dataObject,
			     success: function(ndata) {

			     	//refresh char data
			     	$("#loadDialog").html("");
			     	loadChar();
			     },
			     error: function(xhr, textStatus, error){
			     	//alert user, reset vars, update status div
			     	alert("error in POST")
			     	$("#charLoad").html("profile deletion failed. . .");
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
		     	//for each entry in json, print data, with span around this.activity to select later on
		     	$.each(ldata, function (){

		     		$("#loadDialog").append(
		     			"<input type='radio' name='character' id='" + this.id + "' value='" + this.id + "," + this.activity + "," + this.startMonth + "'> <label for='" + this.id + "' class='radioWrapper'><b>Username:</b> " + this.username + "<br />&nbsp;&nbsp;&nbsp;&nbsp; <b>Gender:</b> "
		     			+ this.gender + "<button type='button' style='float: right;' onclick='deleteChar("+ this.id + ")'>Delete</button><br />&nbsp;&nbsp;&nbsp;&nbsp; <b>Activity:</b> " + this.activity+ "<br /><br /> </label>" 
		     			);

		     	})
		     	isLoaded = true;
		    },
		    error: function(xhr, textStatus, error){
		     	alert("profile load failed. . . ");
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
		     
		    },
		    error: function(xhr, textStatus, error){
		     	alert("Assessment submission failed. . . ");
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

		//remove circular references generated by fullcalendar for some ungodly reason.
		for(i=0;i<sourceObj.length;i++) {

			delete sourceObj[i].source;
			delete sourceObj[i].className;
			
		}

		sourceString = JSON.stringify(sourceObj);

		var dataObject = {
			'type'		: "eventAdd",
			'UID'		: charID,
			'source'	: sourceString
		}

		$.ajax({
			type: "POST",
		    url: "assets/snippets/fitnessPlanSimulator/char.modify.php",
		    data: dataObject,
		    success: function(ndata) {
		     	$('#calendar').fullCalendar('removeEvents');
		     	$('#calendar').fullCalendar('refetchEvents');
	    		$('#calendar').fullCalendar('rerenderEvents');
		    },
		    error: function(xhr, textStatus, error){
		     	//alert user, reset vars, update status div
		     	alert("error in POST");
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
		var current = parseInt(target.charAt(1));
		if (object.hasClass('next')) {
			navTo = current + 1;
		}
		if (object.hasClass('prev')){
			navTo = current - 1;
		}
		$('#p'+current).hide('fade', 700, function(){
			$('#p'+ navTo).show('fade', 700, function(){
				switch(navTo) {
				case 1:
					$('#header').html("Strength Training Simulation");
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
					$('#header').html("Strength Training Simulation");
					break;
			}
				});
		});
	}

	function loadCalendar(){
	  var now = new Date();
      var start = new Date(now.getFullYear(),(parseInt(startMonthRange) + 1),0,0,0,0,0); 
      var end = new Date();
      end.setMonth(start.getMonth() + 2); //Adjust as needed
		$('#calendar').fullCalendar({
			viewDisplay   : function(view) {

		      var cal_date_string = view.start.getMonth()+'/'+view.start.getFullYear();
		      var cur_date_string = start.getMonth()+'/'+start.getFullYear();
		      var end_date_string = end.getMonth()+'/'+end.getFullYear();

		      if(cal_date_string == cur_date_string) { jQuery('.fc-button-prev').addClass("fc-state-disabled"); }
		      else { jQuery('.fc-button-prev').removeClass("fc-state-disabled"); }

		      if(end_date_string == cal_date_string) { jQuery('.fc-button-next').addClass("fc-state-disabled"); }
		      else { jQuery('.fc-button-next').removeClass("fc-state-disabled"); }
		    },
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
			left: 'prev,',
			center: 'title',
			right: ',next'
			},
			editable: false,
			dayClick: function(date, allDay, jsEvent, view) {
				dateSelect = date;
		        $( "#dateDialog" ).dialog( "open" );
		    },
		    eventClick: function(event) {
		    	dateEvent = event;

		    	Stime = event.start + "";
		    	Stime = Stime.split(" ");

		    	Etime = event.end + "";
		    	Etime = Etime.split(" ");

		    	var desc = event.description.split(',');

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
		$('#calendar').fullCalendar('gotoDate', start);
		$('#calendar').fullCalendar('render');
		setTimeout(function() {
			simulate();
		}, 100);
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

	function checkWorkouts(monthSrc, $i) {

		//get our limits (sunday, sat, next sat)

		//get first sunday
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

		var lastAccepted = 0;
		var counterW1 = 0;
		var counterW2 = 0;
		var lbCountW1 = 0;
		var lbCountW2 = 0;
		var ubCountW1 = 0;
		var ubCountW2 = 0;
		for($j = 0; $j<monthSrc.length; $j++){
		
			var tmpDate = monthSrc[$j].start;

			//First sunday to saturday
			if(tmpDate >= sundayDate && tmpDate <= dateOneWeek){
				counterW1++;
				lastAccepted = $j;
				
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

							var thisDay = monthSrc[next].start;
							var initialDay = monthSrc[$j].start;
							var diffDays = Math.round(Math.abs((thisDay.getTime() - initialDay.getTime())/(oneDay)));

							if (diffDays < 1.5) {
								var errorString = "Two or more sequential upper body exercise days starting on " + months[thisDay.getMonth()] + " " + thisDay.getDate() + "<br /><br/>";
								if(statusLog.indexOf(errorString) == -1){
									statusLog += errorString;
									pointsArray.push(4);
								}
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

							var thisDay = monthSrc[next].start;
							var initialDay = monthSrc[$j].start;
							var diffDays = Math.round(Math.abs((thisDay.getTime() - initialDay.getTime())/(oneDay)));

							if (diffDays < 1.5) {
								var errorString = "Two or more sequential lower body exercise days starting on " + months[thisDay.getMonth()] + " " + thisDay.getDate() + "<br /><br/>";
								if(statusLog.indexOf(errorString) == -1){
									statusLog += errorString;
									pointsArray.push(4);
								}
							}

						}
					}
				}	
			}

			//Past this saturday, before or equal to next saturday
			if(tmpDate > dateOneWeek && tmpDate <= dateTwoWeek){
				counterW2++;
				lastAccepted = $j;
				
				//current excer data
				var excer = monthSrc[$j].description.split(",");

				if(excer[0] > 0 && excer[1] > 0 && excer[2] > 0 && excer[3] > 0 && excer[4] > 0 && excer[5] > 0 && excer[6] > 0 && excer[7] > 0 && excer[8] > 0 && excer[9] > 0 && excer[10] > 0 && excer[11] > 0){
					ubCountW2++;
				}
				if(excer[12] > 0 && excer[13] > 0 && excer[14] > 0 && excer[15] > 0 && excer[16] > 0 && excer[17] > 0 && excer[18] > 0 && excer[19] > 0 && excer[20] > 0 && excer[21] > 0 && excer[22] > 0 && excer[23] > 0){
					lbCountW2++;
				}	
				
			}
		}


		//make sure 3 workouts per week
		if(counterW1 > 3){
			if(statusLog.indexOf("Too many workout periods( " + counterW1 + " ) within the week of " + selSun + "<br /><br/>") == -1){
				statusLog += "Too many workout periods( " + counterW1 + " ) within the week of " + selSun + "<br /><br/>";
				pointsArray.push(4);
			}
		} else if(counterW2 > 3){
			if(statusLog.indexOf("Too many workout periods( " + counterW2 + " ) within the week of " + sel2Sun + "<br /><br/>") == -1){
				statusLog += "Too many workout periods( " + counterW2 + " ) within the week of " + sel2Sun + "<br /><br/>";
				pointsArray.push(4);
			}
		} else if(ubCountW1 == 2 && lbCountW1 == 1){//THIS WEEK: 2UP 1LOW
			if(ubCountW2 == 2 && lbCountW2 == 1){//NEXT WEEK: 1UP 2LOW REPEATED
				if(statusLog.indexOf("Distribution repeated within the week of " + selSun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Distribution repeated within the week of " + selSun + " compared to the surrounding weeks<br /><br/>";
					pointsArray.push(3);
				}
			}else if(ubCountW2 > 1 && lbCountW2 <= 2){//NEXT WEEK: >1UP 2LOW
				if(statusLog.indexOf("Too many upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Too many upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					pointsArray.push(4);
				}
			}else if(ubCountW2 < 1 && lbCountW2 == 2){//NEXT WEEK: <1UP 2LOW
				if(statusLog.indexOf("<span style=\"color:#2E4DFF;\">Too few upper body days within the week of " + sel2Sun + " compared to the surrounding weeks</span><br /><br/>") == -1){
					statusLog += "<span style=\"color:#2E4DFF;\">Too few upper body days within the week of " + sel2Sun + " compared to the surrounding weeks</span><br /><br/>";
					pointsArray.push(0.5);
				}
			}else if(ubCountW2 == 1 && lbCountW2 > 2){//NEXT WEEK: 1UP >2LOW
				if(statusLog.indexOf("Too many lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Too many lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					pointsArray.push(4);
				}
			}else if(ubCountW2 == 1 && lbCountW2 < 2){//NEXT WEEK: 1UP <2LOW
				if(statusLog.indexOf("<span style=\"color:#2E4DFF;\">Too few lower body days within the week of " + sel2Sun + " compared to the surrounding weeks</span><br /><br/>") == -1){
					statusLog += "<span style=\"color:#2E4DFF;\">Too few lower body days within the week of " + sel2Sun + " compared to the surrounding weeks</span><br /><br/>";
					pointsArray.push(0.5);
				}
			}else if(ubCountW2 > 2 && lbCountW2 > 2){//NEXT WEEK: <1UP 2LOW
				if(statusLog.indexOf("Too many upper and lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Too many upper and lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					pointsArray.push(4);
				}
			}
		} else if(ubCountW1 == 1 && lbCountW1 == 2){//THIS WEEK: 1UP 2LOW
			if(ubCountW2 == 1 && lbCountW2 == 2){//NEXT WEEK: 1UP 2LOW REPEATED
				if(statusLog.indexOf("Distribution repeated within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Distribution repeated within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					pointsArray.push(3);
				}
			}else if(ubCountW2 > 2 && lbCountW2 <= 1){//NEXT WEEK: >1UP 2LOW
				if(statusLog.indexOf("Too many upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Too many upper body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					pointsArray.push(4);
				}
			}else if(ubCountW2 < 2 && lbCountW2 == 1){//NEXT WEEK: <1UP 2LOW
				if(statusLog.indexOf("<span style=\"color:#2E4DFF;\">Too few upper body days within the week of " + sel2Sun + " compared to the surrounding weeks</span><br /><br/>") == -1){
					statusLog += "<span style=\"color:#2E4DFF;\">Too few upper body days within the week of " + sel2Sun + " compared to the surrounding weeks</span><br /><br/>";
					pointsArray.push(0.5);
				}
			}else if(ubCountW2 == 2 && lbCountW2 > 1){//NEXT WEEK: 1UP >2LOW
				if(statusLog.indexOf("Too many lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Too many lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					pointsArray.push(4);
				}
			}else if(ubCountW2 == 2 && lbCountW2 < 1){//NEXT WEEK: 1UP <2LOW
				if(statusLog.indexOf("<span style=\"color:#2E4DFF;\">Too few lower body days within the week of " + sel2Sun + " compared to the surrounding weeks</span><br /><br/>") == -1){
					statusLog += "<span style=\"color:#2E4DFF;\">Too few lower body days within the week of " + sel2Sun + " compared to the surrounding weeks</span><br /><br/>";
				    pointsArray.push(0.5);
				}
			}else if(ubCountW2 > 2 && lbCountW2 > 2){//NEXT WEEK: <1UP 2LOW
				if(statusLog.indexOf("Too many upper and lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>") == -1){
					statusLog += "Too many upper and lower body days within the week of " + sel2Sun + " compared to the surrounding weeks<br /><br/>";
					pointsArray.push(4);
				}
			}
		} else if(ubCountW1 > 2 && lbCountW2 > 2){//THIS WEEK: >=2UP AND >=2 LOW

			if(statusLog.indexOf("Too many upper and lower body days within the week of " + selSun + " compared to the surrounding weeks<br /><br/>") == -1){
				statusLog += "Too many upper and lower body days within the week of " + selSun + " compared to the surrounding weeks<br /><br/>";
				pointsArray.push(5);
			}

		} else if(ubCountW1 < 2 && lbCountW2 < 1 || ubCountW1 < 1 && lbCountW2 < 2 ){//THIS WEEK: <=1UP AND <=1 LOW
			var errorString = "<span style=\"color:#2E4DFF;\">Too few upper and lower body days within the week of " + selSun + " compared to the surrounding weeks</span><br /><br/>"
			if(statusLog.indexOf(errorString) == -1){
				statusLog += errorString;
				pointsArray.push(0.5);
			}
		} else {
			pointsArray.push(1.5);
		}	
	}

	function calcORM(maxWeight) {
		var orm = maxWeight / (1.0278 - (5 * 0.0278));
		return orm;
	}
	function calculateOffset(monthSrc) {


		var thisDate = monthSrc.start;

		var thisMonth = thisDate.getMonth();

		if (thisMonth == -1) {
			thisMonth = 11;
		}
		if (thisMonth == 13) {
			thisMonth = 0;
		}
		var monthOffset = thisMonth - startMonthRange;
		if(monthOffset == 4) {
			monthOffset = 3;
		} else if(monthOffset > 4) {
			monthOffset = 0;
		}

		return monthOffset;
	}
	function checkORM(monthSrc) {
		if(monthSrc.start) {
			var monthOffset = calculateOffset(monthSrc);
			var thisDate = monthSrc.start;
			var tmpData = monthSrc.description.split(",");
			var excerData = new Array(tmpData.length/3);

			var j = 0;
			var k = 0;
			for(i = 0;i < tmpData.length;i++) {
				if (k == 0) {
					excerData[j] = new Array(3);
				}
				excerData[j][k] = tmpData[i];
				if(k == 2) {
					j++;
					k = 0;
				} else {
					k++;
				}
			}
			for (i = 0; i < excerData.length; i++) {
				var red = new Array(3);
				red[0] = new Array(2);
				red[1] = new Array(2);
				red[2] = new Array(2);
				red[0]["percent"] =   0.9;
				red[1]["percent"] =   0.8;
				red[2]["percent"] =   0.5;
				red[0]["reps"] = 6;
				red[1]["reps"] = 10;
				red[2]["reps"] = 999;
				blue = new Array(3);
				blue[0] = new Array(2);
				blue[1] = new Array(2);
				blue[2] = new Array(2);
				blue[0]["percent"] =   0.8;
				blue[1]["percent"] =   0.7;
				blue[2]["percent"] =   0.3;
				blue[0]["reps"] = 3;
				blue[1]["reps"] = 6;
				blue[2]["reps"] = 15;

				// 10% increase in max weight per month
				var increase = new Array(3);
				increase[0] = 1;
				increase[1] = 1.1;
				increase[2] = 1.21;

				var assessTotal = assess[i]*increase[monthOffset];
				console.log("Assessment Total: " + assessTotal);
				// ORM Calculation
				var thisORM = calcORM(assessTotal);
				if (excerData[i][0] > thisORM * red[monthOffset]["percent"]) {
					var errorString = (red[monthOffset]["percent"] *100) + "% of one rep maximum weight exceeded for exercise #" + (i+1) + " on " + months[thisDate.getMonth()] + " " + thisDate.getDate() + "<br /> <br />";
					if(statusLog.indexOf(errorString) < 0) {
						statusLog += errorString;
					}
				    pointsArray.push(3);
				} else if (excerData[i][0] < thisORM * blue[monthOffset]["percent"] && excerData[i][0] > 0) {
					var errorString = "<span style=\"color:#2E4DFF;\">Below "+ (blue[monthOffset]["percent"] *100) + "% of one rep maximum weight for exercise #" + (i+1) + " on " + months[thisDate.getMonth()] + " " + thisDate.getDate() + "</span><br /> <br />";
					if(statusLog.indexOf(errorString) < 0) {
						statusLog += errorString;
					}
				    pointsArray.push(0.5);
				} else if (excerData[i][0] > 0) {
					pointsArray.push(1.5);
				}


				// Reps calculation
				if(excerData[i][1] > red[monthOffset]["reps"] && excerData[i][0] > 0) {
					var errorString = "Limit of " + red[monthOffset]["reps"] + " reps exceeded for exercise #" + (i+1) + " on " + months[thisDate.getMonth()] + " " + thisDate.getDate() + "<br /> <br />";
					if(statusLog.indexOf(errorString) < 0) {
						statusLog += errorString;
					}
					pointsArray.push(3);
				} else if(excerData[i][1] < blue[monthOffset]["reps"] && excerData[i][0] > 0) {
					var errorString = "<span style=\"color:#2E4DFF;\">Below "+ blue[monthOffset]["reps"] + " reps for exercise #" + (i+1) + " on " + months[thisDate.getMonth()] + " " + thisDate.getDate() + "</span><br /> <br />";
					if(statusLog.indexOf(errorString) < 0) {
						statusLog += errorString;
					}
					pointsArray.push(0.5);
				} else if (excerData[i][0] > 0) {
					pointsArray.push(1.5);
				}

				// Sets calculation
				if(excerData[i][2] > 5 && excerData[i][0] > 0) {
					var errorString = "Limit of 5 sets exceeded for exercise #" + (i+1) + " on " + months[thisDate.getMonth()] + " " + thisDate.getDate() + "<br /> <br />";
					if(statusLog.indexOf(errorString) < 0) {
						statusLog += errorString;
						pointsArray.push(3);
					}
				} else if(excerData[i][2] < 3 && excerData[i][0] > 0) {
					var errorString = "<span style=\"color:#2E4DFF;\">Below 3 sets for exercise #" + (i+1) + " on " + months[thisDate.getMonth()] + " " + thisDate.getDate() + "</span><br /> <br />";
					if(statusLog.indexOf(errorString) < 0) {
						statusLog += errorString;
					}
					pointsArray.push(0.5);
				} else if (excerData[i][0] > 0) {
					pointsArray.push(1.5);
				}

			}
		}
	}
	function simulate(){ 
		pointsArray = [];
		statusLog = "";
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

		for($i = 0; $i<monthSrc.length; $i++){
			checkWorkouts(monthSrc,$i); // Check frequency of workout
			checkORM(monthSrc[$i]); // Check One Rep Max
		}

		if (monthSrc.length > 0) {
			var lastMonth = monthSrc.length - 1;
			var monthOffset = calculateOffset(monthSrc[lastMonth]);
			// Calculate Average
			var pointsTotal = 0;
			for(i = 0; i < pointsArray.length; i++) {
				pointsTotal += pointsArray[i];
			}
			var pointsAverage = pointsTotal / pointsArray.length;

			console.log("Points Average: " + pointsAverage);
			$( "#progressbar" ).progressbar({
		    	value: ((pointsAverage / 3) * 100)
		    });
			var statusMessage = "";
			if (pointsAverage <= 1) {
				$(".ui-progressbar-value").css("background-color", "#2E4DFF !important" );
				$('#statusMessage').css("color","#2E4DFF !important");
				statusMessage = "You are not maximizing your workouts and aren't progressing as you'd like!";
			} else if (pointsAverage > 1 && pointsAverage <= 2) {
				$(".ui-progressbar-value").css("background-color", "#269136 !important" );
				$('#statusMessage').css("color","#269136 !important");
				statusMessage = "You are perfoming well in your workouts without pushing yourself too hard! Good Work!";
			} else {
				$(".ui-progressbar-value").css("background-color", "#BA1818 !important" );
				$('#statusMessage').css("color","#BA1818 !important");
				statusMessage = "You are are pushing yourself too hard! You're in danger of injury!";
			}
			$("#statusMessage").html(statusMessage);
			//if errors exist, show div and display errors, else hide it
			if(statusLog != ""){
				$("#statusLog").show();
				$("#statusLog").html("<h2>You have errors in your workout!</h2>" + statusLog);
			}else{
				$("#statusLog").show();
				if (monthOffset < 3) {
					$("#statusLog").html("<h2 style='color:green';> No errors so far!</h2>");
				} else {
					$("#statusLog").html("<h2 style='color:green';> Excellent! You have achieved your goals!</h2>");
				}
			}	
		}
	}
</script>
</head>
<div id="wrapper">
<div id="header" class="title">
	Strength Training Simulation
</div><br /><br /><br />
	<div id="p1">
	<img src="/forms/assets/snippets/FitnessPlanSimulator/images/hwc_small.jpg" style="float:left; margin-right:25px;height:240px;"><p>It doesn’t take someone smart to see that our bodies were designed for movement. 
	God did not only create us to move, but he gave our bodies the need  to move. However, very little of what most of us have to do each day at college requires significant physical effort. </p>

	<p>Southern has created a great environment to stay fit from Hulsey Wellness Center to your dorm’s basement gym. This week you will learn about strength training through a fitness plan simulation. 
	This simulation will cover a three month time period. The first month you will design your workout to maximize strength, the second month will expand strength, and the third month build endurance.</p> 

	<p><br />If you are just starting this simulation, begin by <strong>creating a new profile</strong>. 
	<br />If you are continuing to work on one you have already created, <strong>load your profile</strong>.</p>

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
				  <option value="Fitness">General Fitness</option>
				</select>
			</p>
		</div>

			<div id="loadDialog" title="Load a profile">
			</div>
	</div>

	<div id="p2" style="display:none;">
		<!--Basketball Outline-->
		<div id="Basketball" style="display:none;">
			<img src="/forms/assets/snippets/FitnessPlanSimulator/images/basketball_small.jpg" style="float:right; margin-left:25px;">
			<h2>Basketball</h2>
			<b>Goal:</b> Be able to Dunk<br /><br />
			<b>Muscle Groups to be worked out:</b>
			<ul>
				<li>Chest</li>
				<li>Shoulder</li>
				<li>Triceps</li>
				<li>Abs and Biceps</li>
				<li>Glutes</li>
				<li>Quads and Hamstrings</li>
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
			<img src="/forms/assets/snippets/FitnessPlanSimulator/images/soccer_small.jpg" style="float:right; margin-left:25px;">
			<h2>Soccer</h2>
			<b>Goal:</b> Be able to run quicker, longer and kick harder.<br /><br />
			<b>Muscle Groups to be worked out:</b>
			<ul>
				<li>Chest</li>
				<li>Shoulder</li>
				<li>Triceps</li>
				<li>Abs and Biceps</li>
				<li>Glutes</li>
				<li>Quads and Hamstrings</li>
			</ul>

			You will first need to create a fitness plan for the next three months:<br />
			- 1st Month will focus on raw strength<br />
			- 2nd Month will focus on expanding strength<br />
			- 3rd Month will focus on endurance<br />
			<br />
			In following this plan, you will be working out three days a week, rotating each week.
		</div>
		<div id="Volleyball" style="display:none;">
			<img src="/forms/assets/snippets/FitnessPlanSimulator/images/volleyball_small.jpg" style="float:right; margin-left:25px;">
			<h2>Volleyball</h2>
			<b>Goal:</b> Be able to jump higher to spike the volleyball.<br /><br />
			<b>Muscle Groups to be worked out:</b>
			<ul>
				<li>Chest</li>
				<li>Shoulder</li>
				<li>Triceps</li>
				<li>Abs and Biceps</li>
				<li>Glutes</li>
				<li>Quads and Hamstrings</li>
			</ul>

			You will first need to create a fitness plan for the next three months:<br />
			- 1st Month will focus on raw strength<br />
			- 2nd Month will focus on expanding strength<br />
			- 3rd Month will focus on endurance<br />
			<br />
			In following this plan, you will be working out three days a week, rotating each week.
		</div>

		<div id="Hiking" style="display:none;">
			<img src="/forms/assets/snippets/FitnessPlanSimulator/images/hiking_small.jpg" style="float:right; margin-left:25px;">
			<h2>Hiking</h2>
			<b>Goal:</b> Increase endurance and speed. <br /><br />
			<b>Muscle Groups to be worked out:</b>
			<ul>
				<li>Chest</li>
				<li>Shoulder</li>
				<li>Triceps</li>
				<li>Abs and Biceps</li>
				<li>Glutes</li>
				<li>Quads and Hamstrings</li>
			</ul>

			You will first need to create a fitness plan for the next three months:<br />
			- 1st Month will focus on raw strength<br />
			- 2nd Month will focus on expanding strength<br />
			- 3rd Month will focus on endurance<br />
			<br />
			In following this plan, you will be working out three days a week, rotating each week.
		</div>

		<div id="Fitness" style="display:none;">
			<img src="/forms/assets/snippets/FitnessPlanSimulator/images/hiking_small.jpg" style="float:right; margin-left:25px;">
			<h2>General Fitness</h2>
			<b>Goal:</b> Increase general fitness and health. <br /><br />
			<b>Muscle Groups to be worked out:</b>
			<ul>
				<li>Chest</li>
				<li>Shoulder</li>
				<li>Triceps</li>
				<li>Abs and Biceps</li>
				<li>Glutes</li>
				<li>Quads and Hamstrings</li>
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

		<div id="instructions" style="font-size:12px;">

			<h3>Scoring</h3>
			When you enter your excersize schedule on the next page, you will be scored on several parameters. Your average score reflects the outcome of your workout routine based on the goals you set on the previous screen.
			Below you will find a description of how your workout should be designed over the three month span of the simulation. 

			<h4>One Repetition Maximum</h4>
			The values on this page are used to calculate your one repetition maximum (ORM). The formula <em>Max Weight / (1.0278 - (Repetitions*0.0278))</em> 
			is used to calculate your maximum lift capacity.  In this case it is the weight entered here multiplied by 0.8888.
			Your ideal strength building exercises will be balanced at a percentage of your maximum lift capacity and the number of repetitions depending on your goals for each month (strength, expansion, endurance).  
			Your maximum lift weight (ORM) also <strong>increases each month by 10%</strong> to adjust for gains in your workout.  If you drift too far out of the recommended balances your results will be impacted.  
			You will either underperformance or risk injury from overexertion.

			<ul>
				<li>The ideal for the first month will be <strong>high weight (close to your values on this page) and low repetitions (3-5)</strong>.  This helps build your strength initially.</li>
				<li>The ideal for the second month wil be slightly <strong>lower weight and an increase in repetitions (6-10)</strong>.  This helps you increase your maximum lift weight (ORM) more rapidly.</li>
				<li>The ideal for the third month will be a <strong>low weight and a large number of repetions (15+)</strong>.  This helps build endurance.</li>
			</ul>

			<h4>Number of Sets</h4>
			You will also need to plan how many times your perform each set of repetitions.  <strong>You should keep your sets between 3 and 5.</strong>  Too many or to few sets will impact your results (under-performance or injury).

			<h4>Frequency</h4>
			You should be lifting weights <strong>3 times each week</strong>.  You'll want to balance your workouts between upper body (the first four exercises) and lower body (the last four exercises).  
			You should only work out each zone a maximum of 2 times per week.  <strong>You should also allow a day for your body to recover between each routine.</strong>  
			If you work out too frequently, or too infrequently it will impact your results (underperformance or injury) as well.

			<h4>Evaluating Results</h4>
			As you enter your workout information, your scoring will be calculated automatically. A bar will appear across the top of the workout schedule showing your status.  
			Below the bar you will see alerts for any routines where you have drifted outside of the recommended parameters. <strong>Remember it's ok to have some alerts, as long as you balance your workout.</strong> 
			Increases in weight should be met with decreases in repetitions or sets. Results are color coded. Blue represents underperformance and red represents overexertion.<br /><br />

			<blockquote>
				<h5>Poor Performance Example</h5>
				<img src="/forms/assets/snippets/FitnessPlanSimulator/images/below.png" width="500" /><br />
				<h5>Good Performance Example</h5>
				<img src="/forms/assets/snippets/FitnessPlanSimulator/images/good.png" width="500" /><br />
				<h5>Overexertion Example</h5>
				<img src="/forms/assets/snippets/FitnessPlanSimulator/images/above.png" width="500" /><br />
			</blockquote>

			<h4>Getting Started</h4>
			When you are ready, click next to go to the calendar and begin entering your workouts.  Once you have begun entering your workouts you will be taken straight to the calendar page when you return to this site. 
			If you would like to return to this page just <strong>click "< Previous" to get back to this information later.</strong>


		</div>

	<div id="buttons"><a href="#" class="pButton green prev">< Previous</a> <a href="#" class="pButton green next">Next ></a></div>
	</div>

	<div id="p4" style="display:none;">
		<div style="font-size:12px;">
			<strong>Scheduling Routines:</strong>
			<ul>
				<li>Choose three days for each week to workout.</li>
				<li>Weeks should be alternated between two upper body days and two lower body days. <br />(i.e.: Work Upper body on Mon/Fri, and lower body on Wed. Flip for the following week)</li>
				<li>To add a workout day, <strong>click on any cell in the calendar below</strong>. A pop-up window will appear, <br />with fields to enter in information such as time/weight/etc.</li>
				<li>Start scheduling your routine <strong>at the first full week</strong> of the first month <br />(don't put any workouts in the previous month).</li>
				<li>You will be scheduling <strong>three months</strong> for this simulation</li>
				<li>Still having trouble getting the right schedule? <a href="https://marr.southern.edu/courses/PEAC_125/fitness_simulator.pdf" target="_blank">Click Here for More Help</a></li>
			</ul>
		</div>

		<h3 id="statusMessage"></h3>
		<div id="progressbar"></div><br />
		<div id="statusLog"></div><br />
		<div id='calendar'></div>

		<div id="dateDialog" title="Add Routine">
			<input type="text" style="left:-5000px; position:absolute;" />
			Enter Start time: <input id="startTime" type="text" class="dateCreate" tabindex="41" autocomplete="off"><br />
			Enter End time: <input id="endTime" type="text" class="dateCreate" tabindex="42" autocomplete="off"><br /><br />
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
					<input type="text" class="dateExInput" tabindex="0" value="0" id="dateChestPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateChestPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateChestPressSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Shoulder Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateShoulderPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateShoulderPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateShoulderPressSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Tricep Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateTricepPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateTricepPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateTricepPressSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Arm Curl Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateArmCurlWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateArmCurlReps">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateArmCurlSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Leg Press Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateLegPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateLegPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateLegPressSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Leg Extension Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateLegExtensionWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateLegExtensionReps">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateLegExtensionSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Leg Curl Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateLegCurlWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateLegCurlReps">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateLegCurlSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Hip Adductor Machine</strong>:
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateHipAdductorWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateHipAdductorReps">
				</td>
				<td>
					<input type="text" class="dateExInput" value="0" id="dateHipAdductorSets">
				</td>
			</tr>
		</table>
		</div>
		<div id="eventDialog" title="Edit Routine">
			<input type="text" style="left:-5000px; position:absolute;" />
			Enter Start time: <input id="EstartTime" type="text" class="dateCreate" tabindex="41" autocomplete="off"><br />
			Enter End time: <input id="EendTime" type="text" class="dateCreate" tabindex="42" autocomplete="off"><br /><br />
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
					<input type="text" class="dateExInput" tabindex="0" id="EdateChestPressWeight">
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

	<div id="buttons" class="sel"><a href="#" class="pButton green prev">< Previous</a> </div>
	</div>

<!--END WRAPPER-->
</div>
</html>

<?php 


?>