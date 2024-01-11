<?php
/**
 * Discussion settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */
/** WordPress Administration Bootstrap */
//require_once __DIR__ . '/admin.php';
require_once ABSPATH . 'wp-admin/admin.php';

global $current_user;
$user_email = $current_user->user_email;

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
}

// Resaltado en menú
global $submenu_file;
$submenu_file = "options-discussion.php";

// Used in the HTML title tag.
$title       = __( 'Discussion Settings' );
$parent_file = 'options-general.php';

add_action( 'admin_print_footer_scripts', 'options_discussion_add_js' );

get_current_screen()->add_help_tab(
	array(
		'id'      => 'overview',
		'title'   => __( 'Overview' ),
		'content' => '<p>' . __( 'This screen provides many options for controlling the management and display of comments and links to your posts/pages. So many, in fact, they will not all fit here! :) Use the documentation links to get information on what each discussion setting does.' ) . '</p>' .
			'<p>' . __( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' ) . '</p>',
	)
);

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
	'<p>' . __( '<a href="https://wordpress.org/documentation/article/settings-discussion-screen/">Documentation on Discussion Settings</a>' ) . '</p>' .
	'<p>' . __( '<a href="https://wordpress.org/support/forums/">Support forums</a>' ) . '</p>'
);

require_once ABSPATH . 'wp-admin/admin-header.php';
?>

<div class="wrap">
<h1><?php echo esc_html( $title ); ?></h1>

<form method="post" action="options.php">
<?php settings_fields( 'discussion' ); ?>

<input type="hidden" name="default_pingback_flag" id="default_pingback_flag" value="<?php echo get_option( 'default_pingback_flag' ); ?>" />
<input type="hidden" name="default_ping_status" id="default_ping_status" value="<?php echo get_option( 'default_ping_status' ); ?>" />
<input type="hidden" name="default_comment_status" id="default_comment_status" value="<?php echo get_option( 'default_comment_status' ); ?>" />
<input type="hidden" name="require_name_email" id="require_name_email" value="1" <?php echo get_option( 'require_name_email' ); ?> />
<input type="hidden" name="comment_registration" id="comment_registration" value="<?php echo get_option( 'comment_registration' ); ?>" />
<?php /*<input type="hidden" name="close_comments_for_old_posts" id="close_comments_for_old_posts" value="<?php echo get_option( 'close_comments_for_old_posts' ); ?>" />*/?>
<input type="hidden" name="show_comments_cookies_opt_in" id="show_comments_cookies_opt_in" value="<?php echo get_option( 'show_comments_cookies_opt_in' ); ?>" />
<input type="hidden" name="disallowed_keys" id="disallowed_keys" value="<?php echo esc_textarea(get_option( 'disallowed_keys' )); ?>" />
<input type="hidden" name="thread_comments" id="thread_comments" value="<?php echo get_option( 'thread_comments' ); ?>" />
<input type="hidden" name="thread_comments_depth" id="thread_comments_depth" value="<?php echo get_option( 'thread_comments_depth' );?>" />
<input type="hidden" name="page_comments" id="page_comments" value="<?php echo get_option( 'page_comments' );?>" />
<input type="hidden" name="comments_per_page" id="comments_per_page" value="<?php echo get_option( 'comments_per_page' );?>" />
<input type="hidden" name="default_comments_page" id="default_comments_page" value="<?php echo get_option( 'default_comments_page' );?>" />
<?php /*<input type="hidden" name="show_avatars" id="show_avatars" value="<?php echo get_option( 'show_avatars' ); ?>" />*/?>
<input type="hidden" name="comments_notify" id="comments_notify" value="<?php echo get_option( 'comments_notify' );?>" />
<input type="hidden" name="moderation_notify" id="moderation_notify" value="<?php echo get_option( 'moderation_notify' );?>" />
<input type="hidden" name="comment_moderation" id="comment_moderation" value="<?php echo get_option( 'comment_moderation' );?>" />
<input type="hidden" name="comment_previously_approved" id="comment_previously_approved" value="<?php echo get_option( 'comment_previously_approved' );?>" />
<input type="hidden" name="comment_max_links" id="comment_max_links" value="<?php echo get_option( 'comment_max_links' );?>" />
<input type="hidden" name="moderation_keys" id="moderation_keys" value="<?php echo get_option( 'moderation_keys' );?>" />
<input type="hidden" name="disallowed_keys" id="disallowed_keys" value="<?php echo get_option( 'disallowed_keys' );?>" />
<input type="hidden" name="avatar_rating" id="avatar_rating" value="<?php echo get_option( 'avatar_rating' ); ?>" />



