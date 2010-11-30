<?php
// geoPosty map sidebar widget
// this creates a static map, i think it's useful so we won't delete this yet
class postyMapSidebar extends WP_Widget {
	function postyMapSidebar() {
		$widget_ops = array('classname' => 'posty_map_sidebar', 'description' => 'Adds a static map to your site' );
		$this->WP_Widget('posty_static_map', 'GeoPosty: Static Maps', $widget_ops);
	}

	function widget($args, $instance){
		global $posty_plugin_url;
		
		// $data = get_option('postyMap');
		extract($args, EXTR_SKIP);

		if (!empty($instance['distancefrom']) && $instance['miles'] > 1) {
			if ($instance['reverse'] && (geoDistanceFrom($instance['distancefrom']) < $instance['miles'])) return false;
			elseif (!$instance['reverse'] && geoDistanceFrom($instance['distancefrom']) > $instance['miles']) return false;
		}
		$locationTest = geoLocationContent($instance['locationtype'], $instance['location'], $instance['reverse']);
		if (!$locationTest) return false;

		recordGeoStats('w_staticmap');

		echo $args['before_widget'];
		echo $args['before_title'] . do_shortcode(stripslashes($instance['title'])) . $args['after_title'];
	//	echo '<p><img src="http://maps.google.com/maps/api/staticmap?center='. $geoPosty['Latitude'] .','. $geoPosty['Longitude'] .'&zoom='. $instance['zoom'] .'&size='. $instance['width'] .'x'. $instance['height'] .'&maptype='. $instance['maptype'] .'&sensor=false" class="geoMap" width="'.$instance['width'].'" height="'.$instance['height'].'" alt="GeoPosty &amp Google Generated Map" /></p>';

		echo '<p>' . geoStaticMap($instance['zoom'], $instance['width'], $instance['height'], $instance['maptype'], $instance['marker']) . '</p>';

		echo $args['after_widget'];
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['maptype'] = strip_tags($new_instance['maptype']);
		$instance['zoom'] = strip_tags($new_instance['zoom']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['marker'] = strip_tags($new_instance['marker']);
		$instance['distancefrom'] = strip_tags($new_instance['distancefrom']);
 		$instance['miles'] = strip_tags($new_instance['miles']);
 		$instance['locationtype'] = strip_tags($new_instance['locationtype']);
 		$instance['location'] = strip_tags($new_instance['location']);
		$instance['reverse'] = strip_tags($new_instance['reverse']);

		// preload the distance cache!
		if (!empty($instance['distancefrom'])) geoGetAddressLocation($instance['distancefrom']);

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'width' => '', 'height' => '', 'zoom' => '', 'maptype' => '', 'marker' => '', 'distancefrom' => '', 'miles' => '' ) );
		$maptype = strip_tags($instance['maptype']);
		$width = strip_tags($instance['width']);
		$height = strip_tags($instance['height']);
		$zoom = strip_tags($instance['zoom']);
		$title = strip_tags($instance['title']);
		$marker = strip_tags($instance['marker']);
		$distancefrom = strip_tags($instance['distancefrom']);
		$miles = strip_tags($instance['miles']);
 		$locationtype = strip_tags($instance['locationtype']);
		$location = strip_tags($instance['location']);
		$reverse = strip_tags($instance['reverse']);

