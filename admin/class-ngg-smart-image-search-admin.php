<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://r-fotos.de/wordpress-plugins
 * @since      1.0.0
 *
 * @package    NGG_Smart_Image_Search
 * @subpackage NGG_Smart_Image_Search/admin
 */
 
 define('hr_SIS_setting_mode', 'public');  

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    NGG_Smart_Image_Search
 * @subpackage NGG_Smart_Image_Search/admin
 * @author     Harald R&ouml;h <hroeh@t-online.de>
 */
class NGG_Smart_Image_Search_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in NGG_Smart_Image_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The NGG_Smart_Image_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
//wp_die($hook);

    if($hook != 'settings_page_ngg_smart_image_search') {
                return;
        }

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ngg-smart-image-search-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name, plugins_url( 'fonts/genericons/genericons.css', dirname(__FILE__) ), array(), $this->version, 'all' );

    // only activate color-picker if enabled by settings (some user experience conflicts if activated)
 		$hr_SIS_options = get_option( 'hr_SIS_settings');
    if ( isset($hr_SIS_options['enable_color_picker'] ) && $hr_SIS_options['enable_color_picker'] ) {
        wp_enqueue_style( 'wp-color-picker' ) ;
    }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in NGG_Smart_Image_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The NGG_Smart_Image_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

    if($hook != 'settings_page_ngg_smart_image_search') {
                return;
        }

   	wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ngg-smart-image-search-admin.js', array( 'jquery' ), $this->version, false );

 		$hr_SIS_options = get_option( 'hr_SIS_settings');
    if ( isset($hr_SIS_options['enable_color_picker'] ) && $hr_SIS_options['enable_color_picker'] ) {
        wp_enqueue_script( 'wp-color-picker' ) ;
    }
    
	}


  /**
   * Register the administration menu for this plugin into the WordPress Dashboard menu.
   *
   * @since    1.0.0
   */
   public function hr_SIS_add_admin_menu(  ) { 
  
      /*
       * Add a settings page for this plugin to the Settings menu.
       *
       *  add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
       *
       * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
       *
       *        Administration Menus: http://codex.wordpress.org/Administration_Menus
       *
       */
      add_options_page( 'NGG Smart Image Search', 'NGG Smart Image Search', 'manage_options', 'ngg_smart_image_search', 'hr_SIS_options_page' );
      
   }
 
 
   /**
   * Add settings action link to the plugins page.
   * Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
   * @since    1.0.0
   */
   public function hr_SIS_add_action_links( $links ) {
     $settings_link = array(
      '<a href="' . admin_url( 'options-general.php?page=' . 'ngg_smart_image_search' ) . '">' . __('Settings', $this->plugin_name) . '</a>',
     );
     return array_merge( $links, $settings_link );
   }  


	/**
	 * Register widget for search parameters.
	 * @since    1.0.0
	 */
	public function hr_SIS_widget_load() {
     register_widget( 'HR_SIS_Widget' );
	}

	/**
	 * Monitor post updates to catch changes for landing page slug and title.
	 * @since    1.0.0
	 */
	public function hr_SIS_check_post_update( $post_ID, $post_after, $post_before ) {
     
     $hr_SIS_options = get_option( 'hr_SIS_settings');
     
     if ( $post_ID == $hr_SIS_options['search_page_id'] ) {
     	   // update of landing page, check slug and title
     	   $hr_SIS_update = false ;
     	   if ( $post_before->post_name !== $post_after->post_name ) {
     	   	   $hr_SIS_options['search_page_slug'] = $post_after->post_name ;
     	   	   $hr_SIS_update = true ;
     	   }     	
     	   if ( $post_before->post_title !== $post_after->post_title ) {
     	   	   $hr_SIS_options['search_page_title'] = $post_after->post_title ;
     	   	   $hr_SIS_update = true ;
     	   }
     	   if ( $hr_SIS_update ) {
             update_option( 'hr_SIS_settings', $hr_SIS_options );
     	   }
     }
     return;
	}


   /**
   * Issue warnings, if either of the needed plugins NextGEN Gallery is not installed or not activated
   * @since    1.0.0
   */
   public function hr_SIS_check_plugins(  ) {
     	global $pagenow;
     	
     	$hr_SIS_options = get_option( 'hr_SIS_settings');
     	$hr_SIS_show_warnings = false;
     	
     	if ( isset( $hr_SIS_options['show_notifications'] ) ) {
    		 switch ( $hr_SIS_options['show_notifications'] ) {
     		 	  case "none":
     		 	      // show no warnings
     		 	      break;
     		 	  case "only option":
              	// show warnings only on own settings page
     	          if ( ($pagenow == 'options-general.php') && isset($_GET['page']) && ($_GET['page'] == 'ngg_smart_image_search') ) {
     	             $hr_SIS_show_warnings = true;
     	          }   	
     		        break;
     		 	  case "both":
               	// show warnings only on plugin page or on own settings page
     		 	      if ( ($pagenow == 'plugins.php') || 
     	             ( ($pagenow == 'options-general.php') && isset($_GET['page']) && ($_GET['page'] == 'ngg_smart_image_search') ) ) {
     	             $hr_SIS_show_warnings = true;
     	          }   	
     		 	      break;
     		 	  default:
     		 	      echo "-----> ", "Unexpected value for option show_notifications", "<br>";
     		 }
    	}
  	
//     	var_dump("Variable pagenow: ", $pagenow); echo "<br><br>";
//     	var_dump("Aufrufparameter _GET: ", $_GET); echo "<br><br>";

     	// show warnings only on plugin page or on own settings page
     	if ( $hr_SIS_show_warnings ) {
     		// check if nextgen gallery plugin is installed		
     		if (get_plugins('/nextgen-gallery')) {
     			
       		// check if nextgen gallery plugin is activated
       		if (!is_plugin_active('nextgen-gallery/nggallery.php')) {
            // nextgen gallery is not activated, issue warning
       			$hr_SIS_warning = '<div class="notice notice-warning is-dismissible"><p>';
       			$hr_SIS_warning.= '<b>' . __( 'Warning:', 'ngg-smart-image-search' ) . ' </b>';
       			$hr_SIS_warning.= sprintf( __( 'NGG Smart Image Search is an add-on for the %1$s WordPress plugin, but <b>%1$s is not <i>activated</i></b>.', 'ngg-smart-image-search' ), 'NextGEN Gallery' );
       			$hr_SIS_warning.= '<br /></p></div>';
       			
       			echo $hr_SIS_warning;
       		}	
    		} else {
          //  nextgen gallery is not installed, issue warning
       		$hr_SIS_warning = '<div class="notice notice-error"><p>';
       		$hr_SIS_warning.= '<b>' . __( 'Error:', 'ngg-smart-image-search' ) . ' </b>';
       		$hr_SIS_warning.= sprintf( __( 'NGG Smart Image Search is an add-on for the %1$s WordPress plugin, but <b>%1$s is not <i>installed</i></b>.', 'ngg-smart-image-search' ), 'NextGEN Gallery' );
     			$hr_SIS_warning.= '<br /></p></div>';
     			
     			echo $hr_SIS_warning;
     		}

     		// check if nextgen gallery pro plugin is installed		
     		if (get_plugins('/nextgen-gallery-pro')) {
      		// check if nextgen gallery pro plugin is activated
       		if (!is_plugin_active('nextgen-gallery-pro/ngg-pro.php')) {
            // nextgen gallery is not activated, issue warning
       		
       		}	
    		} else {
          //  nextgen gallery pro is not installed, issue warning
     		
     		}

    	}	     
   }  


  
  /**
   * Register sections and fields for the settings page of this plugin.
   *
   * @since    1.0.0
   */
  public function hr_SIS_settings_init(  ) { 
  
//    echo "............................................................... function settings init hr-SIS aufgerufen.<br>";
  
      /* register_setting( $option_group, $option_name, $sanitize_callback );   */
      /*  hint:  option_group  should match option_name  to avoid problems      */
  	register_setting( 'hr_SIS_pluginPage', 'hr_SIS_settings', 'hr_SIS_settings_sanitize_callback');
  
  	/*  add_settings_section( $id, $title, $callback, $page );  */
  	/*                              */
  	add_settings_section(
  		'hr_SIS_pluginPage_section', 
  		__( 'Landing Page for Widget Search Function:', 'ngg-smart-image-search' ), 
  		'hr_SIS_settings_section_callback', 
  		'hr_SIS_pluginPage'
  	);

  	add_settings_field( 
  		'hr_SIS_textfield_page_id', 
  		__( 'Search Landing Page', 'ngg-smart-image-search' ), 
  		'hr_SIS_textfield_page_id_render', 
  		'hr_SIS_pluginPage', 
  		'hr_SIS_pluginPage_section' 
  	);
  	
  	/*  add_settings_section( $id, $title, $callback, $page );  */
  	/*  second section for search mode specification                            */
  	add_settings_section(
  		'hr_SIS_pluginPage_section2', 
  		__( 'Search Mode and Styles:', 'ngg-smart-image-search' ), 
  		'hr_SIS_settings_section2_callback', 
  		'hr_SIS_pluginPage'
  	);
  
  	add_settings_field( 
  		'hr_SIS_radio_search_mode', 
  		__( 'Search Mode', 'ngg-smart-image-search' ), 
  		'hr_SIS_radio_search_mode_render', 
  		'hr_SIS_pluginPage', 
  		'hr_SIS_pluginPage_section2'
  	);
 
  	add_settings_field( 
  		'hr_SIS_radio_searchbox_style', 
  		__( 'Searchbox Style', 'ngg-smart-image-search' ), 
  		'hr_SIS_radio_searchbox_style_render', 
  		'hr_SIS_pluginPage', 
  		'hr_SIS_pluginPage_section2'
  	);
 
  	add_settings_field( 
  		'hr_SIS_input_thumbnail_borderstyle', 
  		__( 'Thumbnail Border Style', 'ngg-smart-image-search' ), 
  		'hr_SIS_input_thumbnail_borderstyle_render', 
  		'hr_SIS_pluginPage', 
  		'hr_SIS_pluginPage_section2'
  	);

  	
   	/*  add_settings_section( $id, $title, $callback, $page );  */
  	/*  third section for miscellaneous                            */
  	add_settings_section(
  		'hr_SIS_pluginPage_section4', 
  		__( 'Miscellaneous Options:', 'ngg-smart-image-search' ), 
  		'hr_SIS_settings_section4_callback', 
  		'hr_SIS_pluginPage'
  	);
  
  	add_settings_field( 
  		'hr_SIS_radio_show_notifications', 
  		__( 'Show notifications', 'ngg-smart-image-search' ), 
  		'hr_SIS_radio_show_notifications_render', 
  		'hr_SIS_pluginPage', 
  		'hr_SIS_pluginPage_section4'
  	);

  	add_settings_field( 
  		'hr_SIS_checkbox_enable_uploader', 
  		__( 'Enable uploader', 'ngg-smart-image-search' ), 
  		'hr_SIS_checkbox_enable_uploader_render', 
  		'hr_SIS_pluginPage', 
  		'hr_SIS_pluginPage_section4'
  	);
  	
  	return;
  }


  /**
   * Create search landing page.
   * needs localization
   * @since    1.0.0
   */
  public function hr_SIS_create_search_landing_page(  ) { 

      // check whether a search landing page is already defined by a previous activation
      $hr_SIS_options = get_option( 'hr_SIS_settings');
      
      if ( ( isset( $hr_SIS_options['search_page_id'] ) ) && ( $hr_SIS_options['search_page_id'] !== 'not yet defined' ) ) {
      	  // page exists, verify current slug and title, which might have been updated
    			global $table_prefix, $wpdb;
    		  $hr_SQL_get_page_data                 = 'SELECT post_title, post_name FROM ' . $table_prefix . 'posts  WHERE ID=%s' ;
          $hr_SIS_landing_page_data             = $wpdb->get_results($wpdb->prepare( $hr_SQL_get_page_data, $hr_SIS_options['search_page_id'] ) );

          $hr_SIS_title = $hr_SIS_landing_page_data[0]->post_title ;
          $hr_SIS_slug  = $hr_SIS_landing_page_data[0]->post_name ;
          
          if ( ( $hr_SIS_title <> $hr_SIS_options['search_page_title'] ) OR ( $hr_SIS_slug <> $hr_SIS_options['search_page_slug'] ) ) {
              // there was a modification, update settings
              $hr_SIS_options['search_page_slug']   = $hr_SIS_slug ;
              $hr_SIS_options['search_page_title']  = $hr_SIS_title ;
              update_option( 'hr_SIS_settings', $hr_SIS_options );
          }
      } else  {
          // generate a landing page for the image search widget        ngg-smart-image-search
          $hr_SIS_title    = __( 'Search Images in NextGEN Galleries', 'ngg-smart-image-search' );
          $hr_SIS_slug     = __( 'search-ngg-images', 'ngg-smart-image-search' );
          $hr_SIS_content  = "[hr_SIS_textbox usertype='logged_in']" .
                             __( 'Images will be searched in all galleries of this website.<br><br>The specified searchstring will be searched in the fields <em>Title</em>, <em>Description</em>, <em>Filename</em> and <em>Tags</em> of the respective images. The search is not case sensitive.', 'ngg-smart-image-search' ) .
                             "[/hr_SIS_textbox][hr_SIS_textbox usertype='public']" .
                             __( 'Images will be searched in all public galleries of this website.<br><br>The specified searchstring will be searched in the fields <em>Title</em>, <em>Description</em> and <em>Tags</em> of the respective images. The search is not case sensitive.', 'ngg-smart-image-search' ) .
                             "[/hr_SIS_textbox]<br><br>[hr_SIS_display_images]" ;
          
          $hr_SIS_post_id = wp_insert_post(
              array(
        						'post_author'		  =>	wp_get_current_user()->ID,
        						'post_content'    =>  $hr_SIS_content,
        						'post_title'	  	=>	$hr_SIS_title,
        						'post_excert' 		=>	'',
        						'post_status' 		=>	'publish',
        						'post_type'		    =>	'page',
        						'comment_status'	=>	'closed',
        						'ping_status'		  =>	'closed',
        						'post_name'       =>  $hr_SIS_slug
        					)
    			);
    			$hr_SIS_options['search_page_id'] = $hr_SIS_post_id ;
    			$hr_SIS_options['search_page_slug'] = $hr_SIS_slug ;
    			$hr_SIS_options['search_page_title'] = $hr_SIS_title ;
          update_option( 'hr_SIS_settings', $hr_SIS_options );
      }
 
      return;
	}		

}   // end of definition for class  NGG_Smart_Image_Search_Admin


  class HR_SIS_Widget extends WP_Widget {
  
      function __construct() {
	        parent::__construct(
	                // Base ID of this widget
	                'hrsis_widget',

                 	// Widget name will appear in backend UI
                	__('NGG Smart Image Search', 'ngg-smart-image-search'),

                	// Widget description
                	array( 'description' => __( 'Smart Image Search in NextGEN Galleries', 'ngg-smart-image-search' ), )
	        );
      }
      
      
      function widget ( $args, $instance ) {
          
/*          echo "check user logged in: ", ( is_user_logged_in() ) ? "true" : "false" ,
               "<br> visibility logged in: ", $instance['visibility_logged_in'],  
               "<br> visibility public: ", $instance['visibility_public'], "<br>";    
          var_dump( "instance ", $instance ) ; echo "<br>";
*/          
          // get user options from widget setup
          if ( ( ( is_user_logged_in() ) && ( $instance['visibility_logged_in'] == 1  ) )  ||
               ( (! is_user_logged_in() ) && ( $instance['visibility_public'] == 1 ) ) ) {
              // show this widget to user
          } else {
          	  // hide this widget to user
          	  return;
          }

//          var_dump("args bei widget ", $args); echo "<br><hr>";
          extract( $args );
          
          // output standard opening lines for widget
          // echo $before_widget;
          
          // output user title, if provided
          //if ( $title ) {
          //    echo $before_title . $title . $after_title;
          //}
          
          // generate search action box
          hr_SIS_output_searchform( $instance );
          
          //  output standard closing lines for widget
          // echo $after_widget;
          
      }
      
      function update ( $new_instance, $old_instance ) {
       
          $instance = $old_instance;
          $instance['visibility_public']       = ( ! empty( $new_instance['visibility_public'] ) ) ? strip_tags( $new_instance['visibility_public'] ) : '';
          $instance['visibility_logged_in']    = ( ! empty( $new_instance['visibility_logged_in'] ) ) ? strip_tags( $new_instance['visibility_logged_in'] ) : '';
          $instance['title']                   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
          $instance['show_title']              = ( ! empty( $new_instance['show_title'] ) ) ? strip_tags( $new_instance['show_title'] ) : '';
          $instance['placeholder_text']        = ( ! empty( $new_instance['placeholder_text'] ) ) ? strip_tags( $new_instance['placeholder_text'] ) : '';
          $instance['limit']                   = ( ! empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : '';
          $instance['searchsize']              = ( ! empty( $new_instance['searchsize'] ) ) ? strip_tags( $new_instance['searchsize'] ) : '';
          $instance['search_title']            = ( ! empty( $new_instance['search_title'] ) ) ? strip_tags( $new_instance['search_title'] ) : '';
          $instance['search_descr']            = ( ! empty( $new_instance['search_descr'] ) ) ? strip_tags( $new_instance['search_descr'] ) : '';
          $instance['search_file']             = ( ! empty( $new_instance['search_file'] ) ) ? strip_tags( $new_instance['search_file'] ) : '';
          $instance['search_tags']             = ( ! empty( $new_instance['search_tags'] ) ) ? strip_tags( $new_instance['search_tags'] ) : '';
          $instance['include_galleries']       = ( ! empty( $new_instance['include_galleries'] ) ) ? strip_tags( $new_instance['include_galleries'] ) : '';
          $instance['search_galleries']        = ( ! empty( $new_instance['search_galleries'] ) ) ? strip_tags( $new_instance['search_galleries'] ) : '';
          $instance['search_album']            = ( ! empty( $new_instance['search_album'] ) ) ? strip_tags( $new_instance['search_album'] ) : '';
          $instance['exclude_galleries']       = ( ! empty( $new_instance['exclude_galleries'] ) ) ? strip_tags( $new_instance['exclude_galleries'] ) : '';
          $instance['excluded_albums']         = ( ! empty( $new_instance['excluded_albums'] ) ) ? strip_tags( $new_instance['excluded_albums'] ) : '';
          $instance['excluded_galleries']      = ( ! empty( $new_instance['excluded_galleries'] ) ) ? strip_tags( $new_instance['excluded_galleries'] ) : '';
          $instance['list_pid']                = ( ! empty( $new_instance['list_pid'] ) ) ? strip_tags( $new_instance['list_pid'] ) : '';
          $instance['list_title']              = ( ! empty( $new_instance['list_title'] ) ) ? strip_tags( $new_instance['list_title'] ) : '';
          $instance['list_descr']              = ( ! empty( $new_instance['list_descr'] ) ) ? strip_tags( $new_instance['list_descr'] ) : '';
          $instance['list_date']               = ( ! empty( $new_instance['list_date'] ) ) ? strip_tags( $new_instance['list_date'] ) : '';
          $instance['list_gal_id']             = ( ! empty( $new_instance['list_gal_id'] ) ) ? strip_tags( $new_instance['list_gal_id'] ) : '';
          $instance['list_gal_name']           = ( ! empty( $new_instance['list_gal_name'] ) ) ? strip_tags( $new_instance['list_gal_name'] ) : '';
          $instance['list_gal_descr']          = ( ! empty( $new_instance['list_gal_descr'] ) ) ? strip_tags( $new_instance['list_gal_descr'] ) : '';
          $instance['list_uploader']           = ( ! empty( $new_instance['list_uploader'] ) ) ? strip_tags( $new_instance['list_uploader'] ) : '';
          $instance['list_tags']               = ( ! empty( $new_instance['list_tags'] ) ) ? strip_tags( $new_instance['list_tags'] ) : '';
          $instance['list_file']               = ( ! empty( $new_instance['list_file'] ) ) ? strip_tags( $new_instance['list_file'] ) : '';
          $instance['list_file_size']          = ( ! empty( $new_instance['list_file_size'] ) ) ? strip_tags( $new_instance['list_file_size'] ) : '';
          $instance['list_bu_size']            = ( ! empty( $new_instance['list_bu_size'] ) ) ? strip_tags( $new_instance['list_bu_size'] ) : '';
               
          return $instance;
      
      }
      
      function form ( $instance ) {
//          var_dump("para instance: ", $instance); echo "<br><hr>";
      
          // define default widget values for all parameters
          $defaults = array( 'visibility_public' => '1',           // 0/1 show widget in frontend for public users
                             'visibility_logged_in' => '1',        // 0/1 show widget on frontend for logged in users
                             'title' => '',                        // title in backend widget box
                             'show_title' => '0',                  // =1 will show title in widget
                             'placeholder_text' => __("Enter searchstring for images", "ngg-smart-image-search"),
                             'limit' => '30',                      // limit number of displayed images for search
                             'searchsize' => '3',                  // minimum length of search string
                             'search_title' => '1',                // 0/1 search in title of image
                             'search_descr' => '1',                // 0/1 search in description of image
                             'search_file' => '0',                 // 0/1 search in filename of image
                             'search_tags' => '1',                 // 0/1 search in tags of image
                             'include_galleries' => 'all',         // 'all' = search in all galleries
                                                                   // 'selected' = only search in explicitly listed albums and galleries
                             'search_galleries' => '',             // explicite search list of gallery id's, seperated by comma
                             'search_album' => '',                 // explicite search list of album id's, seperated by comma
                             'exclude_galleries' => 'none',        // 'none' = no exclusion for search defined
                                                                   // 'selected' = do not search in explicity lised galeries and albums
                             'excluded_albums' => '',              // explicite exclude list of album id's, seperated by comma
                             'excluded_galleries' => '',           // explicite exclude list of gallery id's, seperated by comma
                             'list_pid' => '0',                    // 0/1 list image id in search result list (pid = picture id)
                             'list_title' => '1',                  // 0/1 list title of image in search result list
                             'list_descr' => '1',                  // 0/1 list description of image in search result list
                             'list_date' => '0',                   // 0/1 list date of image in search result list
                             'list_file' => '0',                   // 0/1 list filenam of image in search result list
                             'list_file_size' => '0',              // 0/1 list filesize (bytes andpixel) of image in search result list
                             'list_bu_size' => '0',                // 0/1 list filesize of backup image in search result list
                             'list_uploader' => '0',               // 0/1 list user id of image uploader in search result list
                             'list_tags' => '1',                   // 0/1 list tags of image in search result list
                             'list_gal_id' => '0',                 // 0/1 list gallery id of image in search result list
                             'list_gal_name' => '1',               // 0/1 list gallery name / title of image in search result list
                             'list_gal_descr' => '0'               // 0/1 list gallery description of image in search result list
                            );
          
          $instance = wp_parse_args( (array) $instance, $defaults );
          if ( $instance['include_galleries'] == '' ) { $instance['include_galleries'] = "all" ; }
          if ( $instance['exclude_galleries'] == '' ) { $instance['exclude_galleries'] = "none" ; }
          $hr_SIS_instance = $instance ;

//          var_dump("widget settings: ", $hr_SIS_instance); echo "<br><hr>";

          ?>
          <p>
              <span style='padding-right:10px;'><?php _e('plugin visibility for', 'ngg-smart-image-search')?></span>
   	         	
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'visibility_public' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'visibility_public' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'visibility_public' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['visibility_public'], 1 ); ?>>
              <?php _e( 'public user', 'ngg-smart-image-search' ) ; ?></label></span>
   	         	
   	         	<label for="<?php echo $this->get_field_id( 'visibility_logged_in' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'visibility_logged_in' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'visibility_logged_in' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['visibility_logged_in'], 1 ); ?>>
              <?php _e( 'logged in user', 'ngg-smart-image-search' ) ; ?></label>
          </p>
          <p>
              <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'ngg-smart-image-search')?>:</label>
              <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />

   	         	<span style='padding-left:20px;'><label for="<?php echo $this->get_field_id( 'show_title' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'show_title' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'show_title' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['show_title'], 1 ); ?>>
              <?php _e( 'show title in widget', 'ngg-smart-image-search' ) ; ?></label>
          </p>
          <p>
              <label for="<?php echo $this->get_field_id( 'placeholder_text' ); ?>"><?php _e('Placeholder', 'ngg-smart-image-search')?>:</label>
              <input type="text" id="<?php echo $this->get_field_id( 'placeholder_text' ); ?>" name="<?php echo $this->get_field_name( 'placeholder_text' ); ?>" 
                     value="<?php echo $instance['placeholder_text']; ?>" style="width:75%;" />
          </p>
          
          <p>
              <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e('Limit number of listed images', 'ngg-smart-image-search')?>:</label>
              <input type="text" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" 
                     value="<?php echo $instance['limit']; ?>" style="width:10%;" />
          </p>
      
          <p>
              <label for="<?php echo $this->get_field_id( 'searchsize' ); ?>"><?php _e('Minimum size of search string', 'ngg-smart-image-search')?>:</label>
              <input type="text" id="<?php echo $this->get_field_id( 'searchsize' ); ?>" name="<?php echo $this->get_field_name( 'searchsize' ); ?>" 
                     value="<?php echo $instance['searchsize']; ?>" style="width:10%;" />
              <?php _e('characters long', 'ngg-smart-image-search')?>
          </p>
          <hr>
          <p>
              <?php _e('check searchstring in field', 'ngg-smart-image-search')?><br>
   	         	
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'search_title' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'search_title' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'search_title' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['search_title'], 1 ); ?>>
              <?php _e( 'Title', 'ngg-smart-image-search' ) ; ?></label></span>
   	         	
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'search_descr' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'search_descr' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'search_descr' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['search_descr'], 1 ); ?>>
              <?php _e( 'Description', 'ngg-smart-image-search' ) ; ?></label></span>
   	         	
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'search_file' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'search_file' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'search_file' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['search_file'], 1 ); ?>>
              <?php _e( 'Filename', 'ngg-smart-image-search' ) ; ?></label></span>
   	         	
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'search_tags' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'search_tags' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'search_tags' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['search_tags'], 1 ); ?>>
              <?php _e( 'Tags', 'ngg-smart-image-search' ) ; ?></label></span>
          </p>
          <hr>
          <p>
   	         	<label for="<?php echo $this->get_field_id( 'include_galleries' ).'_all'; ?>">
   	         	<input type='radio' id="<?php echo $this->get_field_id( 'include_galleries' ).'_all'; ?>" name="<?php echo $this->get_field_name( 'include_galleries' ); ?>" 
   	         	       value='all' <?php checked( $hr_SIS_instance['include_galleries'], 'all' ); ?>>
              <?php _e( 'search in all galleries', 'ngg-smart-image-search' ) ; ?></label><br>
              <label for="<?php echo $this->get_field_id( 'include_galleries' ).'_selected'; ?>">
            	<input type='radio' id="<?php echo $this->get_field_id( 'include_galleries' ).'_selected'; ?>" name="<?php echo $this->get_field_name( 'include_galleries' ); ?>" 
            	       value='selected' <?php checked( $hr_SIS_instance['include_galleries'], 'selected' ); ?>>
              <?php _e( 'only search in specified albums and galleries', 'ngg-smart-image-search' ) ; ?></label><br>
              <span style='padding-right:11px; padding-left:25px;'><label for="<?php echo $this->get_field_id( 'search_album' ); ?>"><?php _e('Search albums', 'ngg-smart-image-search')?>:</label></span>
              <input type="text" id="<?php echo $this->get_field_id( 'search_album' ); ?>" name="<?php echo $this->get_field_name( 'search_album' ); ?>" 
                     value="<?php echo $instance['search_album']; ?>" style="width:60%;" /><br>
              <span style='padding-right:5px; padding-left:25px;'><label for="<?php echo $this->get_field_id( 'search_galleries' ); ?>"><?php _e('Search galleries', 'ngg-smart-image-search')?>:</label></span>
              <input type="text" id="<?php echo $this->get_field_id( 'search_galleries' ); ?>" name="<?php echo $this->get_field_name( 'search_galleries' ); ?>" 
                     value="<?php echo $instance['search_galleries']; ?>" style="width:60%;" /><br>
              <span style='padding-left:125px;'><small><?php _e( 'comma separated list of gallery and album IDs', 'ngg-smart-image-search' ) ; ?></small></span>
          </p>
          <hr>
          <p>
   	         	<label for="<?php echo $this->get_field_id( 'exclude_galleries' ).'_all'; ?>">
   	         	<input type='radio' id="<?php echo $this->get_field_id( 'exclude_galleries' ).'_all'; ?>" name="<?php echo $this->get_field_name( 'exclude_galleries' ); ?>" 
   	         	       value='none' <?php checked( $hr_SIS_instance['exclude_galleries'], 'none' ); ?>>
              <?php _e( 'exclude no gallery from search', 'ngg-smart-image-search' ) ; ?></label><br>

              <label for="<?php echo $this->get_field_id( 'exclude_galleries' ).'_selected'; ?>">
            	<input type='radio' id="<?php echo $this->get_field_id( 'exclude_galleries' ).'_selected'; ?>" name="<?php echo $this->get_field_name( 'exclude_galleries' ); ?>" 
            	       value='selected' <?php checked( $hr_SIS_instance['exclude_galleries'], 'selected' ); ?>>
              <?php _e( 'exclude specified albums and galleries from search', 'ngg-smart-image-search' ) ; ?></label><br>
              <span style='padding-right:11px; padding-left:25px;'><label for="<?php echo $this->get_field_id( 'excluded_albums' ); ?>"><?php _e('Exclude albums', 'ngg-smart-image-search')?>:</label></span>
              <input type="text" id="<?php echo $this->get_field_id( 'excluded_albums' ); ?>" name="<?php echo $this->get_field_name( 'excluded_albums' ); ?>" 
                     value="<?php echo $instance['excluded_albums']; ?>" style="width:60%;" /><br>
              <span style='padding-right:5px; padding-left:25px;'><label for="<?php echo $this->get_field_id( 'excluded_galleries' ); ?>"><?php _e('Exclude galleries', 'ngg-smart-image-search')?>:</label></span>
              <input type="text" id="<?php echo $this->get_field_id( 'excluded_galleries' ); ?>" name="<?php echo $this->get_field_name( 'excluded_galleries' ); ?>" 
                     value="<?php echo $instance['excluded_galleries']; ?>" style="width:60%;" /><br>
              <span style='padding-left:150px;'><small><?php _e( 'comma separated list of gallery and album IDs', 'ngg-smart-image-search' ) ; ?></small></span>
         </p>
          <hr>
          <p>
   	         	<?php _e( 'Result list will describe images by', 'ngg-smart-image-search' ) ; ?><br>
   	         	
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_pid' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_pid' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_pid' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_pid'], 1 ); ?>>
              <?php _e( 'Image ID', 'ngg-smart-image-search' ) ; ?></label></span>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_title' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_title' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_title' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_title'], 1 ); ?>>
              <?php _e( 'Title', 'ngg-smart-image-search' ) ; ?></label></span>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_descr' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_descr' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_descr' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_descr'], 1 ); ?>>
              <?php _e( 'Description', 'ngg-smart-image-search' ) ; ?></label></span>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_date' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_date' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_date' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_date'], 1 ); ?>>
              <?php _e( 'Date', 'ngg-smart-image-search' ) ; ?></label></span><br>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_file' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_file' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_file' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_file'], 1 ); ?>>
              <?php _e( 'Filename', 'ngg-smart-image-search' ) ; ?></label></span>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_file_size' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_file_size' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_file_size' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_file_size'], 1 ); ?>>
              <?php _e( 'File Size', 'ngg-smart-image-search' ) ; ?></label></span>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_bu_size' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_bu_size' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_bu_size' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_bu_size'], 1 ); ?>>
              <?php _e( 'Backup Size', 'ngg-smart-image-search' ) ; ?></label></span><br>

              <?php  // check if uploader setting option is enabled, also widget option is included here
                     $hr_SIS_options = get_option( 'hr_SIS_settings');
                     if ( $hr_SIS_options['enable_uploader'] == 1 ) { ?>
               	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_uploader' ).'_1'; ?>">
              	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_uploader' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_uploader' ); ?>" 
              	                 value='1' <?php checked( $hr_SIS_instance['list_uploader'], 1 ); ?>>
                          <?php _e( 'Uploader', 'ngg-smart-image-search' ) ; ?></label></span> <?php
                     }  ?>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_tags' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_tags' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_tags' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_tags'], 1 ); ?>>
              <?php _e( 'Tags', 'ngg-smart-image-search' ) ; ?></label></span><br>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_gal_id' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_gal_id' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_gal_id' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_gal_id'], 1 ); ?>>
              <?php _e( 'Gallery ID', 'ngg-smart-image-search' ) ; ?></label></span>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_gal_name' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_gal_name' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_gal_name' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_gal_name'], 1 ); ?>>
              <?php _e( 'Gallery Name', 'ngg-smart-image-search' ) ; ?></label></span>
              
   	         	<span style='padding-right:10px;'><label for="<?php echo $this->get_field_id( 'list_gal_descr' ).'_1'; ?>">
  	          <input type='checkbox' id="<?php echo $this->get_field_id( 'list_gal_descr' ).'_1'; ?>" name="<?php echo $this->get_field_name( 'list_gal_descr' ); ?>" 
  	                 value='1' <?php checked( $hr_SIS_instance['list_gal_descr'], 1 ); ?>>
              <?php _e( 'Gall.Description', 'ngg-smart-image-search' ) ; ?></label></span>
              
          </p>
              
          
          <?php
