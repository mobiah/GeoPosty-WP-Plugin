<?php

add_action('media_buttons_context', 'geoposty_shortcode_helper');

function geoposty_shortcode_helper($base) {
	global $posty_plugin_url;

	$output = '<a href="#TB_inline?width=450&height=500&inlineId=geoposty_helper" class="thickbox" title="' . __("Add GeoPosty Shortcode") . '"><img src="'.$posty_plugin_url.'/images/icon-small.png" alt="' . __("Add GeoPosty Shortcodes") . '" /></a>';
	return $base . $output;
}

add_action('admin_footer',  'geoposty_shortcodes');
function geoposty_shortcodes(){
?>

	<script type="text/javascript">
	function InsertGeoShortCode(){
		var shortcode = jQuery("#add_geo_shortcode").val();
		var shortoptgroup = jQuery("#add_geo_shortcode").find("option:selected").parent().attr("label");
		var shortattr = '';

		if (shortoptgroup == 'Google Based Shortcodes') {
			if (jQuery('#geoWidgetWidth').val() > 0) shortattr += ' width="'+ jQuery('#geoWidgetWidth').val() +'"';
			if (jQuery('#geoWidgetHeight').val() > 0) shortattr += ' height="'+ jQuery('#geoWidgetHeight').val() +'"';
			if (jQuery('#geoWidgetResults').val() > 0) shortattr += ' results="'+ jQuery('#geoWidgetResults').val() +'"';
			if (jQuery('#geoWidgetSearch').val() != '') shortattr += ' search="'+ jQuery('#geoWidgetSearch').val() +'"';
			if (jQuery('#geoWidgetRadiusAddress').val() != '') shortattr += ' distancefrom="'+ jQuery('#geoWidgetRadiusAddress').val() +'"';
			if (jQuery('#geoWidgetRadiusDistance').val() > 0) shortattr += ' miles="'+ jQuery('#geoWidgetRadiusDistance').val() +'"';
			if (jQuery('#geoWidgetLocationType').val() != '') shortattr += ' locationtype="'+ jQuery('#geoWidgetLocationType').val() +'"';
			if (jQuery('#geoWidgetLocation').val()  != '') shortattr += ' location="'+ jQuery('#geoWidgetLocation').val() +'"';
			if (jQuery('#geoReverseSearch').is(':checked')) shortattr += ' reverse="flipit"';

		} else if (shortoptgroup == 'Weather Based Shortcodes') {
			if (jQuery('#geoWidgetImage').is(':checked')) shortattr += ' image="on"';
			if (jQuery('#geoWidgetHumidity').is(':checked')) shortattr += ' humidity="on"';
			if (jQuery('#geoWidgetWind').is(':checked')) shortattr += ' wind="on"';
			shortattr += ' measurement="'+ jQuery('#geoWidgetMeasurement').val() +'"';
			if (jQuery('#geoWidgetRadiusAddress').val() != '') shortattr += ' distancefrom="'+ jQuery('#geoWidgetRadiusAddress').val() +'"';
			if (jQuery('#geoWidgetRadiusDistance').val() > 0) shortattr += ' miles="'+ jQuery('#geoWidgetRadiusDistance').val() +'"';
			if (jQuery('#geoWidgetLocationType').val() != '') shortattr += ' locationtype="'+ jQuery('#geoWidgetLocationType').val() +'"';
			if (jQuery('#geoWidgetLocation').val()  != '') shortattr += ' location="'+ jQuery('#geoWidgetLocation').val() +'"';
			if (jQuery('#geoReverseSearch').is(':checked')) shortattr += ' reverse="flipit"';

		} else if (shortoptgroup == 'Location Content Filtering') {
			if (jQuery('#geoWidgetRadiusAddress').val() != '') shortattr += ' distancefrom="'+ jQuery('#geoWidgetRadiusAddress').val() +'"';
			if (jQuery('#geoWidgetRadiusDistance').val() > 0) shortattr += ' miles="'+ jQuery('#geoWidgetRadiusDistance').val() +'"';
			if (jQuery('#geoWidgetLocationType').val() != '') shortattr += ' locationtype="'+ jQuery('#geoWidgetLocationType').val() +'"';
			if (jQuery('#geoWidgetLocation').val()  != '') shortattr += ' location="'+ jQuery('#geoWidgetLocation').val() +'"';
			if (jQuery('#geoReverseSearch').is(':checked')) shortattr += ' reverse="flipit"';
		} 
		// PRIMER '1' FOR ADDED CODE M PILON
		  else if (shortoptgroup == 'Redirection Shortcodes') {
			if (jQuery('#destinationPage').val()  != '') shortattr += ' redirectpage="' + jQuery('#destinationPage').val() + '"';
			if (jQuery('input:radio[name=redirectType]:checked').val() != '') shortattr += ' redirecttype="' + jQuery('input:radio[name=redirectType]:checked').val() + '"';
			if (jQuery('#redirectURL').val() != '') shortattr += ' redirecturl="'+ jQuery('#destinationURL').val() +'"';
			if (jQuery('#geoWidgetRadiusAddress').val() != '') shortattr += ' distancefrom="'+ jQuery('#geoWidgetRadiusAddress').val() +'"';
			if (jQuery('#geoWidgetRadiusDistance').val() > 0) shortattr += ' miles="'+ jQuery('#geoWidgetRadiusDistance').val() +'"';
		}
		// BELOW ADDED BY M PILON 6/24/2010
		
		// reset input elements
		jQuery('#geoWidgetWidth, #geoWidgetHeight, #geoWidgetSearch, #geoWidgetRadiusAddress, #add_geo_shortcode').attr('value', '');
		jQuery('#geoMapOptions').slideUp();
		jQuery('#geoRadiusLimit').slideUp();
		jQuery('#geoLocationLimit').slideUp();
		jQuery('#geoWeatherOptions').slideUp();
		jQuery('#geoRedirectOptions').slideUp();
		jQuery('#geoLocalizedContent').slideUp();	
		jQuery('#geoReverse').slideUp();


			var win = window.dialogArguments || opener || parent || top;

		if (shortoptgroup == 'Location Content Filtering') {
			win.send_to_editor("[" + shortcode + shortattr +"]Put your geo-specific content here![/"+ shortcode +"]");
		} else {
			win.send_to_editor("[" + shortcode + shortattr +"]");
		}
	    }

	function geoShortCodeForm(){
		var shortcode = jQuery("#add_geo_shortcode").val();
		var shortoptgroup = jQuery("#add_geo_shortcode").find("option:selected").parent().attr("label");

		if (shortoptgroup == 'Google Based Shortcodes') {
			jQuery('#geoMapOptions').slideDown();
			jQuery('#geoRadiusLimit').slideDown();
			jQuery('#geoLocationLimit').slideDown();
			jQuery('#geoReverse').slideDown();
			jQuery('#geoLocalizedContent').slideUp();
			jQuery('#geoWeatherOptions').slideUp();
			jQuery('#geoRedirectOptions').slideUp();
		} else if (shortoptgroup == 'Weather Based Shortcodes') {
			jQuery('#geoMapOptions').slideUp();
			jQuery('#geoRadiusLimit').slideDown();
			jQuery('#geoLocationLimit').slideDown();
			jQuery('#geoReverse').slideDown();
			jQuery('#geoWeatherOptions').slideDown();
			jQuery('#geoLocalizedContent').slideUp();
			jQuery('#geoRedirectOptions').slideUp();
		} else if (shortoptgroup == 'Location Content Filtering') {
			jQuery('#geoMapOptions').slideUp();
			jQuery('#geoRadiusLimit').slideDown();
			jQuery('#geoLocationLimit').slideDown();
			jQuery('#geoReverse').slideDown();
			jQuery('#geoLocalizedContent').slideDown();
			jQuery('#geoWeatherOptions').slideUp();
			jQuery('#geoRedirectOptions').slideUp();
		} else if (shortoptgroup == 'Redirection Shortcodes') {
			jQuery('#geoMapOptions').slideUp();
			jQuery('#geoRadiusLimit').slideDown();
			jQuery('#geoLocationLimit').slideDown();
			jQuery('#geoReverse').slideDown();
			jQuery('#geoLocalizedContent').slideUp();
			jQuery('#geoWeatherOptions').slideUp();	
			jQuery('#geoRedirectOptions').slideDown();
		} else {
			jQuery('#geoMapOptions').slideUp();
			jQuery('#geoRadiusLimit').slideUp();
			jQuery('#geoLocalizedContent').slideUp();
			jQuery('#geoLocationLimit').slideUp();
			jQuery('#geoReverse').slideUp();
			jQuery('#geoWeatherOptions').slideUp();
			jQuery('#geoRedirectOptions').slideUp();
		}
	}

	</script>

	<div id="geoposty_helper" style="display:none;">
	    <div class="wrap">
		<div>
		    <div style="padding:15px 15px 0 15px;">
			<h3><?php _e("GeoPosty Shortcodes"); ?></h3>
		    </div>
		    <div style="padding:15px 15px 0 15px;">
			<select id="add_geo_shortcode" onchange="geoShortCodeForm();">
			    <option value="">  <?php _e("Select a shortcode"); ?>  </option>
				<optgroup label="Basic Shortcodes">
					<option value="geoip">IP</option>
					<option value="geoisp">ISP</option>
					<option value="geocontinent">Continent</option>
					<option value="geocountry">Country</option>
					<option value="georegion">Region</option>
					<option value="geostate">State</option>
					<option value="geocity">City</option>
					<option value="geoareacode">Area Code</option>
					<option value="geolatitude">Latitude</option>
					<option value="geolongitude">Longitude</option>
				</optgroup>
				<optgroup label="Location Content Filtering">
					<option value="geocontent">Localized Content</option>
				</optgroup>
				<optgroup label="Google Based Shortcodes">
					<option value="geogooglemap">Google Business Map</option>
					<option value="geobusiness">Google Business Listing</option>
				</optgroup>
				<optgroup label="Weather Based Shortcodes">
					<option value="geoweather">Weather</option>
				</optgroup>
				<!-- below added by M PILON 6/25/2010 
				<optgroup label="Redirection Shortcodes">
						<option value="georredirect">Radius Based Redirect</option>
				</optgroup>
				-->
				

			</select> <br/>
		    </div>

			<div id="geoMapOptions" style="display:none;padding:15px 15px 0 15px;">
				<label for="geoWidgetWidth"><?php _e("Width"); ?></label> <input type="text" id="geoWidgetWidth" maxlength="4" size="3"  /> px <br />
				<label for="geoWidgetHeight"><?php _e("Height"); ?></label> <input type="text" id="geoWidgetHeight" maxlength="4" size="3"  /> px <em>We recommend something like 350x350, depending on your layout</em><br />

				<label for="geoWidgetResults"><?php _e("Maximum Number of Results to Display"); ?></label> <select id="geoWidgetResults">
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
					<option>6</option>
					<option>7</option>
					<option>8</option>
				</select><br />
				<label for="geoWidgetSearch"><?php _e("Search"); ?></label> <input type="text" id="geoWidgetSearch" class="regular-text"  /><br />
				<em>Term, Business Name or Point of Interest</em>
			</div>

			<div id="geoWeatherOptions" style="display:none;padding:15px 15px 0 15px;">
				<input type="checkbox" id="geoWidgetImage"  /> <label for="geoWidgetImage"><?php _e("Image"); ?></label><br />
				<input type="checkbox" id="geoWidgetHumidity"  /> <label for="geoWidgetHumidity"><?php _e("Humidity"); ?></label><br />
				<input type="checkbox" id="geoWidgetWind"  /> <label for="geoWidgetWind"><?php _e("Wind"); ?></label><br />
				<select id="geoWidgetMeasurement"><option value="Fahenheit">Fahenheit</option><option value="Celsuis">Celsius</option> </select> <label for="geoWidgetMeasurement"><?php _e("Measurement"); ?></label><br />
			</div>

			<div id="geoRadiusLimit" style="display:none;padding:0 15px">
				<h4 class="geoswitch"><a href="#" class="geoWidgetRadius">Radius Based Filtering</a> <em>Will display to visitors near a certain location</em></h4>

				<div class="geoWidgetRadius" style="display:none;">

					<p>This feature is only enabled for US locations at this time.</p>

					<label for="geoWidgetRadiusDistance"><?php _e("Show this item to people who are within"); ?></label> <select id="geoWidgetRadiusDistance">
						<option></option>
						<option>50</option>
						<option>100</option>
						<option>200</option>
						<option>500</option>
						<option>1000</option>
						<option>1500</option>
						<option>5000</option>
					</select>  miles <br />

					of <input type="text" id="geoWidgetRadiusAddress" class="regular-text"  /><br />
					<label for="geoWidgetRadiusAddress"><em><?php _e("This must be in the form <strong>City Name, ST</strong>."); ?></em></label><br />

				</div>	
			</div>
			
			<div id="geoLocationLimit" style="display:none;padding:0 15px;">
				<h4 class="geoswitch"><a href="#" class="geoWidgetLocation">Location Based Filtering</a> <em>Will display to visitors in the location that you name</em></h4>

				<div class="geoWidgetLocation" style="display:none;">
					<label for="geoWidgetLocationType"><?php _e("Show this item to people who are in the"); ?></label>	
					<select id="geoWidgetLocationType">
						<option></option>
						<option>City</option>
						<option>State/Province/Territory</option>
						<option>Continent</option>
						<option>Country</option>
						<option>US Area Code</option>
					</select><br />

					of <input type="text" id="geoWidgetLocation" class="regular-text"  /><br />

					<p><a href="http://www.usps.com/ncsc/lookups/usps_abbreviations.html" target="_blank">Use the USPS 2-letter code for US states</a><br />
					<a href="http://www.iso.org/iso/english_country_names_and_code_elements" target="_blank">Use the ISO 2-letter code for countries</a></p>
				</div>

			</div>

			<div id="geoReverse" style="display:none;padding:0 15px;">
				<label for="geoReverseSearch">Reverse filtering</label>
				<input type="checkbox" id="geoReverseSearch" />
			</div>

			<div id="geoLocalizedContent" style="display:none;padding:0 15px;">
				<p><em>Step 1: Customize your display settings here.<br />
				Step 2: Click Insert to put the shortcode into your page or post.<br />
				Step 3: Put your content in between the two shortcode sections, like this: [shortcode]Your content goes here![/shortcode]</em></p>
			</div>
			
			<!-- added by matt pilon 6/25/2010 -->
			<div id="geoRedirectOptions" style="display:none; padding:15px 15px 0 15px;">
					<h4>Redirect users meeting this criterion to:</h4>
					<input type="radio" name="redirectType" value="page">&nbsp; &nbsp; This page: &nbsp; &nbsp;<?php wp_dropdown_pages(array("name" => 'destinationPage', "show_option_none" => "Select a page", "selected" => '')); ?><br />&nbsp; &nbsp;&nbsp; &nbsp;-OR-<br>
					<input type="radio" name="redirectType" value="url">&nbsp; &nbsp;This URL:&nbsp; &nbsp; <input type="text" id="destinationURL">	 
			</div>
			<!-- end added by matt pilon -->
			
		    <div style="padding:15px;">
			<input type="button" class="button-primary" value="Insert Shortcode" onclick="InsertGeoShortCode();"/>&nbsp;&nbsp;&nbsp;
		    	<a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel"); ?></a> <br />

			<p><em>Google Maps will only allow one map to display per page. Other GeoPosty features may appear several times on the same page, but you must insert a separate shortcode for each.</em></p>
		    </div>
		</div>
	    </div>
	</div>

<?php
}

