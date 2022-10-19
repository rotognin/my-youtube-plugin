<?php

if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

if ( ! function_exists( 'my_youtube_uninstall' ) ) {

    function my_youtube_uninstall() {

        delete_option( 'my_youtube_op' );

        $upload_dir = wp_upload_dir();
        $json_folder = $upload_dir['basedir'] . '/my-youtube';
        $json_file = $json_folder . '/my-youtube.json';

        unlink( $json_file );
        rmdir( $json_folder );

    }

}

// Registrar a função de desinstalar
register_uninstall_hook( __FILE__, 'my_youtube_uninstall' );