$.each( $('.basement_google_map'), function( index, map ) {
	var map_container = $( map ).find( '.basement_google_map_container' ),
		map_lat_long = new google.maps.LatLng( map_container.data( 'latitude' ), map_container.data( 'longitude' ) ),
		map_options = {
			zoom: map_container.data( 'zoom' ),
			scrollwheel: false,
			navigationControl: false,
			mapTypeControl: false,
			scaleControl: false,
			draggable: true,
			cmyLatlng: map_lat_long,
			center: map_lat_long
		},
		google_map = new google.maps.Map(map_container.get(0), map_options);

	new google.maps.Marker({
		position: map_lat_long,
		map: google_map
	});

	$( map ).find('.block-link').on('click', function(){
		var popup_container = $( $( map ).find('.block-link').data( 'target' ) ),
			map_popup = popup_container.find( '.basement_google_map_popup' );
			map_popup.height($win.height());

			$(window).resize(function() {
				map_popup.height($win.height());
			});

		setTimeout(function() {
			new google.maps.Marker({
				position: map_lat_long,
				map: new google.maps.Map( map_popup.get(0), map_options)
			});
		}, instance.options.speedAnimation/2);
	});
});