add_action('wp_ajax_geo_confirm', 'geo_ajax_confirm');
add_action('wp_ajax_geo_followup', 'geo_ajax_followup');

function geo_ajax_confirm() {
	global $current_user, $_GET;

	$ip = getGeoIpAddress();
	$host = $_SERVER['HTTP_HOST'];
	if(GDEBUG) { error_log("geoposty:admin:geo_ajax_confirm host=$host ip=$ip"); }
	$geoRequestURL = GEOSERVER . 'domain='. $host .'&ip='. $ip .'&domainkey=' . $_GET['domainkey'];
	if(GDEBUG) { error_log("geoposty:admin:geo_ajax_confirm geoRequestURL=$geoRequestURL"); }

	$data = trim(wp_remote_retrieve_body(wp_remote_get($geoRequestURL)));
	$geoPostyXML = @simplexml_load_string($data);
	if (!$geoPostyXML) { echo 'There was some type of problem with your request. The API said: <em>' . $data . '</em>'; }
	die();
}

function geo_ajax_followup() {
	global $current_user;
	// WARN current_user user_email: assumes that my local email here is same as email I typed on geoposty.com to get api key
	$geoRequestURL = GEOSERVER . 'domain='. $_SERVER['HTTP_HOST'] .'&email='. $current_user->user_email .'&confirm=1';
	$data = trim(wp_remote_retrieve_body(wp_remote_get($geoRequestURL)));
	if(GDEBUG) { error_log("geoposty:admin:geo_ajax_followup url=$geoRequestURL data=$data geoposty_api_key=$geoposty_api_key"); }
	echo $data;
	die();
}

