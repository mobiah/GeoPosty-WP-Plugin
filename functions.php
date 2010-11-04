<?php
// this is where the magic happens.
function geoUserXMLData() {

	$getHundred = get_option('geoHundred');

	$usersXML = new SimpleXMLElement("<geoUsers></geoUsers>");
//	$newsXML->addAttribute('newsPagePrefix', 'value goes here');

	foreach($getHundred as $user) {
		$usersIntro = $usersXML->addChild('user');
		$usersIntro->addAttribute('lat', $user['Latitude']);
		$usersIntro->addAttribute('lon', $user['Longitude']);
		$usersIntro->addAttribute('ip', $user['IPAddress']);
		$usersIntro->addAttribute('city', $user['City']);
		$usersIntro->addAttribute('state', $user['State']);
		$usersIntro->addAttribute('isp', $user['Carrier']);
		$usersIntro->addAttribute('continent', $user['Continent']);
		$usersIntro->addAttribute('country', $user['Country']);
		$usersIntro->addAttribute('region', $user['Region']);
		$usersIntro->addAttribute('postalcode', $user['PostalCode']);
		$usersIntro->addAttribute('areacode', $user['AreaCode']);
	}

	header('Content-type: text/xml');
	echo $usersXML->asXML();
}

add_action('do_feed_geouserxml', 'geoUserXMLData', 10, 1); // Make sure to have 'do_feed_customfeed'

function geoStaticMap($zoom, $width, $height, $maptype, $marker = false) {
	$geoPosty = getGeoPosty();
	if (!is_array($geoPosty)) return false;

	if ($marker == 'on') $mapMarker = '&amp;markers='. $geoPosty['Latitude'] .','. $geoPosty['Longitude'];

	return '<img src="http://maps.google.com/maps/api/staticmap?center='. $geoPosty['Latitude'] .','. $geoPosty['Longitude'] .'&amp;zoom='. $zoom .'&amp;size='. $width .'x'. $height .'&amp;maptype='. $maptype .'&amp;sensor=false'.$mapMarker.'" class="geoMap" width="'.$width.'" height="'.$height.'" alt="GeoPosty &amp Google Generated Map" />';
}

function geoGoogleMap($width, $height, $search, $results) {
	$geoPosty = getGeoPosty();
	if (!is_array($geoPosty)) return false;

	$output .= '<div id="geoPostyGoogleMap" style="width:'.$width.'px;height:'.$height.'px;"></div>';
	$output .= '<script type="text/javascript"> loadGoogleMap('.$geoPosty['Latitude'].', '. $geoPosty['Longitude'] .', \''.$search.'\', \''.$results.'\');  </script>'; //boo-urns

	return $output;
}

function geoUserMap($width, $height) {
	$geoPosty = getGeoPosty();
	if (!is_array($geoPosty)) return false;

	$output .= '<div id="geoPostyUserMap" style="width:'.$width.'px;height:'.$height.'px;"></div>';
	$output .= '<script type="text/javascript"> loadUserGoogleMap();  </script>'; //boo-urns

	return $output;
}

function geoZoomUserMap($width, $height) {
	$geoPosty = getGeoPosty();
	if (!is_array($geoPosty)) return false;

	$output .= '<div id="geoPostyZoomMap" style="width:'.$width.'px;height:'.$height.'px;"></div>';
	$output .= '<script type="text/javascript"> loadZoomUserGoogleMap();  </script>'; //boo-urns

	return $output;
}

function geoWeather($image, $measurement, $humidity, $wind) {
	$geoPosty = getGeoPosty();
	if (!is_array($geoPosty)) return false;

	$weather = getGeoPostyWeather();

	$output = '<span class="geoweather">';

	if ($image == 'on') $output .= '<span class="geoposty-weather-image"><img src="http://www.google.com' . $weather->{weather}->{current_conditions}->{icon}->attributes()->data . '" alt="'.$weather->{weather}->{current_conditions}->{condition}->attributes()->data.'" /></span><br />';
	$output .= '<strong class="geoposty-weather-condition">' . $weather->{weather}->{current_conditions}->{condition}->attributes()->data . ', <span class="';

	if ($measurement == 'Celcius') $output .= 'geopost-weather-celcius">' . $weather->{weather}->{current_conditions}->{temp_c}->attributes()->data;
	else $output .= 'geopost-weather-farenheit">' . $weather->{weather}->{current_conditions}->{temp_f}->attributes()->data ;

	$output .= '&deg;</span></strong><br />';

	if ($humidity == 'on') $output .= '<span class="geoposty-weather-humidity">' . $weather->{weather}->{current_conditions}->{humidity}->attributes()->data.'</span><br />';
	if ($wind == 'on') $output .= '<span class="geoposty-weather-wind">' . $weather->{weather}->{current_conditions}->{wind_condition}->attributes()->data . '</span>';

	$output .= '</span>';

	return $output;
}

function recordGeoStats($name) {
	$geoStats = get_option('geoStats');
	$geoStats[$name]++;
	update_option('geoStats', $geoStats);
}

// borrowed from http://roshanbh.com.np/2007/12/getting-real-ip-address-in-php.html
function getGeoIpAddress() {
	//if(GDEBUG){ error_log("geoposty:functions:geoGeoIp: client_ip=".$_SERVER['HTTP_CLIENT_IP']." : x_forw=".$_SERVER['HTTP_X_FORWARDED_FOR']." : remote=".$_SERVER['REMOTE_ADDR']);}
	//if(GDEBUG){ error_log("geoposty:functions:geoGeoIp: server=" . var_export($_SERVER,true));}
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip=$_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	else $ip=$_SERVER['REMOTE_ADDR'];
	return $ip;
}

