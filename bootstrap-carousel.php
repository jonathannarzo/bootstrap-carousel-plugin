<?php
/**
 * Plugin Name: Bootstrap carousel
 * Plugin URI: http://www.www.com
 * Description: Wordpress version of bootstrap carousel
 * Version: 1.0
 * Author: Atan
 * Author URI: http://www.www.com
 */
require_once('inc/admin-page.php');
require_once('inc/plugin-widget.php');
require_once('inc/twbs-carousel-view.php');

//plugin activation
register_activation_hook(__FILE__, 'createSliderTable');

//plugin deactivation
register_deactivation_hook(__FILE__, 'sliderDeactivate');

// Register widget
add_action('widgets_init', 'twbsCarouselWidget');

// Admin controls
add_action('admin_menu', 'twbsAdminUI::twbsCarouselMenu');

/**
 * Shortcode
 * usage: [twbs_carousel slider="slider 1"]
 */
add_action('init', 'twbsCarouselShortCode');

// Scripts
add_action('admin_enqueue_scripts', 'loadPluginScripts');

global $wpdb;
global $slides;
global $photos;
$slides = "{$wpdb->prefix}twbs_carousel_slides";
$photos = "{$wpdb->prefix}twbs_carousel_photos";

function createSliderTable() {
	global $wpdb;
	global $slides;
	global $photos;
	if ($wpdb->get_var("SHOW TABLES LIKE '$slides'") != $slides) {
		$sql = "CREATE TABLE `$slides` (
			`id` INT (11) NOT NULL AUTO_INCREMENT,
			`slide_name` VARCHAR(100) NOT NULL UNIQUE,
			PRIMARY KEY (id)
		);";
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	if ($wpdb->get_var("SHOW TABLES LIKE '$photos'") != $photos) {
		$sql = "CREATE TABLE `$photos` (
			`id` INT (11) NOT NULL AUTO_INCREMENT,
			`slide_id` INT(11) NOT NULL,
			`photo_path` VARCHAR(255) NOT NULL,
			PRIMARY KEY (id),
			INDEX(`slide_id`)
		);";
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}

function sliderDeactivate() {
	error_log('Bootstrap carousel plugin deactivated.');
}

function twbsCarouselWidget() {
	register_widget(twbsCarousel);
}

function loadPluginScripts() {
	wp_enqueue_media();
	wp_enqueue_script('media-uploader', plugins_url().'/bootstrap-carousel/js/media-upload.js', array('jquery'), '', true);
	wp_enqueue_script('media-uploader');
}

function twbsCarouselShortCode() {
	add_shortcode('twbs_carousel', 'twbsCarouselShortCodeView');
}

function twbsCarouselShortCodeView($args, $content) {
	if (isset($args['slider'])) twbsCarouselView::viewCarousel($args['slider']);
	else echo 'Please provide a slider! <i>(ex. [twbs_carousel slider="slider name"])</i>';
}