<table class="form-table" role="presentation">
<tr>
<th scope="row"><?php _e( 'Default post settings' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span>
	<?php
	/* translators: Hidden accessibility text. */
	_e( 'Default post settings' );
	?>
</span></legend>
<label for="default_comment_status">
<input name="default_comment_status" type="checkbox" id="default_comment_status" value="open" <?php checked( 'open', get_option( 'default_comment_status' ) ); ?> />
<?php _e( 'Allow people to submit comments on new posts' ); ?></label>
</fieldset></td>
</tr>
<tr>
<th scope="row"><?php _e( 'Other comment settings' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span>
	<?php
	/* translators: Hidden accessibility text. */
	_e( 'Other comment settings' );
	?>
</span></legend>
<label for="close_comments_for_old_posts">
<input name="close_comments_for_old_posts" type="checkbox" id="close_comments_for_old_posts" value="1" <?php checked( '1', get_option( 'close_comments_for_old_posts' ) ); ?> />
<?php
printf(
	/* translators: %s: Number of days. */
	__( 'Automatically close comments on posts older than %s days' ),
	'</label> <label for="close_comments_days_old"><input name="close_comments_days_old" type="number" min="0" step="1" id="close_comments_days_old" value="' . esc_attr( get_option( 'close_comments_days_old' ) ) . '" class="small-text" />'
);
?>
</label>
<br />
<label for="comment_order">
<?php

$comment_order = '<select name="comment_order" id="comment_order"><option value="asc"';
if ( 'asc' === get_option( 'comment_order' ) ) {
	$comment_order .= ' selected="selected"';
}
$comment_order .= '>' . __( 'older' ) . '</option><option value="desc"';
if ( 'desc' === get_option( 'comment_order' ) ) {
	$comment_order .= ' selected="selected"';
}
$comment_order .= '>' . __( 'newer' ) . '</option></select>';

/* translators: %s: Form field control for 'older' or 'newer' comments. */
printf( __( 'Comments should be displayed with the %s comments at the top of each page' ), $comment_order );

?>
</label>
</fieldset></td>
</tr>
<?php do_settings_fields( 'discussion', 'default' ); ?>
</table>

<h2 class="title"><?php _e( 'Avatars' ); ?></h2>

<p><?php _e( 'An avatar is an image that can be associated with a user across multiple websites. In this area, you can choose to display avatars of users who interact with the site.' ); ?></p>

<?php
// The above would be a good place to link to the documentation on the Gravatar functions, for putting it in themes. Anything like that?

$show_avatars       = get_option( 'show_avatars' );
$show_avatars_class = '';
if ( ! $show_avatars ) {
	$show_avatars_class = ' hide-if-js';
}
?>

<table class="form-table" role="presentation">
<tr>
<th scope="row"><?php _e( 'Avatar Display' ); ?></th>
<td>
	<label for="show_avatars">
		<input type="checkbox" id="show_avatars" name="show_avatars" value="1" <?php checked( $show_avatars, 1 ); ?> />
		<?php _e( 'Show Avatars' ); ?>
	</label>
</td>
</tr>
<tr class="avatar-settings<?php echo $show_avatars_class; ?>">
<th scope="row"><?php _e( 'Default Avatar' ); ?></th>
<td class="defaultavatarpicker"><fieldset><legend class="screen-reader-text"><span>
	<?php
	/* translators: Hidden accessibility text. */
	_e( 'Default Avatar' );
	?>
</span></legend>

<p>
<?php _e( 'For users without a custom avatar of their own, you can either display a generic logo or a generated one based on their email address.' ); ?><br />
</p>

<?php
$avatar_defaults = array(
	'mystery'          => __( 'Mystery Person' ),
	'identicon'        => __( 'Identicon (Generated)' ),
	'wavatar'          => __( 'Wavatar (Generated)' ),
	'monsterid'        => __( 'MonsterID (Generated)' ),
	'retro'            => __( 'Retro (Generated)' ),
	'robohash'         => __( 'RoboHash (Generated)' ),
);
/**
 * Filters the default avatars.
 *
 * Avatars are stored in key/value pairs, where the key is option value,
 * and the name is the displayed avatar name.
 *
 * @since 2.6.0
 *
 * @param string[] $avatar_defaults Associative array of default avatars.
 */
$avatar_defaults = apply_filters( 'avatar_defaults', $avatar_defaults );
$default         = get_option( 'avatar_default', 'mystery' );
$avatar_list     = '';

// Force avatars on to display these choices.
add_filter( 'pre_option_show_avatars', '__return_true', 100 );

session_start();
foreach ( $avatar_defaults as $default_key => $default_name ) {
	$selected     = ( $default === $default_key ) ? 'checked="checked" ' : '';
	$avatar_list .= "\n\t<label><input type='radio' name='avatar_default' id='avatar_{$default_key}' value='" . esc_attr( $default_key ) . "' {$selected}/> ";
	$avatar_list .= get_avatar( $user_email, 32, $default_key, '', array( 'force_default' => true ) );
	$avatar_list .= ' ' . $default_name . '</label>';
	$avatar_list .= '<br />';
}

remove_filter( 'pre_option_show_avatars', '__return_true', 100 );

/**
 * Filters the HTML output of the default avatar list.
 *
 * @since 2.6.0
 *
 * @param string $avatar_list HTML markup of the avatar list.
 */
echo apply_filters( 'default_avatar_select', $avatar_list );
?>

</fieldset></td>
</tr>
<?php do_settings_fields( 'discussion', 'avatars' ); ?>
</table>

<?php do_settings_sections( 'discussion' ); ?>

<?php submit_button(); ?>
</form>
</div>

<?php require_once ABSPATH . 'wp-admin/admin-footer.php'; ?>
