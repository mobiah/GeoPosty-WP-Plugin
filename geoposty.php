<?php
/*
	Plugin Name: Geo Location Tool GeoPosty 

	Plugin URI: http://geoposty.com/

	Description: Provide users a more geographically rich experience with GeoPosty.  Leveraging IP geo location data from the Quova platform, you can provide users with maps, weather, business, and text not only relevant to your topic, but relevant to your user's location.  Widgets and shortcodes are preloaded to make implementation a snap.
	Version: 0.9.2

	Author: GeoPosty Team
	Author URI: http://geoposty.com/
*/

// debug
// error_reporting (E_ALL ^ E_NOTICE);
// ini_set("display_errors", 1);
// debug

define('GDEBUG',false);
define('SERVER','http://api.geoposty.com/');
define('GEOSERVER','http://api.geoposty.com/geo.php?');


require(dirname(__FILE__)  . '/functions.php');

// need to compress all databae entries into single array
$geoposty_api_key = get_option('geoposty_api_key');
$posty_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__));
$geoMD5 = md5(getGeoIpAddress());
if(GDEBUG) { error_log("geoposty: api_key=$geoposty_api_key"); }

if (empty($geoposty_api_key)) {

	function geoposty_warning() {
		echo "
		<div id='geoposty-warning' class='updated fade'><p><strong>".__('GeoPosty needs your attention.')."</strong> ".sprintf(__('You must <a href="%1$s">enter your API credentials</a> for it to work.'), "plugins.php?page=geoposty-key-config")."</p></div>
			";
	}
	add_action('admin_notices', 'geoposty_warning');
	if (is_admin()) {
		require(dirname(__FILE__)  . '/admin.php');
		wp_enqueue_script('geopostyadminjs', $posty_plugin_url . "/js/geoposty-admin.js", array('jquery'));
		wp_enqueue_style('geopostyadmincss', $posty_plugin_url . "/css/geoposty-admin.css");
	}

} else {

	require(dirname(__FILE__)  . '/curl.php');
	//if (is_admin()) 

	// get our quova data
	// $geoPostyXML = getGeoPostyXML($geoposty_api_key);

	require(dirname(__FILE__)  . '/widgets.php');
	require(dirname(__FILE__)  . '/shortcodes.php');
	require(dirname(__FILE__)  . '/reporting.php');

	if (!is_admin()) {
		// make sure we have jquery
		wp_enqueue_script('jquery');

		// we need javascript for the google widgets!
		wp_register_script('googlejs', "http://www.google.com/jsapi");
		wp_enqueue_script('googlejs');

		// now the javascript that is fun
		wp_register_script('geopostyjs', $posty_plugin_url . "/js/geoposty.js");
		wp_enqueue_script('geopostyjs');
	} else {
		require(dirname(__FILE__)  . '/admin.php');
		wp_enqueue_script('geopostyadminjs', $posty_plugin_url . "/js/geoposty-admin.js", array('jquery'));
		wp_enqueue_style('geopostyadmincss', $posty_plugin_url . "/css/geoposty-admin.css");

		// reserved for checking updates and sending users messages
		// add_action('after_plugin_row_' . plugin_basename(__FILE__),  'geoposty_plugin_row');
	}
}


register_deactivation_hook( __FILE__, 'geoposty_deactivate');

function geoposty_deactivate() {
	// remove the options we put into the database
	// transient stuff will leave on its own
	delete_option('geoposty_api_key');
	delete_option('geoposty_tests');
	delete_option('geoposty_redirects');
	delete_option('geoLogging');
	delete_option('geoHundred');
	delete_option('geoStats');
	delete_option('geoAddressLocation');			
}

?>
