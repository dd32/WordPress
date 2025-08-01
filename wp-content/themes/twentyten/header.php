<?php
/**
 * Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>
<?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the site name.
	bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) ) {
	echo " | $site_description";
}

	// Add a page number if necessary:
if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
	/* translators: %s: Page number. */
	echo esc_html( ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) ) );
}

?>
	</title>
<link rel="profile" href="https://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo esc_url( get_stylesheet_uri() ); ?>?ver=20250415" />
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
<?php
	/*
	 * We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
if ( is_singular() && get_option( 'thread_comments' ) ) {
	wp_enqueue_script( 'comment-reply' );
}

	/*
	 * Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="wrapper" class="hfeed">
	<?php // Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. ?>
	<a href="#content" class="screen-reader-text skip-link"><?php _e( 'Skip to content', 'twentyten' ); ?></a>
	<div id="header">
		<div id="masthead">
			<div id="branding" role="banner">
				<?php
				$heading_tag      = ( is_home() || is_front_page() ) ? 'h1' : 'div';
				$is_front         = ! is_paged() && ( is_front_page() || ( is_home() && ( (int) get_option( 'page_for_posts' ) !== get_queried_object_id() ) ) );
				$site_name        = get_bloginfo( 'name', 'display' );
				$site_description = get_bloginfo( 'description', 'display' );

				if ( $site_name ) :
					?>
					<<?php echo $heading_tag; ?> id="site-title">
						<span>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" <?php echo $is_front ? 'aria-current="page"' : ''; ?>><?php echo $site_name; ?></a>
						</span>
					</<?php echo $heading_tag; ?>>
					<?php
				endif;

				if ( $site_description ) :
					?>
					<div id="site-description"><?php echo $site_description; ?></div>
					<?php
				endif;

				// Compatibility with versions of WordPress prior to 3.4.
				if ( function_exists( 'get_custom_header' ) ) {
					/*
					 * We need to figure out what the minimum width should be for our featured image.
					 * This result would be the suggested width if the theme were to implement flexible widths.
					 */
					$header_image_width = get_theme_support( 'custom-header', 'width' );
				} else {
					$header_image_width = HEADER_IMAGE_WIDTH;
				}

				// Check if this is a post or page, if it has a thumbnail, and if it's a big one.
				$image = false;
				if ( is_singular() && has_post_thumbnail( $post->ID ) ) {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( $header_image_width, $header_image_width ) );
				}
				if ( $image && $image[1] >= $header_image_width ) {
					// Houston, we have a new header image!
					echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );
				} else {
					twentyten_header_image();
				} // End check for featured image or standard header.
				?>
			</div><!-- #branding -->

			<div id="access" role="navigation">
				<?php
				/*
				 * Our navigation menu. If one isn't filled out, wp_nav_menu() falls back to wp_page_menu().
				 * The menu assigned to the primary location is the one used.
				 * If one isn't assigned, the menu with the lowest ID is used.
				 */
				wp_nav_menu(
					array(
						'container_class' => 'menu-header',
						'theme_location'  => 'primary',
					)
				);
				?>
			</div><!-- #access -->
		</div><!-- #masthead -->
	</div><!-- #header -->

	<div id="main">
