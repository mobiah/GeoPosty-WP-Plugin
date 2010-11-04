<?php

function getGeoPosty() {
	global $geoMD5, $geoposty_api_key;

	if (!geoSpiderDetect()) return false;

	$geoPosty = get_transient('geo-' . $geoMD5);

	// only query quova if needed
	if (!is_array($geoPosty)) {
		// should replace the _SERVER variables (or at least do a bunch of checking on them).  also add some better error checking

		$ip = getGeoIpAddress();
		$host = $_SERVER['HTTP_HOST'];
		$server = GEOSERVER .'domain='. $host .'&ip='. $ip .'&domainkey=' . $geoposty_api_key;
		if(GDEBUG) { error_log("geoposty:curl:getGeoPosty ip=$ip server=$server"); }
		$data = wp_remote_retrieve_body(wp_remote_get($server));
		$geoPostyXML = @simplexml_load_string($data);

		if (!$geoPostyXML) {
			// check what was returned in $data
			// log that data into a geoposty option
			// report that in admin?
			$geoLogging = get_option('geoLogging');
			if (!is_array($geoLogging)) $geoLogging = array();

			$geoLogCount = count($geoLogging)+1;
			
			$geoLogging[$geoLogCount]['time'] = time();
			$geoLogging[$geoLogCount]['message'] = trim($data);

			update_option('geoLogging', $geoLogging);

			return false;
		}

		// convert the simpleXML object to an array
		// can't serialize PHP built-in objects :(
		// fix case
		$geoPosty = array(
			'IPAddress' => (string)$geoPostyXML->{IPAddress},
			'Carrier' => ucwords($geoPostyXML->{Network}->{Carrier}),
			'Continent' => ucwords($geoPostyXML->{Location}->{Continent}->{Name}),
			'Country' => strtoupper($geoPostyXML->{Location}->{Country}->{Name}),
			'Region' => ucwords($geoPostyXML->{Location}->{Region}->{Name}),
			'State' => strtoupper($geoPostyXML->{Location}->{State}->{Name}),
			'City' => ucwords($geoPostyXML->{Location}->{City}->{Name}),
			'PostalCode' => (string)$geoPostyXML->{Location}->{City}->{PostalCode},
			'AreaCode' => (string)$geoPostyXML->{Location}->{City}->{AreaCode},
			'Latitude' => (string)$geoPostyXML->{Location}->{City}->{Coordinates}->{Latitude},
			'Longitude' => (string)$geoPostyXML->{Location}->{City}->{Coordinates}->{Longitude}
		);

		// cache quova info for 24 hours per IP
		set_transient('geo-' . $geoMD5, $geoPosty, 60*60*24);

		// save out last 100 visitors for fun & profit
		$geoLastHundred = get_option('geoHundred');
		
		if (!is_array($geoLastHundred)) $geoLastHundred = array();

		@array_unshift($geoLastHundred, $geoPosty);
		unset($geoLastHundred[100]);
		update_option('geoHundred', $geoLastHundred);
	} 

	return $geoPosty;
}

// update this to cache the object once we figure out the above bug.
function getGeoPostyWeather() {
	global $geoMD5;

	$geoPosty = getGeoPosty();

	$data = get_transient('geoWeather-' . $geoMD5);

	if ($data === false) {
		$data = wp_remote_retrieve_body(wp_remote_get('http://www.google.com/ig/api?hl=en&weather=' . $geoPosty['PostalCode']));

		$geoPostyWeatherXML = @simplexml_load_string($data);

		if (!$geoPostyWeatherXML) return false;

		// cache weather info for 2 hours per IP
		set_transient('geoWeather-' . $geoMD5, $data, 60*60*2);
	} 

	if (empty($geoPostyWeatherXML)) $geoPostyWeatherXML = @simplexml_load_string($data);

	return $geoPostyWeatherXML;
}

function geoGetAddressLocation($address) {
	global $geoposty_api_key;

	$addressMD5 = md5($address);
	$getAddressLocation = get_option('geoAddressLocation');

	if ($getAddressLocation[$addressMD5]) return $getAddressLocation[$addressMD5];
	else {
		$server = SERVER . 'geosearch.php?domainkey='.$geoposty_api_key.'&q='.urlencode($address);
		if(GDEBUG) { error_log("geoposty:curl:geoGetAddressLocation: server=$server "); }
		$data = wp_remote_retrieve_body(wp_remote_get($server));

		if (trim($data) == 'Error: invalid query') return false;

		$addressLatLon = json_decode($data);

		$getAddressLocation[$addressMD5]['longitude'] = $addressLatLon['0']['4'];
		$getAddressLocation[$addressMD5]['latitude'] = $addressLatLon['0']['3'];

		update_option('geoAddressLocation', $getAddressLocation);

		return $getAddressLocation[$addressMD5];
	}
}

function geoAdminStats($type) {
	global $geoposty_api_key;

	$data = wp_remote_retrieve_body(wp_remote_get(GEOSERVER .'domain='. $_SERVER['HTTP_HOST'] .'&domainkey=' . $geoposty_api_key . '&stats=' . $type));

	return json_decode($data);
}

/*
add_action('wp_footer', 'doFoot');

function doFoot() {
	echo '<pre>';
	$getAddressLocation = get_option('geoAddressLocation');
	echo '<h2>'. count($getAddressLocation) .'</h2>';
	print_r($getAddressLocation);
	echo '</pre>';
}

*/
?>
