<?php

    $i=0;
    $m=1;
    $w=1;
    $d=1;
    $output;
    //Continue printing untill all records displayed
    while( $m<=12 ) {
    
    echo("M:" . $m . " W:" . $w . " D:" . $d . "<br />");
    
    // if true, we have completed 7 days, reset day counter and begin next week
    if((($d) % 7) == 0)
    {
    	$w++;
    	$d=0;
    }

    // if true, we have completed 4 weeks, reset week counter and begin next month
    if(($w % 5) == 0){
        $w = 1;
        $m++;
    }
    
    $i++;
    $d++;
    }
?>