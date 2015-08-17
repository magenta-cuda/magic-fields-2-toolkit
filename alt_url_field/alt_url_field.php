<?php

class alt_url_field extends mf_custom_fields {

    public static $suffix_label = '_mf2tk_label';

    public function _update_description( ) {
        global $mf_domain;
        $this->description = __( "URL field", $mf_domain );
    }
  
    public function _options( ) {
        global $mf_domain;
        return [
            'option' => [
                'target' => [
                    'type'        => 'select',
                    'id'          => 'target',
                    'label'       => 'target',
                    'name'        => 'mf_field[option][target]',
                    'default'     => 'blank',
                    'options'     => [
                                         '_blank'  => '_blank',
                                         '_self'   => '_self',
                                         '_top'    => '_top',
                                         '_parent' => '_parent'
                                     ],
                    'add_empty'   => FALSE,
                    'description' => __( 'where to open the linked document - this value can be overridden by specifying a &quot;target&quot; parameter with the',
                                         $mf_domain ) . ' ' . mf2tk\get_tags( )[ 'show_custom_field' ] . ' shortcode',
                    'value'       => '',
                    'div_class'   => '',
                    'class'       => ''
                ]
            ]
        ];
    }
  
    public function display_field( $field, $group_index = 1, $field_index = 1 ) {
        global $post;
        global $mf_domain;
        $value             = array_key_exists( 'input_value', $field ) ? $field[ 'input_value' ] : '';
        $label_field_name  = $field[ 'name' ] . self::$suffix_label;
        $label_field_id    = "mf2tk-$label_field_name-$group_index-$field_index";
        $label_input_name  = "magicfields[$label_field_name][$group_index][$field_index]";
        $label_input_value = mf2tk\get_mf_post_value( $label_field_name, $group_index, $field_index, '' );
        $index             = $group_index === 1 && $field_index === 1 ? '' : "<$group_index,$field_index>";
        ob_start( );
?>
<div class="text_field_mf url_field_mf">
    <div class="mf2tk-field-input-main">
        <div class="mf2tk-field_value_pane">
            <div><?php _e( 'URL', $mf_domain ); ?>:
                <button class="mf2tk-test-load-button" style="float:right;"><?php _e( 'Test Load', $mf_domain ); ?></button>
                <input type="url" name="<?php echo $field[ 'input_name' ]; ?>" placeholder="<?php echo $field[ 'label' ]; ?>"
                    value="<?php echo $value; ?>" maxlength="2048" style="clear:both;display:block;" />
            </div>
            <div><?php _e( 'label: e.g.', $mf_domain ); ?> &lt;a href=&quot;...&quot;&gt;<span style="background-color:yellow;"><?php
                _e( 'the label is displayed here', $mf_domain ); ?></span>&lt;/a&gt;
                <input type="text" name="<?php echo $label_input_name; ?>" placeholder="<?php _e( 'display label for', $mf_domain ); echo " $field[label]"; ?>"
                    value="<?php echo $label_input_value; ?>" style="clear:both;display:block;" />
            </div>
        </div>
    </div>
<?php
        $output = ob_get_contents( )
            . mf2tk\get_how_to_use_html( $field, $group_index, $field_index, $post, ' filter="url_to_link2"', 'alt_url_field::get_url' ) . '</div>';
        ob_end_clean( );
        return $output;
    }
    
    public static function get_url( $field_name, $group_index = 1, $field_index = 1, $post_id = NULL, $atts = [ ] ) {
        global $post;
        if ( $post_id === NULL ) {
            $post_id = $post->ID;
        }
        $data   = mf2tk\get_data2( $field_name, $group_index, $field_index, $post_id );
        $value  = $data[ 'meta_value' ];
        $target = '_' . mf2tk\get_data_option( 'target', $atts, $data[ 'options' ], '_blank' );
        $label  = mf2tk\get_optional_field( $field_name, $group_index, $field_index, $post_id, self::$suffix_label );
        return "<a href=\"$value\" target=\"$target\">$label</a>";
    }

}