	?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title <input name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" value="<?php echo stripslashes($title); ?>" class="widefat" /></label></p>
		<p><label>Map Type <select name="<?php echo $this->get_field_name('maptype'); ?>"><option <?php if ($maptype == 'roadmap') echo 'selected="selected"'; ?>>roadmap</option><option <?php if ($maptype == 'satellite') echo 'selected="selected"'; ?>>satellite</option><option <?php if ($maptype == 'terrain') echo 'selected="selected"'; ?>>terrain</option><option <?php if ($maptype == 'hybrid') echo 'selected="selected"'; ?>>hybrid</option></select></label></p>
		<p><label>Zoom (1-21) <input name="<?php echo $this->get_field_name('zoom'); ?>" type="text" value="<?php echo $zoom; ?>" class="widefat" /></label><br />
		<em>zoom level 1=continent, zoom level 21=user's backyard.  we recommend something like 12.</em></p>
		<p><label>Width <input name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" class="widefat" /></label></p>
		<p><label>Height <input name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" class="widefat" /></label></p>

		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Radius Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($miles) && empty($distancefrom)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'distancefrom' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Distance From <input name="<?php echo $this->get_field_name('distancefrom'); ?>" type="text" value="<?php echo $distancefrom; ?>" class="widefat" /></label></p>
			<p><label for="<?php echo $this->get_field_id( 'miles' ); ?>">Miles 
					<select name="<?php echo $this->get_field_name('miles'); ?>">
						<option></option>
						<option <?php if ($miles == '50') echo 'selected="selected"'; ?>>50</option>
						<option <?php if ($miles == '100') echo 'selected="selected"'; ?>>100</option>
						<option <?php if ($miles == '200') echo 'selected="selected"'; ?>>200</option>
						<option <?php if ($miles == '500') echo 'selected="selected"'; ?>>500</option>
						<option <?php if ($miles == '1000') echo 'selected="selected"'; ?>>1000</option>
						<option <?php if ($miles == '1500') echo 'selected="selected"'; ?>>1500</option>
						<option <?php if ($miles == '5000') echo 'selected="selected"'; ?>>5000</option>
					</select></label></p>
		</div>

		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'locationtype' ); ?>">Location Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($locationtype) && empty($location)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'locationtype' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'locationtype' ); ?>">Type 
					<select name="<?php echo $this->get_field_name('locationtype'); ?>" id="<?php echo $this->get_field_id( 'locationtype' ); ?>">
						<option></option>
						<option <?php if ($locationtype == 'City') echo 'selected="selected"'; ?>>City</option>
						<option <?php if ($locationtype == 'State/Province/Territory') echo 'selected="selected"'; ?>>State/Province/Territory</option>
						<option <?php if ($locationtype == 'Continent') echo 'selected="selected"'; ?>>Continent</option>
						<option <?php if ($locationtype == 'Country') echo 'selected="selected"'; ?>>Country</option>
						<option <?php if ($locationtype == 'US Area Code') echo 'selected="selected"'; ?>>US Area Code</option>
					</select></label></p>

			<p><label for="<?php echo $this->get_field_id( 'location' ); ?>">Location <input name="<?php echo $this->get_field_name('location'); ?>" id="<?php echo $this->get_field_name('location'); ?>" type="text" value="<?php echo $location; ?>" class="widefat" /></label><br /><em>A comma separated list of valid locations.</em></p>
		</div>

		<p><input class="checkbox" type="checkbox" <?php checked( $reverse, 'on' ); ?> id="<?php echo $this->get_field_id( 'reverse' ); ?>" name="<?php echo $this->get_field_name( 'reverse' ); ?>" /> <label for="<?php echo $this->get_field_id( 'reverse' ); ?>">Reverse filtering results?</label></p> 

		<p><input class="checkbox" type="checkbox" <?php checked( $marker, 'on' ); ?> id="<?php echo $this->get_field_id( 'marker' ); ?>" name="<?php echo $this->get_field_name( 'marker' ); ?>" /> <label for="<?php echo $this->get_field_id( 'marker' ); ?>">Display Marker?</label></p>	 

 
		<p>You can use <a href="http://geoposty.com/shortcodes">shortcodes</a> in the title.</p>
	<?php
	}
} 


// geoPosty google map sidebar widget
class postyGoogleMapSidebar extends WP_Widget {
	function postyGoogleMapSidebar() {
		$widget_ops = array('classname' => 'posty_map_sidebar', 'description' => 'Adds a map local to your reader with your point of interest search' );
		$this->WP_Widget('posty_google_map', 'GeoPosty: Local Maps', $widget_ops);
	}