//          var_dump("instance: ", $instance); echo "<br><hr>";


      }
      

  }    // end of definition for class  HR_SIS_Widget

 /**
 * Layout of search widget on frontend site
 *   consists of a simple searchbox
 *   parameters passed by a serialized hidden field
                <label class="" for="search-text">
                 		<input type="text" name="hr_SIS_search_text" class="hr_searchfield" placeholder="<?php echo $instance['placeholder_text']; ?>" />
                </label>

                 		<input type="text" name="hr_SIS_search_text" class="hr_searchfield" placeholder="<?php echo $instance['placeholder_text']; ?>" />


 * @since    1.0.0
 */
function hr_SIS_output_searchform( $instance ) {

  
//  var_dump("instance:", $instance); echo "<br><hr>";
   
//  $hr_SIS_uebergabe = serialize( $instance );  
  $hr_SIS_uebergabe = json_encode( $instance );  
  $hr_SIS_uebergabe = str_replace( '"', '_#_', $hr_SIS_uebergabe) ;
  
  // retrieve current slug for search page
  $hr_SIS_options = get_option( 'hr_SIS_settings' );
  $hr_SIS_currentURL = get_option("siteurl") . "/" . $hr_SIS_options['search_page_slug'] ;
  $hr_SIS_stylemode = $hr_SIS_options['style_mode'] ;
//  var_dump("setings:", $hr_SIS_options); echo "<br><hr>";

    
    if ( $hr_SIS_stylemode ) {
    	  // own class definitions
    	  $hr_SIS_widget_title  = "hr-widget-title widget-title" ;
    	  $hr_SIS_search_form   = "hr-searchform-box";
    	  $hr_SIS_search_field  = "hr_searchfield";
    	  $hr_SIS_search_submit = "hr_searchsubmit";
    	  $hr_SIS_search_icon   = "hr-searchicon";
    } else {
    	  // general class definitions
    	  $hr_SIS_widget_title  = "widget-title" ;
    	  $hr_SIS_search_form   = "search-form" ;
    	  $hr_SIS_search_field  = "search-field" ;
    	  $hr_SIS_search_submit = "search-submit" ;
    	  $hr_SIS_search_icon   = "icon-search" ;
    }

  ?>  
     <div class="widget">
        <aside class="" >
            <!--BEGIN #searchform NGG Smart Image Search -->
            <?php if ( $instance['show_title'] ) { ?>
            <h2 class="<?php echo $hr_SIS_widget_title; ?>" ><?php echo esc_html($instance['title']); ?></h2>
            <?php } ?>
            <form action="<?php  echo $hr_SIS_currentURL ; ?>" method="post" class="<?php echo $hr_SIS_search_form; ?>">
               	<input type="hidden" name="hr_SIS_source" value="widget" >
               	<input type="hidden" name="hr_SIS_search_settings" value="<?php echo $hr_SIS_uebergabe; ?>"  >
                 		<input type="text" name="hr_SIS_search_text" class="<?php echo $hr_SIS_search_field; ?>" placeholder="<?php echo $instance['placeholder_text']; ?>" />

            <?php if ( $hr_SIS_stylemode ) { ?>
    			      <button type="submit" class="<?php echo $hr_SIS_search_submit; ?>" >
    			      	  <span class="<?php echo $hr_SIS_search_icon; ?>" ></span> 
    			      </button>
            <?php } else {?>
                <button type="submit" class="<?php echo $hr_SIS_search_submit; ?>">
              	  <svg class="icon <?php echo $hr_SIS_search_icon; ?>" aria-hidden="true" role="img"  > 
              	    <use href="#icon-search" xlink:href="#icon-search"></use> 
              	  </svg>
               	</button>

            <?php } ?>

             </form>
             <!--END #searchform NGG Smart Image Search -->
        </aside>
        <div class="clearfix"></div>
     </div>
  <?php
  return ;		
}

  /**
   * Render the settings page for this plugin.
   *
   * @since    1.0.0
   */
  function hr_SIS_options_page(  ) { 
  		$hr_SIS_options = get_option( 'hr_SIS_settings');
  		//var_dump($hr_SIS_options); echo "<br>";
 		  //var_dump( get_current_screen() ); echo "<br>";

  	?>
  	<form action='options.php' method='post'>
  		<h1 class='hr_SIS_h1'><?php echo __( 'Settings', 'ngg-smart-image-search' ) . ' &gt; ' . __( 'NGG Smart Image Search', 'ngg-smart-image-search' ) ;
  		   if ( hr_SIS_setting_mode == 'modified' ) { 
  		   	  echo " &gt; ", __( 'extended mode', 'ngg-smart-image-search' ) ;
  		   }  ?></h1><?php

    		/*  settings_fields( $option_group ), must be called inside of the form tag  */
    		/*  This should match the group name used in register_setting()  */
  		settings_fields( 'hr_SIS_pluginPage' );
  
    		/*  do_settings_sections( $page );  with slug name of page */
    		/*  print out all setting sections */
  		do_settings_sections( 'hr_SIS_pluginPage' );

  		?><span style='padding-right:50px;'><?php
  		submit_button( __( 'Save Changes', 'ngg-smart-image-search' ), 'primary', 'submit' , false);
  		?></span>
  	</form>
  	<?php
  }


  /**
   * define callback functions to build up the setting page
   *
   * @since    1.0.0
   */
  function hr_SIS_settings_section_callback(  ) { 
  	echo "<p class='hr_SIS_p'>", __( 'Widget Search landing page will be automatically generated during plugin activation and initialization.', 'ngg-smart-image-search' ), "</p>";
  }
  
  function hr_SIS_textfield_page_id_render(  ) { 
  	$hr_SIS_options = get_option( 'hr_SIS_settings');
  	echo __( 'page id', 'ngg-smart-image-search' ) ;?>
  	<input type='text' name='hr_SIS_settings[search_page_id]' value='<?php echo $hr_SIS_options['search_page_id']; ?>' style='width:70px;' readonly  >
    <span style='padding-left:40px;'><?php echo __( 'slug', 'ngg-smart-image-search' ) ;?> </span> 
  	<input type='text' name='hr_SIS_settings[search_page_slug]' value='<?php echo $hr_SIS_options['search_page_slug']; ?>' style='width:200px;' readonly  >
    <span style='padding-left:40px;'><?php echo __( 'title', 'ngg-smart-image-search' ) ;?> </span> 
  	<input type='text' name='hr_SIS_settings[search_page_title]' value='<?php echo $hr_SIS_options['search_page_title']; ?>' style='width:300px;' readonly >
  	<span style='padding-left:40px;'><a class='post-edit-link' href='<?php echo get_option('siteurl'); ?>/wp-admin/post.php?post=<?php echo $hr_SIS_options['search_page_id']; ?>&action=edit' ><em>page_edit_link</em></a></span>
    <?php
  }

  function hr_SIS_settings_section2_callback(  ) { 
  	echo "<p class='hr_SIS_p'>", __( 'Define how the entered search string will be interpreted by the plugin and how searchbox and thumbnails will be styled.', 'ngg-smart-image-search' ), "</p>";
  }

  function hr_SIS_radio_search_mode_render(  ) { 
  	$hr_SIS_options = get_option( 'hr_SIS_settings');
//  	var_dump("settings ", $hr_SIS_options);echo "<br><hr>";
  	?>
  	<span style='display:inline-block;width:175px;'><input type='radio' name='hr_SIS_settings[search_mode]' value='basic' <?php checked( $hr_SIS_options['search_mode'], 'basic' ); ?>>
    <?php echo __( 'basic search', 'ngg-smart-image-search' ) ; ?></span>
    <span style='padding:0px;'><?php echo __( 'Basic search will search for all search items in the input search string, which are seperated by blanks (white space).', 'ngg-smart-image-search' ) ; ?></span><br>
    <span style='padding-left:179px;'><?php echo __( 'Selected images must satisfy at least one of these search items (OR condition).', 'ngg-smart-image-search' ) ; ?></span><br>
  	<span style='display:inline-block;width:175px;padding-left:40px;'><input type='checkbox' name='hr_SIS_settings[enable_escape]' value='1' <?php checked( $hr_SIS_options['enable_escape'], 1 ); ?>>
    <?php echo __( 'escape +', 'ngg-smart-image-search' ) ; ?></span>
    <span style='padding-right:0px;'><?php echo __( 'enable escape character for dynamic switch to extended search mode', 'ngg-smart-image-search' ) ;?> </span><br><br> 

   	<span style='display:inline-block;width:175px;'><input type='radio' name='hr_SIS_settings[search_mode]' value='extended' <?php checked( $hr_SIS_options['search_mode'], 'extended' ); ?>>
    <?php echo __( 'extended search', 'ngg-smart-image-search' ) ; ?></span>
    <?php echo __( 'Extended search enables multi-key search items and special search terms in the input search string.', 'ngg-smart-image-search' ) ; ?><br>
    <span style='padding-left:179px;'><?php echo __( 'Selected images must satisfy all of these search items (AND condition).', 'ngg-smart-image-search' ) ; ?></span><br>
  	<span style='display:inline-block;width:175px;padding-left:40px;'><input type='checkbox' name='hr_SIS_settings[enable_escape2]' value='1' <?php checked( $hr_SIS_options['enable_escape2'], 1 ); ?>>
    <?php echo __( 'escape -', 'ngg-smart-image-search' ) ; ?></span>
    <span style='padding-right:0px;'><?php echo __( 'enable escape character for dynamic switch to basic search mode', 'ngg-smart-image-search' ) ;?> </span><br><br> 
  	<?php
  }
 
  function hr_SIS_radio_searchbox_style_render(  ) { 
  	$hr_SIS_options = get_option( 'hr_SIS_settings');
    ?>
  	<span style='padding-right:40px;'><input type='radio' name='hr_SIS_settings[style_mode]' value='1' <?php checked( $hr_SIS_options['style_mode'], '1' ); ?>>
    <?php echo __( 'plugin specific style', 'ngg-smart-image-search' ) ; ?></span>
   	<span style='padding-right:40px;'><input type='radio' name='hr_SIS_settings[style_mode]' value='0' <?php checked( $hr_SIS_options['style_mode'], '0' ); ?>>
    <?php echo __( 'general theme dependent style', 'ngg-smart-image-search' ) ; ?></span><br>
    <?php echo __( 'CSS classnames for the seachbox definition will be plugin-specific or more wordpress generic adopting theme styling.', 'ngg-smart-image-search' ) ; ?>
    
    <?php
  }
 
  function hr_SIS_input_thumbnail_borderstyle_render(  ) { 
  	$hr_SIS_options = get_option( 'hr_SIS_settings');
  	if ( !isset($hr_SIS_options['enable_color_picker'] ) ) {
  		$hr_SIS_options['enable_color_picker'] = 0 ;
  		update_option( 'hr_SIS_settings', $hr_SIS_options );
  	}
  	 	
  	echo __( 'These settings only apply to thumbnails used in display type si (single images), li (linked images) and at (advanced thumbnails).', 'ngg-smart-image-search' ), "<br>" ;
  	echo __( 'All other thumbnails are displayed by NextGEN Gallery code and use their own applicable settings.', 'ngg-smart-image-search' ),"<br>" ;
  	echo __( 'border size', 'ngg-smart-image-search' ) ;
  	?>
  	<input type='text' name='hr_SIS_settings[border_size]' value='<?php echo $hr_SIS_options['border_size']; ?>' style='width:35px;' > px  
    <span style='padding-left:20px;'><?php echo __( 'Border size must be specified as an integer in the range 0 to 20 and will be interpreted as pixel.', 'ngg-smart-image-search' ) ;?></span><br>

    <?php
    if ( isset($hr_SIS_options['enable_color_picker'] ) && $hr_SIS_options['enable_color_picker'] ) {
    ?>
        <div style='float:left; min-width:200px;'>
          <span style='display:inline-block;'><?php echo __( 'border color', 'ngg-smart-image-search' ) ;?> </span> 
      	  <input type='text' name='hr_SIS_settings[border_color]' value='<?php echo $hr_SIS_options['border_color']; ?>' class='cpa-color-picker' >
        </div>
        <div style='float:left; min-width:200px;' >
          <span style='display:inline-block;'><?php echo __( 'border color hover', 'ngg-smart-image-search' ) ;?> </span> 
      	  <input type='text' name='hr_SIS_settings[border_color_hover]' value='<?php echo $hr_SIS_options['border_color_hover']; ?>' class='cpa-color-picker' >
        </div>
        <div style='float:left;'>
          <br><?php echo __( 'Select color in color picker or directly provide a hex color code.', 'ngg-smart-image-search' ) ; ?> 
        </div>
    
      	<br><br><input type='checkbox' name='hr_SIS_settings[enable_color_picker]' value='1' <?php checked( $hr_SIS_options['enable_color_picker'], 1 ); ?>>
        <span style='padding-right:40px;'><?php echo __( 'enable color picker for color selection (disable for debug)', 'ngg-smart-image-search' ) ;?> </span> 
    <?php
    } else {   // no color picker selected because of conflicts with other plugins for javascript handling
    ?>
        <div style='float:left; min-width:200px;'>
          <span style='display:inline-block;'><?php echo __( 'border color', 'ngg-smart-image-search' ) ;?> </span> 
      	  <input type='text' name='hr_SIS_settings[border_color]' value='<?php echo $hr_SIS_options['border_color']; ?>' style='width:80px; >
        </div>
        <div style='float:left; min-width:200px;' >
          <span style='display:inline-block;margin-left:40px;'><?php echo __( 'border color hover', 'ngg-smart-image-search' ) ;?> </span> 
      	  <input type='text' name='hr_SIS_settings[border_color_hover]' value='<?php echo $hr_SIS_options['border_color_hover']; ?>' style='width:80px; margin-right:40px;' >
        </div>
        <div style='float:left;'>
          <?php echo __( 'Directly input a hex color code.', 'ngg-smart-image-search' ) ; ?> 
        </div><br><br>
        <div  >
        	<input type='checkbox' name='hr_SIS_settings[enable_color_picker]' value='1' <?php checked( $hr_SIS_options['enable_color_picker'], 1 ); ?>>
          <span style='padding-right:40px;'><?php echo __( 'enable color picker for color selection (disable for debug)', 'ngg-smart-image-search' ) ;?> </span> 
        </div>
    <?php
    }
  }
 
  function hr_SIS_settings_section4_callback(  ) { 
  	echo "<p class='hr_SIS_p'>", __( 'Miscellaneous further settings for notifications and custom field.', 'ngg-smart-image-search' ), "</p>";
  }

  function hr_SIS_radio_show_notifications_render(  ) { 
  	$hr_SIS_options = get_option( 'hr_SIS_settings');
  	?>
  	<span style='padding-right:40px;'><input type='radio' name='hr_SIS_settings[show_notifications]' value='both' <?php checked( $hr_SIS_options['show_notifications'], 'both' ); ?>>
    <?php echo __( 'on plugin page and on settings page', 'ngg-smart-image-search' ) ; ?></span>
   	<span style='padding-right:40px;'><input type='radio' name='hr_SIS_settings[show_notifications]' value='only option' <?php checked( $hr_SIS_options['show_notifications'], 'only option' ); ?>>
    <?php echo __( 'only on settings page', 'ngg-smart-image-search' ) ; ?></span>
  	<input type='radio' name='hr_SIS_settings[show_notifications]' value='none' <?php checked( $hr_SIS_options['show_notifications'], 'none' ); ?>>
    <?php echo __( 'show no notification', 'ngg-smart-image-search' ) ; ?><br>
    <?php echo __( 'Plugin sets notifications if the needed plugin (NextGEN Gallery) is not installed or activated.', 'ngg-smart-image-search' ) ; ?>
  	<?php
  }

  function hr_SIS_checkbox_enable_uploader_render(  ) { 
  	global $table_prefix, $wpdb;
  	$hr_SIS_SQL_check_column = 'SHOW COLUMNS FROM ' . $table_prefix . 'ngg_pictures LIKE %s ' ;
    $hr_SIS_check_uploader  = $wpdb->get_results($wpdb->prepare( $hr_SIS_SQL_check_column, 'uploader' ) );
//    var_dump ( $hr_SIS_check_uploader ); echo "<br>";
    if ( count($hr_SIS_check_uploader) == 1 ) {
        $hr_SIS_uploader_status = __( 'Field <em>uploader</em> exists, option enabled.', 'ngg-smart-image-search' ) ;
        $hr_SIS_marker = 1 ;
    } else {
        $hr_SIS_uploader_status = __( 'Field <em>uploader</em> does not exist, option disabled.', 'ngg-smart-image-search' ) ;
        $hr_SIS_marker = 0 ;
    }
    
    $hr_SIS_options = get_option( 'hr_SIS_settings');
  	if ( !isset($hr_SIS_options['enable_uploader'] ) ) {
  		$hr_SIS_options['enable_uploader'] = 0 ;
  		update_option( 'hr_SIS_settings', $hr_SIS_options );
  	}

  	?>
  	<input type='checkbox' name='hr_SIS_settings[enable_uploader]' value='<?php echo $hr_SIS_marker ; ?>' <?php checked( $hr_SIS_options['enable_uploader'], 1 ); ?>>
    <span style='padding-right:40px;'><?php echo __( 'enable listing of uploader user-id', 'ngg-smart-image-search' ) ;?> </span> 
    <?php
     echo  "(" . $hr_SIS_uploader_status . ")" ;
  }
  		 

