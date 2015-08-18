<?php
/*
 * This code derived from "dropdown_field.php" of Magic Fields 2 by Hunk and Gnuget
 * License: GPL2
 */

 // initialisation

// class with static properties encapsulating functions for the field type

class alt_dropdown_field extends mf_custom_fields {

    public $allow_multiple = TRUE;
    public $has_properties = TRUE;
  
    public function _update_description( ) {
        global $mf_domain;
        $this->description = __( 'Dropdown with optional textbox', $mf_domain );
    }
  
    public function _options( ) {
        global $mf_domain;
    
        return [
            'option' => [
                'options' => [
                    'type'        =>  'textarea',
                    'id'          =>  'dropdown_options',
                    'label'       =>  __( 'Options', $mf_domain ),
                    'name'        =>  'mf_field[option][options]',
                    'default'     =>  '',
                    'description' =>  __( 'Separate each option with a newline.', $mf_domain ),
                    'value'       =>  '',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'multiple' => [
                    'type'        =>  'checkbox',
                    'id'          =>  'multiple_dropdown_options',
                    'label'       =>  __( 'The dropdown can have multiple values', $mf_domain ),
                    'name'        =>  'mf_field[option][multiple]',
                    'default'     =>  '',
                    'description' =>  '',
                    'value'       =>  '',
                    'div_class'   =>  '',
                    'class'       =>  ''
                ],
                'default_value' => [
                    'type'        =>  'text',
                    'id'          =>  'dropdown_default_value',
                    'label'       =>  __( 'Default values', $mf_domain ),
                    'name'        =>  'mf_field[option][default_value]',
                    'default'     =>  '',
                    'description' =>  __( 'Separate each value with a newline.', $mf_domain ),
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
        
        $output = '<div class="mf2tk-field-input-main">'
            . "<div id=\"div-alt-dropdown-{$field['id']}-{$group_index}-{$field_index}\" class=\"mf2tk-field_value_pane\">";

        $check_post_id = NULL;
        if ( !empty( $_REQUEST[ 'post' ] ) ) {
            $check_post_id = $_REQUEST[ 'post' ];
        }

        $values = [ ];
        if ( $check_post_id ) {
            $values = ( $field[ 'input_value' ] ) ? ( is_serialized( $field[ 'input_value' ] ) ) ? unserialize( $field[ 'input_value' ] )
                : (array) $field[ 'input_value' ] : [ ];
        } else {
            $values[ ] = $field[ 'options' ][ 'default_value' ];
        }
        foreach ( $values as &$val ) {
            $val = trim( $val );
        }

        $options     = preg_split( "/\\n/", $field[ 'options' ][ 'options' ] );
        $is_multiple = ( $field[ 'options' ][ 'multiple' ] ) ? TRUE : FALSE;
        $multiple    = ( $is_multiple ) ? 'multiple="multiple"' : '';
        $separator   = ( $is_multiple ) ? ' separator=", "'     : '';
        
        # output select box
        
        $output .= "<div class=\"mf-dropdown-box\"><select class=\"dropdown_mf\" id=\"{$field['input_id']}\" name=\"{$field['input_name']}[]\" {$multiple}>";
        foreach ( $options as $option ) {
            $option = trim( $option );
            if ( !$option ) {
                continue;
            }
            $check = in_array( $option, $values ) ? 'selected="selected"' : '';
            $output .= sprintf( '<option value="%s" %s >%s</option>', esc_attr( $option ), $check, esc_attr( $option ) );
        }
        $output .= '<option value="add-new">' . __( '--Enter New Value--', $mf_domain ) . '</option>';
        $output .= '</select></div>';

        # output input box
        
        $output .= "<div class=\"text_field_mf\"><input type=\"text\" placeholder=\"{$field['label']}\" style=\"display:none;\" /></div>";
        
        # output how to use box
        
        $output .= '</div></div>' . mf2tk\get_how_to_use_html( $field, $group_index, $field_index, $post, $separator );
        error_log( "##### alt_dropdown_field::display_field():return=" . $output );
        return $output;
    }
}
