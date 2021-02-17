/**
 * Plugin Name: Divi Mega Pro
 * Plugin URL: https://divilife.com/
 * Description: Create mega menus and tooltips from Divi Builder
 * Author: Divi Life — Tim Strifler
 * Author URI: https://divilife.com
*/
( function( $, undefined ) {
  
	function updateAllTippyPosition() {
		
		const alldmps = document.querySelectorAll('[data-divimegaproid]');
		
		if ( typeof alldmps !== 'undefined' ) {
			
			var dmpslength = alldmps.length;
			
			for ( var i = 0; i < dmpslength; i++ ) {
				
				if ( typeof alldmps[i] === 'object' ) {
					
					const instance = alldmps[i]._tippy;
						
					instance.popperInstance.update();
				}
			}
		}
	}
	
	wp.customize( 'et_divi[header_style]', function( value ) {
		value.bind( function( to ) {
			
			updateAllTippyPosition();
			
		} );
	} );
	
	wp.customize( 'et_divi[vertical_nav]', function( value ) {
		value.bind( function( to ) {
			
			updateAllTippyPosition();
			
		} );
	} );
	
	wp.customize( 'et_divi[vertical_nav_orientation]', function( value ) {
		value.bind( function( to ) {
			
			updateAllTippyPosition();
			
		} );
	} );
	
	wp.customize( 'et_divi[hide_nav]', function( value ) {
		value.bind( function( to ) {
			
			updateAllTippyPosition();
			
		} );
	} );
	
} )( jQuery );