function hr_SIS_settings_sanitize_callback( $fields ) {

   $valid_fields = $fields;
 
   // Validate Border size as integer in the range 0 to 20
    $bordersize = trim( $fields['border_size'] );
    $bordersize = strip_tags( stripslashes( $bordersize ) );
    // Check if is a valid hex color
    if( FALSE === hr_SIS_check_integer( $bordersize ) ) {
        // Set the error message
        add_settings_error( 'hr_SIS_settings', 'integer_range_error', 'Border size must be integer and in the range 0 to 20, was reset to default', 'error' ); // $setting, $code, $message, $type
        // Get the previous valid value
        $valid_fields['border_size'] = "5";   // reset to default
    } else {
        $valid_fields['border_size'] = $bordersize ;
    }

    // Validate Border Background Color
    $background = trim( $fields['border_color'] );
    $background = strip_tags( stripslashes( $background ) );
 
    // Check if is a valid hex color
    if( FALSE === hr_SIS_check_color( $background ) ) {
        // Set the error message
        add_settings_error( 'hr_SIS_settings', 'cpa_bg_error', 'Invalid color for Thumbnail Border Color reset to default', 'error' ); // $setting, $code, $message, $type
        // Get the previous valid value
        $valid_fields['border_color'] = "#fafaff";   // reset to default
    } else {
        $valid_fields['border_color'] = $background ;
    }

    // Validate Border Background Hover Color
    $background = trim( $fields['border_color_hover'] );
    $background = strip_tags( stripslashes( $background ) );

    // Check if is a valid hex color
    if( FALSE === hr_SIS_check_color( $background ) ) {
        // Set the error message
        add_settings_error( 'hr_SIS_settings', 'cpa_bg_error', 'Invalid color for Thumbnail Border Hover Color reset to default', 'error' ); // $setting, $code, $message, $type
        // Get the previous valid value
        $valid_fields['border_color_hover'] = "#a8a8af";   // reset to default
    } else {
        $valid_fields['border_color_hover'] = $background ;
    }

    return $valid_fields;
}

