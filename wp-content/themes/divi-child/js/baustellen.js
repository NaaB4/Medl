/*
*  add_marker
*
*  This function will add a marker to the selected Google Map
*
*  @type	function
*  @date	8/11/2013
*  @since	4.3.0
*
*  @param	$marker (jQuery element)
*  @param	map (Google Map object)
*  @return	n/a
*/
function scrollToMap() {
  jQuery('html, body').animate({ scrollTop: (jQuery('#baustellen-karte').offset().top - 200) }, 'slow');
};

let openInfoWindows = [];

function openInfoWindow(map, marker, infowindow) {
  jQuery(openInfoWindows).each(function (i, openInfoWindow) {
    openInfoWindow.close();
  });
  infowindow.open(map, marker);
  openInfoWindows.push(infowindow);
}

function add_marker($marker, map) {

  // var
  var latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng'));
  // create marker
  var marker = new google.maps.Marker({
    position: latlng,
    map: map,
    title: $marker.attr('data-adress'),
    icon: { url: '/wp-content/uploads/baustellenIcon.png', size: new google.maps.Size(46, 43), anchor: new google.maps.Point(16, 43) },
    shape: { coord: [1, 1, 46, 43], type: 'rect' },
    anchorPoint: new google.maps.Point(0, -45),
    opacity: 1
  });

  // if marker contains HTML, add it to an infoWindow
  if ($marker.html()) {
    // create info window
    var infowindow = new google.maps.InfoWindow({
      content: $marker.html()
    });

    // show info window when marker is clicked
    google.maps.event.addListener(marker, 'click', function () {
      openInfoWindow(map, marker, infowindow)
    });
  }

  var button = $marker.find('.pinButton');

  button.on('click', function () {
    openInfoWindow(map, marker, infowindow)
  });

}

jQuery(document).on('ready', function () {

  var waitForEl = function (selector, callback) {
    if (jQuery(selector).length) {
      callback();
    } else {
      setTimeout(function () {
        waitForEl(selector, callback);
      }, 100);
    }
  };
  // do something only the first time the map is loaded
  waitForEl("#baustellen-karte iframe", function () {
    google.maps.event.addListenerOnce(jQuery('.et_pb_map_container').data('map'), 'idle', function () {
      google.maps.event.addListener(jQuery('.et_pb_map_container').data('map'), 'click', function () {
        jQuery(openInfoWindows).each(function (i, openInfoWindow) {
          openInfoWindow.close();
        });
      })
      jQuery('.standort').each(function (idx, standort) {
        add_marker(jQuery(standort), jQuery('.et_pb_map_container').data('map'));
      })

    });
  });
});

jQuery(document).on('click', '.pinButton', function () {
  scrollToMap();
})
