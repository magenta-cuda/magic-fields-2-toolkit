<?php

/*
 * Copyright 2012 by Magenta Cuda
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * Tested on WordPress 3.5 and Magic Fields 2.1
 */

/*
 * Finds files in folder "wp-content/files_mf" that are not referenced by published or draft posts.
 */
 
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( $hook !== 'settings_page_magic-fields-2-toolkit-page' ) {
        return;
    }
    wp_enqueue_style(  'mf2tk_clean_files_mf', plugins_url( 'css/mf2tk_clean_mf_files.css', __FILE__ ) );
    wp_enqueue_style(  'mf2tk-jquery-ui',      plugins_url( 'css/mf2tk-jquery-ui.min.css',  __FILE__ ) );
    wp_enqueue_script( 'jquery-ui-tabs' );
    wp_enqueue_script( 'mf2tk_clean_mf_files', plugins_url( 'js/mf2tk_clean_mf_files.js',   __FILE__ ), [ 'jquery' ] );
} );

function mf2tk_get_unreferenced_image_files_in_folder_files_mf( ) {
    global $wpdb;
    global $mf_domain;
    
    $MF_TABLE_CUSTOM_FIELDS = MF_TABLE_CUSTOM_FIELDS;
    $MF_FILES_URL           = MF_FILES_URL;
    
    if ( !( $handle = opendir( MF_FILES_DIR ) ) ) {
        return;
    }
    echo( '<h2>' . __( 'Unreferenced Files in Folder', $mf_domain ) . '".../wp-content/files_mf/"</h2><div id="mf2tk-unreferenced-files">'
        . '<ul><li><a href="#dir-files-mc">' . __( 'Files of Folder', $mf_domain ) . '".../wp-content/files_mf/"</a></li>'
        . '<li><a href="#dir-referenced-mc">' . __( 'Referenced by Published or Draft Posts', $mf_domain ) . '</a></li>'
        . '<li><a href="#dir-unreferenced-mc">' . __( 'Unreferenced by Published or Draft Posts', $mf_domain ) . '</a></li></ul>' );
    $entries = [ ];
    while ( FALSE !== ($entry = readdir( $handle ) ) ) {
      if ( is_dir( MF_FILES_DIR . $entry ) ) {
          continue;
      }
      $entries[ ] = $entry;    
    }
    closedir( $handle );
    $entries = array_map( function( $v ) {
        $o = new stdClass( );
        $o->real_name = $v;
        $o->friendly_name = substr( $v, 10);
        $o->size = filesize( MF_FILES_DIR . $v );
        $o->date = date( DATE_RSS, (integer) substr( $v, 0, 10 ) );
        return $o;
    }, $entries );
    usort( $entries, function( $a, $b ) {
        if ( $a->friendly_name === $b->friendly_name ) {
            return 0;
        }
        return $a->friendly_name < $b->friendly_name ? -1 : 1;
    } );
    echo( '<div id="dir-files-mc"><table class="mf2tk-unreferenced">' );
    echo( '<tr><th>No.</th><th>' . __( 'Friendly Name', $mf_domain ) . '</th><th>' . __( 'Real File Name', $mf_domain ) . '</th><th>Size</th><th>'
        . __( 'Time Stamp', $mf_domain) . '</th></tr>' );
    foreach ( $entries as $i => $entry ) {
        $j = $i + 1;
        echo( <<<EOD
<tr><td>{$j}</td><td><a href="{$MF_FILES_URL}{$entry->real_name}" target="_blank">{$entry->friendly_name}</td><td>{$entry->real_name}</td>
    <td>{$entry->size}</td><td>{$entry->date}</td></tr>
EOD
        );
    }
    echo( '</table></div>' );
    $sql = <<<EOD
SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta
    WHERE meta_key IN (SELECT name FROM $MF_TABLE_CUSTOM_FIELDS WHERE type = "image" OR type = "audio" OR type = "file" )
        AND post_id IN ( SELECT ID FROM  $wpdb->posts WHERE post_status = "publish" OR post_status = "draft" OR post_status = "auto-draft" )
    ORDER BY SUBSTR( meta_value, 11 ) ASC
EOD;
    $results = $wpdb->get_results( $sql, ARRAY_A );
    echo( '<div id="dir-referenced-mc"><table class="mf2tk-unreferenced"><th>No.</th><th>Friendly Name</th><th>'
        . __( 'Real File Name', $mf_domain ) . '</th><th>' . __( 'Referenced by', $mf_domain ) . '</th><th>via Field</th>' );
    $referenced = [ ];
    $previous = '';
    $count = 0;
    foreach ( $results as $result ) {
        $value = $result[ 'meta_value' ];
        if ( !$value ) {
            continue;
        }
        if ( $value !== $previous ) {
            ++$count;
            $previous = $value;
        }
        $referenced[] = $value;
        echo( '<tr><td>' . $count . '</td>'
            . "<td><a href=\"{$MF_FILES_URL}{$value}\" target=\"_blank\">" . substr( $value, 10 ) . '</a></td>'
            . "<td><a href=\"{$MF_FILES_URL}{$value}\" target=\"_blank\">{$value}</a></td>"
            . '<td><a href="' . get_permalink( $result[ 'post_id' ] ) . '" target="_blank">' . get_the_title( $result[ 'post_id' ] ) . '</a></td>'
            . "<td>{$result['meta_key']}</td></tr>" );
    }   # foreach ( $results as $result ) {
    echo( '</table></div>' );
    $entries = array_map( function( $o ) {
        return $o->real_name;
    }, $entries );
    $unreferenced = array_merge( array_diff( $entries, $referenced ) );
    echo( '<div id="dir-unreferenced-mc">' );
    echo( '<form method="post" action="' . get_option('siteurl') . '/wp-admin/options-general.php?page=get_unreferenced_files_mc&amp;noheader=true">'
      . '<button class="mf2tk-delete-mf-files">' . __( 'Select All', $mf_domain ) . '</button>&nbsp;&nbsp;' . '<button class="mf2tk-delete-mf-files">'
      . __( 'Clear All', $mf_domain ) . '</button><br><hr><ol>' );
    foreach ( $unreferenced as $i => $unreference ) {
        echo( <<<EOD
<li><input type="checkbox" class="mf2tk-delete-mf-files" name="to-be-deleted-{$i}" value="{$unreference}">&nbsp;&nbsp;
    <a href="{$MF_FILES_URL}{$unreference}" target="_blank"><span class="mf2tk-delete-mf-files">&quot;{$unreference}&quot;</span></a></li>
EOD
        );
    }
    echo( '</ol><hr><br><input type="submit" value="' . __( 'Delete Checked', $mf_domain ) . '"></form></div>' );
    echo( '</div>' );
}   # function mf2tk_get_unreferenced_image_files_in_folder_files_mf( ) {

