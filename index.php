<?php
/**
 * Main template file.
 *
 * @package MARIUSZKOWAL_WordPress
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>

			<?php
		endwhile;
	else :
		?>

		<p><?php esc_html_e( 'Nie znaleziono treści do wyświetlenia.', 'mariuszkowal-wordpress' ); ?></p>

		<?php
	endif;
	?>
</main>

<?php
get_footer();
