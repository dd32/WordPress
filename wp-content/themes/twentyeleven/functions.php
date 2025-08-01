<?php
/**
 * Twenty Eleven functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyeleven_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @link https://developer.wordpress.org/themes/advanced-topics/child-themes/
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value).
 *     remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see https://developer.wordpress.org/plugins/.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = 584;
}

/*
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'twentyeleven_setup' );

if ( ! function_exists( 'twentyeleven_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
	 * functions.php file.
	 *
	 * @uses load_theme_textdomain()    For translation/localization support.
	 * @uses add_editor_style()         To style the visual editor.
	 * @uses add_theme_support()        To add support for post thumbnails, automatic feed links, custom headers
	 *                                  and backgrounds, and post formats.
	 * @uses register_nav_menus()       To add support for navigation menus.
	 * @uses register_default_headers() To register the default custom header images provided with the theme.
	 * @uses set_post_thumbnail_size()  To set a custom post thumbnail size.
	 *
	 * @since Twenty Eleven 1.0
	 */
	function twentyeleven_setup() {

		/*
		 * Make Twenty Eleven available for translation.
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on Twenty Eleven, use
		 * a find and replace to change 'twentyeleven' to the name
		 * of your theme in all the template files.
		 *
		 * Manual loading of text domain is not required after the introduction of
		 * just in time translation loading in WordPress version 4.6.
		 *
		 * @ticket 58318
		 */
		if ( version_compare( $GLOBALS['wp_version'], '4.6', '<' ) ) {
			load_theme_textdomain( 'twentyeleven', get_template_directory() . '/languages' );
		}

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Load regular editor styles into the new block-based editor.
		add_theme_support( 'editor-styles' );

		// Load default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add support for custom color scheme.
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Blue', 'twentyeleven' ),
					'slug'  => 'blue',
					'color' => '#1982d1',
				),
				array(
					'name'  => __( 'Black', 'twentyeleven' ),
					'slug'  => 'black',
					'color' => '#000',
				),
				array(
					'name'  => __( 'Dark Gray', 'twentyeleven' ),
					'slug'  => 'dark-gray',
					'color' => '#373737',
				),
				array(
					'name'  => __( 'Medium Gray', 'twentyeleven' ),
					'slug'  => 'medium-gray',
					'color' => '#666',
				),
				array(
					'name'  => __( 'Light Gray', 'twentyeleven' ),
					'slug'  => 'light-gray',
					'color' => '#e2e2e2',
				),
				array(
					'name'  => __( 'White', 'twentyeleven' ),
					'slug'  => 'white',
					'color' => '#fff',
				),
			)
		);

		// Load up our theme options page and related code.
		require get_template_directory() . '/inc/theme-options.php';

		// Grab Twenty Eleven's Ephemera widget.
		require get_template_directory() . '/inc/widgets.php';

		// Load block patterns.
		require get_template_directory() . '/inc/block-patterns.php';

		// Add default posts and comments RSS feed links to <head>.
		add_theme_support( 'automatic-feed-links' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menu( 'primary', __( 'Primary Menu', 'twentyeleven' ) );

		// Add support for a variety of post formats.
		add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

		$theme_options = twentyeleven_get_theme_options();
		if ( 'dark' === $theme_options['color_scheme'] ) {
			$default_background_color = '1d1d1d';
		} else {
			$default_background_color = 'e2e2e2';
		}

		// Add support for custom backgrounds.
		add_theme_support(
			'custom-background',
			array(
				/*
				* Let WordPress know what our default background color is.
				* This is dependent on our current color scheme.
				*/
				'default-color' => $default_background_color,
			)
		);

		// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images.
		add_theme_support( 'post-thumbnails' );

		// Add support for custom headers.
		$custom_header_support = array(
			// The default header text color.
			'default-text-color'     => '000',
			// The height and width of our custom header.
			/**
			 * Filters the Twenty Eleven default header image width.
			 *
			 * @since Twenty Eleven 1.0
			 *
			 * @param int The default header image width in pixels. Default 1000.
			 */
			'width'                  => apply_filters( 'twentyeleven_header_image_width', 1000 ),
			/**
			 * Filters the Twenty Eleven default header image height.
			 *
			 * @since Twenty Eleven 1.0
			 *
			 * @param int The default header image height in pixels. Default 288.
			 */
			'height'                 => apply_filters( 'twentyeleven_header_image_height', 288 ),
			// Support flexible heights.
			'flex-height'            => true,
			// Random image rotation by default.
			'random-default'         => true,
			// Callback for styling the header.
			'wp-head-callback'       => 'twentyeleven_header_style',
			// Callback for styling the header preview in the admin.
			'admin-head-callback'    => 'twentyeleven_admin_header_style',
			// Callback used to display the header preview in the admin.
			'admin-preview-callback' => 'twentyeleven_admin_header_image',
		);

		add_theme_support( 'custom-header', $custom_header_support );

		if ( ! function_exists( 'get_custom_header' ) ) {
			// This is all for compatibility with versions of WordPress prior to 3.4.
			define( 'HEADER_TEXTCOLOR', $custom_header_support['default-text-color'] );
			define( 'HEADER_IMAGE', '' );
			define( 'HEADER_IMAGE_WIDTH', $custom_header_support['width'] );
			define( 'HEADER_IMAGE_HEIGHT', $custom_header_support['height'] );
			add_custom_image_header( $custom_header_support['wp-head-callback'], $custom_header_support['admin-head-callback'], $custom_header_support['admin-preview-callback'] );
			add_custom_background();
		}

		/*
		 * We'll be using post thumbnails for custom header images on posts and pages.
		 * We want them to be the size of the header image that we just defined.
		 * Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
		 */
		set_post_thumbnail_size( $custom_header_support['width'], $custom_header_support['height'], true );

		/*
		 * Add Twenty Eleven's custom image sizes.
		 * Used for large feature (header) images.
		 */
		add_image_size( 'large-feature', $custom_header_support['width'], $custom_header_support['height'], true );
		// Used for featured posts if a large-feature doesn't exist.
		add_image_size( 'small-feature', 500, 300 );

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers(
			array(
				'wheel'      => array(
					'url'           => '%s/images/headers/wheel.jpg',
					'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Wheel', 'twentyeleven' ),
				),
				'shore'      => array(
					'url'           => '%s/images/headers/shore.jpg',
					'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Shore', 'twentyeleven' ),
				),
				'trolley'    => array(
					'url'           => '%s/images/headers/trolley.jpg',
					'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Trolley', 'twentyeleven' ),
				),
				'pine-cone'  => array(
					'url'           => '%s/images/headers/pine-cone.jpg',
					'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Pine Cone', 'twentyeleven' ),
				),
				'chessboard' => array(
					'url'           => '%s/images/headers/chessboard.jpg',
					'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Chessboard', 'twentyeleven' ),
				),
				'lanterns'   => array(
					'url'           => '%s/images/headers/lanterns.jpg',
					'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Lanterns', 'twentyeleven' ),
				),
				'willow'     => array(
					'url'           => '%s/images/headers/willow.jpg',
					'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Willow', 'twentyeleven' ),
				),
				'hanoi'      => array(
					'url'           => '%s/images/headers/hanoi.jpg',
					'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
					/* translators: Header image description. */
					'description'   => __( 'Hanoi Plant', 'twentyeleven' ),
				),
			)
		);

		// Indicate widget sidebars can use selective refresh in the Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
