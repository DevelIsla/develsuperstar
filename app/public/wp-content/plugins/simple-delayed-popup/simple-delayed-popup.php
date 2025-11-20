<?php
/**
 * Plugin Name: Simple Delayed Popup
 * Description: Displays a popup message after a configurable delay.
 * Version: 1.0
 * Author: Antigravity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Simple_Delayed_Popup {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( $this, 'render_popup' ) );
	}

	public function add_admin_menu() {
		add_options_page(
			'Simple Popup Settings',
			'Simple Popup',
			'manage_options',
			'simple-delayed-popup',
			array( $this, 'settings_page_html' )
		);
	}

	public function register_settings() {
		register_setting( 'sdp_options', 'sdp_popup_message' );
		register_setting( 'sdp_options', 'sdp_popup_delay', array(
			'default' => 5000,
			'sanitize_callback' => 'absint',
		) );
	}

	public function settings_page_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'sdp_options' );
				do_settings_sections( 'sdp_options' );
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Popup Message</th>
						<td>
							<textarea name="sdp_popup_message" rows="5" cols="50" class="large-text"><?php echo esc_textarea( get_option( 'sdp_popup_message' ) ); ?></textarea>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Delay (milliseconds)</th>
						<td>
							<input type="number" name="sdp_popup_delay" value="<?php echo esc_attr( get_option( 'sdp_popup_delay', 5000 ) ); ?>" />
							<p class="description">1000 ms = 1 second. Default is 5000.</p>
						</td>
					</tr>
				</table>
				<?php submit_button( 'Save Settings' ); ?>
			</form>
		</div>
		<?php
	}

	public function enqueue_assets() {
		wp_enqueue_style( 'sdp-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), '1.0' );
		wp_enqueue_script( 'sdp-script', plugin_dir_url( __FILE__ ) . 'js/script.js', array(), '1.0', true );

		$delay = get_option( 'sdp_popup_delay', 5000 );
		wp_localize_script( 'sdp-script', 'sdpSettings', array(
			'delay' => $delay,
		) );
	}

	public function render_popup() {
		$message = get_option( 'sdp_popup_message' );
		if ( empty( $message ) ) {
			return;
		}
		?>
		<div id="sdp-overlay" class="sdp-hidden">
			<div id="sdp-popup">
				<button id="sdp-close">&times;</button>
				<div class="sdp-content">
					<?php echo wp_kses_post( wpautop( $message ) ); ?>
				</div>
			</div>
		</div>
		<?php
	}
}

new Simple_Delayed_Popup();
