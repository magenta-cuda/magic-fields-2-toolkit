<?php

class alt_numeric_field extends mf_custom_fields {

    public function _update_description( ) {
        global $mf_domain;
        $this->description = __( "Numeric field with currency prefix and/or unit suffix", $mf_domain );
    }
  
    public function _options( ) {
        global $mf_domain;
        $show_custom_field_tag = mf2tk\get_tags( )[ 'show_custom_field' ];    
        return [
            'option' => [
                'precision' => [
                    'type'        => 'text',
                    'id'          => 'numeric_precision',
                    'label'       => __( 'Precision', $mf_domain ),
                    'name'        => 'mf_field[option][precision]',
                    'description' => __( 'number of decimal places; 0 for integers - second parameter to PHP\'s number_format() - this value can be overridden by specifying a &quot;precision&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       => '2',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'unit'=> [
                    'type'        => 'text',
                    'id'          => 'numeric_unit',
                    'label'       => __( 'Unit of Measurement', $mf_domain ),
                    'name'        => 'mf_field[option][unit]',
                    'description' => esc_attr__( 'unit of measurement, e.g. &quot;in&quot;, &quot;sq mi&quot;, &quot; fl oz&quot;, ... or &quot;%&quot; or counter e.g. &quot;item:items&quot;, &quot; man: men&quot; given as singular:plural pair - really just a suffix to append to the value - this value can be overridden by specifying a &quot;unit&quot; parameter with the',
                                         $mf_domain ) . " $show_custom_field_tag shortcode", # TODO: esc_html__()?
                    'value'       => '',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'currency'=> [
                    'type'        => 'text',
                    'id'          => 'numeric_currency',
                    'label'       => __( 'Currency', $mf_domain ),
                    'name'        => 'mf_field[option][currency]',
                    'description' => __( 'currency code e.g. &quot;$&quot;, &quot;&amp;euro;&quot;, &quot;&amp;#128;&quot;,  &quot;&amp;#x80;&quot; ... - really just a prefix to prepend to the value - this value can be overridden by specifying a &quot;currency&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       => '',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'min' => [
                    'type'        => 'text',
                    'id'          => 'numeric_min',
                    'label'       => __( 'Minimum', $mf_domain ),
                    'name'        => 'mf_field[option][min]',
                    'description' => __( 'minimum', $mf_domain ),
                    'value'       => '0',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'max' => [
                    'type'        => 'text',
                    'id'          => 'numeric_max',
                    'label'       => __( 'Maximum', $mf_domain ),
                    'name'        => 'mf_field[option][max]',
                    'description' => __( 'maximum', $mf_domain ),
                    'value'       => '',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'step' => [
                    'type'        => 'text',
                    'id'          => 'numeric_step',
                    'label'       => __( 'Step Size', $mf_domain ),
                    'name'        => 'mf_field[option][step]',
                    'description' => __( 'step size', $mf_domain ),
                    'value'       => '1',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'decimal_point' => [
                    'type'        => 'text',
                    'id'          => 'numeric_decimal_point',
                    'label'       => __( 'Decimal Point', $mf_domain ),
                    'name'        => 'mf_field[option][decimal_point]',
                    'description' => __( 'The separator for the decimal point - third parameter to PHP\'s number_format() - this value can be overridden by specifying a &quot;decimal_point&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       => '.',
                    'div_class'   => '',
                    'class'       => ''
                ],
                'thousands_separator' => [
                    'type'        => 'text',
                    'id'          => 'numeric_thousands_separator',
                    'label'       => __( 'Thousands Separator', $mf_domain ),
                    'name'        => 'mf_field[option][thousands_separator]',
                    'description' => __( 'The thousands separator - fourth parameter to PHP\'s number_format() - this value can be overridden by specifying a &quot;thousands_separator&quot; parameter with the',
                                          $mf_domain ) . " $show_custom_field_tag shortcode",
                    'value'       => ',',
                    'div_class'   => '',
                    'class'       => ''
                ],
            ]
        ];
    }
  
    public function display_field( $field, $group_index = 1, $field_index = 1 ) {
        global $post;
        global $mf_domain;
        $show_custom_field_tag = mf2tk\get_tags( )[ 'show_custom_field' ];    
        $options =& $field['options'];
        $min  = ( array_key_exists( 'min',  $options ) && is_numeric ( $options['min']  ) )
                    ? " min  = \"$options[min]\""  : '';
        $max  = ( array_key_exists( 'max',  $options ) && is_numeric ( $options['max']  )
                        && $options['max'] > $options['min'] )
                    ? " max  = \"$options[max]\""  : '';
        $step = ( array_key_exists( 'step', $options ) && is_numeric ( $options['step'] ) && $options['step'] > 0 )
                    ? " step = \"$options[step]\"" : '';
        $currency = ( array_key_exists( 'currency',    $options ) ) ? $options['currency']  : '';
        $unit     = ( array_key_exists( 'unit',        $options ) ) ? $options['unit']      : '';
        $value    = ( array_key_exists( 'input_value', $field   ) ) ? $field['input_value'] : '';
        if ( strpos( $unit, ':' ) ) {
            $unit = explode( ':', $unit );
            $unit = $value == 1 ? $unit[0] : $unit[1];
        }
        $index = $group_index === 1 && $field_index === 1 ? '' : "<$group_index,$field_index>";
        ob_start( );
?>
<div class="text_field_mf">
    <div class="mf2tk-field-input-main">
        <div class="mf2tk-field_value_pane">
            <div><span><?php echo $currency; ?></span><input type="number" name="<?php echo $field['input_name']; ?>"
                placeholder="<?php echo $field['label']; ?>" value="<?php echo $value; ?>" <?php echo "$min$max$step"; ?>
                style="display:inline-block;text-align:right;width:16em;" /><span><?php echo $unit; ?></span>
            </div>
        </div>
        <div style="font-size:75%;margin:5px 50px;"><?php _e( 'Value has', $mf_domain ); echo " $options[precision] ";
            _e( 'decimal places.', $mf_domain ); ?></div>
    </div>
<?php
        $output = ob_get_contents( ) . mf2tk\get_how_to_use_html( $field, $group_index, $field_index, $post, '', 'alt_numeric_field::get_numeric' ) . '</div>';
        ob_end_clean( );
        error_log( '##### alt_numeric_field::display_field():$output=' . $output );
        return $output;
    }
    
    public static function get_numeric( $field_name, $group_index = 1, $field_index = 1, $post_id = NULL, $atts = [ ] ) {
        global $post;
        if ( $post_id === NULL ) {
            $post_id = $post->ID;
        }
        $data = mf2tk\get_data2( $field_name, $group_index, $field_index, $post_id );
        $value = $data[ 'meta_value' ];
        $opts  = $data[ 'options'    ];
        $currency            = mf2tk\get_data_option( 'currency',            $atts, $opts      );
        $precision           = mf2tk\get_data_option( 'precision',           $atts, $opts, 2   );
        $decimal_point       = mf2tk\get_data_option( 'decimal_point',       $atts, $opts, '.' );
        $thousands_separator = mf2tk\get_data_option( 'thousands_separator', $atts, $opts, ',' );
        $unit                = mf2tk\get_data_option( 'unit',                $atts, $opts      );
        if ( strpos( $unit, ':' ) ) {
            $unit = explode( ':', $unit ); 
            if ( $value == 1 ) {
                $unit = $unit[0];
            } else {
                $unit = $unit[1];
            }
        }        
        return $currency . number_format( (double) $value, $precision, $decimal_point, $thousands_separator ) . $unit;
    }

}
