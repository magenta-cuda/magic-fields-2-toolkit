<?php

add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) {
        return;
    }
    wp_enqueue_script( 'jquery-ui-draggable' );
    wp_enqueue_script( 'jquery-ui-droppable' );
} );

class alt_table_field extends mf_custom_fields {

  //Properties
  public  $css_script = FALSE;
  public  $js_script = FALSE;
  public  $js_dependencies = array();
  public  $allow_multiple = FALSE;
  public  $has_properties = TRUE;
  
    public function get_properties() {
        $properties['css']              = $this->css_script;
        $properties['js_dependencies']  = $this->js_dependencies;
        $properties['js']               = $this->js_script;
        return $properties;
    }

    public function _update_description( ) {
        global $mf_domain; 
        $this->description = __( 'Table of Magic Fields - This is a psuedo field for automatically generating shortcodes to display all/some Magic Fields in a table',
            $mf_domain );
    }
    
    public function _options( ) {
        return [ 'option'  => [ ] ];
    }
    
    public function display_field( $table_field, $group_index = 1, $field_index = 1 ) {
        global $post, $wpdb;
        global $mf_domain;
        $input_value = '';
        $matches = [ ];
        if ( array_key_exists( 'input_value', $table_field ) ) {
            $input_value = $table_field[ 'input_value' ];
            preg_match( '/field=([\w;]+)\|filter=([\w;]+)/', $input_value, $matches );
        }
        $fields   = array_key_exists( 1, $matches ) ? $matches[1] : '';
        $filters  = array_key_exists( 2, $matches ) ? $matches[2] : '';
        $fields0  = array_key_exists( 1, $matches ) && $matches[1] ? explode( ';', $matches[1] ) : [ ];
        $filters0 = [ ];
        if ( array_key_exists( 2, $matches ) && $matches[2] ) {
            foreach ( explode( ';', $matches[2] ) as $filter ) {
                if ( preg_match( '/^(\w+)__(w|h)(\d+)$/', $filter, $filter_matches ) === 1 ) {
                    $filters0[$filter_matches[1]] = [ $filter_matches[2], $filter_matches[3] ];
                } else {
                    $filters0[$filter] = null;
                }
            }
        }
        $default_height         = 50;
        $post_type              = $post->post_type;
        $MF_TABLE_CUSTOM_FIELDS = MF_TABLE_CUSTOM_FIELDS;
        $results = $wpdb->get_col( $wpdb->prepare( <<<EOD
SELECT name FROM $MF_TABLE_CUSTOM_FIELDS WHERE post_type = %s AND type != 'alt_table' AND type != 'alt_template'
EOD
        , $post_type ) );
        $checked_fields   = array_intersect( $fields0, $results );
        $unchecked_fields = array_diff( $results, $checked_fields );
        $all_fields       = array_merge(
            array_map( function( $v ) { return TRUE;  }, array_flip( $checked_fields   ) ),
            array_map( function( $v ) { return FALSE; }, array_flip( $unchecked_fields ) )
        );
        foreach ( [ 'tk_value_as_color', 'tk_value_as_checkbox', 'tk_value_as_audio', 'tk_value_as_image', 'tk_value_as_video', 'url_to_link2' ] as $filter ) {
            $filter_checked = $filter . '_checked';
            $$filter_checked = array_key_exists( $filter, $filters0 ) ? ' checked' : '';
        }
        $tk_value_as_image_height_checked = ' checked';
        $tk_value_as_image_width_checked  = '';
        $tk_value_as_image_size           = $default_height;
        if ( array_key_exists( 'tk_value_as_image', $filters0 ) ) {
            if ( $filters0['tk_value_as_image'][0] === 'h' ) {
                $tk_value_as_image_height_checked = ' checked';
                $tk_value_as_image_width_checked  = '';
            } else {
                $tk_value_as_image_height_checked = '';
                $tk_value_as_image_width_checked  = ' checked';
            }
            $tk_value_as_image_size = $filters0['tk_value_as_image'][1];
        }
        $tk_value_as_video_height_checked = ' checked';
        $tk_value_as_video_width_checked  = '';
        $tk_value_as_video_size           = $default_height;
        if ( array_key_exists( 'tk_value_as_video', $filters0 ) ) {
            if ( $filters0['tk_value_as_video'][0] === 'h' ) {
                $tk_value_as_video_height_checked = ' checked';
                $tk_value_as_video_width_checked  = '';
            } else {
                $tk_value_as_video_height_checked = '';
                $tk_value_as_video_width_checked  = ' checked';
            }
            $tk_value_as_video_size = $filters0['tk_value_as_video'][1];
        }
        ob_start( );
?>
    <div class="mf2tk-field-input-optional">
        <h6><?php _e( 'How to Use', $mf_domain ); ?></h6>
        <div class="mf2tk-field_value_pane" style="clear:both;">
            <textarea class="mf2tk-how-to-use mf2tk-table-shortcode" rows="10" cols="80" readonly><!-- Edit below to your liking -->
&lt;div&gt;
&lt;style scoped&gt;
table.mf2tk-alt_table{border-collapse:collapse;}
table.mf2tk-alt_table,table.mf2tk-alt_table td{border:2px solid black;}
table.mf2tk-alt_table td{padding:7px 10px 3px 10px;}
&lt;/style&gt;
&lt;table class="mf2tk-alt_table"&gt;
[show_custom_field
&nbsp;&nbsp;&nbsp;&nbsp;field="<?php echo $fields; ?>"
&nbsp;&nbsp;&nbsp;&nbsp;filter="<?php echo $filters; ?>"
&nbsp;&nbsp;&nbsp;&nbsp;separator=", "
&nbsp;&nbsp;&nbsp;&nbsp;field_before="&lt;tr&gt;&lt;td&gt;&lt;!--$Field--&gt;&lt;/td&gt;&lt;td&gt;"
&nbsp;&nbsp;&nbsp;&nbsp;field_after="&lt;/td&gt;&lt;/tr&gt;"
]
&lt/table&gt;
&lt/div&gt;
<!-- Edit above to your liking -->
</textarea>
- <?php _e( 'To display a table of all Magic Fields', $mf_domain ); ?> <button class="mf2tk-how-to-use"><?php _e( 'select', $mf_domain ); ?>,</button>
<?php _e( 'copy and paste above into the editor above in &quot;Text&quot; mode', $mf_domain ); ?>
        </div>
    </div>
    <!-- optional configuration field -->
    <div class="mf2tk-field-input-optional">
        <button class="mf2tk-field_value_pane_button"><?php _e( 'Open', $mf_domain ); ?></button>
        <h6><?php _e( 'Re-Configure Table', $mf_domain ); ?></h6>
        <div class="mf2tk-field_value_pane" style="display:none;clear:both;">
            <fieldset class="mf2tk-configure mf2tk-fields">
                <legend><?php _e( 'Fields', $mf_domain ); ?>:</legend>
                <!-- before drop point -->
                <div><div class="mf2tk-dragable-field-after"></div></div>
<?php
        $output = ob_get_contents( );
        ob_end_clean( );
        foreach ( $all_fields as $field => $is_checked ) {
        $checked = $is_checked ? ' checked' : '';
        $output .= <<<EOD
                <div class="mf2tk-dragable-field">
                    <label class="mf2tk-configure">
                        <input type="checkbox" class="mf2tk-configure" value="$field"$checked>$field
                    </label>
                    <br>
                    <!-- a drop point -->
                    <div class="mf2tk-dragable-field-after"></div>
                </div>
EOD;
        }
        ob_start( );
?>
                <span><?php _e( 'Use drag and drop to change field order', $mf_domain ); ?></span>
            </fieldset>
            <fieldset class="mf2tk-configure mf2tk-filters">
                <legend><?php _e( 'Filters', $mf_domain ); ?>:</legend>
                <div>
                    <label class="mf2tk-configure">
                        <input type="checkbox" class="mf2tk-configure"
                            value="tk_value_as_color"<?php echo $tk_value_as_color_checked; ?>>tk_value_as_color
                    </label>
                </div>
                <div>
                    <label class="mf2tk-configure">
                        <input type="checkbox" class="mf2tk-configure"
                            value="tk_value_as_checkbox"<?php echo $tk_value_as_checkbox_checked; ?>>tk_value_as_checkbox
                    </label>
                </div>
                <div>
                    <label class="mf2tk-configure">
                        <input type="checkbox" class="mf2tk-configure"
                            value="tk_value_as_audio"<?php echo $tk_value_as_audio_checked; ?>>tk_value_as_audio
                    </label>
                </div>
                <div>
                    <label class="mf2tk-configure">
                        <input type="checkbox" class="mf2tk-configure"
                            value="tk_value_as_image__"<?php echo $tk_value_as_image_checked; ?>>tk_value_as_image__
                    </label>
                    <label class="mf2tk-configure">
                        <input type="radio" class="mf2tk-configure" name="tk_value_as_image_<?php echo $post_type; ?>"
                            value="width"<?php echo $tk_value_as_image_width_checked; ?>>Width
                    </label>
                    <label class="mf2tk-configure">
                        <input type="radio" class="mf2tk-configure" name="tk_value_as_image_<?php echo $post_type; ?>"
                            value="height"<?php echo $tk_value_as_image_height_checked; ?>>Height
                    </label>
                    <label class="mf2tk-configure">
                        <input type="number" class="mf2tk-configure" max="9999" value="<?php echo $tk_value_as_image_size; ?>">
                     </label>
                </div>
                <div>
                    <label class="mf2tk-configure">
                        <input type="checkbox" class="mf2tk-configure"
                            value="tk_value_as_video__"<?php echo $tk_value_as_video_checked; ?>>tk_value_as_video__
                    </label>
                    <label class="mf2tk-configure">
                        <input type="radio" class="mf2tk-configure" name="tk_value_as_video_<?php echo $post_type; ?>"
                            value="width"<?php echo $tk_value_as_video_width_checked; ?>>Width
                    </label>
                    <label class="mf2tk-configure">
                        <input type="radio" class="mf2tk-configure" name="tk_value_as_video_<?php echo $post_type; ?>"
                            value="height"<?php echo $tk_value_as_video_height_checked; ?>>Height
                    </label>
                    <label class="mf2tk-configure">
                        <input type="number" class="mf2tk-configure" max="9999" value="<?php echo $tk_value_as_video_size; ?>">
                     </label>
                </div>
                <div>
                    <label class="mf2tk-configure">
                        <input type="checkbox" class="mf2tk-configure" value="url_to_link2"<?php echo $url_to_link2_checked; ?>>url_to_link2
                    </label>
                </div>
            </fieldset>
            <button class="mf2tk-refresh-table-shortcode" id="button-<?php echo "{$table_field['name']}-{$group_index}-{$field_index}"; ?>">
                <?php _e( 'Refresh Table Shortcode', $mf_domain ); ?></button>
        </div>
    </div>
    <input type="hidden" id="input-<?php echo "{$table_field['name']}-{$group_index}-{$field_index}"; ?>" name="<?php echo $table_field['input_name']; ?>"
        value="<?php echo $input_value; ?>">
<?php
        $output .= ob_get_contents( );
        ob_end_clean( );
        return $output;
    }
}
