<?php

/*
Plugin Name: Magic Fields 2 Toolkit
Plugin URI:  http://magicfields17.wordpress.com/magic-fields-2-toolkit-0-4-2/
Description: post content template interpreter, custom fields shortcode, media shortcodes, custom post copier, ...
Version:     1.2.1.2.1
Author:      Magenta Cuda
Author URI:  http://magentacuda.wordpress.com
License:     GPL2
*/

/*  
    Copyright 2013  Magenta Cuda

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

 /*
  Project I: Magic Fields 2 Toolkit
  This project is being maintained but no longer being actively developed.
 */
 
# The check for version is in its own file since if the file contains PHP 5.4 code an ugly fatal error will be triggered instead

list( $major, $minor ) = sscanf( phpversion(), '%D.%D' );
$tested_major = 5;
$tested_minor = 4;
if ( !( $major > $tested_major || ( $major == $tested_major && $minor >= $tested_minor ) ) ) {
    add_action( 'admin_notices', function () use ( $major, $minor, $tested_major, $tested_minor ) {
        echo <<<EOD
<div style="padding:10px 20px;border:2px solid red;margin:50px 20px;font-weight:bold;">
    NextGEN Gallery's nggtags for WordPress's Media Library will not work with PHP version $major.$minor;
    Please uninstall it or upgrade your PHP version to $tested_major.$tested_minor or later.
</div>
EOD;
    } );
    return;
}

register_activation_hook( __FILE__, function( ) {
    set_transient( 'magic_fields_2_tolkit_activated', 'magic_fields_2_tolkit_activated', 10 );
} );

if ( is_admin( ) && get_transient( 'magic_fields_2_tolkit_activated' ) ) {
    add_action( 'admin_notices', function( ) {
?>
<div style="clear:both;border:2px solid red;padding:5px 10px;margin:10px;">
After installing Magic Fields 2 Toolkit you should visit its settings page -
<a href="<?php echo admin_url( 'options-general.php?page=magic-fields-2-toolkit-page' ); ?>">
Settings -> Magic Fields 2 Toolkit</a>.
</div>
<?php
        delete_transient( 'magic_fields_2_tolkit_activated' );
    } );
}

# ok to start loading PHP 5.4 code

require( dirname( __FILE__ ) . '/magic-fields-2-toolkit.php' );
