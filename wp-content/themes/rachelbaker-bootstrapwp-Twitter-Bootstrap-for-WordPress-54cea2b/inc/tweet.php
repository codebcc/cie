<?php

include('../twitter-oauth/twitteroauth/twitteroauth.php');
include('../twitter-oauth/config.php'); 
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTHTOKEN, OAUTHSECRET);

$content = $connection->get('account/verify_credentials');

date_default_timezone_set('Europe/Berlin');

if(isset($_GET["send"])) $send = $_GET["send"];

function tweet($text)
{

  global $connection;

	return $connection->post('statuses/update', array('status' => $text));
}

function getTodaysTweet() {
	
  $con = mysql_connect("localhost","root","helepad");
  if (!$con)
    {
    die('Could not connect: ' . mysql_error());
    }

      // Select database
      mysql_select_db ("cie", $con)
            or die ("Unable to select database");

      $res = mysql_query("SELECT * FROM  wp_ai1ec_events WHERE DATE(start) = DATE(NOW())", $con)
            or die ("Unable to run query");

      $nrows = mysql_num_rows($res);

      if($nrows==0) return false;

      

      while ($row = mysql_fetch_array($res))
      {
          //print_r($row);

        $tweet = "TONIGHT: ";

  	    $res2 = mysql_query("SELECT * FROM  wp_posts WHERE ID = '".$row["post_id"]."'", $con)
  	          or die ("Unable to run query");

  	    $row2 = mysql_fetch_array($res2);

  	    $title = $row2["post_title"];

  	    $link = goo_gl_short_url($row2["guid"]);

  	    $start = new DateTime($row["start"]);

        $start->modify("+2 hours");

  	    $start = $start->format('H:i');

  	    $tweet .= $start." ".$title." ".$link." >> ";

        $main_link = goo_gl_short_url("http://comedyinenglish.de/event-calendar");

        $tweet .= "all events: ".$main_link;

        echo $tweet."<br><br>";

        if(!isset($send)) $t = tweet(utf8_encode ($tweet));

        print_r($t); 

      }

    mysql_close($con);

  // some code

    
    //$myrows = $wpdb->get_results( "SELECT * FROM  wp_ai1ec_events WHERE DATE(start) >= DATE(".$from.") ORDER BY DATE(start)");
}

function goo_gl_short_url($longUrl) {
    $GoogleApiKey = 'AIzaSyDnrUfm_7nlsGwW8c3s8bMrHsRXqSlBXm0';
    $postData = array('longUrl' => $longUrl, 'key' => $GoogleApiKey);
    $jsonData = json_encode($postData);
    $curlObj = curl_init();
    curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
    curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
    //As the API is on https, set the value for CURLOPT_SSL_VERIFYPEER to false. This will stop cURL from verifying the SSL certificate.
    curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlObj, CURLOPT_HEADER, 0);
    curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    curl_setopt($curlObj, CURLOPT_POST, 1);
    curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
    $response = curl_exec($curlObj);
    $json = json_decode($response);
    curl_close($curlObj);
    return $json->id;
}

getTodaysTweet();

?>