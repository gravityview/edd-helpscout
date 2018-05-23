<?php

namespace GitHub\HelpScout;

defined( 'ABSPATH' ) or exit;

// define some default constants
require_once __DIR__ . '/includes/default-constants.php';

// Load default API actions
require_once __DIR__ . '/includes/default-actions.php';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/class-request.php';
require_once __DIR__ . '/includes/class-listener.php';
require_once __DIR__ . '/includes/class-endpoint.php';
require_once __DIR__ . '/helpscout-api-php/src/HelpScout/ApiClient.php';
require_once __DIR__ . '/shuber/curl/curl.php';

// Check for API actions
if( ! is_admin() ) {
	add_action( 'init', 'GitHub\\HelpScout\\listen_for_actions' );
}