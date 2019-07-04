<?php
/**
 * Plugin Name: Floating Sidebar for Facebook - by Algohex
 * Plugin URI:
 * Description: Display a floating Facebook sidebar on your website, work with any public Facebook page
 * Version: 0.4.0
 * Author: Algohex Web Developer Team
 * Author URI:
 * License: GNU-3.0
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// activation
function alffs_plugin_activation() {

	// add facebook page url options
	add_option(
		  'alffs_facebook_page_url'
		, $value      = 'https://www.facebook.com/facebook/'
		, $deprecated = ''
		, $autoload   = 'yes'
	);
	// floating bar position
	add_option(
		  'alffs_facebook_floating_position'
		, $value      = 'right'
		, $deprecated = ''
		, $autoload   = 'yes'
	);
	// facebook icon option
	add_option(
		  'alffs_facebook_floating_icon'
		, $value      = '1'
		, $deprecated = ''
		, $autoload   = 'yes'
	);

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'alffs_plugin_activation' );

// deactivation
function alffs_plugin_deactivation() {
	delete_option( 'alffs_facebook_page_url' );
	delete_option( 'alffs_facebook_floating_position' );
	delete_option( 'alffs_facebook_floating_icon' );

	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'alffs_plugin_deactivation' );

/**
 * Floating Sidebar for Facebook Core
 */
// register menu page
function alffs_options_page() {
	$options = new Options;

	add_menu_page(
		  'Floating Sidebar for Facebook'
		, 'Floating Sidebar for Facebook'
		, 'manage_options'
		, 'alffs'
		, 'alffs_options_page_html'
		, plugin_dir_url(__FILE__) . 'images/algohex-enterprise-icon-white.png'
		, 80
	);
}
add_action( 'admin_menu', 'alffs_options_page' );


include plugin_dir_path( __FILE__ ) . 'admin/Options.php';

function alffs_options_page_html () {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'administrator' ) ) {
		return;
	}
	$options = new Options;
	$errors  = new WP_Error();

	// update options
	if (
			! empty( $_POST )
		&& isset( $_GET['nonce'] )
		&& $_GET['action'] == 'alffs_update_options'
		&& wp_verify_nonce( $_GET['nonce'], 'alffs_update_options' )
		&& check_admin_referer( 'alffs_update_options', 'nonce' )
	) {

		if ( ! empty( $_POST[ 'facebookPageUrl' ] ) ) {
			$facebookPageUrl = esc_url_raw( $_POST['facebookPageUrl'] );
			preg_match( '/(www.)(\w.+)(.com)/', $facebookPageUrl, $matches );
			if ( $matches[0] == 'www.facebook.com' ) {
				update_option( 'alffs_facebook_page_url', $facebookPageUrl );
			} else {
				$errors->add( 'page_url_error', __( '<strong>Notice</strong>: The Facebook page URL is invalid.' ) );
			}
		}

		if ( ! empty( $_POST[ 'facebookFloatingPostion' ] ) ) {
			$floatingOption = sanitize_option( 'alffs_facebook_floating_position', $_POST['facebookFloatingPostion'] );
			if ( $floatingOption == 'left' || $floatingOption == 'right' ) {
				update_option( 'alffs_facebook_floating_position', $floatingOption );
			} else {
				$errors->add( 'floating_position_error', __( '<strong>Notice</strong>: The floating position is invalid.' ) );
			}
		}

		if ( ! empty( $_POST[ 'facebookFloatingIcon' ] ) ) {
			$floatingIcon = sanitize_option( 'alffs_facebook_floating_icon',$_POST['facebookFloatingIcon'] );
			if ( $floatingIcon >= 1 && $floatingIcon <=3 ) {
				update_option( 'alffs_facebook_floating_icon', $floatingIcon );
			} else {
				$errors->add( 'floating_icon_error', __( '<strong>Notice</strong>: The floating icon is invalid.' ) );
			}
		}

	}

	// preapre form action url
	$formActionUrl = add_query_arg(
		[
			'action' => 'alffs_update_options',
			'nonce'  => wp_create_nonce('alffs_update_options'),
		],
		esc_url( admin_url('admin.php') . '?page=alffs' )
	);
	?>

	<div class="wrap">
		<?php
			if (isset($errors) && sizeof($errors)>0 && $errors->get_error_code()) {
				foreach ($errors->errors as $error) {
					echo '<div class="notice notice-error"><p>'.$error[0].'</p></div>';
				}
			}
		?>
		<?php if ( $options->getOptionPageTitle() != '' ) { ?>
			<h1><?php esc_html_e( $options->getOptionPageTitle() ); ?></h1>
		<?php } ?>
		<small>- by Algohex -</small>

		<form action="<?php esc_html_e( $formActionUrl ) ; ?>" method="post">
			<table class="form-table">
				<tbody>

					<tr>
						<td width="20%">
							<label>Facebook Page URL</label>
						</td>
						<td>
							<input class="regular-text" type="text" name="facebookPageUrl" value="<?php esc_html_e( $options->getFacebookPageUrl() ) ?>" />
						</td>
					</tr>

					<tr>
						<td width="20%">
							<label>Floating Position</label>
						</td>
						<td>
							<select name="facebookFloatingPostion">
								<option value="left" <?php if ( $options->getFacebookFloatingPosition() == 'left' ) echo 'selected="selected"'; ?>>Left</option>
								<option value="right" <?php if ( $options->getFacebookFloatingPosition() == 'right' ) echo 'selected="selected"'; ?>>Right</option>
							</select>
						</td>
					</tr>

					<tr>
						<td width="20%">
							<label>Floating Icon</label>
						</td>
						<td>
							<label for="facebookFloatingIcon1">
								<input type="radio" name="facebookFloatingIcon" id="facebookFloatingIcon1" value="1" <?php if ( $options->getFacebookFloatingIcon() == '1' ) echo 'checked'; ?>> <img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/ficon1.png' ?>" alt="Aloghex Facebook Icon 1" /> <br>
							</label>
							<label for="facebookFloatingIcon2">
								<input type="radio" name="facebookFloatingIcon" id="facebookFloatingIcon2" value="2" <?php if ( $options->getFacebookFloatingIcon() == '2' ) echo 'checked'; ?>> <img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/ficon2.png' ?>" alt="Aloghex Facebook Icon 2" /> <br>
							</label>
							<label for="facebookFloatingIcon3">
								<input type="radio" name="facebookFloatingIcon" id="facebookFloatingIcon3" value="3" <?php if ( $options->getFacebookFloatingIcon() == '3' ) echo 'checked'; ?>> <img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/ficon3.png' ?>" alt="Aloghex Facebook Icon 3" />
							</label>
						</td>
					</tr>

				</tbody>
			</table>

			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
			</p>
		</form>
	</div>

	<?php
}

function alffs_frontend_hook() {
	$options = new Options;

	if ( esc_url( $options->getFacebookPageUrl() ) == '' ) {
		return;
	}

	$src = 'https://www.facebook.com/plugins/page.php?href='
			. esc_url( $options->getFacebookPageUrl() )
			. '&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=291823838192298'
		;

	$iconOption = esc_html( $options->getFacebookFloatingIcon() );
	?>

	<style type="text/css">
		.algohexFbIcon:hover {
			cursor: pointer;
		}
	</style>

	<div class="algohex-floating-facebook" id="algohexFbDiv" style="
		position                                                       : fixed;
		z-index                                                        : 999999;
		<?php esc_html_e( $options->getFacebookFloatingPosition() ) ?> : -340px;
		top                                                            : 20%;
		-webkit-transition                                             : 0.5s;
		transition                                                     : 0.5s;
	">
		<?php if ( $options->getFacebookFloatingPosition() == 'right' ) { ?>
		<div class="algohexFbIcon" onclick="algohexFbIcon()">
			<img
				style  = "position: absolute; left: -40px; background-color: white;"
				src    = "<?php echo plugin_dir_url( __FILE__ ) . 'images/ficon' . $iconOption . '.png'?>"
				alt    = "Algohex Facebook Icon"
			/>
		</div>
		<?php } ?>
		<iframe
			src               = "<?php esc_html_e( $src ); ?>"
			width             = "340"
			height            = "450"
			style             = "border:none;overflow:hidden"
			scrolling         = "no"
			frameborder       = "0"
			allowTransparency = "true"
			allow             = "encrypted-media"
		></iframe>
		<?php if ( $options->getFacebookFloatingPosition() == 'left' ) { ?>
		<div class="algohexFbIcon" onclick="algohexFbIcon()">
			<img
				style  = "position: absolute; right: -40px; top: 0; background-color: white;"
				src    = "<?php echo plugin_dir_url( __FILE__ ) . 'images/ficon' . $iconOption . '.png'?>"
				alt    = "Algohex Facebook Icon"
			/>
		</div>
		<?php } ?>
	</div>

	<script type="text/javascript">
		function algohexFbIcon() {
			var element = document.getElementById( 'algohexFbDiv' );
			if ( element.style.<?php esc_html_e( $options->getFacebookFloatingPosition() ) ?> != '0px' ) {
				element.style.<?php esc_html_e( $options->getFacebookFloatingPosition() ) ?> = '0px';
			} else {
				element.style.<?php esc_html_e( $options->getFacebookFloatingPosition() ) ?> = '-340px';
			}
		}
	</script>

	<?php
}
add_action( 'get_footer', 'alffs_frontend_hook' );