	function widget($args, $instance){
		global $posty_plugin_url;

		// $data = get_option('postyMap');
		extract($args, EXTR_SKIP);

		if (!empty($instance['distancefrom']) && $instance['miles'] > 1) {
			if ($instance['reverse'] && (geoDistanceFrom($instance['distancefrom']) < $instance['miles'])) return false;
			elseif (!$instance['reverse'] && geoDistanceFrom($instance['distancefrom']) > $instance['miles']) return false;
		}
		$locationTest = geoLocationContent($instance['locationtype'], $instance['location'], $instance['reverse']);
		if (!$locationTest) return false;

		recordGeoStats('w_googlemap');

		echo $args['before_widget'];
		echo $args['before_title'] . do_shortcode(stripslashes($instance['title'])) . $args['after_title'];
		echo geoGoogleMap($instance['width'], $instance['height'], $instance['search'], $instance['results']);

		echo $args['after_widget'];
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['maptype'] = strip_tags($new_instance['maptype']);
		$instance['results'] = strip_tags($new_instance['results']);
		// $instance['zoom'] = strip_tags($new_instance['zoom']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['search'] = strip_tags($new_instance['search']);
 		$instance['distancefrom'] = strip_tags($new_instance['distancefrom']);
 		$instance['miles'] = strip_tags($new_instance['miles']);
 		$instance['locationtype'] = strip_tags($new_instance['locationtype']);
 		$instance['location'] = strip_tags($new_instance['location']);
		$instance['reverse'] = strip_tags($new_instance['reverse']);

		// preload the distance cache!
		if (!empty($instance['distancefrom'])) geoGetAddressLocation($instance['distancefrom']);

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'width' => '', 'height' => '', 'zoom' => '', 'maptype' => '', 'search' => '' , 'results' => '' ) );
		$maptype = strip_tags($instance['maptype']);
		$width = strip_tags($instance['width']);
		$height = strip_tags($instance['height']);
		// $zoom = strip_tags($instance['zoom']);
		$title = strip_tags($instance['title']);
		$results = strip_tags($instance['results']);
		$search = strip_tags($instance['search']);
		$distancefrom = strip_tags($instance['distancefrom']);
		$miles = strip_tags($instance['miles']);
 		$locationtype = strip_tags($instance['locationtype']);
		$location = strip_tags($instance['location']);
		$reverse = strip_tags($instance['reverse']);

	?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title <input name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" value="<?php echo stripslashes($title); ?>" class="widefat" /></label></p>

		<p><label for="<?php echo $this->get_field_id( 'search' ); ?>">Search <input name="<?php echo $this->get_field_name('search'); ?>" id="<?php echo $this->get_field_id( 'search' ); ?>" type="text" value="<?php echo $search; ?>" class="widefat" /></label></p>

		<p><label for="<?php echo $this->get_field_id( 'results' ); ?>">Results <select name="<?php echo $this->get_field_name('results'); ?>" id="<?php echo $this->get_field_id( 'results' ); ?>"><option <?php if ($results == '1') echo 'selected="selected"'; ?>>1</option><option <?php if ($results == '2') echo 'selected="selected"'; ?>>2</option><option <?php if ($results == '3') echo 'selected="selected"'; ?>>3</option><option <?php if ($results == '4') echo 'selected="selected"'; ?>>4</option><option <?php if ($results == '5') echo 'selected="selected"'; ?>>5</option><option <?php if ($results == '6') echo 'selected="selected"'; ?>>6</option><option <?php if ($results == '7') echo 'selected="selected"'; ?>>7</option><option <?php if ($results == '8') echo 'selected="selected"'; ?>>8</option></select></label></p>

		<p><label>Map Type <select name="<?php echo $this->get_field_name('maptype'); ?>"><option <?php if ($maptype == 'roadmap') echo 'selected="selected"'; ?>>roadmap</option><option <?php if ($maptype == 'satellite') echo 'selected="selected"'; ?>>satellite</option><option <?php if ($maptype == 'terrain') echo 'selected="selected"'; ?>>terrain</option><option <?php if ($maptype == 'hybrid') echo 'selected="selected"'; ?>>hybrid</option></select></label></p>
		<!--<p><label>Zoom (1-21) <input name="<?php echo $this->get_field_name('zoom'); ?>" type="text" value="<?php echo $zoom; ?>" class="widefat" /></label><br />
		<em>zoom level 1=continent, zoom level 21=user's backyard.  we recommend something like 12.</em></p>-->
		<p><label>Width <input name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" class="widefat" /></label></p>
		<p><label>Height <input name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" class="widefat" /></label></p>

		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Radius Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($miles) && empty($distancefrom)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'distancefrom' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Distance From <input name="<?php echo $this->get_field_name('distancefrom'); ?>" type="text" value="<?php echo $distancefrom; ?>" class="widefat" /></label></p>
			<p><label for="<?php echo $this->get_field_id( 'miles' ); ?>">Miles 
					<select name="<?php echo $this->get_field_name('miles'); ?>">
						<option></option>
						<option <?php if ($miles == '50') echo 'selected="selected"'; ?>>50</option>
						<option <?php if ($miles == '100') echo 'selected="selected"'; ?>>100</option>
						<option <?php if ($miles == '200') echo 'selected="selected"'; ?>>200</option>
						<option <?php if ($miles == '500') echo 'selected="selected"'; ?>>500</option>
						<option <?php if ($miles == '1000') echo 'selected="selected"'; ?>>1000</option>
						<option <?php if ($miles == '1500') echo 'selected="selected"'; ?>>1500</option>
						<option <?php if ($miles == '5000') echo 'selected="selected"'; ?>>5000</option>
					</select></label></p>
		</div>

		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'locationtype' ); ?>">Location Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($locationtype) && empty($location)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'locationtype' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'locationtype' ); ?>">Type 
					<select name="<?php echo $this->get_field_name('locationtype'); ?>" id="<?php echo $this->get_field_id( 'locationtype' ); ?>">
						<option></option>
						<option <?php if ($locationtype == 'City') echo 'selected="selected"'; ?>>City</option>
						<option <?php if ($locationtype == 'State/Province/Territory') echo 'selected="selected"'; ?>>State/Province/Territory</option>
						<option <?php if ($locationtype == 'Continent') echo 'selected="selected"'; ?>>Continent</option>
						<option <?php if ($locationtype == 'Country') echo 'selected="selected"'; ?>>Country</option>
						<option <?php if ($locationtype == 'US Area Code') echo 'selected="selected"'; ?>>US Area Code</option>
					</select></label></p>

			<p><label for="<?php echo $this->get_field_id( 'location' ); ?>">Location <input name="<?php echo $this->get_field_name('location'); ?>" id="<?php echo $this->get_field_name('location'); ?>" type="text" value="<?php echo $location; ?>" class="widefat" /></label><br /><em>A comma separated list of valid locations.</em></p>
		</div>

		<p><input class="checkbox" type="checkbox" <?php checked( $reverse, 'on' ); ?> id="<?php echo $this->get_field_id( 'reverse' ); ?>" name="<?php echo $this->get_field_name( 'reverse' ); ?>" /> <label for="<?php echo $this->get_field_id( 'reverse' ); ?>">Reverse filtering results?</label></p>	

		<p>You can use <a href="http://geoposty.com/shortcodes">shortcodes</a> in the title.</p>  
	<?php
	}
}