/**
 * Function that will check if value is a valid HEX color.
 */
function hr_SIS_check_color( $value ) {
 
    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
        return true;
    }
 
    return false;
}
function hr_SIS_check_integer( $value ) {
 
    if ( preg_match( '/^\d+$/', $value ) ) { // maximum 4 digits for general use

        if ( $value > 20 ) { return false ; } ;

        return true;
    }
 
    return false;
}


function def_svg_icon() {
echo
'<svg style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
<defs>
<symbol id="icon-search" viewBox="0 0 30 32" preserveAspectRatio="none" >
<path class="path1" d="M20.571 14.857q0-3.304-2.348-5.652t-5.652-2.348-5.652 2.348-2.348 5.652 2.348 5.652 5.652 2.348 5.652-2.348 2.348-5.652zM29.714 29.714q0 0.929-0.679 1.607t-1.607 0.679q-0.964 0-1.607-0.679l-6.125-6.107q-3.196 2.214-7.125 2.214-2.554 0-4.884-0.991t-4.018-2.679-2.679-4.018-0.991-4.884 0.991-4.884 2.679-4.018 4.018-2.679 4.884-0.991 4.884 0.991 4.018 2.679 2.679 4.018 0.991 4.884q0 3.929-2.214 7.125l6.125 6.125q0.661 0.661 0.661 1.607z"></path>
</symbol>
</defs>
</svg>
' ;

}
add_action( 'wp_footer', 'def_svg_icon' );


