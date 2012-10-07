<?php 
function doFacebook() {
	$config = array();
	$config['appId'] = '366597510039533';
	$config['secret'] = 'd510cdd653447ac40dfd2a0540e2dca4';
	$config['fileUpload'] = false; // optional

	$facebook = new Facebook($config);

	return $facebook;
}

function getWall($id) {
	global $facebook;
	$access_token = $facebook->getAccessToken();
	//return $facebook->api('/'.$id.'/feed?access_token=".$access_token','GET');
	//'select post_id from stream where source_id = 40796308305 and created_time <'.time().' LIMIT 1000000 ;'
	$fql='/fql?q=SELECT+post_id,message,attachment+FROM+stream+WHERE+source_id='.$id.'+LIMIT+100000&access_token='.$access_token;
	return $facebook->api($fql,'GET');
	//echo $fql;

}

function getPageId($id) {
	global $facebook;
	$access_token = $facebook->getAccessToken();
	$fb = $facebook->api('/'.$id.'/?access_token='.$access_token,'GET');	
	return $fb["id"];
}

function getPage($id) {
	global $facebook;
	$access_token = $facebook->getAccessToken();
	return $facebook->api('/'.$id.'/?access_token=".$access_token','GET');	
}
 
function cleanDate($d) {
	global $e;
	if(isset($e["timezone"])) {
		//echo $e["name"];
		$d = str_replace("T", " ", $d);
		//echo $d."\n";
		$myDateTime = new DateTime(date($d));
		//print_r($myDateTime);
		$d = date('Y-m-d H:i:00', $myDateTime->format('U') + 32400); //add 9 hours because of shitty facebook
		//echo "\n".$d."\n\n";
	} 
	$dd = str_replace("-", "", $d);
	$dd = str_replace(" " , "T", $dd);
	$dd = str_replace(":", "", $dd);
	return $dd;
}

function vcalAdd($key,$e,$prefix) {
	global $vcal;
	if(isset($e[$key])) array_push($vcal,$prefix.$e[$key]);
}

function vcalAddDate($key,$prefix) {
	global $vcal, $e;
	if(isset($e[$key])) {
		$d = cleanDate($e[$key]);
		array_push($vcal,$prefix.$d);
	}
}

function ifAdd($key,$e, $sep="") {
	return (isset($e[$key])) ? $e[$key].$sep : "";
}


function getArea() {

	global $area, $areas, $e, $v;

	if($area==0) return true;

	if(($area==7) && !$v) {
		return true;
	} else {

		//no venue data + categorgy specified?
		if(!$v) return false;

		$vl = $v["location"];

		$distances = array();

		foreach($areas as $a) {

			array_push($distances, array(distance($a[0], $a[1], $vl["latitude"], $vl["longitude"]), $a[2]));

		}

		$index = -1;

		$dd = 100;

		foreach($distances as $key => $d) {
			if($d[0]<$dd) {
				$dd = $d[0];
				$index = $key;
			}
		}

		return ($distances[$index][1]==$area);

	}
	return true;
}

function distance($lat1, $lng1, $lat2, $lng2, $miles = true)
{
	$pi80 = M_PI / 180;
	$lat1 *= $pi80;
	$lng1 *= $pi80;
	$lat2 *= $pi80;
	$lng2 *= $pi80;

	$r = 6372.797; // mean radius of Earth in km
	$dlat = $lat2 - $lat1;
	$dlng = $lng2 - $lng1;
	$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	$km = $r * $c;

	return ($miles ? ($km * 0.621371192) : $km);
}