if ( is_admin( ) ) {
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], 'wp-admin/options-general.php?page=magic-fields-2-toolkit-page' ) !== FALSE ) {
        add_action( 'admin_notices', function( ) {
            if ( $deleted = get_transient( 'deleted_files_mc17' ) ) {
                echo( "<div style=\"padding:0px 20px;border:1px solid red;margin:20px;\">$deleted</div>" );
                delete_transient( 'deleted_files_mc17' );
            }
        } );
        add_action( 'settings_page_magic-fields-2-toolkit-page', function( ) {
            echo( '<div id="mf2tk-unreferenced-files-container">' );
            mf2tk_get_unreferenced_image_files_in_folder_files_mf( );
            echo( '</div>' );
        }, 11 );
    }
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], 'wp-admin/options-general.php?page=get_unreferenced_files_mc' ) !== FALSE ) {
        add_action( 'admin_menu', function( ) {
            global $_registered_pages;
            $hookname = get_plugin_page_hookname( 'get_unreferenced_files_mc', 'options-general.php' );
            add_action( $hookname, function( ) {
                global $mf_domain;
                $MF_FILES_DIR = MF_FILES_DIR;
                $deleted = '<h3>' . __( 'Status of File Delete Requests', $mf_domain ) . '</h3><ul>';
                $unlinked = 0;
                $not_unlinked = 0;
                foreach ( $_REQUEST as $key => $request ) {
                    if ( strpos( $key, 'to-be-deleted-' ) !== 0 ) {
                        continue;
                    }
                    if ( unlink( "{$MF_FILES_DIR}{$request}" ) ) {
                        $status = '<span class="mf2tk-success">' . __( 'deleted', $mf_domain ) . '</span>';
                        ++$unlinked;
                    } else {
                        $status = '<span class="mf2tk-failure">' . __( 'failed',  $mf_domain ) . '</span>';
                        ++$not_unlinked;
                    }
                    $deleted .= "<li>$status - \"{$MF_FILES_DIR}{$request}\"</li>";
                } 
                $deleted .= '</ul><h3><span class="mf2tk-success">' . __( 'deleted', $mf_domain ) . " $unlinked " . __( 'files', $mf_domain )
                    . '</span>, &nbsp;&nbsp;<span class="mf2tk-failure">' . __( 'failed', $mf_domain ) . " $not_unlinked " . __( 'files', $mf_domain )
                    . '</span>.</h3>';
                set_transient( 'deleted_files_mc17', $deleted, 10 );
                wp_redirect( 'options-general.php?page=magic-fields-2-toolkit-page' ); 
                die;
            } );   # add_action( $hookname, function( ) {
            $_registered_pages[ $hookname ] = TRUE;
        } );   # add_action( 'admin_menu', function( ) {
    }   # if ( strpos( $_SERVER[ 'REQUEST_URI' ], 'wp-admin/options-general.php?page=get_unreferenced_files_mc' ) !== FALSE ) {
}   # if ( is_admin( ) ) {

?>