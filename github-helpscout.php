<?php
/*
Plugin Name: GitHub integration for HelpScout
Plugin URI: https://gravityview.co/
Description: GitHub integration for HelpScout, thanks to <a href="https://dannyvankooten.com/">Danny van Kooten</a>
Version: 2.0-beta
Author: GravityView
Author URI: https://gravityview.co
Text Domain: github-helpscout
Requires PHP: 5.6
Domain Path: /languages
License: GPL v3

GitHub integration for HelpScout
Copyright (C) 2018, GravityView, hello@gravityview.co

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


// prevent dire file access
defined( 'ABSPATH' ) or exit;

// define some useful constants
define( 'GITHUB_HELPSCOUT_VERSION', '0.1' );
define( 'GITHUB_HELPSCOUT_FILE', __FILE__ );


/**
 * @ignore
 */
function _load_github_helpscout() {

	// do nothing if PHP is below version 5.6
	if( version_compare( PHP_VERSION, '5.6', '<' ) ) {
		return;
	}

	// go!
	require __DIR__ . '/bootstrap.php';
}

/**
 * Bootstrap the plugin at `plugins_loaded` (after EDD)
 */
add_action( 'plugins_loaded', '_load_github_helpscout' , 90 );