endif; // twentyeleven_setup()

/**
 * Enqueues scripts and styles for front end.
 *
 * @since Twenty Eleven 2.9
 */
function twentyeleven_scripts_styles() {
	// Theme block stylesheet.
	wp_enqueue_style( 'twentyeleven-block-style', get_template_directory_uri() . '/blocks.css', array(), '20240703' );
}
add_action( 'wp_enqueue_scripts', 'twentyeleven_scripts_styles' );

/**
 * Enqueues styles for the block-based editor.
 *
 * @since Twenty Eleven 2.9
 */
function twentyeleven_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'twentyeleven-block-editor-style', get_template_directory_uri() . '/editor-blocks.css', array(), '20240716' );
}
add_action( 'enqueue_block_editor_assets', 'twentyeleven_block_editor_styles' );

if ( ! function_exists( 'twentyeleven_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @since Twenty Eleven 1.0
	 */
	function twentyeleven_header_style() {
		$text_color = get_header_textcolor();

		// If no custom options for text are set, let's bail.
		if ( HEADER_TEXTCOLOR === $text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css" id="twentyeleven-header-css">
		<?php
		// Has the text been hidden?
		if ( 'blank' === $text_color ) :
			?>
		#site-title,
		#site-description {
			position: absolute;
			clip-path: inset(50%);
		}
			<?php
			// If the user has set a custom color for the text, use that.
		else :
			?>
		#site-title a,
		#site-description {
			color: #<?php echo $text_color; ?>;
		}
	<?php endif; ?>
	</style>
		<?php
	}
endif; // twentyeleven_header_style()

if ( ! function_exists( 'twentyeleven_admin_header_style' ) ) :
	/**
	 * Styles the header image displayed on the Appearance > Header admin panel.
	 *
	 * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
	 *
	 * @since Twenty Eleven 1.0
	 */
	function twentyeleven_admin_header_style() {
		?>
	<style type="text/css" id="twentyeleven-admin-header-css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
		<?php
		// If the user has set a custom color for the text, use that.
		if ( get_header_textcolor() !== HEADER_TEXTCOLOR ) :
			?>
	#site-title a,
	#site-description {
		color: #<?php echo get_header_textcolor(); ?>;
	}
	<?php endif; ?>
	#headimg img {
		max-width: 1000px;
		height: auto;
		width: 100%;
	}
	</style>
		<?php
	}
