<?php

require_once( WP_PLUGIN_DIR . '/magic-fields-2-toolkit/alt_media_field.php' );

class alt_image_field extends mf_custom_fields {

    private static $suffix_caption = '_mf2tk_caption';
    private static $suffix_link    = '_mf2tk_link';
    private static $suffix_hover   = '_mf2tk_hover';
    
    public function _update_description( ) {
        global $mf_domain;
        $this->description = __( 'This is an alternate Magic Fields 2 field for images.', $mf_domain );
    }
  
    public function _options( ) {
        global $mf_domain;
        $show_custom_field_tag = mf2tk\get_tags( )[ 'show_custom_field' ];
        return [
            'option'  => [
                'max_width'  => [
                    'type'        =>  'text',
                    'id'          =>  'max_width',
                    'label'       =>  __( 'Width', $mf_domain ),
                    'name'        =>  'mf_field[option][max_width]',
                    'default'     =>  '96%',
                    'description' =>  __( 'width in pixels or percentage - e.g. &quot;320&quot;, &quot;320px&quot;, &quot;96%&quot; - this value can be overridden by specifying a &quot;width&quot; parameter with the', $mf_domain )
                                          . " $show_custom_field_tag shortcode",
                    'value'       =>  '96%',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'max_height'  => [
                    'type'        =>  'text',
                    'id'          =>  'max_height',
                    'label'       =>  __( 'Height', $mf_domain ),
                    'name'        =>  'mf_field[option][max_height]',
                    'default'     =>  '0',
                    'description' =>  __( 'height in pixels - e.g. &quot;240&quot;, &quot;240px&quot; - 0 lets the browser set the height to preserve the aspect ratio - recommended - this value can be overridden by specifying a &quot;height&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '0',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'align' => [
                    'type'        => 'select',
                    'id'          => 'align',
                    'label'       => __( 'Alignment', $mf_domain ),
                    'name'        =>  'mf_field[option][align]',
                    'default'     => 'aligncenter',
                    'options'     => [
                                        'aligncenter' => __( 'Center', $mf_domain ),
                                        'alignright'  => __( 'Right',  $mf_domain ),
                                        'alignleft'   => __( 'Left',   $mf_domain ),
                                        'alignnone'   => __( 'None',   $mf_domain )
                                    ],
                    'add_empty'   => FALSE,
                    'description' => __( 'alignment is effective only if a caption is specified - this value can be overridden by specifying an &quot;align&quot; parameter with the',
                                        $mf_domain ) . " $show_custom_field_tag shortcode - " . __( 'the parameter values are', $mf_domain )
                                        . ' &quot;aligncenter&quot;, &quot;alignright&quot; ' . __( 'and', $mf_domain ) . ' &quot;alignleft&quot;',
                    'value'       => 'aligncenter',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'class_name'  => [
                    'type'        =>  'text',
                    'id'          =>  'class_name',
                    'label'       =>  __( 'Class Name', $mf_domain ),
                    'name'        =>  'mf_field[option][class_name]',
                    'default'     =>  '',
                    'description' =>  __( 'This is the class option of the WordPress caption shortcode and is set only if a caption is specified - this value can be overridden by specifying a &quot;class_name&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'popup_width'  => [
                    'type'        =>  'text',
                    'id'          =>  'popup_width',
                    'label'       =>  __( 'Mouseover Popup Width', $mf_domain ),
                    'name'        =>  'mf_field[option][popup_width]',
                    'default'     =>  '60%',
                    'description' =>  __( 'mouseover popup width in pixels or percentage - e.g. &quot;240&quot;, &quot;240px&quot;, &quot;60%&quot; - this value can be overridden by specifying a &quot;popup_width&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '60%',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'popup_height'  => [
                    'type'        =>  'text',
                    'id'          =>  'popup_height',
                    'label'       =>  __( 'Mouseover Popup Height', $mf_domain ),
                    'name'        =>  'mf_field[option][popup_height]',
                    'default'     =>  '60%',
                    'description' =>  __( 'mouseover popup height in pixels or percentage - e.g. &quot;135&quot;, &quot;135px&quot;, &quot;60%&quot;- this value can be overridden by specifying a &quot;popup_height&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '60%',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'popup_style'  => [
                    'type'        =>  'text',
                    'id'          =>  'popup_style',
                    'label'       =>  __( 'Mouseover Popup Style', $mf_domain ),
                    'name'        =>  'mf_field[option][popup_style]',
                    'default'     =>  'background-color:transparent;text-align:center;',
                    'description' =>  __( 'mouseover popup style - this value can be overridden by specifying a &quot;popup_style&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode - &quot;background-color:transparent;text-align:center;&quot; "
                                          . __( 'is good for text overlays', $mf_domain ),
                    'value'       =>  'background-color:transparent;text-align:center;border:2px solid red;border-radius:7px;',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'popup_classname' => [
                    'type'        =>  'text',
                    'id'          =>  'popup_classname',
                    'label'       =>  __( 'Mouseover Popup Classname', $mf_domain ),
                    'name'        =>  'mf_field[option][popup_classname]',
                    'default'     =>  '',
                    'description' =>  __( 'mouseover popup classname - this value can be overridden by specifying a &quot;popup_classname&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ]
            ]
        ];
    }

    public function display_field( $field, $group_index = 1, $field_index = 1 ) {
        global $mf_domain, $post;
        # setup main field
        $field_id              = "mf2tk-$field[name]-$group_index-$field_index";
        $input_value           = str_replace( '"', '&quot;', $field['input_value'] );
        $opts                  = $field[ 'options' ];
        $width                 = mf2tk\get_data_option( 'max_width',  NULL, $opts, 320 );
        $height                = mf2tk\get_data_option( 'max_height', NULL, $opts, 240 );
        $orig_width            = $width;
        if ( substr_compare( $width, "%", -1 ) === 0 ) {
            $width             = 320;
            $height            = 0;
        } else if ( substr_compare( $width, "px", -2 ) === 0 ) {
            $width             = intval( substr( $width, 0, -2 ) );
        }
        if ( substr_compare( $height, "px", -2 ) === 0 ) {
            $width             = intval( substr( $height, 0, -2 ) );
        }
        $attrWidth             = $width  ? " width=\"$width\""   : '';
        $attrHeight            = $height ? " height=\"$height\"" : '';
        #set up caption field
        $caption_field_name    = $field['name'] . self::$suffix_caption;
        $caption_input_name    = sprintf( 'magicfields[%s][%d][%d]', $caption_field_name, $group_index, $field_index );
        $caption_input_value   = str_replace( '"', '&quot;', mf2tk\get_mf_post_value( $caption_field_name, $group_index, $field_index, '' ) );
        #set up link field
        $link_field_name       = $field['name'] . self::$suffix_link;
        $link_input_name       = sprintf( 'magicfields[%s][%d][%d]', $link_field_name, $group_index, $field_index );
        $link_input_value      = mf2tk\get_mf_post_value( $link_field_name, $group_index, $field_index, '' );
        #set up hover field
        $hover_field_name      = $field['name'] . self::$suffix_hover;
        $hover_input_name      = sprintf( 'magicfields[%s][%d][%d]', $hover_field_name, $group_index, $field_index );
        $hover_input_value     = mf2tk\get_mf_post_value( $hover_field_name, $group_index, $field_index, '' );
        $index                 = $group_index === 1 && $field_index === 1 ? '' : "<$group_index,$field_index>";
        # get the user defined shortcode
        $show_custom_field_tag = mf2tk\get_tags( )[ 'show_custom_field' ];
        # generate and return the HTML
        ob_start( );
?>
<div class="media_field_mf">
    <!-- url of image field -->
    <div class="mf2tk-field-input-main">
        <h6><?php _e( 'URL of the Image', $mf_domain ); ?></h6>
        <div class="mf2tk-field_value_pane">
            <input type="text" name="<?php echo $field['input_name']; ?>" id="<?php echo $field_id; ?>" class="mf2tk-img" maxlength="2048"
                placeholder="<?php _e( 'URL of the Image', $mf_domain ); ?>" value="<?php echo $input_value; ?>" <?php echo $field['input_validate']; ?>>
            <button id="<?php echo $field_id; ?>.media-library-button" class="mf2tk-media-library-button"><?php _e( 'Get URL from Media Library', $mf_domain ); ?></button>
            <button id="<?php echo $field_id; ?>.refresh-button" class="mf2tk-alt_media_admin-refresh"><?php _e( 'Reload Media', $mf_domain ); ?></button>
            <br>
            <div style="width:<?php echo $width; ?>px;padding-top:10px;margin:auto;">
                <img class="mf2tk-poster" src="<?php echo "$input_value\"{$attrWidth}{$attrHeight}"; ?>>
            </div>
        </div>
    </div>
    <!-- optional caption field -->
    <div class="mf2tk-field-input-optional mf2tk-caption-field">
        <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
        <h6><?php _e( 'Optional Caption for Image', $mf_domain ); ?></h6>
        <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
            <input type="text" name="<?php echo $caption_input_name; ?>" maxlength="256"
                placeholder="<?php _e( 'Optional Caption for Image', $mf_domain ); ?>" value="<?php echo $caption_input_value; ?>">
        </div>
    </div>
    <!-- optional link field -->
    <div class="mf2tk-field-input-optional">
        <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
        <h6><?php _e( 'Optional Link for Image', $mf_domain ); ?></h6>
        <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
            <input type="url" name="<?php echo $link_input_name; ?>" maxlength="2048"
                placeholder="<?php _e( 'Optional Link for Image', $mf_domain ); ?>" value="<?php echo $link_input_value; ?>">
            <button class="mf2tk-test-load-button" style="float:right;"><?php _e( 'Test Load', $mf_domain ); ?></button>
        </div>
    </div>
    <!-- optional hover field -->
    <div class="mf2tk-field-input-optional">
        <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
        <h6><?php _e( 'Optional Mouseover Popup for Image', $mf_domain ); ?></h6>
        <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
            <textarea name="<?php echo $hover_input_name; ?>" rows="8" cols="80"
                placeholder="<?php _e( 'Enter post content fragment with show_custom_field and/or show_macro shortcodes. This will be displayed as a popup when the mouse is over the image. Although this could be plain HTML, using a reusable content template is probably more convenient.',
                $mf_domain ); ?>"><?php echo $hover_input_value; ?></textarea>
        </div>
    </div>
<?php
        $output = ob_get_contents( ) . mf2tk\get_how_to_use_html( $field, $group_index, $field_index, $post, ' filter="url_to_media"',
            'alt_image_field::get_image', TRUE, $caption_input_value, $orig_width ) . '</div>';
        ob_end_clean( );
        return $output;
    }
  
    static function get_image( $field_name, $group_index = 1, $field_index = 1, $post_id = NULL, $atts = [ ] ) {
        global $post;
        if ( $post_id === NULL ) {
            $post_id = $post->ID;
        }
        $data         = mf2tk\get_data2( $field_name, $group_index, $field_index, $post_id );
        $opts         = $data[ 'options' ];
        $width        = mf2tk\get_data_option( 'width',  $atts, $opts, 320, 'max_width'  );
        $height       = mf2tk\get_data_option( 'height', $atts, $opts, 240, 'max_height' );
        # get optional caption
        $caption      = mf2tk\get_optional_field( $field_name, $group_index, $field_index, $post_id, self::$suffix_caption );
        # get optional link
        $link         = mf2tk\get_optional_field( $field_name, $group_index, $field_index, $post_id, self::$suffix_link    );
        # get optional mouse-over popup
        $hover        = mf2tk\get_optional_field( $field_name, $group_index, $field_index, $post_id, self::$suffix_hover   );
        $percent_mode = substr_compare( $width, "%", -1 ) === 0;
        if ( substr_compare( $width, "px", -2 ) === 0 ) {
            $width = substr( $width, 0, -2 );
        }
        if ( $percent_mode ) {
            $attrSize    = ' style="width:100%;height:auto;"';
            $hover_width = $caption ? '100%' : $width;
        } else {
            $attrSize    = ( $width  ? " width=\"$width\""   : '' ) . ( $height ? " height=\"$height\"" : '' );
            $hover_width = "{$width}px";
        }
        # if an optional mouse-over popup has been specified let the containing div handle the mouse-over event 
        if ( $hover ) {
            $popup_width     = mf2tk\get_data_option( 'popup_width',     $atts, $opts, 320 );
            $popup_width     = is_numeric( $popup_width )  ? "{$popup_width}px"  : $popup_width;
            $popup_height    = mf2tk\get_data_option( 'popup_height',    $atts, $opts, 240 );
            $popup_height    = is_numeric( $popup_height ) ? "{$popup_height}px" : $popup_height;
            $popup_style     = mf2tk\get_data_option( 'popup_style',     $atts, $opts      );
            $popup_classname = mf2tk\get_data_option( 'popup_classname', $atts, $opts      );
            $popup_classname = 'mf2tk-overlay' . ( $popup_classname ? ' ' . $popup_classname : '' );
            $hover           = mf2tk\do_macro( [ 'post' => $post_id ], $hover );
            $hover_class     = 'mf2tk-hover';
            $overlay         = <<<EOD
<div class="$popup_classname" style="display:none;position:absolute;z-index:10000;text-align:center;width:{$popup_width};height:{$popup_height};{$popup_style}">
    $hover
</div>
EOD;
        } else {
            $hover_class     = '';
            $overlay         = '';
        }
        $html = <<<EOD
<div class="$hover_class" style="position:relative;display:inline-block;width:{$hover_width};max-width:100%;padding:0px;">
    <a href="$link" target="_blank"><img src="$data[meta_value]"{$attrSize}></a>
    $overlay
</div>
EOD;
        # if an optional caption has been specified wrap image with caption container
        if ( $caption ) {
            $align      = mf2tk\get_data_option( 'align',      $atts, $opts, 'aligncenter' );
            $align      = mf2tk\re_align( $align );
            $class_name = mf2tk\get_data_option( 'class_name', $atts, $opts                );
            if ( !$width ) {
                $width = 160;
            }
            if ( !$class_name ) {
                $class_name = "mf2tk-{$data['type']}-{$field_name}";
            }
            $class_name .= ' mf2tk-alt-image';
            $html = alt_media_field::img_caption_shortcode( [ 'width' => $width, 'align' => $align, 'class' => $class_name, 'caption' => $caption ], $html );
            $html = preg_replace_callback( '/(<img\s.*?)>/', function( $matches ) {
                return $matches[1] . ' style="margin:0;max-width:100%">';  
            }, $html, 1 );
        }
        return $html;
    }  
}
