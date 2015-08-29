<?php

require_once( WP_PLUGIN_DIR . '/magic-fields-2-toolkit/alt_media_field.php' );

class alt_video_field extends alt_media_field {

    public function _update_description( ) {
        global $mf_domain;
        $this->description = __( 'This is a Magic Fields 2 field for WordPress\'s video shortcode facility.', $mf_domain );
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
                    'description' =>  __( 'width in pixels - this value can be overridden by specifying a &quot;width&quot; parameter with the', $mf_domain )
                                          . " $show_custom_field_tag shortcode",
                    'value'       =>  '320',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'max_height'  => [
                    'type'        =>  'text',
                    'id'          =>  'max_height',
                    'label'       =>  __( 'Height', $mf_domain ),
                    'name'        =>  'mf_field[option][max_height]',
                    'default'     =>  '0',
                    'description' =>  __( 'height in pixels - 0 lets the browser set the height to preserve the aspect ratio - recommended but you must at least load the meta data on page load - 0 does not work for Flash videos - this value can be overridden by specifying a &quot;height&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '0',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'loop' => [
                    'type'        => 'checkbox',
                    'id'          => 'loop',
                    'label'       => __( 'Loop to beginning when finished and continue playing', $mf_domain ),
                    'name'        => 'mf_field[option][loop]',
                    'default'     =>  '',
                    'description' =>  __( 'this value can be overridden by specifying an "loop" parameter with the', $mf_domain )
                                      . " $show_custom_field_tag shortcode - " . __( 'the parameter value is', $mf_domain ) . ' "on" '
                                      . __( 'or', $mf_domain ) . ' "off"',
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
                    'description' =>  __( 'this value can be overridden by specifying an "autoplay" parameter with the', $mf_domain )
                                      . " $show_custom_field_tag shortcode - " . __( 'the parameter value is', $mf_domain ) . ' "on" '
                                      . __( 'or', $mf_domain ) . ' "off"',
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
                                        'none'     => __( 'Do not load the video',  $mf_domain ),
                                        'auto'     => __( 'Load the entire video',  $mf_domain )
                                     ],
                    'add_empty'   => FALSE,
                    'description' => __( 'this value can be overridden by specifying a &quot;preload&quot; parameter with the',
                                         $mf_domain ) . " $show_custom_field_tag shortcode - " . __( 'the parameter value is', $mf_domain )
                                         . ' &quot;metadata&quot;, &quot;none&quot; ' .  __( 'or', $mf_domain ) . ' &quot;auto&quot; - &quot;auto&quot; '
                                         . __( 'loads the entire video', $mf_domain ),
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
                                        'aligncenter' => __( 'Center', $mf_domain ),
                                        'alignright'  => __( 'Right',  $mf_domain ),
                                        'alignleft'   => __( 'Left',   $mf_domain ),
                                        'alignnone'   => __( 'None',   $mf_domain )
                                     ],
                    'add_empty'   => FALSE,
                    'description' => __( 'alignment is effective only if a caption is specified - this value can be overridden by specifying an &quot;align&quot; parameter with the',
                                         $mf_domain ) . " $show_custom_field_tag shortcode - " . __( 'the parameter value is', $mf_domain )
                                        . ' &quot;aligncenter&quot;, &quot;alignright&quot;, &quot;alignleft&quot; ' . __( 'or', $mf_domain )
                                        . ' &quot;alignnone&quot;',
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
                'aspect_ratio'  => [
                    'type'        =>  'text',
                    'id'          =>  'aspect_ratio',
                    'label'       =>  __( 'Default Aspect Ratio - width:height, e.g. 16:9', $mf_domain ),
                    'name'        =>  'mf_field[option][aspect_ratio]',
                    'default'     =>  '4:3',
                    'description' =>  __( 'If the browser determines the height then use this as the aspect ratio when the browser is unable to determine the aspect ratio - i.e. for Flash videos - this value can be overridden by specifying a &quot;aspect_ratio&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       =>  '4:3',
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
        return parent::template( $field, $group_index, $field_index, 'video', 'wp_video_shortcode' );
    }

    public static function get_video( $field_name, $group_index = 1, $field_index = 1, $post_id = NULL, $atts = [ ] ) {
        global $post;
        if ( !$post_id ) {
            $post_id = $post->ID;
        }
        $data          = mf2tk\get_data2( $field_name, $group_index, $field_index, $post_id );
        $options       = $data[ 'options' ];
        $original_atts = $atts;
        $invalid_atts  = [ ];   # since parent::get_template() is shared with audio some entries are media specific
        parent::get_template( $field_name, $group_index, $field_index, $post_id, $atts, $invalid_atts, 'video', 'wp_video_shortcode', 'alt_video_field',
            $height, $width, $hover, $caption, $poster, $link, $html );
        $percent_mode  = substr_compare( $width, "%", -1 ) === 0;
        if ( $percent_mode ) {
            # since wp_video_shortcode() was given a dummy integer width and height replace those with 100%
            $html = preg_replace_callback( '/style\s*=\s*("|\')(.*?;)?\s*(width:.*?)(;|\1)/', function( $matches ) {
                error_log( 'get_video():$matches=' . print_r( $matches, true ) );
                return str_replace( $matches[3], 'width:100%', $matches[0] );
            }, $html );
            $html = preg_replace_callback( '/<video\s[^>]*?(\sheight=("|\').*?\2)/', function( $matches ) {
                error_log( 'get_video():$matches=' . print_r( $matches, true ) );
                return str_replace( $matches[1], '', $matches[0] );
            }, $html );
            $html = preg_replace_callback( '/<video\s[^>]*?(\swidth=("|\').*?\2)/', function( $matches ) {
                error_log( 'get_video():$matches=' . print_r( $matches, true ) );
                return str_replace( $matches[1], ' width="100%" height="100%" style="width:100%;height:100%;"', $matches[0] );
            }, $html );
            error_log( 'get_video():$html=' . $html );
        }
        if ( !$percent_mode && ( !$height || !$width ) && preg_match( '/<video\s+class="wp-video-shortcode"\s+id="([^"]+)"/', $html, $matches ) ) {
            # height or width not specified so emit javascript patch to resize mediaelement.js elements according to aspect ratio
            $id = $matches[1];
            $aspect_ratio = mf2tk\get_data_option( 'aspect_ratio', $original_atts, $options, '4:3' );
            if ( preg_match( '/([\d\.]+):([\d\.]+)/', $aspect_ratio, $matches ) ) { $aspect_ratio = $matches[1] / $matches[2]; }
            $do_width = !$width ? 'true' : 'false';
            $html .= <<<EOD
<script type="text/javascript">
    jQuery(document).ready(function(){mf2tkResizeVideo("video.wp-video-shortcode#$id",$aspect_ratio,$do_width);});
</script>
EOD;
        }
        $hover_width = $caption ? '100%' : "{$width}px";
        $html = <<<EOD
<div style="position:relative;z-index:0;display:inline-block;width:{$hover_width};padding:0px;">
    $html
EOD;
        # if an optional mouse-over popup has been specified let the containing div handle the mouse-over event 
        if ( $hover ) {
            $attrWidth       = $width  ? " width=\"$width\""   : '';
            $attrHeight      = $height ? " height=\"$height\"" : '';
            $popup_width     = mf2tk\get_data_option( 'popup_width',     $original_atts, $options, 320 );
            $popup_height    = mf2tk\get_data_option( 'popup_height',    $original_atts, $options, 240 );
            $popup_style     = mf2tk\get_data_option( 'popup_style',     $original_atts, $options, 'background-color:white;border:2px solid black;' );
            $popup_classname = mf2tk\get_data_option( 'popup_classname', $original_atts, $options );
            $popup_classname = 'mf2tk-overlay' . ( $popup_classname ? ' ' . $popup_classname : '' );
            $hover           = mf2tk\do_macro( [ 'post' => $post_id ], $hover );
            $hover_class     = 'mf2tk-hover';
            $hover_width     = $caption ? '80%' : ( ( 4 * $width ) / 5 ) . 'px';
            $html .= <<<EOD
    <div class="$hover_class mf2tk-top-80" style="position:absolute;left:0px;top:0px;z-index:10;display:block;width:{$hover_width};height:80%;">
        <div class="$popup_classname" style="display:none;position:absolute;z-index:10000;text-align:center;width:{$popup_width}px;height:{$popup_height}px;{$popup_style}">
            $hover
        </div>
    </div>
EOD;
        }
        $html .= <<<EOD
</div>
EOD;
        if ( $caption ) {
            $width      = mf2tk\get_data_option( 'width',      $original_atts, $options, 160,           'max_width' );
            $class_name = mf2tk\get_data_option( 'class_name', $original_atts, $options                             );
            $align      = mf2tk\get_data_option( 'align',      $original_atts, $options, 'aligncenter'              );
            $align      = mf2tk\re_align( $align );
            if ( !$width ) {
                $width = 160;
            }
            if ( !$class_name ) {
                $class_name = "mf2tk-{$data['type']}-{$field_name}";
            }
            $class_name .= ' mf2tk-alt-video';
            $html = alt_media_field::img_caption_shortcode( [ 'width' => $width, 'align' => $align, 'class' => $class_name, 'caption' => $caption ], $html );
            if ( !$percent_mode ) {
                $html = preg_replace_callback( '/<div\s.*?style=".*?(width:\s*\d+px)/', function( $matches ) use ( $width ) {
                    return str_replace( $matches[1], "width:{$width}px", $matches[0] );  
                }, $html, 1 );
            }
        }      
        return $html;
    }  
}
