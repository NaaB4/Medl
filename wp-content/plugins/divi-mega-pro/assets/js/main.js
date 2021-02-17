/**
 * Plugin Name: Divi Mega Pro
 * Plugin URL: https://divilife.com/
 * Description: Create mega menus and tooltips from Divi Builder
 * Version: 1.3
 * Author: Divi Life — Tim Strifler
 * Author URI: https://divilife.com
*/

// Intercept ajax calls to replace current post id with Divi Bars post id on Divi Optin submit
!function(send) {
	
	var divimegapro_ajax_intercept = function( body ) {
		
		var isDiviMegaProOpen = document.querySelectorAll( '.divimegapro.dmp-open' )
		, isDiviOverlaysOpen = document.querySelectorAll( '.overlay.open' );
		
		if ( isDiviMegaProOpen.length > 0 && isDiviOverlaysOpen.length < 1 ) {
		
			try {
				
				if ( body !== null ) {
				
					var doCustomFieldName = 'et_pb_signup_divimegaproid'
					, action = 'action=et_pb_submit_subscribe_form'
					, is_optin_submit_subscribe_form = body.indexOf( action )
					, is_divimegapro_ref_form = body.indexOf( doCustomFieldName );
					
					if ( is_optin_submit_subscribe_form !== -1 && is_divimegapro_ref_form !== -1 ) {
						
						var result = [];
						
						body.split('&').forEach(function(part) {
							
							var item = part.split("=")
							, name = decodeURIComponent( item[0] )
							, value = decodeURIComponent( item[1] )
							, doCustomField = 'et_custom_fields[' + doCustomFieldName + ']';
							
							if ( name != doCustomField && name != 'et_post_id' ) {
							
								result.push( part );
							}
							
							if ( name == doCustomField ) {
								
								result.push( 'et_post_id=' + value );
							}
						});
						
						var url = result.join('&');
						
						body = url;
					}
					
					send.call(this, body);
				}
			}
			catch( err ) {
				
				// In case there is an error,
				// do not add anything and send the original payload.
				send.call(this, body);
			}
		}
		else {
			
			send.call(this, body);
		}
	};
	
	XMLHttpRequest.prototype.send = divimegapro_ajax_intercept;
	
}( XMLHttpRequest.prototype.send );

;(function ( $, window, document, undefined ) {
  'use strict';
  
    $.fn.mainDiviMegaPro = function( options ) {
		
		var divimegapro_body
		, divimegapro
		, idx_divimegapro
		, divi_divimegapro_container_selector
		, $divimegapro = this
		, contentLengthcache
		, divimegaproHeightCache
		, diviMobile
		, themesBreakpoint = { Divi:980, Extra:1024 }
		, styleTagID = 'divi-mega-pro-styles'
		, vw
		, fixedElements
		, scrollCheck
		, diviElement_togglePro;
		
		const dmps = [];
		
		const diviPageContainer = document.getElementById('page-container')
		, diviTopHeader = document.getElementById('top-header')
		, diviMainHeader = document.getElementById('main-header')
		, diviMainFooter = document.getElementById('main-footer')
		, documentHTML = $('html')
		, documentBody = document.body;
		
		if (typeof options == 'function') {
			options = { success: options };
		}
		else if ( options === undefined ) {
			options = {};
		}
		
		// Trying to prevent others plugins override Divi Mega Pro styles
		$( '<style id="' + styleTagID + '"></style>' ).appendTo( 'head' );
		
		if ( $('div.divimegapro-container').length ) {
			
			// Viewing from a non-large device?
			diviMobile = isDiviMobile();
			
			if ( diviMobile ) {
				
				diviElement_togglePro = $('.et_mobile_nav_menu > .mobile_nav');
				
				if ( diviElement_togglePro.length ) {
					
					diviElement_togglePro.on( 'click touchstart', function(e) {
						
						if ( $('.tippy-popper').length ) {
							
							var allPoppers = document.querySelectorAll('.tippy-popper');
							
							$.each( allPoppers, function ( index, popper ) {
								
								const instance = popper._tippy;
								
								if ( instance.state.isVisible ) {
									
									instance.hide();
								}
							});
						}
					});
				}
			}
			
			var divimegapro_container = $( 'div.divimegapro-wrapper' )
			, container = $( 'div#page-container' )
			, removeMonarchTimer = 0;
			
			// Remove any duplicated divimegapro
			$( divimegapro_container ).each(function () {
				$('[id="' + this.id + '"]:gt(0)').remove();
			});
			
			
			$( 'body [rel^="divimegapro"]' ).each(function() {
				
				var divimegaproArr = $(this).attr('rel').split('-')
				, divimegapro_id = parseInt( divimegaproArr[1] )
				, selector = this;
				
				if ( divimegapro_id ) {
				
					selector.setAttribute( 'data-divimegaproid', divimegapro_id ); 
					
					dmps[ divimegapro_id ] = '';
					createDiviMegaPro( divimegapro_id, selector );
				}
			});
			
			
			$( 'body [class*="divimegapro"]' ).each(function() {
			
				var divimegaproArr = $(this).attr('class')
				, divimegapro_match = divimegaproArr.match(/divimegapro-(\d+)/)
				, selector = this
				, divimegapro_id = null;
				
				if ( null != divimegapro_match ) {
					
					divimegapro_id = divimegapro_match[1];
					
					if ( divimegapro_id ) {
						
						selector.setAttribute( 'data-divimegaproid', divimegapro_id ); 
						
						dmps[ divimegapro_id ] = '';
						createDiviMegaPro( divimegapro_id, selector );
					}
				}
			});
			
			
			$('.nav a, .mobile_nav a').each(function( index,value ) {
				
				var href = $( value ).attr('href');
				
				if ( href !== undefined ) {
				
					idx_divimegapro = href.indexOf('divimegapro');
					
					if ( idx_divimegapro !== -1 ) {
						
						var idx_divimegaproArr = href.split('-');
						
						if ( idx_divimegaproArr.length > 1 ) {
							
							var divimegapro_id = parseInt( idx_divimegaproArr[1] )
							, selector = this;
							
							if ( divimegapro_id ) {
								
								selector.setAttribute( 'data-divimegaproid', divimegapro_id ); 
								
								if ( !( divimegapro_id in dmps ) ) {
									
									dmps[ divimegapro_id ] = '';
									createDiviMegaPro( divimegapro_id, selector );
								}
							}
						}
					}
				}
			});
			
			if ( typeof divimegapros_with_css_trigger !== 'undefined' ) {
				
				var dmpTriggerType = ''
				, dmp_container_selector
				, dmp_container
				, dmp_options;
				
				if ( $( divimegapros_with_css_trigger ).length > 0 ) {
					
					$.each( divimegapros_with_css_trigger, function( divimegapro_id, selector ) {
						
						dmps[ divimegapro_id ] = '';
						
						$( selector ).each( function( e ) {
							
							this.setAttribute( 'data-divimegaproid', divimegapro_id );
							
							createDiviMegaPro( divimegapro_id, this );
						});
					});
				}
			}
			
			$('a').each(function() {
				
				var href = $(this).attr('href');
				
				if ( href !== undefined ) {
				
					var hash = href[0]
					, ref = href.indexOf('divimegapro');
					
					if ( hash == '#' && href.length > 1 && ref != -1 ) {
						
						var divimegapro_id = parseInt( href.replace('#divimegapro-', '') );
						
						if ( typeof divimegapro_id == 'number' ) {
							
							$(this).attr('data-divimegaproid', divimegapro_id);
							
							createDiviMegaPro( divimegapro_id, this );
						}
					}
				}
			});
			
			
			$('body').on( 'click touchstart', '.divimegapro-close', function(e) {
				
				e.preventDefault();
				
				var dmpid = $( this ).parents('.tippy-popper').data('dmpid');
				
				var alldmps = document.querySelectorAll( '.dmp-' + dmpid );
				
				$.each( alldmps, function ( index, popper ) {
					
					const instance = popper._tippy;
					
					if ( instance.state.isVisible ) {
						
						instance.hide();
					}
				});
			});
			
			
			function createDiviMegaPro( divimegapro_id, selector, dmp_parent_selector ) {
				
				var divimegapro_selector = '#divimegapro-' + divimegapro_id
				, divimegapro = $( divimegapro_selector )
				, divimegapro_container_selector = '#divimegapro-container-' + divimegapro_id
				, divimegapro_container = $( divimegapro_container_selector )
				, tippyDmpSelector = '.dmp-' + divimegapro_id;
				
				if ( typeof dmp_parent_selector === 'undefined' ) {
					
					var dmp_parent_selector = '';
				}
				
				if ( typeof divimegapro_container.data() == 'undefined' ) {
					
					return;
				}
				
				var options = getOptions( divimegapro_container )
				, triggerType = options['triggertype']
				, exitType = options['exittype']
				, trigger = triggerType
				, hideOnClick = true
				, flip = false
				, flipBehavior = ["top", "bottom", "right", "left"]
				, flipOnUpdate = true
				, interactiveDebounce = 0
				, maxWidth = ''
				, popperOptions = {}
				, megaprofixedheight = 0;
				
				const refElement = $( selector );
				
				if ( options['bgcolor'] != '' ) {
					
					$( divimegapro_selector + ' .divimegapro-pre-body' ).css( { 'background-color': options['bgcolor'] } );
				}
				
				
				if ( options['fontcolor'] != '' ) {
					
					$( divimegapro_selector + ' .divimegapro-pre-body *' ).css( 'color', options['fontcolor'] );
				}
				
				
				if ( !diviMobile ) {
					
					if ( exitType == 'hover' ) {
						
						if ( trigger != 'mouseenter focus' && exitType == 'hover' ) {
							
							hideOnClick = false;
						}
					}
				}
				
				
				// Fix to prevent "hide DMP on mouseout" when exitType is set to click
				if ( exitType == 'click' ) {
					
					interactiveDebounce = 900000;
				}
				
				removeMonarch();
				
				setTimeout( function() {
					
					// Default props
					var props = {}
					, appendTo = props.appendTo = diviPageContainer;
					props.parentDiviTopHeader = refElement.closest('#top-header');
					props.parentDiviMainHeader = refElement.closest('#main-header');
					props.parentDiviModuleMenu = refElement.closest('.et_pb_menu, .et_pb_fullwidth_menu');
					props.parentSlideMenuContainer = refElement.closest('.et_slide_in_menu_container');
					props.parentDiviPageContainer = refElement.closest('#page-container');
					props.parentDiviETMainArea = refElement.closest('#et-main-area');
					props.parentDiviMainContent = refElement.closest('#main-content');
					props.parentDiviMainFooter = refElement.closest('#main-footer');
					props.placement = options['placement'];
					
					if ( options['arrowEnabled'] === true && options['arrowType'] === 'round' ) {
						
						options['arrowEnabled'] = tippy.roundArrow;
					}
					
					popperOptions = {
						
						onUpdate: function onUpdate( data ) {
							
							var tippy = data.instance.reference._tippy;
							
							if ( dmp_parent_selector === '' ) {
								
								appendTo = whereAppendTippy( tippy, props );
							}
							
							if ( tippy !== null ) {
									
								if ( appendTo === diviPageContainer ) {
									
									var tippyClass = tippy.popper.className
									, tippyClassFixOnTop = 'tippy-popper-fixontop';
								}
							}
							
							setCustomWidth( tippy, options );
							
							if ( tippy.popperChildren.content.firstChild !== null ) {
							
								setMaxHeight( tippy, options, props );
							
								updateDiviIframes( '#' + tippy.popperChildren.content.firstChild.getAttribute('id') );
							}
						}
					};
					
					if ( options['megaprowidth'] == '100%' ) {
						
						maxWidth = '100%';
						
						if ( props.parentDiviTopHeader.length
							 || props.parentDiviMainHeader.length 
							 ) {
						 
							options['placement'] = 'bottom-start';
						}
					}
					
					// This is required since transform styles will not allow fixed elements to work properly when parent elements are absolute
					popperOptions['modifiers'] = {
						computeStyle: {
							gpuAcceleration: false,
						},
					};
					
					if ( options['megaprowidth'] == '100%' || props.parentSlideMenuContainer.length ) {
						
						popperOptions['modifiers']['computeStyle']['y'] = 'left';
					}
					
					popperOptions['modifiers']['flip'] = {
						flipVariations: true,
						flipVariationsByContent: true
					};
					
					if ( options['placement'] == 'left' || options['placement'] == 'right' ) {
						
						flip = true;
						
						popperOptions['positionFixed'] = true;
					}
					
					if ( options['placement'] == 'top' ) {
						
						flip = true;
						flipBehavior = ["top", "left", "right", "bottom"];
					}
					
					if ( options['placement'] == 'left' ) {
						
						flipBehavior = ["left", "bottom", "right", "top"];
					}
					
					if ( options['placement'] == 'right' ) {
						
						flipBehavior = ["right", "bottom", "left", "top"];
					}
					
					// is nested child?
					if ( dmp_parent_selector !== '' ) {
						
						flip = true;
						flipBehavior = ["bottom", "left", "right", "top" ];
						
						popperOptions['positionFixed'] = true;
					}
					
					
					// Get element object in case a string was supplied as reference element
					if ( typeof selector !== 'object' ) {
						
						selector = document.querySelector( selector );
					}
					
					appendTo = whereAppendTippy( null, props, dmp_parent_selector );
					
					// mega pro is triggered from Divi Slide Menu or when Tippy is appended to parent
					if ( props.parentSlideMenuContainer.length || appendTo === 'parent' ) {
						
						popperOptions['positionFixed'] = true;
						
						popperOptions['modifiers']['preventOverflow'] = {
							priority: ['left','top']
						};
						
						if ( props.parentSlideMenuContainer.css('right') !== '' ) {
							
							popperOptions['modifiers']['computeStyle']['y'] = 'right';
							
							popperOptions['modifiers']['preventOverflow'] = {
								priority: ['right','top']
							};
						}
					}
					
					dmps[ divimegapro_id ] = tippy( selector, {
						appendTo: appendTo,
						aria: null,
						allowHTML: true,
						arrow: options['arrowEnabled'],
						boundary: 'scrollParent',
						maxWidth: maxWidth,
						placement: options['placement'],
						content: '',
						delay: [ null, options['delay'] ],
						animation: options['animation'],
						distance: options['distance'],
						offset: '0,0',
						interactive: true,
						interactiveDebounce: interactiveDebounce,
						interactiveBorder: 9,
						zIndex: 16777271,
						trigger: triggerType,
						theme: 'dmmbasic',
						lazy: false,
						flip: flip,
						flipBehavior: flipBehavior,
						flipOnUpdate: true,
						hideOnClick: hideOnClick,
						ignoreAttributes: true,
						sticky: true,
						popperOptions: popperOptions,
						onMount: function onMount( instance ) {
							
							instance.popperInstance.update();
							
							var dmpContainerID = 'divimegapro-container-' + divimegapro_id + '-clone-' + instance.popper.id
							, dmpContainerPopper = tippyDmpSelector + '.tippy-popper .tippy-content';
							
							if ( dmp_parent_selector === '' ) {
								
								tippy.hideAll({ duration: 0, exclude: instance });
								
								$( "html,body" ).addClass('divimegapro-open');
								
								if ( $( documentBody ).hasClass('admin-bar') ) {
									
									documentHTML.addClass('divimegapro-open-adminbar');
								}
							}
							
							divimegapro.addClass( 'dmp-open' );
							
							// Add DMP Ref Class
							if ( instance.popper.className.indexOf('dmp') == -1 ) {
								
								instance.popper.className = instance.popper.className + ' dmp-' + divimegapro_id;
								
								instance.popper.setAttribute( 'data-dmpid', divimegapro_id ); 
							}
							
							toggleSrcInPlayableTags( divimegapro );
							
							if ( $( divimegapro_container_selector + ' ' + divimegapro_selector ).length ) {
							
								$( dmpContainerPopper ).html( '' );
							}
							
							var dmpCloneContainer = $( divimegapro_selector ).clone().attr('id', dmpContainerID );
							
							dmpCloneContainer.appendTo( dmpContainerPopper );
							
							initDiviElements( divimegapro_id );
							
							checkNestingMenus( divimegapro_id, instance.popper.id, instance );
							
							updateDiviIframes( '#' + dmpContainerID );
						},
						onShow: function onShow( instance ) {
							
							if ( trigger == 'mouseenter focus' && exitType == 'click' ) {
								
								instance.setProps({ trigger: 'click' });
							}
							
							if ( trigger == 'click' && exitType == 'hover' ) {
								
								instance.setProps({ trigger: tippy.defaultProps.trigger });
							}
						},
						onShown: function onShown( instance ) {
							
							var dmpContainerPopper = tippyDmpSelector + '.tippy-popper .tippy-content';
							
							// Add Divi Mega Pro reference in Email Optin module
							var et_pb_newsletter = $( dmpContainerPopper ).find('.et_pb_newsletter_form form .et_pb_newsletter_fields');
							if ( et_pb_newsletter.length ) {
								
								var et_pb_signup_divimegaproid = et_pb_newsletter.find('et_pb_signup_divimegaproid');
								
								if ( et_pb_signup_divimegaproid.length < 1 ) {
									
									$('<input>').attr({
										
										type: 'text',
										name: 'et_pb_signup_divimegaproid',
										class: 'et_pb_signup_divimegaproid et_pb_signup_custom_field',
										'data-original_id': 'et_pb_signup_divimegaproid',
										value: divimegapro_id
										
									}).appendTo( et_pb_newsletter );
								}
							}
							
							if ( !refElement.is(':visible') ) {
								
								if ( instance.popper.className.indexOf('topfixed') == -1 ) {
									
									instance.popper.className = instance.popper.className + ' topfixed';
								}
							}
							
							divimegapro.addClass( 'divimegapro-opened' );
							
							dmpRemoveDiviFix( dmpContainerPopper );
						},
						onHide: function onHide( instance ) {
							
							if ( trigger == 'mouseenter focus' && exitType == 'click' ) {
								
								instance.setProps({ trigger: tippy.defaultProps.trigger });
							}
							
							if ( trigger == 'click' && exitType == 'hover' ) {
								
								instance.setProps({ trigger: 'click' });
							}
							
							if ( refElement.parents('.et_mobile_menu').is(':visible') ) {
								
								refElement.parents('.mobile_nav').removeClass('closed');
								refElement.parents('.mobile_nav').addClass('opened');
								refElement.parents('.et_mobile_menu').removeClass('dmp-divimobilemenu-visible');
								refElement.parents('.et_mobile_menu').attr( 'style', 'display:block');
							}
							
							divimegapro.removeClass( 'dmp-open' );
							divimegapro.addClass( 'dmp-close' );
							
							divimegapro.removeClass( 'divimegapro-opened' );
						},
						onHidden: function onHidden( instance ) {
							
							divimegapro.removeClass( 'dmp-close' );
							
							if ( dmp_parent_selector == '' ) {
								
								$( "html,body" ).removeClass( 'divimegapro-open' );
								
								if ( $( documentBody ).hasClass('admin-bar') ) {
									
									documentHTML.removeClass('divimegapro-open-adminbar');
								}
							}
							
							dmmTogglePlayableTags( divimegapro_selector );
						},
					});
					
				}, 1);
			}
			
			
			function whereAppendTippy( tippy, props, dmp_parent_selector ) {
				
				var tippyInstance = false
				, tippyParent = props.appendTo
				, appendTo;
				
				const wrapper = document.createElement('div');
				wrapper.className = 'tippy-wrapper';
				
				if ( tippy !== null ) {
					
					tippyInstance = true;
				}
				
				if ( props.parentDiviTopHeader.length ) {
					
					tippyParent = diviTopHeader;
				}
				
				if ( props.parentDiviMainHeader.length ) {
					
					tippyParent = diviMainHeader;
				}
				
				if ( props.parentDiviMainContent.length ) {
				
					tippyParent = diviPageContainer;
				}
				
				if ( window.et_is_vertical_nav 
					&& ( props.parentDiviTopHeader.length || props.parentDiviMainHeader.length ) ) {
					
					tippyParent = diviPageContainer;
				}
				
				if ( dmp_parent_selector !== undefined && dmp_parent_selector !== '' ) {
				
					tippyParent = document.querySelector( dmp_parent_selector );
				}
				
				appendTo = tippyParent;
				
				if ( appendTo === '' || appendTo === null ) {
					
					tippyParent = appendTo = 'parent';
					
					if ( tippyInstance ) {
						
						tippy.props.flip = true;
					}
				}
				
				if ( tippyInstance ) {
					
					var placement = tippy.props.placement;
					
					if ( placement !== 'left' && placement !== 'right' ) {
						
						if ( tippyParent == diviPageContainer ) {
							
							tippy.props.flip = true;
						}
						else {
							
							tippy.props.flip = false;
						}
					}
					
					tippy.props.appendTo = tippyParent;
				}
				
				return appendTo;
			}
			
			
			function checkNestingMenus( parentdmp_id, popper_id, instance ) {
				
				var divimegapro_selector = '#divimegapro-container-' + parentdmp_id + '-clone-' + popper_id
				, divimegapro_popper = '.dmp-' + parentdmp_id;
				
				$( divimegapro_selector + ' [data-divimegaproid]' ).each(function() {
					
					var divimegapro_id = parseInt( $(this).attr('data-divimegaproid') );
					
					if ( typeof divimegapro_id == 'number' ) {
						
						createDiviMegaPro( divimegapro_id, this, divimegapro_popper );
					}
				});
			}
			
			
			function setCustomWidth( instance, options ) {
				
				var megaprowidth = options['megaprowidth'] + '';
				
				const viewportWidth = $(window).width();
				
				const customWidthInt = parseInt( megaprowidth );
				
				const customWidthUnit = megaprowidth.replace(/[0-9]/g, '');
				
				var customWidth = 0;
				
				if ( customWidthInt > 0 ) {
					
					if ( customWidthUnit == '' ) {
						
						customWidth = customWidthInt + 'px';
					}
					else {
						
						customWidth = customWidthInt + customWidthUnit;
					}
					
					if ( customWidthInt > viewportWidth 
					&& customWidthUnit == 'px' ) {
					
						customWidth = viewportWidth + 'px';
					}
					
					instance.popper.style.width = customWidth;
					
					// Wide DMP when "Mega Pro Width" setting is null
					if ( options['megaprowidth'] == '100%' ) {
						
						instance.popper.className = instance.popper.className + ' tippy-popper-wide';
					}
					else {
						
						instance.popper.className = instance.popper.className.replace( /\btippy-popper-wide\b/g, '' );
					}
				}
			}
			
			
			function setMaxHeight( instance, options, props ) {
				
				var divimegapro_selector = instance.popperChildren.content.firstChild.getAttribute('id');
				
				instance.props.maxHeight = '';
				instance.popperChildren.tooltip.style.maxHeight = '';
				instance.popperChildren.content.style.maxHeight = '';
				instance.popperChildren.content.style.overflowY = '';
				
				const dmpCloneContainerHeight = $( '#' + divimegapro_selector ).height();
				
				const viewportWidth = $(window).width();
				
				const viewportHeight = $(window).height();
				
				var dmpTotalHeight = dmpCloneContainerHeight;
				
				var customHeight = 0;
				
				if ( options['megaprofixedheight'] > 0 ) {
					
					customHeight = options['megaprofixedheight'];
				}
				
				if ( viewportWidth >= themesBreakpoint[ 'Divi' ] ) {
					
					// Scroll is not required when Divi Mega Pro is triggered from #page-container and not being triggered by Divi Menu module
					if ( ( props.parentDiviMainContent.length || props.parentDiviPageContainer.length 
						|| props.parentDiviETMainArea.length || props.parentDiviMainFooter.length ) 
						&& 
						!( props.parentDiviTopHeader.length || props.parentDiviMainHeader.length || props.parentDiviModuleMenu.length || props.parentSlideMenuContainer.length ) 
						) {
						
						return;
					}
				}
				
				const clientRect = instance.reference.getBoundingClientRect();
				
				const referenceHeight = clientRect[ 'height' ];
				
				const referenceTop = clientRect[ 'top' ];
				
				const distance = instance.props.distance;
				
				var maxHeight = viewportHeight - referenceHeight - distance;
				
				if ( instance.popperInstance.options.positionFixed === true ) {
					
					var distanceFromTop = parseFloat( instance.popper.style.top );
					
					dmpTotalHeight = ( dmpCloneContainerHeight + distanceFromTop );
					
					maxHeight = viewportHeight - distanceFromTop;
				}
				
				// Scroll is not required when Mega Pro Height is lower than Viewport Height
				if ( dmpTotalHeight < viewportHeight && customHeight == 0 ) {
					
					return;
				}
				
				// Apply custom height if there any
				if ( maxHeight >= customHeight && customHeight > 0 ) {
					
					maxHeight = customHeight;
				}
				
				instance.props.maxHeight = maxHeight;
				instance.popperChildren.tooltip.style.maxHeight = maxHeight + 'px';
				instance.popperChildren.content.style.maxHeight = maxHeight + 'px';
				instance.popperChildren.content.style.overflowY = 'auto';
				
				var firstDiviSection = instance.popperChildren.content.querySelectorAll('.et_pb_section:first-child');
				
				firstDiviSection = $( firstDiviSection );
				
				const firstDiviSectionBoxShadow = firstDiviSection.css('box-shadow');
				
				instance.popperChildren.content.style.boxShadow = firstDiviSectionBoxShadow;
				
				const firstDiviSectionborderTopLeftRadius = firstDiviSection.css('border-top-left-radius');
				const firstDiviSectionborderTopRightRadius = firstDiviSection.css('border-top-right-radius');
				const firstDiviSectionborderBottomRightRadius = firstDiviSection.css('border-bottom-right-radius');
				const firstDiviSectionborderBottomLeftRadius = firstDiviSection.css('border-bottom-left-radius');
				
				instance.popperChildren.content.style.borderTopLeftRadius = firstDiviSectionborderTopLeftRadius;
				instance.popperChildren.content.style.borderTopRightRadius = firstDiviSectionborderTopRightRadius;
				instance.popperChildren.content.style.borderBottomRightRadius = firstDiviSectionborderBottomRightRadius;
				instance.popperChildren.content.style.borderBottomLeftRadius = firstDiviSectionborderBottomLeftRadius;
			}
			
			function initDiviElements( divimegapro_id ) {
				
				// Set Divi Elements
				var $divimegaprobody = $( '.dmp-' + divimegapro_id + ' .divimegapro-body'),
					$et_pb_circle_counter = $(".divimegapro-body .et_pb_circle_counter"),
					$et_pb_number_counter = $(".divimegapro-body .et_pb_number_counter"),
					$et_pb_countdown_timer = $(".divimegapro-body .et_pb_countdown_timer"),
					$et_pb_tabs = $(".divimegapro-body .et_pb_tabs"),
					$et_pb_map = $( '.dmp-' + divimegapro_id + ' .et_pb_map_container'),
					$et_pb_menu = $(".et-menu-nav ul.nav");
					
				$('.divimegapro-body .et_animated').each(function() {
					et_remove_animation( $( this ) );
				});
				
				// Init Divi Elements
				setTimeout( function() {
					
					window.et_fix_testimonial_inner_width(), 
					$et_pb_circle_counter.length && window.et_pb_reinit_circle_counters($et_pb_circle_counter), 
					$et_pb_number_counter.length && window.et_pb_reinit_number_counters($et_pb_number_counter), 
					$et_pb_countdown_timer.length && window.et_pb_countdown_timer_init($et_pb_countdown_timer),
					($et_pb_tabs.length) && window.et_pb_tabs_init($et_pb_tabs),
					window.et_reinit_waypoint_modules(),
					window.et_pb_init_modules(),
					$et_pb_menu.length > 0 && $et_pb_menu.each((function(t) {
						
						var n = $et_pb_menu.closest(".et_pb_module").find("div .mobile_nav");
						
						n.each((function() {
							
							var s = $(this), n = s.find("> ul");
							
							(s.off("click"), s.on("click", ".mobile_menu_bar", (function() {
                                return $(".mobile_nav.opened .mobile_menu_bar").not($(this)).trigger("click"), s.hasClass("closed") ? (s.removeClass("closed").addClass("opened"), n.stop().slideDown(500)) : (s.removeClass("opened").addClass("closed"), n.stop().slideUp(500)), !1
                            })))
						}))
                    })),
					$divimegaprobody.find(".et_pb_slider").length > 0 && $divimegaprobody.find(".et_pb_slider").each((function() {
						et_pb_slider_init($(this))
                    })),
					et_pb_init_maps( $et_pb_map );
					
					setTimeout( function() {
						
						callDiviLifeFuncs( '.dmp-' + divimegapro_id + ' ' );
					
					}, 100);
					
				}, 1);
			}
			
			function et_pb_init_maps( $et_pb_map ) {
				$et_pb_map.each(function() {
					et_pb_map_init($(this))
				})
			}
			
			function et_get_animation_classes() {
				return ["et_animated", "infinite", "fade", "fadeTop", "fadeRight", "fadeBottom", "fadeLeft", "slide", "slideTop", "slideRight", "slideBottom", "slideLeft", "bounce", "bounceTop", "bounceRight", "bounceBottom", "bounceLeft", "zoom", "zoomTop", "zoomRight", "zoomBottom", "zoomLeft", "flip", "flipTop", "flipRight", "flipBottom", "flipLeft", "fold", "foldTop", "foldRight", "foldBottom", "foldLeft", "roll", "rollTop", "rollRight", "rollBottom", "rollLeft"]
			}
			
			function et_remove_animation($element) {
				var animation_classes = et_get_animation_classes();
				$element.removeClass(animation_classes.join(" ")), $element.removeAttr("style")
			}
			
			
			function getOptions( divimegapro_container ) {
				
				var dmmdataObject = divimegapro_container.data()
				, options = [];
				
				options['megaprowidthcustom'] = 0;
				
				options['animation'] = dmmdataObject['animation'];
				
				options['triggertype'] = 'mouseenter focus';
				if ( dmmdataObject['triggertype'] == 'click' ) {
					
					options['triggertype'] = 'click';
				}
				
				options['placement'] = dmmdataObject['placement'];
				options['distance'] = parseInt( dmmdataObject['margintopbottom'] );
				
				if ( !isInt( options['distance'] ) ) {
					
					options['distance'] = 0;
				}
				
				if ( !isInt( dmmdataObject['megaprowidth'] ) ) {
					
					options['megaprowidth'] = dmmdataObject['megaprowidthcustom'];
					options['megaprowidthcustom'] = 1;
					
				} else {
					
					options['megaprowidth'] = dmmdataObject['megaprowidth'] + '%';
				}
				
				options['megaprofixedheight'] = parseInt( dmmdataObject['megaprofixedheight'] );
				
				if ( !isInt( options['megaprofixedheight'] ) ) {
					
					options['megaprofixedheight'] = 0;
				}
				
				if ( dmmdataObject['exittype'] == 'click' ) {
					
					options['exittype'] = 'click';
				
				} else {
					
					options['exittype'] = 'hover';
				}
				
				if ( dmmdataObject['exitdelay'] != 0 
					&& dmmdataObject['exitdelay'] != '' 
					&& options['exittype'] == 'hover' ) {
				
					options['delay'] = dmmdataObject['exitdelay'] * 1000;
				
				} else {
					
					options['delay'] = 0;
				}
				
				
				options['arrowEnabled'] = false;
				/* Arrow Feature */
				if ( dmmdataObject['enable_arrow'] == 1 ) {
				
					options['arrowEnabled'] = true;
				}
				
				options['arrowType'] = dmmdataObject['arrowfeature_type'];
				
				options['bgcolor'] = dmmdataObject['bgcolor'];
				options['fontcolor'] = dmmdataObject['fontcolor'];
				
				return options;
			}
			
			
			function getScrollTop() {
				
				if ( typeof pageYOffset!= 'undefined' ) {
					
					// most browsers except IE before #9
					return pageYOffset;
				}
				else {
					
					var B = document.body; // IE 'quirks'
					var D = document.documentElement; // IE with doctype
					D = ( D.clientHeight ) ? D: B;
					
					return D.scrollTop;
				}
			}
			
			
			function toggleSrcInPlayableTags( str ) {
				
				str.find("iframe").each(function() { 
					var src = $(this).data('src');
					$(this).attr('src', src);  
				});
				
				return str;
			}
			
			
			function getActiveDiviMegaPro() {
				
				var divimegapro = null
				, divimegapro_id = null
				, elemID = null
				, placement = null;
				
				// find active divimegapro only in top
				divimegapro = $( 'body' ).find( '.divimegapro.open' );
				
				if ( !divimegapro ) {
					
					divimegapro = $( 'body' ).find( '.divimegapro.close' );
				}
				
				if ( divimegapro.length ) {
					
					var divimegaproArr = divimegapro.attr('id').split('-')
					, divimegapro_id = divimegaproArr[1];
				}
				
				return divimegapro_id;
			}
			
			
			function isInt(value) {
				var x;
				return isNaN(value) ? !1 : (x = parseFloat(value), (0 | x) === x);
			}
			
			
			function removeMonarch() {
				
				if ( $( '.divimegapro .et_social_inline' ).length ) {
					
					$( '.divimegapro .et_social_inline' ).parents('.et_pb_row').remove();
				}
				
				removeMonarchTimer = setTimeout(removeMonarch, 500);
			}
			
			
			// Belongs to https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions
			function escapeRegExp(string) {
				return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
			}
			
			
			function isDiviMobile() {
				
				diviMobile = false;
				
				if ( $('body').hasClass('et_mobile_device') ) {
					
					diviMobile = true;
				}
				
				return diviMobile;
			}
		}
		
		var checkCursorOverDiviTabTimer = 0,
		checkDiviTabElem;
		
		// Enable Divi URL Link module
		function enableDiviURLLinkModules( parent ) {
			
			"undefined" != typeof et_link_options_data && 0 < et_link_options_data.length && $.each(et_link_options_data, function(index, link_option_entry) {
				if (link_option_entry.class && link_option_entry.url && link_option_entry.target) {
					var $clickable = $("." + link_option_entry.class);
					$clickable.on("click", function(event) {
						if (event.target !== event.currentTarget && !et_is_click_exception($(event.target)) || event.target === event.currentTarget) {
							if (event.stopPropagation(), "_blank" === link_option_entry.target) return void window.open(link_option_entry.url);
							var url = link_option_entry.url;
							url && "#" === url[0] && $(url).length ? (et_pb_smooth_scroll($(url), void 0, 800), history.pushState(null, "", url)) : window.location = url
						}
					}), $clickable.on("click", "a, button", function(event) {
						et_is_click_exception($(this)) || event.stopPropagation()
					})
				}
			});
		}
		
		function et_is_click_exception($element) {
			for (var is_exception = !1, click_exceptions = [".et_pb_toggle_title", ".mejs-container *", ".et_pb_contact_field input", ".et_pb_contact_field textarea", ".et_pb_contact_field_checkbox *", ".et_pb_contact_field_radio *", ".et_pb_contact_captcha", ".et_pb_tabs_controls a"], i = 0; i < click_exceptions.length; i++)
				if ($element.is(click_exceptions[i])) {
					is_exception = !0;
					break
				}
			return is_exception
		}
		
		// Enable Divi Toggle with hover
		function enableDiviToggleHover( parent ) {
			
			if ( typeof parent === 'undefined' ) {
				
				var parent = '';
			}
			
			$( parent + '.et_pb_toggle').on( 'mouseenter', function(e) {
				$( this ).children('.et_pb_toggle_title').trigger( "click" );
			});
		}
		
		// Enable Divi Tabs with hover
		function enableDiviTabHover( parent ) {
			
			if ( typeof parent === 'undefined' ) {
				
				var parent = '';
			}
			
			$( parent + '.et_pb_tabs .et_pb_tabs_controls > [class^="et_pb_tab_"]').on( 'mouseenter', function(e) {
				
				if ( ! $( this ).hasClass('et_pb_tab_active') ) {
					checkDiviTabElem = $( this );
				}
				else {
					checkDiviTabElem = false;
				}
			});
		}
		
		function checkDiviTab() {
			
			if ( checkDiviTabElem ) {
				
				if ( ! checkDiviTabElem.parent().hasClass('et_pb_tab_active') ) {
					
					checkDiviTabElem.first('a').trigger( "click" );
				}
			}
			
			checkCursorOverDiviTabTimer = setTimeout( checkDiviTab, 150 );
		}
		
		function callDiviLifeFuncs( parent ) {
			
			removeMobileMenuDuplicates();
			
			enableDiviURLLinkModules( parent );
			
			if ( typeof diviTabsToggleHover !== 'undefined' ) {
				
				if ( diviTabsToggleHover === true ) {
				
					checkDiviTab();
					enableDiviTabHover( parent );
					enableDiviToggleHover( parent );
				}
			}
		}
		
		function removeMobileMenuDuplicates() {
			
			var mobile_menu_selector = '.et_pb_menu__wrap .et_mobile_menu';
			
			if ( $( mobile_menu_selector ).length > 1 ) {
			
				$( mobile_menu_selector ).each(function ( key, value ) {
					
					if ( key > 0 ) {
						
						$( this ).remove();
					}
				});
			}
		}
		
		if ( typeof diviTabsToggleHoverGlobal !== 'undefined' ) {
			
			if ( diviTabsToggleHoverGlobal === true ) {
			
				callDiviLifeFuncs();
			}
		}
		
		
		function updateDiviIframes( selector ) {
			
			// Find all iframes inside the divimegapros
			var $dibiframes = $( selector + ' iframe' )
			, ratio = 1;
			
			setTimeout( function() { 
			
				$dibiframes.each( function() {
					
					ratio = $( this ).attr( "data-ratio" );
					
					if ( ratio === undefined ) {
						
						var iframeHeight = this.height;
						
						if ( iframeHeight == '' ) {
							
							iframeHeight = $( this ).height();
						}
						
						var iframeWidth = this.width;
						
						if ( iframeWidth == '' ) {
							
							iframeWidth = $( this ).width();
						}
						
						iframeHeight = parseInt( iframeHeight );
						iframeWidth = parseInt( iframeWidth );
						
						ratio = iframeHeight / iframeWidth;
						
						$( this ).attr( "data-ratio", ratio );
					}
					else {
						
						ratio = $( this ).attr( 'data-ratio' );
					}
					
					// Remove hardcoded width & height attributes
					$( this ).removeAttr( "width" ).removeAttr( "height" );
					
					// Get the parent container's width
					var width = $( this ).parent().width();
					
					$( this ).width( width ).height( width * ratio );
				});
				
			}, 50);
		}
		
	}; // end mainDiviMegaPro
	
	function dmpRemoveDiviFix( divimegapro_selector ) {
		
		var divimegapro = $( divimegapro_selector )
		, et_pb_section_first = divimegapro.find( '.et_pb_section_first' );
		
		et_pb_section_first.removeAttr('style');
		et_pb_section_first.data('fix-page-container', 'off');
	}
	
	function dmmTogglePlayableTags( divimegapro_id, wait ) {
	
		var $ = jQuery;
		
		if ( !divimegapro_id  ) {
			
			divimegapro_id = "";
		}
		
		if ( !wait  ) {
			
			wait = 1;
		}
		
		/* Prevent playable tags load content before divimegapro call */
		setTimeout(function() {
			
			$( divimegapro_id + ".divimegapro").find("iframe").not( '[id^="gform"], .frm-g-recaptcha' ).each(function() { 
			
				var iframeParent = $(this).parent();
				
				// Don't modify Google Map iframe
				if ( iframeParent.attr("class") == 'gm-style' ) {
					
					return;
				}
				
				var iframe = $(this).prop("outerHTML");
				var src = iframe.match(/src=[\'"]?((?:(?!\/>|>|"|\'|\s).)+)"/);
				
				if ( src !== null ) {
					
					src = src[0];
					src = src.replace("src", "data-src");
					iframe = iframe.replace(/src=".*?"/i, "src=\"about:blank\" data-src=\"\"" );
					
					if ( src != "data-src=\"about:blank\"" ) {
						iframe = iframe.replace("data-src=\"\"", src );
					}
					
					$( iframe ).insertAfter( $(this) );
					
					$(this).remove();
				}
			});
			
		}, wait);
		
		$( divimegapro_id + ".divimegapro").find("video").each(function() {
			$(this).get(0).pause();
		});
		
		$( divimegapro_id + ".divimegapro").find("audio").each(function() {
			
			this.pause();
			this.currentTime = 0;
		});
	}
	
	dmmTogglePlayableTags( '', 1000 );
	
	// Resize the iframes when the window is resized
	$(window).on('resize orientationchange', function() {
		
		dmpRemoveDiviFix( '.divimegapro.dmp-open' );
	});
	
	$(window).ready(function() {
		
		// Divi Cascade Fix
		if ( !$('#cloned-et-builder-module-design-cached-inline-styles').length ) {
			
			// Divi Cached Inline Styles
			var divicachedcsscontent = ''
			, divimoduledesigncss = $( 'style[id^="et-builder-module-design"]' )
			, divicachedcss = $( 'style[id*="cached-inline-styles"]' )
			, htmldivimoduledesigncss = divimoduledesigncss.html()
			, htmldivicachedcss = divicachedcss.html();
			
			// Remove #page-container from Divi Cached Inline Styles tag and cloning it to prevent issues
			if ( undefined !== htmldivimoduledesigncss ) {
				
				htmldivimoduledesigncss = htmldivimoduledesigncss.replace(/\#page-container/g, ' ');
				htmldivimoduledesigncss = htmldivimoduledesigncss.replace(/\.et_pb_extra_column_main/g, ' ');
			}
			
			if ( undefined !== htmldivicachedcss ) {
				
				htmldivicachedcss = htmldivicachedcss.replace(/\#page-container/g, ' ');
				htmldivicachedcss = htmldivicachedcss.replace(/\.et_pb_extra_column_main/g, ' ');
			}
			
			divicachedcsscontent = htmldivimoduledesigncss + ' ' + htmldivicachedcss;
			
			if ( divicachedcsscontent !== '' ) {
				
				$( divicachedcss ).after( '<style id="cloned-et-builder-module-design-cached-inline-styles">' + divicachedcsscontent + '</style>' );
			}
		}
		
		$('body').prepend( $( "#sidebar-divimegapro" ) );
		
		setTimeout(function() {
			
			$('.divimegapro-wrapper .divimegapro').mainDiviMegaPro();
			
		}, 1);
	});
	
})( jQuery, window, document );
