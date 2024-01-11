<?php
/**
 * Writing settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
//require_once __DIR__ . '/admin.php';
require_once get_home_path() . 'wp-admin/admin.php';

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

// Resaltado en menú
global $submenu_file;
$submenu_file = "options-writing.php";


// Used in the HTML title tag.
$title       = __( 'Writing Settings' );
$parent_file = 'options-general.php';

get_current_screen()->add_help_tab(
	array(
		'id'      => 'overview',
		'title'   => __( 'Writing Settings' ),
		'content' => '<p>' . __( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' ) . '</p>',
	)
);

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
	'<p>' . __( '<a href="https://wordpress.org/support/forums/">Support forums</a>' ) . '</p>'
);

wp_enqueue_script( 'user-profile' );

require_once ABSPATH . 'wp-admin/admin-header.php';
?>

<div class="wrap">
<h1><?php echo esc_html( $title ); ?></h1>

<form method="post" action="options.php">
<?php settings_fields( 'writing' ); ?>

<?php /*****************************************/ ?>
<?php
if ( get_option( 'link_manager_enabled' ) ) :
?>
	<input type="hidden" name="default_link_category" id="default_link_category" value="<?php echo get_option( 'default_link_category' );?>" />
<?php endif; ?>

<input name="mailserver_url" type="hidden" id="mailserver_url" value="<?php form_option( 'mailserver_url' ); ?>" />
<input name="mailserver_port" type="hidden" id="mailserver_port" value="<?php form_option( 'mailserver_port' ); ?>" />
<input name="mailserver_login" type="hidden" id="mailserver_login" value="<?php form_option( 'mailserver_login' ); ?>" />
<input name="mailserver_pass" type="hidden" id="mailserver_pass" value="<?php echo esc_attr( get_option( 'mailserver_pass' ) ); ?>" />
<input name="default_email_category" type="hidden" id="default_email_category" value="<?php echo get_option( 'default_email_category' ); ?>" />

<?php
if ( apply_filters( 'enable_update_services_configuration', true ) ) {
	if ( 1 == get_option( 'blog_public' ) ) :
?>
		<input type="hidden" name="ping_sites" id="ping_sites" value="<?php echo get_option( 'ping_sites' ); ?>" />
<?php
	endif;
} ?>
<?php /*****************************************/ ?>

<table class="form-table" role="presentation">
<?php if ( get_site_option( 'initial_db_version' ) < 32453 ) : ?>
<tr>
<th scope="row"><?php _e( 'Formatting' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span>
	<?php
	/* translators: Hidden accessibility text. */
	_e( 'Formatting' );
	?>
</span></legend>
<label for="use_smilies">
<input name="use_smilies" type="checkbox" id="use_smilies" value="1" <?php checked( '1', get_option( 'use_smilies' ) ); ?> />
	<?php _e( 'Convert emoticons like <code>:-)</code> and <code>:-P</code> to graphics on display' ); ?></label><br />
<label for="use_balanceTags"><input name="use_balanceTags" type="checkbox" id="use_balanceTags" value="1" <?php checked( '1', get_option( 'use_balanceTags' ) ); ?> /> <?php _e( 'WordPress should correct invalidly nested XHTML automatically' ); ?></label>
</fieldset></td>
</tr>
<?php endif; ?>
<tr>
<th scope="row"><label for="default_category"><?php _e( 'Default Post Category' ); ?></label></th>
<td>
<?php
wp_dropdown_categories(
	array(
		'hide_empty'   => 0,
		'name'         => 'default_category',
		'orderby'      => 'name',
		'selected'     => get_option( 'default_category' ),
		'hierarchical' => true,
	)
);
?>
</td>
</tr>
<?php
$post_formats = get_post_format_strings();
unset( $post_formats['standard'] );
?>
<tr>
<th scope="row"><label for="default_post_format"><?php _e( 'Default Post Format' ); ?></label></th>
<td>
	<select name="default_post_format" id="default_post_format">
		<option value="0"><?php echo get_post_format_string( 'standard' ); ?></option>
<?php foreach ( $post_formats as $format_slug => $format_name ) : ?>
		<option<?php selected( get_option( 'default_post_format' ), $format_slug ); ?> value="<?php echo esc_attr( $format_slug ); ?>"><?php echo esc_html( $format_name ); ?></option>
<?php endforeach; ?>
	</select>
</td>
</tr>

<?php
do_settings_fields( 'writing', 'default' );
do_settings_fields( 'writing', 'remote_publishing' ); // A deprecated section.
?>
</table>


<?php do_settings_sections( 'writing' ); ?>

<?php submit_button(); ?>
</form>
</div>

<?php require_once ABSPATH . 'wp-admin/admin-footer.php'; ?>
