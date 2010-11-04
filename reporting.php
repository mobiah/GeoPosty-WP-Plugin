<?php
// our reporting
if ( !wp_next_scheduled('geoUsageReporting') ) {
	wp_schedule_event( time(), 'twicedaily', 'geoUsageReporting' ); // hourly, daily and twicedaily
}


function geoUsageReportingFunction() {
	global $geoposty_api_key;

	$geoStats = get_option('geoStats');

	$options = array();
	$options['timeout'] = 1;
	$options['body'] = $geoStats;
	$options['body']['domainkey'] = $geoposty_api_key;
	$options['body']['domain'] = $_SERVER['HTTP_HOST'];

	$doStats = wp_remote_post('http://api.geoposty.com/stats.php', $options);

	if (is_wp_error($doStats)) return;

	delete_option('geoStats');
}

add_action('geoUsageReporting', 'geoUsageReportingFunction');

register_deactivation_hook(__FILE__, 'removeGeoReporting');

function removeGeoReporting() {
	wp_clear_scheduled_hook('geoUsageReporting');
}


?>
