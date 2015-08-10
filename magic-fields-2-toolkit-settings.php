<?php

class Magic_Fields_2_Toolkit_Settings {
    
    public static $fields = [
        'alt_textbox', 'alt_related_type', 'alt_dropdown', 'alt_numeric', 'alt_url', 'alt_embed', 'alt_video', 'alt_audio',
        'alt_image', 'alt_table', 'alt_template'
    ];
    
    private static function sync_field_and_option( $field, $options ) {
        if ( array_key_exists( $field . '_field', $options ) ) {
            $mf_dir = MF_PATH . "/field_types/{$field}_field/";
            if ( !file_exists( $mf_dir ) ) {
                if ( !mkdir( $mf_dir, 0777 ) ) {
                    error_log( "mkdir( $mf_dir, 0777 ) failed" );
                }
            }
            if ( file_exists( $mf_dir ) ) {
                $my_dir = dirname( __FILE__ ) . "/{$field}_field/";
                foreach ( [ "{$field}_field.php", 'preview.jpg', 'icon_color.png', 'icon_gray.png' ] as $file ) {
                    if ( !copy( $my_dir . $file, $mf_dir . $file ) ) {
                        error_log( "copy( $my_dir{$file}, $mf_dir{$file} ) failed" );
                    }
                }
            }
        }
    }
    
    private static function do_field_type_option( $field, $input, $options ) {
        # make the reality (filesystem) match the options for alt_*
        $new = array_key_exists( $field . '_field', $input );
        $old = array_key_exists( $field . '_field', $options );
        if ( defined( 'MF_PATH' ) ) {
            $mf_dir = MF_PATH . "/field_types/{$field}_field/";
            $my_dir = dirname( __FILE__ ) . "/{$field}_field/";
            $files = [ "{$field}_field.php", 'preview.jpg', 'icon_color.png', 'icon_gray.png' ];
            $failed = [];
            if ( $new && !$old ) {
                if ( !file_exists( $mf_dir ) ) {
                    if ( mkdir( $mf_dir, 0777 ) ) {
                        foreach ( $files as $file ) {
                            if ( !copy( $my_dir . $file, $mf_dir . $file ) ) { $failed[] = "copy \"{$my_dir}{$file}\""; }
                        }
                    } else {
                        $failed[] = "mkdir \"$mf_dir\"";
                    }
                    if ( $failed ) { unset( $input["{$field}_field"] ); }
                }
            } else if ( !$new && $old ) {
               if ( file_exists( $mf_dir ) ) {
                    foreach ( $files as $file ) {
                        if ( !unlink( $mf_dir . $file ) ) { $failed[] = "unlink \"{$mf_dir}{$file}\""; }
                    }
                    if ( !rmdir( $mf_dir ) ) { $failed[] = "rmdir \"$mf_dir\""; }
                    if ( $failed ) { $input[$field . '_field'] = 'enabled'; }
               }
            }
            if ( $failed ) {
                add_settings_error( "magic_fields_2_toolkit_{$field}_field", "{$field}_field",
                    implode( ', ', $failed ) . ' failed!' );
            }
        }
        return $input;
    }
    
