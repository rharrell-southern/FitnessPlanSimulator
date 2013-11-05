<?php
/*
*************************************************************************
	MODx Content Management System and PHP Application Framework 
	Managed and maintained by Raymond Irving, Ryan Thrash and the
	MODx community
*************************************************************************
	MODx is an opensource PHP/MySQL content management system and content
	management framework that is flexible, adaptable, supports XHTML/CSS
	layouts, and works with most web browsers, including Safari.

	MODx is distributed under the GNU General Public License	
*************************************************************************

	MODx CMS and Application Framework ("MODx")
	Copyright 2005 and forever thereafter by Raymond Irving & Ryan Thrash.
	All rights reserved.

	This file and all related or dependant files distributed with this filie
	are considered as a whole to make up MODx.

	MODx is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	MODx is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with MODx (located in "/assets/docs/"); if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

	For more information on MODx please visit http://modxcms.com/
	
**************************************************************************
    Originally based on Etomite by Alex Butter
**************************************************************************
*/	

/**
 * Initialize Document Parsing
 * -----------------------------
 */

error_reporting(E_ALL ^ E_NOTICE);
ini_set('error_log', 'E:/wwwroot/temp/errors.log');
ini_set('display_errors', '1');
define("MGR_DIR", "manager");

// get start time
$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $tstart = $mtime;
$mstart = memory_get_usage();

// harden it
require_once(dirname(__FILE__).'/../../../manager/includes/protect.inc.php');

// set some settings, and address some IE issues
@ini_set('url_rewriter.tags', '');
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_only_cookies',1);
session_cache_limiter('');
header('P3P: CP="NOI NID ADMa OUR IND UNI COM NAV"'); // header for weird cookie stuff. Blame IE.
header('Cache-Control: private, must-revalidate');
ob_start();
error_reporting(E_ALL & ~E_NOTICE);

/**
 *	Filename: index.php
 *	Function: This file loads and executes the parser. *
 */

define("IN_ETOMITE_PARSER", "true"); // provides compatibility with etomite 0.6 and maybe later versions
define("IN_PARSER_MODE", "true");
define("IN_MANAGER_MODE", "false");

if (!defined('MODX_API_MODE')) {
    define('MODX_API_MODE', false);
}

// initialize the variables prior to grabbing the config file
$database_type = '';
$database_server = '';
$database_user = '';
$database_password = '';
$dbase = '';
$table_prefix = '';
$base_url = '';
$base_path = '';

// get the required includes
if($database_user=="") {
	$rt = @include_once(dirname(__FILE__).'/../../../manager/includes/config.inc.php');
	// Be sure config.inc.php is there and that it contains some important values
	if(!$rt || !$database_type || !$database_server || !$database_user || !$dbase) {
	echo "
<style type=\"text/css\">
*{margin:0;padding:0}
body{margin:50px;background:#eee;}
.install{padding:10px;border:5px solid #f22;background:#f99;margin:0 auto;font:120%/1em serif;text-align:center;}
p{ margin:20px 0; }
a{font-size:200%;color:#f22;text-decoration:underline;margin-top: 30px;padding: 5px;}
</style>
<div class=\"install\">
<p>MODX is not currently installed or the configuration file cannot be found.</p>
<p>Do you want to <a href=\"install/index.php\">install now</a>?</p>
</div>";
		exit;
	}
}

// start session 
startCMSSession();

// initiate a new document parser
include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
$modx = new DocumentParser;
$etomite = &$modx; // for backward compatibility



    /***************************************************
   *												  *
  *				END MODX, START PHP CODE             *
 *													*	
***************************************************/			

global $modx;

if($_POST["type"] == "new"){
	//define table
	$table = "fp_users";


	//get (max ID + 1), and send back to ajax call to track created character ID
	$ID = $modx->db->getValue('SELECT MAX(ID) FROM '.$table);
	echo($ID + 1 );



	$fields = array(
		//ID (auto increment)
		'username'				=> $_POST["username"],
		'activity'				=> $_POST["activity"],
		'gender'				=> $_POST["gender"],
		'personalAssessment'	=> "",
	);
	$modx->db->insert($fields, 'fp_users');

}else if($_POST["type"] == "remove"){
	//TODO: write

}else if($_POST["type"] == "load"){
	//define table
	$table = "fp_users";
	//$username = "Chris Ban";

	$res = $modx->db->query('SELECT * FROM '.$table.' WHERE username = \''. $_POST["username"].'\'');
	$totalRecords = $modx->db->getRecordCount( $res );
	   if($totalRecords >= 1)
	   {

	   		$output = '{';
	   		$i=0;
	   		//Continue printing untill all records displayed
			while( $row = $modx->db->getRow( $res ) ) {

				$output .= '"character'.$i.'":{ "id":"'.$row['ID'].'","username":"'.$row['username'].'", "gender":"'.$row['gender'].'","activity":"'.$row['activity'].'"}';
				if ($i < ($totalRecords - 1)) {
					$output .= ",";
				}
				$i++;
			}
			$output .= '}';
	   } else {
				$output = "{}";
			}

			echo($output);
}else if($_POST["type"] == "checkAssess"){
	//define table
	$table = "fp_users";
	$table2 = "fp_data";
	$output;

	$res = $modx->db->query('SELECT * FROM '.$table.' WHERE ID = \''. $_POST["charID"].'\'');
	$row = $modx->db->getRow( $res );

	if ($row['personalAssessment'] != ""){
	$output = '{"Assessment":{"AssessmentData":"'.$row["personalAssessment"].'"}, "Entries" : {';

	$res2 = $modx->db->query('SELECT * FROM '.$table2.' WHERE ID = \''. $_POST["charID"].'\'');
	$totalRecords = $modx->db->getRecordCount( $res2 );

	$output;
		if($totalRecords >= 1)
	   {
	   		
	   		$i=0;
	   		//Continue printing untill all records displayed
			while( $row2 = $modx->db->getRow( $res2 ) ) {

				$output .= '"Entry'.$i.'":{ "id":"'.$row2['PID'].'","date":"'.$row2['date'].'", "E1":"'.$row2['E1'].'","E2":"'.$row2['E2'].'","E3":"'.$row2['E3'].'","E4":"'.$row2['E4'].'","E5":"'.$row2['E5'].'"}';
				if ($i < ($totalRecords - 1)) {
					$output .= ",";
				}

				$i++;
			}
			$output .= '}}';
	   } else{
	   	$output .= '}}';
	   }
	}else{
		$output = '{}';
	}
	echo($output);
}else if($_POST["type"] == "submitAssess"){
	//define table
	$table = "fp_users";

	$fields = array(
		'personalAssessment'	=> $_POST["personalAssessment"],
	);
	$modx->db->update($fields, 'fp_users', 'id = "' . $_POST["ID"] . '"');
}
?>