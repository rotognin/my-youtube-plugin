<?php
/**
 * Plugin Name:       My Youtube
 * Plugin URI:        https://example.com/plugins/the-basics/
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

 add_filter( 'the_content', 'thanks' );

 function thanks( $content ){

    return $content . '<p><strong>Muito obrigado!!!</strong></p>';
 }

 /**
  * Continuar aula de plugin da host gator!!!
  */