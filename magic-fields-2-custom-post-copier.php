<?php

/*
 * Description:   Create a copy of a Magic Fields 2 custom post including the
 *                Magic Fields 2 custom fields, custom groups and custom
 *                taxonomies.
 * Documentation: http://magicfields17.wordpress.com/toolkit
 * Author:        Magenta Cuda
 * License:       GPL2
 *
 * To copy a custom post open the "All Your Custom Post Type" menu item and 
 * click on "Create Copy" for the entry of the desired post.
 */

/*  Copyright 2013  Magenta Cuda  (email:magenta.cuda@yahoo.com)

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

add_filter( 'post_row_actions', function( $actions, $post ) {
    global $mf_domain;
    $actions[ 'ufmf2_duplicate_post' ] = '<a href="' . admin_url( "admin.php?action=magic_fields_2_toolkit_copy_post&post={$post->ID}" ) . '">'
        . __( 'Create Copy', $mf_domain ) . '</a>';
    return $actions;
}, 10, 2 );

add_action( 'admin_action_magic_fields_2_toolkit_copy_post', function( ) {
    global $wpdb;
    global $mf_domain;
    try {
        #copy the post
        $result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE ID = %d", $_REQUEST[ 'post' ] ), ARRAY_A );
        $post = $result[0];
        unset( $post['ID'] );
        $post['guid']                = '';
        $post['post_name']           = '';
        $post['post_status']         = 'draft';
        $post[ 'post_title' ]        = __( 'Copy of', $mf_domain ) . " {$post['post_title']}";
        $post[ 'post_author' ]       = get_current_user_id( );
        $post[ 'post_date' ]         = current_time( 'mysql' );
        $post[ 'post_date_gmt' ]     = '0000-00-00 00:00:00';
        $post[ 'post_modified' ]     = $post[ 'post_date' ];
		$post[ 'post_modified_gmt' ] = get_gmt_from_date( $post[ 'post_date' ] );
        if ( FALSE === $wpdb->insert( $wpdb->posts, $post ) ) {
            throw new Exception( '' );
        }
        $id = (integer) $wpdb->insert_id; 
        $wpdb->update( $wpdb->posts, [ 'guid' => get_permalink( $id ) ], [ 'ID' => $id ] );

        # copy the custom fields of the post
        $new_meta_id = [ ];
        foreach( $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->postmeta} WHERE post_id = %d", $_REQUEST['post'] ), ARRAY_A ) as $row ) {
            if ( $row[ 'meta_key' ] === 'edit_last' || $row[ 'meta_key' ] === 'edit_lock' ) {
                continue;
            }
            $old_meta_id = $row[ 'meta_id' ];
            unset( $row[ 'meta_id' ] );
            $row[ 'post_id' ] = $id;
            $wpdb->insert( $wpdb->postmeta, $row );
            $new_meta_id[ $old_meta_id ] = (integer) $wpdb->insert_id;
        }
        
        # copy the Magic Fields 2 proprietary data
        if ( defined( 'MF_TABLE_POST_META' ) ) {
            $MF_TABLE_POST_META = MF_TABLE_POST_META;
            foreach ( $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $MF_TABLE_POST_META WHERE post_id = %d", $_REQUEST[ 'post' ] ), ARRAY_A ) as $row ) {
                $row[ 'post_id' ] = $id;
                $row[ 'meta_id' ] = $new_meta_id[ $row[ 'meta_id' ] ];
                $wpdb->insert( MF_TABLE_POST_META, $row );
            }
        }
        
        # copy the taxonomy data
        foreach ( $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->term_relationships} WHERE object_id = %d", $_REQUEST[ 'post' ] ), ARRAY_A )
            as $row ) {
            $row[ 'object_id' ] = $id;
            $wpdb->insert( $wpdb->term_relationships, $row );
            $result = $wpdb->get_col( $wpdb->prepare( "SELECT count FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id = %s", $row[ 'term_taxonomy_id' ] ) );
            $count = $result[0];
            $wpdb->update( $wpdb->term_taxonomy, [ 'count' => ( $count + 1 ) ], [ 'term_taxonomy_id' => $row['term_taxonomy_id'] ] );
        }
        
        # open the post editor on the copied post
        wp_redirect( admin_url( 'post.php?action=edit&post=' . $id ) );
        
    } catch (Exception $e) {
        set_transient( 'magic_fields_2_custom_post_copier_error', __( 'copy of ... failed', $mf_domain ), 10 );
        wp_redirect( admin_url( "edit.php?post_type={$_REQUEST['post_type']}" ) );
    }
} );

if ( is_admin( ) ) {
    if ( $error = get_transient( 'magic_fields_2_custom_post_copier_error' ) ) {
        add_action('admin_notices', function( ) use ( $error ) {
           echo '<div class="error"><p>' . $error . '</p></div>';
        } );
        delete_transient( 'magic_fields_2_custom_post_copier_error' );
    }
}

?>