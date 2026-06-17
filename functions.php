<?php
/**
 * Theme functions.
 *
 * @package MARIUSZKOWAL_WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ŁADOWANIE BIBLIOTEKI TGMPA */
require_once get_template_directory() . '/inc/tgmpa/class-tgm-plugin-activation.php';

/* REJESTRACJA WYMAGANYCH WTYCZEK TGMPA */
function mariuszkowal_wordpress_register_required_plugins() {
	$plugins = array(
		array(
			'name'     => __( 'MARIUSZKOWAL Portfolio Plugin', 'mariuszkowal-wordpress' ),
			'slug'     => 'mariuszkowal-portfolio-plugin',
			'source'   => get_template_directory() . '/plugins/mariuszkowal-portfolio-plugin.zip',
			'required' => true,
			'version'  => '1.0.0',
		),
		array(
			'name'     => __( 'Advanced Custom Fields Pro', 'mariuszkowal-wordpress' ),
			'slug'     => 'advanced-custom-fields-pro',
			'source'   => get_template_directory() . '/plugins/advanced-custom-fields-pro.zip',
			'required' => true,
		),
		array(
			'name'     => __( 'Contact Form 7', 'mariuszkowal-wordpress' ),
			'slug'     => 'contact-form-7',
			'required' => true,
		),
	);

	$config = array(
		'id'           => 'mariuszkowal-wordpress',
		'menu'         => 'mariuszkowal-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => false,
		'is_automatic' => false,
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'mariuszkowal_wordpress_register_required_plugins' );

/* KONFIGURACJA PODSTAWOWYCH FUNKCJI MOTYWU */
function mariuszkowal_wordpress_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );

	register_nav_menus(
		array(
			'front_page_menu' => __( 'Menu Strona Główna', 'mariuszkowal-wordpress' ),
			'other_pages_menu' => __( 'Menu Inne Strony', 'mariuszkowal-wordpress' ),
		)
	);
}
add_action( 'after_setup_theme', 'mariuszkowal_wordpress_setup' );

