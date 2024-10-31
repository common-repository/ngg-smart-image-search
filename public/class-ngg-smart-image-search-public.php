<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://r-fotos.de/wordpress-plugins
 * @since      1.0.0
 *
 * @package    NGG_Smart_Image_Search
 * @subpackage NGG_Smart_Image_Search/public
 */

define('hr_SIS_dump_mode', 'off');  


/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    NGG_Smart_Image_Search
 * @subpackage NGG_Smart_Image_Search/public
 * @author     Harald R&ouml;h <hroeh@t-online.de>
 */
class NGG_Smart_Image_Search_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ngg-smart-image-search-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name . '-genericons', plugins_url( 'fonts/genericons/genericons.css', dirname(__FILE__) ), array(), $this->version, 'all' );

		//wp_register_style( 'hr-fancybox-css', plugin_dir_url( __FILE__ ) . 'js/fancyapps-fancybox-v-5-0.css', array(), $this->version, 'all' );
		wp_register_style( 'hr-fancybox-css', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css', array(), $this->version, 'all' );
		wp_enqueue_style(  'hr-fancybox-css' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ngg-smart-image-search-public.js', array( 'jquery' ), $this->version, false );

		//wp_register_script( 'hr-fancybox-js',  plugin_dir_url( __FILE__ ) . 'js/fancyapps-fancybox-v-5-0.js', array(), $this->version, true );
		wp_register_script( 'hr-fancybox-js',  'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js', array(), $this->version, true );
		wp_enqueue_script(  'hr-fancybox-js' );

		wp_register_script( 'hr-fancybind-js', plugin_dir_url( __FILE__ ) . 'js/fancyapps-fancybind-v-5-0.js', array(), $this->version, true );
		wp_enqueue_script(  'hr-fancybind-js' );

	}


	/**
	 * Implement shortcode handler for decode post entries
	 *
	 * @since    1.0.0
	 */
  public static function hr_SIS_decode_post_handler( $atts ){

   	global $table_prefix, $wpdb;
    
    $hr_SIS_output_box = "" ;
    $hr_postid         = 1;

    // check for direct shortcode parameters
    if ( ! $atts == '' ) {
        foreach ( $atts as  $hr_SIS_index => $hr_SIS_value ) {
            // check if shortcode parameter is only set for public or logged in user
           
            var_dump( "index=", $hr_SIS_index, "  value=", $hr_SIS_value ) ; echo "<br>";
            if ( $hr_SIS_index == "postid" ) {
            	  $hr_postid = $hr_SIS_value ;
            }
        }
    }

    $hr_post = get_post( $hr_postid ); 
    $hr_content = $hr_post->post_content;
    var_dump( "content=", $hr_content) ; echo "<br><hr>";
    $hr_contentX = stripcslashes($hr_content);
    $hr_contentX = json_decode(base64_decode($hr_contentX), TRUE);
    
    var_dump("content post_decode: ", $hr_contentX); echo "<br><hr>" ;
    
    if ( ! $hr_contentX == '' ) {
        foreach ( $hr_contentX as  $hr_SIS_index => $hr_SIS_value ) {
//            var_dump( $hr_SIS_index,  $hr_SIS_value ) ; echo "<br>";
            echo  $hr_SIS_index,  "=", $hr_SIS_value , "<br>";

        }
    }
    
    
    /*
    $hr_contentY = implode (",", $hr_contentX);
    var_dump("content post_implode: ", $hr_contentY); echo "<br>" ;
    */


    return $hr_SIS_output_box ;			
  }


	/**
	 * Implement shortcode handler for the search box on the search page
	 *
	 * @since    1.0.0
	 */
  public static function hr_SIS_nextgen_searchbox_handler( $atts ){

    $hr_SIS_debug1     = 0 ;
    $hr_SIS_output_box = "" ;
    $hr_SIS_stylemode = 1 ;  // =1 for own style  =0 for general/theme style
    
    // dump parameters if requested
    if ($hr_SIS_debug1 == 1 ) {
      echo "=============> Aufruf searchbox handler<br>";
      var_dump( "_POST: ", $_POST); echo "<br><hr>";
      var_dump( "_REQUEST: ", $_REQUEST); echo "<br><hr>";
      var_dump( "_atts: ", $atts); echo "<br><hr>";
    }

    // get default parameters
    $hr_SIS_array = array () ; 
    $hr_SIS_array = hr_SIS_check_defaults( $hr_SIS_array ) ;
//    var_dump( "defaults: ", $hr_SIS_array ); echo "<br><hr>";
//    var_dump( "_atts: ", $atts); echo "<br><hr>";

    // get style mode for searchbox
    $hr_SIS_options = get_option( 'hr_SIS_settings' );
    $hr_SIS_stylemode = $hr_SIS_options['style_mode'] ;
    
    // check for direct shortcode parameters
    $hr_SIS_form_id = "" ;    // set default for form_id
 	  $hr_SIS_form_target = ""; // set default for target address

    if ( ! $atts == '' ) {

        $hr_user_ID = get_current_user_id();  
        foreach ( $atts as  $hr_SIS_index => $hr_SIS_value ) {

            // check if shortcode parameter is only set for public or logged in user
            $hr_SIS_shortcode_parameter = preg_match('/^(?P<scope>(pu|lo))\_(?P<scpara>.*)$/', $hr_SIS_index, $hr_SIS_match);
//            var_dumP( $hr_SIS_index, $hr_SIS_shortcode_parameter ) ; echo "<br>";
            if ( $hr_SIS_shortcode_parameter ) {
            	  $hr_SIS_scope  = $hr_SIS_match['scope'] ;
            	  $hr_SIS_scpara = $hr_SIS_match['scpara'] ;
            } else {
            	  $hr_SIS_scope  = "" ;
            	  $hr_SIS_scpara = $hr_SIS_index ;
            }

          	// check if index exists in settings
            if ( isset ( $hr_SIS_array[$hr_SIS_scpara] ) ) {
            	  // valid index, overwrite value only if not explicitly specified for public or logged in user
            	  if ( ( $hr_SIS_scope == "" ) ||                                   // scope pu=public or lo=logged is not set
            	       ( ( ( $hr_user_ID == 0 ) && ( $hr_SIS_scope == "pu" ) ) or    // either public
            	         ( ( $hr_user_ID > 0 ) && ( $hr_SIS_scope == "lo" ) ) ) ) {  // or logged in
            	      $hr_SIS_array[$hr_SIS_scpara] = $hr_SIS_value ;
            	  }
            } else {
            	  // check additional parameters for the search shortcode
            	  switch ( $hr_SIS_scpara ) {
          		
          	        case "form_id":
                        $hr_SIS_form_id = $hr_SIS_value ;
                        break;

          	        case "target":
                        $hr_SIS_form_target = $hr_SIS_value ;
                        break;
                        
                    default:            	
          	            $hr_SIS_output_box .= __("Warning: unknown shortcode parameter:", "ngg-smart-image-search") . " " . $hr_SIS_index . '="' . $hr_SIS_value . '"<br><br>' ;
       	        }
            }
        }
    }
//    var_dump( "operational: ", $hr_SIS_array ); echo "<br><hr>";

    // set placeholder text before serialization
    $hr_SIS_placeholder = $hr_SIS_array['placeholder_text'] ;

    // serialize parameters    
    $hr_SIS_uebergabe = json_encode( $hr_SIS_array );  
    $hr_SIS_uebergabe = str_replace( '"', '_#_', $hr_SIS_uebergabe) ;
    if ($hr_SIS_debug1 == 1 ) {
      var_dump( "serialisierte uebergabe: ", $hr_SIS_uebergabe); echo "<br><hr>";
    }
    
    // get current slug / post name for addressing this post if target is not set by parameter
    if ( $hr_SIS_form_target == "" ) {
    	  $hr_SIS_slug = get_post_field( 'post_name', get_post() );
    	  $hr_SIS_form_target =  get_option("siteurl") . '/' . $hr_SIS_slug  ;
    }
    
    if ( $hr_SIS_stylemode ) {
    	  // own class definitions, no need for title option, because this can be defined directly on the page
    	  $hr_SIS_search_form   = "hr-searchform-box";
    	  $hr_SIS_search_field  = "hr_searchfield";
    	  $hr_SIS_search_submit = "hr_searchsubmit";
    	  $hr_SIS_search_icon   = "hr-searchicon";
    } else {
    	  // general class definitions
    	  $hr_SIS_search_form   = "search-form" ;
    	  $hr_SIS_search_field  = "search-field" ;
    	  $hr_SIS_search_submit = "search-submit" ;
    	  $hr_SIS_search_icon   = "icon-search" ;
    }
    		
    // output Such Box für Eingabe des Suchstrings        optional button search text   __("Search", "ngg-smart-image-search") 
    $hr_SIS_output_box .= '<div class="hr_searchform_wrapper"> ' ;
    if ( $hr_SIS_form_id == "" ) {           // check whether a form id is given by parameter
        $hr_SIS_output_box .= '<form ' ;
    } else {
        $hr_SIS_output_box .= '<form id="' . $hr_SIS_form_id . '" ' ;
    }
    $hr_SIS_output_box .= 'action="' . esc_url($hr_SIS_form_target) . '" method="post" class="' . $hr_SIS_search_form . '" > ' .
            				      '<input type="hidden" name="hr_SIS_source" value="shortcode" > ' .
                  	      '<input type="hidden" name="hr_SIS_search_settings" value="' . $hr_SIS_uebergabe . '" > ' .
            				      '<input type="text" class="' . $hr_SIS_search_field . '" name="hr_SIS_search_text" placeholder="' . $hr_SIS_placeholder . '"  /> ' ;

    if ( $hr_SIS_stylemode ) {
    	
    	  // use plugin specific styling of search button/icon
    	  $hr_SIS_output_box .= '<button type="submit" class="' . $hr_SIS_search_submit . '" > ' .
    			                    '  <span class="' . $hr_SIS_search_icon . '" ></span> ' .
                              '</button> ' . 
    		         	            '</form></div><br>';
    } else {
       // use general styling with SVG search button/icon
       $hr_SIS_output_box .= '<button type="submit" class="' . $hr_SIS_search_submit . '">' .
	                              '<svg class="icon ' . $hr_SIS_search_icon . '" aria-hidden="true" role="img" > ' .
	                                '<use href="#icon-search" xlink:href="#icon-search"></use> ' .
	                              '</svg>' .
	                            '</button></form></div><br>' ;
    }



    return $hr_SIS_output_box ;			
  }




	/**
	 * Implement shortcode handler for listing search results on the search page
	 *  can receive its calling parameters either from search-widget in sidebar
	 *     or from shortcode searchbox
	 * @since    1.0.0
	 *     in addition a static searchstring can be provided  as current shortcode parameter
	 * @since    2.0
	 */
  public static function hr_SIS_display_images_handler( $atts ) {
	
  	global $table_prefix, $wpdb;

  	$hr_SIS_debug1           = 0;               // 1=schaltet Dump-Protokollierung von diversen Feldern ein
  	$hr_SIS_output           = "";              // generated output by this shortcode
  	$hr_SIS_search_text      = "";              // search text being looked after
  	$hr_SIS_array            = array();         // array for settings by widget or searchbox-shortcode
  	$hr_SIS_parameter_array  = array();         // array for function calls
  	$hr_SIS_sql_ngg_pictures = "";              // SQL search call
  	$hr_SIS_search_filename  = false;           // given by settings whether to include filename search or not
  	
    $hr_SIS_spacing          = "10em";          // default setting for shortcode parameter
    $hr_SIS_display          = "ngg_single_images"; // default setting for shortcode parameter
    $hr_SIS_nextgen_native_parameters = array() ;   // default setting for native NextGEN shortcode parameters

  	$hr_restrict_public_search = 0 ;            // 1 = limits public search (without login) to public galleries, not used yet
  	$hr_user_ID              = 0;               // set >0 if user is logged in
  	$hr_SIS_options          = get_option( 'hr_SIS_settings');         // needed for test enable uploader option
    $hr_SIS_limit            = 100 ;           // default limit, will be overridden by settings
    $hr_SIS_search_mode			 = "basic";        // default setting, possible values "basic", "extended"
    $hr_sort_field           = 'pid' ;         //  default sort by pid
    $hr_sort_direction       = "DESC" ;         //  default sort direction
    $hr_SIS_verbose          = 1 ;              // 1 = comments search results, 0 = no comments
    $hr_multiple_searchcount = 10 ;             // defines maximum number for multiple search terms
    $hr_SIS_paging					 = 0;               // switch to indicate paging of result list items
    $hr_SIS_images_per_page  = 0;               // default setting for paging is no paging
    $hr_SIS_active_page      = 1;               // paging starts per default with first page
    $hr_SIS_search_result_list = "";              // search result list of image PIDs 
    $hr_SIS_initial_call     = 0;               // =1 if initial call without search string input
    $hr_SIS_piclist          = "";              // clear initial setting
    $hr_SIS_gallery_search   = 0;               // set to one if searchstring specifies gallery search
		$hr_SIS_search_limit_type = "";							// init special search recent/last marker
    $hr_SIS_search_gallery_id = 0 ;      				// init special search gallery marker
      
    // dump parameters if requested
    if ($hr_SIS_debug1 == 1 ) {
      echo "<br>===========>  Aufruf image list handler<br>";
      var_dump( "_POST:", $_POST ); echo "<br>";
      var_dump( "_REQUEST:", $_REQUEST ); echo "<br>";
      var_dump( "Parameter _atts: ", $atts ); echo "<br>";
      var_dump( "url:", $_SERVER['DOCUMENT_ROOT'] ); echo "<br><hr><br>";
    }

    // check if initial call of page
    if ( count($_POST) == 0 ) {
    	  $hr_SIS_initial_call = 1; 
    }

//    if( hr_device_is_mobile() ) { echo ">>> Test Geraet ist mobil <br>"; } else { echo ">>> Test Geraet ist nicht mobil <br>"; }; 

    
    // get search mode by option settings
    if ( isset($hr_SIS_options['search_mode']) ) {
    	  $hr_SIS_search_mode = $hr_SIS_options['search_mode'] ;
    }    

    // check if static streachstring is provided as current shortcode parameter
    if ( ( ! $atts == '' ) && (isset($atts['static_search'])) ) {
    	  $hr_SIS_search_type = "static" ;
        $hr_SIS_search_text = $atts['static_search'] ;
//        echo "static_search=&lt;", $hr_SIS_search_text, "&gt;<br><br>" ;
        // prepare for differentiation between public and logged in users
        $hr_user_ID = get_current_user_id();  
    } else {
        // if no static searchstring given, check received POST parameter and deserialize, if necessary
    	  $hr_SIS_search_type = "dynamic" ;
        if ( isset($_POST['hr_SIS_search_settings']) ) {
    
            $hr_SIS_uebergabe = $_POST['hr_SIS_search_settings'] ;
            $hr_SIS_uebergabe = str_replace( '_#_', '"', $hr_SIS_uebergabe) ;
//            var_dump("source:".$_POST['hr_SIS_source'], $hr_SIS_uebergabe); echo "<br><hr>";
    
            $hr_SIS_array = json_decode( $hr_SIS_uebergabe, true );  
//            var_dump("deserialisiert ", $hr_SIS_array); echo "<br><hr>";

        }
    }
    
    // include all default values, if values are not yet set
    $hr_SIS_array = hr_SIS_check_defaults( $hr_SIS_array ) ;
    // override search limit
    $hr_SIS_limit = $hr_SIS_array['limit'] ;

    // check for direct shortcode parameters
    if (  ! $atts == '' ) {
        foreach ( $atts as  $hr_SIS_index => $hr_SIS_value ) {
          	// check parameter
          	switch ( $hr_SIS_index ) {
          		
          	    case "spacing":
                    // check parameter value, must have format  <digits>em or <digits>rem or <digits>px 
                    $hr_SIS_shortcode_parameter = preg_match('/^\d+(em|rem|px)$/', $hr_SIS_value, $hr_SIS_match);
//                    echo "parameter spacing set to ", $hr_SIS_value, "<br>";
                    if ( $hr_SIS_shortcode_parameter ) {
          	            $hr_SIS_spacing = $hr_SIS_value ;
          	        } else {
        	              $hr_SIS_output .= __("Warning: shortcode parameter has wrong format:", "ngg-smart-image-search") . " " . $hr_SIS_index . '="' . $hr_SIS_value . '"<br><br>' ;
          	        }
          	        break;
          	        
          	    case "display":
          	        $hr_SIS_display = $hr_SIS_value ;
          	        break;
          	        
          	    case "images_per_page":
          	        $hr_SIS_images_per_page = $hr_SIS_value ;
          	        break;

                // check for direct NextGEN Gallery parameter          	        
          	    case "display_type_view":
          	    case "override_thumbnail_settings":
          	    case "images_per_page":
          	    case "thumbnail_width":
          	    case "thumbnail_height":
          	    case "thumbnail_crop":
          	    case "ngg_triggers_display":																// possible values  'always' or 'never'
                case "captions_animation":
          	    case "captions_display_title":
          	    case "sortorder":
          	    case "captions_display_description":
          	    case "is_ecommerce_enabled":
          	        $hr_SIS_nextgen_native_parameters[$hr_SIS_index] = $hr_SIS_value ;
          	        break;

          	    case "static_search":
          	        // ignore this parameter here, is already handled elsewhere
          	        break;

          	    case "order_by":  
          	        // check parameter values for order field
          	        switch ( mb_strtolower($hr_SIS_value, 'UTF-8') ) {
          	        	  case "pid":
         	                  $hr_sort_field = "pid" ;
         	                  break;
           	        	  case "date":
           	        	  case "imagedate":
         	                  $hr_sort_field = "imagedate" ;
         	                  break;
          	        	  case "title":
          	        	  case "alttext":
         	                  $hr_sort_field = "alttext" ;
         	                  break;
          	        	  case "filename":
         	                  $hr_sort_field = "filename" ;
         	                  break;
          	        	  case "random":
         	                  $hr_sort_field = "random" ;
         	                  break;
          	        	  default:
          	        	      $hr_SIS_output .= __("Warning: unknown value for shortcode parameter:", "ngg-smart-image-search") . $hr_SIS_index . '="' . esc_html($hr_SIS_value) . '"<br><br>' ;
          	        }
          	        break;

          	    case "order_direction":
          	        // check parameter values for order direction
          	        switch ( mb_strtolower($hr_SIS_value, 'UTF-8') ) {
          	        	  case "asc":
         	                  $hr_sort_direction = "ASC" ;
         	                  break;
          	        	  case "desc":
         	                  $hr_sort_direction = "DESC" ;
         	                  break;
          	        	  default:
          	        	      $hr_SIS_output .= __("Warning: unknown value for shortcode parameter:", "ngg-smart-image-search") . $hr_SIS_index . '="' . esc_html($hr_SIS_value) . '"<br><br>' ;
          	        }
           	        break;


          	    case "verbose":
          	        // suppresses or shows comments on number of found images and special search cases
          	        if ( ( $hr_SIS_value == '0' ) || ( $hr_SIS_value == '1' ) ) {
          	        	  $hr_SIS_verbose = $hr_SIS_value ;
//                        echo "parameter verbose set to '", $hr_SIS_value, "'<br>";
          	        } else {
                    	  $hr_SIS_output .= __("Warning: unknown value for shortcode parameter:", "ngg-smart-image-search") . $hr_SIS_index . '="' . esc_html($hr_SIS_value) . '"<br><br>' ;
           	        }
          	        break;
          	        
          	    default:	// unknown shortcode parameter
                    if ( $hr_SIS_search_type == "static" ) {
          	            // check for additional search parameters as in shortcode searchbox
                        // check if shortcode parameter is only set for public or logged in user
                        $hr_SIS_shortcode_parameter = preg_match('/^(?P<scope>(pu|lo))\_(?P<scpara>.*)$/', $hr_SIS_index, $hr_SIS_match);
//                        var_dumP( $hr_SIS_index, $hr_SIS_shortcode_parameter ) ; echo "<br>";
                        if ( $hr_SIS_shortcode_parameter ) {
                        	  $hr_SIS_scope  = $hr_SIS_match['scope'] ;
                        	  $hr_SIS_scpara = $hr_SIS_match['scpara'] ;
                        } else {
                        	  $hr_SIS_scope  = "" ;
                        	  $hr_SIS_scpara = $hr_SIS_index ;
                        }
            
                      	// check if index exists in settings
                        if ( isset ( $hr_SIS_array[$hr_SIS_scpara] ) ) {
                        	  // valid index, overwrite value only if not explicitly specified for public or logged in user
                        	  if ( ( $hr_SIS_scope == "" ) ||                                   // scope pu=public or lo=logged is not set
                        	       ( ( ( $hr_user_ID == 0 ) && ( $hr_SIS_scope == "pu" ) ) or    // either public
                        	         ( ( $hr_user_ID > 0 ) && ( $hr_SIS_scope == "lo" ) ) ) ) {  // or logged in
                        	      $hr_SIS_array[$hr_SIS_scpara] = $hr_SIS_value ;
                        	  }
                        } else {
                    	      $hr_SIS_output .= __("Warning: unknown shortcode parameter:", "ngg-smart-image-search") . " " . $hr_SIS_index . '="' . esc_html($hr_SIS_value) . '"<br><br>' ;
                        }
          	        } else {
                    	  $hr_SIS_output .= __("Warning: unknown shortcode parameter:", "ngg-smart-image-search") . $hr_SIS_index . '="' . esc_html($hr_SIS_value) . '"<br><br>' ;
                	  }
          	}
         }
    }

    // get search text from search box (either widget or shortcode)
  	if ( ($_POST) && (isset($_POST['hr_SIS_search_text'])) ) {
  		   $hr_SIS_search_text = $_POST['hr_SIS_search_text'];
    }

    // check for subsequent paging call and recall previous search result
  	if ( ($_POST) && (isset($_POST['hr_SIS_paging_search_list'])) ) {

         // reconstruct old values for display result
  		   $hr_SIS_search_result_list = $_POST['hr_SIS_paging_search_list'];
  		   $hr_SIS_images_per_page    = $_POST['hr_SIS_images_per_page'] ;
  		   $hr_SIS_active_page        = $_POST['hr_SIS_page_select'] ;
  		   $hr_SIS_display            = $_POST['hr_SIS_paging_display_type'] ;
  		   $hr_SIS_gallery_search     = $_POST['hr_SIS_paging_gallery_search'] ;
  		   $hr_SIS_paging_sql_setup   = $_POST['hr_SIS_paging_sql_setup'] ;
  		   $hr_SIS_paging_sql_sort    = $_POST['hr_SIS_paging_sql_sort'] ;
  		   $hr_SIS_output_message     = $_POST['hr_SIS_output_message'] ;
  		   $hr_SIS_paging             = 1;
  		   
  		   $hr_SIS_paging_sql_setup = str_replace( "\'", "'", $hr_SIS_paging_sql_setup) ;
  		   
  		   $hr_SIS_piclist_array = explode(",", $hr_SIS_search_result_list);
         // var_dump("deserialisiert ", $hr_SIS_piclist_array); echo "<br><hr>";
         $hr_SIS_count_found_images  = count($hr_SIS_piclist_array);
  		   
         // build up picture list to display search result list and paging list
         $hr_SIS_stop_index = $hr_SIS_images_per_page * $hr_SIS_active_page ;
         $hr_SIS_start_index = $hr_SIS_stop_index - $hr_SIS_images_per_page ;
         $hr_SIS_stop_index = min($hr_SIS_count_found_images, $hr_SIS_stop_index); 
         $hr_SIS_piclist = $hr_SIS_piclist_array[$hr_SIS_start_index] ; // first element
         for ( $hr_SIS_index = $hr_SIS_start_index+1;  $hr_SIS_index < $hr_SIS_stop_index ; $hr_SIS_index ++ ) {
           	  $hr_SIS_piclist .= ',' . $hr_SIS_piclist_array[$hr_SIS_index] ; 
         }
 
         // var_dump("aktuelle Seite ", $hr_SIS_piclist); echo "<br><hr>";
  		   
         if ( $hr_SIS_gallery_search ) {  // set special switch for gallery search for li or si output display type with paging
             $hr_SIS_array['list_gal_id']    = '' ;
             $hr_SIS_array['list_gal_name']  = '' ;
             $hr_SIS_array['list_gal_descr'] = '' ;
  		   }
  		   
  		   
    } else  {
         // no paging, do search as usual



    // check searchtext string
    if ( $hr_SIS_search_text == '' ) {
    	  if ( !$hr_SIS_initial_call ) {
    	      $hr_SIS_output .=  __("No searchstring for images entered.", "ngg-smart-image-search") . "<br>" ;
    	  }
        return $hr_SIS_output; 
    }

    // check if search text contains output directive '>xxx' at the end
    $hr_SIS_search_pattern = preg_match('/^(?P<searchtext>.*)\>(?P<outputmode>(si|ngg_single_images|li|linked_images|bt|ngg_basic_thumbnails|at|advanced_thumbnails|pt|ngg_pro_thumbnails|ma|ngg_pro_masonry|mo|ngg_pro_mosaic))$/', $hr_SIS_search_text, $hr_SIS_match);
    $hr_SIS_pro_not_available = 0;
    if ( $hr_SIS_search_pattern ) {
    	  // dynamically overwrite display setting if valid outputmode is recognized
    	  $hr_SIS_display     = $hr_SIS_match['outputmode'] ;
    	  $hr_SIS_search_text = $hr_SIS_match['searchtext'] ; 

        // check whether nextgen plus or pro is installed and activated for pro galleries
        if ( ( in_array($hr_SIS_match['outputmode'], array("pt", "ngg_pro_thumbnails", "ma", "ngg_pro_masonry", "mo", "ngg_pro_mosaic"), true) ) 
            && ( !( ( (get_plugins('/nextgen-gallery-pro'))  && ( (is_plugin_active('nextgen-gallery-pro/ngg-pro.php')) or (is_plugin_active('nextgen-gallery-pro/nggallery-pro.php')) ) ) or
                    ( (get_plugins('/nextgen-gallery-plus')) && ( (is_plugin_active('nextgen-gallery-plus/ngg-plus.php') ) or (is_plugin_active('nextgen-gallery-plus/nggallery-plus.php') ) ) ) ) ) ) {
            $hr_SIS_display     = "at" ;        // switch to advanced thumbnails if pro galleries are not available
            $hr_SIS_pro_not_available = 1 ;
        }
    }

    // only search if required minimum lenght of search text is given
    if ( strlen($hr_SIS_search_text) < $hr_SIS_array['searchsize'] ) {
    	  $hr_SIS_output .= sprintf(  __("Searchstring _<b><em>%s</em></b>_ does not have the required minimum length of %s characters.", "ngg-smart-image-search"),
    	                             esc_html($hr_SIS_search_text), $hr_SIS_array['searchsize'] ) . "<br>" ;
        return $hr_SIS_output; 
    }

    // check for dynamic overwrite of basic search mode
    if ( ( $hr_SIS_search_mode == "basic" ) && ( substr($hr_SIS_search_text, 0, 1) == "+" ) && 
         ( isset($hr_SIS_options['enable_escape']) ) && ( $hr_SIS_options['enable_escape'] == "1" ) ) { 
    	  // switch to extended mode
    	  $hr_SIS_search_mode = "extended";
        $hr_SIS_search_text = substr($hr_SIS_search_text, 1);
    }

    // check for dynamic overwrite of extended search mode
    if ( ( $hr_SIS_search_mode == "extended" ) && ( substr($hr_SIS_search_text, 0, 1) == "-" ) && 
         ( isset($hr_SIS_options['enable_escape2']) ) && ( $hr_SIS_options['enable_escape2'] == "1" ) ) { 
    	  // switch to basic mode
    	  $hr_SIS_search_mode = "basic";
        $hr_SIS_search_text = substr($hr_SIS_search_text, 1);
    }

    // init variables for extended search mode
    $hr_SIS_search_gallery_id = 0 ;      // init special search gallery marker
    $hr_SIS_search_limit_type = "" ;     // init special search recent/last marker
    if ( $hr_SIS_search_mode == "basic" ) {
        
        // get all words from the search text
        $hr_SIS_words = preg_match_all('~\s*(\S+)\s*~',$hr_SIS_search_text, $hr_SIS_wordlist);
        
        if ( $hr_SIS_words ) {
          //  echo "check for words in search string: ", $hr_SIS_words, "words found.<br>";
          //  var_dump("++++++found+++++++", $hr_SIS_wordlist); echo"<br><hl>";
        } else {
            $hr_SIS_output .= sprintf(  __("No search item found in basic searchstring %s.", "ngg-smart-image-search"),
                                        esc_html($hr_SIS_search_text) ) . "<br>" ;
            return $hr_SIS_output; 
        }
        
        $hr_search_index = 0;
        while ( $hr_search_index < $hr_SIS_words )  :
            $hr_SIS_search_array[$hr_search_index] = trim( $hr_SIS_wordlist[0][$hr_search_index] ) ;
            $hr_SIS_search_qcode[$hr_search_index] = "&" ;
            $hr_SIS_search_qmode[$hr_search_index]  = "text" ;
            // echo "Suchindex ", $hr_search_index, " mit Suchitem ", $hr_SIS_search_array[$hr_search_index], " gefunden.<br>";

            // only search if required minimum lenght of search text is given
            if ( strlen($hr_SIS_search_array[$hr_search_index]) < $hr_SIS_array['searchsize'] ) {
            	  $hr_SIS_output .= sprintf(  __("Searchitem _<b><em>%s</em></b>_ in searchstring _<b><em>%s</em></b>_ does not have the required minimum length of %s characters.", "ngg-smart-image-search"), $hr_SIS_search_array[$hr_search_index], esc_html($hr_SIS_search_text), $hr_SIS_array['searchsize'] ) . "<br>" ;
                return $hr_SIS_output; 
            }

            $hr_search_index ++;
        endwhile;
        $hr_search_index --;
        // var_dump("++++++takeover+++++++", $hr_SIS_search_array); echo"<br><hl>";
         

    }  else {    	  // search mode must be extended

        // check searchstring for multiple search options, $hr_search_index counts multiple search requests
        $hr_search_index = 0 ;
        $hr_SIS_search_pattern3 = preg_match('/^(?P<searchtext1>.*?) (?P<qcode>(-\&|-\&t|-\&f|-\&d|-\&a|\&|\&t|\&f|\&d|\&a)) (?P<searchtext2>.*)$/', $hr_SIS_search_text, $hr_SIS_match3);
        if ( $hr_SIS_search_pattern3 ) {
            $hr_SIS_search_array[0] = $hr_SIS_match3['searchtext1'] ;
            $hr_SIS_search_array[1] = $hr_SIS_match3['searchtext2'] ;
            $hr_SIS_search_qcode[0] = "&" ;
            $hr_SIS_search_qcode[1] = $hr_SIS_match3['qcode'] ;
            
            $hr_search_index = 1;
            $hr_SIS_search_pattern3 = preg_match('/^(?P<searchtext1>.*?) (?P<qcode>(-\&|-\&t|-\&f|-\&d|-\&a|\&|\&t|\&f|\&d|\&a)) (?P<searchtext2>.*)$/', $hr_SIS_search_array[$hr_search_index], $hr_SIS_match3);
            while ( ( $hr_search_index < $hr_multiple_searchcount-1 ) && ( $hr_SIS_search_pattern3 ) ) :
                $hr_SIS_search_array[$hr_search_index] = $hr_SIS_match3['searchtext1'] ;
                $hr_search_index ++;
                $hr_SIS_search_array[$hr_search_index] = $hr_SIS_match3['searchtext2'] ;
                $hr_SIS_search_qcode[$hr_search_index] = $hr_SIS_match3['qcode'] ;
                $hr_SIS_search_pattern3 = preg_match('/^(?P<searchtext1>.*?) (?P<qcode>(-\&|-\&t|-\&f|-\&d|-\&a|\&|\&t|\&f|\&d|\&a)) (?P<searchtext2>.*)$/', $hr_SIS_search_array[$hr_search_index], $hr_SIS_match3);
            endwhile;
            
            if (  ( $hr_search_index == $hr_multiple_searchcount-1 ) && ( $hr_SIS_search_pattern3 ) ) {
            	  echo "maximum multiple search terms exceeded<br>" ;
            }
        } else {
        	  // no qualified multiple search, get normal search with index 0
            $hr_SIS_search_array[0] = $hr_SIS_search_text ;
            $hr_SIS_search_qcode[0] = "&" ;
        }
    
        // also check first entry for qualified search
        $hr_SIS_search_pattern2 = preg_match('/^(?P<qcode2>(-\&|-\&t|-\&f|-\&d|-\&a|\&|\&t|\&f|\&d|\&a))\s(?P<searchtext2>.*)$/', $hr_SIS_search_array[0], $hr_SIS_match2);
        if ( $hr_SIS_search_pattern2 ) {
            $hr_SIS_search_array[0] = $hr_SIS_match2['searchtext2'] ;
            $hr_SIS_search_qcode[0] = $hr_SIS_match2['qcode2'] ;
        }
            
        // protocoll generated input structure
        if ( ( hr_SIS_dump_mode == 'active' ) && ( is_user_logged_in() ) ) {
            var_dump("setting options ", $hr_SIS_options); echo "<br>";
            var_dump("shortcode parameter ", $hr_SIS_array); echo "<br><hr>";
            var_dump("search_array ", $hr_SIS_search_array); echo "<br>";
            var_dump("search_index ", $hr_search_index); echo "<br>";
            var_dump("search_qcode ", $hr_SIS_search_qcode); echo "<br>";
        }
    
        // check each searchterm for special search request
        for ( $hr_index0 = 0;  $hr_index0 <= $hr_search_index ; $hr_index0 ++ ) {
            $hr_SIS_search_qmode[$hr_index0] = 'text' ;   // default is text search
            $hr_SIS_search_pattern = preg_match('/^(?P<code>(g|r|l)):(?P<digit>\d+)$/', trim($hr_SIS_search_array[$hr_index0]), $hr_SIS_match);
            if ( $hr_SIS_search_pattern ) {
              	switch ( $hr_SIS_match['code'] ) {
              	
              	    case "g":             // special request for gallery search
                        if ( is_user_logged_in() ) {        // exept only for logged in users
                            if ( ( $hr_SIS_search_gallery_id == 0 ) or ( $hr_SIS_search_gallery_id == $hr_SIS_match['digit'] ) ) {
                                $hr_SIS_search_gallery_id = $hr_SIS_match['digit'] ;
                            } else {
                            	  // conflict: cannot filter for two different galleries
        	                      $hr_SIS_output = sprintf(  __("ERROR: you cannot search for two different gallery id's in searchstring %s.", "ngg-smart-image-search"),
        	                             "<b><em>" . esc_tml($hr_SIS_search_text) . "</em></b>" ) . "<br>" ;
                                return $hr_SIS_output; 
                            }
    
                            if ( $hr_SIS_search_qcode[$hr_index0] <> "&" ) {
                            	  // conflict: gallery search only possible with qualifier  &
        	                      $hr_SIS_output = sprintf(  __("ERROR: you cannot use field qualifier %s for gallery search in searchstring %s.", "ngg-smart-image-search"),
        	                             $hr_SIS_search_qcode[$hr_index0], "<b><em>" . esc_html($hr_SIS_search_text) . "</em></b>" ) . "<br>" ;
                                return $hr_SIS_output; 
                            }
    
                            $hr_SIS_search_qmode[$hr_index0] = "gallery" ;
                            $hr_SIS_array['list_gal_id']    = '' ;
                            $hr_SIS_array['list_gal_name']  = '' ;
                            $hr_SIS_array['list_gal_descr'] = '' ;
                            $hr_SIS_gallery_search = 1;        // set special switch for gallery search for li or si output display type with paging 
                        } else {
                        	  // conflict: gallery search only possible with qualifier  &
    	                      $hr_SIS_output = sprintf(  __("WARNING: gallery search in searchstring %s is not authorized by settings for public users.", "ngg-smart-image-search"),
    	                              "<b><em>" . esc_html($hr_SIS_search_text) . "</em></b>" ) . "<br>" ;
                            return $hr_SIS_output; 
                        }
              	        break;
              	        
              	    case "r":             // special request for recent images with newest imagedate
                        if ( $hr_SIS_search_limit_type == "last" ) {
                        	  // conflict: cannot filter for two different galleries
    	                      $hr_SIS_output = sprintf( __("ERROR searchstring %s : you cannot search for recent and last images at the same time.", "ngg-smart-image-search"),
    	                                                 "<b><em>" . esc_html($hr_SIS_search_text) . "</em></b>" ) . "<br>" ;
                            return $hr_SIS_output; 
                        } else {
                            $hr_SIS_search_limit_type  = "recent" ;
                        }
                        
                        if ( $hr_SIS_search_qcode[$hr_index0] <> "&" ) {
                        	  // conflict: recent search only possible with qualifier  &
    	                      $hr_SIS_output = sprintf(  __("ERROR: you cannot use field qualifier %s for recent images search in searchstring %s.", "ngg-smart-image-search"),
    	                             $hr_SIS_search_qcode[$hr_index0], "<b><em>" . esc_html($hr_SIS_search_text) . "</em></b>" ) . "<br>" ;
                            return $hr_SIS_output; 
                        }
                        
                        $hr_SIS_search_qmode[$hr_index0] = "recent" ;
                        $hr_SIS_search_count  = $hr_SIS_match['digit'] ;
                        if ( $hr_SIS_limit > $hr_SIS_search_count ) {
                        	  $hr_SIS_limit = $hr_SIS_search_count ;
                        }
                        $hr_sort_field = "imagedate" ;
                        $hr_sort_direction = "DESC";	    
              	        break;
              	        
              	    case "l":             // special request for last images with highest pid=picture id
                        if ( $hr_SIS_search_limit_type == "recent" ) {
                        	  // conflict: cannot filter for two different galleries
    	                      $hr_SIS_output = sprintf( __("ERROR searchstring %s : you cannot search for recent and last images at the same time.", "ngg-smart-image-search"),
    	                                                 "<b><em>" . esc_html($hr_SIS_search_text) . "</em></b>" ) . "<br>" ;
                            return $hr_SIS_output; 
                        } else {
                            $hr_SIS_search_limit_type  = "last" ;
                        }
                       
                        if ( $hr_SIS_search_qcode[$hr_index0] <> "&" ) {
                        	  // conflict: recent search only possible with qualifier  &
    	                      $hr_SIS_output = sprintf(  __("ERROR: you cannot use field qualifier %s for last images search in searchstring %s.", "ngg-smart-image-search"),
    	                             $hr_SIS_search_qcode[$hr_index0], "<b><em>" . esc_html($hr_SIS_search_text) . "</em></b>" ) . "<br>" ;
                            return $hr_SIS_output; 
                        }
    
                        $hr_SIS_search_qmode[$hr_index0] = "last" ;
                        $hr_SIS_search_count  = $hr_SIS_match['digit'] ;
                        if ( $hr_SIS_limit > $hr_SIS_search_count ) {
                        	  $hr_SIS_limit = $hr_SIS_search_count ;
                        }
                        $hr_sort_field = "pid" ;    // should be default already if not previously overwritten
                        $hr_sort_direction = "DESC";	    
              	        break;
                }
            }
        }
        if ( ( hr_SIS_dump_mode == 'active' ) && ( is_user_logged_in() ) ) {
            var_dump("search_qmode ", $hr_SIS_search_qmode); echo "<br>";
        }

    }



    // check scope of search by galleries
    if  ( ( $hr_SIS_array['include_galleries'] == 'selected' ) &&
          ( ( $hr_SIS_array['search_album'] <> '' ) || ( $hr_SIS_array['search_galleries'] <> '' ) ) ) {
        	$hr_SIS_search_scope = hr_SIS_get_gallery_list ( $hr_SIS_array['search_album'], $hr_SIS_array['search_galleries'] ) ;
    } else {
    	  // search in all galleries with possible exlusions
    	  $hr_SIS_search_scope = '' ;
    }

    // check scope of exclusion for galleries
    if  ( ( $hr_SIS_array['exclude_galleries'] == 'selected' ) && 
          ( ( $hr_SIS_array['excluded_albums'] <> '' ) || ( $hr_SIS_array['excluded_galleries'] <> '' ) ) ) {
        	$hr_SIS_exclude_scope = hr_SIS_get_gallery_list ( $hr_SIS_array['excluded_albums'], $hr_SIS_array['excluded_galleries'] ) ;
    } else {
    	  // no excluded galleries defined
    	  $hr_SIS_exclude_scope = '' ;
    }

    // set up SQL call for search of images
    //=======================================
    // retrieve all possible image fields 
    $hr_SIS_sql_ngg_pictures = "SELECT npic.pid, npic.alttext, npic.description,  npic.filename, npic.imagedate, npic.galleryid, npic.exclude, npic.extras_post_id, " ;

		//var_dump( $hr_SIS_array  ); echo "<br>";
		//var_dump( $hr_SIS_options  ); echo "<br>";

    // special treatment for optional image custom field uploader, only included if enabled by options and selected by widget
    if ( ( isset( $hr_SIS_array['list_uploader']) ) && ( $hr_SIS_array['list_uploader'] == '1' )  && ( $hr_SIS_options['enable_uploader'] == '1' ) ) {
    	  $hr_SIS_sql_ngg_pictures .= "npic.uploader, " ;
    }

    // special treatment for optional tags field
    if ( $hr_SIS_array['list_tags'] ) {
        $hr_SIS_sql_ngg_pictures .= " ttags.wtname, " ;                   
    }

    // continue with gallery fields and table join for these gallery fields
    $hr_SIS_sql_ngg_pictures .=    "ngal.name, ngal.title, ngal.galdesc, ngal.slug, ngal.path, ngal.author, ngal.pageid " .
                                " FROM ( ( " . $table_prefix . "ngg_pictures npic LEFT JOIN " . $table_prefix . "ngg_gallery ngal ON npic.galleryid = ngal.gid ) " ;
                                
    // include table joins if tags are requested to be searched or listed
    if ( ( $hr_SIS_array['search_tags'] ) || ( $hr_SIS_array['list_tags']  ) ) {
          $hr_SIS_sql_ngg_pictures .= " LEFT JOIN ( " .
                           " SELECT trel.object_id trpid, group_concat(tterm.name) wtname FROM  " . $table_prefix . "term_relationships trel " .
                           "    LEFT JOIN " . $table_prefix . "term_taxonomy ttax ON trel.term_taxonomy_id = ttax.term_taxonomy_id  " .
                           "    LEFT JOIN " . $table_prefix . "terms tterm        ON ttax.term_id = tterm.term_id " .
                           " WHERE  ttax.taxonomy = 'ngg_tag' GROUP BY trpid ) ttags ON npic.pid = ttags.trpid ) " ;
          
    } else {
    	  $hr_SIS_sql_ngg_pictures .= " ) " ;
    }
 
    // keep sql setup for possible later paging calls
    $hr_SIS_paging_sql_setup = $hr_SIS_sql_ngg_pictures; 
 
    // standard case, search for textstring in specified image fields                      
    $hr_SIS_loop_index   = 0 ;
    $hr_SIS_searchstring = '';

    // build up array for search parameters in following sql call, here count array index
    $hr_SIS_search_array_index = 0 ;

    while ( $hr_SIS_loop_index <= $hr_search_index ) :    // loop at least once, or more often for number of qualified searches             

      	// echo "loop index ", $hr_SIS_loop_index, " with search qmode ",  $hr_SIS_search_qmode[$hr_SIS_loop_index], "<br>";

        switch ( $hr_SIS_search_qmode[$hr_SIS_loop_index] ) {

    	      case "text" :

                // fix common search parameter for this loop
                $hr_SIS_search_parameter = "%" . $hr_SIS_search_array[$hr_SIS_loop_index] . "%" ;
                $hr_SIS_searchstring0 = "";
                if ( ( $hr_SIS_array['search_title'] ) && ( in_array($hr_SIS_search_qcode[$hr_SIS_loop_index], array("&", "&a", "-&", "-&a"), true) ) ) {
                	  if ( $hr_SIS_search_qcode[$hr_SIS_loop_index] == "-&a" ) {
                	  	   $hr_SIS_searchstring0  .= "!( npic.alttext like %s ) " ;
                	  } else {
                	  	   $hr_SIS_searchstring0  .= "OR ( npic.alttext like %s ) " ;
                	  }	   
                	  $hr_SIS_parameter_array[$hr_SIS_search_array_index] = $hr_SIS_search_parameter ;
                	  $hr_SIS_search_array_index ++;
                }
                // special treatment for optional description field
                if ( ( $hr_SIS_array['search_descr'] ) && ( in_array($hr_SIS_search_qcode[$hr_SIS_loop_index], array("&", "&d", "-&", "-&d"), true) ) ) {
                	  if ( $hr_SIS_search_qcode[$hr_SIS_loop_index] == "-&d" ) {
                	  	   $hr_SIS_searchstring0  .= "!( npic.description like %s ) " ;
                	  } else {
                	  	   $hr_SIS_searchstring0  .= "OR ( npic.description like %s ) " ;
                	  }	   
                	  $hr_SIS_parameter_array[$hr_SIS_search_array_index] = $hr_SIS_search_parameter ;
                	  $hr_SIS_search_array_index ++;
                }
                // special treatment for optional filename field
                if ( ( $hr_SIS_array['search_file'] ) && ( in_array($hr_SIS_search_qcode[$hr_SIS_loop_index], array("&", "&f", "-&", "-&f"), true) ) ) {
                	  if ( $hr_SIS_search_qcode[$hr_SIS_loop_index] == "-&f" ) {
                	  	   $hr_SIS_searchstring0  .= "!( npic.filename like %s ) " ;
                	  } else {
                	  	   $hr_SIS_searchstring0  .= "OR ( npic.filename like %s ) " ;
                	  }	   
                	  $hr_SIS_parameter_array[$hr_SIS_search_array_index] = $hr_SIS_search_parameter ;
                	  $hr_SIS_search_array_index ++;
                }
                // special treatment for optional tags field
                if ( ( $hr_SIS_array['search_tags'] ) && ( in_array($hr_SIS_search_qcode[$hr_SIS_loop_index], array("&", "&t", "-&", "-&t"), true) ) ) {
                	  if ( $hr_SIS_search_qcode[$hr_SIS_loop_index] == "-&t" ) {
                	  	   $hr_SIS_searchstring0  .= "( ( ttags.wtname is null ) OR !( ttags.wtname like %s ) ) " ; 
                 	  } else {
                         if ( $hr_SIS_search_qcode[$hr_SIS_loop_index] == "-&" ) {
                	      	   $hr_SIS_searchstring0  .= "OR ( !( ttags.wtname is null ) AND ( ttags.wtname like %s ) ) " ;    
                         } else {	
                       	     $hr_SIS_searchstring0  .= "OR ( ttags.wtname like %s ) " ; 
                 	  	   }             	  	   
                	  }	   
                	  $hr_SIS_parameter_array[$hr_SIS_search_array_index] = $hr_SIS_search_parameter ;
                	  $hr_SIS_search_array_index ++;
                }
                // cleanup leading 'OR', if present
                if ( $hr_SIS_searchstring0 <> '' ) {
                	  if ( substr( $hr_SIS_searchstring0, 0, 3 ) == 'OR ' ) {
                	      $hr_SIS_searchstring0 = substr( $hr_SIS_searchstring0, 3 ) ;
                	  }
                } else {  // avoid empty string, stop search
                	  if ( $hr_SIS_search_qcode[$hr_SIS_loop_index] == "&" ) {
                	  	  $hr_SIS_search_text = $hr_SIS_parameter_array[$hr_SIS_search_array_index] ;
                	  } else {
                	  	   $hr_SIS_search_text =  $hr_SIS_search_qcode[$hr_SIS_loop_index] . " " . $hr_SIS_parameter_array[$hr_SIS_search_array_index] ;
                	  }
                	
                	  $hr_SIS_output = sprintf(  __("Searchstring %s will result in empty search.", "ngg-smart-image-search"),
    	                                          "<b><em>" . esc_html($hr_SIS_search_text) . "</em></b>" ) . "<br>" ;
                    return $hr_SIS_output; 
                }
                break;

          	case "gallery" :
                // special case, search for specified gallery id                      
                $hr_SIS_searchstring0     = "( npic.galleryid = " . $hr_SIS_search_gallery_id . " ) " ;
             //   $hr_SIS_search_scope    = '' ;
             //   $hr_SIS_exclude_scope   = '' ;
                break;
                
          	case "recent" :
          	case "last" :
          	    // define dummy qualifier
          	    $hr_SIS_searchstring0     = "npic.pid <> 0" ;
          	    break;
                
            default :
                // do nothing 
 
        }   // end switch search_qmode
 
        
        if ( $hr_SIS_loop_index == 0 ) {
        	  if ( $hr_search_index == 0  ) {
                // simple search, only one search term
                if ( $hr_SIS_search_qcode[$hr_SIS_loop_index] == "-&" ) {
                    $hr_SIS_searchstring = "!(" . $hr_SIS_searchstring0 . ")" ;
                } else {
                	  $hr_SIS_searchstring = $hr_SIS_searchstring0 ;
                }
            } else {
            	  //  more then one search term
                if ( $hr_SIS_search_qcode[$hr_SIS_loop_index] == "-&" ) {
                    $hr_SIS_searchstring = "!( " . $hr_SIS_searchstring0 . " ) " ;
                } else {
                	  $hr_SIS_searchstring = "( " . $hr_SIS_searchstring0 . " ) " ;
                }
            }
        } else {
            if ( $hr_SIS_search_mode == "basic" ) {
       	        // follow up search text combined with logical OR
       	        $hr_SIS_searchstring .= " OR ( " . $hr_SIS_searchstring0 . " ) " ;
        	  } else {
            	  // follow up search text combined with logical AND
                if ( $hr_SIS_search_qcode[$hr_SIS_loop_index] == "-&" ) {
                    $hr_SIS_searchstring .= " AND !( " . $hr_SIS_searchstring0 . " ) " ;
                } else {
                    $hr_SIS_searchstring .= " AND ( " . $hr_SIS_searchstring0 . " ) " ;
                }
            }
        }
        $hr_SIS_searchstring0 = "" ;
        $hr_SIS_loop_index ++;
    endwhile ;
          


    // continue with where clause for searchstring                     
    $hr_SIS_sql_ngg_pictures .= " WHERE ( ( " .  $hr_SIS_searchstring  . " ) ";
    
    // filter excluded images if not special gallery display or original nextgen gallery display
 		if ( ( $hr_SIS_gallery_search ) && ( ( $hr_SIS_display  == "li" ) 
 		                or ( $hr_SIS_display == "si" ) or ( $hr_SIS_display == "at" ) or ( $hr_SIS_display == "linked_images" )
 		                or ( $hr_SIS_display == "ngg_single_images" ) or ( $hr_SIS_display == "advanced_thumbnails" ) ) ) {
        // this is special gallery search, do not exclude excluded images, i.e. do nothing here
    } else {
        $hr_SIS_sql_ngg_pictures .= " AND ( npic.exclude <> 1 ) ";       // one parenthesis still open
    }  
                                 
    // specify search gallery scope, if set
    if ( $hr_SIS_search_scope <> '' ) {
    	   $hr_SIS_sql_ngg_pictures .= " AND ( npic.galleryid in (" . $hr_SIS_search_scope . ") ) ";       // one parenthesis still open
    }
                                
    // specify exclude gallery scope, if set
    if ( $hr_SIS_exclude_scope <> '' ) {
    	   $hr_SIS_sql_ngg_pictures .= " AND ( ! ( npic.galleryid in (" . $hr_SIS_exclude_scope . ") ) ) ) ";
    } else {
    	   $hr_SIS_sql_ngg_pictures .= " ) " ;
    }
                                 
    // specify sort option, where special search gallery / code recent / last will overwrite other parameter settings
    if ( $hr_SIS_gallery_search == 1 ) {  // in this case sort by gallery order
    	  $hr_SIS_paging_sql_sort = "  ORDER BY npic.sortorder ASC "  ;                       
    } else {
        if ( $hr_SIS_search_limit_type  == "recent" ) {  // in this case sort by picture date 
            $hr_SIS_paging_sql_sort = "  ORDER BY npic.imagedate DESC "  ;                       
        } else {
            if ( ( $hr_SIS_search_limit_type == "last" )  or  ( $hr_sort_field == "random" ) ) {
                $hr_SIS_paging_sql_sort = "  ORDER BY npic.pid DESC "  ;
            } else {
            	  $hr_SIS_paging_sql_sort = "  ORDER BY npic." . $hr_sort_field . " " . $hr_sort_direction  ;
            }                   
        }
    }
    
    // finish up with limit result list and checking for overflow
    $hr_SIS_limit_overflow = $hr_SIS_limit + 1;
    $hr_SIS_sql_ngg_pictures .= $hr_SIS_paging_sql_sort .  " LIMIT " . $hr_SIS_limit_overflow ;   // increase limit by 1 to recognize overflow of limit

    if ( ( ( hr_SIS_dump_mode == 'active' ) && ( is_user_logged_in() ) ) or ( $hr_SIS_debug1 == 1 ) ) {
       var_dump( "SQL-call ", $hr_SIS_sql_ngg_pictures); echo "<br>";
       var_dump( "SQL-Sort ", $hr_SIS_paging_sql_sort); echo "<br>";
       var_dump( "Parameter ", $hr_SIS_parameter_array); echo "<br><hr>";
    }
                                
    // execute prepared SQL call 
    //==============================                                    
 		if ( count( $hr_SIS_parameter_array ) == 0 ) {
 			//echo "count ist null <br><hr>" ;
 			$hr_SIS_pictures = $wpdb->get_results( $hr_SIS_sql_ngg_pictures );
 		} else {
 			//echo "count ist nicht null <br><hr>" ;
 			$hr_SIS_pictures = $wpdb->get_results( $wpdb->prepare( $hr_SIS_sql_ngg_pictures, $hr_SIS_parameter_array ) );
 		}
 		

    $hr_SIS_count_found_images = count( $hr_SIS_pictures ) ;
    
    // check for overflow of limit
    if ( $hr_SIS_count_found_images > $hr_SIS_limit ) {
    	  $hr_SIS_result_list_overflow = 1;
    	  $hr_SIS_count_found_images = $hr_SIS_limit ;
    	  unset($hr_SIS_pictures[$hr_SIS_count_found_images]);  // array starts with element [0], therefor this deletes last elelemnt of array
    } else {
    	  $hr_SIS_result_list_overflow = 0;
    }
    
    // check if paging is necessary for resultlist
    if ( ( $hr_SIS_images_per_page > 0 )  &&  ( $hr_SIS_count_found_images > $hr_SIS_images_per_page ) ) {
    
        // indicate paging necessity
        $hr_SIS_paging = 1;
        
        // build up picture list to display search result list and paging list
        $hr_SIS_index = 0;
	    	foreach($hr_SIS_pictures as $hr_SIS_picture){

            // build up complete search result list
            if ( $hr_SIS_search_result_list == "" ) { // no leading comma for first item
            	  $hr_SIS_search_result_list = $hr_SIS_picture->pid ; 
            } else  { 
            	  $hr_SIS_search_result_list .= ',' . $hr_SIS_picture->pid ; 
            }

            // build up only result list for first page
            if ( $hr_SIS_index < $hr_SIS_images_per_page ) {
                if ( $hr_SIS_piclist == "" ) { // no leading comma for first item
                	  $hr_SIS_piclist = $hr_SIS_picture->pid ; 
                } else  { 
                	  $hr_SIS_piclist .= ',' . $hr_SIS_picture->pid ; 
                }
            	  $hr_SIS_index ++;
            }
        }
     }
    
    

    if ( ( hr_SIS_dump_mode == 'active' ) && ( is_user_logged_in() ) ) {
//       var_dump( "get results ergebnis ", $hr_SIS_pictures); echo "<br>";
    }
    $hr_SIS_output_message = "";
  	if ( $hr_SIS_verbose == 1 ) {
      	if ( $hr_SIS_count_found_images == 1 ) {
      		  $hr_SIS_output_message .= sprintf(  __("1 image found for searchstring %s.", "ngg-smart-image-search"),
        	                              " <b><em>" . esc_html( $hr_SIS_search_text ) . "</em></b> " ) . "<br>" ;
      	} else {
      	    $hr_SIS_output_message .= sprintf(  __("%s images found for searchstring %s.", "ngg-smart-image-search"),
        	                             $hr_SIS_count_found_images, " <b><em>" . esc_html( $hr_SIS_search_text ) . "</em></b> " )  ;
      	}

        // if paging is active, document paging with number of images per page
        if ( $hr_SIS_paging ) {
          	 $hr_SIS_output_message .= ' ' . sprintf(  __("Show paged result list with %s images per page.", "ngg-smart-image-search"),
            	                         $hr_SIS_images_per_page ) . "<br>" ;
        } else {
        	    $hr_SIS_output_message .= "<br>" ;
        }
    
        if ( $hr_SIS_result_list_overflow ) {
      	    $hr_SIS_output_message .= __("(Number of displayed images limited by settings.)", "ngg-smart-image-search") . "<br>" ;
        }
    }


    // mark special search for gallery
    if ( ( $hr_SIS_verbose == 1 ) && ( $hr_SIS_search_gallery_id > 0 ) ) {
    		 $hr_SIS_output_message .= __("This is a special search for displaying gallery", "ngg-smart-image-search") ;

         // output gallery name with a link if there exists a gallery page
         if ( $hr_SIS_pictures[0]->pageid > 0 ) {
             $hr_SIS_backend_gallery = get_option("siteurl") . '/?p=' . $hr_SIS_pictures[0]->pageid . '/' ;
             $hr_SIS_output_message .= ' <strong><a href="' . $hr_SIS_backend_gallery . '" target="_blank" >' . $hr_SIS_pictures[0]->title  . '</a></strong> ';
         } else {
        	   $hr_SIS_output_message .= ' <strong>' .  $hr_SIS_pictures[0]->title . '</strong> ' ;
         }
  
         // output gallery id with a link to backend if user has authorization for this gallery
         $hr_user_ID = get_current_user_id();        // data type is integer
         if ( ( current_user_can('NextGEN Manage others gallery') ) || ( $hr_user_ID == $hr_SIS_picture->author  ) ) {             // set link only when authorized for gallery
             $hr_SIS_backend_gallery = get_option("siteurl") . '/wp-admin/admin.php?page=nggallery-manage-gallery&mode=edit&gid=' . $hr_SIS_search_gallery_id ;
             $hr_SIS_output_message .= '(id <a href="' . $hr_SIS_backend_gallery . '" target="_blank" ><strong>' . $hr_SIS_search_gallery_id . '</strong></a>).<br>';
         } else {
             $hr_SIS_output_message .= '(id  <strong>' . $hr_SIS_search_gallery_id . ' </strong>).<br>' ;
         }
    } 

    if ( ( $hr_SIS_verbose == 1 ) && ( $hr_SIS_search_limit_type  == "last" ) ) {
    		 $hr_SIS_output_message .= __("This is a special search for displaying the last uploaded images.", "ngg-smart-image-search") . "<br>" ; 
 		}

    if ( ( $hr_SIS_verbose == 1 ) && ( $hr_SIS_search_limit_type  == "recent" ) ) {
    		 $hr_SIS_output_message .= __("This is a special search for displaying the images with newest imagedate.", "ngg-smart-image-search") . "<br>"  ; 
 		}

    if ( ( $hr_SIS_verbose == 1 ) && ( $hr_SIS_pro_not_available == 1 ) ) {
    		 $hr_SIS_output_message .= __("Warning: NextGEN pro galleries not available. Switched display type to advanced thumbnails.", "ngg-smart-image-search") . "<br>"  ; 
 		}

    }  // end doing search


    // output result list
    //======================================  
    $hr_pic_width  = 200 ;
    $hr_pic_height = 200 ;

    // show search result documentation independent of paging
    $hr_SIS_output .= $hr_SIS_output_message ;


    // display top row of paging buttons if paging is active, remove Modernizr.Canvas check 
    if ( $hr_SIS_paging  ) {

        $hr_SIS_output .= '<hr-top-paging-buttons><br><div class="hr_page_form">' ;
 
        $hr_SIS_number_pages = ceil( $hr_SIS_count_found_images / $hr_SIS_images_per_page );
        
        if ( $hr_SIS_debug1 == 1 ) {
        	  echo "Gefundene Bilder  ", $hr_SIS_count_found_images, " dividiert durch Bilder /Seite ", $hr_SIS_images_per_page, 
        	       " ergibt ", $hr_SIS_number_pages, " Seitenanzeigen<br>";
        	
        }
            	      
        for ( $hr_SIS_index = 1;  $hr_SIS_index <= $hr_SIS_number_pages ; $hr_SIS_index ++ ) {
             
   			    $hr_SIS_output .= '<button form="hr_SIS_paging" type="submit" name="hr_SIS_page_select" value="' . $hr_SIS_index . '" class="hr_paging_number' ;
   			    if ( $hr_SIS_index == $hr_SIS_active_page ) {
   			    	  $hr_SIS_output .= ' hr_active_page' ;   // mark active selected page
   			    }
   			    $hr_SIS_output .= ' btn btn-primary" >' . $hr_SIS_index . '</button> ' ;
        }

    		$hr_SIS_output .= '</div><br></hr-top-paging-buttons>';
    }



    switch ( $hr_SIS_display ) {

        // output image list utilizing native NextGEN galleries
        case "bt":
        case "basic_thumbnail" :
        case "ngg_basic_thumbnails" :
        case "photocrati-nextgen_basic_thumbnails" :
        case "basic_slideshow" :
        case "basic_imagebrowser" :
        case "pt" :
        case "ngg_pro_thumbnails" :
        case "thumbnail" :
        case "photocrati-nextgen_pro_thumbnail_grid" :
        case "ma" :
        case "masonry" :
        case "ngg_pro_masonry" :
        case "photocrati-nextgen_pro_masonry" :
        case "mo" :
        case "pro_mosaic" :
        case "ngg_pro_mosaic" :
        case "photocrati-nextgen_pro_mosaic" :
        case "slideshow" :
        case "pro_imagebrowser" :
        case "pro_horizontal_filmstrip" :
        case "pro_sidescroll" :
        case "pro_film" :
        case "pro_blog_gallery" :
            
            // normalize display type
            switch ( $hr_SIS_display ) {
                case "bt":
                case "basic_thumbnail":
                case "ngg_basic_thumbnails" :
                    $hr_SIS_display_type = "photocrati-nextgen_basic_thumbnails" ;
                    break;
                case "pt" :
                case "thumbnail":
                case "ngg_pro_thumbnails" :
                     $hr_SIS_display_type = "photocrati-nextgen_pro_thumbnail_grid" ;
                    break;
                case "ma" :
                case "masonry":
                case "pro_mosaic":
                case "ngg_pro_masonry" :
                    $hr_SIS_display_type = "photocrati-nextgen_pro_masonry" ;
                    break;
                case "mo" :
                case "ngg_pro_mosaic" :
                    $hr_SIS_display_type = "photocrati-nextgen_pro_mosaic" ;
                    break;
                default :
                    $hr_SIS_display_type = $hr_SIS_display ;
            }
        
            // build up picture list to display result list if no paging active
            if ( !$hr_SIS_paging ) {
        	    	foreach($hr_SIS_pictures as $hr_SIS_picture){
                    if ( $hr_SIS_piclist == "" ) { $hr_SIS_piclist = $hr_SIS_picture->pid ; } else  { $hr_SIS_piclist .= ',' . $hr_SIS_picture->pid ; }
                }
            }
             if ($hr_SIS_debug1 == 1 ) {
                echo "<br>===========>  check galerie ausgabe<br>";
                var_dump( "image search result list:", $hr_SIS_piclist ); echo "<br>"; 
            }          
 
            // randomize image sequence if requested
            if ( $hr_sort_field == "random" ) {
            	  $hr_SIS_random = explode(",", $hr_SIS_piclist);
             	  shuffle($hr_SIS_random);
             	  $hr_SIS_random = implode(",", $hr_SIS_random) ;
            }
            
            // generate NextGEN Gallery shortcode
            $hr_SIS_shortcode =  '[ngg_images image_ids="' . $hr_SIS_piclist . '" display_type= "' . $hr_SIS_display_type . '" ' ;
            
            //include additional nextgen parameter
            // override NextGEN paging parameter with lowest possible value
            if ( $hr_SIS_paging ) {
                $hr_SIS_nextgen_native_parameters['images_per_page'] = min($hr_SIS_array['limit'], $hr_SIS_count_found_images, $hr_SIS_images_per_page) ;
            } else {
            	  $hr_SIS_nextgen_native_parameters['images_per_page'] = min($hr_SIS_array['limit'], $hr_SIS_count_found_images );
            }
            foreach ( $hr_SIS_nextgen_native_parameters as $hr_SIS_nextgen_parameter => $hr_SIS_nextgen_parameter_value ):
                $hr_SIS_shortcode .=   $hr_SIS_nextgen_parameter . '="' . $hr_SIS_nextgen_parameter_value . '" ' ; 
            endforeach;
            if ( $hr_sort_field <> "random" ) {
                $hr_SIS_shortcode .=   ' order_by="' . $hr_sort_field . '" order_direction="' . $hr_sort_direction . '" ]' ; 
            } else {
              	$hr_SIS_shortcode .= ' sortorder="' . $hr_SIS_random . '" ]' ;
            }

            // output NextGEN Gallery
            // dump parameters if requested
            if ($hr_SIS_debug1 == 1 ) {
                var_dump("nextgen shortcode: ", $hr_SIS_shortcode); echo "<br>";
            //    $hr_SIS_dump = do_shortcode( $hr_SIS_shortcode ) ;
            //    var_dump("nextgen shortcode:", $hr_SIS_dump );echo "<br>";
            }
            $hr_SIS_output .= '<div style="margin-bottom:5px"> ' . do_shortcode( $hr_SIS_shortcode ) . ' </div>' ;
  	        break;
        
        // output image list in gallery form with own code (legacy code, not really needed any longer)
        case "at" :
        case "advanced_thumbnails" :
            // if paging is active, limit picture array to active page items
            if ( $hr_SIS_paging ) {
                // to be on the save side, fetch image array again for given piclist
                $hr_SIS_sql_ngg_pictures = $hr_SIS_paging_sql_setup . " WHERE ( npic.pid in (" .  $hr_SIS_piclist  . " ) ) " . $hr_SIS_paging_sql_sort ;
                if ($hr_SIS_debug1 == 1 ) {
                    var_dump("page sql call= ", $hr_SIS_sql_ngg_pictures); echo "<br>";
                }
                $hr_SIS_pictures = $wpdb->get_results($wpdb->prepare( $hr_SIS_sql_ngg_pictures, array() ) );
            }

          	foreach($hr_SIS_pictures as $hr_SIS_picture){

    	          // add leading slash to path, if missing (was on some installations)
  		        	if ( substr( $hr_SIS_picture->path, 0 , 1) !== '/' ) { $hr_SIS_pathname = '/' . $hr_SIS_picture->path ; } else { $hr_SIS_pathname = $hr_SIS_picture->path ; }
 								
 								// check for closing slash to path, if missing
 								if ( substr($hr_SIS_pathname, -1) !== '/' ) { $hr_SIS_pathname .= '/' ; }
 								
                // to address image file correctly we need the local path to the file
                $hr_SIS_document_root = get_option("siteurl") ;
          			$hr_SIS_filename = $hr_SIS_document_root . $hr_SIS_pathname . $hr_SIS_picture->filename ;
          			$hr_SIS_thumbsfilename = $hr_SIS_document_root . $hr_SIS_pathname . "thumbs/thumbs_" .  $hr_SIS_picture->filename ;  // $_SERVER['DOCUMENT_ROOT']
          			$hr_SIS_thumbsfilename2 = ABSPATH . substr($hr_SIS_pathname,1) . "thumbs/thumbs_" .  $hr_SIS_picture->filename ;  
           			
          			if (  !file_exists( $hr_SIS_thumbsfilename2 ) ) {
          					// NextGEN changed at some time thumbs-filename from thumbs_imagefile to thumbs-imagefile
          					$hr_SIS_thumbsfilename = $hr_SIS_document_root . $hr_SIS_pathname . "thumbs/thumbs-" .  $hr_SIS_picture->filename ;
          			}
          			if (  !file_exists( $hr_SIS_thumbsfilename2 ) ) {
          					// NextGEN changed at some time thumbs-filename from thumbs_imagefile to thumbs-imagefile
          					//$hr_SIS_output .=  'Datei ' . $hr_SIS_thumbsfilename2 . ' konnte nicht gefunden werden.<br>' ;
  							}
  							if ( $hr_SIS_picture->exclude == 1 ) {
  								  $hr_SIS_titleline = $hr_SIS_picture->alttext . " " . __("(excluded)", "ngg-smart-image-search") ;
  							} else {
  								  $hr_SIS_titleline = $hr_SIS_picture->alttext ;
  							}
  
                $hr_SIS_output .= 
                  		 '<div class="hr_at_box" > ' .
           				        '<div class="hr_at_inner"> ' .
                             '<a data-src="' . $hr_SIS_filename . '" data-caption="' . esc_html($hr_SIS_titleline) . '" data-fancybox="gallery"> ' .
                               '<img class="hr_at_image" src="' . $hr_SIS_thumbsfilename . '" />' .
                             '</a> ' .
                           '</div> ' .
                 		       '<div class="hr_at_text">' . esc_html($hr_SIS_titleline) . '</div> ' .
                 	     '</div> ' ;	
	          }
	          $hr_SIS_output .=  '<div style="clear:both"></div><br> ' ;
	          break;

        // output list of found images in table with field descriptions
        //================================================================
        case "li" :
        case "linked_images" :
        case "si" :    
        case "ngg_single_images" :

            // if paging is active, limit picture array to active page items
            if ( $hr_SIS_paging ) {
                // to be on the save side, fetch image array again for given piclist
                $hr_SIS_sql_ngg_pictures = $hr_SIS_paging_sql_setup . " WHERE ( npic.pid in (" .  $hr_SIS_piclist  . " ) ) " . $hr_SIS_paging_sql_sort ;
                if ($hr_SIS_debug1 == 1 ) {
                    var_dump("page sql call= ", $hr_SIS_sql_ngg_pictures); echo "<br>";
                }
                $hr_SIS_pictures = $wpdb->get_results($wpdb->prepare( $hr_SIS_sql_ngg_pictures, array() ) );
            }

 
            // start result list table with image in left column and descriptions in right column
            $hr_SIS_output .= '<br><table class="hr_resultlist" style="table-layout:fixed;">' ;

        		foreach($hr_SIS_pictures as $hr_SIS_picture){
                // one table row per image
                $hr_SIS_output .= '<tr><td style="width=28%; vertical-align:middle; ">' ;
   
                // different image display for single images and linked images       
                switch ( $hr_SIS_display ) {
            	
            	      case "li" :               // use fancybox for linked images
            	      case "linked_images" :    // ------------------------------- 
          
            	          // add leading slash to path, if missing (was on some installations)
          		        	if ( substr( $hr_SIS_picture->path, 0 , 1) !== '/' ) { $hr_SIS_pathname = '/' . $hr_SIS_picture->path ; } else { $hr_SIS_pathname = $hr_SIS_picture->path ; }

				 								// check for closing slash to path, if missing
 												if ( substr($hr_SIS_pathname, -1) !== '/' ) { $hr_SIS_pathname .= '/' ; }
          			
                        // to address image file correctly we need the local path to the file
                        $hr_SIS_document_root = get_option("siteurl") ;
                  			$hr_SIS_filename = $hr_SIS_document_root . $hr_SIS_pathname . "/" .  $hr_SIS_picture->filename ;
                  			$hr_SIS_thumbsfilename = $hr_SIS_document_root . $hr_SIS_pathname . "/thumbs/thumbs_" .  $hr_SIS_picture->filename ;
       					  			$hr_SIS_thumbsfilename2 = ABSPATH . substr($hr_SIS_pathname,1) . "thumbs/thumbs_" .  $hr_SIS_picture->filename ;  
           			
                  			if (  !file_exists ( $hr_SIS_thumbsfilename2 ) ) {
                  					// NextGEN changed at some time thumbs-filename from thumbs_imagefile to thumbs-imagefile
                  					$hr_SIS_thumbsfilename = $hr_SIS_document_root . $hr_SIS_pathname . "/thumbs/thumbs-" .  $hr_SIS_picture->filename ;
                  			}
                  
          							if ( $hr_SIS_picture->exclude == 1 ) {
          								  $hr_SIS_titleline = $hr_SIS_picture->alttext . " " . __("(excluded)", "ngg-smart-image-search") ;
          							} else {
          								  $hr_SIS_titleline = $hr_SIS_picture->alttext ;
          							}

                        $hr_SIS_output .= 
              				        '<div class="hr_at_inner"> ' .
                                '<a data-src="' . $hr_SIS_filename . '" data-caption="' . esc_html($hr_SIS_titleline) . '" data-fancybox="gallery"> ' .
                                  '<img class="hr_at_image" src="' . $hr_SIS_thumbsfilename . '" />' .
                                '</a> ' .
                            '</div> ' ;
            
                        break;
           
                    case "si" :                     // show image thumb using nextgen gallery single image display
                    case "ngg_single_images" :      // -----------------------------------------------------------
   
                  		  $hr_SIS_shortcode =  '[ngg_images image_ids="' . $hr_SIS_picture->pid  .
                  			                            '" display_type="photocrati-nextgen_basic_singlepic" ' . 
                  			                            'width=' . $hr_pic_width . ' height=' . $hr_pic_height . ' show_captions=1 style="margin:auto;"]' ;
                  	    $hr_SIS_output .= '<div><div style="margin-bottom:5px"> ' . do_shortcode( $hr_SIS_shortcode ) . ' </div>' ;
                        break;
                }
           			// mark excluded images of galleries
           			if ( (  $hr_SIS_gallery_search  )  &&  ( $hr_SIS_picture->exclude == 1 ) ) {
          				   $hr_SIS_output .= '<div class="hr_li-si_text">'  . __("(excluded)", "ngg-smart-image-search") . '</div>' ;
           			} 
           			$hr_SIS_output .= '</div></td>' ;  // close image column
  
                // keep image list. not sure, whether it is needed
                if ( $hr_SIS_piclist == "" ) { $hr_SIS_piclist = $hr_SIS_picture->pid ; } else  { $hr_SIS_piclist .= ',' . $hr_SIS_picture->pid ; }
   
   
                // build up column with detailed image information
                // ------------------------------------------------------
          			$hr_SIS_output .= '<td style="vertical-align:middle; padding-left:10px;width:72%;">' ;
          			
          			if ( $hr_SIS_array['list_pid'] ) {         // if requested, list  image id
                    $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">' . __("image id", "ngg-smart-image-search") . ':</span>' . $hr_SIS_picture->pid . '<br/>' ;
                }
                if ( $hr_SIS_array['list_title'] ) {       // if requested, list  image title
          		      $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">' . __("image title", "ngg-smart-image-search") . ':</span>' . html_entity_decode($hr_SIS_picture->alttext, ENT_QUOTES, 'UTF-8') . '<br/>' ;
          		  }
          		  if ( $hr_SIS_array['list_descr'] ) {       // if requested, list  image description
          		      $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">' . __("description", "ngg-smart-image-search") . ':</span>' . html_entity_decode($hr_SIS_picture->description, ENT_QUOTES, 'UTF-8') . '<br/>';
          		  }
          		  if ( $hr_SIS_array['list_date'] ) {        // if requested, list  image date
          		      $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">' . __("image date", "ngg-smart-image-search") . ':</span>' . $hr_SIS_picture->imagedate . '<br/>' ;
          		  }
            	  if ( $hr_SIS_array['list_file'] ) {        // if requested, list  image filename
          		      $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">' . __("filename", "ngg-smart-image-search") . ':</span>' . $hr_SIS_picture->filename . '<br/>' ;
          		  }
          		  if ( $hr_SIS_array['list_file_size'] ) {   // if requested, list  image filesize
          
          		  	  // add leading slash to path, if missing (was on some installations)
              			if ( substr( $hr_SIS_picture->path, 0 , 1) !== '/' ) { $hr_SIS_pathname = '/' . $hr_SIS_picture->path ; } else { $hr_SIS_pathname = $hr_SIS_picture->path ; }
              			
                    // to address image file correctly we need the local path to the file
                    $hr_SIS_document_root = $_SERVER['DOCUMENT_ROOT'] ;
              			$hr_SIS_filename = $hr_SIS_document_root . $hr_SIS_pathname . "/" .  $hr_SIS_picture->filename ;
                    $hr_SIS_filepath_found = 1;
                    switch ( 1 ) {
                    	  case ( file_exists ( $hr_SIS_filename ) ):		
                    	      // file exists, no addressing problem
                    	      break;
          
          /*          	      echo "script_url = ", $_SERVER['SCRIPT_URL'], " and pos = ", $hr_SIS_pos, "<br>";
                    	      $hr_SIS_document_root = "/home/content/11/3498711/html/mgi/wp" ;
              			        $hr_SIS_filename = $hr_SIS_document_root . $hr_SIS_pathname . "/" .  $hr_SIS_picture->filename ;
                    	      if ( file_exists ( $hr_SIS_filename ) ) {
                    	      	  break;    // done, local address determined
                    	      }*/
                    	  case ( isset( $_SERVER['REAL_DOCUMENT_ROOT'] ) && isset( $_SERVER['SCRIPT_URL'] ) ):
          //          	      echo "a) Filepath ", $hr_SIS_filename , " could not be found.<br>";
                    	      
                    	      // check whether you find local name in virtual host environment
                    	      $hr_SIS_document_root = $_SERVER['REAL_DOCUMENT_ROOT'] ;
                    	      $hr_SIS_pos = strrpos( $_SERVER['SCRIPT_URL'], '/' ) ;
                    	      if ( $hr_SIS_pos > 0 ) {
                    	      	  $hr_SIS_document_root .= substr( $_SERVER['SCRIPT_URL'], 0, $hr_SIS_pos ) ;
                    	      }
                    	      $hr_SIS_filename = $hr_SIS_document_root . $hr_SIS_pathname . "/" .  $hr_SIS_picture->filename ;
                    	      if ( file_exists ( $hr_SIS_filename ) ) {
                    	      	  break;    // done, local address determined
                    	      }
                    	      $hr_SIS_pos = strpos( $hr_SIS_document_root, '/home' ) ;
                    	      $hr_SIS_document_root = substr( $hr_SIS_document_root, $hr_SIS_pos ) ;
                    	      $hr_SIS_filename = $hr_SIS_document_root . $hr_SIS_pathname . "/" .  $hr_SIS_picture->filename ;
                    	      if ( file_exists ( $hr_SIS_filename ) ) {
                    	      	  break;    // done, local address determined
                    	      }
                    	  default:
          //          	      echo "b) Filepath ", $hr_SIS_filename , " could not be found.<br>";
                    	      // if no local path is found, try siteurl
                    	      $hr_SIS_document_root = get_option("siteurl") ;
              			        $hr_SIS_filename = $hr_SIS_document_root . $hr_SIS_pathname . "/" .  $hr_SIS_picture->filename ;
              			        if ( file_exists ( $hr_SIS_filename ) ) {
                    	      	  // done, file address determined
                    	      } else {
                    	      	  // could not find path to file
           //         	      	  $hr_SIS_filepath_found = 0;
          //              	      echo "c) Filepath ", $hr_SIS_filename , " could not be found.<br>";
                    	      }
                    }
                    
                		 if ($hr_SIS_debug1 == 2 ) {
                       $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">check path:</span>' . $hr_SIS_picture->path . '<br/>' ;
                       $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">check filename:</span>' . $hr_SIS_filename . '<br/>' ;
                     }
          
                    if ( $hr_SIS_filepath_found == 1 ) {
          
              			    $hr_SIS_filesize = hr_SIS_filesize(filesize($hr_SIS_filename));    
              			    list($hr_SIS_width, $hr_SIS_height) = getimagesize($hr_SIS_filename, $hr_SIS_data_array)	;
              			
                        if ( $hr_SIS_height > 0  ) {
                            $hr_ratio = $hr_SIS_width / $hr_SIS_height ;
                        }
                      
                        switch ( 1 ) {
                          	  case ( $hr_SIS_height == 0  ):                            //  missing values
                          	       $hr_format =  $hr_SIS_width . ":0" ;
                          	       break;
                          	  case ( $hr_ratio > 1.2 &&  $hr_ratio <= 1.3  ):           //  5:4
                          	       $hr_format =  "5:" . round( 5/$hr_ratio, 2 ) ;
                          	       break;
                           	  case ( $hr_ratio > 1.3 &&  $hr_ratio <= 1.38  ):          //  4:3
                          	       $hr_format =  "4:" . round( 4/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio > 1.38 &&  $hr_ratio <= 1.48 ):            //  7:5
                          	       $hr_format = "7:" .  round( 7/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio > 1.48 &&  $hr_ratio <= 1.58 ):          //  3:2
                          	       $hr_format = "3:" . round( 3/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio > 1.58 &&  $hr_ratio <= 1.65 ):          //  16:10
                          	       $hr_format = "16:" .  round( 16/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio > 1.65 &&  $hr_ratio <= 1.8 ):           //   16:9
                          	       $hr_format = "16:" .  round( 16/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio >  1.8 ):                                //   x:1
                          	       $hr_format = round( $hr_ratio, 2 ) . ":1" ;
                          	       break;
              
                          	  case ( $hr_ratio > 0.5 &&  $hr_ratio <= 0.6 ):            //   9:16  
                          	       $hr_format = "9:" . round( 9/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio > 0.6 &&  $hr_ratio <= 0.64 ):           //   10:16
                          	       $hr_format = "10:" . round( 10/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio > 0.64 &&  $hr_ratio <= 0.7 ):           //   2:3
                          	       $hr_format = "2:" . round( 2/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio > 0.7 &&  $hr_ratio <= 0.73 ):           // 5:7
                          	       $hr_format = "5:" . round( 5/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio > 0.73 &&  $hr_ratio <= 0.77 ):          // 3:4
                          	       $hr_format = "3:" . round( 3/$hr_ratio, 2 ) ;
                          	       break;
                          	  case ( $hr_ratio > 0.77 &&  $hr_ratio <= 0.82 ):          // 4:5
                          	       $hr_format = "4:" . round( 4/$hr_ratio, 2 ) ;
                          	       break;
                           	       
                              default:
                                   $hr_format = "1:" . round( 1/$hr_ratio, 2 ) ;
                        }
                  			
              		      $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">' . __("image size", "ngg-smart-image-search") . ':</span>' . 
              		                          $hr_SIS_filesize . ',  ' . $hr_SIS_width . ' x ' . $hr_SIS_height . ' Pixel, Format ' . $hr_format . '<br/>'  ;
                    } else {
             		        $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">' . __("image size", "ngg-smart-image-search") . ':</span>' . __("file path not found", "ngg-smart-image-search") . '<br/>'  ;
                     }
          		  }
          
          		  if ( ( $hr_SIS_array['list_bu_size'] == '1' ) && ( $hr_SIS_filepath_found == 1 ) ) {     // if requested and path to file is known, list  backup image filesize
              			$hr_SIS_filenameBU = $_SERVER['DOCUMENT_ROOT']. $hr_SIS_pathname . "/" .  $hr_SIS_picture->filename . "_backup" ;
                    if (file_exists($hr_SIS_filenameBU)) {
              			  $hr_SIS_filesizeBU = hr_SIS_filesize(filesize($hr_SIS_filenameBU));    
              			  list($hr_SIS_widthBU, $hr_SIS_heightBU) = getimagesize($hr_SIS_filenameBU, $hr_SIS_data_array)	;
                      $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">' . __("backup size", "ngg-smart-image-search") . ':</span>' .  $hr_SIS_filesizeBU .
                                        ',  ' . $hr_SIS_widthBU . ' x ' . $hr_SIS_heightBU . ' Pixel<br/>' ;
                    } else {
                      $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . ';">' . __("backup size", "ngg-smart-image-search") . ':</span>' .  __("not available", "ngg-smart-image-search") . '<br/>' ;
                    }
                }
          
          		  if ( ( $hr_SIS_array['list_uploader'] ) && ( $hr_SIS_options['enable_uploader'] == '1' ) ) {    // if enabled and requested, list  name of image uploading user
                    if ( $hr_SIS_picture->uploader == 0 ) {
                    	   $hr_SIS_uploader = "unbekannt" ;
                    } else { 
                         $hr_user_ID = get_current_user_id();        // data type is integer
                         settype($hr_user_ID, "string");             // data type now string for comparison
                    	   // get display name of user id
                     	   $hr_sql_select_user = ' SELECT display_name FROM ' . $table_prefix . 'users WHERE ID=' . $hr_SIS_picture->uploader ;
          //          	   echo "SQL:" . $hr_sql_select_user . "<br>";
                    	   $hr_MySQL_select2   = $wpdb->get_results($hr_sql_select_user);
          //          	   var_dump ($hr_MySQL_select2); echo "<br>";
                    	   $hr_SIS_uploader = $hr_MySQL_select2[0] -> display_name;
                    }
                    // only show uploader if image is own image or user can manage the gallery
                    if ( current_user_can ('NextGEN Manage others gallery') or ( $hr_SIS_picture->author  == $hr_user_ID ) ) {   // $hr_SIS_search_gallery_id
                         $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . '">' . __("uploader", "ngg-smart-image-search") . ':</span>' . $hr_SIS_uploader . '<br/>' ;
          	        }
                }
          		  if ( $hr_SIS_array['list_gal_id'] ) {      // if requested, list  gallery id with link to gallery
          
                    $hr_user_ID = get_current_user_id();        // data type is integer
          //          settype($hr_user_ID, "string");             // data type now string for comparison
           
                    if ( ( current_user_can('NextGEN Manage others gallery') ) || ( $hr_user_ID == $hr_SIS_picture->author  ) ) {             // set link only when authorized for gallery
                        $hr_SIS_backend_gallery = get_option("siteurl") . '/wp-admin/admin.php?page=nggallery-manage-gallery&mode=edit&gid=' . $hr_SIS_picture->galleryid ;
          		          $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . '">' . __("gallery id", "ngg-smart-image-search") . ':</span>' . 
          		                             '<a href="' . $hr_SIS_backend_gallery . '" target="_blank" >' . $hr_SIS_picture->galleryid . '</a><br/>';
                    } else {
          		          $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . '">' . __("gallery id", "ngg-smart-image-search") . ':</span>' . $hr_SIS_picture->galleryid . '<br/>';
                    }
          		  }
          		  if ( $hr_SIS_array['list_gal_name'] ) {    // if requested, list  gallery name
          
                    if ( $hr_SIS_picture->pageid > 0 ) {
                        $hr_SIS_backend_gallery = get_option("siteurl") . '/?p=' . $hr_SIS_picture->pageid . '/' ;
          
          		          $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . '">' . __("gallery title", "ngg-smart-image-search") . ':</span>' . 
          		                             '<a href="' . $hr_SIS_backend_gallery . '" target="_blank" >' . esc_html($hr_SIS_picture->title) . '</a><br/>';
          		      } else {
          		      	  $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . '">' . __("gallery title", "ngg-smart-image-search") . ':</span>' . html_entity_decode($hr_SIS_picture->title, ENT_QUOTES, 'UTF-8') . '<br/>';
          		      }
          		  }   
          		  if ( $hr_SIS_array['list_gal_descr'] ) {   // if requested, list  gallery description
          		      $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . '">' . __("description", "ngg-smart-image-search") . ':</span>' . html_entity_decode($hr_SIS_picture->galdesc, ENT_QUOTES, 'UTF-8') . '<br/>';
          		  }
          		  if ( ( $hr_SIS_array['list_tags'] ) && ( $hr_SIS_picture->wtname <> '' ) ) {     // only list tags, if requested and not empty
          //		  	  echo "Suchstring ", $hr_SIS_search_text,  ",   Rueckgabewert: ", $hr_SIS_picture->wtname, ",   ", ( strpos( $hr_SIS_picture->wtname,  ',' ) === false ) ? "Komma nicht gefunden" : "Komma gefunden", "<br>";
          		  	  // if more than one tag is in list, separate by comma and blank
          		  	  if ( strpos( $hr_SIS_picture->wtname,  "," ) === false ) {
                       $hr_SIS_tags_string = $hr_SIS_picture->wtname ;
                    } else {
                	     $hr_SIS_tags_string = preg_replace('/,/', ', ', $hr_SIS_picture->wtname) ;
                    }
          		  	
          		      $hr_SIS_output .= '<span style="float:left;width:'. $hr_SIS_spacing . '">' . __("tags", "ngg-smart-image-search") . ':</span>' . esc_html($hr_SIS_tags_string) . '<br/>';
          		  }
          		  
          		  // close current table row
          		  $hr_SIS_output .= '</td></tr>' ;              
          	}   // end of image loop for single or linked images
        
            $hr_SIS_output .= '</table>' ;
            break;  // end of case single or linked images

        // check if correct display parameter is supplied
        default:
            $hr_SIS_output .=  __("Warning: ", "ngg-smart-image-search") . __("No correct display parameter supplied", "ngg-smart-image-search") ;
            $hr_SIS_output .=  ' (' . $hr_SIS_display . ').' ;
    }  // end output if not gallery
    
    // display paging buttons if necessary
    if ( $hr_SIS_paging ) {

        // get current slug / post name for addressing this post if target is not set by parameter
        if ( $hr_SIS_form_target == "" ) {
        	  $hr_SIS_slug = get_post_field( 'post_name', get_post() );
        	  $hr_SIS_form_target =  get_option("siteurl") . '/' . $hr_SIS_slug  ;
        }
    		
        $hr_SIS_output .= '<br><div class="hr_page_form"><form id="hr_SIS_paging" action="' . $hr_SIS_form_target . '" method="post" class="hr_paging" > ' .
                  	      '<input type="hidden" name="hr_SIS_paging_search_list" value="' . $hr_SIS_search_result_list . '" > ' .
                  	      '<input type="hidden" name="hr_SIS_images_per_page" value="' . $hr_SIS_images_per_page . '" > ' .
                   	      '<input type="hidden" name="hr_SIS_paging_display_type" value="' . $hr_SIS_display . '" > ' .
                   	      '<input type="hidden" name="hr_SIS_paging_gallery_search" value="' . $hr_SIS_gallery_search . '" > ' .
                   	      '<input type="hidden" name="hr_SIS_paging_sql_setup" value="' . $hr_SIS_paging_sql_setup . '" > ' .
                   	      '<input type="hidden" name="hr_SIS_paging_sql_sort" value="' . $hr_SIS_paging_sql_sort . '" > ' .
                   	      '<input type="hidden" name="hr_SIS_output_message" value="' . esc_html($hr_SIS_output_message) . '" > ' ;
                   	      
                   	      

        $hr_SIS_number_pages = ceil( $hr_SIS_count_found_images / $hr_SIS_images_per_page );
            	      
        for ( $hr_SIS_index = 1;  $hr_SIS_index <= $hr_SIS_number_pages ; $hr_SIS_index ++ ) {
             
   			    $hr_SIS_output .= '<button type="submit" name="hr_SIS_page_select" value="' . $hr_SIS_index . '" class="hr_paging_number' ;
   			    if ( $hr_SIS_index == $hr_SIS_active_page ) {
   			    	  $hr_SIS_output .= ' hr_active_page' ;   // mark active selected page
   			    }
   			    $hr_SIS_output .= ' btn btn-primary" >' . $hr_SIS_index . '</button> ' ;
        }

    		$hr_SIS_output .= '</form></div><br>';
    }
    
    return $hr_SIS_output;

  }   // end of function   hr_SIS_display_images_handler
 
 
	/**
	 * Implement shortcode handler for textboxes depending whether user is logged in or not
	 *  
	 * @since    1.0.0
	 */
  public static function hr_SIS_textbox_handler( $atts, $content ) {
 
//    var_dump( "Parameter atts: ", $atts) ; echo "<br><hr>";
//    var_dump( "Parameter content: ", $content) ; echo "<br><hr>";
    
    if ( ( isset($atts['usertype'] ) ) &&
         ( ( ( $atts['usertype'] == 'public' )    && ( ! is_user_logged_in() ) )  ||
           ( ( $atts['usertype'] == 'logged_in' ) && ( is_user_logged_in() )   ) ) ) {
       $hr_SIS_output = $content;
    } else {
    	 $hr_SIS_output = '' ;
    }
    
    return $hr_SIS_output;
  }
}


