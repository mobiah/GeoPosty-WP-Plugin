<?php
add_shortcode('geoip', 'geoIP');
add_shortcode('geoisp', 'geoISP');
add_shortcode('geocontinent', 'geoContinent');
add_shortcode('geocountry', 'geoCountry');
add_shortcode('georegion', 'geoRegion');
add_shortcode('geostate', 'geoState');
add_shortcode('geocity', 'geoCity');
//add_shortcode('geopostalcode', 'geoPostalCode');
add_shortcode('geoareacode', 'geoAreaCode');
add_shortcode('geolatitude', 'geoLatitude');
add_shortcode('geolongitude', 'geoLongitude');
add_shortcode('geostaticmap', 'geoStaticMapShortcode');
add_shortcode('geogooglemap', 'geoGoogleMapShortcode');
add_shortcode('geousermap', 'geoUserMapShortcode');
add_shortcode('geozoomusermap', 'geoZoomUserMapShortcode');
add_shortcode('geoweather', 'geoWeatherShortcode');
add_shortcode('geodistancefrom', 'geoDistanceFromShortcode');
//add_shortcode('georredirect', 'geoRRedirect');
add_shortcode('geobusiness', 'geoGoogleBusinesses');
add_shortcode('geocontent', 'geoContentFilter' );

function geoContentFilter( $attributes, $output = null ) {
	recordGeoStats('s_content');

	extract(shortcode_atts(array(
		'miles' => '',
		'distancefrom' => '',
		'locationtype' => '',
		'location' => '',
		'reverse' => false
	), $attributes));

	if (!empty($distancefrom) && $miles > 1) {
		if (geoDistanceFrom($distancefrom) > $miles) return false;
	}
	$locationTest = geoLocationContent($locationtype, $location, $reverse);
	if (!$locationTest) return false;

	return do_shortcode($output);
}

function geoDistanceFromShortcode($attributes) {

	recordGeoStats('distance');

	extract(shortcode_atts(array(
		'address' => '1600 Pennsylvania Ave, Washington, DC 20500'
	), $attributes));

	$geoPosty = getGeoPosty();
	if (!is_array($geoPosty)) return false;

	$addressLatLon = geoGetAddressLocation($address);

	return getGeoDistance($geoPosty['Latitude'], $geoPosty['Longitude'], $addressLatLon['latitude'], $addressLatLon['longitude']);
}

function geoStaticMapShortcode($attributes) {
	recordGeoStats('staticmap');

	extract(shortcode_atts(array(
		'zoom' => '6',
		'width' => '200',
		'height' => '200',
		'maptype' => 'roadmap'
	), $attributes));

	return geoStaticMap($zoom, $width, $height, $maptype);
}

function geoGoogleBusinesses($attributes) {

	recordGeoStats('s_business');

	extract(shortcode_atts(array(
		'width' => '200',
		'height' => '200',
		'search' => '',
		'results' => '0',
		'miles' => '',
		'distancefrom' => '',
		'locationtype' => '',
		'location' => '',
		'reverse' => false
	), $attributes));

	if (!empty($distancefrom) && $miles > 1) {
		if ($reverse && (geoDistanceFrom($distancefrom) < $miles)) return false;
		elseif (!$reverse && geoDistanceFrom($distancefrom) > $miles) return false;
	}
	$locationTest = geoLocationContent($locationtype, $location, $reverse);
	if (!$locationTest) return false;

	$geoPosty = getGeoPosty();

	$output = '<div id="geoGoogleBusiness" style="width:'.$width.'px; height: '. $height .'px"></div>
	<script type="text/javascript"> googleBusinessSearch('.$geoPosty['Latitude'].', '. $geoPosty['Longitude'] .', \''.$search.'\', \''.$results.'\');  </script>'; //boo-urns

	return $output;
}

function geoGoogleMapShortcode($attributes) {

	recordGeoStats('googlemap');

	extract(shortcode_atts(array(
		'width' => '200',
		'height' => '200',
		'search' => '',
		'results' => '0',
		'miles' => '',
		'distancefrom' => '',
		'locationtype' => '',
		'location' => '',
		'reverse' => false
	), $attributes));

	if (!empty($distancefrom) && $miles > 1) {
		if ($reverse && (geoDistanceFrom($distancefrom) < $miles)) return false;
		elseif (!$reverse && geoDistanceFrom($distancefrom) > $miles) return false;
	}
	$locationTest = geoLocationContent($locationtype, $location, $reverse);
	if (!$locationTest) return false;

	return geoGoogleMap($width, $height, $search, $results);
}

function geoUserMapShortcode($attributes) {

	recordGeoStats('usermap');

	extract(shortcode_atts(array(
		'width' => '200',
		'height' => '200'
	), $attributes));

	return geoUserMap($width, $height);
}

function geoZoomUserMapShortcode($attributes) {

	recordGeoStats('zoommap');

	extract(shortcode_atts(array(
		'width' => '200',
		'height' => '200'
	), $attributes));

	return geoZoomUserMap($width, $height);
}

function geoWeatherShortcode($attributes) {

	recordGeoStats('weather');

	extract(shortcode_atts(array(
		'image' => 'on',
		'measurement' => 'Farenheit',
		'humidity' => 'on',
		'wind' => 'on',
		'miles' => '',
		'distancefrom' => '',
		'locationtype' => '',
		'location' => '',
		'reverse' => false
	), $attributes));

	if (!empty($distancefrom) && $miles > 1) {
		if ($reverse && (geoDistanceFrom($distancefrom) < $miles)) return false;
		elseif (!$reverse && geoDistanceFrom($distancefrom) > $miles) return false;
	}
	$locationTest = geoLocationContent($locationtype, $location, $reverse);
	if (!$locationTest) return false;


	return geoWeather($image, $measurement, $humidity, $wind);
}

function geoIP() {
	recordGeoStats('ip');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['IPAddress'];
	else return 'a series of numbers that mark your place on the internet';
}
function geoISP() {
	recordGeoStats('isp');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['Carrier'];
	else return 'the company that provides your internet access';
}
function geoContinent() {
	recordGeoStats('continent');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['Continent'];
	else return 'your continent';
}
function geoCountry() {
	recordGeoStats('country');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['Country'];
	else return 'your country';
}
function geoRegion() {
	recordGeoStats('region');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['Region'];
	else return 'your part of the country';
}
function geoState() {
	recordGeoStats('state');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['State'];
	else return 'your state';
}
function geoCity() {
	recordGeoStats('city');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['City'];
	else return 'your city';
}
function geoPostalCode() {
	recordGeoStats('postal');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['PostalCode'];
	else return 'your zip code';
}
function geoAreaCode() {
	recordGeoStats('areacode');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['AreaCode'];
	else return 'your telephone number area code';	
}
function geoLatitude() {
	recordGeoStats('lat');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['Latitude'];
	else return 'your latitude';
}
function geoLongitude() {
	recordGeoStats('lon');
	$geoPosty = getGeoPosty();

	if ($geoPosty) return $geoPosty['Longitude'];
	else return 'your longitude';
}

?>
