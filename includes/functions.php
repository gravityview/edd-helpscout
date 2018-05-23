<?php

namespace GitHub\HelpScout;

/**
 * This will kill the request if the current request doesn't pass authorization checks.
 */
function authorize_request() {
	if( ! verify_request_signature() || ! verify_referer() ) {
		status_header( 401 );
		exit;
	}
}

/**
 * Verify that we're coming from secure.helpscout.net.
 *
 * This is easily spoofed, so keep that in mind when using this as your only auth check.
 *
 * @return bool
 */
function verify_referer() {
	return isset( $_SERVER['HTTP_REFERER'] ) && strpos( $_SERVER['HTTP_REFERER'], 'https://secure.helpscout.net/' ) === 0;
}

/**
 * Verify the request signature that was received.
 *
 * This is harder to spoof as it uses your secret key.
 *
 * @return bool
 */
function verify_request_signature() {

	if( empty( $_GET['s'] ) ) {
		return false;
	}

	$params = $_GET;
	$signature = $params['s'];
	unset( $params['s'] );

	$request = new Request( $params );
	return $request->signature_equals( $signature );
}

/**
 * Listen for API actions
 */
function listen_for_actions() {
	$listener = new Listener( GITHUB_HELPSCOUT_API_PATH );
	$action = $listener->listen( $_SERVER['REQUEST_URI'] );

	if( ! empty( $action ) ) {

		/**
		 * Perform an API action. Request is unauthorized at this point, so make sure to perform auth checks in your action callback.
		 *
		 * The dynamic portion of the hook, `$action`, refers to the name of the action.
		 *
		 * @see authorize_request
		 * @see verify_request_signature
		 */
		do_action( 'github_helpscout_' . $action );
	}
}