add_shortcode('hr_SIS_nextgen_searchbox',     array( 'NGG_Smart_Image_Search_Public' ,'hr_SIS_nextgen_searchbox_handler' ) );

add_shortcode('hr_SIS_search_nextgen_images', array( 'NGG_Smart_Image_Search_Public', 'hr_SIS_display_images_handler' ) ) ;
add_shortcode('hr_SIS_display_images',        array( 'NGG_Smart_Image_Search_Public', 'hr_SIS_display_images_handler' ) ) ;

add_shortcode('hr_SIS_textbox',               array( 'NGG_Smart_Image_Search_Public' ,'hr_SIS_textbox_handler' ) );

add_shortcode('hr_SIS_decode_post',           array( 'NGG_Smart_Image_Search_Public' ,'hr_SIS_decode_post_handler' ) );


function hr_SIS_check_defaults ( $hr_SIS_inputs ) {
    
    // shortcode default values for public users
    $defaults_public = array( 'title' => '',                        // title in backend widget box
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
          
    // shortcode default values for logged in users
    $defaults_privat = array( 'title' => '',                        // title in backend widget box
                              'placeholder_text' => __("Enter searchstring for images", "ngg-smart-image-search"),
                              'limit' => '80',                      // limit number of displayed images for search
                              'searchsize' => '2',                  // minimum length of search string
                              'search_title' => '1',                // 0/1 search in title of image
                              'search_descr' => '1',                // 0/1 search in description of image
                              'search_file' => '1',                 // 0/1 search in filename of image
                              'search_tags' => '1',                 // 0/1 search in tags of image
                              'include_galleries' => 'all',         // 'all' = search in all galleries
                                                                    // 'selected' = only search in explicitly listed albums and galleries
                              'search_galleries' => '',             // explicite search list of gallery id's, seperated by comma
                              'search_album' => '',                 // explicite search list of album id's, seperated by comma
                              'exclude_galleries' => 'none',        // 'none' = no exclusion for search defined
                                                                    // 'selected' = do not search in explicity lised galeries and albums
                              'excluded_albums' => '',              // explicite exclude list of album id's, seperated by comma
                              'excluded_galleries' => '',           // explicite exclude list of gallery id's, seperated by comma
                              'list_pid' => '1',                    // 0/1 list image id in search result list (pid = picture id)
                              'list_title' => '1',                  // 0/1 list title of image in search result list
                              'list_descr' => '1',                  // 0/1 list description of image in search result list
                              'list_date' => '1',                   // 0/1 list date of image in search result list
                              'list_file' => '1',                   // 0/1 list filenam of image in search result list
                              'list_file_size' => '1',              // 0/1 list filesize (bytes andpixel) of image in search result list
                              'list_bu_size' => '1',                // 0/1 list filesize of backup image in search result list
                              'list_uploader' => '1',               // 0/1 list user id of image uploader in search result list
                              'list_tags' => '1',                   // 0/1 list tags of image in search result list
                              'list_gal_id' => '1',                 // 0/1 list gallery id of image in search result list
                              'list_gal_name' => '1',               // 0/1 list gallery name / title of image in search result list
                              'list_gal_descr' => '0'               // 0/1 list gallery description of image in search result list
                            );

    if ( is_user_logged_in() ) {
         $hr_SIS_inputs = wp_parse_args( (array) $hr_SIS_inputs, $defaults_privat );
    } else {
    	   $hr_SIS_inputs = wp_parse_args( (array) $hr_SIS_inputs, $defaults_public );
    }	
    if ( $hr_SIS_inputs['include_galleries'] == '' ) { $hr_SIS_inputs['include_galleries'] = "all" ; }
    if ( $hr_SIS_inputs['exclude_galleries'] == '' ) { $hr_SIS_inputs['exclude_galleries'] = "none" ; }
    if ( hr_SIS_dump_mode == 'active' ) {
        echo "<br>===========>  set defaults public / privat<br>";
        var_dump( "is logged in:", is_user_logged_in() ); echo "<br>";
        var_dump( "inputs:", $hr_SIS_inputs ); echo "<br>";
    }
    return $hr_SIS_inputs ;
}


// get readable form for bytes of filesize
function hr_SIS_filesize ( $bytes, $decimals = 2 ) {
    $size = array('B','kB','MB','GB');
    $factor = floor((strlen($bytes) - 1) / 3);
    // for kB use no decimals
    if ( $factor == 1 ) {
    	$decimals = 0;
    }     	
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . " " . @$size[$factor];
}


	/**
	 * get gallery list for NextGEN album
	 *    input is a comma seperated list of album ids and a comma seperated list of gallery ids
	 *    returns  a comma seperated list of optimized gallery ids
	 * @since    1.0.0
	 */
function hr_SIS_get_gallery_list ( $hr_SIS_album_ids, $hr_SIS_gallery_ids ) {

  	global $table_prefix, $wpdb;

    // remove possible white spaces in album list
    $hr_SIS_album_ids = preg_replace('/\s+/', '', $hr_SIS_album_ids);

    if ( $hr_SIS_album_ids <> '' ) {    // album list is nonzero
        // add leading character 'a' to each album id in list
        $hr_SIS_album_ids = 'a' . preg_replace('/,(\d+)/', ',a${1}', $hr_SIS_album_ids);
//        var_dump( "modified parameter ", $hr_SIS_album_ids ); echo "<br>";
        // switch to array list
        $hr_SIS_gallery_list = explode( ",", $hr_SIS_album_ids );
//        var_dump ("initial list ", $hr_SIS_gallery_list); echo "<br><br>";
        
        // implement almost recursive search for gallery ids in albums
        $hr_SIS_search_album = true ;
        $hr_SIS_loop_count = 0;
        while ( $hr_SIS_search_album ) {
        	  $hr_SIS_loop_count ++; 
//        	  echo "neuer " . $hr_SIS_loop_count . ". Durchlauf while Schleife<br>";
        	  $hr_SIS_search_album = false;
            // avoid infinite loop by errornous album definitions 
        	  if ( $hr_SIS_loop_count > 1000 ) { break; }
        	  
            foreach ( $hr_SIS_gallery_list as  $hr_SIS_index => $hr_SIS_gallery_id ) {
        
                if ( preg_match('/^a(?P<digit>\d+)$/', $hr_SIS_gallery_id, $hr_SIS_match_album) ) {
                	  // album found as $hr_SIS_match_album[digit]
                    // read elements of album, can be galleries or albums in any order, seperated by comma
                    $hr_SIS_element_list =  $wpdb->get_var( $wpdb->prepare( "SELECT sortorder FROM ".$table_prefix."ngg_album WHERE id = %s ", $hr_SIS_match_album['digit'] ) );
                
                    // decode and resolve to array of element ids
                    $hr_SIS_element_IDs = stripcslashes($hr_SIS_element_list);
                    $hr_SIS_element_IDs = json_decode(base64_decode($hr_SIS_element_IDs), TRUE);
//                    var_dump("hr_galerie_IDs: ", $hr_SIS_element_IDs); echo "<br>" ;
                    
                    // delete resolved gallery id
                	  unset( $hr_SIS_gallery_list[$hr_SIS_index] ) ;
                	  
                	  // merge resolved gallery ids to gallery list
                	  $hr_SIS_gallery_list = array_merge( $hr_SIS_gallery_list, $hr_SIS_element_IDs ) ;
//                    var_dump("merged hr_galerie_IDs: ", $hr_SIS_gallery_list); echo "<br>" ;
                    
                    // go for new while loop
                	  $hr_SIS_search_album = true;
                	  break ;
                }
        
            }
        }  // end of while loop
    } else {
    	  // no album list present, define empty array
    	  $hr_SIS_gallery_list = array() ;
    }
    
    // remove possible white spaces in gallery list
    $hr_SIS_gallery_ids = preg_replace('/\s+/', '', $hr_SIS_gallery_ids);
    // merge gallery ids with generated list for album ids
    if ( $hr_SIS_gallery_ids <> '' ) {
        $hr_SIS_gallery_list = array_merge( $hr_SIS_gallery_list, explode( ',', $hr_SIS_gallery_ids ) ) ;
    }
    
    // remove non unique values from list
    $hr_SIS_gallery_list = array_unique( $hr_SIS_gallery_list ) ;
    
    // sort gallery list in ascending order
    usort($hr_SIS_gallery_list, function($a, $b) {
        return $a - $b;
    });
    
    // change to comma seperated string
//    var_dump("gallery ids in ascending order: ", $hr_SIS_gallery_list); echo "<br><hr>" ;
    $hr_SIS_select_ids = implode (",", $hr_SIS_gallery_list );
//    var_dump("hr_select_ids in ascending order: ", $hr_SIS_select_ids); echo "<br><hr>" ;

    return $hr_SIS_select_ids ;
}


/**
 *   define thumbnail border with size, color and hover color by settings
 *   and then pass as internal style definition to the page header section
 */
function set_thumbnail_boarder() {

  	$hr_SIS_options = get_option( 'hr_SIS_settings');

    if ( isset ($hr_SIS_options['border_size']) && $hr_SIS_options['border_size'] > 0 ) {
    	  $hr_SIS_border_size = $hr_SIS_options['border_size'] ;
    } else {
    	  $hr_SIS_border_size = 1 ;     // define as default
    }
    if ( isset ($hr_SIS_options['border_color']) && trim($hr_SIS_options['border_color']) <> "" ) {
    	  $hr_SIS_border_color = $hr_SIS_options['border_color'] ;
    } else {
    	  $hr_SIS_border_color = "#fafafa" ;     // define as default
    }
    
    if ( isset ($hr_SIS_options['border_color_hover']) && trim($hr_SIS_options['border_color_hover']) <> "" ) {
    	  $hr_SIS_border_color_hover = $hr_SIS_options['border_color_hover'] ;
    } else {
    	  $hr_SIS_border_color_hover = "#a8a8af" ;     // define as default
    }

echo
'<!-- NGG SIS modify thumbnail by parameter -->
<style>
img.hr_li_image,
img.hr_at_image {
   border: ' . $hr_SIS_border_size . 'px solid ' . $hr_SIS_border_color . ' ; 
}
table.hr_resultlist img.ngg-singlepic {
   border: ' . $hr_SIS_border_size . 'px solid ' . $hr_SIS_border_color . ' ; 
   padding: 0 ;
}
table.hr_resultlist img.ngg-singlepic:hover {
   border: ' . $hr_SIS_border_size . 'px solid ' . $hr_SIS_border_color_hover . ' ; 
}
img.hr_at_image:hover {
   border: ' . $hr_SIS_border_size . 'px solid ' . $hr_SIS_border_color_hover . ' ; 
   opacity: 0.8;
}
</style>

';
}

add_action( 'wp_head', 'set_thumbnail_boarder' );


/**
 *   mark second paging buttons at top of list as not displayable
 *   if canvas is not supported document.querySelector("hr-top-paging-buttons").style='display: none;';

function mark_canvas_inability() {
	
	echo '<script> if (!Modernizr.Canvas) { document.querySelector("hr-top-paging-buttons").removeAttribute("style") ; } </script>';
	
}

add_action( 'wp_footer', 'mark_canvas_inability' );
 */


