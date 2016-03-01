<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit();
global $wpdb;
$slides = "{$wpdb->prefix}twbs_carousel_slides";
$photos = "{$wpdb->prefix}twbs_carousel_photos";
if ($wpdb->get_var("SHOW TABLES LIKE '$slides'") == $slides) {
	$sql = "DROP TABLE `$slides`;";
	$wpdb->query($sql);
}
if ($wpdb->get_var("SHOW TABLES LIKE '$photos'") == $photos) {
	$sql = "DROP TABLE `$photos`;";
	$wpdb->query($sql);
}
?>