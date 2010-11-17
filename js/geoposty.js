jQuery(document).ready(function($) {
	// $() will work as an alias for jQuery() inside of this function

	$('#geoGoogleBusiness span').hide();

});
	
	google.load("search", "1");
	google.load("maps", "3",  {other_params:"sensor=false"});

function loadUserGoogleMap() {
	// this is a random location
	var latlng = new google.maps.LatLng('37.439974', '-116.367187');
	var myOptions = {
		zoom: 5,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
		
	map = new google.maps.Map(document.getElementById("geoPostyUserMap"), myOptions);
	var LatLngList = new Array();

	jQuery.get('/index.php?feed=geouserxml',{},function(xml){

		jQuery(xml).find('user').each(function() {
	
			var description = jQuery(this).attr('isp') + '<br />' + jQuery(this).attr('city') + ', ' + jQuery(this).attr('state');

			var latlng = new google.maps.LatLng(parseFloat(jQuery(this).attr('lat')), parseFloat(jQuery(this).attr('lon')));

			var marker = new google.maps.Marker({
				position: latlng,
				map: map,
				title: jQuery(this).attr('isp')
			});

			// remember our locations
			LatLngList.push(latlng);

			attachEvent(marker, description);	

		});

		//  Create a new viewpoint bound
		var bounds = new google.maps.LatLngBounds();

		//  Go through each...
		for (var i = 0, LtLgLen = LatLngList.length; i < LtLgLen; i++) {
			//  And increase the bounds to take this point
			bounds.extend (LatLngList[i]);
		}

		//  Zoom the map
		map.fitBounds (bounds);
	});
}

/*
function loadGoogleMap(latitude, longitude, zoom) {
	var latlng = new google.maps.LatLng(latitude, longitude);
	var myOptions = {
		zoom: zoom,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
		
	map = new google.maps.Map(document.getElementById("geoPostyAreaMap"), myOptions);
}*/

function loadZoomUserGoogleMap() {
	// center of US?
	var latlng = new google.maps.LatLng('38', '-97');
	var myOptions = {
		zoom: 5,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
		
	map = new google.maps.Map(document.getElementById("geoPostyZoomMap"), myOptions);
	var timer = 0;
	timeOuts = new Array();

	jQuery.get('/index.php?feed=geouserxml',{},function(xml){
		jQuery(xml).find('user').each(function() {

			// passing an object into setTimeout breaks
			// lets make some variables and drop them in.
			var description =  jQuery(this).attr('lat') + ', ' + jQuery(this).attr('lon') + '<br />' + jQuery(this).attr('city') + ', ' + jQuery(this).attr('state') + ' ' + jQuery(this).attr('postalcode') + '<br />' + jQuery(this).attr('country') + '<br /><br />' +  jQuery(this).attr('isp') + '<br />' + jQuery(this).attr('ip');
			var lat = jQuery(this).attr('lat');
			var lon = jQuery(this).attr('lon');

			if (lon != '') {
				timeOuts[timer] = setTimeout("doZoomPoint('"+ description +"', '"+ lat +"', '"+ lon +"')", timer);
				timer = timer + 3000;
			}
		});
	});
}

function clearTimeouts() {
	if (typeof(timeOuts) != 'undefined') {
		for( key in timeOuts ){  
			clearTimeout(timeOuts[key]);  
		}  
	}
}  

function doZoomPoint(description, lat, lon) {
	
	var latlng = new google.maps.LatLng(parseFloat(lat), parseFloat(lon));

	map.panTo(latlng);

	var marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: lat + ',' + lon
	});

	attachEvent(marker, description);

}


