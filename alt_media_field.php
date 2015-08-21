<?php

abstract class alt_media_field extends mf_custom_fields {
    
    public static $suffix_fallback           = '_mf2tk_fallback';
    public static $suffix_alternate_fallback = '_mf2tk_alternate_fallback';
    public static $suffix_caption            = '_mf2tk_caption';
    public static $suffix_poster             = '_mf2tk_poster';
    public static $suffix_link               = '_mf2tk_link';
    public static $suffix_hover              = '_mf2tk_hover';

    # template( ) is called by display_field() of alt_audio_field and display_field() of alt_video_field to implement common funtionality

    public function template( $field, $group_index, $field_index, $media_type, $wp_media_shortcode ) {
        global $mf_domain, $post;

        $media_title = [ 'audio' => __( 'Audio', $mf_domain ), 'video' => __( 'Video', $mf_domain ) ];
        $opts        = $field[ 'options' ];
        $null        = NULL;
        $width       = mf2tk\get_data_option( 'max_width',  $null, $opts, 320 );
        $height      = mf2tk\get_data_option( 'max_height', $null, $opts, 240 );
        $dimensions  = [];
      
        if ( $media_type === 'video' ) {
            if ( $width  ) {
                $dimensions[ 'width'  ] = $width;
            }
            if ( $height ) {
                $dimensions[ 'height' ] = $height;
            }
        }

        $dimensions['preload'] = 'metadata';
        $attrWidth             = $width  ? " width=\"$width\""   : '';
        $attrHeight            = $height ? " height=\"$height\"" : '';
        
        # setup main field
        $field_id    = "mf2tk-$field[name]-$group_index-$field_index";
        $input_value = str_replace( '"', '&quot;', $field['input_value'] );
        if ( !empty( $field['input_value'] ) ) {
            $media_shortcode = call_user_func( $wp_media_shortcode, array_merge( [ 'src' => $field['input_value'] ], $dimensions ) );
        } else {
            $media_shortcode = '';
        }
        
        $index = "[{$group_index}][{$field_index}]";
        
        # setup fallback field
        $fallback_field_name  = $field[ 'name' ] . self::$suffix_fallback;
        $fallback_field_id    = "mf2tk-{$fallback_field_name}-{$group_index}-{$field_index}";
        $fallback_input_name  = "magicfields[{$fallback_field_name}]{$index}";
        $fallback_input_value = mf2tk\get_mf_post_value( $fallback_field_name, $group_index, $field_index, '' );
        if ( $fallback_input_value ) {
            $fallback_media_shortcode = call_user_func( $wp_media_shortcode, array_merge( [ 'src' => $fallback_input_value ], $dimensions ) );
            $fallback_media_button    = __( 'Hide', $mf_domain );
            $fallback_media_display   = 'block';
        } else {
            $fallback_media_shortcode = '';
            $fallback_media_button    = __( 'Open', $mf_domain );
            $fallback_media_display   = 'none';
        }
        
        # setup alternate fallback field
        $alternate_fallback_field_name  = $field[ 'name' ] . self::$suffix_alternate_fallback;
        $alternate_fallback_field_id    = "mf2tk-{$alternate_fallback_field_name}-{$group_index}-{$field_index}";
        $alternate_fallback_input_name  = "magicfields[{$alternate_fallback_field_name}]{$index}";
        $alternate_fallback_input_value = mf2tk\get_mf_post_value( $alternate_fallback_field_name, $group_index, $field_index, '' );
        if ( $alternate_fallback_input_value ) {
            $alternate_fallback_media_shortcode = call_user_func( $wp_media_shortcode, array_merge( [ 'src' => $alternate_fallback_input_value ], $dimensions ) );
            $alternate_fallback_media_button    = __( 'Hide', $mf_domain );
            $alternate_fallback_media_display   = 'block';
        } else {
            $alternate_fallback_media_shortcode = '';
            $alternate_fallback_media_button    = __( 'Open', $mf_domain );
            $alternate_fallback_media_display   = 'none';
        }
        #set up caption field
        $caption_field_name  = $field[ 'name' ] . self::$suffix_caption;
        $caption_input_name  = "magicfields[{$caption_field_name}]{$index}";
        $caption_input_value = str_replace( '"', '&quot;', mf2tk\get_mf_post_value( $caption_field_name, $group_index, $field_index, '' ) );
        
        # setup optional poster image field
        $poster_field_name  = $field[ 'name' ] . self::$suffix_poster;
        $poster_field_id    = "mf2tk-{$poster_field_name}-{$group_index}-{$field_index}";
        $poster_input_name  = "magicfields[{$poster_field_name}]{$index}";
        $poster_input_value = str_replace( '"', '&quot;', mf2tk\get_mf_post_value( $poster_field_name, $group_index, $field_index, '' ) );
        $ucfirst_media_type = $media_title[ $media_type ];

        if ( $media_type === 'audio' ) {
            #set up the link field; video cannot have a link field since clicks play/stop the video
            $link_field_name  = $field[ 'name' ] . self::$suffix_link;
            $link_input_name  = "magicfields[{$link_field_name}]{$index}";
            $link_input_value = mf2tk\get_mf_post_value( $link_field_name, $group_index, $field_index, '' );
        }

        #set up hover field
        $hover_field_name  = $field[ 'name' ] . self::$suffix_hover;
        $hover_input_name  = "magicfields[{$hover_field_name}]{$index}";
        $hover_input_value = mf2tk\get_mf_post_value( $hover_field_name, $group_index, $field_index, '' );

        # generate and return the HTML
        ob_start( );
    ?>
    <div class="media_field_mf">
        <!-- main <?php echo $media_type; ?> field -->
        <div class="mf2tk-field-input-main">
            <h6><?php _e( 'Main', $mf_domain ); echo " $ucfirst_media_type"; ?></h6>
            <div class="mf2tk-field_value_pane">
                <input type="text" name="<?php echo $field['input_name']; ?>" id="<?php echo $field_id; ?>" class="mf2tk-<?php echo $media_type; ?>"
                    maxlength="2048" placeholder="<?php _e( 'URL of the', $mf_domain ); echo " $ucfirst_media_type"; ?>" value="<?php echo $input_value; ?>"
                    <?php echo $field['input_validate']; ?>>
                <button id="<?php echo $field_id; ?>.media-library-button" class="mf2tk-media-library-button"><?php _e( 'Get URL from Media Library', $mf_domain ); ?></button>
                <button id="<?php echo $field_id; ?>.refresh-button" class="mf2tk-alt_media_admin-refresh"><?php _e( 'Reload Media', $mf_domain ); ?></button>
                <br>
                <div class="mf2tk-media" style="width:<?php echo $width; ?>px;padding-top:10px;margin:auto;">
                    <?php echo $media_shortcode; ?>
                </div>
            </div>
        </div>
        <!-- optional fallback <?php echo $media_type; ?> field -->
        <div class="mf2tk-field-input-optional">
            <button class="mf2tk-field_value_pane_button"><?php echo $fallback_media_button; ?></button>
            <h6><?php _e( 'Optional Fallback', $mf_domain ); echo " $ucfirst_media_type"; ?></h6>
            <div class="mf2tk-field_value_pane" style="display:<?php echo $fallback_media_display; ?>;clear:both;">
                <input type="text" name="<?php echo $fallback_input_name; ?>" id="<?php echo $fallback_field_id; ?>" class="mf2tk-<?php echo $media_type; ?>"
                    maxlength="2048" placeholder="<?php _e( 'URL of fallback', $mf_domain ); echo " $ucfirst_media_type"; ?>"
                    value="<?php echo $fallback_input_value; ?>">
                <button id="<?php echo $fallback_field_id; ?>.media-library-button" class="mf2tk-media-library-button">
                    <?php _e( 'Get URL from Media Library', $mf_domain ); ?></button>
                <button id="<?php echo $fallback_field_id; ?>.refresh-button" class="mf2tk-alt_media_admin-refresh">
                    <?php _e( 'Reload Media', $mf_domain ); ?></button>
                <br>
                <div class="mf2tk-media" style="width:<?php echo $width; ?>px;padding-top:10px;margin:auto;">
                    <?php echo $fallback_media_shortcode; ?>
                </div>
            </div>
        </div>
        <!-- optional alternate fallback <?php echo $media_type; ?> field -->
        <div class="mf2tk-field-input-optional">
            <button class="mf2tk-field_value_pane_button"><?php echo $alternate_fallback_media_button; ?></button>
            <h6><?php _e( 'Optional Alternate Fallback', $mf_domain ); echo " $ucfirst_media_type"; ?></h6>
            <div class="mf2tk-field_value_pane" style="display:<?php echo $alternate_fallback_media_display; ?>;clear:both;">
                <input type="text" name="<?php echo $alternate_fallback_input_name; ?>" id="<?php echo $alternate_fallback_field_id; ?>"
                    class="mf2tk-<?php echo $media_type; ?>" maxlength="2048"
                    placeholder="<?php _e( 'URL of alternate fallback', $mf_domain ); echo " $ucfirst_media_type"; ?>"
                    value="<?php echo $alternate_fallback_input_value; ?>">
                <button id="<?php echo $alternate_fallback_field_id; ?>.media-library-button" class="mf2tk-media-library-button">
                    <?php _e( 'Get URL from Media Library', $mf_domain ); ?></button>
                <button id="<?php echo $alternate_fallback_field_id; ?>.refresh-button" class="mf2tk-alt_media_admin-refresh">
                    <?php _e( 'Reload Media', $mf_domain ); ?></button>
                <br>
                <div class="mf2tk-media" style="width:<?php echo $width; ?>px;padding-top:10px;margin:auto;">
                    <?php echo $alternate_fallback_media_shortcode; ?>
                </div>
            </div>
        </div>
        <!-- optional caption field -->
        <div class="mf2tk-field-input-optional mf2tk-caption-field">
            <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
            <h6><?php _e( 'Optional Caption for', $mf_domain ); echo " $ucfirst_media_type"; ?></h6>
            <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
                <input type="text" name="<?php echo $caption_input_name; ?>" maxlength="256"
                    placeholder="<?php _e( 'optional caption for', $mf_domain ); echo " $ucfirst_media_type"; ?>" value="<?php echo $caption_input_value; ?>">
            </div>
        </div>
        <!-- optional poster image field -->
        <div class="mf2tk-field-input-optional">
            <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
            <h6><?php _e( 'Optional Poster Image for', $mf_domain ); echo " $ucfirst_media_type"; ?></h6>
            <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
                <input type="text" name="<?php echo $poster_input_name; ?>" id="<?php echo $poster_field_id; ?>" class="mf2tk-img" maxlength="2048"
                    placeholder="<?php _e( 'URL of optional poster image', $mf_domain ); ?>" value="<?php echo $poster_input_value; ?>">
                <button id="<?php echo $poster_field_id; ?>.media-library-button" class="mf2tk-media-library-button">
                    <?php _e( 'Get URL from Media Library', $mf_domain ); ?></button>
                <button id="<?php echo $poster_field_id; ?>.refresh-button" class="mf2tk-alt_media_admin-refresh">
                    <?php _e( 'Reload Media', $mf_domain ); ?></button>
                <br>
                <div style="width:<?php echo $width; ?>px;padding-top:10px;margin:auto;">
                    <img class="mf2tk-poster" src="<?php echo $poster_input_value; ?>"<?php echo $attrWidth . $attrHeight; ?>>
                </div>
            </div>
        </div>
    <?php
        if ( $media_type === 'audio' ) {
    ?>
        <!-- optional link field -->
        <div class="mf2tk-field-input-optional">
            <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
            <h6><?php _e( 'Optional Link for Poster Image', $mf_domain ); ?></h6>
            <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
                <input type="url" name="<?php echo $link_input_name; ?>" maxlength="2048" placeholder="<?php _e( 'optional link for image', $mf_domain ); ?>"
                    value="<?php echo $link_input_value; ?>">
                <button class="mf2tk-test-load-button" style="float:right;"><?php _e( 'Test Load', $mf_domain ); ?></button>
            </div>
        </div>
    <?php
        }
        $show_custom_field_tag = mf2tk\get_tags( )[ 'show_custom_field' ];
        $popup_for = $media_type === 'audio' ? __( 'Poster Image', $mf_domain ) : __( 'Video', $mf_domain );
    ?>
        <!-- optional hover field -->
        <div class="mf2tk-field-input-optional">
            <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
            <h6><?php _e( 'Optional Mouseover Popup for', $mf_domain ); echo " $popup_for"; ?></h6>
            <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
                <textarea name="<?php echo $hover_input_name; ?>" rows="8" cols="80"
                    placeholder="<?php _e( 'Enter post content fragment with show_custom_field and/or show_macro shortcodes. This will be displayed as a popup when the mouse is over the image. Although this could be plain HTML, using a reusable content template is probably more convenient.',
                        $mf_domain ); ?>"><?php echo $hover_input_value; ?></textarea>
            </div>
        </div>
    <?php
        $html = ob_get_contents( ) . mf2tk\get_how_to_use_html( $field, $group_index, $field_index, $post, ' filter="url_to_media"',
            "alt_{$media_type}_field::get_{$media_type}", TRUE, $caption_input_value, $width ) . '</div>';
        ob_end_clean( );
        if ( $media_type === 'video' && ( !$height || !$width )
            && preg_match_all( '/<video\s+class="wp-video-shortcode"\s+id="([^"]+)"/', $html, $matches, PREG_PATTERN_ORDER ) ) {
            $aspect_ratio = mf2tk\get_data_option( 'aspect_ratio', $null, $opts, '4:3' );
            if ( preg_match( '/([\d\.]+):([\d\.]+)/', $aspect_ratio, $matches1 ) ) {
                $aspect_ratio = $matches1[1] / $matches1[2];
            }
            $do_width = !$width ? 'true' : 'false';
            foreach( $matches[1] as $id ) {
            $html .= <<<EOD
<script type="text/javascript">
    jQuery(document).ready(function(){mf2tkResizeVideo("video.wp-video-shortcode#$id",$aspect_ratio,$do_width);});
</script>
EOD;
            }
        }
        return $html;
    }

    # called by get_audio() of alt_audio_field and get_video() of alt_video_field to implement common funtionality
    # N.B. $height, $width, $hover, $caption, $poster and $html are passed by reference to return values to the caller

    public static function get_template( $field_name, $group_index, $field_index, $post_id, $atts, $invalid_atts, $media_type, $wp_media_shortcode, $classname,
        &$height, &$width, &$hover, &$caption, &$poster, &$link, &$html ) {

        $srcs = mf2tk\get_media_srcs( $field_name, $group_index, $field_index, $post_id, $classname );
        if ( count( $srcs ) === 1 ) {
            $srcs = [ 'src' => reset( $srcs ) ];
        } else if ( !count( $srcs ) ) {
            $html = 'No media found!';
            return;
        }

        # get optional caption
        $caption = mf2tk\get_optional_field( $field_name, $group_index, $field_index, $post_id, self::$suffix_caption );

        # get optional poster image
        $poster  = mf2tk\get_optional_field( $field_name, $group_index, $field_index, $post_id, self::$suffix_poster );

        if ( $media_type === 'audio' ) {
            # get optional link; video cannot have a link field since clicks play/stop the video
            $link    = mf2tk\get_optional_field( $field_name, $group_index, $field_index, $post_id, self::$suffix_link    );
        }

        # get optional mouse-over popup
        $hover   = mf2tk\get_optional_field( $field_name, $group_index, $field_index, $post_id, self::$suffix_hover   );

        # merge shortcode parameters with magic field attributes
        $options = mf2tk\get_data2( $field_name, $group_index, $field_index, $post_id )['options'];

        $width    = mf2tk\get_data_option( 'width',    $atts, $options, 320,        'max_width'  );
        $height   = mf2tk\get_data_option( 'height',   $atts, $options, 240,        'max_height' );
        $loop     = mf2tk\get_data_option( 'loop',     $atts, $options                           ) ? 'on' : 'off';
        $autoplay = mf2tk\get_data_option( 'autoplay', $atts, $options                           ) ? 'on' : 'off';
        $preload  = mf2tk\get_data_option( 'preload',  $atts, $options, 'metadata'               );

        unset( $atts );   # $atts is re-used later for the attribute array for wp media shortcode

        # construct attribute array for wp media shortcode
        $atts = $srcs;
        foreach ( [ 'width', 'height', 'loop', 'autoplay', 'preload', 'poster' ] as $var ) {
            if ( $$var ) {
                $atts[ $var ] = $$var;
            }
        }
        $atts = array_diff_key( $atts, $invalid_atts );
        $atts = array_filter( $atts, function( $v ) {
            return $v !== 'off';
        } );
        $html = call_user_func( $wp_media_shortcode, $atts );   # html returned by reference
    }

    # admin_refresh( ) is invoked by an AJAX request to reload the media element in the post editor

    public static function admin_refresh( ) {

        global $wpdb;
        preg_match( '/magicfields\[(\w+)\]\[\d+\]\[\d+\]/', $_REQUEST[ 'field' ], $matches );
        $field      = str_replace( self::$suffix_fallback, '', str_replace( self::$suffix_alternate_fallback, '', $matches[1] ) );
        $result     = $wpdb->get_row( $wpdb->prepare( 'SELECT type, options FROM ' . MF_TABLE_CUSTOM_FIELDS . ' WHERE name = %s', $field ), ARRAY_A );
        $options    = unserialize( $result['options'] );
        $dimensions = [];
        if ( $result[ 'type' ] === 'alt_video' ) {
            if ( $options[ 'max_width' ]  ) {
                $dimensions[ 'width' ]  = $options[ 'max_width' ];
            }
            if ( $options[ 'max_height' ] ) {
                $dimensions[ 'height' ] = $options[ 'max_height' ];
            }
        }
        $html = call_user_func( $result['type'] === 'alt_video' ? 'wp_video_shortcode' : 'wp_audio_shortcode',
            array_merge( [ 'src' => $_REQUEST[ 'url' ] ], $dimensions ) );
        $html = "<div class=\"mf2tk-new-wp-media-shortcode\">$html</div>";
        $html .= <<<EOD
        <script type="text/javascript">
        (function($){
            var settings = {};
            if ( $( document.body ).hasClass( 'mce-content-body' ) ) {
                return;
            }
            if ( typeof _wpmejsSettings !== 'undefined' ) {
                settings.pluginPath = _wpmejsSettings.pluginPath;
            }
            $('div.mf2tk-new-wp-media-shortcode .wp-audio-shortcode, div.mf2tk-new-wp-media-shortcode .wp-video-shortcode')
                .mediaelementplayer( settings );
        }(jQuery));
EOD;
        if ( ( !$options[ 'max_height' ] || !$options[ 'max_width' ] ) && preg_match( '/<video\s+class="wp-video-shortcode"\s+id="([^"]+)"/', $html, $matches ) ) {
            $id = $matches[1];
            $aspect_ratio = array_key_exists( 'aspect_ratio', $options ) ? $options[ 'aspect_ratio' ] : '4:3';
            if ( preg_match( '/([\d\.]+):([\d\.]+)/', $aspect_ratio, $matches ) ) {
                $aspect_ratio = $matches[1] / $matches[2];
            }
            $do_width = !$options['max_width'] ? 'true' : 'false';
            $html .= "mf2tkResizeVideo(\"div.mf2tk-new-wp-media-shortcode video.wp-video-shortcode#$id\",$aspect_ratio,$do_width);";
        }
        $html .= 'jQuery("div.mf2tk-new-wp-media-shortcode").removeClass("mf2tk-new-wp-media-shortcode");</script>';
        echo $html;
        die;
    }

}

?>