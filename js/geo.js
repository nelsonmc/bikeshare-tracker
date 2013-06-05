// Display notifications to mobile/iOS users
if( /iPhone|iPad|iPod/i.test(navigator.userAgent) ) {
	$(".geo-notification-device").html("(for iPhone/iPad folks, this can be found in Settings &rsaquo; Privacy &rsaquo; Location Services &rsaquo; Safari)");
	$(".geo-notification-device").css({'display':'block'});

	if(!window.navigator.standalone) {
		$(".standalone-notification-device").html("(psst..did you know you can add this to your home screen as a web app? Just click the Share/Save arrow at the bottom of your screen)");
		$(".standalone-notification-device").css({'display':'block'});
	}
}

// Function success retrieves the closest stations
// Is executed if browser geolocation feature is available
function success(position) {
	var lat = position.coords.latitude;
	var long = position.coords.longitude;
	$(".geo-notification").remove(); // remove mobile/iOS notifications in preparation for data display
	$.ajax({
		type: "GET",
		url: "functions.php",
		data: { 'action': 'getClosestStations', 'lat': lat, 'long': long }, 
		dataType: 'json',
		success: function(data) {
			// for each resulting station in JSON output, add to DOM
			$(".results .result").fadeOut('fast');
			for (var x = 0; x < data.length; x++) {
				content = "<a href='http://maps.apple.com/maps?saddr=" + lat + "," + long + "&daddr=" + data[x].lat + "," + data[x].long + "&dirflg=w' class='clearfix'>";
				content += "<div class='row result clearfix'>";
				content += "<div class='col distance'>" + data[x]["@attributes"].distance + "</div>";
				content += "<div class='smallcol bikes'>" + data[x].nbBikes + "</div>";
				content += "<div class='smallcol docks'>" + data[x].nbEmptyDocks + "</div>";
				content += "<div class='bigcol name'>" + data[x].name + "</div>";
				content += "</div>";
				content += "</a>";
				$(content).hide().appendTo(".results").fadeIn('fast');
			}
			// only display refresh button after 8 seconds
			$("a.refresh").removeClass("thinking").slideUp().delay(8*1000).slideDown();
		}
	});
}

// Function try_refresh attempts to load geolocation before triggering data retrieval
function try_refresh() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(success);
	} else {
		error('Geo Location is not supported');
	}
}

// Attempt data refresh on initial page load
try_refresh();

// And then submit it on all manual refreshes thereafter
$('a.refresh').click( function() {
	$(this).addClass("thinking");
	try_refresh();

});