    public function __construct( ) {
        global $mf_domain;

        add_action( 'admin_enqueue_scripts', function( ) {
            wp_enqueue_style( 'mf2tk_admin', plugins_url( 'css/mf2tk_admin.css', __FILE__ ) );
            wp_enqueue_style( 'dashicons' );
            wp_enqueue_script( 'mf2tk_clean_mf_files', plugins_url( 'js/mf2tk_clean_mf_files.js', __FILE__ ), [ 'jquery' ] );
        } );
        
        add_action( 'admin_init', function( ) {
            global $mf_domain;
            if ( !defined( 'MF_PATH' ) ) {
                return;
            }
            $options = get_option( 'magic_fields_2_toolkit_enabled', [ ] );
            foreach ( Magic_Fields_2_Toolkit_Settings::$fields as $field ) {
                Magic_Fields_2_Toolkit_Settings::sync_field_and_option( $field, $options );
            }

            add_settings_section( 'magic_fields_2_toolkit_settings_sec', __( 'Features', $mf_domain ), function( ) {
                global $mf_domain;
?>
<div style="padding:10px 50px;"><?php _e( 'Use this form to enable specific features.', $mf_domain ); ?></div>
<?php
            }, 'magic-fields-2-toolkit-page' );

            $settings = [
                ['dumb_shortcodes',           __( 'Dumb Shortcodes',           $mf_domain ), 'shortcode'         ],
                ['dumb_macros',               __( 'Content Templates',         $mf_domain ), 'macros'            ],
                ['alt_template_field',        __( 'Alt Template Field',        $mf_domain ), 'alt_template'      ],
                ['alt_table_field',           __( 'Alt Table Field',           $mf_domain ), 'alt_table'         ],
                ['alt_numeric_field',         __( 'Alt Numeric Field',         $mf_domain ), 'alt_numeric'       ],
                ['alt_url_field',             __( 'Alt URL Field',             $mf_domain ), 'alt_url'           ],
                ['alt_related_type_field',    __( 'Alt Related Type Field',    $mf_domain ), 'alt_related'       ],
                ['alt_embed_field',           __( 'Alt Embed Field',           $mf_domain ), 'embed'             ],
                ['alt_video_field',           __( 'Alt Video Field',           $mf_domain ), 'video'             ],
                ['alt_audio_field',           __( 'Alt Audio Field',           $mf_domain ), 'audio'             ],
                ['alt_image_field',           __( 'Alt Image Field',           $mf_domain ), 'image'             ],
                ['alt_textbox_field',         __( 'Alt Textbox Field',         $mf_domain ), 'alt_textbox'       ],
                ['alt_dropdown_field',        __( 'Alt Dropdown Field',        $mf_domain ), 'alt_dropdown'      ],
                ['search_using_magic_fields', __( 'Search using Magic Fields', $mf_domain ), 'search'            ],
                ['custom_post_copier',        __( 'Custom Post Copier',        $mf_domain ), 'copy'              ],
                ['clean_files_mf',            __( 'Clean Folder files_mf',     $mf_domain ), 'unreferenced'      ],
                ['alt_get_audio',             __( 'Alt Get Audio',             $mf_domain ), 'alt_audio'         ],
                ['utility_functions',         __( 'Utility Functions',         $mf_domain ), 'utility_functions' ]
            ];

            array_walk( $settings, function( $v, $i, $options ) {
                $name  = $v[0];
                $title = $v[1];
                $help  = $v[2];
                add_settings_field( "magic_fields_2_toolkit_$name", $title, function( ) use ( $name, $help, $options ) {
?>
<a href="http://magicfields17.wordpress.com/magic-fields-2-toolkit-0-4-2/#<?php echo $help; ?>" target="_blank"><div class="dashicons dashicons-info"
    style="text-decoration:none;padding-right:10px;vertical-align:middle;"></div></a>
<input name="magic_fields_2_toolkit_enabled[<?php echo $name; ?>]" type="checkbox" value="enabled"
    <?php echo is_array( $options ) && array_key_exists( $name, $options ) ? ' checked' : ''; ?> style="margin:0px;">
<?php
                }, 'magic-fields-2-toolkit-page', 'magic_fields_2_toolkit_settings_sec' );
            }, $options );   # array_walk( $settings, function( $v, $i, $options ) {

            add_settings_section( 'magic_fields_2_toolkit_tags_sec', __( 'Labels', $mf_domain ), function( ) {
                global $mf_domain;
?>
<div style="padding:10px 50px;"><?php _e( 'The original labels that the toolkit used are inconsistent. Use this form to rename them to your liking, e.g., you can use short labels to reduce typing. Aliases are supported so that you can use the old label and new label simultaneously until the old label is completely replaced. N.B. this does not change existing labels in the post content of existing posts. My current convention is to use a &quot;mt_&quot; prefix but you are free to use your own convention.',
    $mf_domain ); ?></div>
<?php
            }, 'magic-fields-2-toolkit-page' );   # add_settings_section( 'magic_fields_2_toolkit_tags_sec', __( 'Labels', $mf_domain ), function( ) {

            $tags = [
                [ 'show_custom_field',       __( 'shortcode to show a custom field',           $mf_domain ) ],
                [ 'show_custom_field_alias', __( 'alias shortcode to show a custom field',     $mf_domain ) ],
                [ 'show_macro',              __( 'shortcode to show a content template',       $mf_domain ) ],
                [ 'show_macro_alias',        __( 'alias shortcode to show a content template', $mf_domain ) ],
                [ 'mt_show_gallery',         __( 'shortcode to show a gallery',                $mf_domain ) ],
                [ 'mt_show_gallery_alias',   __( 'alias shortcode to show a gallery',          $mf_domain ) ],
                [ 'mt_show_tabs',            __( 'shortcode to show tabs',                     $mf_domain ) ],
                [ 'mt_show_tabs_alias',      __( 'alias shortcode to tabs',                    $mf_domain ) ]   
            ];
            
            array_walk( $tags, function( $v, $i, $options ) {
                $name  = $v[0];
                $title = $v[1];
                $value = !empty( $options[ $name ] ) ? $options[ $name ] : '';
                add_settings_field( "magic_fields_2_toolkit-tags-$name", $title, function( $a ) {
                    $name  = $a[0];
                    $value = $a[1];
                    echo( "<input name=\"magic_fields_2_toolkit_tags[$name]\" type=\"text\" value=\"$value\">" );
                }, 'magic-fields-2-toolkit-page', 'magic_fields_2_toolkit_tags_sec', [ $name, $value ] );
            }, mf2tk\get_tags( ) );   # array_walk( $tags, function( $v, $i, $options ) {

            add_settings_section( 'magic_fields_2_toolkit_sync_sec', __( 'Sync the Toolkit\'s Fields with the Fields of Magic Fields 2', $mf_domain ),
                function( ) {
                    global $mf_domain;
?>
<div>
<div style="width:70%;padding:10px 50px 50px 50px;float:left;"><?php _e( 'The latest version of the toolkit\'s fields ( alt_*_field ) must be copied to the &quot;fields_types&quot; folder of the &quot;Magic Fields 2&quot; plugin. The toolkit should handle this automatically. However, this can fail to happen if you update the Magic Fields 2 plugin (Since the toolkit needs to be installed after &quot;Magic Fields 2&quot;.) or you upgrade the toolkit by manually copying the files (The activation code will not run in  this case.). You can force the toolkit to synchronize its fields with those in the &quot;fields_types&quot; folder of &quot;Magic Fields 2&quot; plugin at anytime by clicking the &quot;Sync Fields&quot; button to the right.',
    $mf_domain); ?></div>
<input name="mf2tk-sync-fields" id="mf2tk-sync-fields" class="button button-primary" value="Sync Fields" type="button"
    style="float:left;margin:30px 20px;">
<div style="clear:both;"></div>
</div>
<?php
            }, 'magic-fields-2-toolkit-page' );   # add_settings_section( 'magic_fields_2_toolkit_sync_sec', __( 'Sync the Toolkit\'s Fields with the Fields of Magic Fields 2', $mf_domain ),
                
            register_setting( 'magic-fields-2-toolkit-page', 'magic_fields_2_toolkit_enabled', function( $input ) {
                if ( $input === NULL ) {
                    $input = [ ];
                }
                $options = get_option( 'magic_fields_2_toolkit_enabled', [ ] );
                foreach ( Magic_Fields_2_Toolkit_Settings::$fields as $field ) {
                    $input = Magic_Fields_2_Toolkit_Settings::do_field_type_option( $field, $input, $options );
                }
                return $input;
            } );   # register_setting( 'magic-fields-2-toolkit-page', 'magic_fields_2_toolkit_enabled', function( $input ) {

            register_setting( 'magic-fields-2-toolkit-page', 'magic_fields_2_toolkit_tags', function( $input ) {
                if ( $input === NULL ) {
                    $input = [ ];
                }
                if ( array_key_exists( 'update_from_tpcti', $input ) ) {
                    # this update was generated by TPCTI so don't sync with TCPTI otherwise you have infinite recursion
                    unset( $input[ 'update_from_tpcti' ] );
                    return $input;
                }
                if ( defined( 'mf2tk\TPCTI_VERSION' ) && mf2tk\TPCTI_VERSION >= 1.0 ) {
                    # synchronize the 'show_macro' tag with the TPCTI option 'shortcode_name' 
                    $tpcti_options = get_option( 'tpcti_options', ( object ) [
                        'shortcode_name' => 'show_macro',
                        'content_macro_post_type' => 'content_macro',
                        'filter' => '@',
                        'post_member' => '.',
                        'use_native_mode' => false
                    ] );
                    $tpcti_options->shortcode_name       = $input[ 'show_macro' ];
                    $tpcti_options->shortcode_name_alias = $input[ 'show_macro_alias' ];
                    update_option( 'tpcti_options', $tpcti_options );
                }
                return $input;
            } );   # register_setting( 'magic-fields-2-toolkit-page', 'magic_fields_2_toolkit_tags', function( $input ) {
            
        } );   # add_action( 'admin_init', function() {
            
        add_action( 'admin_menu', function( ) {
            global $mf_domain;
            if ( !defined( 'MF_PATH' ) ) {
                return;
            }
            add_options_page( __( 'Magic Fields 2 Toolkit', $mf_domain ), __( 'Magic Fields 2 Toolkit', $mf_domain ), 'manage_options',
                'magic-fields-2-toolkit-page', function( ) {
                global $mf_domain;
                if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == TRUE ) {
                }
                echo( '<h1>' . __( 'Magic Fields 2 Toolkit', $mf_domain ) . '</h1><form method="post" action="options.php">' );
                settings_fields( 'magic-fields-2-toolkit-page' ); 
                do_settings_sections( 'magic-fields-2-toolkit-page' );
                submit_button( );
                echo( '</form>' );
?>
<div style="border:2px solid black;background-color:LightGray;padding:10px;margin:30px 25px;font-size:larger;font-weight:bold;">
<?php _e( 'For usage instructions please visit ', $mf_domain ); ?>
<a href="http://magicfields17.wordpress.com/magic-fields-2-toolkit-0-4-2/" target="_blank"><?php _e( 'the online documentation', $mf_domain ); ?></a>.</div>
<?php
            } );   # add_options_page( 'Magic Fields 2 Toolkit', 'Magic Fields 2 Toolkit', 'manage_options', 'magic-fields-2-toolkit-page', function( ) {
        }, 11 );   # add_action( 'admin_menu', function( ) {

        # AJAX action 'wp_ajax_mf2tk_sync_fields' syncs the toolkit's fields with the fields in "Magic Fields 2"

        add_action( 'wp_ajax_mf2tk_sync_fields', function( ) {
            global $mf_domain;
            $options = get_option( 'magic_fields_2_toolkit_enabled', [ ] );
            foreach ( Magic_Fields_2_Toolkit_Settings::$fields as $field ) {
                Magic_Fields_2_Toolkit_Settings::sync_field_and_option( $field, $options );
            }
            die( __( 'The fields: ', $mf_domain ) . implode( ', ', Magic_Fields_2_Toolkit_Settings::$fields ) . ' '
                . __( 'have been synchronized.', $mf_domain ) );
        } );

    }   # public function __construct( ) {

}   # class Magic_Fields_2_Toolkit_Settings {

new Magic_Fields_2_Toolkit_Settings( );
