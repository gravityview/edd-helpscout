<?php

namespace GitHub\HelpScout;

defined( 'ABSPATH' ) or exit;

/**
 * Resends a purchase receipt
 */
function create_issue() {
	authorize_request();

	die('<script>window.close();</script>');
}

/**
 * Get customer info
 */
function get_issue_info() {
	new Endpoint();
}


add_action( 'github_helpscout_issue_info', 'GitHub\\HelpScout\\get_issue_info' );