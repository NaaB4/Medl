/*remove mega menu on mobile
jQuery(function($){
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ){
      var cl =  jQuery('#main-header .nav li').attr("class").split(" ");
      var newcl =[];
      for(var i=0;i<cl.length;i++){
          r = cl[i].search(/divimegapro+/);
          if(r)newcl[newcl.length] = cl[i];
      }
      jQuery('#main-header .nav li').removeClass().addClass(newcl.join(" "));
  		jQuery('#main-header .nav li').removeAttr('data-divimegaproid');

      jQuery('#top-menu-nav, #top-menu, #et_top_search').addClass('display-none');
      jQuery('#et_mobile_nav_menu').addClass('display-block-menu');
  }
});
*/

jQuery(window).on("load, resize", function() {
    var viewportWidth = jQuery(window).width();
    if (viewportWidth < 1025) {
		var cl =  jQuery('.et_menu_container #mobile_menu li').attr("class").split(" ");
		var newcl =[];
		for(var i=0;i<cl.length;i++){
			r = cl[i].search(/divimegapro+/);
			if(r)newcl[newcl.length] = cl[i];
		}
		jQuery('.et_menu_container #mobile_menu li').removeClass().addClass(newcl.join(" "));
		jQuery('.et_menu_container #mobile_menu li').removeAttr('data-divimegaproid');
    }
});
//MOBILE MENU
//
//

(function($) {

    function setup_collapsible_submenus() {
        var $menu = $('#mobile_menu'),
            top_level_link = '#mobile_menu .menu-item-has-children > a';

        $menu.find('a').each(function() {
            $(this).off('click');


            if ( ! $(this).siblings('.sub-menu').length ) {
                $(this).on('click', function(event) {
                    $(this).parents('.mobile_nav').trigger('click');
                });
            } else {
                $(this).on('click', function(event) {
                    event.preventDefault();
                    $(this).parent().toggleClass('visible');
                });
            }
        });
    }


    $(window).load(function() {
       setTimeout(function() {
            setup_collapsible_submenus();
        }, 100);
    });

})(jQuery);


//Footer austauschen PK / GK
jQuery(window).load(function() {
  if ((jQuery('#main-content > article').hasClass( "category-privatkunden" )) || ( jQuery('body').hasClass( "single-post" )) || ( jQuery('body').hasClass( "single-wpdmpro" )) || ( jQuery('body').hasClass( "search" ))) {
    jQuery('.footer-widget #nav_menu-11').addClass('d-none');
    jQuery('.footer-widget #nav_menu-6').removeClass('d-none');
  }
  if ( jQuery('#main-content > article').hasClass( "category-geschaeftskunden" )) {
    jQuery('.footer-widget #nav_menu-11').removeClass('d-none');
    jQuery('.footer-widget #nav_menu-6').addClass('d-none');
  }
});



jQuery(window).load(function() {
  // Sobald gescrollt wird, wird die funktion "scrollFunction" aufgerufen
  window.onscroll = function() {scrollFunction()};

  // Funktion "scrollFunction"
  function scrollFunction() {
      // Wenn der gescrollte Bereich 37.5px übersteigt (Höhe des Top-Menüs)
    if (window.pageYOffset > 37.5) {
        // Füge jeweilige Klassen zu Objekten hinzu
      jQuery(".stickyKontakt0").addClass("sticky0");
      jQuery(".stickyKontakt1").addClass("sticky1");
      jQuery(".meta.login").addClass("metaScrollPadding");
    } else {
        // Entferne jeweilige Klassen von Objekten
        jQuery(".stickyKontakt0").removeClass("sticky0");
        jQuery(".stickyKontakt1").removeClass("sticky1");
        jQuery(".meta.login").removeClass("metaScrollPadding");
    }
  }
});

/*Slick Slider*/
document.addEventListener('DOMContentLoaded', function(){
  jQuery('.single-item:not(.slick-initialized)').slick({
    dots: true,
    arrows: true,
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 7000,
    responsive: [
      {
        breakpoint: 1400,
        settings: {
          slidesToShow: 2,
          arrows: true
        }
      },
      {
        breakpoint: 980,
        settings: {
          slidesToShow: 2,
          arrows: true
        }
      },
      {
        breakpoint: 780,
        settings: {
          slidesToShow: 1,
          arrows: true
         }
      }
    ]
  });
});

document.addEventListener('DOMContentLoaded', function(){
  jQuery('.blue-slider:not(.slick-initialized)').slick({
    dots: true,
    arrows: true,
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 3,
    autoplay: false,
    autoplaySpeed: 7000,
    responsive: [
      {
        breakpoint: 968,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true
        }
      },
      {
        breakpoint: 680,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true
         }
      }
    ]
  });
});

document.addEventListener('DOMContentLoaded', function(){
  jQuery('.single-item-arrows:not(.slick-initialized)').slick({
    dots: true,
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 3,
    autoplay: false,
    autoplaySpeed: 5000,
    responsive: [
      {
        breakpoint: 1200,
        settings: { slidesToShow: 2,
        slidesToScroll: 2 }
      },
      {
        breakpoint: 680,
        settings: { slidesToShow: 1,
        slidesToScroll: 1 }
      }
    ]
  });
});


/*Baustellen*/
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

//Banner
jQuery(document).on('ready', function ()  {
    jQuery('#startbanner').fadeOut(1);
    jQuery('#startbanner').removeClass('hidden');
    jQuery('#startbanner').fadeIn(1000);

  jQuery( "#close-startbanner" ).click(function() {
  	jQuery('#startbanner').fadeOut(1000);
	});
});

//iframe Karriere
(function($) {
  $(document).on('ready', function () {
	var if_height, src = 'https://recruitingapp-5344.de.umantis.com/Jobs/All?CompanyID=All&Reset=G',
	iframe = $( '<iframe src="' + src + '" name="' + document.location.href + '" width="100%" height="500" frameborder="0" scrolling="yes"><\/iframe>' ).appendTo( '#umantis_iframe' );
  var umw = document.getElementById("umanti-wrapper");
	$.receiveMessage(function(e){
		var h = Number( e.data.replace( /.*if_height=(\d+)(?:&|$)/, '$1' ) );
		if (!isNaN( h ) && h > 0 && h !== if_height) {
			/* Height has changed, update the iframe */
			if_height = h;
			iframe.height(h);
      umw.style.height = h + 5 + 'px';
		}
	} );
});
})( jQuery );
