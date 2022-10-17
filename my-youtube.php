<?php
/**
 * Plugin Name:       My Youtube
 * Plugin URI:        https://github.com/rotognin/my-youtube-plugin
 * Description:       Plugin para buscar informações do Youtube
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Rodrigo Tognin
 * Author URI:        https://rodrigotognin.com.br
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-youtube
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
   wp_die();
}

// Versão do plugin
if ( ! defined( 'MY_YOUTUBE_VERSION' ) ) {
   define( 'MY_YOUTUBE_VERSION', '1.0.0' );
}

// Nome do plugin
if ( ! defined( 'MY_YOUTUBE_NAME' ) ) {
   define( 'MY_YOUTUBE_NAME', 'My Youtube' );
}

// Apelido do plugin
if ( ! defined( 'MY_YOUTUBE_SLUG' ) ) {
   define( 'MY_YOUTUBE_SLUG', 'my-youtube' );
}

// Nome base do plugin
if ( ! defined( 'MY_YOUTUBE_BASENAME' ) ) {
   define( 'MY_YOUTUBE_BASENAME', plugin_basename( __FILE__ ) );
}

// Pasta base do plugin
if ( ! defined( 'MY_YOUTUBE_PLUGIN_DIR' ) ) {
   define( 'MY_YOUTUBE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Nome do arquivo JSON
if ( ! defined( 'MY_YOUTUBE_JSON_FILENAME' ) ) {
   define( 'MY_YOUTUBE_JSON_FILENAME', 'my-youtube.json' );
}

require_once MY_YOUTUBE_PLUGIN_DIR . 'includes/class-my-youtube.php';
require_once MY_YOUTUBE_PLUGIN_DIR . 'includes/class-my-youtube-json.php';
require_once MY_YOUTUBE_PLUGIN_DIR . 'includes/class-my-youtube-shortcode.php';
require_once MY_YOUTUBE_PLUGIN_DIR . 'includes/class-my-youtube-widget.php';

if ( is_admin() ) {
   require_once MY_YOUTUBE_PLUGIN_DIR . 'includes/class-my-youtube-admin.php';
}

// plugin instance
$my_yt_plugin = new My_Youtube();

$channel_id = $my_yt_plugin->options['channel_id'];

if ( $channel_id != "" ){
   $expiration = $my_yt_rec_plugin->options['cache_expiration'];
   $my_yt_rec_json = new My_Youtube_Json( 
       $channel_id, 
       $expiration, 
       MY_YOUTUBE_SLUG, 
       MY_YOUTUBE_JSON_FILENAME 
   );
 
}

// Widget Instance
$my_yt_rec_widget = new My_Youtube_Widget();

// Shortcode Instance
$my_yt_rec_shortcode = new My_Youtube_Shortcode();

if ( is_admin() ) {
   $my_youtube = new My_Youtube_Admin(
      MY_YOUTUBE_BASENAME,
      MY_YOUTUBE_SLUG,
      MY_YOUTUBE_JSON_FILENAME
   );
}