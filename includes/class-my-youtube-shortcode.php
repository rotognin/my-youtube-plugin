<?php

if ( ! class_exists( 'My_Youtube_Shortcode' ) ) {

    class My_Youtube_Shortcode {

        public function __construct() {
            add_shortcode('my_youtube', array( $this, 'shortcode' ) );
        }

        /**
         * Adicionar o shortcode numa publicação da seguinte maneira:
         * [ my_youtube limit=? layout=(grid/list) ]
         */
        public function shortcode( $args, $content = null ) {
            extract( $args );           

            $shortcode_unique_id = 'my_yt_shortcode_' . wp_rand( 1, 1000 );

            // Check the widget options
            $limit      = isset( $limit ) ? $limit : 1;
            $layout     = (isset( $layout ) && $layout == 'list')  ? $layout : 'grid';
            $language 	= get_locale();

            $content    = "
                        <div id='$shortcode_unique_id'>" . __('Loading...' , 'my-youtube') . "</div>
                        <script>
                        MyYoutube.lists.push({
                            container: '$shortcode_unique_id',
                            layout: '$layout',
                            limit: $limit,
                            lang: '$language',
                            callback: MyYoutube.buildList
                        });
                        console.log('Adicionou!');
                        </script>
                        ";

            return $content;
        }

    }

}