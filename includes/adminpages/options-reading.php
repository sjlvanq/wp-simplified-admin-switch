<?php
/**
 * Reading settings administration panel.
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
$submenu_file = "options-reading.php";

// Used in the HTML title tag.
$title       = __( 'Reading Settings' );
$parent_file = 'options-general.php';

add_action( 'admin_head', 'options_reading_add_js' );

get_current_screen()->add_help_tab(
	array(
		'id'      => 'overview',
		'title'   => __( 'Reading Settings' ),
		'content' => '<p>' . __( 'This screen contains the settings that affect the display of your content.' ) . '</p>' .
			'<p>' . sprintf(
				/* translators: %s: URL to create a new page. */
				__( 'You can choose what&#8217;s displayed on the homepage of your site. It can be posts in reverse chronological order (classic blog), or a fixed/static page. To set a static homepage, you first need to create two <a href="%s">Pages</a>. One will become the homepage, and the other will be where your posts are displayed.' ),
				'post-new.php?post_type=page'
			) . '</p>' .
			'<p>' . __( 'You must click the Save Changes button at the bottom of the screen for new settings to take effect.' ) . '</p>',
	)
);

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
	'<p>' . __( '<a href="https://wordpress.org/documentation/article/settings-reading-screen/">Documentation on Reading Settings</a>' ) . '</p>' .
	'<p>' . __( '<a href="https://wordpress.org/support/forums/">Support forums</a>' ) . '</p>'
);

require_once ABSPATH . 'wp-admin/admin-header.php';
?>

<div class="wrap">
<h1><?php echo esc_html( $title ); ?></h1>

<form method="post" action="options.php">
<input type="hidden" name="posts_per_rss" id="posts_per_rss" value="<?php echo get_option( 'posts_per_rss' );?>" />
<input type="hidden" name="rss_use_excerpt" id="rss_use_excerpt" value="<?php echo get_option( 'rss_use_excerpt' ); ?>" />
<input type="hidden" name="blog_public" id="blog_public" value="<?php echo get_option( 'blog_public' ); ?>" />
<?php /*****************************************/ ?>

<?php
settings_fields( 'reading' );

if ( ! in_array( get_option( 'blog_charset' ), array( 'utf8', 'utf-8', 'UTF8', 'UTF-8' ), true ) ) {
	add_settings_field( 'blog_charset', __( 'Encoding for pages and feeds' ), 'options_reading_blog_charset', 'reading', 'default', array( 'label_for' => 'blog_charset' ) );
}
?>

<?php if ( ! get_pages() ) : ?>
<input name="show_on_front" type="hidden" value="posts" />
<table class="form-table" role="presentation">
	<?php
	if ( 'posts' !== get_option( 'show_on_front' ) ) :
		update_option( 'show_on_front', 'posts' );
	endif;

else :
	if ( 'page' === get_option( 'show_on_front' ) && ! get_option( 'page_on_front' ) && ! get_option( 'page_for_posts' ) ) {
		update_option( 'show_on_front', 'posts' );
	}
	?>
<table class="form-table" role="presentation">
<tr>
<th scope="row"><?php _e( 'Your homepage displays' ); ?></th>
<td id="front-static-pages"><fieldset>
	<legend class="screen-reader-text"><span>
		<?php
		/* translators: Hidden accessibility text. */
		_e( 'Your homepage displays' );
		?>
	</span></legend>
	<p><label>
		<input name="show_on_front" type="radio" value="posts" class="tog" <?php checked( 'posts', get_option( 'show_on_front' ) ); ?> />
		<?php _e( 'Your latest posts' ); ?>
	</label>
	</p>
	<p><label>
		<input name="show_on_front" type="radio" value="page" class="tog" <?php checked( 'page', get_option( 'show_on_front' ) ); ?> />
		<?php
		printf(
			/* translators: %s: URL to Pages screen. */
			__( 'A <a href="%s">static page</a> (select below)' ),
			'edit.php?post_type=page'
		);
		?>
	</label>
	</p>
<ul>
	<li><label for="page_on_front">
	<?php
	printf(
		/* translators: %s: Select field to choose the front page. */
		__( 'Homepage: %s' ),
		wp_dropdown_pages(
			array(
				'name'              => 'page_on_front',
				'echo'              => 0,
				'show_option_none'  => __( '&mdash; Select &mdash;' ),
				'option_none_value' => '0',
				'selected'          => get_option( 'page_on_front' ),
			)
		)
	);
	?>
</label></li>
	<li><label for="page_for_posts">
	<?php
	printf(
		/* translators: %s: Select field to choose the page for posts. */
		__( 'Posts page: %s' ),
		wp_dropdown_pages(
			array(
				'name'              => 'page_for_posts',
				'echo'              => 0,
				'show_option_none'  => __( '&mdash; Select &mdash;' ),
				'option_none_value' => '0',
				'selected'          => get_option( 'page_for_posts' ),
			)
		)
	);
	?>
</label></li>
</ul>
	<?php
	if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) === get_option( 'page_on_front' ) ) :
		wp_admin_notice(
			__( '<strong>Warning:</strong> these pages should not be the same!' ),
			array(
				'type'               => 'warning',
				'id'                 => 'front-page-warning',
				'additional_classes' => array( 'inline' ),
			)
		);
	endif;
	if ( get_option( 'wp_page_for_privacy_policy' ) === get_option( 'page_for_posts' ) || get_option( 'wp_page_for_privacy_policy' ) === get_option( 'page_on_front' ) ) :
		wp_admin_notice(
			__( '<strong>Warning:</strong> these pages should not be the same as your Privacy Policy page!' ),
			array(
				'type'               => 'warning',
				'id'                 => 'privacy-policy-page-warning',
				'additional_classes' => array( 'inline' ),
			)
		);
	endif;
	?>
</fieldset></td>
</tr>
<?php endif; ?>
<tr>
<th scope="row"><label for="posts_per_page"><?php _e( 'Blog pages show at most' ); ?></label></th>
<td>
<input name="posts_per_page" type="number" step="1" min="1" id="posts_per_page" value="<?php form_option( 'posts_per_page' ); ?>" class="small-text" /> <?php _e( 'posts' ); ?>
</td>
</tr>

<?php do_settings_fields( 'reading', 'default' ); ?>
</table>

<?php do_settings_sections( 'reading' ); ?>

<?php submit_button(); ?>
</form>
</div>
<?php require_once ABSPATH . 'wp-admin/admin-footer.php'; ?>
