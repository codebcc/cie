<?php

error_reporting(E_ERROR);
date_default_timezone_set('Europe/Berlin');

//AIzaSyDnrUfm_7nlsGwW8c3s8bMrHsRXqSlBXm0


require_once("fb/src/facebook.php");
require_once("functions.php");

$facebook = doFacebook();
$wallId = '184196778359304';
$wall = getWall($wallId);
$json = json_encode($wall);
//echo '<pre>'.$json.'</pre>';



$areas = array(
	//lat,long,categoryID
    array(52.482362,13.436086,4, "neukolln"),	//Neukolln (Rathaus Neukolln)
    array(52.521786,13.409808,5,"mitte"),	//Mitte (TV Tower)
    array(52.487955,13.39448,6,"kreuzberg"),		//Kreuzberg (Geisenaurstr)	
    array(0,0,7,"unspecified")
);

$area = ($_GET["area"]) ? $_GET["area"] : 0;

$m = array();
$e = "";

foreach($areas as $a) {

	$vcal = array("BEGIN:VCALENDAR","VERSION:1.0");

	$area = $a[2];

	foreach ($wall as $i) {

		foreach($i as $j) {


			if(isset($j["link"])) {

				//pick out only posts that have links

				$l = strtolower($j["link"]);
				$pos = strrpos($l, "facebook.com/events/");
				$id = substr($l, 31, (strlen($l)-32));
				//substr(string, start)

				//only use facebook events links && ones that haven't already been used

				if( ($pos>-1) && (!in_array($id, $m))) {

					$e = get_fb_event($id);
					if(!$e["timezone"]) $e["timezone"] = "Europe/Berlin";
					print_r($e);
					echo '<br><br>'; 
					$img = get_fb_image($id, "large");
					//echo $img."sss";
					//echo $e["name"]."\n".$e["start_time"]."\n".$e["timezone"]."\n\n";
					$v = get_fb_venue($e);

					//print_r($e);

					if(getArea()) {

						array_push($vcal, "BEGIN:VEVENT");
						vcalAddDate("start_time","DTSTART;TZID=Europe/Berlin:");
						if(isset($e["end_time"])) vcalAddDate("end_time","DTEND;TZID=Europe/Berlin:");
						
						$name = "";
						if(isset($e["name"])) {
							$name = $e["name"];
							//if(isset($e["location"])) $name .= ", ".$e["location"];
						} elseif(isset($e["location"])) {
							$name = $e["location"];
						}
						if(isset($e["venue"])) if(isset($e["venue"]["city"])) $name .= ", ".$e["venue"]["city"];

						array_push($vcal,"SUMMARY:".$name);

						if(isset($v)) {
							if(isset($v["location"])) {
								$loc = $v["location"]["latitude"].",+".$v["location"]["longitude"];
								$location = 'LOCATION: <a href="'.$v["link"].'">'.$v["name"].'</a> <a href="http://maps.google.de/maps?q='.$loc.'('.ifAdd("location",$e).')">MAP</a>'; 

								array_push($vcal,$location);
								//echo "neukolln:".distance($neukolln[0], $neukolln[1], $v["latitude"], $v["longitude"], false)."km<br>";
								//echo "mitte:".distance($mitte[0], $mitte[1], $v["latitude"], $v["longitude"], false)."km<br>";
							} else {
								$loc = ifAdd("location",$e).",+".ifAdd("street",$v).",+".ifAdd("city",$v).",+".ifAdd("state",$v).",+".ifAdd("country",$v);
								array_push($vcal,'LOCATION:<a href="http://maps.google.de/maps?q='.$loc.'">MAP</a>');
							}
						}

						if(isset($e["description"])) {
							$desc = "<a href='https://www.facebook.com/events/".$id."' class='uibutton confirm'>Event on Facebook</a><br><br>";
							if(strlen($img)>0) $desc .= '<img src="'.$img.'" alt="" class="img" />';
							$desc .= auto_link_text($e["description"]);
							
							array_push($vcal,"DESCRIPTION:".$desc);
						}

						array_push($vcal,"PRIORITY:3","END:VEVENT");
						array_push($m,$id);

						//echo file_exists("ics/".)

					}
					

				}
			}


		}
	}

	array_push($vcal,"END:VCALENDAR");

	$file = 'cal-'.$a[3].".ics";

	$f = fopen($file, 'w');

	foreach($vcal as $i ) {
		fwrite($f, $i."\r\n");
	}

	fclose($f);
}



?>
