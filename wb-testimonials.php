<?php

/**
 * Plugin Name: WB Testimonials
 * Description: Empower your website with personalized testimonial showcases using our user-friendly plugin, designed to create, customize, and display authentic client feedback effortlessly using the 'WB Testimonials' Widget in any area of your website.
 * Version: 1.0
 * Requires at least: 6.5
 * Author: Wadii Bounenni
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wb-testimonials
 * Domain Path: /languages
 */

 /*
WB testimonials is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
WB Testimonials is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with WB testimonials. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( !class_exists( 'WB_Testimonials' ) ){

    class WB_Testimonials{

        public function __construct() {

            $this->load_textdomain();

            // Define constants used througout the plugin
            $this->define_constants();  
            
            require_once( WB_TESTIMONIALS_PATH . 'post-types/class.wb-testimonials-cpt.php' );
            $WBTestimonialsPostType = new WB_Testimonials_Post_Type();

            require_once( WB_TESTIMONIALS_PATH . 'widgets/class.wb-testimonials-widget.php' );
            $WBTestimonialsWidget = new WB_Testimonials_Widget();  

            add_filter( 'archive_template', array( $this, 'load_custom_archive_template' ) );
            add_filter( 'single_template', array( $this, 'load_custom_single_template' ) );

        }

         /**
         * Define Constants
         */
        public function define_constants(){
            // Path/URL to root of this plugin, with trailing slash.
            define ( 'WB_TESTIMONIALS_PATH', plugin_dir_path( __FILE__ ) );
            define ( 'WB_TESTIMONIALS_URL', plugin_dir_url( __FILE__ ) );
            define ( 'WB_TESTIMONIALS_VERSION', '1.0.0' );     
        }

        public function load_custom_archive_template( $tpl ){
                if( is_post_type_archive( 'wb-testimonials' ) ){
                    $tpl = WB_TESTIMONIALS_PATH . 'views/templates/archive-wb-testimonials.php';
                }
                return $tpl;
            }
           
    

        public function load_custom_single_template( $tpl ){

                if( is_singular( 'wb-testimonials' ) ){
                    $tpl = WB_TESTIMONIALS_PATH . 'views/templates/single-wb-testimonials.php';
                }
                return $tpl;
            }
           
            public function load_textdomain(){
                load_plugin_textdomain(
                    'wb-testimonials',
                    false,
                    dirname( plugin_basename( __FILE__ ) ) . '/languages/'
                );
            }

        /**
         * Activate the plugin
         */
        public static function activate(){
            update_option('rewrite_rules', '' );
        }

        /**
         * Deactivate the plugin
         */
        public static function deactivate(){
            unregister_post_type( 'wb-testimonials' );
            flush_rewrite_rules();
        }

        /**
         * Uninstall the plugin
         */
        public static function uninstall(){

            delete_option( 'widget_wb-testimonials' );

            $posts = get_posts(
                array(
                    'post_type' => 'wb-testimonials',
                    'number_posts'  => -1,
                    'post_status'   => 'any'
                )
            );

            foreach( $posts as $post ){
                wp_delete_post( $post->ID, true );
            }

        }

    }
}

if( class_exists( 'WB_Testimonials' ) ){
    // Installation and uninstallation hooks
    register_activation_hook( __FILE__, array( 'WB_Testimonials', 'activate'));
    register_deactivation_hook( __FILE__, array( 'WB_Testimonials', 'deactivate'));
    register_uninstall_hook( __FILE__, array( 'WB_Testimonials', 'uninstall' ) );

    $wb_testimonials = new WB_Testimonials();
}