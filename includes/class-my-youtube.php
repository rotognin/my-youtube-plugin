<?php

if ( ! class_exists( 'My_Youtube' ) ) {

    class My_Youtube {

        public $options;

        public function __construct() {

            $this->options = get_option( 'my_youtube_op' );

            // Obrigatório para o plugin funcionar
            if ( $this->options['channel_id'] != '' ) {

                // Filters
                add_filter( 'the_content', array( $this, 'add_videos_list_in_single_content' ));
                // Actions
                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ));

            }
        }

        public function add_videos_list_in_single_content( $content ) {

            if ( is_single() ) {

                $position = $this->options['show_position'];

                if ( $position == 'before' ) {
                    $content = $this->build_html_videos_list() . $content;
                } else {
                    $content .= $this->build_html_videos_list();
                }

                return $content;

            }

        }

        private function build_html_videos_list() {

            $limit      = $this->options['limit'];
            $layout     = $this->options['layout'];
            $custom_css = $this->options['custom_css'];

            $custom_css = strip_tags($custom_css);
            $custom_css = htmlspecialchars($custom_css, ENT_HTML5 | ENT_NOQUOTES | ENT_SUBSTITUTE, 'utf-8');

            $language     = get_locale();
            $container_id = 'my-yt-container';

            if ( $custom_css != '' ){

                $content .= "<style>{$custom_css}</style>";

            }

            $content    .= "<div id='$container_id'>" . __('Loading...' , 'my-youtube') . "</div>";
			$script      = "<script>
								MyYoutube.lists.push({
								container: '$container_id',
								layout: '$layout',
								limit: $limit,
								lang: '$language',
								callback: MyYoutube.buildList
								});
							</script>";

			return $content . $script;

        }

        public function enqueue_assets() {

	        wp_enqueue_style( 'my-youtube-style', plugin_dir_url( __DIR__ ) . 'public/css/style.css' );
        	wp_enqueue_script( 'my-youtube-scripts', plugin_dir_url( __DIR__ ) . 'public/js/scripts.js', array( 'jquery' ), '', false );
        	wp_enqueue_script( 'my-youtube-loader', plugin_dir_url( __DIR__ ) . 'public/js/loader.js', array( 'jquery' ), '', true );
			wp_localize_script( 'my-youtube-scripts', 'my_yt_rec_ajax', array( 'url' => network_admin_url( 'admin-ajax.php' ) ) );
			
		}

    }

}