endif; // twentyeleven_admin_header_style()

if ( ! function_exists( 'twentyeleven_admin_header_image' ) ) :
	/**
	 * Displays custom header image markup on the Appearance > Header admin panel.
	 *
	 * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
	 *
	 * @since Twenty Eleven 1.0
	 */
	function twentyeleven_admin_header_image() {

		?>
		<div id="headimg">
			<?php
			$color = get_header_textcolor();
			$image = get_header_image();
			$style = 'display: none;';
			if ( $color && 'blank' !== $color ) {
				$style = 'color: #' . $color . ';';
			}
			?>
			<h1 class="displaying-header-text"><a id="name" style="<?php echo esc_attr( $style ); ?>" onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>" tabindex="-1"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc" class="displaying-header-text" style="<?php echo esc_attr( $style ); ?>"><?php bloginfo( 'description' ); ?></div>
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" />
		<?php endif; ?>
		</div>
		<?php
	}
endif; // twentyeleven_admin_header_image()


if ( ! function_exists( 'twentyeleven_header_image' ) ) :
	/**
	 * Displays custom header image markup.
	 *
	 * @since Twenty Eleven 4.5
	 */
	function twentyeleven_header_image() {
		$attrs = array(
			'alt' => get_bloginfo( 'name', 'display' ),
		);

		// Compatibility with versions of WordPress prior to 3.4.
		if ( function_exists( 'get_custom_header' ) ) {
			$custom_header   = get_custom_header();
			$attrs['width']  = $custom_header->width;
			$attrs['height'] = $custom_header->height;
		} else {
			$attrs['width']  = HEADER_IMAGE_WIDTH;
			$attrs['height'] = HEADER_IMAGE_HEIGHT;
		}

		if ( function_exists( 'the_header_image_tag' ) ) {
			the_header_image_tag( $attrs );
			return;
		}

		?>
		<img src="<?php header_image(); ?>" width="<?php echo esc_attr( $attrs['width'] ); ?>" height="<?php echo esc_attr( $attrs['height'] ); ?>" alt="<?php echo esc_attr( $attrs['alt'] ); ?>" />
		<?php
	}
endif; // twentyeleven_header_image()

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove
 * the filter and add your own function tied to
 * the excerpt_length filter hook.
 *
 * @since Twenty Eleven 1.0
 *
 * @param int $length The number of excerpt characters.
 * @return int The filtered number of characters.
 */
function twentyeleven_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );

if ( ! function_exists( 'twentyeleven_continue_reading_link' ) ) :
	/**
	 * Returns a "Continue Reading" link for excerpts.
	 *
	 * @since Twenty Eleven 1.0
	 *
	 * @return string The "Continue Reading" HTML link.
	 */
	function twentyeleven_continue_reading_link() {
		return ' <a href="' . esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) . '</a>';
	}
endif; // twentyeleven_continue_reading_link()

/**
 * Replaces "[...]" in the Read More link with an ellipsis.
 *
 * The "[...]" is appended to automatically generated excerpts.
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Eleven 1.0
 *
 * @param string $more The Read More text.
 * @return string The filtered Read More text.
 */
function twentyeleven_auto_excerpt_more( $more ) {
	if ( ! is_admin() ) {
		return ' &hellip;' . twentyeleven_continue_reading_link();
	}
	return $more;
}
add_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Eleven 1.0
 *
 * @param string $output The "Continue Reading" link.
 * @return string The filtered "Continue Reading" link.
 */
function twentyeleven_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() && ! is_admin() ) {
		$output .= twentyeleven_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );

/**
 * Shows a home link for the wp_nav_menu() fallback, wp_page_menu().
 *
 * @since Twenty Eleven 1.0
 *
 * @param array $args The page menu arguments. @see wp_page_menu()
 * @return array The filtered page menu arguments.
 */
function twentyeleven_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) ) {
		$args['show_home'] = true;
	}
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyeleven_page_menu_args' );

