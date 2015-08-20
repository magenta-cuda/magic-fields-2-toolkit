<?php

class alt_embed_field extends mf_custom_fields {

    private static $suffix_caption = '_mf2tk_caption';

    public function _update_description( ) {
        global $mf_domain;
        $this->description = __( 'This is a Magic Fields 2 field for WordPress\'s embed shortcode facility.', $mf_domain );
    }
  
    public function _options( ) {
        global $mf_domain;
        $show_custom_field_tag = mf2tk\get_tags( )[ 'show_custom_field' ];            
        return [
            'option'  => [
                'max_width'  => [
                    'type'        =>  'text',
                    'id'          =>  'embed_max_width',
                    'label'       =>  __( 'Width', $mf_domain ),
                    'name'        =>  'mf_field[option][max_width]',
                    'default'     =>  '320',
                    'description' =>  __( 'width in pixels - this value can be overridden by specifying a &quot;width&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '320',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'max_height'  => [
                    'type'        =>  'text',
                    'id'          =>  'embed_max_height',
                    'label'       =>  __( 'Height', $mf_domain ),
                    'name'        =>  'mf_field[option][max_height]',
                    'default'     =>  '0',
                    'description' =>  __( 'height in pixels - 0 lets the browser set the height to preserve the aspect ratio - recommended - this value can be overridden by specifying a &quot;height&quot; parameter with the',
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
                    'default'     => '',
                    'options'     => [
                                        'aligncenter' => __( 'Center', $mf_domain ),
                                        'alignright'  => __( 'Right',  $mf_domain ),
                                        'alignleft'   => __( 'Left',   $mf_domain ),
                                        'alignnone'   => __( 'None',   $mf_domain )
                                     ],
                    'add_empty'   => FALSE,
                    'description' => __( 'alignment is effective only if a caption is specified - this value can be overridden by specifying an &quot;align&quot; parameter with the',
                                         $mf_domain ) . " $show_custom_field_tag shortcode - " . __( 'the parameter values are', $mf_domain )
                                         . ' &quot;aligncenter&quot;, &quot;alignright&quot;, &quot;alignleft&quot; ' . __( 'and', $mf_domain )
                                         . ' &quot;alignnone&quot;',
                    'value'       => '',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'class_name'  => [
                    'type'        =>  'text',
                    'id'          =>  'class_name',
                    'label'       =>  __( 'Class Name', $mf_domain ),
                    'name'        =>  'mf_field[option][class_name]',
                    'default'     =>  '',
                    'description' =>  __( 'This is the class option of the WordPress caption shortcode and is set only if a caption is specified - this value can be overridden by specifying a "class_name" parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ]
            ]
        ];
    }

    public function display_field( $field, $group_index = 1, $field_index = 1 ) {
        global $post;
        global $mf_domain;
        $opts   = $field[ 'options' ];
        $width  = mf2tk\get_data_option( 'max_width',  NULL, $opts, 320 );
        $height = mf2tk\get_data_option( 'max_height', NULL, $opts, 240 );
        $args   = [ ];
        if ( $width ) {
            $args[ 'width' ]  = $width;
        }
        if ( $height ) {
            $args[ 'height' ] = $height;
        }
        $caption_field_name  = $field[ 'name' ] . self::$suffix_caption;
        $caption_input_name  = sprintf( 'magicfields[%s][%d][%d]', $caption_field_name, $group_index, $field_index );
        $caption_input_value = str_replace( '"', '&quot;', mf2tk\get_mf_post_value( $caption_field_name, $group_index, $field_index, '' ) );
        $embed               = wp_oembed_get( $field['input_value'], $args );
        $index               = $group_index === 1 && $field_index === 1 ? '' : "<$group_index,$field_index>";
        # generate and return the HTML
        ob_start( );
?>
<div class="media_field_mf">
    <div class="mf2tk-field-input-main">
        <h6><?php _e( 'URL of the Page Containing the Embed Element', $mf_domain ); ?></h6>
        <div class="mf2tk-field_value_pane">
            <input type="text" name="<?php echo $field['input_name']; ?>" class="mf2tk-alt_embed_admin-url" maxlength="2048"
                placeholder="<?php _e( 'URL of the page containing the embed element', $mf_domain ); ?>" value="<?php echo $field['input_value']; ?>"
                <?php echo $field['input_validate']; ?>>
            <button class="mf2tk-alt_embed_admin-refresh"><?php _e( 'Reload Embed', $mf_domain ); ?></button>
            <h6 style="display:inline;"><?php _e( 'This is the Embed element for the URL specified above.', $mf_domain ); ?></h6>
            <div class="mf2tk-alt_embed_admin-embed" style="width:<?php echo $width; ?>px;padding-top:10px;margin:auto;"><?php echo $embed; ?></div>
        </div>
    </div>
    <!-- optional caption field -->
    <div class="mf2tk-field-input-optional mf2tk-caption-field">
        <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
        <h6><?php _e( 'Optional Caption for Embed', $mf_domain ); ?></h6>
        <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
            <input type="text" name="<?php echo $caption_input_name; ?>" maxlength="256" placeholder="<?php _e( 'optional caption for embed', $mf_domain ); ?>"
                value="<?php echo $caption_input_value; ?>">
        </div>
    </div>
<?php
        $output = ob_get_contents( ) . mf2tk\get_how_to_use_html( $field, $group_index, $field_index, $post, ' filter="url_to_media"',
            'alt_embed_field::get_embed', TRUE, $caption_input_value, $width ) . '</div>';
        ob_end_clean( );
        error_log( '##### alt_embed_field::display_field():$output=' . $output );
        return $output;
    }
  
    static function get_embed( $field_name, $group_index = 1, $field_index = 1, $post_id = NULL, $atts = [ ] ) {
        global $wpdb, $post;
        if ( !$post_id ) {
            $post_id = $post->ID;
        }
        $data       = mf2tk\get_data2( $field_name, $group_index, $field_index, $post_id );
        $opts       = $data[ 'options' ];
        # get width and height
        $max_width  = mf2tk\get_data_option( 'width',  $atts, $opts, 320, 'max_width'  );
        $max_height = mf2tk\get_data_option( 'height', $atts, $opts, 240, 'max_height' );
        # get optional caption
        $caption    = mf2tk\get_optional_field( $field_name, $group_index, $field_index, $post_id, self::$suffix_caption );
        # If value is not an URL
        if ( substr_compare( $data[ 'meta_value' ], 'http:', 0, 5 ) !== 0 && substr_compare( $data[ 'meta_value' ], 'https:', 0, 6 ) !== 0 ) {
            # Then it should be the HTML to be embedded
            return $data[ 'meta_value' ];
        } else {
            // Else use oEmbed to get the HTML
            $args = [ ];
            if ( $max_width  ) {
                $args[ 'width' ]  = $max_width;
            }
            if ( $max_height ) {
                $args[ 'height' ] = $max_height;
            }
            $html = wp_oembed_get( $data['meta_value'], $args );
            if ( $caption ) {
                $class_name = mf2tk\get_data_option( 'class_name', $atts, $opts );
                $align      = mf2tk\get_data_option( 'align',      $atts, $opts, 'aligncenter' );
                $align      = mf2tk\re_align( $align );
                if ( !$max_width  ) {
                    $max_width = 240;
                }
                if ( !$class_name ) {
                    $class_name = "mf2tk-{$data['type']}-{$field_name}";
                }
                $class_name .= ' mf2tk-alt-embed';
                $html = img_caption_shortcode( [ 'width' => $max_width, 'align' => $align, 'class' => $class_name, 'caption' => $caption ],
                    "<div style=\"width:{$max_width}px;display:inline-block;padding:0px;margin:0px;\">$html</div>" );
                $html = preg_replace_callback( '/<div\s.*?style=".*?(width:\s*\d+px)/', function( $matches ) use ( $max_width ) {
                    return str_replace( $matches[1], "width:{$max_width}px;max-width:100%", $matches[0] );
                }, $html, 1 );
            }
            return $html;
        }
    }
    
    # admin_refresh( ) is invoked by an AJAX request to reload the media element in the post editor
    
    public static function admin_refresh( ) {
        global $wpdb;
        preg_match( '/magicfields\[(\w+)\]\[\d+\]\[\d+\]/', $_REQUEST[ 'field' ], $matches );
        $options = unserialize( $wpdb->get_var( $wpdb->prepare( 'SELECT options FROM ' . MF_TABLE_CUSTOM_FIELDS . ' WHERE name = %s', $matches[1] ) ) );
        $width   = $options[ 'max_width' ];
        $height  = $options[ 'max_height' ];
        $args    = [ ];
        if ( $width  ) {
            $args['width']  = $width;
        }
        if ( $height ) {
            $args['height'] = $height;
        }
        echo wp_oembed_get( $_REQUEST[ 'url' ], $args );
        die;
    }
}