function loadGoogleMap(latitude, longitude, searchTerm, numberResults) {
	if (jQuery('#geoPostyGoogleMap').length) {

		searchResultCount = numberResults;
//		map = new google.maps.Map2(document.getElementById("geoPostyGoogleMap"));
//		map = new google.maps.Map(document.getElementById("geoPostyGoogleMap"));
//		map.setCenter(new google.maps.LatLng(latitude, longitude), zoom);
//		googleMapSearch(latitude, longitude, searchTerm);

		var latlng = new google.maps.LatLng(latitude, longitude);
		var myOptions = {
			zoom: 11,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		
		map = new google.maps.Map(document.getElementById("geoPostyGoogleMap"), myOptions);

		googleMapSearch(latitude, longitude, searchTerm);
	}
}

function googleMapSearch(latitude, longitude, searchTerm) {

	// Create a search control
	var searchControl = new google.search.SearchControl();

	// Set the Search Control to get the most number of results
	searchControl.setResultSetSize(google.search.Search.LARGE_RESULTSET);

	var searchLocation = latitude + ',' + longitude;

	// Add in local search
	var localSearch = new google.search.LocalSearch();
	var options = new google.search.SearcherOptions();
	options.setExpandMode(GSearchControl.EXPAND_MODE_OPEN);
	searchControl.addSearcher(localSearch, options);

	// Set the Local Search center point
	localSearch.setCenterPoint(searchLocation);

//	why was this called twice? lame.
//	localSearch.setCenterPoint(searchLocation);

	// Tell the searcher to draw itself and tell it where to attach
	searchControl.draw(document.getElementById("geoNullRoute"));

	// Declare function for using results
	searchControl.setSearchCompleteCallback(this, mapResults);

	// Execute an inital search
	searchControl.execute(searchTerm);
}

function mapResults(sc, searcher)
{
	var resultcontent = '';
	var LatLngList = new Array();

	for (i=0; i<searchResultCount; i++)
	{
		var result = searcher.results[i];

		if (result) {

			var description = result.title;
			if (result.addressLines[0]) description += '<br />' + result.addressLines[0];
			if (result.addressLines[1]) description += '<br />' + result.addressLines[1];

			var latlng = new google.maps.LatLng(parseFloat(result.lat), parseFloat(result.lng));

			var marker = new google.maps.Marker({
				position: latlng,
				map: map,
				title: result.titleNoFormatting
			});

			// remember our locations
			LatLngList.push(latlng);
			attachEvent(marker, description);
		}
	}

	//  Create a new viewpoint bound
	var bounds = new google.maps.LatLngBounds();

	//  Go through each...
	for (var i = 0, LtLgLen = LatLngList.length; i < LtLgLen; i++) {
		//  And increase the bounds to take this point
		bounds.extend (LatLngList[i]);
	}

	//  Zoom the map
	map.fitBounds (bounds);

}

// create click event
function attachEvent(marker, description) {
	if (typeof(marker) != 'object') return false;	// 11/11/2010 fix for IE bug (McInvale)
	var infowindow = new google.maps.InfoWindow({
		content: description
	});

	google.maps.event.addListener(marker, 'click', function() {
		clearTimeouts();
		infowindow.open(map,marker);
	});
}

function zoomMap() {

}

function googleBusinessSearch(latitude, longitude, searchTerm, numberResults) {

	geoBusinessNumberResults = numberResults;

	// Create a search control
	var searchControl = new google.search.SearchControl();

	// Set the Search Control to get the most number of results
	searchControl.setResultSetSize(google.search.Search.LARGE_RESULTSET);

	var searchLocation = latitude + ',' + longitude;

	// Add in local search
	var localSearch = new google.search.LocalSearch();
	var options = new google.search.SearcherOptions();
	options.setExpandMode(GSearchControl.EXPAND_MODE_OPEN);
	searchControl.addSearcher(localSearch, options);

	// Set the Local Search center point
	localSearch.setCenterPoint(searchLocation);

	localSearch.setCenterPoint(searchLocation);

	// Tell the searcher to draw itself and tell it where to attach
	searchControl.draw(document.getElementById("geoNullRoute"));

	// Declare function for using results
	searchControl.setSearchCompleteCallback(this, displayResults);

	// Execute an inital search
	searchControl.execute(searchTerm);
}

function displayResults(sc, searcher)
{
	var resultcontent = '';
	// var resultdiv = document.getElementById('geoGoogleBusiness');

	for (i=0; i<geoBusinessNumberResults; i++)
	{
		if (searcher.results[i]) {
			var result = searcher.results[i];
			resultcontent += '<p><a href="'+ result.url +'">'+ result.title + '</a><br />' + result.addressLines[0] + '<br />' + result.addressLines[1] + '</p>';
		}
	}

	jQuery('#geoGoogleBusiness').html(resultcontent);
}