add_action('admin_menu', 'geoposty_config_page');
function geoposty_config_page() {
	global $posty_plugin_url, $geoposty_api_key;

	if ( function_exists('add_menu_page') ) {
		add_menu_page(__('GeoPosty Account Manager'), __('GeoPosty'), 'manage_options', 'geoposty-key-config', 'geoposty_conf', $posty_plugin_url . '/images/icon.png');

		if (!empty($geoposty_api_key)) {
			add_submenu_page( 'geoposty-key-config', __('GeoPosty Account Manager'), __('Config &amp; Stats'), 'manage_options', 'geoposty-key-config', 'geoposty_conf');
			add_submenu_page( 'geoposty-key-config', __('GeoPosty Redirects'), __('Redirects'), 'manage_options', 'geoposty-redirects', 'geoRedirectsConfig');
			add_submenu_page( 'geoposty-key-config', __('GeoPosty Readme'), __('Help'), 'manage_options', 'geoposty-readme', 'geopostyReadme');
		}
	}
}

function geopostyReadme() {
	$file = dirname(__FILE__)  . '/help.html';
	if(GDEBUG) { error_log("geoposty:admin:geopostyReadme file=$file"); }
	$readme = file_get_contents($file);
	$msg = <<<PAGE
<div class="wrap">
	<div class="tool-box">$readme
	</div>
</div>
PAGE;
	print $msg;
}

