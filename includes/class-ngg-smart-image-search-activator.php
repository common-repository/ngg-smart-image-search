<?php

/**
 * Fired during plugin activation
 *
 * @link       https://r-fotos.de/wordpress-plugins
 * @since      1.0.0
 *
 * @package    NGG_Smart_Image_Search
 * @subpackage NGG_Smart_Image_Search/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    NGG_Smart_Image_Search
 * @subpackage NGG_Smart_Image_Search/includes
 * @author     Harald R&ouml;h <hroeh@t-online.de>
 */
class NGG_Smart_Image_Search_Activator {

	/**
	 * Set default settings and create search widget landing page.
	 * Save id, slug, title of landing page in settings.
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		  global $table_prefix, $wpdb;

   	  $hr_SIS_options =  get_option( 'hr_SIS_settings' ) ;
   	  
   	  if ( null == $hr_SIS_options )                           
   	  {  //  no options yet defined, init options
  	     $hr_SIS_options = array(
  	         'search_page_id'      => 'not yet defined',
  	         'search_mode'         => 'extended',
  	         'enable_escape'       => '1',
  	         'enable_escape2'      => '0',
  	         'style_mode'          => '1',
  	         'border_size'         => '1',
  	         'border_color'        => '#f8f8ff',
  	         'border_color_hover'  => '#c8c8cf',
  	         'enable_color_picker' => '1',
  	         'show_notifications'  => 'both',
  	         'enable_uploader'     => '0'
  	     ) ;
  	     update_option( 'hr_SIS_settings', $hr_SIS_options );
  	     
  	  } else {  // options already defined, 
  	  	 // check if need for new options
  	  	 $hr_check_update = 0;
  	  	 
  	  	 // check if search page still exists or was deleted
  	  	 $hr_SIS_landing_page_id = $hr_SIS_options['search_page_id'] ;
  	  	 $hr_SIS_landing_page_status = get_post_status ( $hr_SIS_landing_page_id ) ;
  	  	 if ( is_bool( $hr_SIS_landing_page_status ) OR  $hr_SIS_landing_page_status <> 'publish' ) {
             $hr_SIS_options['search_page_id'] = 'not yet defined' ;
             $hr_check_update = 1 ;
         }
         
         // check if new options after version update are already initialized
         if ( !isset($hr_SIS_options['search_mode'] ) ) {
             $hr_SIS_options['search_mode'] = "extended" ;
             $hr_check_update = 1 ;
         }
         if ( !isset($hr_SIS_options['enable_escape'] ) ) {
             $hr_SIS_options['enable_escape'] = '1' ;
             $hr_check_update = 1 ;
         }
         if ( !isset($hr_SIS_options['enable_escape2'] ) ) {
             $hr_SIS_options['enable_escape2'] = '1' ;
             $hr_check_update = 1 ;
         }
         if ( !isset($hr_SIS_options['style_mode'] ) ) {
             $hr_SIS_options['style_mode'] = '1' ;        // use plugin specific searchbox style
             $hr_check_update = 1 ;
         }
         if ( !isset($hr_SIS_options['border_size'] ) ) {
             $hr_SIS_options['border_size'] = '1' ;   
             $hr_check_update = 1 ;
         }
         if ( !isset($hr_SIS_options['border_color'] ) ) {
             $hr_SIS_options['border_color'] = '#fafaff' ;  
             $hr_check_update = 1 ;
         }
         if ( !isset($hr_SIS_options['border_color_hover'] ) ) {
             $hr_SIS_options['border_color_hover'] = '#a8a8af' ;
             $hr_check_update = 1 ;
         }
         if ( !isset($hr_SIS_options['enable_color_picker'] ) ) {
             $hr_SIS_options['enable_color_picker'] = '1' ;
             $hr_check_update = 1 ;
         }
         if ( !isset($hr_SIS_options['enable_uploader'] ) ) {
             $hr_SIS_options['enable_uploader'] = '0' ;
             $hr_check_update = 1 ;
         }

         if ( $hr_check_update ) {
             update_option( 'hr_SIS_settings', $hr_SIS_options );
         }

  	  }

	}

}
