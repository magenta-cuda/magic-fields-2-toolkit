<?php
/*
Plugin Name: Test Harness
*/
add_filter( 'the_content', function( $content ) {
    $field_id = \mf2tk\get_field_id( 'name', 'colors' );
    error_log( '$field_id=' . $field_id );
    $group_id = \mf2tk\get_group_id( 'dimensions', 'engine' );
    error_log( '$group_id=' . $group_id );
    $field_names = \mf2tk\get_field_names( 'motor' );
    error_log( '$field_names=' . print_r( $field_names, true ) );
    $group_names = \mf2tk\get_group_names( 'engine' );
    error_log( '$group_names=' . print_r( $group_names, true ) );
    $field_names = \mf2tk\get_field_names_in_group( 'dimensions', 'engine' );
    error_log( '$field_names=' . print_r( $field_names, true ) );
    $field_type = \mf2tk\get_field_type( 'video_test', 'motor' );
    error_log( '$field_type=' . $field_type );
    $field_options = \mf2tk\get_field_options( 'video_test', 'motor' );
    error_log( '$field_options=' . print_r( $field_options, true ) );
    return $content;
} );
?>