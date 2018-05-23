<?php

namespace GitHub\HelpScout;

use HelpScout\ApiClient;

/**
 * This class takes care of requests coming from HelpScout App Integrations
 */
class Endpoint {

	/**
	 * @var array|mixed
	 */
	private $data;

	/**
	 * @var array
	 */
	private $conversation = array();

	/**
	 * @var array
	 */
	private $custom_fields = array();

	/**
	 * @var array
	 */
	private $github_issues = array();

	/**
	 * Constructor
	 */
	public function __construct() {

		// get request data
		$this->data = $this->parse_data();

		// validate request
		if ( ! $this->validate() ) {
			$this->respond( 'Invalid signature' );
			exit;
		}

		// get customer email(s)
		$this->setup_conversation_info();

		// get customer payment(s)
		$this->github_issues = $this->get_github_issues();

		// build the final response HTML for HelpScout
		$html = $this->build_response_html();

		// respond with the built HTML string
		$this->respond( $html );
	}

	/**
	 * @return array|mixed
	 */
	private function parse_data() {

		$data_string = file_get_contents( 'php://input' );
		$data        = json_decode( $data_string, true );

		return $data;
	}

	/**
	 * Validate the request
	 *
	 * - Validates the payload
	 * - Validates the request signature
	 *
	 * @return bool
	 */
	private function validate() {

		// we need at least this
		if ( ! isset( $this->data['ticket']['id'] ) ) {
			return false;
		}

		// check request signature
		$request = new Request( $this->data );

		if ( isset( $_SERVER['HTTP_X_HELPSCOUT_SIGNATURE'] ) && $request->signature_equals( $_SERVER['HTTP_X_HELPSCOUT_SIGNATURE'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get an array of emails belonging to the customer
	 *
	 * @return array
	 */
	private function setup_conversation_info() {

		$scout = ApiClient::getInstance();
		$scout->setKey( GITHUB_HELPSCOUT_HS_API_KEY );

		try {

			$this->conversation = $scout->getConversation( $this->data['ticket']['id'] );

			$this->custom_fields = $this->conversation->getCustomFields();

		} catch ( \Exception $e ) {
		}

		return array();
	}

	/**
	 * Query all payments belonging to the customer (by email)
	 *
	 * @return array
	 */
	private function get_github_issues() {

		$github_issues = array();

		/**
		 * @var HelpScout\model\ref\customfields $custom_field
		 */
		foreach ( $this->custom_fields as $custom_field ) {

			$name = $custom_field->getName();

			if( preg_match( '/github/ism', $name ) ) {

				$issue_urls = explode( ' ', $custom_field->getValue() );

				foreach ( $issue_urls as $issue_url ) {
					preg_match( '/https\:\/\/github.com\/(.+?)\/issues\/(\d+)/ism', $issue_url, $matches );

					if( $matches ) {
						$github_issues[] = array(
							'url' => trim( $issue_url ),
							'repo' => $matches[1],
							'id' => trim( $matches[2] ),
						);
					}
				}
			}
		}

		return $github_issues;
	}

	/**
	 * Process the request
	 *  - Find purchase data
	 *  - Generate response*
	 * @link http://developer.helpscout.net/custom-apps/style-guide/ HelpScout Custom Apps Style Guide
	 * @return string
	 */
	private function build_response_html() {

		if ( empty( $this->github_issues ) ) {
			return 'No connected issues.';
		}

		// build HTML output
		$html = '';
		foreach ( $this->github_issues as $issue ) {
			$html .= str_replace( '\t', '', $this->issue_row( $issue ) );
		}

		return $html;
	}

	/**
	 * @param array $issue
	 *
	 * @return string
	 */
	public function issue_row( array $issue ) {
		ob_start();
		include dirname( GITHUB_HELPSCOUT_FILE ) . '/views/issue-row.php';
		$html = ob_get_clean();

		return $html;
	}

	/**
	 * Set JSON headers, return the given response string
	 *
	 * @param string $html HTML content of the response
	 * @param int    $code The HTTP status code to respond with
	 */
	private function respond( $html, $code = 200 ) {
		$response = array( 'html' => $html );

		// clear output, some plugins might have thrown errors by now.
		if ( ob_get_level() > 0 ) {
			ob_end_clean();
		}

		status_header( $code );
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		die();
	}

}
