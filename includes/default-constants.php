<?php

defined( 'ABSPATH' ) or exit;

// define API path (if it's not set already)
if( ! defined( 'GITHUB_HELPSCOUT_API_PATH' ) ) {
	define( 'GITHUB_HELPSCOUT_API_PATH', '/github-helpscout-api/' );
}

if( ! defined( 'GITHUB_HELPSCOUT_SECRET_KEY' ) ) {
	define( 'GITHUB_HELPSCOUT_SECRET_KEY', wp_generate_password( 40 ) );
	trigger_error( sprintf( "Please set the %s constant in your %s file.", 'GITHUB_HELPSCOUT_SECRET_KEY', 'wp-config.php' ) );
}