function geoRedirectsConfig() {
	if ( isset($_POST['submit']) ) {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?'));

		$redirects = $_POST['geoRedirects'];

		// let's kill a bug
		for($r=0;$r<count($redirects);$r++) {
			if (($redirects[$r]['source'] == '/' && $redirects[$r]['destination'] == get_option('siteurl') . '/')) unset($redirects[$r]);
		}

		$redirects = array_merge($redirects);

		if ( empty($redirects) ) {
			delete_option('geoposty_redirects');
		} else {
			update_option('geoposty_redirects', $redirects);
		}
	}
?>

<script type="text/javascript">
window.onbeforeunload = function() { 
  if (geoRedirectsWarning) {
    return 'You have made changes on this page that you have not yet confirmed. If you navigate away from this page you will lose your unsaved changes';
  }
}
</script>

<div class="wrap">
	<h2><?php _e('GeoPosty Redirects'); ?></h2>
	<div class="tool-box">
		<p id="geoRedirectSave">Configure some redirects!</p>

		<form action="" method="post" id="geoposty-redirects" >

		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" class="manage-column">Redirect</th>
					<th scope="col" class="manage-column">Filtering Method</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" class="manage-column">Redirect</th>
					<th scope="col" class="manage-column">Filtering Method</th>
				</tr>
			</tfoot>
			<tr>
				<td valign="top"><p><label for="geoRedirectSource">Visitors to this url:</label><br />
				<?php echo get_option('siteurl'); ?><input type="text" id="geoRedirectSource" class="geoLongText" name="geoRedirects[0][source]" value="/" /><br />
				<label for="geoRedirectDestination">will be redirected to this url:</label><br />
				<input type="text" id="geoRedirectDestination" class="geoLongText" name="geoRedirects[0][destination]" value="<?php echo get_option('siteurl'); ?>/" /></p></td>
				<td valign="top">

					<h4 class="geoswitch"><a href="#" class="geoWidgetRadius">Radius Based Filtering</a></h4>

					<div class="geoWidgetRadius" style="display:none;">

						<p>This feature is only enabled for US locations at this time.</p>

						<label for="geoWidgetRadiusDistance"><?php _e("Show this item to people who are within"); ?></label> <select name="geoRedirects[0][radius]" id="geoWidgetRadiusDistance">
							<option></option>
							<option>50</option>
							<option>100</option>
							<option>200</option>
							<option>500</option>
							<option>1000</option>
							<option>1500</option>
							<option>5000</option>
						</select>  miles <br />

						of <input type="text" name="geoRedirects[0][radiuslocation]" id="geoWidgetRadiusAddress" class="regular-text"  /><br />
						<label for="geoWidgetRadiusAddress"><em><?php _e("This must be in the form <strong>City Name, ST</strong>."); ?></em></label><br />


					</div>	
			
					<h4 class="geoswitch"><a href="#" class="geoWidgetLocation">Location Based Filtering</a></h4>

					<div class="geoWidgetLocation" style="display:none;">
						<label for="geoWidgetLocationType"><?php _e("Show this item to people who are in the"); ?></label>	
						<select id="geoWidgetLocationType" name="geoRedirects[0][location]">
							<option></option>
							<option>City</option>
							<option>State/Province/Territory</option>
							<option>Continent</option>
							<option>Country</option>
							<option>US Area Code</option>
						</select><br />

						of <input type="text" id="geoWidgetLocation" class="regular-text" name="geoRedirects[0][locationaddress]"  /><br />

						<p><a href="http://www.usps.com/ncsc/lookups/usps_abbreviations.html" target="_blank">Use the USPS 2-letter code for US states</a><br />
						<a href="http://www.iso.org/iso/english_country_names_and_code_elements" target="_blank">Use the ISO 2-letter code for countries</a></p>
					</div>


				</td>
			</tr>
<?php
	$geoRedirects = get_option('geoposty_redirects');

	if (is_array($geoRedirects)) {

		$counter = 0;
		foreach ($geoRedirects as $redirect) {

			if (empty($redirect['source']) || empty($redirect['destination'])) continue;

			$counter++;
		?>
				<tr id="geoDeleteRow<?php echo $counter; ?>">
				
					<td valign="top"><p><label for="geoRedirectSource<?php echo $counter; ?>">Visitors to this url:</label><br />
					<input type="text" id="geoRedirectSource<?php echo $counter; ?>" class="geoLongText" name="geoRedirects[<?php echo $counter; ?>][source]" value="<?php echo $redirect['source']; ?>" /><br />
					<label for="geoRedirectDestination<?php echo $counter; ?>">will be redirected to this url:</label><br />
					<input type="text" id="geoRedirectDestination<?php echo $counter; ?>" class="geoLongText" name="geoRedirects[<?php echo $counter; ?>][destination]" value="<?php echo $redirect['destination']; ?>" /><br />
					<strong class="geoDeleteMe"><a href="#" class="geoDeleteRow<?php echo $counter; ?>">Delete This Redirect</a></strong></p></td>
					<td valign="top">

					<?php
						if ($redirect['radius'] > 49) {
					?>
						<strong>Radius Based Filtering</strong><br />

						<label><?php _e("Show this item to people who are within"); ?></label> <select name="geoRedirects[<?php echo $counter; ?>][radius]">
							<option><?php echo $redirect['radius']; ?></option>
							<option>50</option>
							<option>100</option>
							<option>200</option>
							<option>500</option>
							<option>1000</option>
							<option>1500</option>
							<option>5000</option>
						</select>  miles <br />

						of <input type="text" name="geoRedirects[<?php echo $counter; ?>][radiuslocation]" class="regular-text" value="<?php echo $redirect['radiuslocation']; ?>" /><br />
						<label><em><?php _e("This must be in the form <strong>City Name, ST</strong>."); ?></em></label>
					<?php
						} else {
					?>
						<strong>Location Based Filtering</strong><br />

						<label><?php _e("Show this item to people who are in the"); ?></label>	
						<select name="geoRedirects[<?php echo $counter; ?>][location]">
							<option><?php echo $redirect['location']; ?></option>
							<option>City</option>
							<option>State/Province/Territory</option>
							<option>Continent</option>
							<option>Country</option>
							<option>US Area Code</option>
						</select><br />

						of <input type="text" class="regular-text" name="geoRedirects[<?php echo $counter; ?>][locationaddress]" value="<?php echo $redirect['locationaddress']; ?>"  /><br />
					<?php
						}
					?>


					</td>
				</tr>


		<?php
		}
	}
?>

		</table>

		<p class="submit"><input type="submit" id="georedirectsubmit" class="button-primary" name="submit" value="<?php _e('Save Redirects &raquo;'); ?>" /></p>
		</form>

	</div>
</div>
<?php
/*
	echo '<pre>';
	print_r(get_option('geoposty_redirects'));
	echo '</pre>';


Source URL
Destination URL
Status Code
Filtering
	Location
	Radius
*/
}