// geoPosty text replacement sidebar widget
class postyTextSidebar extends WP_Widget {
	function postyTextSidebar() {
		$widget_ops = array('classname' => 'posty_text_sidebar', 'description' => 'Uses shortcodes to drop local information into your custom text' );
		$this->WP_Widget('posty_text', 'GeoPosty: Local Content', $widget_ops);
	}

	function widget($args, $instance){
		global $posty_plugin_url;
		$geoPosty = getGeoPosty();

		if (!empty($instance['distancefrom']) && $instance['miles'] > 1) {
			if ($instance['reverse'] && (geoDistanceFrom($instance['distancefrom']) < $instance['miles'])) return false;
			elseif (!$instance['reverse'] && geoDistanceFrom($instance['distancefrom']) > $instance['miles']) return false;
		}
		$locationTest = geoLocationContent($instance['locationtype'], $instance['location'], $instance['reverse']);
		if (!$locationTest) return false;


		recordGeoStats('w_text');

		extract($args, EXTR_SKIP);

		$text = html_entity_decode(stripslashes($instance['text']));

		echo $args['before_widget'];
		echo $args['before_title'] . do_shortcode(stripslashes($instance['title'])) . $args['after_title'];
		echo '<p class="geoText">'. do_shortcode($text) .'</p>';
		echo $args['after_widget'];
	}
 
	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = $new_instance['text'];
 		$instance['distancefrom'] = strip_tags($new_instance['distancefrom']);
 		$instance['miles'] = strip_tags($new_instance['miles']);
  		$instance['locationtype'] = strip_tags($new_instance['locationtype']);
 		$instance['location'] = strip_tags($new_instance['location']);
		$instance['reverse'] = strip_tags($new_instance['reverse']);