/**
 * Registers sidebars and widgetized areas.
 *
 * Also register the default Ephemera widget.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_widgets_init() {

	register_widget( 'Twenty_Eleven_Ephemera_Widget' );

	register_sidebar(
		array(
			'name'          => __( 'Main Sidebar', 'twentyeleven' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Showcase Sidebar', 'twentyeleven' ),
			'id'            => 'sidebar-2',
			'description'   => __( 'The sidebar for the optional Showcase Template', 'twentyeleven' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Area One', 'twentyeleven' ),
			'id'            => 'sidebar-3',
			'description'   => __( 'An optional widget area for your site footer', 'twentyeleven' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Area Two', 'twentyeleven' ),
			'id'            => 'sidebar-4',
			'description'   => __( 'An optional widget area for your site footer', 'twentyeleven' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Area Three', 'twentyeleven' ),
			'id'            => 'sidebar-5',
			'description'   => __( 'An optional widget area for your site footer', 'twentyeleven' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'twentyeleven_widgets_init' );

if ( ! function_exists( 'twentyeleven_content_nav' ) ) :
	/**
	 * Displays navigation to next/previous pages when applicable.
	 *
	 * @since Twenty Eleven 1.0
	 *
	 * @param string $html_id The HTML id attribute.
	 */
	function twentyeleven_content_nav( $html_id ) {
		global $wp_query;

		if ( $wp_query->max_num_pages > 1 ) :
			?>
			<nav id="<?php echo esc_attr( $html_id ); ?>">
				<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyeleven' ) ); ?></div>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></div>
			</nav><!-- #nav-above -->
			<?php
	endif;
	}
endif; // twentyeleven_content_nav()

/**
 * Returns the first link from the post content. If none found, the
 * post permalink is used as a fallback.
 *
 * @since Twenty Eleven 1.0
 *
 * @uses get_url_in_content() to get the first URL from the post content.
 *
 * @return string The first link.
 */
function twentyeleven_get_first_url() {
	$content = get_the_content();
	$has_url = function_exists( 'get_url_in_content' ) ? get_url_in_content( $content ) : false;

	if ( ! $has_url ) {
		$has_url = twentyeleven_url_grabber();
	}

	/** This filter is documented in wp-includes/link-template.php */
	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Returns the URL for the first link found in the post content.
 *
 * @since Twenty Eleven 1.0
 *
 * @return string|bool URL or false when no link is present.
 */
function twentyeleven_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) ) {
		return false;
	}

	return esc_url_raw( $matches[1] );
}

/**
 * Counts the number of footer sidebars to enable dynamic classes for the footer.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		++$count;
	}

	if ( is_active_sidebar( 'sidebar-4' ) ) {
		++$count;
	}

	if ( is_active_sidebar( 'sidebar-5' ) ) {
		++$count;
	}

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class ) {
		echo 'class="' . esc_attr( $class ) . '"';
	}
}

if ( ! function_exists( 'twentyeleven_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own twentyeleven_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Twenty Eleven 1.0
	 *
	 * @param WP_Comment $comment The comment object.
	 * @param array      $args    An array of comment arguments. @see get_comment_reply_link()
	 * @param int        $depth   The depth of the comment.
	 */
	function twentyeleven_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback':
			case 'trackback':
				?>
		<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
				<?php
				break;
			default:
				?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					$avatar_size = 68;

					if ( '0' !== $comment->comment_parent ) {
						$avatar_size = 39;
					}

					echo get_avatar( $comment, $avatar_size );

					printf(
						/* translators: 1: Comment author, 2: Date and time. */
						__( '%1$s on %2$s <span class="says">said:</span>', 'twentyeleven' ),
						sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
						sprintf(
							'<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
							esc_url( get_comment_link( $comment->comment_ID ) ),
							get_comment_time( 'c' ),
							/* translators: 1: Date, 2: Time. */
							sprintf( __( '%1$s at %2$s', 'twentyeleven' ), get_comment_date(), get_comment_time() )
						)
					);
					?>

					<?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .comment-author .vcard -->

					<?php
					$commenter = wp_get_current_commenter();
					if ( $commenter['comment_author_email'] ) {
						$moderation_note = __( 'Your comment is awaiting moderation.', 'twentyeleven' );
					} else {
						$moderation_note = __( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'twentyeleven' );
					}
					?>

					<?php if ( '0' === $comment->comment_approved ) : ?>
					<em class="comment-awaiting-moderation"><?php echo $moderation_note; ?></em>
					<br />
					<?php endif; ?>

				</footer>

				<div class="comment-content"><?php comment_text(); ?></div>

				<div class="reply">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'reply_text' => __( 'Reply <span>&darr;</span>', 'twentyeleven' ),
								'depth'      => $depth,
								'max_depth'  => $args['max_depth'],
							)
						)
					);
					?>
				</div><!-- .reply -->
			</article><!-- #comment-## -->

				<?php
				break;
		endswitch;
	}
