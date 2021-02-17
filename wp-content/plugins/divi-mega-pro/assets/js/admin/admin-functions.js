function dmp_getAllValuesDiviMegaProsCustomClose() {
	
	var options = {};
	options['color'] = jQuery('.dmp_closebtn-text-color').val();
	options['backgroundColor'] = jQuery('.dmp_closebtn-bg-color').val();
	options['fontsize'] = parseFloat( jQuery('.dmp_closebtn_fontsize').val() );
	options['borderRadius'] = parseFloat( jQuery('.dmp_closebtn_borderradius').val() );
	options['padding'] = parseFloat( jQuery('.dmp_closebtn_padding').val() );
	
	return options;
};

function dmp_getAllValuesDiviMegaProsArrowFeature() {
	
	var options = {};
	options['color'] = jQuery('.dmp_arrowfeature-color').val();
	options['width'] = parseFloat( jQuery('.dmp_arrowfeature-width').val() );
	options['height'] = parseFloat( jQuery('.dmp_arrowfeature-height').val() );
	
	return options;
};


function updateElemScale(element, scaleX, scaleY, scaleType) {
	
	scaleX = scaleX * 0.1;
	scaleY = scaleY * 0.1;
	
	element.css({
		'-webkit-transform' : 'scale(' + scaleX + ', ' + scaleY + ')',
		'-moz-transform'    : 'scale(' + scaleX + ', ' + scaleY + ')',
		'-ms-transform'     : 'scale(' + scaleX + ', ' + scaleY + ')',
		'-o-transform'      : 'scale(' + scaleX + ', ' + scaleY + ')',
		'transform'         : 'scale(' + scaleX + ', ' + ')'
	});
}


function updateElemColor(element, color) {
	
	element.css({
		'border-bottom-color' : color,
		'fill' : color
	});
	
	jQuery('.dmp_arrowfeature-preview').css( 'background-color', color );
}


function dmpformatPostResults ( post ) {
	
	var post_title = dmpFormatPostTitle( post );
	
	if ( post.loading ) {
		return post.text;
	}
	
    if ( typeof post_title === 'undefined' ) {
		post_title = 'Post without Title';
    }

	var markup = "<div class='select2-result-post clearfix'>" +
	"<div class='select2-result-post__meta'>" +
	  "<div class='select2-result-post__title'>" + post.id + " : " + post_title + "</div>";

	markup += "</div></div>";

	return markup;
}

function dmpFormatPostTitle (post) {
	return post.post_title || post.text;
}