/* ŁADOWANIE PLIKÓW CSS I JS MOTYWU */
function mariuszkowal_wordpress_enqueue_assets() {
	$theme_version  = wp_get_theme()->get( 'Version' );
	$style_path     = get_stylesheet_directory() . '/style.css';
	$script_path    = get_theme_file_path( 'assets/js/main.js' );
	$style_version  = file_exists( $style_path ) ? filemtime( $style_path ) : $theme_version;
	$script_version = file_exists( $script_path ) ? filemtime( $script_path ) : $theme_version;
	$script_deps    = array();

	wp_enqueue_style(
		'mariuszkowal-wordpress-font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css',
		array(),
		'7.0.1'
	);

	if ( is_singular( 'project' ) ) {
		wp_enqueue_style(
			'mariuszkowal-wordpress-fancybox',
			'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css',
			array(),
			'5.0.0'
		);

		wp_enqueue_script(
			'mariuszkowal-wordpress-fancybox',
			'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js',
			array(),
			'5.0.0',
			true
		);

		$script_deps[] = 'mariuszkowal-wordpress-fancybox';
	}

	wp_enqueue_style(
		'mariuszkowal-wordpress-style',
		get_stylesheet_uri(),
		array( 'mariuszkowal-wordpress-font-awesome' ),
		$style_version
	);

	wp_enqueue_script(
		'mariuszkowal-wordpress-main',
		get_theme_file_uri( 'assets/js/main.js' ),
		$script_deps,
		$script_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'mariuszkowal_wordpress_enqueue_assets' );

/* REJESTRACJA WIDGETÓW REKLAMOWYCH */
function mariuszkowal_wordpress_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Reklama Portfolio 1', 'mariuszkowal-wordpress' ),
			'id'            => 'portfolio_ad_1',
			'description'   => __( 'Pierwszy widget reklamowy w sidebarze strony Portfolio.', 'mariuszkowal-wordpress' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget sidebar-widget--ad %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Reklama Portfolio 2', 'mariuszkowal-wordpress' ),
			'id'            => 'portfolio_ad_2',
			'description'   => __( 'Drugi widget reklamowy w sidebarze strony Portfolio.', 'mariuszkowal-wordpress' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget sidebar-widget--ad %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Reklama Blog 1', 'mariuszkowal-wordpress' ),
			'id'            => 'blog_ad_1',
			'description'   => __( 'Pierwszy widget reklamowy w sidebarze stron blogowych.', 'mariuszkowal-wordpress' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget sidebar-widget--ad %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Reklama Blog 2', 'mariuszkowal-wordpress' ),
			'id'            => 'blog_ad_2',
			'description'   => __( 'Drugi widget reklamowy w sidebarze stron blogowych.', 'mariuszkowal-wordpress' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget sidebar-widget--ad %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'mariuszkowal_wordpress_widgets_init' );

/* STRONA USTAWIEŃ MOTYWU ACF PRO */
function mariuszkowal_wordpress_acf_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Ustawienia Motywu', 'mariuszkowal-wordpress' ),
			'menu_title' => __( 'Ustawienia Motywu', 'mariuszkowal-wordpress' ),
			'menu_slug'  => 'theme-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
		)
	);
}
add_action( 'acf/init', 'mariuszkowal_wordpress_acf_options_page' );

/* WYŁĄCZENIE ARCHIWUM CPT PROJECT DLA STRONY PORTFOLIO */
function mariuszkowal_wordpress_disable_project_archive( $args, $post_type ) {
	if ( 'project' !== $post_type ) {
		return $args;
	}

	$args['has_archive'] = false;

	return $args;
}
add_filter( 'register_post_type_args', 'mariuszkowal_wordpress_disable_project_archive', 10, 2 );

/* AKTYWNY ODNOŚNIK PORTFOLIO W MENU */
function mariuszkowal_wordpress_is_portfolio_context() {
	return is_page_template( 'portfolio.php' ) || is_singular( 'project' ) || is_tax( 'project-category' );
}

function mariuszkowal_wordpress_is_blog_context() {
	return is_page_template( 'blog.php' ) || is_singular( 'post' ) || is_home() || is_category() || is_tag() || is_search();
}

function mariuszkowal_wordpress_is_named_menu_item( $menu_item, $name ) {
	if ( empty( $menu_item->url ) ) {
		return false;
	}

	$menu_item_path = wp_parse_url( $menu_item->url, PHP_URL_PATH );
	$menu_item_path = trim( (string) $menu_item_path, '/' );
	$menu_item_hash = wp_parse_url( $menu_item->url, PHP_URL_FRAGMENT );
	$menu_item_hash = trim( (string) $menu_item_hash, '#' );

	return $name === basename( $menu_item_path ) || $name === $menu_item_hash;
}

function mariuszkowal_wordpress_nav_menu_css_class( $classes, $menu_item ) {
	$is_portfolio_item = mariuszkowal_wordpress_is_portfolio_context() && mariuszkowal_wordpress_is_named_menu_item( $menu_item, 'portfolio' );
	$is_blog_item      = mariuszkowal_wordpress_is_blog_context() && mariuszkowal_wordpress_is_named_menu_item( $menu_item, 'blog' );

	if ( $is_portfolio_item || $is_blog_item ) {
		$classes[] = 'current-menu-item';
		$classes[] = 'current_page_item';
	}

	return array_unique( $classes );
}
add_filter( 'nav_menu_css_class', 'mariuszkowal_wordpress_nav_menu_css_class', 10, 2 );

function mariuszkowal_wordpress_nav_menu_link_attributes( $atts, $menu_item ) {
	$is_current_menu_item = ! empty( $menu_item->classes ) && in_array( 'current-menu-item', (array) $menu_item->classes, true );
	$is_portfolio_item    = mariuszkowal_wordpress_is_portfolio_context() && mariuszkowal_wordpress_is_named_menu_item( $menu_item, 'portfolio' );
	$is_blog_item         = mariuszkowal_wordpress_is_blog_context() && mariuszkowal_wordpress_is_named_menu_item( $menu_item, 'blog' );

	if ( ! $is_current_menu_item && ! $is_portfolio_item && ! $is_blog_item ) {
		return $atts;
	}

	$atts['class']        = empty( $atts['class'] ) ? 'is-active' : $atts['class'] . ' is-active';
	$atts['aria-current'] = 'page';

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'mariuszkowal_wordpress_nav_menu_link_attributes', 10, 2 );

/* FORMATOWANIE BLOKÓW CODE WE WPISACH */
function mariuszkowal_wordpress_clean_code_line_breaks( $content ) {
	return preg_replace_callback(
		'/<code\b[^>]*>.*?<\/code>/is',
		function ( $matches ) {
			return preg_replace( '/<br\s*\/?>/i', '', $matches[0] );
		},
		$content
	);
}

/* SIDEBAR BLOGA */
function mariuszkowal_wordpress_blog_sidebar( $search_input_id = 'blog-search' ) {
	$blog_categories = get_categories(
		array(
			'hide_empty' => true,
			'taxonomy'   => 'category',
		)
	);
	$blog_tags       = get_tags(
		array(
			'hide_empty' => true,
			'taxonomy'   => 'post_tag',
		)
	);
	?>

	<div class="sidebar-widget">
		<h3><?php esc_html_e( 'Wyszukiwarka', 'mariuszkowal-wordpress' ); ?></h3>
		<form class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
			<label class="sr-only" for="<?php echo esc_attr( $search_input_id ); ?>"><?php esc_html_e( 'Szukaj wpisów', 'mariuszkowal-wordpress' ); ?></label>
			<input id="<?php echo esc_attr( $search_input_id ); ?>" name="s" type="search" placeholder="<?php esc_attr_e( 'Szukaj wpisów...', 'mariuszkowal-wordpress' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
			<button class="button button--primary" type="submit"><?php esc_html_e( 'SZUKAJ', 'mariuszkowal-wordpress' ); ?></button>
		</form>
	</div>

	<?php if ( $blog_categories ) : ?>
		<div class="sidebar-widget">
			<h3><?php esc_html_e( 'Kategorie', 'mariuszkowal-wordpress' ); ?></h3>
			<ul class="sidebar-list">
				<?php foreach ( $blog_categories as $blog_category ) : ?>
					<li class="<?php echo is_category( $blog_category->term_id ) ? 'current-cat' : ''; ?>">
						<a href="<?php echo esc_url( get_category_link( $blog_category ) ); ?>">
							<?php echo esc_html( $blog_category->name ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if ( $blog_tags ) : ?>
		<div class="sidebar-widget">
			<h3><?php esc_html_e( 'Tagi', 'mariuszkowal-wordpress' ); ?></h3>
			<div class="tag-list">
				<?php foreach ( $blog_tags as $blog_tag ) : ?>
					<a class="<?php echo is_tag( $blog_tag->term_id ) ? 'is-active' : ''; ?>" href="<?php echo esc_url( get_tag_link( $blog_tag ) ); ?>">
						<?php echo esc_html( $blog_tag->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'blog_ad_1' ) ) : ?>
		<?php dynamic_sidebar( 'blog_ad_1' ); ?>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'blog_ad_2' ) ) : ?>
		<?php dynamic_sidebar( 'blog_ad_2' ); ?>
	<?php endif; ?>
	<?php
}

/* WYŁĄCZENIE EDYTORA BLOKOWEGO GUTENBERG */
add_filter( 'use_block_editor_for_post', '__return_false' );
add_filter( 'use_block_editor_for_post_type', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );
