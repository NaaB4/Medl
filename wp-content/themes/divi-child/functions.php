<?php
/*
if(isset($_GET['debug'])){
	add_action('init',function(){
		list($items,$total)=EDC_TARIFFS::getList(['page_number'=>1,'per_page'=>999999]);
		foreach($items as $item) if($item->type==3){
			$min_e_buf=$item->options['min_electricity_delivery_per_year'];
			$max_e_buf=$item->options['max_electricity_delivery_per_year'];
			$min_g_buf=$item->options['min_gas_delivery_per_year'];
			$max_g_buf=$item->options['max_gas_delivery_per_year'];
			var_dump($min_e_buf);
			var_dump($max_e_buf);
			var_dump($min_g_buf);
			var_dump($max_g_buf);
			echo "\r\n<br>";
			echo "\r\n<br>";
			echo "\r\n<br>";
			echo "\r\n<br>";
			echo "\r\n<br>";
			EDC_TARIFFS::updateTariffOptions($item->id,$item->type,[
				'min_electricity_delivery_per_year'=>$min_g_buf,
				'max_electricity_delivery_per_year'=>$max_g_buf,
				'min_gas_delivery_per_year'=>$min_e_buf,
				'max_gas_delivery_per_year'=>$max_e_buf,
			]);
		}
			die();
	},50);
}*/