		// preload the distance cache!
		if (!empty($instance['distancefrom'])) geoGetAddressLocation($instance['distancefrom']);

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );

		$title = strip_tags($instance['title']);
		$text = $instance['text'];
		$distancefrom = strip_tags($instance['distancefrom']);
		$miles = strip_tags($instance['miles']);
 		$locationtype = strip_tags($instance['locationtype']);
		$location = strip_tags($instance['location']);
		$reverse = strip_tags($instance['reverse']);
	?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title <input name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" value="<?php echo stripslashes($title); ?>" class="widefat" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>">Text <textarea class="widefat geoTextCounter" rows="6" cols="15" name="<?php echo $this->get_field_name('text'); ?>" id="<?php echo $this->get_field_id( 'text' ); ?>"><?php echo stripslashes($text); ?></textarea><br />
		<em>We recommend you use 500 characters or less for your text. So far you've used <span class="geoTextCounter">0</span>.</em></label></p>

		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Radius Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($miles) && empty($distancefrom)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'distancefrom' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Distance From <input name="<?php echo $this->get_field_name('distancefrom'); ?>" type="text" value="<?php echo $distancefrom; ?>" class="widefat" /></label></p>
			<p><label for="<?php echo $this->get_field_id( 'miles' ); ?>">Miles 
					<select name="<?php echo $this->get_field_name('miles'); ?>">
						<option></option>
						<option <?php if ($miles == '50') echo 'selected="selected"'; ?>>50</option>
						<option <?php if ($miles == '100') echo 'selected="selected"'; ?>>100</option>
						<option <?php if ($miles == '200') echo 'selected="selected"'; ?>>200</option>
						<option <?php if ($miles == '500') echo 'selected="selected"'; ?>>500</option>
						<option <?php if ($miles == '1000') echo 'selected="selected"'; ?>>1000</option>
						<option <?php if ($miles == '1500') echo 'selected="selected"'; ?>>1500</option>
						<option <?php if ($miles == '5000') echo 'selected="selected"'; ?>>5000</option>
					</select></label></p>
		</div>

		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'locationtype' ); ?>">Location Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($locationtype) && empty($location)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'locationtype' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'locationtype' ); ?>">Type 
					<select name="<?php echo $this->get_field_name('locationtype'); ?>" id="<?php echo $this->get_field_id( 'locationtype' ); ?>">
						<option></option>
						<option <?php if ($locationtype == 'City') echo 'selected="selected"'; ?>>City</option>
						<option <?php if ($locationtype == 'State/Province/Territory') echo 'selected="selected"'; ?>>State/Province/Territory</option>
						<option <?php if ($locationtype == 'Continent') echo 'selected="selected"'; ?>>Continent</option>
						<option <?php if ($locationtype == 'Country') echo 'selected="selected"'; ?>>Country</option>
						<option <?php if ($locationtype == 'US Area Code') echo 'selected="selected"'; ?>>US Area Code</option>
					</select></label></p>

			<p><label for="<?php echo $this->get_field_id( 'location' ); ?>">Location <input name="<?php echo $this->get_field_name('location'); ?>" id="<?php echo $this->get_field_name('location'); ?>" type="text" value="<?php echo $location; ?>" class="widefat" /></label><br /><em>A comma separated list of valid locations.</em></p>
		</div>

		<p><input class="checkbox" type="checkbox" <?php checked( $reverse, 'on' ); ?> id="<?php echo $this->get_field_id( 'reverse' ); ?>" name="<?php echo $this->get_field_name( 'reverse' ); ?>" /> <label for="<?php echo $this->get_field_id( 'reverse' ); ?>">Reverse filtering results?</label></p>	

		<p>You can use <a href="http://geoposty.com/shortcodes">shortcodes</a> in the text or title.</p>
		  
	<?php
	}
}

// geoPosty weather sidebar widget
class postyWeatherSidebar extends WP_Widget {
	function postyWeatherSidebar() {
		$widget_ops = array('classname' => 'posty_weather_sidebar', 'description' => 'Adds a weather icon and other information local to your reader' );
		$this->WP_Widget('posty_weather', 'GeoPosty: Local Weather', $widget_ops);
	}

