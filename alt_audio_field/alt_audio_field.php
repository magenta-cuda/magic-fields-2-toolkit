<?php

require_once( WP_PLUGIN_DIR . '/magic-fields-2-toolkit/alt_media_field.php' );

class alt_audio_field extends alt_media_field {

    public function _update_description( ) {
        global $mf_domain;
        $this->description = __( 'This is a Magic Fields 2 field for WordPress\'s audio shortcode facility.', $mf_domain );
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
                    'default'     =>  '320',
                    'description' =>  __( 'width in pixels for optional caption and/or optional image - this value can be overridden by specifying a &quot;width&quot; parameter with the ',
                                          $mf_domain ) . "$show_custom_field_tag shortcode",
                    'value'       =>  '320',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'max_height'  => [
                    'type'        =>  'text',
                    'id'          =>  'max_height',
                    'label'       =>  __( 'Height', $mf_domain ),
                    'name'        =>  'mf_field[option][max_height]',
                    'default'     =>  '240',
                    'description' =>  __( 'height in pixels for the optional image - 0 lets the browser set the height to preserve the aspect ratio - recommended - this value can be overridden by specifying a &quot;height&quot; parameter with the ',
                                          $mf_domain ) . "$show_custom_field_tag shortcode",
                    'value'       =>  '240',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'loop' => [
                    'type'        => 'checkbox',
                    'id'          => 'loop',
                    'label'       => __( 'Loop to beginning when finished and continue playing', $mf_domain ),
                    'name'        => 'mf_field[option][loop]',
                    'default'     =>  '',
                    'description' => __( 'this value can be overridden by specifying an "loop" parameter with the', $mf_domain )
                                         . " $show_custom_field_tag shortcode - " . __( 'the parameter value is', $mf_domain )
                                         . ' "on" ' . __( 'or', $mf_domain ) . ' "off"',
                    'value'       =>  '',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'autoplay' => [
                    'type'        => 'checkbox',
                    'id'          => 'autoplay',
                    'label'       => __( 'Automatically play as soon as the media file is ready', $mf_domain ),
                    'name'        => 'mf_field[option][autoplay]',
                    'default'     =>  '',
                    'description' => __( 'this value can be overridden by specifying an "autoplay" parameter with the', $mf_domain )
                                         . " $show_custom_field_tag shortcode - " . __( 'the parameter value is', $mf_domain )
                                         . ' "on" ' . __( 'or', $mf_domain ) . ' "off"',
                    'value'       =>  '',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'preload' => [
                    'type'        => 'select',
                    'id'          => 'preload',
                    'label'       => __( 'When the page loads', $mf_domain ),
                    'name'        =>  'mf_field[option][preload]',
                    'default'     => 'metadata',
                    'options'     => [
                                        'metadata' => __( 'load only the metadata', $mf_domain ),
                                        'none'     => __( 'Do not load the audio',  $mf_domain ),
                                        'auto'     => __( 'Load the entire audio',  $mf_domain )
                                    ],
                    'add_empty'   => FALSE,
                    'description' => __( 'this value can be overridden by specifying a &quot;preload&quot; parameter with the', $mf_domain )
                                         . " $show_custom_field_tag shortcode - " . __( 'the parameter value is', $mf_domain )
                                         . ' &quot;metadata&quot;, &quot;none&quot; ' . __( 'or', $mf_domain ) . ' &quot;auto&quot; - &quot;auto&quot; '
                                         . __( 'loads the entire audio', $mf_domain ),
                    'value'       => 'metadata',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'align' => [
                    'type'        => 'select',
                    'id'          => 'align',
                    'label'       => __( 'Alignment', $mf_domain ),
                    'name'        =>  'mf_field[option][align]',
                    'default'     => 'aligncenter',
                    'options'     => [
                                        'aligncenter' => __( 'Center',  $mf_domain ),
                                        'alignright'  => __( 'Right',   $mf_domain ),
                                        'alignleft'   => __( 'Left',    $mf_domain ),
                                        'alignnone'   => __( 'None',    $mf_domain )
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
                    'default'     =>  '320',
                    'description' =>  __( 'mouseover popup width in pixels - this value can be overridden by specifying a &quot;popup_width&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '320',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'popup_height'  => [
                    'type'        =>  'text',
                    'id'          =>  'popup_height',
                    'label'       =>  __( 'Mouseover Popup Height', $mf_domain ),
                    'name'        =>  'mf_field[option][popup_height]',
                    'default'     =>  '240',
                    'description' =>  __( 'mouseover popup height in pixels - this value can be overridden by specifying a &quot;popup_height&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '240',
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
                    'value'       =>  'background-color:transparent;text-align:center;',
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
        return parent::template( $field, $group_index, $field_index, 'audio', 'wp_audio_shortcode' );
    }
  
    public static function get_audio( $field_name, $group_index = 1, $field_index = 1, $post_id = NULL, $atts = [ ] ) {
        global $post;
        if ( !$post_id ) {
            $post_id = $post->ID;
        }
        $original_atts = $atts;   # save $atts since magic-fields-2-alt-media-get-template.php will modify $atts
        $invalid_atts  = [ 'width' => true, 'height' => true, 'poster' => true ]; # since parent::get_template( ) is shared with video and some entries are media specific
        parent::get_template( $field_name, $group_index, $field_index, $post_id, $atts, $invalid_atts, 'audio', 'wp_audio_shortcode', 'alt_audio_field',
            $height, $width, $hover, $caption, $poster, $link, $html );
        $data       = mf2tk\get_data2( $field_name, $group_index, $field_index, $post_id );
        $opts       = $data[ 'options' ];
        $width      = mf2tk\get_data_option( 'width',  $original_atts, $opts, 320, 'max_width'  );
        $height     = mf2tk\get_data_option( 'height', $original_atts, $opts, 240, 'max_height' );
        $attrWidth  = $width  ? " width=\"$width\""   : '';
        $attrHeight = $height ? " height=\"$height\"" : '';
        # attach optional poster image
        if ( $poster ) {
            # if an optional mouse-over popup has been specified let the containing div handle the mouse-over event
            # N.B. the mouse-over popup and clickable link feature for audio requires that a poster image be specified
            if ( $hover ) {
                $popup_width     = mf2tk\get_data_option( 'popup_width',     $original_atts, $opts, 320 );
                $popup_height    = mf2tk\get_data_option( 'popup_height',    $original_atts, $opts, 240 );
                $popup_style     = mf2tk\get_data_option( 'popup_style',     $original_atts, $opts,
                                                          'background-color:white;border:2px solid black;' );
                $popup_classname = mf2tk\get_data_option( 'popup_classname', $original_atts, $opts      );
                $popup_classname = 'mf2tk-overlay' . ( $popup_classname ? ' ' . $popup_classname : '' );
                $hover = mf2tk\do_macro( [ 'post' => $post_id ], $hover );
                $hover_class = 'mf2tk-hover';
                $overlay = <<<EOD
<div class="$popup_classname"
    style="display:none;position:absolute;z-index:10000;text-align:center;width:{$popup_width}px;height:{$popup_height}px;{$popup_style}">
    $hover
</div>
EOD;
            $html = <<<EOD
<div class="$hover_class" style="display:inline-block;width:{$width}px;padding:0px;margin:0px;">
    <a href="$link" target="_blank"><img src="$poster"{$attrWidth}{$attrHeight}></a>
    $overlay
</div>
$html
EOD;
            } else {
                $html = <<<EOD
<div style="display:inline-block;width:{$width}px;padding:0px;">
    <img src="$poster"{$attrWidth}{$attrHeight}>
    $html
</div>
EOD;
            }
        }
        # attach optional caption
        if ( $caption ) {
            $align      = mf2tk\get_data_option( 'align',      $original_atts, $opts, 'aligncenter' );
            $align      = mf2tk\re_align( $align );
            $class_name = mf2tk\get_data_option( 'class_name', $original_atts, $opts                );
            if ( !$width      ) { $width = 160;                                        }
            if ( !$class_name ) { $class_name = "mf2tk-{$data['type']}-{$field_name}"; }
            $class_name .= ' mf2tk-alt-image';
            $html = img_caption_shortcode( array( 'width' => $width, 'align' => $align,
                'class' => $class_name, 'caption' => $caption ), $html );
            $html = preg_replace_callback( '/<div\s.*?style=".*?(width:\s*\d+px)/', function( $matches ) use ( $width ) {
                return str_replace( $matches[1], "width:{$width}px;max-width:100%", $matches[0] );  
            }, $html, 1 );
            $html = preg_replace_callback( '/(<img\s.*?)>/', function( $matches ) {
                return $matches[1] . ' style="margin:0;max-width:100%">';  
            }, $html, 1 );
        }
        return $html;
    }  
}