function my_theme_enqueue_styles() {

    $parent_style = 'divi-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


//add categories & taxonomies to pages
function add_taxonomies_to_pages() {
 register_taxonomy_for_object_type( 'post_tag', 'page' );
 register_taxonomy_for_object_type( 'category', 'page' );
 }
add_action( 'init', 'add_taxonomies_to_pages' );


//Category Klasse zum Body
add_filter('body_class','add_category_to_single');
  function add_category_to_single($classes) {
    if (is_page() || is_category()) {
      global $post;
      foreach((get_the_category($post->ID)) as $category) {
        // add category slug to the $classes array
        $classes[] = $category->category_nicename;
      }
    }
    // return the $classes array
    return $classes;
  }



//Shortcode to show the module
function showmodule_shortcode($moduleid) {
extract(shortcode_atts(array('id' =>'*'),$moduleid));
return do_shortcode('[et_pb_section global_module="'.$id.'"][/et_pb_section]');
}
add_shortcode('showmodule', 'showmodule_shortcode');



add_shortcode('field', 'shortcode_field');

function shortcode_field($atts){
     extract(shortcode_atts(array(
                  'post_id' => NULL,
               ), $atts));
  if(!isset($atts[0])) return;
       $field = esc_attr($atts[0]);
       global $post;
       $post_id = (NULL === $post_id) ? $post->ID : $post_id;
       return get_post_meta($post_id, $field, true);
}


// Liste aller "Projekt"-Seiten bekommen
function output_projects_list() {
    global $wpdb;

    $custom_post_type = 'project'; // define your custom post type slug here

    // A sql query to return all post titles
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish' ORDER BY ID DESC", $custom_post_type ), ARRAY_A );

    // Return null if we found no results
    if ( ! $results )
        return;

    $output = '';
    foreach( $results as $index => $post ) {
        $output .= '<div class="standort flex-col" ';
        $standort = get_field( 'standort', $post['ID'] );
        if($standort) {
            foreach($standort as $attr => $value) {
                $output .= " data-$attr=\"$value\" ";
            }
        }

        $output .= ' ><div onclick="scrollToMap()" class="et_pb_module et_pb_blurb et_pb_blurb_0 baustellen-info-blurb et_pb_bg_layout_light  et_pb_text_align_left  et_pb_blurb_position_left" ><div class="content">';

        if(get_field( 'baustelle', $post['ID'] )) {
            $output .= '<h4 class="et_pb_module_header">' . get_field('baustelle', $post['ID']) . '</h4>';
        }

        if(get_field( 'bereich', $post['ID'] )) {
            $output .= '<div><p><strong>In welchem Bereich wird gearbeitet?</strong></p><p>' . get_field('bereich', $post['ID']) . '</p></div>';
        }

        if(get_field( 'verkehr', $post['ID'] )) {
            $output .= '<div><p><strong>Wie wird der Verkehr geregelt?</strong></p><p>' . get_field('verkehr', $post['ID']) . '</p></div>';
        }

        if(get_field( 'art_der_baumasnahme', $post['ID'] )) {
            $output .= '<div><p><strong>Art der Baumaßnahme</strong></p><p>' . get_field('art_der_baumasnahme', $post['ID']) . '</p></div>';
        }

        if(get_field( 'baubeginn', $post['ID'] ) && get_field( 'bauende', $post['ID'] )) {
            $output .= '<div><p><strong>Zeitraum</strong></p><p>' . get_field('baubeginn', $post['ID']) . ' <strong>–</strong> ' . get_field('bauende', $post['ID']) . '</p></div>';
        }

        $output .= '</div>';

        if(get_field( 'standort', $post['ID'] )) {
            $output .= '<div class="et_pb_button_module_wrapper et_pb_button_0_wrapper et_pb_button_alignment_center et_pb_module mt-1">
            				<div class="et_pb_button et_pb_button_0 button-2 text-align-center et_pb_bg_layout_light pinButton">auf Karte anzeigen!</div>
            			</div>';
        }


        $output .= '</div></div>';
    }
    // get the html
    return $output;
}


// Medl Baustellen Shortcode: [baustellen]
function baustellen_function(){
  return output_projects_list();
}

add_shortcode('baustellen', 'baustellen_function' );


//Baustellen Slider
// Liste aller "Projekt"-Seiten bekommen
function output_projects_slider_list() {
    global $wpdb;

    $custom_post_type = 'project'; // define your custom post type slug here

    // A sql query to return all post titles
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'", $custom_post_type ), ARRAY_A );

    // Return null if we found no results
    if ( ! $results )
        return;

    $output = '<div class="single-item-arrows baustellen slider"><div class="baustellenteaser"><div><img class="icon" src="/wp-content/uploads/baustellenIcon-1.png" alt="Baustellen Icon"><h4 class="blue">Alle medl-Baustellen auf einen Blick</h4><div class="et_pb_button_wrapper tarifbox-button"><a class="et_pb_button et_pb_pricing_table_button" href="/service/baustellen/">Hier bauen wir für euch!</a></div></div></div>';

      foreach( $results as $index => $post ) {
          $output .= '<div class="standort m-10"';
          $standort = get_field( 'standort', $post['ID'] );
          if($standort) {
              foreach($standort as $attr => $value) {
                  $output .= " data-$attr=\"$value\" ";
              }
          }

          $output .= ' ><div class="m-10"><div class="et_pb_module et_pb_blurb et_pb_blurb_0 baustellen-info-blurb et_pb_bg_layout_light  et_pb_text_align_left  et_pb_blurb_position_left" ><div class="content">';

          if(get_field( 'baustelle', $post['ID'] )) {
              $output .= '<h4 class="et_pb_module_header">' . get_field('baustelle', $post['ID']) . '</h4>';
          }

          if(get_field( 'bereich', $post['ID'] )) {
              $output .= '<div><p><strong>In welchem Bereich wird gearbeitet?</strong></p><p>' . get_field('bereich', $post['ID']) . '</p></div>';
          }

          if(get_field( 'verkehr', $post['ID'] )) {
              $output .= '<div><p><strong>Wie wird der Verkehr geregelt?</strong></p><p>' . get_field('verkehr', $post['ID']) . '</p></div>';
          }

          if(get_field( 'art_der_baumasnahme', $post['ID'] )) {
              $output .= '<div><p><strong>Art der Baumaßnahme</strong></p><p>' . get_field('art_der_baumasnahme', $post['ID']) . '</p></div>';
          }

          if(get_field( 'baubeginn', $post['ID'] ) && get_field( 'bauende', $post['ID'] )) {
              $output .= '<div><p><strong>Zeitraum</strong></p><p>' . get_field('baubeginn', $post['ID']) . ' <strong>–</strong> ' . get_field('bauende', $post['ID']) . '</p></div>';
          }

          $output .= '</div>';


          $output .= '</div></div></div>';
      }
      $output .= '</div>';
    // get the html
    return $output;
}


// Medl Baustellen Slider Shortcode: [baustellenslider]
function baustellenslider_function(){
  return output_projects_slider_list();
}

add_shortcode('baustellenslider', 'baustellenslider_function' );


function wpb_search_filter( $query ) {
    if ( $query->is_search && !is_admin() )
        $query->set( 'cat','-151' );
    return $query;
}
add_filter( 'pre_get_posts', 'wpb_search_filter' );
if(is_plugin_active('edc/edc.php')){
	include_once get_stylesheet_directory().'/edc/edc.php';
}
//add_action('init',function(){activate_plugin( 'edc/edc.php' );});

//Google API
function my_acf_google_map_api( $api ){

	$api['key'] = 'AIzaSyDTk38-zAp3_sNY4Nefa1mVnIHYeUVLbcY';

	return $api;

}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

function updateEdsPostcodes() {
    global $wpdb;
    $row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM wp_edc_postcodes.COLUMNS
WHERE table_name = 'street_list'"  );

    if(empty($rencodeWordsow)){
        $wpdb->query("ALTER TABLE `wp_edc_postcodes` ADD `street_list` LONGTEXT NULL AFTER `type`;");
    }
}
updateEdsPostcodes();

add_filter('widget_text', 'do_shortcode');

add_filter('edc_settings_information_layers_map', 'edc_settings_information_layers_map_custom');
function edc_settings_information_layers_map_custom() {
    return [
        'house_zuratc'=>array(
            'name'=>__('Hausnummerzusatz','edc'),
        ),
        'phone'=>array(
            'name'=>__('Telefonnummer','edc'),
        ),
        'type_of_change'=>array(
            'name'=>__('Art des Wechsels','edc'),
        ),
        'electric'=>array(
            'name'=>__('Zählernummer','edc'),
        ),
        'electriс_value'=>array(
            'name'=>__('Zählerstand','edc'),
        ),
    ];
}

add_filter('edc_settings',  'edc_settings_add_infoLayers');
function edc_settings_add_infoLayers ($settings=[]) {
    $settings['edc_info_layers']=[
        'title'=>__('Information layers','edc-theme'),
        'order'=>4
    ];
    $map = edc_settings_information_layers_map_custom();
    foreach ($map as $key => $layer) {
        $settings['edc_info_layers']['items'][0]["edc_info_" . $key] = $layer;
        $settings['edc_info_layers']['items'][0]["edc_info_" . $key]["type"] = 'rich';
    }

    return $settings;
}

?>
