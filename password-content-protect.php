<?php
/*
  Plugin Name: WP Plugin Vite React sample.
  Description: React Vite WordPress Plugin Starter
  Version: 1.0.0
  Author: DoQuan
 */

if (!defined('ABSPATH')) exit;
function create_custom_table() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'content';

      $charset_collate = $wpdb->get_charset_collate();

      $sql = "CREATE TABLE $table_name (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        type VARCHAR(50) NOT NULL,
        url VARCHAR(255),
        content_short TEXT,
        content_long TEXT
    ) $charset_collate;";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_custom_table');

function copyright_date_block_copyright_date_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'copyright_date_block_copyright_date_block_init' );


require_once(__DIR__ . '/frontend.php');
require_once(__DIR__ . '/backend.php');