function geoCachingPlugins() {
	require_once(ABSPATH.'/wp-admin/admin-functions.php');

	$plugins = get_plugins();

	foreach($plugins as $plug) {
		$cacher = $plug['Name'] . $plug['Description'];

		if (stristr($cacher, 'cache')) return true;
	}

	return false;
}


function geoStatsGraph($type, $count) {
	$geoDailyStats = geoAdminStats($type);
	$geoMaxDay = 0;
	$geoDayCount = 0;

	foreach ($geoDailyStats as $geoDay) {
		$geoDayCount++;
		if ($geoDayCount == $count) break;
		if ($type == 'd') {
			$arrayGeoStats[] = $geoDay['1'];
			$arrayGeoDays[] = date('m-d', strtotime($geoDay['0']));
			if ($geoMaxDay < $geoDay['1']) $geoMaxDay = $geoDay['1'];
		} else {
			$arrayGeoStats[] = $geoDay['2'];
			$arrayGeoDays[] = $geoDay['0'];
			if ($geoMaxDay < $geoDay['2']) $geoMaxDay = $geoDay['2'];
		}
	}

	$displayGeoStats = @array_reverse($arrayGeoStats);
	$displayGeoDays = @array_reverse($arrayGeoDays);

	$graph = 'http://chart.apis.google.com/chart?cht=lc&chs=800x300&chco=217297&chd=t:'. @implode($displayGeoStats, ',') .'&chxt=y,x&chxr=0,0,'. $geoMaxDay .'&chds=0,'. $geoMaxDay .'&chxl=1:|'. @implode($displayGeoDays, '|');
	return $graph;
}

