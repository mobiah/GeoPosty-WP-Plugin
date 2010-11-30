jQuery(document).ready(function($) {
	// $() will work as an alias for jQuery() inside of this function

	$('textarea.geoTextCounter').keypress(function() { 
		$('span.geoTextCounter').html($(this).val().length);
	});

	$('#geoChangeSubscription').click(function() {
		$('#geoChangeiframe').fadeIn();
		return false;
	});

	$('#geoposty-conf').live('submit', function() {
		var geoKey = $('#geoPostyKey').val();
		var geoTest = $('#geoPostyTest').val();
		//console.debug('geoposty-conf geoKey=' + geoKey);
		//console.debug('geoposty-conf geoTest=' + geoTest);

		if (geoTest == 'go') {
			return true;
		}

		$('#geoKeyReply').addClass('updated').html('Testing key <em>'+geoKey+'</em>, please wait...');

		var data = {
			action: 'geo_confirm',
			domainkey: geoKey
		};		

		$.get(ajaxurl, data, function(response) {
			//console.debug('geoposty-conf ajaxurl=' + ajaxurl);
			//console.debug('geoposty-conf response=' + response);
			if (response.length > 2) {
				$('#geoKeyReply').addClass('updated').html(response);
			} else {
				$('#geoKeyReply').html('Your key looks good! Please save it.');

				var data = {
					action: 'geo_followup'
				};

				$.get(ajaxurl, data);
				$('#geoPostyTest').val('go');
				$('#geosubmit').val('Save Key');
			}
		});

		return false;
	});

	$('.geoswitch > a').live('click',function() {
		// what class are we working with?
		var thisClass = $(this).attr('class');
		$('div.'+thisClass).slideToggle();
		return false;
	});

	$('.geoDeleteMe > a').click(function() {
		geoRedirectsWarning = true;

		// what class are we working with?
		var thisClass = $(this).attr('class');

		$('#'+thisClass).fadeOut('normal', function() {
			$(this).remove();
		});

		$('#geoRedirectSave').addClass('updated').html('You must save your redirects to complete the deletion process!');


		return false;
	});

	$('#geoposty-redirects').submit(function() {
		geoRedirectsWarning = false;
	});

	$('#geoposty-redirects input, #geoposty-redirects select').change(function() {
		geoRedirectsWarning = true;
	});

	// this sucks.
	$('#geoDailyLink').click(function() {
		$('#geoDailyGraph').fadeIn();
		$('#geoWeeklyGraph').hide();
		$('#geoMonthlyGraph').hide();
		return false;
	});
	$('#geoWeeklyLink').click(function() {
		$('#geoDailyGraph').hide();
		$('#geoWeeklyGraph').fadeIn();
		$('#geoMonthlyGraph').hide();
		return false;
	});
	$('#geoMonthlyLink').click(function() {
		$('#geoDailyGraph').hide();
		$('#geoWeeklyGraph').hide();
		$('#geoMonthlyGraph').fadeIn();
		return false;
	});
});
