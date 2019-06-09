<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Floating Facebook Sidebar Options
 */
class Options {

	private $facebook_page_url;

	private $faecbook_page_url_option_name;
	private $option_page_title;

	function __construct() {
		$this->facebook_page_url             = 'https://www.facebook.com/facebook/';
		$this->facebook_page_url_option_name = 'alffs_facebook_page_url';
		$this->option_page_title             = 'Floating Facebook Sidebar';
	}

	// getter and setter
	function setFacebookPageUrl( $facebook_page_url ) {
		$this->facebook_page_url = $facebook_page_url;
	}
	function getFacebookPageUrl() {
		return $this->facebook_page_url;
	}

	function getFacebookPageUrlOptionName() {
		return $this->facebook_page_url_option_name;
	}

	function getOptionPageTitle() {
		return $this->option_page_title;
	}

}