function geoposty_conf() {

	if ( isset($_POST['submit']) ) {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?'));

		// check_admin_referer( $geoposty_nonce );
		$key = trim($_POST['geoPostyKey']);
		if(GDEBUG) { error_log("geoposty:admin:geoposty_conf key=:$key:"); }

		// reset our cache for testing!
		// this isn't permanent
		reset_geo_cache();

		if ( empty($key) ) { delete_option('geoposty_api_key'); } 
		else { 
			update_option('geoposty_api_key', $key); 
			$sname = "admin.php?page=geoposty-readme";
			if(GDEBUG) { error_log("geoposty:admin:geoposty_conf update_option : saving key to database sname=$sname"); }
			// jump to help page for first time user
			?>
			<script type="text/javascript">window.location = '<?php echo $sname; ?>';</script>
			<?php
		}
	}

	// don't use global here
	$geoposty_api_key = get_option('geoposty_api_key');
	if(GDEBUG) { error_log("geoposty:admin:geoposty_conf api_key=:$geoposty_api_key:"); }
?>
<?php if ( !empty($_POST['submit'] ) ) : ?>
	<div id="message" class="updated fade">
		<p><strong><?php _e('Information saved. This page will automatically refresh to get you the most recent information. <a href="'.$_SERVER['SCRIPT_NAME'].'?page=geoposty-key-config">You can manually reload.</a>') ?></strong></p>
	</div>
	<script type="text/javascript">window.location = '<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=geoposty-key-config';</script>
<?php 
	return;
	endif;
 ?>

<?php
	if (geoCachingPlugins()) {
?>
<div class="updated fade"><p><strong><?php _e('It looks like you have a caching plugin installed, that may cause problems with GeoPosty. <a href="http://geoposty.com/faq/">Take a look at our FAQs.</a>') ?></strong></p></div>
<?php
	}
?>

<div class="wrap">
	<h2><?php _e('GeoPosty Configuration'); ?></h2>
	<div class="tool-box">
<?php
	if (!empty($geoposty_api_key)) {
	
	$geoAdminSummary = geoAdminStats('s');
	$geoAdminSubscription = geoAdminStats('p');
	$subserver = SERVER . 'isubs.php?domainkey=' . $geoposty_api_key . '&domain=' . $_SERVER['HTTP_HOST'];
	$host = $_SERVER['HTTP_HOST'];
	if(GDEBUG) { error_log("geoposty:admin:geoposty_conf host=$host : subserver=$subserver"); }

	if ($geoAdminSubscription[0][2] > 1) {
		// subscribed!
?>
		<h3><?php echo number_format(($geoAdminSummary['0']['2']/$geoAdminSummary['0']['1'])*100); ?>% of lookups used for this subscription period.</h3>

		<iframe src="<?php echo $subserver; ?>" width="400" height="150" style="display:none;" class="alignright" id="geoChangeiframe"></iframe>

		<p><strong>Subscription Information</strong><br />
		Monthly Subscription: <?php echo number_format($geoAdminSubscription[0][2]); ?> lookups/month for $<?php echo number_format($geoAdminSubscription[0][0], 2); ?>  <a href="#" id="geoChangeSubscription">Change</a><br />
		Month Start Day: <?php echo $geoAdminSubscription[0][1]; ?>
		</p>

<?php
	} else {
		// not subscribed!
		if($host == 'localhost') {
?>
		<h2>You are using a LOCALHOST test key.</h2>
		<h3>When moving to your live server, you MUST install the Geoposty plugin there in order to get a live key</h3>
<?php
		}
		else {
?>
		<iframe src="<?php echo $subserver?>" width="400" height="150" class="alignright"></iframe>
		<h3>You have used <?php echo $geoAdminSummary['0']['2']; ?> of your 10,000 free* lookups this month!</h3>

		
<?php
		}
	}


	if($host == 'localhost') { }
	else {
?>


			<h3>GeoPosty Usage</h3>

			<h4 style="float:left;margin-right:10px;"><a href="#" id="geoDailyLink">Daily Usage</a></h4> 
			<h4 style="float:left;margin-right:10px;"><a href="#" id="geoWeeklyLink">Weekly Usage</a></h4> 
			<h4 style="float:left;margin-right:10px;"><a href="#" id="geoMonthlyLink">Monthly Usage</a></h4> 

			<div style="width:800px;height:300px;clear:both;" id="geoGraphsWrapper">
				<img src="<?php echo geoStatsGraph('d', '21'); ?>" alt="Daily Usage" id="geoDailyGraph" />
				<img src="<?php echo geoStatsGraph('w', '10'); ?>" alt="Weekly Usage" id="geoWeeklyGraph" style="display:none;" />
				<img src="<?php echo geoStatsGraph('m', '6'); ?>" alt="Monthly Usage" id="geoMonthlyGraph" style="display:none;" />
			</div>




<?php
		}
	}

	if (empty($geoposty_api_key)) { 
		echo '<p>Hello, new GeoPosty user! Let\'s get you set up to start putting localized content on your site!</p>';

		$geoposty_tests = get_option('geoposty_tests');

		if (empty($geoposty_tests)) {
			echo '<p>First, we need to run a couple of tests to confirm that your server can run GeoPosty. Don\'t worry, this won\'t hurt a bit.</p>';

			if (version_compare(phpversion(), "5.0", ">=")) $geoPHPTest = ' class="geopass"';
			if (function_exists(simplexml_load_string)) $geoXMLTest = ' class="geopass"';
			if (function_exists(json_decode)) $geoJSONTest = ' class="geopass"';
			if (wp_remote_retrieve_response_code(wp_remote_get(SERVER)) == '200') $geoRemoteAPI = ' class="geopass"';

?>
			<dl class="geoTests">
				<dt>PHP Version</dt>
				<dd<?php echo $geoPHPTest; ?>>Confirming PHP version</dd>	
				<dt>SimpleXML</dt>
				<dd<?php echo $geoXMLTest; ?>>Confirming SimpleXML is available</dd>
				<dt>json_decode</dt>
				<dd<?php echo $geoXMLTest; ?>>Confirming json_decode is available</dd>
				<dt>External Request to API</dt>
				<dd<?php echo $geoRemoteAPI; ?>>Outbound request to API</dd>
			</dl>
<?php
			if (!empty($geoPHPTest) && !empty($geoXMLTest) && !empty($geoJSONTest) && !empty($geoRemoteAPI)) {
				add_option('geoposty_tests', 'Great success!!');
?>
				<h2 class="geoSuccess">Success! You can run GeoPosty on your server. <a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=geoposty-key-config">Continue to configuration</a></h2>
<?php	
			}
		} 
		else {
?>
			<form action="" method="post" id="geoposty-conf" >

				<input type="hidden" id="geoPostyTest" value="" />

				<table class="form-table">
					<tr>
						<th scope="row"><label for="geoPostyKey">First, enter your key here:</label></th>
						<td><input id="geoPostyKey" name="geoPostyKey" type="text" class="regular-text" value="<?php echo $geoposty_api_key; ?>" /></td>
					</tr>

					<tr>
						<td colspan="2" class="aligncenter">
<?php 
	if (empty($geoposty_api_key)) {
		if($_SERVER['HTTP_HOST'] == 'localhost') { echo '<a href="http://geoposty.com/request-geoposty-localhost-development-key/" target="_blank">Request a <b>LOCALHOST</b> test API key</a>'; }
		else { echo '<a href="http://geoposty.com/request-your-api-key/" target="_blank">Request an API key</a>'; }
	}
?>
						<div id="geoKeyReply"></div></td>
					</tr>
				</table>

			<p class="submit"><input type="submit" id="geosubmit" class="button-primary" name="submit" value="Test Your Key &raquo;" /></p>
			</form>
<?php
		}
	}
?>


	</div><!-- narrow -->
</div><!-- wrap -->
<?php
}



// this isn't permanent
function reset_geo_cache() {
	global $wpdb;

	// is there not a built in way to do this?
	$facestransients = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%_transient_geo%'" );

	foreach ($facestransients as $trans) {
		$deleteTransient = str_replace('_transient_' , '', $trans->option_name);
		delete_transient($deleteTransient);
	}
}
?>