/**
 * Make sure the function does not exist before defining it
 /
if( ! function_exists( 'hr_remove_class_filter' ) ){
	/**
	 * Remove Class Filter Without Access to Class Object
	 *
	 * In order to use the core WordPress remove_filter() on a filter added with the callback
	 * to a class, you either have to have access to that class object, or it has to be a call
	 * to a static method.  This method allows you to remove filters with a callback to a class
	 * you don't have access to.
	 *
	 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
	 * Updated 2-27-2017 to use internal WordPress removal for 4.7+ (to prevent PHP warnings output)
	 *
	 * @param string $tag         Filter to remove
	 * @param string $class_name  Class name for the filter's callback
	 * @param string $method_name Method name for the filter's callback
	 * @param int    $priority    Priority of the filter (default 10)
	 *
	 * @return bool Whether the function is removed.
	 *
	function hr_remove_class_filter( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		global $wp_filter;
		// Check that filter actually exists first
		if ( ! isset( $wp_filter[ $tag ] ) ) {
			return FALSE;
		}
		/**
		 * If filter config is an object, means we're using WordPress 4.7+ and the config is no longer
		 * a simple array, rather it is an object that implements the ArrayAccess interface.
		 *
		 * To be backwards compatible, we set $callbacks equal to the correct array as a reference (so $wp_filter is updated)
		 *
		 * @see https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
		 /
		if ( is_object( $wp_filter[ $tag ] ) && isset( $wp_filter[ $tag ]->callbacks ) ) {
			// Create $fob object from filter tag, to use below
			$fob       = $wp_filter[ $tag ];
			$callbacks = &$wp_filter[ $tag ]->callbacks;
		} else {
			$callbacks = &$wp_filter[ $tag ];
		}
		// Exit if there aren't any callbacks for specified priority
		if ( ! isset( $callbacks[ $priority ] ) || empty( $callbacks[ $priority ] ) ) {
			return FALSE;
		}
		// Loop through each filter for the specified priority, looking for our class & method
		foreach ( (array) $callbacks[ $priority ] as $filter_id => $filter ) {
			// Filter should always be an array - array( $this, 'method' ), if not goto next
			if ( ! isset( $filter['function'] ) || ! is_array( $filter['function'] ) ) {
				continue;
			}
			// If first value in array is not an object, it can't be a class
			if ( ! is_object( $filter['function'][0] ) ) {
				continue;
			}
			// Method doesn't match the one we're looking for, goto next
			if ( $filter['function'][1] !== $method_name ) {
				continue;
			}
			// Method matched, now let's check the Class
			if ( get_class( $filter['function'][0] ) === $class_name ) {
				// WordPress 4.7+ use core remove_filter() since we found the class object
				if ( isset( $fob ) ) {
					// Handles removing filter, reseting callback priority keys mid-iteration, etc.
					$fob->remove_filter( $tag, $filter['function'], $priority );
				} else {
					// Use legacy removal process (pre 4.7)
					unset( $callbacks[ $priority ][ $filter_id ] );
					// and if it was the only filter in that priority, unset that priority
					if ( empty( $callbacks[ $priority ] ) ) {
						unset( $callbacks[ $priority ] );
					}
					// and if the only filter for that tag, set the tag to an empty array
					if ( empty( $callbacks ) ) {
						$callbacks = array();
					}
					// Remove this filter from merged_filters, which specifies if filters have been sorted
					unset( $GLOBALS['merged_filters'][ $tag ] );
				}
				return TRUE;
			}
		}
		return FALSE;
	}
}
 /**
 * Make sure the function does not exist before defining it
 ***
if( ! function_exists( 'hr_remove_class_action9') ){
	/**
	 * Remove Class Action Without Access to Class Object
	 *
	 * In order to use the core WordPress remove_action() on an action added with the callback
	 * to a class, you either have to have access to that class object, or it has to be a call
	 * to a static method.  This method allows you to remove actions with a callback to a class
	 * you don't have access to.
	 *
	 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
	 *
	 * @param string $tag         Action to remove
	 * @param string $class_name  Class name for the action's callback
	 * @param string $method_name Method name for the action's callback
	 * @param int    $priority    Priority of the action (default 10)
	 *
	 * @return bool               Whether the function is removed.
	 *
	function hr_remove_class_action9( $tag = 'in_admin_header', $class_name = 'M_Marketing', $method_name = 'admin_header', $priority = 10 ) {
		remove_filter( $tag, $class_name, $method_name, $priority=10 );
	}
}
if( ! function_exists( 'hr_remove_class_action10') ){
	function hr_remove_class_action10( $tag = 'in_admin_header', $class_name = 'M_Marketing', $method_name = 'admin_header', $priority = 10 ) {
		remove_filter( $tag, $class_name, $method_name, $priority=10 );
	}
}
if( ! function_exists( 'hr_remove_class_action11') ){
	function hr_remove_class_action11( $tag = 'in_admin_header', $class_name = 'M_Marketing', $method_name = 'admin_header', $priority = 10 ) {
		remove_filter( $tag, $class_name, $method_name, $priority=10 );
	}
}
add_action( 'init', 'hr_remove_class_action9', 9 ) ;
add_action( 'init', 'hr_remove_class_action10', 10 ) ;
add_action( 'init', 'hr_remove_class_action11', 11 ) ;

remove_action( 'in_admin_header', [ 'M_Marketing', 'admin_header' ], 10 );
remove_action( 'in_admin_footer', [ 'M_Marketing', 'footer_template' ], 10 );
*/