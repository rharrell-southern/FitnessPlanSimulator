<html>
<head>
	<link rel="stylesheet" type="text/css" href="http://marr.southern.edu/forms/assets/snippets/fitnessPlanSimulator/main.css" media="screen" />
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.core.js"></script>
	<script src="http://jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.fade.js"></script>
  	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

	<title>Southern Adventist University: Fitness Plan Simulator</title>
	<script type="text/javascript">

		//global user info vars
		var username = "";
		var isLoaded = false;
		var charID = -1;
		var activity = "";


		$(function() {
			//Get username from url var
			window.onload=function(){
				username = QueryString.name;
				if(username) {
					username = username.replace('%20', ' ');
				}
				console.log("Username: " + username);
			};

			$( document ).tooltip();

			$('#benchTip').on('click',function(){ 
				window.open('http://www.bodybuilding.com/exercises/detail/view/name/bench-press-powerlifting');
			});

			$('#militaryTip').on('click',function(){ 
				window.open('http://www.bodybuilding.com/exercises/detail/view/name/seated-barbell-military-press');
			});

			$('#squatTip').on('click',function(){ 
				window.open('http://www.bodybuilding.com/exercises/detail/view/name/barbell-full-squat');
			});

			$('#deadliftTip').on('click',function(){ 
				window.open('http://www.bodybuilding.com/exercises/detail/view/name/barbell-deadlift');
			});

			$('#romDeadliftTip').on('click',function(){ 
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
					if($("#benchPress").val() !="" && $("#militaryPress").val() !="" && $("#squats").val() !="" && $("#deadlifts").val() !="" && $("#romanianDeadlifts").val() !=""){
						submitAssessment($("#benchPress").val(), $("#militaryPress").val(), $("#squats").val(), $("#deadlifts").val(), $("#romanianDeadlifts").val());
						go($(this));
					}else{
						alert("Please enter a value for each exercise!")
					}
				}else{
					go($(this));
					}
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
		        	console.log("Selected activty:" + activity);
		        	console.log("Selected gender:" + gender);

		        	//ensure user has selected BOTH an activity and gender
		        	if(activity != "default" && gender != null){
		          		$( this ).dialog( "close" );

		          		//submit data to database
		          		submitChar(activity, gender);

		          		$("#"+activity).show();

		          		//print selected data
		        		console.log("Selected Activity: " + activity);


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

		        	//track selected ID
		        	charID = $("input[name=character]:checked", "#loadDialog").val();

		        	//get the activity related to selected charID (which will be the next span of class "act")
		        	activity = $("input[name=character]:checked", "#loadDialog").nextAll("span.act:first").text();
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
				     	console.log("Data: " + ndata);

				     	/*$.each(ndata.Entries, function (){
							console.log(this.id + " " + this.date);
						});*/

				     	//if ndata.Assessment is NOT empty, user has already done assessment, and needs to skip it.
				     	if(!jQuery.isEmptyObject(ndata.Assessment)) {
				     		console.log("fired");
							//populate self assessment data
							var substr = ndata.Assessment.AssessmentData.split(',');
							$("#benchPress").val(substr[0]);
							$("#militaryPress").val(substr[1]); 
							$("#squats").val(substr[2]);
							$("#deadlifts").val(substr[3]);
							$("#romanianDeadlifts").val(substr[4]);

							//populate monthly data
							//TODO: add logic

							//navigate to correct month
							//TODO: add logic to locate which month to nav to
							//
							$('#p1').hide('fade', 700, function(){
								$('#p4').show('fade', 700, function(){
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
			     	console.log("charID: " + ndata);

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
		     			"<input type='radio' name='character' value='" + this.id + "'> <b>Username:</b> " + this.username + "<br />&nbsp;&nbsp;&nbsp;&nbsp; <b>Gender:</b> "
		     			+ this.gender + "<br />&nbsp;&nbsp;&nbsp;&nbsp; <b>Activity:</b> <span class='act'>" + this.activity+ "</span><br /> <br />" 
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
		     	console.log(ldata);

		     
		    },
		    error: function(xhr, textStatus, error){
		     	alert("Assessment submission failed. . . ")
		     	console.log("xhr.statusText: " + xhr.statusText);
		     	console.log("textStatus: " + textStatus);
		     	console.log("error: " + error);
		     	isLoaded = false;
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
		//console.log('Current: ' + current + ' - Nav To: ' + navTo);
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
				default:
					$('#header').html("Southern's fitness plan simulator");
					break;
			}
				});
		});
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
					<strong>Bench Press&nbsp;</strong><span class="toolTip" id="benchTip" title="A weightlifting exercise in which a lifter lies on a bench with feet on the floor and raises a weight with both arms.">?</span>:
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
					<strong>Military Press&nbsp;</strong><span class="toolTip" id="militaryTip" title="A weightlifting exercise in which the barbell is lifted to shoulder height and then lifted overhead">?</span>:
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
					<strong>Squats&nbsp;</strong><span class="toolTip" id="squatTip" title="A weightlifting exercise in which a lifter holds a bar braced across the trapezius or rear deltoid muscle in the upper back. 
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
					<strong>Deadlifts&nbsp;</strong><span class="toolTip" id="deadliftTip" title="A weightlifting exercise in which a barbell is lifted off the ground from a stabilized, bent over position">?</span>:
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
					<strong>Romanian Deadlifts&nbsp;</strong><span class="toolTip" id="romDeadliftTip" title="A weightlifting exercise in which a lifter lies on a bench with feet on the floor and raises a weight with both arms.">?</span>:
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
		
		<strong><u>Step two</u></strong>: XXXXXXXXXXXX <br /><br />

		

	<div id="buttons" class="sel"><a href="#" class="pButton green prev">< Previous</a> <a href="#" class="pButton green next">Next ></a></div>
	</div>

<!--END WRAPPER-->
</div>
</html>

<?php 


?>