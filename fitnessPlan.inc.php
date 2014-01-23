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


			//On simulate
			$('.sim').on('click',function(e){ 

				//prevent jumping to top of page
				e.preventDefault();

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
						console.log("Date: " + monthSrc[$i].start);
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
							console.log("(J) trying: " + tmpDate);

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


								if(excer[0] > 0 && excer[1] > 0 && excer[2] > 0 && excer[3] > 0 && excer[4] > 0 && excer[5] > 0){
									ubCountW1++;



									//if previous isn't one before the first item, and next isn't one before the last item
									if(next >= 0 && next < monthSrc.length){
										//next excer data
										var excer2 = monthSrc[next].description.split(",");

										if(excer2[0] > 0 && excer2[1] > 0 && excer2[2] > 0 && excer2[3] > 0 && excer2[4] > 0 && excer2[5] > 0){

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

								if(excer[6] > 0 && excer[7] > 0 && excer[8] > 0 && excer[9] > 0 && excer[10] > 0 && excer[11] > 0 && excer[12] > 0 && excer[13] > 0 && excer[14] > 0){
									lbCountW1++;
									
									//if previous isn't one before the first item, and next isn't one before the last item
									if(next >= 0 && next < monthSrc.length){
										//next excer data
										var excer2 = monthSrc[next].description.split(",");

										if(excer2[6] > 0 && excer2[7] > 0 && excer2[8] > 0 && excer2[9] > 0 && excer2[10] > 0 && excer2[11] > 0 && excer2[12] > 0 && excer2[13] > 0 && excer2[14] > 0){

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

								console.log("counterUBW1: " + ubCountW1);
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

								if(excer[0] > 0 && excer[1] > 0 && excer[2] > 0 && excer[3] > 0 && excer[4] > 0 && excer[5] > 0){
									ubCountW2++;
								}
								if(excer[6] > 0 && excer[7] > 0 && excer[8] > 0 && excer[9] > 0 && excer[10] > 0 && excer[11] > 0 && excer[12] > 0 && excer[13] > 0 && excer[14] > 0){
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
				console.log("SL: " + statusLog);
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
					if($("#benchPress").val() !="" && $("#militaryPress").val() !="" && $("#squats").val() !="" && $("#deadlifts").val() !="" && $("#romanianDeadlifts").val() !=""){
						//if numbers
						if((isNaN($("#benchPress").val() / 1) == false) && (isNaN($("#militaryPress").val() / 1) == false) && (isNaN($("#squats").val() / 1) == false) && (isNaN($("#deadlifts").val() / 1) == false) && (isNaN($("#romanianDeadlifts").val() / 1) == false)){
							submitAssessment($("#benchPress").val(), $("#militaryPress").val(), $("#squats").val(), $("#deadlifts").val(), $("#romanianDeadlifts").val());
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
		      	height: 470,
		      	width: 450,
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
				    daySource.description = $("#dateBenchPressWeight").val() + "," + $("#dateBenchPressReps").val() + "," + $("#dateBenchPressSets").val() + "," + $("#dateMilitaryPressWeight").val() + "," + $("#dateMilitaryPressReps").val() + "," + 
				    	$("#dateMilitaryPressSets").val() + "," + $("#dateSquatsWeight").val() + "," + $("#dateSquatsReps").val() + "," + $("#dateSquatsSets").val() + "," + $("#dateDeadliftsWeight").val() + "," + $("#dateDeadliftsReps").val() + "," + $("#dateDeadliftsSets").val() + "," + 
				    	$("#dateRomanianDeadliftsWeight").val() + "," + $("#dateRomanianDeadliftsReps").val() + "," + $("#dateRomanianDeadliftsSets").val();

				    var day = new Array();
				    day[0] = daySource;

				    if($("#startTime").val() == "" || $("#endTime").val() == "" || $("#dateBenchPressWeight").val() == "" || $("#dateBenchPressReps").val() == "" || $("#dateBenchPressSets").val() == "" || $("#dateMilitaryPressWeight").val() == "" || $("#dateMilitaryPressReps").val() == "" || 
				    	$("#dateMilitaryPressSets").val() == "" || $("#dateSquatsWeight").val() == "" || $("#dateSquatsReps").val() == "" || $("#dateSquatsSets").val() == "" || $("#dateDeadliftsWeight").val() == "" || $("#dateDeadliftsReps").val() == "" || $("#dateDeadliftsSets").val() == "" || 
				    	$("#dateRomanianDeadliftsWeight").val() == "" || $("#dateRomanianDeadliftsReps").val() == "" || $("#dateRomanianDeadliftsSets").val() == ""){
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
	    				$("#dateBenchPressWeight").val("");
	    				$("#dateBenchPressReps").val("");
	    				$("#dateBenchPressSets").val("");
	    				$("#dateMilitaryPressWeight").val("");
	    				$("#dateMilitaryPressReps").val("");
				    	$("#dateMilitaryPressSets").val("");
	    				$("#dateSquatsWeight").val("");
	    				$("#dateSquatsReps").val("");
	    				$("#dateSquatsSets").val("");
	    				$("#dateDeadliftsWeight").val("");
	    				$("#dateDeadliftsReps").val("");
	    				$("#dateDeadliftsSets").val("");
				    	$("#dateRomanianDeadliftsWeight").val("");
	    				$("#dateRomanianDeadliftsReps").val("");
	    				$("#dateRomanianDeadliftsSets").val("");

			          	$( this ).dialog( "close" );
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
		      	height: 470,
		      	width: 450,
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



				    dateEvent.description = $("#EdateBenchPressWeight").val() + "," + $("#EdateBenchPressReps").val() + "," + $("#EdateBenchPressSets").val() + "," + $("#EdateMilitaryPressWeight").val() + "," + $("#EdateMilitaryPressReps").val() + "," + 
				    	$("#EdateMilitaryPressSets").val() + "," + $("#EdateSquatsWeight").val() + "," + $("#EdateSquatsReps").val() + "," + $("#EdateSquatsSets").val() + "," + $("#EdateDeadliftsWeight").val() + "," + $("#EdateDeadliftsReps").val() + "," + $("#EdateDeadliftsSets").val() + "," + 
				    	$("#EdateRomanianDeadliftsWeight").val() + "," + $("#EdateRomanianDeadliftsReps").val() + "," + $("#EdateRomanianDeadliftsSets").val();


				    if($("#EstartTime").val() == "" || $("#EendTime").val() == "" || $("#EdateBenchPressWeight").val() == "" || $("#EdateBenchPressReps").val() == "" || $("#EdateBenchPressSets").val() == "" || $("#EdateMilitaryPressWeight").val() == "" || $("#EdateMilitaryPressReps").val() == "" || 
				    	$("#EdateMilitaryPressSets").val() == "" || $("#EdateSquatsWeight").val() == "" || $("#EdateSquatsReps").val() == "" || $("#EdateSquatsSets").val() == "" || $("#EdateDeadliftsWeight").val() == "" || $("#EdateDeadliftsReps").val() == "" || $("#EdateDeadliftsSets").val() == "" || 
				    	$("#EdateRomanianDeadliftsWeight").val() == "" || $("#EdateRomanianDeadliftsReps").val() == "" || $("#EdateRomanianDeadliftsSets").val() == ""){
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
	    				$("#EdateBenchPressWeight").val("");
	    				$("#EdateBenchPressReps").val("");
	    				$("#EdateBenchPressSets").val("");
	    				$("#EdateMilitaryPressWeight").val("");
	    				$("#EdateMilitaryPressReps").val("");
				    	$("#EdateMilitaryPressSets").val("");
	    				$("#EdateSquatsWeight").val("");
	    				$("#EdateSquatsReps").val("");
	    				$("#EdateSquatsSets").val("");
	    				$("#EdateDeadliftsWeight").val("");
	    				$("#EdateDeadliftsReps").val("");
	    				$("#EdateDeadliftsSets").val("");
				    	$("#EdateRomanianDeadliftsWeight").val("");
	    				$("#EdateRomanianDeadliftsReps").val("");
	    				$("#EdateRomanianDeadliftsSets").val("");


			          	$( this ).dialog( "close" );
			          }

		        },
		        "Delete": function() {

				    $('#calendar').fullCalendar('removeEvents', dateEvent.id)
    				
				    var source = $("#calendar").fullCalendar( 'clientEvents' );

    				submitSource(source);

		          	$( this ).dialog( "close" );
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
							var substr = ndata.Assessment.AssessmentData.split(',');
							$("#benchPress").val(substr[0]);
							$("#militaryPress").val(substr[1]); 
							$("#squats").val(substr[2]);
							$("#deadlifts").val(substr[3]);
							$("#romanianDeadlifts").val(substr[4]);


							$('#p1').hide('fade', 700, function(){
								$('#p4').show('fade', 700, function(){

									loadCalendar();


								});
							});

						} else {
							$('#p1').hide('fade', 700, function(){
								$('#p2').show('fade', 700, function(){

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
	function submitAssessment(benchPressVal, militaryPressVal, squatsVal, deadliftsVal, romanianDeadliftsVal){

		var dataObject = {
				'type'					: "submitAssess",
				'ID'					: charID,
				'personalAssessment'	: benchPressVal+","+ militaryPressVal+","+squatsVal+","+deadliftsVal+","+romanianDeadliftsVal
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

			$("#EstartTime").val(Stime[4]);
			$("#EendTime").val(Etime[4]);
			$("#EdateBenchPressWeight").val(desc[0]);
			$("#EdateBenchPressReps").val(desc[1]);
			$("#EdateBenchPressSets").val(desc[2]);
			$("#EdateMilitaryPressWeight").val(desc[3]);
			$("#EdateMilitaryPressReps").val(desc[4]);
	    	$("#EdateMilitaryPressSets").val(desc[5]);
			$("#EdateSquatsWeight").val(desc[6]);
			$("#EdateSquatsReps").val(desc[7]);
			$("#EdateSquatsSets").val(desc[8]);
			$("#EdateDeadliftsWeight").val(desc[9]);
			$("#EdateDeadliftsReps").val(desc[10]);
			$("#EdateDeadliftsSets").val(desc[11]);
	    	$("#EdateRomanianDeadliftsWeight").val(desc[12]);
			$("#EdateRomanianDeadliftsReps").val(desc[13]);
			$("#EdateRomanianDeadliftsSets").val(desc[14]);

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
	</script>
</head>
<div id="wrapper">
<div id="header" class="title">
	Southern's fitness plan simulator
</div>
	<div id="p1">
	<p>At Southern you are given a great environment to stay fit. From Hulsey Wellness Center to your dorm’s basement gym you have many opportunities to stay fit. Anybody who plays intramurals knows it’s nice to win. 
	So, how <em>do</em> we win? By becoming more athletic in our sport.</p>
	<p>Benefits of staying fit include optimal performance in daily activities (such as walking, running, lifting…), improved posture, an increase in resting metabolism, improved self-image and psychological well-being, and so on. 
		In this simulation you are about to embark on, you will choose an activity or sport and will be assigned a goal related to your selection. You will then create a fitness plan that will aide you in meeting your goal in three 
		months.</p>

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
			- 2nd Month will focus on explosion<br />
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
			- 2nd Month will focus on explosion<br />
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
			- 2nd Month will focus on explosion<br />
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
			- 2nd Month will focus on explosion<br />
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
					<strong>Bench Press&nbsp;</strong><span class="toolTip benchTip" title="A weightlifting exercise in which a lifter lies on a bench with feet on the floor and raises a weight with both arms.">?</span>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="benchPress">
				</td>
				<td>
					5
				</td>
			</tr>
			<tr>
				<td>
					<strong>Military Press&nbsp;</strong><span class="toolTip militaryTip" title="A weightlifting exercise in which the barbell is lifted to shoulder height and then lifted overhead">?</span>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="militaryPress">
				</td>
				<td>
					5
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Squats&nbsp;</strong><span class="toolTip squatTip" title="A weightlifting exercise in which a lifter holds a bar braced across the trapezius or rear deltoid muscle in the upper back. 
						The exercise starts by moving the hips back and bending the knees and hips to lower the torso and accompanying weight, then returning to the upright position.">?</span>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="squats">
				</td>
				<td>
					5
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Deadlifts&nbsp;</strong><span class="toolTip deadliftTip" title="A weightlifting exercise in which a barbell is lifted off the ground from a stabilized, bent over position">?</span>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="deadlifts">
				</td>
				<td>
					5
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Romanian Deadlifts&nbsp;</strong><span class="toolTip romDeadliftTip" title="A weightlifting exercise in which a lifter lies on a bench with feet on the floor and raises a weight with both arms.">?</span>:
				</td>
				<td>
					<input type="text" class="personalAssessmentInput" id="romanianDeadlifts">
				</td>
				<td>
					5
				</td>
			</tr>
		</table>

			<span style="font-size: 8px;"><center>Hover over question marks for tips, or click to view more information!</center></span>

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
					<strong>Bench Press&nbsp;</strong><span class="toolTip benchTip" title="A weightlifting exercise in which a lifter lies on a bench with feet on the floor and raises a weight with both arms.">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateBenchPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateBenchPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateBenchPressSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Military Press&nbsp;</strong><span class="toolTip militaryTip" title="A weightlifting exercise in which the barbell is lifted to shoulder height and then lifted overhead">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateMilitaryPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateMilitaryPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateMilitaryPressSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Squats&nbsp;</strong><span class="toolTip squatTip" title="A weightlifting exercise in which a lifter holds a bar braced across the trapezius or rear deltoid muscle in the upper back. 
						The exercise starts by moving the hips back and bending the knees and hips to lower the torso and accompanying weight, then returning to the upright position.">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateSquatsWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateSquatsReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateSquatsSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Deadlifts&nbsp;</strong><span class="toolTip deadliftTip" title="A weightlifting exercise in which a barbell is lifted off the ground from a stabilized, bent over position">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateDeadliftsWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateDeadliftsReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateDeadliftsSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Romanian Deadlifts&nbsp;</strong><span class="toolTip romDeadliftTip" title="A weightlifting exercise in which a lifter lies on a bench with feet on the floor and raises a weight with both arms.">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateRomanianDeadliftsWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateRomanianDeadliftsReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="dateRomanianDeadliftsSets">
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
					<strong>Bench Press&nbsp;</strong><span class="toolTip benchTip" title="A weightlifting exercise in which a lifter lies on a bench with feet on the floor and raises a weight with both arms.">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateBenchPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateBenchPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateBenchPressSets">
				</td>
			</tr>
			<tr>
				<td>
					<strong>Military Press&nbsp;</strong><span class="toolTip militaryTip" title="A weightlifting exercise in which the barbell is lifted to shoulder height and then lifted overhead">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateMilitaryPressWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateMilitaryPressReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateMilitaryPressSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Squats&nbsp;</strong><span class="toolTip squatTip" title="A weightlifting exercise in which a lifter holds a bar braced across the trapezius or rear deltoid muscle in the upper back. 
						The exercise starts by moving the hips back and bending the knees and hips to lower the torso and accompanying weight, then returning to the upright position.">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateSquatsWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateSquatsReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateSquatsSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Deadlifts&nbsp;</strong><span class="toolTip deadliftTip" title="A weightlifting exercise in which a barbell is lifted off the ground from a stabilized, bent over position">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateDeadliftsWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateDeadliftsReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateDeadliftsSets">
				</td>
			</tr>		
			<tr>
				<td>
					<strong>Romanian Deadlifts&nbsp;</strong><span class="toolTip romDeadliftTip" title="A weightlifting exercise in which a lifter lies on a bench with feet on the floor and raises a weight with both arms.">?</span>:
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateRomanianDeadliftsWeight">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateRomanianDeadliftsReps">
				</td>
				<td>
					<input type="text" class="dateExInput" id="EdateRomanianDeadliftsSets">
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