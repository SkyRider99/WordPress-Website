<?php
/**
 * Tuxedo Responsive Widget Columns Uninstall
 *
 * Uninstalling Tuxedo Responsive Widget Columns deletes all options.
 *
 * @package TuxedoRWC
 * @since 1.0.0
 */

/** Check if we are uninstalling. */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/** Delete options. */
delete_option( 'tuxrwc_gutter' );
delete_option( 'tuxrwc_collapse' );