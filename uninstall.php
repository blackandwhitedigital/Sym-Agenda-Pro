<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$option_name = 'wporg_option';
 
delete_option($option_name);
 
// for site options in MultiSite
delete_site_option($option_name);
 
// drop a custom database table
global $wpdb;
$postmeta = $wpdb->prefix."postmeta";
$posts = $wpdb->prefix."posts";
$speakerid=$wpdb->get_col("SELECT ID from $posts where post_type = 'agenda'");
foreach ($speakerid as $key => $value) {
$wpdb->query("DELETE FROM $postmeta WHERE post_id = ".$value);
}
$wpdb->query("DELETE FROM $posts WHERE post_type = 'agenda' "); 