jQuery(document).ready(function( $ ) {
    
	DiviMegaProsCustomClose = new DiviMegaProsCustomClose( dmp_getAllValuesDiviMegaProsCustomClose() );
	DiviMegaProsCustomClose.update();
	
	DiviMegaProsArrowFeature = new DiviMegaProsArrowFeature( dmp_getAllValuesDiviMegaProsArrowFeature() );
	DiviMegaProsArrowFeature.update();
	
	/* Arrow Feature */
	$('.dmp_arrowfeature-color').wpColorPicker({
		clear: function() {
			
			DiviMegaProsArrowFeature.color = '';
			DiviMegaProsArrowFeature.update();
		},
		change: function(event, ui) {
			
			var hexcolor = jQuery( this ).wpColorPicker( 'color' );
			
			DiviMegaProsArrowFeature.color = hexcolor;
			DiviMegaProsArrowFeature.update();
		}
	});
	
	var _arrowfeature_width = $('#dmp_slider-arrowfeature-width');
	_arrowfeature_width.slider({
		value: 10,
		min: 10,
		max: 100,
		step: 1,
		slide: function(event, ui) {
			var val = dmp_getFromField(ui.value, 10, 100);
			DiviMegaProsArrowFeature.width = val;
			DiviMegaProsArrowFeature.update();
			
			$('.dmp_arrowfeature-width').val(val);
		}
	});
	
	var default_val = $('.dmp_arrowfeature-width').val();
	_arrowfeature_width.slider('value', default_val);
	
	var _arrowfeature_height = $('#dmp_slider-arrowfeature-height');
	_arrowfeature_height.slider({
		value: 10,
		min: 10,
		max: 100,
		step: 1,
		slide: function(event, ui) {
			var val = dmp_getFromField(ui.value, 10, 100);
			DiviMegaProsArrowFeature.height = val;
			DiviMegaProsArrowFeature.update();
			
			$('.dmp_arrowfeature-height').val(val);
		}
	});
	
	default_val = $('.dmp_arrowfeature-height').val();
	_arrowfeature_height.slider('value', default_val);
	
	
	
	/* Close Button Customization*/
	$('.dmp_closebtn-text-color').wpColorPicker({
		clear: function() {

			DiviMegaProsCustomClose.color = '';
			DiviMegaProsCustomClose.update();
		},
		change: function(event, ui) {
			
			var hexcolor = jQuery( this ).wpColorPicker( 'color' );
			
			DiviMegaProsCustomClose.color = hexcolor;
			DiviMegaProsCustomClose.update();
		}
	});
	
	
	$('.dmp_closebtn-bg-color').wpColorPicker({
		clear: function() {

			DiviMegaProsCustomClose.backgroundColor = '';
			DiviMegaProsCustomClose.update();
		},
		change: function(event, ui) {
			
			var hexcolor = jQuery( this ).wpColorPicker( 'color' );
			
			DiviMegaProsCustomClose.backgroundColor = hexcolor;
			DiviMegaProsCustomClose.update();
		}
	});
	
	
	/* Close Button cookie */
	$('#dmp_slider-closebtn-cookie').slider({
		value: 0,
		min: 0,
		max: 99,
		step: 1,
		slide: function(event, ui) {
			var val = dmp_getFromField(ui.value, 0, 99);
			
			$('.dmp_closebtn_cookie').val(val);
		}
	});
	
	
	/* Close Button font size */
	var _closebtn_fontsize = $('#dmp_slider-closebtn-fontsize');
	_closebtn_fontsize.slider({
		value: 25,
		min: 25,
		max: 250,
		step: 1,
		slide: function(event, ui) {
			var val = dmp_getFromField(ui.value, 25, 250);
			DiviMegaProsCustomClose.fontsize = val;
			DiviMegaProsCustomClose.update();
			
			$('.dmp_closebtn_fontsize').val(val);
		}
	});
	
	default_val = $('.dmp_closebtn_fontsize').val();
	_closebtn_fontsize.slider('value', default_val);
	
	
	/* Close Button border radius */
	var _closebtn_borderradius = $('#dmp_slider-closebtn-borderradius');
	_closebtn_borderradius.slider({
		value: 0,
		min: 0,
		max: 50,
		step: 1,
		slide: function(event, ui) {
			var val = dmp_getFromField(ui.value, 0, 50);
			DiviMegaProsCustomClose.borderRadius = val;
			DiviMegaProsCustomClose.update();
			
			$('.dmp_closebtn_borderradius').val(val);
		}
	});
	
	default_val = $('.dmp_closebtn_borderradius').val();
	_closebtn_borderradius.slider('value', default_val);
	
	
	/* Close Button padding */
	var _closebtn_padding = $('#dmp_slider-closebtn-padding');
	_closebtn_padding.slider({
		value: 0,
		min: 0,
		max: 99,
		step: 1,
		slide: function(event, ui) {
			var val = dmp_getFromField(ui.value, 0, 99);
			DiviMegaProsCustomClose.padding = val;
			DiviMegaProsCustomClose.update();
			
			$('.dmp_closebtn_padding').val(val);
		}
	});
	
	default_val = $('.dmp_closebtn_padding').val();
	_closebtn_padding.slider('value', default_val);
});