	function widget($args, $instance){
		global $posty_plugin_url;

		if (!empty($instance['distancefrom']) && $instance['miles'] > 1) {
			if ($instance['reverse'] && (geoDistanceFrom($instance['distancefrom']) < $instance['miles'])) return false;
			elseif (!$instance['reverse'] && geoDistanceFrom($instance['distancefrom']) > $instance['miles']) return false;
		}
		$locationTest = geoLocationContent($instance['locationtype'], $instance['location'], $instance['reverse']);
		if (!$locationTest) return false;


		recordGeoStats('w_weather');

		// $data = get_option('postyMap');
		extract($args, EXTR_SKIP);

		echo $args['before_widget'];
		echo $args['before_title'] . do_shortcode(stripslashes($instance['title'])) . $args['after_title'];
		echo geoWeather($instance['image'], $instance['measurement'], $instance['humidity'], $instance['wind']);
		echo $args['after_widget'];
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['image'] = strip_tags($new_instance['image']);
		$instance['measurement'] = strip_tags($new_instance['measurement']);
		$instance['humidity'] = strip_tags($new_instance['humidity']);
		$instance['wind'] = strip_tags($new_instance['wind']);
		// $instance['city'] = strip_tags($new_instance['city']);
		$instance['title'] = strip_tags($new_instance['title']);
 		$instance['distancefrom'] = strip_tags($new_instance['distancefrom']);
 		$instance['miles'] = strip_tags($new_instance['miles']); 
 		$instance['locationtype'] = strip_tags($new_instance['locationtype']);
 		$instance['location'] = strip_tags($new_instance['location']);
		$instance['reverse'] = strip_tags($new_instance['reverse']);

		// preload the distance cache!
		if (!empty($instance['distancefrom'])) geoGetAddressLocation($instance['distancefrom']);

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'image' => '', 'measurement' => '', 'humidity' => '', 'wind' => '', 'city' => '', 'title' => '' ) );
		$image = strip_tags($instance['image']);
		$measurement = strip_tags($instance['measurement']);
		$humidity = strip_tags($instance['humidity']);
		$wind = strip_tags($instance['wind']);
		// $city = strip_tags($instance['city']);
		$title = strip_tags($instance['title']);
		$distancefrom = strip_tags($instance['distancefrom']);
		$miles = strip_tags($instance['miles']);
 		$locationtype = strip_tags($instance['locationtype']);
		$location = strip_tags($instance['location']);
		$reverse = strip_tags($instance['reverse']);
	?>


		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title <input name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" value="<?php echo stripslashes($title); ?>" class="widefat" /></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $image, 'on' ); ?> id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" /> <label for="<?php echo $this->get_field_id( 'image' ); ?>">Display Image?</label></p>
		<p><input class="checkbox" type="checkbox" <?php checked( $humidity, 'on' ); ?> id="<?php echo $this->get_field_id( 'humidity' ); ?>" name="<?php echo $this->get_field_name( 'humidity' ); ?>" /> <label for="<?php echo $this->get_field_id( 'humidity' ); ?>">Display Humidity?</label></p>
		<p><input class="checkbox" type="checkbox" <?php checked( $wind, 'on' ); ?> id="<?php echo $this->get_field_id( 'wind' ); ?>" name="<?php echo $this->get_field_name( 'wind' ); ?>" /> <label for="<?php echo $this->get_field_id( 'wind' ); ?>">Display Wind?</label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'measurement' ); ?>">Measurement <select name="<?php echo $this->get_field_name('measurement'); ?>" id="<?php echo $this->get_field_id( 'measurement' ); ?>">
						<option <?php if ($measurement == 'Fahrenheit') echo 'selected="selected"'; ?>>Fahrenheit</option>
						<option <?php if ($measurement == 'Celcius') echo 'selected="selected"'; ?>>Celcius</option>
					</select></label></p>

		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Radius Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($miles) && empty($distancefrom)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'distancefrom' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Distance From <input name="<?php echo $this->get_field_name('distancefrom'); ?>" type="text" value="<?php echo $distancefrom; ?>" class="widefat" /></label></p>
			<p><label for="<?php echo $this->get_field_id( 'miles' ); ?>">Miles 
					<select name="<?php echo $this->get_field_name('miles'); ?>">
						<option></option>
						<option <?php if ($miles == '50') echo 'selected="selected"'; ?>>50</option>
						<option <?php if ($miles == '100') echo 'selected="selected"'; ?>>100</option>
						<option <?php if ($miles == '200') echo 'selected="selected"'; ?>>200</option>
						<option <?php if ($miles == '500') echo 'selected="selected"'; ?>>500</option>
						<option <?php if ($miles == '1000') echo 'selected="selected"'; ?>>1000</option>
						<option <?php if ($miles == '1500') echo 'selected="selected"'; ?>>1500</option>
						<option <?php if ($miles == '5000') echo 'selected="selected"'; ?>>5000</option>
					</select></label></p>
		</div>

		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'locationtype' ); ?>">Location Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($locationtype) && empty($location)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'locationtype' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'locationtype' ); ?>">Type 
					<select name="<?php echo $this->get_field_name('locationtype'); ?>" id="<?php echo $this->get_field_id( 'locationtype' ); ?>">
						<option></option>
						<option <?php if ($locationtype == 'City') echo 'selected="selected"'; ?>>City</option>
						<option <?php if ($locationtype == 'State/Province/Territory') echo 'selected="selected"'; ?>>State/Province/Territory</option>
						<option <?php if ($locationtype == 'Continent') echo 'selected="selected"'; ?>>Continent</option>
						<option <?php if ($locationtype == 'Country') echo 'selected="selected"'; ?>>Country</option>
						<option <?php if ($locationtype == 'US Area Code') echo 'selected="selected"'; ?>>US Area Code</option>
					</select></label></p>

			<p><label for="<?php echo $this->get_field_id( 'location' ); ?>">Location <input name="<?php echo $this->get_field_name('location'); ?>" id="<?php echo $this->get_field_name('location'); ?>" type="text" value="<?php echo $location; ?>" class="widefat" /></label><br /><em>A comma separated list of valid locations.</em></p>
		</div>	
		<p><input class="checkbox" type="checkbox" <?php checked( $reverse, 'on' ); ?> id="<?php echo $this->get_field_id( 'reverse' ); ?>" name="<?php echo $this->get_field_name( 'reverse' ); ?>" /> <label for="<?php echo $this->get_field_id( 'reverse' ); ?>">Reverse filtering results?</label></p>	  

	<?php
	}
}