endif; // twentyeleven_comment()

if ( ! function_exists( 'twentyeleven_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * Create your own twentyeleven_posted_on to override in a child theme
	 *
	 * @since Twenty Eleven 1.0
	 */
	function twentyeleven_posted_on() {
		printf(
			/* translators: 1: The permalink, 2: Time, 3: Date and time, 4: Date and time, 5: Author posts, 6: Author post link text, 7: Author display name. */
			__( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven' ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			/* translators: %s: Author display name. */
			esc_attr( sprintf( __( 'View all posts by %s', 'twentyeleven' ), get_the_author() ) ),
			get_the_author()
		);
	}
endif;

/**
 * Adds two classes to the array of body classes.
 *
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Twenty Eleven 1.0
 *
 * @param array $classes Existing body classes.
 * @return array The filtered array of body classes.
 */
function twentyeleven_body_classes( $classes ) {

	if ( function_exists( 'is_multi_author' ) && ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) ) {
		$classes[] = 'singular';
	}

	return $classes;
}
add_filter( 'body_class', 'twentyeleven_body_classes' );

/**
 * Retrieves the IDs for images in a gallery.
 *
 * @uses get_post_galleries() First, if available. Falls back to shortcode parsing,
 *                            then as last option uses a get_posts() call.
 *
 * @since Twenty Eleven 1.6
 *
 * @return array List of image IDs from the post gallery.
 */
function twentyeleven_get_gallery_images() {
	$images = array();

	if ( function_exists( 'get_post_galleries' ) ) {
		$galleries = get_post_galleries( get_the_ID(), false );
		if ( isset( $galleries[0]['ids'] ) ) {
			$images = explode( ',', $galleries[0]['ids'] );
		}
	} else {
		$pattern = get_shortcode_regex();
		preg_match( "/$pattern/s", get_the_content(), $match );
		$atts = shortcode_parse_atts( $match[3] );
		if ( isset( $atts['ids'] ) ) {
			$images = explode( ',', $atts['ids'] );
		}
	}

	if ( ! $images ) {
		$images = get_posts(
			array(
				'fields'         => 'ids',
				'numberposts'    => 999,
				'order'          => 'ASC',
				'orderby'        => 'menu_order',
				'post_mime_type' => 'image',
				'post_parent'    => get_the_ID(),
				'post_type'      => 'attachment',
			)
		);
	}

	return $images;
}

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since Twenty Eleven 2.7
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function twentyeleven_widget_tag_cloud_args( $args ) {
	$args['largest']  = 22;
	$args['smallest'] = 8;
	$args['unit']     = 'pt';
	$args['format']   = 'list';

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentyeleven_widget_tag_cloud_args' );

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Fires the wp_body_open action.
	 *
	 * Added for backward compatibility to support pre-5.2.0 WordPress versions.
	 *
	 * @since Twenty Eleven 3.3
	 */
	function wp_body_open() {
		/**
		 * Triggered after the opening <body> tag.
		 *
		 * @since Twenty Eleven 3.3
		 */
		do_action( 'wp_body_open' );
	}
endif;

/**
 * Includes a skip to content link at the top of the page so that users can bypass the menu.
 *
 * @since Twenty Eleven 3.4
 */
function twentyeleven_skip_link() {
	echo '<div class="skip-link"><a class="assistive-text" href="#content">' . esc_html__( 'Skip to primary content', 'twentyeleven' ) . '</a></div>';
	if ( ! is_singular() ) {
		echo '<div class="skip-link"><a class="assistive-text" href="#secondary">' . esc_html__( 'Skip to secondary content', 'twentyeleven' ) . '</a></div>';
	}
}
add_action( 'wp_body_open', 'twentyeleven_skip_link', 5 );

if ( ! function_exists( 'wp_get_list_item_separator' ) ) :
	/**
	 * Retrieves the list item separator based on the locale.
	 *
	 * Added for backward compatibility to support pre-6.0.0 WordPress versions.
	 *
	 * @since 6.0.0
	 */
	function wp_get_list_item_separator() {
		/* translators: Used between list items, there is a space after the comma. */
		return __( ', ', 'twentyeleven' );
	}
endif;
