<?php

/*  Copyright 2013  Magenta Cuda

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace {
  
class Magic_Fields_2_Toolkit_Init {
    public function __construct( ) {
        global $wpdb;
        add_action( 'admin_init', function( ) {
            if ( !is_plugin_active( 'magic-fields-2/main.php' ) ) {
                add_action( 'admin_notices', function( ) {
?>
<div style="clear:both;font-weight:bold;border:2px solid red;padding:5px 10px;margin:10px;">Magic Templates requires that plugin <a href="https://wordpress.org/plugins/magic-fields-2/">Magic Fields 2</a> be installed
and activated.</div> 
<?php
                } );
            }
        } );
        add_action( 'admin_enqueue_scripts', function( $hook ) {
            global $wp_scripts;
            global $mf_domain;
            
            if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) {
                return;
            }
            wp_enqueue_style(  'mf2tk_admin',     plugins_url( 'css/mf2tk_admin.css',   __FILE__ ) );
            wp_enqueue_script( 'mf2tk_admin',     plugins_url( 'js/mf2tk_admin.js',     __FILE__ ),
                [ 'jquery', 'media-models', 'jquery-ui-draggable', 'jquery-ui-droppable' ] );
            wp_enqueue_script( 'mf2tk_alt_media', plugins_url( 'js/mf2tk_alt_media.js', __FILE__ ), [ 'jquery', 'jquery-ui-tabs' ] );
            $options = get_option( 'magic_fields_2_toolkit_enabled', [ ] );
            $mf2tkDisableHowToUse = array_key_exists( 'dumb_shortcodes', $options ) ? 'false' : 'true';
            $wp_scripts->add_data( 'mf2tk_admin', 'data', "var mf2tkDisableHowToUse=$mf2tkDisableHowToUse;" );
            wp_localize_script( 'mf2tk_admin', 'mf2tk_admin_data', [
                'open'              => __( 'Open',                                                           $mf_domain ),
                'hide'              => __( 'Hide',                                                           $mf_domain ),
                'how_to_use'        => __( 'How to Use with the Toolkit\'s Shortcode',                       $mf_domain ),
                'show_custom_field' => mf2tk\get_tags( )[ 'show_custom_field' ],
                'select'            => __( 'select',                                                         $mf_domain ),
                'copy_and_paste'    => __( 'copy and paste this into editor above in &quot;Text&quot; mode', $mf_domain )

            ] );
        } );
        include( dirname(__FILE__) . '/magic-fields-2-toolkit-settings.php' );
        $options = get_option( 'magic_fields_2_toolkit_enabled', [ ] );
        if ( is_array( $options ) ) {
            if ( array_key_exists( 'custom_post_copier', $options ) ) {
                include( dirname(__FILE__) . '/magic-fields-2-custom-post-copier.php' );
            }
            if ( array_key_exists( 'dumb_shortcodes', $options ) ) {
                include_once( dirname(__FILE__) . '/magic-fields-2-dumb-shortcodes-kai.php' );
            }
            if ( array_key_exists( 'dumb_macros', $options ) ) {
                include( dirname( __FILE__ ) . '/magic-fields-2-dumb-macros.php' );
                # posts some pre-defined content macros as useful examples
                if ( !$wpdb->get_var( <<<EOD
                    SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'content_macro'
                        AND post_title = 'Table' AND post_status = 'publish'
EOD
                ) ) {
					wp_insert_post( array(
						'post_type' => 'content_macro',
						'post_name' => 'table',
						'post_title' => 'Table',
						'post_status' => 'publish',
						'post_content' => <<<'EOD'
<!--
     Show table of fields.
     This macro has one required parameter: macro.
     macro is the slug of this post - table.
     This macro has one optional parameter: fields.
     fields is a list of field names in show_custom_field's
     field parameter format. If not specified "*_*<*,*> is used.
     For best results mf2tk_key needs to be defined. This macro has 
     an example of #if($#alpha#)# ... #else# ... #endif# and
     examples of default parameters.
-->

<!-- $#fields# = "__default_*<*,*>"; -->
<!-- $#fields# = '__default_*<*,*>'; -->

<table>
#if($#fields#)#
[show_custom_field field="$#fields#"
    field_before="<tr><td><!--$Field--></td><td>"
    field_after="</td></tr>"
    separator=", " filter="url_to_link2"]
#else#
[show_custom_field field="*_*<*,*>"
    group_before="<tr><td><!--$Group--></td><td>&nbsp;</td></tr>"
    field_before="<tr><td><!--$Field--></td><td>"
    field_after="</td></tr>"
    separator=", " filter="url_to_link2"]
#endif#
</table>
EOD
					) );
				}
                if ( !$wpdb->get_var( <<<EOD
                    SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'content_macro'
                        AND post_title = 'Horizontal Table for a Group' AND post_status = 'publish'
EOD
                ) ) {
					wp_insert_post( array(
						'post_type' => 'content_macro',
						'post_name' => 'horizontal-table-for-group',
						'post_title' => 'Horizontal Table for a Group',
						'post_status' => 'publish',
						'post_content' => <<<'EOD'
<!-- Show custom fields of a group horizontally.
     This macro has three parameters: macro, group and title.
     macro is the slug of this post - horizontal-table-for-group.
     group is the group name and title is a label for the
     upper left corner of the table. Column labels are 
     generated from the field labels.
     For best results the mf2tk_key needs to be defined. --> 
<table>
<!-- to show just the field names hide field values with display:none -->
<tr><td>$#title#</td>[show_custom_field
    field="$#group#_*<1,1>"
    field_before="<td><!--$Field-->"
    before="<span style='display:none'>" after="</span>"
    field_after="</td>"
]</tr>
[show_custom_field
    field="$#group#_*<*,*>"
    group_before="<tr><td><!--$Group--></td>" 
    field_before="<td>"
    filter="url_to_link2" separator=", "
    field_after="</td>"
    group_after="</tr>"   
]
</table>
EOD
					) );
				}
                if ( !$wpdb->get_var( <<<EOD
                    SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'content_macro'
                        AND post_title = 'Horizontal Table for a Group with Group Columns' AND post_status = 'publish'
EOD
                ) ) {
					wp_insert_post( array(
						'post_type' => 'content_macro',
						'post_name' => 'horizontal-table-for-group-with-group-columns',
						'post_title' => 'Horizontal Table for a Group with Group Columns',
						'post_status' => 'publish',
						'post_content' => <<<'EOD'
<!-- Show custom fields of a group horizontally with a column 
     displaying all fields in a group.
     This macro has three parameters: macro, group and title.
     macro is the slug of this post
         - horizontal-table-for-group-with-group-columns.
     group is the group name and title is a label for the
     upper left corner of the table. Column labels are 
     generated from the group labels.
     For best results the mf2tk_key needs to be defined. --> 
<table>
<tr><td>$#title#</td>
<!-- to show just the group labels hide field values with display:none -->
[show_custom_field
    field="$#group#_mf2tk_key<*,1>f"
    group_before="<td><!--$Group-->"
    before="<span style='display:none'>" after="</span>"
    group_after="</td>"
]
</tr>
[show_custom_field
    field="$#group#_*<*,*>f"
    field_before="<tr><td><!--$Field--></td><td>"
    group_separator="</td><td>"
    separator=", " filter="url_to_link2" 
    field_after="</td></tr>"
]
</table>
EOD
					) );
				}
                if ( !$wpdb->get_var( <<<EOD
                    SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'content_macro'
                        AND post_title = 'Boxes by Group' AND post_status = 'publish'
EOD
                ) ) {
					wp_insert_post( array(
						'post_type' => 'content_macro',
						'post_name' => 'boxes-by-group',
						'post_title' => 'Boxes by Group',
						'post_status' => 'publish',
						'post_content' => <<<'EOD'
<!-- Show custom fields of a post in boxes with a box displaying
     all fields in a group.
     This macro has two parameters: macro, class_filter.
     macro is the slug of this post - boxes-by-group.
     class_filter is an optional class/group include/exclude filter.
     This macro uses conditional text inclusion to handle the
     optional class_filter.
     For best results the mf2tk_key needs to be defined. -->
[show_custom_field
    field="*_*<*,*>#if($#class_filter#)#:$#class_filter##endif#"
    separator=", "
    field_before="<div><div style='width:30%;float:left;clear:both;'><!--$Field-->:</div><div>"
    field_after="&nbsp;</div></div>"
    group_before="<div id='<!--$group-->' class='<!--$class-->'
        style='padding:5px;border:2px solid black;margin:5px;'>
        <div style='border-bottom:1px solid red;'>
        <!--$Group-->:</div>"
    group_after="</div>"
    filter="url_to_link2"]                        
EOD
					) );
				}
                if ( !$wpdb->get_var( <<<EOD
                    SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'content_macro'
                        AND post_title = 'Table of Posts' AND post_status = 'publish'
EOD
                ) ) {
					wp_insert_post( array(
						'post_type' => 'content_macro',
						'post_name' => 'table-of-posts',
						'post_title' => 'Table of Posts',
						'post_status' => 'publish',
						'post_content' => <<<'EOD'
<!--
     Show a table of posts.
     This macro has three parameters: macro, posts and fields.
     macro is the slug of this post - table-of-posts.
     posts is a post specification
          a list of post ids or a post tag pattern
     fields is a field specification
          a list of field names or a field name pattern
--> 
<table>
[show_custom_field
    post_id="$#posts##1"
    field="$#fields#"
    before="<span style='display:none;'>"
    after="</span>"
    field_before="<td><!--$Field-->"
    field_after="</td>
    post_before="<tr>"
    post_after="</tr>"]
[show_custom_field
    post_id="$#posts#"
    field="$#fields#"
    separator=", "
    field_before="<td>"
    field_after="</td>
    post_before="<tr>"
    post_after="</tr>"
    filter="url_to_link2"]
</table>
EOD
					) );
				}
                if ( !$wpdb->get_var( <<<EOD
                    SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'content_macro'
                        AND post_title = 'Horizontal Table' AND post_status = 'publish'
EOD
                ) ) {
					wp_insert_post( array(
						'post_type' => 'content_macro',
						'post_name' => 'horizontal-table',
						'post_title' => 'Horizontal Table',
						'post_status' => 'publish',
						'post_content' => <<<'EOD'
<!--
     Show custom fields horizontally with labels on top.
     This macro has two parameters: macro and fields.
     macro is the slug of this post - horizontal-table.
     fields is a field list specification which has a default of
     __default_<*,*>
     The labels are generated from the field labels.
--> 
<!-- $#fields# = "__default_*<*,*>"; -->
<table>
<!-- to show just the field names hide field values with display:none -->
<tr>[show_custom_field field="$#fields#"
    field_before="<td><!--$Field-->"
    before="<span style='display:none'>" after="</span>"
    field_after="</td>"
]</tr>
<tr>[show_custom_field field="$#fields#"
    field_before="<td>"
    filter="url_to_link2" separator=", "
    field_after="</td>"
]</tr>
</table>
EOD
					) );
				}
                
            } else {
			}
            if ( array_key_exists( 'clean_files_mf', $options ) && is_admin() ) {
                include( dirname(__FILE__)
                    . '/magic-fields-2-clean-files_mf.php' );
            }
            if ( array_key_exists( 'search_using_magic_fields', $options ) ) {
                include( dirname(__FILE__)
                    . '/magic-fields-2-search-by-custom-field-kai.php' );
            }
            if ( array_key_exists( 'alt_dropdown_field', $options ) ) {
                add_action( 'save_post', function( $post_id ) {
                    global $wpdb;
                    if ( !array_key_exists( 'magicfields', $_REQUEST ) || !is_array( $_REQUEST['magicfields'] ) ) { return; }
                    $mf_fields = $wpdb->get_results( 'SELECT id, name, post_type, type, options FROM '
                        . MF_TABLE_CUSTOM_FIELDS . ' WHERE type = "alt_dropdown"', OBJECT );
                    foreach ( $_REQUEST['magicfields'] as $field => $values ) {
                        foreach( $mf_fields as $mf_field ) {
                            if ( $mf_field->name === $field && $mf_field->post_type === $_REQUEST['post_type'] ) {
                                $options = unserialize( $mf_field->options );
                                $updated = FALSE;
                                foreach( $values as $values1 ) {
                                    foreach( $values1 as $values2 ) {
                                        foreach( $values2 as $value ) {
                                            $index = strpos( $options['options'], $value );
                                            if ( $index === FALSE || ( $index && !ctype_space(
                                                substr( $options['options'] . "\r\n", $index + strlen( $value ), 1 ) ) ) ) {
                                                $options['options'] = rtrim( $options['options'] ) . "\r\n" . $value;
                                                $updated = TRUE;
                                            }
                                        }
                                    }
                                }
                                if ( $updated ) {
                                    $wpdb->update( MF_TABLE_CUSTOM_FIELDS, array( 'options' => serialize( $options ) ),
                                        array( 'id' => $mf_field->id ) );
                                }
                            }
                        }
                    }
                } );
            }
            if ( array_key_exists( 'alt_get_audio', $options ) ) {
                include( dirname(__FILE__)
                    . '/magic-fields-2-alt-get-audio.php' );
            }
            if ( array_key_exists( 'utility_functions', $options ) ) {
                include( dirname(__FILE__)
                    . '/magic-fields-2-utility-functions.php' );
            }
            # the AJAX admin refresh request is sent from the post editor to reload a media ( embed, audio or video ) element 
            if ( is_admin() && array_key_exists( 'alt_embed_field', $options ) ) {
                add_action( 'wp_ajax_' . 'mf2tk_alt_embed_admin_refresh', function( ) {
                    include dirname( __FILE__ ) . '/alt_embed_field/alt_embed_field.php';
                    alt_embed_field::admin_refresh( );
                } );
            }
            if ( is_admin( ) && ( array_key_exists( 'alt_video_field', $options ) || array_key_exists( 'alt_audio_field', $options ) ) ) {
                add_action( 'wp_ajax_' . 'mf2tk_alt_media_admin_refresh', function( ) {
                    require_once( dirname( __FILE__ ) . '/alt_media_field.php' );
                    alt_media_field::admin_refresh( );
                } );
            }
            if ( array_key_exists( 'alt_video_field', $options ) || array_key_exists( 'alt_audio_field', $options )
                || array_key_exists( 'alt_embed_field', $options ) || array_key_exists( 'alt_image_field', $options )
                || array_key_exists( 'alt_url_field', $options ) ) {
                include_once dirname( __FILE__ ) . '/magic-fields-2-get-optional-field.php';
            }
        }   # if ( is_array( $options ) ) {
		add_filter( 'plugin_action_links', function( $actions, $plugin_file, $plugin_data, $context ) {
            if ( strpos( $plugin_file, basename( __FILE__ ) ) !== FALSE ) {
                array_unshift( $actions, '<a href="' . admin_url( 'options-general.php?page=magic-fields-2-toolkit-page' ) . '">'
                    . __( 'Settings', 'mf2tk' ) . '</a>' );
            }
			return $actions;
		}, 10, 4 );
        add_action( 'plugins_loaded', function( ) {
            global $mf_domain;
            load_plugin_textdomain( $mf_domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
        } );
    }
}

new Magic_Fields_2_Toolkit_Init();

# global functions are bad especially if they have common names but for compatibility with old code we need to keep
# these functions for now. should replace these functions with the namespace qualified version

function get_data2( $field_name, $group_index = 1, $field_index = 1, $post_id ) {
    return mf2tk\get_data2( $field_name, $group_index, $field_index, $post_id );
}

}

namespace mf2tk {
  
# helper functions

function get_tags( ) {
    return get_option( 'magic_fields_2_toolkit_tags', [ 
        'show_custom_field'       => 'show_custom_field',
        'show_custom_field_alias' => 'mt_field',
        'show_macro'              => 'show_macro',
        'show_macro_alias'        => 'mt_template',
        'mt_show_gallery'         => 'mt_show_gallery',
        'mt_show_gallery_alias'   => 'mt_gallery',
        'mt_show_tabs'            => 'mt_tabs'
    ] );
}

# copied from magic-fields-2\mf_front_end.php and modified to fix this problem:
# If you use the same field name in two different custom post types get_data is apparently returning the options of the first
# entry in the wp_mf_custom_fields table with a matching field name ignoring the post type.
# https://wordpress.org/support/topic/get_data-returns-wrong-options

function get_data2( $field_name, $group_index = 1, $field_index = 1, $post_id ) {
  global $wpdb;

  $field_name = str_replace(" ","_",$field_name);

  $sql = $wpdb->prepare(
    'SELECT m.meta_id, w.meta_value, f.type, f.options, f.description, f.label FROM ' . MF_TABLE_POST_META . ' m ' .
    "JOIN $wpdb->postmeta w ON m.meta_id = w.meta_id JOIN " . MF_TABLE_CUSTOM_FIELDS . ' f ON m.field_name = f.name ' .
    "JOIN $wpdb->posts p ON w.post_id = p.ID " .
    "WHERE m.post_id = %d AND m.field_name = %s AND m.group_count = %d AND m.field_count = %d AND f.post_type = p.post_type",
    $post_id, $field_name, $group_index, $field_index );
    
  $result = $wpdb->get_row( $sql, ARRAY_A );
  
  if( empty($result) ) return NULL;

  $result['options'] = unserialize($result['options']);

  if(is_serialized($result['meta_value'])){
    $result['meta_value'] = unserialize( $result['meta_value'] );
  }
  
  return $result;
}

# get_data_option() gets the value for $option and does not generate an error if the option is missing which can happen
# $option is the option name, $atts are the shortcode parameters, $opts are the field options i.e., get_data2()['options']
# $opts_name is the name for $opts - needed when $atts option name is different from $opts option name
# get_data_option() searches the shortcode parameters $atts, then the field options get_data2()['options'] for $option.
# If $option is not found then $default is returned. 
 
function get_data_option( $option, $atts, $opts, $default = "", $opts_name = NULL ) {
    if ( is_array( $atts ) && array_key_exists( $option, $atts ) ) {
        return $atts[ $option ];
    }
    # the $opts name can be different from the $atts name so
    if ( $opts_name === NULL ) {
        $opts_name = $option;
    }
    if ( is_array( $opts) && array_key_exists( $opts_name, $opts ) ) {
        return $opts[ $opts_name ];
    }
    return $default;
}

# get_mf_post_value() is a wrapper to access $mf_post_values and handle the non existant key case gracefully

function get_mf_post_value( $name, $group_index, $field_index, $default ) {
    global $mf_post_values;
    if ( isset( $mf_post_values[ $name ][ $group_index ][ $field_index ] ) ) {
        return $mf_post_values[ $name ][ $group_index ][ $field_index ];
    }
    return $default;
}

# re_align translates an align option into a standard WordPress align option

function re_align( $option ) {
    if ( $option === 'center' ) { return 'aligncenter'; }
    if ( $option === 'left'   ) { return 'alignleft';   }
    if ( $option === 'right'  ) { return 'alignright';  }
    return $option;
}

# MF2 unfortunately defines some very badly named global functions
# These should be replaced with a namespaced version 

function get( $field_name, $group_index = 1, $field_index = 1, $post_id = NULL ) {
    return \get( $field_name, $group_index, $field_index, $post_id );
}

# get_how_to_use_html( ) returns the how to use HTML for all the alt_*_fields 

function get_how_to_use_html( $field, $group_index, $field_index, $post, $parameters = '', $php_function = NULL, $has_caption = FALSE,
    $caption_input_value = NULL, $width = NULL ) {
    global $mf_domain;
    $index = $group_index === 1 && $field_index === 1 ? '' : "<$group_index,$field_index>";
    $show_custom_field_tag = get_tags( )[ 'show_custom_field' ];
    if ( empty( $php_function ) ) {
        $php_function = 'mf2tk\get_data2';
    }
    if ( $has_caption ) {
        # choose how to use text depending on whether a caption is specified or not
        $how_to_use_with_caption_style = 'display:' . ( $caption_input_value ? 'list-item;' : 'none;'      );
        $how_to_use_no_caption_style   = 'display:' . ( $caption_input_value ? 'none;'      : 'list-item;' );
        # setup geometry for no caption image
        $no_caption_padding            = 0;
        $no_caption_border             = 2;
        $no_caption_width              = is_numeric( $width ) ? "{$width}px" : $width;
        $shortcode_width               = substr_compare( $width, "%", -1 ) === 0 ? ' width="100%"' : '';
    }
    ob_start( );
?>
<!-- usage instructions -->
<div class="mf2tk-field-input-optional mf2tk-usage-field">
    <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
    <h6><?php _e( 'How to Use', $mf_domain ); ?></h6>
    <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
        <ul>
<?php if ( $has_caption ) { ?>
                <li class="mf2tk-how-to-use-with-caption" style="<?php echo $how_to_use_with_caption_style; ?>">
                    <?php _e( 'Use with the Toolkit\'s shortcode - (with caption):', $mf_domain ); ?><br>
                    <input type="text" class="mf2tk-how-to-use" size="50" readonly
                        value='[<?php echo $show_custom_field_tag; ?> field="<?php echo "$field[name]$index"; ?>"<?php echo $parameters; ?>]'>
                    <button class="mf2tk-how-to-use"><?php _e( 'select,', $mf_domain ); ?></button>
                        <?php _e( 'copy and paste this into editor above in &quot;Text&quot; mode', $mf_domain ); ?>
                <li class="mf2tk-how-to-use-no-caption" style="<?php echo $how_to_use_no_caption_style; ?>">
                    <?php _e( 'Use with the Toolkit\'s shortcode - (no caption):', $mf_domain ); ?><br>
                    <textarea class="mf2tk-how-to-use" rows="4" cols="80" readonly>&lt;div class="mf2tk-no-caption-media-box" style="box-sizing:content-box;width:<?php echo $no_caption_width; ?>;max-width:100%;border:<?php echo $no_caption_border; ?>px solid black;background-color:gray;padding:<?php echo $no_caption_padding; ?>px;margin:0px auto;"&gt;
    [<?php echo $show_custom_field_tag; ?> field="<?php echo "$field[name]$index"; ?>"<?php echo $shortcode_width; ?><?php echo $parameters; ?>]
&lt;/div&gt;</textarea><br>
                    <button class="mf2tk-how-to-use"><?php _e( 'select,', $mf_domain ); ?></button>
                        <?php _e( 'copy and paste above into the editor above in &quot;Text&quot; mode', $mf_domain ); ?>
<?php } else { ?>
                <li><?php _e( 'Use with the Toolkit\'s shortcode', $mf_domain ); ?>:<br>
                    <input type="text" class="mf2tk-how-to-use" size="50" readonly
                        value='[<?php echo( $show_custom_field_tag ); ?> field="<?php echo "{$field['name']}{$index}"; ?>"<?php echo $parameters; ?>]'>
                    <button class="mf2tk-how-to-use"><?php _e( 'select', $mf_domain ); ?>,</button>
                        <?php _e( 'copy and paste above into the editor above in &quot;Text&quot; mode', $mf_domain ); ?>
<?php } ?>
            <li><?php _e( 'Call the PHP function', $mf_domain ); ?>:<br>
                <?php echo $php_function; ?>( "<?php echo $field[ 'name' ]; ?>", <?php echo "{$group_index}, {$field_index}, {$post->ID}"; ?> )
        </ul>
    </div>
</div>
<?php
    $output = ob_get_contents( );
    ob_end_clean( );
    return $output;
}

}