// geoPosty business sidebar widget
class postyBusinessSidebar extends WP_Widget {
	function postyBusinessSidebar() {
		$widget_ops = array('classname' => 'posty_business_sidebar', 'description' => 'Adds a list of businesses near your reader with your search term.' );
		$this->WP_Widget('posty_business', 'GeoPosty: Local Businesses', $widget_ops);
	}

	function widget($args, $instance){
		global $posty_plugin_url;
		$geoPosty = getGeoPosty();

		if (!empty($instance['distancefrom']) && $instance['miles'] > 1) {
			if ($instance['reverse'] && (geoDistanceFrom($instance['distancefrom']) < $instance['miles'])) return false;
			elseif (!$instance['reverse'] && geoDistanceFrom($instance['distancefrom']) > $instance['miles']) return false;
		}
		$locationTest = geoLocationContent($instance['locationtype'], $instance['location'], $instance['reverse']);
		if (!$locationTest) return false;

		recordGeoStats('w_business');

		extract($args, EXTR_SKIP);

		echo $args['before_widget'];
		
		echo $args['before_title'] . do_shortcode(stripslashes($instance['title'])) . $args['after_title'];

		echo '<div id="geoGoogleBusiness"></div>';

		echo '<script type="text/javascript"> googleBusinessSearch('.$geoPosty['Latitude'].', '. $geoPosty['Longitude'] .', \''.$instance['search'].'\', \''.$instance['results'].'\');  </script>'; //boo-urns

		echo $args['after_widget'];
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['search'] = strip_tags($new_instance['search']);
		$instance['results'] = strip_tags($new_instance['results']);
  		$instance['distancefrom'] = strip_tags($new_instance['distancefrom']);
 		$instance['miles'] = strip_tags($new_instance['miles']);
 		$instance['locationtype'] = strip_tags($new_instance['locationtype']);
 		$instance['location'] = strip_tags($new_instance['location']);
		$instance['reverse'] = strip_tags($new_instance['reverse']);

		// preload the distance cache!
		if (!empty($instance['distancefrom'])) geoGetAddressLocation($instance['distancefrom']);

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'search' => '', 'results' => '' ) );
		$title = strip_tags($instance['title']);
		$search = strip_tags($instance['search']);
		$results = strip_tags($instance['results']);
		$distancefrom = strip_tags($instance['distancefrom']);
		$miles = strip_tags($instance['miles']);
 		$locationtype = strip_tags($instance['locationtype']);
		$location = strip_tags($instance['location']);
		$reverse = strip_tags($instance['reverse']);

	?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title <input name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" value="<?php echo stripslashes($title); ?>" class="widefat" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'search' ); ?>">Search Term <input name="<?php echo $this->get_field_name('search'); ?>" type="text" value="<?php echo stripslashes($search); ?>" class="widefat"  id="<?php echo $this->get_field_id( 'search' ); ?>" /></label><br />
		<em>Locate businesses based upon this search string.</em></p>
	
		<p><label for="<?php echo $this->get_field_id( 'results' ); ?>">Results <select name="<?php echo $this->get_field_name('results'); ?>" id="<?php echo $this->get_field_id( 'results' ); ?>"><option <?php if ($results == '1') echo 'selected="selected"'; ?>>1</option><option <?php if ($results == '2') echo 'selected="selected"'; ?>>2</option><option <?php if ($results == '3') echo 'selected="selected"'; ?>>3</option><option <?php if ($results == '4') echo 'selected="selected"'; ?>>4</option><option <?php if ($results == '5') echo 'selected="selected"'; ?>>5</option><option <?php if ($results == '6') echo 'selected="selected"'; ?>>6</option><option <?php if ($results == '7') echo 'selected="selected"'; ?>>7</option><option <?php if ($results == '8') echo 'selected="selected"'; ?>>8</option></select></label></p>
  
		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Radius Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($miles) && empty($distancefrom)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'distancefrom' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'distancefrom' ); ?>">Distance From <input name="<?php echo $this->get_field_name('distancefrom'); ?>" type="text" value="<?php echo $distancefrom; ?>" class="widefat" /></label></p>
			<p><label for="<?php echo $this->get_field_id( 'miles' ); ?>">Miles 
					<select name="<?php echo $this->get_field_name('miles'); ?>">
						<option></option>
						<option <?php if ($miles == '50') echo 'selected="selected"'; ?>>50</option>
						<option <?php if ($miles == '100') echo 'selected="selected"'; ?>>100</option>
						<option <?php if ($miles == '200') echo 'selected="selected"'; ?>>200</option>
						<option <?php if ($miles == '500') echo 'selected="selected"'; ?>>500</option>
						<option <?php if ($miles == '1000') echo 'selected="selected"'; ?>>1000</option>
						<option <?php if ($miles == '1500') echo 'selected="selected"'; ?>>1500</option>
						<option <?php if ($miles == '5000') echo 'selected="selected"'; ?>>5000</option>
					</select></label></p>
		</div>

		<p class="geoswitch"><a href="#" class="<?php echo $this->get_field_id( 'locationtype' ); ?>">Location Based Display</a></p>

		<?php
			unset($geoWidgetHide);
			if (empty($locationtype) && empty($location)) $geoWidgetHide = ' style="display:none;"';
		?>

		<div class="<?php echo $this->get_field_id( 'locationtype' ); ?>"<?php echo $geoWidgetHide; ?>>
			<p><label for="<?php echo $this->get_field_id( 'locationtype' ); ?>">Type 
					<select name="<?php echo $this->get_field_name('locationtype'); ?>" id="<?php echo $this->get_field_id( 'locationtype' ); ?>">
						<option></option>
						<option <?php if ($locationtype == 'City') echo 'selected="selected"'; ?>>City</option>
						<option <?php if ($locationtype == 'State/Province/Territory') echo 'selected="selected"'; ?>>State/Province/Territory</option>
						<option <?php if ($locationtype == 'Continent') echo 'selected="selected"'; ?>>Continent</option>
						<option <?php if ($locationtype == 'Country') echo 'selected="selected"'; ?>>Country</option>
						<option <?php if ($locationtype == 'US Area Code') echo 'selected="selected"'; ?>>US Area Code</option>
					</select></label></p>

			<p><label for="<?php echo $this->get_field_id( 'location' ); ?>">Location <input name="<?php echo $this->get_field_name('location'); ?>" id="<?php echo $this->get_field_name('location'); ?>" type="text" value="<?php echo $location; ?>" class="widefat" /></label><br /><em>A comma separated list of valid locations.</em></p>
		</div>

		<p><input class="checkbox" type="checkbox" <?php checked( $reverse, 'on' ); ?> id="<?php echo $this->get_field_id( 'reverse' ); ?>" name="<?php echo $this->get_field_name( 'reverse' ); ?>" /> <label for="<?php echo $this->get_field_id( 'reverse' ); ?>">Reverse filtering results?</label></p>	
	<?php
	}
}

function register_geo_widgets(){
	register_widget('postyMapSidebar');
	register_widget('postyTextSidebar');
	register_widget('postyWeatherSidebar');
	register_widget('postyBusinessSidebar');
	register_widget('postyGoogleMapSidebar');
}
add_action('init', 'register_geo_widgets', 1);

?>