jQuery( function ( $ ) {
	
	var dropdownParent = $('#divimegapros_displaylocations_metabox1');
	
	if ( dropdownParent.length ) {
		
		var dmmBlocksHidden = [];
	
		$(".chosen").select2({
			width: '100%',
			theme: "classic",
			minimumResultsForSearch: Infinity
		});
		
		
		$(".do-list-pages").select2({
			dropdownParent: dropdownParent,
			width: '100%',
			theme: "bootstrap",
			ajax: {
				url: ajaxurl,
				dataType: 'json',
				delay: 250,
				method: 'POST',
				data: function (params) {
				  return {
					action: 'ajax_dmp_listposts',
					nonce: divilife_divimegapro,
					q: params.term,
					page: params.page,
					json: 1
				  };
				},
				processResults: function (data, params) {
				  params.page = params.page || 1;
				  
				  return {
					results: data.items,
					pagination: {
					  more: (params.page * 7) < data.total_count
					}
				  };
				},
				cache: true
			},
			minimumInputLength: 1,
			escapeMarkup: function (markup) { return markup; },
			templateResult: dmpformatPostResults,
			templateSelection: dmpFormatPostTitle
		});
		
		
		$('body').on('click','[data-showhideblock]', function(event){
			
			var block_content = $(this).data('showhideblock');
			
			if ( $(this).is(':checked') ) {
			
				$( block_content ).addClass('do-show');
				
			} else {
				
				$( block_content ).removeClass('do-show');
			}
		});
		
		$('body').on('click','#dmp_mpa_disablemobile', function(event){
			
			var closebtn_enablemobile = $('.dmp_enablemobile');
			
			if ( $(this).is(':checked') ) {
			
				$( closebtn_enablemobile ).addClass('do-hide');
				
			} else {
				
				$( closebtn_enablemobile ).removeClass('do-hide');
			}
		});
		
		$('body').on('click','#dmp_mpa_disabledesktop', function(event){
			
			var closebtn_enabledesktop = $('.dmp_enabledesktop');
			
			if ( $(this).is(':checked') ) {
			
				$( closebtn_enabledesktop ).addClass('do-hide');
				
			} else {
				
				$( closebtn_enabledesktop ).removeClass('do-hide');
			}
		});
		
		$('[data-dropdownshowhideblock]').change(function() {
			
			var dropdown_id = $(this).attr('id')
			, showhideblock = $(this).find(':selected').data('showhideblock');
			
			$(this).find('option[data-showhideblock]').each(function() {
				
				var elemRef = $(this).data('showhideblock');
				
				$( elemRef ).removeClass('do-show');
			});
			
			if ( showhideblock !== undefined ) {
				
				$( showhideblock ).addClass('do-show');
			}
		});
	}
});

function DiviMegaProsCustomClose(options) {
    this.htmlElement = jQuery('.divimegapro-customclose-btn');
    this.color = options['color'] || '#333333';
	this.backgroundColor = options['backgroundColor'] || '';
	this.fontsize = options['fontsize'] || 25;
	this.borderRadius = options['borderRadius'] || 0;
    this.padding = options['padding'] || 0;
};

DiviMegaProsCustomClose.prototype.update = function () {
	this.htmlElement.css('color', this.color, 'important');
	this.htmlElement.css('background-color', this.backgroundColor, 'important');
	this.htmlElement.css('-moz-border-radius', this.borderRadius + '%', 'important');
	this.htmlElement.css('-webkit-border-radius', this.borderRadius + '%', 'important');
	this.htmlElement.css('-khtml-border-radius', this.borderRadius + '%', 'important');
	this.htmlElement.css('font-size', this.fontsize + 'px', 'important');
	this.htmlElement.css('border-radius', this.borderRadius + '%', 'important');
	this.htmlElement.css('padding', this.padding + 'px', 'important');
};

function DiviMegaProsArrowFeature(options) {
    this.htmlElement = jQuery('.dmp_arrowfeature-preview .dmp_arrowfeature-preview-round, .dmp_arrowfeature-preview .tippy-arrow');
    this.color = options['color'] || '#ff0000';
	this.width = options['width'] || '';
	this.height = options['height'] || 25;
};

DiviMegaProsArrowFeature.prototype.update = function () {
	updateElemColor( this.htmlElement, this.color );
	updateElemScale( this.htmlElement, this.width, this.height, 'scale');
};

function dmp_getFromField(value, min, max, elem) {
	var val = parseFloat(value);
	if (isNaN(val) || val < min) {
		val = 0;
	} else if (val > max) {
		val = max;
	}

	if (elem)
		elem.val(val);

	return val;
}

