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
	private $facebook_floating_postion;

	function __construct() {
		$this->facebook_page_url             = 'https://www.facebook.com/facebook/';
		$this->facebook_page_url_option_name = 'alffs_facebook_page_url';
		$this->option_page_title             = 'Floating Facebook Sidebar';
		$this->facebook_floating_postion     = 'right';
	}

	// getter and setter
	function setFacebookFloatingPostion( $facebook_floating_postion ) {
		$this->facebook_floating_postion = $facebook_floating_postion;
	}
	function getFacebookFloatingPosition() {
		return get_option( 'alffs_facebook_floating_position' );
	}

	function setFacebookPageUrl( $facebook_page_url ) {
		$this->facebook_page_url = $facebook_page_url;
	}
	function getFacebookPageUrl() {
		return get_option( $this->facebook_page_url_option_name );
	}

	function getFacebookPageUrlOptionName() {
		return $this->facebook_page_url_option_name;
	}

	function getOptionPageTitle() {
		return $this->option_page_title;
	}

}