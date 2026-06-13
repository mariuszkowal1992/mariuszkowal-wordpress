<?php
/**
 * Theme header for the home page template.
 *
 * @package MARIUSZKOWAL_WordPress
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$mariuszkowal_menu_location = 'front_page_menu';
$mariuszkowal_has_menu      = has_nav_menu( $mariuszkowal_menu_location );
?>

<header class="site-header">
	<nav class="main-nav" aria-label="<?php esc_attr_e( 'Główna nawigacja', 'mariuszkowal-wordpress' ); ?>">
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php bloginfo( 'name' ); ?>
		</a>

		<?php if ( $mariuszkowal_has_menu ) : ?>
		<button class="menu-toggle" type="button" aria-label="<?php esc_attr_e( 'Otwórz menu', 'mariuszkowal-wordpress' ); ?>" aria-controls="primary-menu" aria-expanded="false">
			<i class="fa-solid fa-bars" aria-hidden="true"></i>
		</button>

		<div class="nav-panel" id="primary-menu">
			<button class="menu-close" type="button" aria-label="<?php esc_attr_e( 'Zamknij menu', 'mariuszkowal-wordpress' ); ?>">
				<i class="fa-solid fa-xmark" aria-hidden="true"></i>
			</button>

			<?php
			wp_nav_menu(
				array(
					'theme_location' => $mariuszkowal_menu_location,
					'container'      => false,
					'menu_class'     => 'nav-list',
					'menu_id'        => '',
					'fallback_cb'    => false,
				)
			);
			?>
		</div>
		<?php endif; ?>
	</nav>
</header>
