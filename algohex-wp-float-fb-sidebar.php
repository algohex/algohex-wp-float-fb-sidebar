<?php
/**
 * Plugin Name: Floating Facebook Sidebar - by Algohex
 * Plugin URI:
 * Description: Display a Facebook sidebar on your website, work with any public Facebook page
 * Version: 0.1.0
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
 * Floating Facebook Sidebar Core
 */
// register menu page
function alffs_options_page() {
	$options = new Options;

	add_menu_page(
		  'Floating Facebook Sidebar'
		, 'Floating Facebook Sidebar'
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
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$options = new Options;

	// update options
	if ( ! empty( $_POST ) ) {

		if ( ! empty( $_POST[ 'facebookPageUrl' ] ) ) {
			update_option( 'alffs_facebook_page_url', $_POST['facebookPageUrl'] );
		}

		if ( ! empty( $_POST[ 'facebookFloatingPostion' ] ) ) {
			update_option( 'alffs_facebook_floating_position', $_POST['facebookFloatingPostion'] );
		}

		if ( ! empty( $_POST[ 'facebookFloatingIcon' ] ) ) {
			update_option( 'alffs_facebook_floating_icon', $_POST['facebookFloatingIcon'] );
		}

	}
	?>

	<div class="wrap">
		<?php if ( $options->getOptionPageTitle() != '' ) { ?>
			<h1><?php echo $options->getOptionPageTitle(); ?></h1>
		<?php } ?>
		<small>- by Algohex -</small>

		<form action="<?php echo esc_url( admin_url('admin.php') . '?page=alffs'); ?>" method="post">
			<table class="form-table">
				<tbody>

					<tr>
						<td width="20%">
							<label>Facebook Page URL</label>
						</td>
						<td>
							<input class="regular-text" type="text" name="facebookPageUrl" value="<?php echo $options->getFacebookPageUrl() ?>" />
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
							<input type="radio" name="facebookFloatingIcon" value="1" <?php if ( $options->getFacebookFloatingIcon() == '1' ) echo 'checked'; ?>> <img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/ficon1.png' ?>" alt="Aloghex Facebook Icon 1" /> <br>
							<input type="radio" name="facebookFloatingIcon" value="2" <?php if ( $options->getFacebookFloatingIcon() == '2' ) echo 'checked'; ?>> <img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/ficon2.png' ?>" alt="Aloghex Facebook Icon 2" /> <br>
							<input type="radio" name="facebookFloatingIcon" value="3" <?php if ( $options->getFacebookFloatingIcon() == '3' ) echo 'checked'; ?>> <img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/ficon3.png' ?>" alt="Aloghex Facebook Icon 3" />
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

	$src = 'https://www.facebook.com/plugins/page.php?href='
			. $options->getFacebookPageUrl()
			. '&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=291823838192298'
		;

	?>

	<style type="text/css">
		.algohexFbIcon:hover {
			cursor: pointer;
		}
	</style>

	<div class="algohex-floating-facebook" id="algohexFbDiv" style="
		position                                              : fixed;
		z-index                                               : 999999;
		<?php echo $options->getFacebookFloatingPosition() ?> : -340px;
		top                                                   : 20%;
		-webkit-transition                                    : 0.5s;
		transition                                            : 0.5s;
	">
		<?php if ( $options->getFacebookFloatingPosition() == 'right' ) { ?>
		<div class="algohexFbIcon" onclick="algohexFbIcon()">
			<img
				style  = "position: absolute; left: -40px; background-color: white;"
				src    = "<?php echo plugin_dir_url( __FILE__ ) . '/images/ficon' . $options->getFacebookFloatingIcon() . '.png'?>"
				alt    = "Algohex Facebook Icon"
			/>
		</div>
		<?php } ?>
		<iframe
			src               = "<?php echo $src; ?>"
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
				src    = "<?php echo plugin_dir_url( __FILE__ ) . '/images/ficon' . $options->getFacebookFloatingIcon() . '.png'?>"
				alt    = "Algohex Facebook Icon"
			/>
		</div>
		<?php } ?>
	</div>

	<script type="text/javascript">
		function algohexFbIcon() {
			var element = document.getElementById( 'algohexFbDiv' );
			if ( element.style.<?php echo $options->getFacebookFloatingPosition() ?> != '0px' ) {
				element.style.<?php echo $options->getFacebookFloatingPosition() ?> = '0px';
			} else {
				element.style.<?php echo $options->getFacebookFloatingPosition() ?> = '-340px';
			}
		}
	</script>

	<?php
}
add_action( 'get_footer', 'alffs_frontend_hook' );