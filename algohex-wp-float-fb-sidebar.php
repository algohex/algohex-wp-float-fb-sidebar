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

	// add css and js
	// wp_register_style( 'css-bootstrap', plugins_url( __FILE__, 'includes/css/bootstrap.min.css' ) );
	// wp_enqueue_style( 'css-bootstrap' );
	// wp_register_script( 'js-bootstrap', plugins_url( __FILE__, 'includes/js/bootstrap.min.js' ) );
	// wp_enqueue_style( 'js-bootstrap' );

	// add facebook page url options
	add_option(
		  'alffs_facebook_page_url'
		, $value      = 'https://www.facebook.com/facebook/'
		, $deprecated = ''
		, $autoload   = 'yes'
	);

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'alffs_plugin_activation' );

// deactivation
function alffs_plugin_deactivation() {
	delete_option( 'alffs_facebook_page_url' );

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

	if ( ! empty( $_POST ) ) {
		print_r( $_POST );

		$src = 'https://www.facebook.com/plugins/page.php?href='
			. $_POST['facebookPageUrl']
			. '&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=291823838192298'
		;


		?>
		<iframe src=<?php echo $src; ?> width="340" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
		<?php
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

				</tbody>
			</table>

			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
			</p>
		</form>
	</div>

	<?php
}