// borrowed from http://snipplr.com/view/2531/calculate-the-distance-between-two-coordinates-latitude-longitude/
function getGeoDistance($lat1, $lng1, $lat2, $lng2, $miles = true)
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

function geoDistanceFrom($address) {
	$geoPosty = getGeoPosty();
	if (!is_array($geoPosty)) return false;

	$latlng = geoGetAddressLocation($address);

	return getGeoDistance($geoPosty['Latitude'], $geoPosty['Longitude'], $latlng['latitude'], $latlng['longitude']);
}

function geoLocationContent($locationtype, $location, $reverse = false) {
	$geoPosty = getGeoPosty();
	if (!is_array($geoPosty)) return false;
	
	$locationArray = explode(',', strtolower($location));

	switch ($locationtype) {
		case 'City':
			$locationString = $geoPosty['City'];
			break;
		case 'State/Province/Territory':
			$locationString = $geoPosty['State'];
			break;
		case 'Continent':
			$locationString = $geoPosty['Continent'];
			break;
		case 'Country':
			$locationString = $geoPosty['Country'];
			break;
		case 'Region':
			$locationString = $geoPosty['Region'];
			break;
		case 'Postal Code':
			$locationString = $geoPosty['PostalCode'];
			break;
		case 'US Area Code':
			$locationString = $geoPosty['AreaCode'];
			break;
	}

	// remove whitespace
	array_walk($locationArray, 'trim_value');

	if ($reverse && in_array(strtolower($locationString), $locationArray)) return false;
	elseif (!$reverse && !in_array(strtolower($locationString), $locationArray)) return false;

	return true;
}

function trim_value(&$value) { 
    $value = trim($value); 
}

// http://www.hashbangcode.com/blog/search-engine-spider-detection-php-258.html
function geoSpiderDetect() {
	$agentArray = array("ArchitextSpider", "Googlebot", "TeomaAgent",
		"Zyborg", "Gulliver", "Architext spider", "FAST-WebCrawler",
		"Slurp", "Ask Jeeves", "ia_archiver", "Scooter", "Mercator",
		"crawler@fast", "Crawler", "InfoSeek Sidewinder",
		"almaden.ibm.com", "appie 1.1", "augurfind", "baiduspider",
		"bannana_bot", "bdcindexer", "docomo", "frooglebot", "geobot",
		"henrythemiragorobot", "sidewinder", "lachesis", "moget/1.0",
		"nationaldirectory-webspider", "naverrobot", "ncsa beta",
		"netresearchserver", "ng/1.0", "osis-project", "polybot",
		"pompos", "seventwentyfour", "steeler/1.3", "szukacz",
		"teoma", "turnitinbot", "vagabondo", "zao/0", "zyborg/1.0",
		"Lycos_Spider_(T-Rex)", "Lycos_Spider_Beta2(T-Rex)",
		"Fluffy the Spider", "Ultraseek", "MantraAgent","Moget",
		"T-H-U-N-D-E-R-S-T-O-N-E", "MuscatFerret", "VoilaBot",
		"Sleek Spider", "KIT_Fireball", "WISEnut", "WebCrawler",
		"asterias2.0", "suchtop-bot", "YahooSeeker", "ai_archiver",
		"Jetbot"
	);
 
	$theAgent = $_SERVER["HTTP_USER_AGENT"];
	$agentCount = count($agentArray); 

	for ( $i=0; $i<$agentCount; $i++ ) {
		if ( strpos(" ".strtolower($theAgent), strtolower($agentArray[$i]))!= false ) return false;
	}
	
	return true;
}

function geoRedirects() {
	// booyashacka!
	$geoRedirects = get_option('geoposty_redirects');
	if (empty($geoRedirects)) return;

	$cleanURL = $_SERVER['REQUEST_URI'];

	// check the URL for ? parameters and remove if necessary
	$getPosition = strpos($cleanURL,'?');
	if ($getPosition > 0) $cleanURL = substr($_SERVER['REQUEST_URI'],0,$getPosition);

	foreach ($geoRedirects as $redirect) {
		if ($redirect['source'] == $cleanURL) {

			if ($redirect['radius'] > 1) {
				if (geoDistanceFrom($redirect['radiuslocation']) > $redirect['radius']) continue;
			} else {
				if (!geoLocationContent($redirect['location'], $redirect['locationaddress'])) continue;
			}

			// get just the parameters
			if ($getPosition > 0) $urlParameters = substr($_SERVER['REQUEST_URI'],$getPosition-strlen($_SERVER['REQUEST_URI']));

			wp_redirect($redirect['destination'] . $urlParameters, '302');
			die();
		} 
	}
}

add_action('init', 'geoRedirects');

// this is necessary to prevent Google from giving us 620 errors with their API.
// this is no longer necessary since we're not using google anymore
/*
add_action('save_post', 'geoCacheGoogle');
function geoCacheGoogle($postID) {
	if (!wp_is_post_revision($postID)) {
		$cachePost = get_post($postID);
		do_shortcode($cachePost->post_content);
	}
}*/

function geoposty_plugin_row() {
?>
	<tr>
		<td colspan="5">
			<?php echo plugin_basename(__FILE__); ?>
			<p>Hello world!</p>
		</td>
	</tr>
<?php
}
?>
