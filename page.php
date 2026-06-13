<?php
/**
 * Page template.
 *
 * @package MARIUSZKOWAL_WordPress
 */

get_header();
?>

<main>
	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<!-- SEKCJA STRONY INFORMACYJNEJ -->
		<section class="subpage-section page-content-section">
			<h1><?php the_title(); ?></h1>

			<?php if ( get_the_content() ) : ?>
				<div class="page-content-text">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</section>

	<?php endwhile; ?>
</main>

<?php
get_footer();
