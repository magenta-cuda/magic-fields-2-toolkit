<?php
/*
 * This code derived from "related_type_field.php" of Magic Fields 2 by Hunk and Gnuget
 * License: GPL2
 */

// class with static properties encapsulating functions for the field type

class alt_related_type_field extends mf_custom_fields {

    public $allow_multiple = TRUE;
    public $has_properties = TRUE;

    public function _update_description( ) {
        global $mf_domain;
        $this->description = __( "Checkbox list that lets a user select ONE or MORE related posts of a given post type.", $mf_domain );
    }

    public function _options( ) {
        global $mf_domain;

        $posttypes = $this->mf_get_post_types( );
        $select = [ ];
        foreach( $posttypes as $k => $v ) {
            #error_log('##### alt_related_type_field::_options():$v='.print_r($v,TRUE));
            if ( in_array( $v->name, [ 'revision', 'nav_menu_item', 'content_macro' ] ) ) {
                continue;
            }
            $select[ $k ] = $v->label;
        }

        return [
            'option' => [
                'post_type'  => [
                    'type'        => 'select',
                    'id'          => 'post_type',
                    'label'       => __( 'Related Type Panel (Post type)', $mf_domain ),
                    'name'        => 'mf_field[option][post_type]',
                    'default'     =>  '',
                    'options'     => $select,
                    'add_empty'   => FALSE,
                    'description' => '',
                    'value'       => '',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'field_order'  => [
                    'type'        => 'select',
                    'id'          => 'field_order',
                    'label'       => __( 'Field for order of Related type', $mf_domain ),
                    'name'        => 'mf_field[option][field_order]',
                    'default'     => '',
                    'options'     => [ 'id' => 'ID', 'title' => 'Title' ],
                    'add_empty'   => FALSE,
                    'description' => '',
                    'value'       => '',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'order'  => [
                    'type'        => 'select',
                    'id'          => 'order',
                    'label'       => __( 'Order of Related type', $mf_domain ),
                    'name'        => 'mf_field[option][order]',
                    'default'     => '',
                    'options'     => [ 'asc' => 'ASC', 'desc' =>'DESC' ],
                    'add_empty'   => FALSE,
                    'description' => '',
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

        $output = '';

        $check_post_id = NULL;
        if ( !empty( $_REQUEST[ 'post' ] ) ) {
            $check_post_id = $_REQUEST[ 'post' ];
        }

        $values = [ ];
        if( $check_post_id ) {
            $values = ( $field[ 'input_value' ] ) ? ( is_serialized( $field[ 'input_value' ] ) ) ? unserialize( $field[ 'input_value' ] )
                : (array) $field[ 'input_value' ] : [ ];
        }

        $options = get_posts( <<<EOD
post_type={$field['options']['post_type']}&numberposts=-1&order={$field['options']['order']}&orderby={$field['options']['field_order']}&suppress_filters=0
EOD
        );

        $output  = '<div class="mf-checkbox-list-box"><div class="mf2tk-field-input-main"><div class="mf2tk-field_value_pane">';

        foreach( $values as &$val ) {
            $val = trim( $val );
        }

        foreach( $options as $option ) {
            $check = in_array( $option->ID, $values ) ? 'checked="checked"' : '';
            $output .= <<<EOD
<label for="{$field['input_id']}_{$option->ID}" class="selectit mf-checkbox-list">
    <input type="checkbox" class="checkbox_list_mf" id="{$field['input_id']}_{$option->ID}" name="{$field['input_name']}[]" value="{$option->ID}"
        {$check} {$field['input_validate']} />
EOD;
            $output .= esc_attr( $option->post_title ) . '</label>';
        }
        
        $output .= '<div style="clear:both;"></div></div></div>'
            . mf2tk\get_how_to_use_html( $field, $group_index, $field_index, $post, ' filter="url_to_link2" separator=", "' ) . '</div>';
        
        #error_log( "##### alt_related_type_field::display_field() returns $output\n" );
        return $output;
    }
}