function auto_link_text($text)
{
   $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
   $callback = create_function('$matches', '
       $url       = array_shift($matches);
       $url_parts = parse_url($url);

       $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
       $text = preg_replace("/^www./", "", $text);

       $last = -(strlen(strrchr($text, "/"))) + 1;
       if ($last < 0) {
           $text = substr($text, 0, $last) . "&hellip;";
       }

       return sprintf(\'<a rel="nowfollow" href="%s">%s</a>\', $url, $text);
   ');

   return preg_replace_callback($pattern, $callback, $text);
}

function extractLL($ll) {

	//echo $ll;

	$pos = (strpos($ll, "q=")+2);
	$pos2 = (strpos($ll,"(")-1-$pos);
	$ll = substr($ll,$pos,$pos2);

	$pos = (strpos($ll,","));
	$lat = substr($ll,0,$pos);
	$lon = substr($ll,($pos+1),strlen($ll));

	return array($lat,$lon);

}

function extractVenue($v) {
	$v = explode("</a>", $v);
	return $v[0]."</a>";
}

function get_fb_venue($e) {
	if($e["venue"]) if($e["venue"]["id"]) {
		global $facebook;
		$v = $facebook->api('/'.$e["venue"]["id"],'GET');	
		return $v;
	} else {
		return false;
	}
}

function get_fb_event($id) {
	global $facebook;
	$e = $facebook->api('/'.$id,'GET');
	return $e;
}

function get_fb_image($id, $size = "normal") {
	$headers = get_headers('http://graph.facebook.com/'.$id.'/picture?type=normal',1);
	$l = $headers["Location"];
	return str_replace("_s.jpg", "_n.jpg", $l);
}

function make_content($content) {
	return "<p>".str_replace("\n", "<br>", $content)."</p>";
}

function print_r_reverse($in) { 
    $lines = explode("\n", trim($in)); 
    if (trim($lines[0]) != 'Array') { 
        // bottomed out to something that isn't an array 
        return $in; 
    } else { 
        // this is an array, lets parse it 
        if (preg_match("/(\s{5,})\(/", $lines[1], $match)) { 
            // this is a tested array/recursive call to this function 
            // take a set of spaces off the beginning 
            $spaces = $match[1]; 
            $spaces_length = strlen($spaces); 
            $lines_total = count($lines); 
            for ($i = 0; $i < $lines_total; $i++) { 
                if (substr($lines[$i], 0, $spaces_length) == $spaces) { 
                    $lines[$i] = substr($lines[$i], $spaces_length); 
                } 
            } 
        } 
        array_shift($lines); // Array 
        array_shift($lines); // ( 
        array_pop($lines); // ) 
        $in = implode("\n", $lines); 
        // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one) 
        preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER); 
        $pos = array(); 
        $previous_key = ''; 
        $in_length = strlen($in); 
        // store the following in $pos: 
        // array with key = key of the parsed array's item 
        // value = array(start position in $in, $end position in $in) 
        foreach ($matches as $match) { 
            $key = $match[1][0]; 
            $start = $match[0][1] + strlen($match[0][0]); 
            $pos[$key] = array($start, $in_length); 
            if ($previous_key != '') $pos[$previous_key][1] = $match[0][1] - 1; 
            $previous_key = $key; 
        } 
        $ret = array(); 
        foreach ($pos as $key => $where) { 
            // recursively see if the parsed out value is an array too 
            $ret[$key] = print_r_reverse(substr($in, $where[0], $where[1] - $where[0])); 
        } 
        return $ret; 
    } 
} 

function safe_unserialize($serialized) {
    // unserialize will return false for object declared with small cap o
    // as well as if there is any ws between O and :
    if (is_string($serialized) && strpos($serialized, "\0") === false) {
        if (strpos($serialized, 'O:') === false) {
            // the easy case, nothing to worry about
            // let unserialize do the job
            return @unserialize($serialized);
        } else if (!preg_match('/(^|;|{|})O:[0-9]+:"/', $serialized)) {
            // in case we did have a string with O: in it,
            // but it was not a true serialized object
            return @unserialize($serialized);
        }
    }
    return false;
}

function getHref($href) {
	return (strpos($href, "http") > -1) ? $href : "http://".$href;
}

function getMapLink($loc, $name) {
	$street = $loc["street"];
	$city = $loc["city"];
	$country = $loc["country"];
	return "http://maps.google.de?q=".$name."+".$street."+".$city."+".$city."+".$country;


}

function getAddress($loc) {
	$t = "";
	$street = $loc["street"];
	$city = $loc["city"];
	if($street) $t.=$street.", ";
	if($city) $t.=$city;
	return $t;
}

function getMapHTML($loc,$name) {
	if(!$loc["street"]) return false;
	return '<p class="map">Map: <a href="'.getMapLink($loc,$name).'">'.getAddress($loc).'</a></p>';
}

function getProducersEvents($id) {

	global $wpdb;
	global $post;

	$wall = getWallVar();

	print_r($wall);

	$id = getPageId($id);

	echo $id.'<br><br>';

	foreach ($wall->data as $e) {
		echo $e->from->id."<br>";
		if($e->from->id==$id) print_r($e);
	}

}

function getAssociatedProducers($id) {

    global $wpdb;
    global $post;

    $ids = $id.",";

    $myrows = $wpdb->get_results( "SELECT * FROM  wp_posts WHERE post_type = 'performers'");
    foreach ($myrows as $row) {
    	 $orgs = get_field("organisation_links", $row->ID);
    	 if($orgs) {
	    	 foreach ($orgs as $o) {
	    	 	if( (strpos($o["association"], "Produces")>-1) &&($o["organisation"]->ID==$post->ID) ) {
	    	 		 $fbID = get_field("facebook_id", $row->ID);
	    	 		 if(!$fbID) continue;
	    	 		 if(!is_numeric($fbID)) {
	    	 		 	$fbID = getPageId($fbID);
	    	 		 	$ids .= $fbID.",";
	    	 		 } else {
	    	 		 	$ids .= $fbID.",";
	    	 		 }
	    	 	}
	    	 }
    	}
    	 
    }
    
	return $ids;
}

function getProducedGigs($id) {

    $wall = getWallVar();

    foreach ($wall->data as $w) {
    
    	echo ($w->from->id).",";
    }

}

function getWallVar() {
	$file =  $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/toolbox/inc/all-json.php";
	$var = file_get_contents($file);
	return json_decode($var);	
}

function getEventsVar() {
	$file =  $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/toolbox/inc/all-events.php";
	$var = file_get_contents($file);
	json_decode($var);	
	echo json_last_error();
}

function fbButton($id) {
	echo '<a href="http://www.facebook.com/'.$id.'" class="uibutton confirm">See Facebook page</a>';
}

function get_directory($type) {

    global $wpdb;

	$h = '<ul class="directory">';


    $myrows = $wpdb->get_results( "SELECT * FROM  wp_posts WHERE post_type = '".$type."' AND post_status = 'publish' ORDER BY post_title");
    foreach ($myrows as $row) {
    	$h .= '<li><a href="'.$row->guid.'">'.$row->post_title.'</a></li>';
    }
    $h .= '</ul>';
    echo $h;

}

?>