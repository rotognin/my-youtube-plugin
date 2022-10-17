<?php

if ( ! class_exists( 'My_Youtube_Admin' ) ) {
    class My_Youtube_Admin {

        private $options;
        private $plugin_basename;
        private $plugin_slug;
        private $json_filename;

        public function __construct ($basename, $slug, $json_filename) {

            $this->options = get_option('my_youtube_op');

            $this->plugin_basename = $basename;
            $this->plugin_slug = $slug;
            $this->json_filename = $json_filename;

            add_action( 'admin_menu', array ( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array ( $this, 'page_init' ) );
            add_action( 'admin_footer_text', array ( $this, 'page_footer' ) );
            add_action( 'admin_notices', array ( $this, 'show_notices' ) );
            add_filter( "plugin_action_links_" . $this->plugin_basename, array( $this, 'add_settings_link' ) );

        }

        public function add_plugin_page () {

            add_options_page(
                __( 'Settings', 'my-youtube' ),
                __( 'My Youtube', 'my-youtube' ),
                'manage_options',
                $this->plugin_slug,
                array( $this, 'create_admin_page' )
            );
        
        }

        public function create_admin_page () {
            ?>
            <div class="wrap">
                <h1><?php echo __( 'My Youtube', 'my-youtube' ); ?></h1>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'my_youtube_options' );
                        do_settings_sections( 'my-youtube-admin');
                        submit_button();
                    ?>
                </form>
            </div>
            <?php
        }

        public function page_init () {
            register_setting(
                'my_youtube_options', // Option group
                'my_youtube_op', // Option name
                array( $this, 'sanitize' ) // Sanitize
            );

            add_settings_section(
                'setting_section_id_1', // ID
                __( 'General Settings', 'my-youtube'), // Título
                null, // Callback
                'my-youtube-admin' // Página
            );

            add_settings_field(
                'channel_id', // ID
                __( 'Channel Id', 'my-youtube' ), // Título
                array( $this, 'channel_id_callback' ), // Callback
                'my-youtube-admin', // Página
                'setting_section_id_1' // Seção
            );

            add_settings_field(
                'cache_expiration', // ID
                __( 'Cache Expiration', 'my-youtube' ), // Título
                array( $this, 'cache_expiration_callback' ), // Callback
                'my-youtube-admin', // Página
                'setting_section_id_1' // Seção
            );

            add_settings_section(
                'setting_section_id_2',
                __( 'Post Settings', 'my-youtube'),
                null,
                'my-youtube-admin'
            );

            add_settings_field(
                'show_position', // ID
                __( 'Show in Posts', 'my-youtube' ), // Título
                array( $this, 'show_position_callback' ), // Callback
                'my-youtube-admin', // Página
                'setting_section_id_2' // Seção
            );

            add_settings_field(
                'layout', // ID
                __( 'Layout', 'my-youtube' ), // Título
                array( $this, 'show_layout_callback' ), // Callback
                'my-youtube-admin', // Página
                'setting_section_id_2' // Seção
            );

            add_settings_field(
                'limit', // ID
                __( 'Videos in List', 'my-youtube' ), // Título
                array( $this, 'limit_callback' ), // Callback
                'my-youtube-admin', // Página
                'setting_section_id_2' // Seção
            );

            add_settings_section(
                'setting_section_id_3',
                __( 'Customize Style', 'my-youtube'),
                null,
                'my-youtube-admin'
            );

            add_settings_field(
                'custom_css', // ID
                __( 'Your CSS', 'my-youtube' ), // Título
                array( $this, 'custom_css_callback' ), // Callback
                'my-youtube-admin', // Página
                'setting_section_id_3' // Seção
            );

        }

        public function channel_id_callback () {
            $value = isset($this->options['channel_id']) ? esc_attr( $this->options['channel_id'] ) : '';
            ?>
            <input type="text" id="channel_id" name="my_youtube_op[channel_id]" value="<?php echo $value; ?>" class="regular-text" />
                <p class="description"><?php echo __('sample' , 'my-youtube'); ?>: UCcJAE190tfX3-HepxA6IcYw</p>
                <p class="description"><a href="https://support.google.com/youtube/answer/3250431" target="_blank"><?php echo __('Find here your channel Id' , 'my-youtube'); ?></a></p>
            <?php
        }

        public function cache_expiration_callback() {
            $upload_dir = wp_upload_dir();
            $json_url = $upload_dir['baseurl'] . '/' . $this->plugin_slug . '/' . $this->json_filename;

            $value = isset( $this->options['cache_expiration'] ) ? esc_attr( $this->options['cache_expiration'] ) : '1';
            ?>
                <input type="number" id="cache_expiration" min="1" name="my_youtube_op[cache_expiration]" value="<?php echo $value; ?>" class="small-text" />
                <?php echo __('hours is the expiration time for cached data' , 'my-youtube'); ?>.
                <p class="description"><a href="<?php echo $json_url?>" target="_blank"><?php echo __('Test here' , 'my-youtube'); ?></a>.
            <?php
        }

        public function show_position_callback() {
            $value = isset( $this->options['show_position'] ) ? esc_attr( $this->options['show_position'] ) : '';
            ?>
            <fieldset>
                <legend class="screen-reader-text"><span><?php echo __('On posts show videos in position:' , 'my-youtube') ?></span></legend>
                <label><input type="radio" name="my_youtube_op[show_position]" value=""<?php echo ( $value == '' ) ? 'checked="checked"' : ''; ?>> <?php echo __('Disable' , 'my-youtube'); ?></label><br>
                <label><input type="radio" name="my_youtube_op[show_position]" value="after"<?php echo ( $value == 'after' ) ? 'checked="checked"' : ''; ?>> <?php echo __('After' , 'my-youtube'); ?></label><br>
                <label><input type="radio" name="my_youtube_op[show_position]" value="before"<?php echo ( $value == 'before' ) ? 'checked="checked"' : ''; ?>> <?php echo __('Before' , 'my-youtube'); ?></label>
            </fieldset>
            <?php
        }

        public function show_layout_callback() {
            $value = isset( $this->options['layout'] ) ? esc_attr( $this->options['layout'] ) : 'grid';
            ?>
            <select name="my_youtube_op[layout]">
                <option value="grid"<?php echo ( $value == 'grid' ) ? 'selected="selected"' : '' ?>><?php echo __('Grid' , 'my-youtube'); ?></option>
                <option value="list"<?php echo ( $value == 'list' ) ? 'selected="selected"' : '' ?>><?php echo __('List' , 'my-youtube'); ?></option>
            </select>
            <?php
        }

        public function limit_callback() {
            $value = isset( $this->options['limit'] ) ? esc_attr( $this->options['limit'] ) : '3';
            ?>
            <input type="number" id="limit" min="1" max="15" name="my_youtube_op[limit]" value="<?php echo $value; ?>" class="small-text" />
            <p class="description"><?php echo __('Max' , 'my-youtube'); ?> 15</p>
            <?php
        }

        public function custom_css_callback() {
            $value = isset( $this->options['custom_css'] ) ? esc_attr( $this->options['custom_css'] ) : '';
            ?>
            <textarea id="custom_css" name="my_youtube_op[custom_css]" rows="10" cols="50" class="large-text code"><?php echo $value; ?></textarea>
            <?php
        }

        public function page_footer(){
            return __( 'Plugin Version', 'my-youtube' ) . ' ' . MY_YOUTUBE_VERSION;
        }

        /**
         * Sanitize each setting field as needed
         *
         * @param array $input Contains all settings fields as array keys
         */
        public function sanitize( $input ) {

            $new_input = array();          

            if( isset( $input['channel_id'] ) )
                $new_input['channel_id'] = sanitize_text_field( $input['channel_id'] );
                
            if( isset( $input['cache_expiration'] ) )
                $new_input['cache_expiration'] = absint( $input['cache_expiration'] );

            if( isset( $input['show_position'] ) )
                $new_input['show_position'] = sanitize_text_field( $input['show_position'] );

            if( isset( $input['layout'] ) )
                $new_input['layout'] = sanitize_text_field( $input['layout'] );

            if( isset( $input['limit'] ) )
                $new_input['limit'] = absint( $input['limit'] );

            if( isset( $input['custom_css'] ) )
                $new_input['custom_css'] = sanitize_text_field( $input['custom_css'] );

            return $new_input;
        }

        public function show_notices () {

            $value = isset( $this->options['channel_id']) ? esc_attr( $this->options['channel_id']) : '';

            if ( $value == '' ){
                ?>
                <div class="error notice">
                <?php echo $channel_id ?>
                    <p><strong><?php echo __( 'My Youtube', 'my-youtube' ); ?></strong></p>
                    <p><?php echo __( 'Fill with your Youtube channel ID', 'my-youtube' ); ?></p>
                </div>
                <?php
            }
        }

        public function add_settings_link ( $links ) {
            $settings_link = '<a href="options-general.php?=' . $this->plugin_slug . '">' . __( 'Settings', 'my-youtube') . '</a>';
            array_unshift( $links, $settings_link);
            return $links;
        }



    }

}
