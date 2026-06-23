<?php
/**
 * Single post template.
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

		<!-- SEKCJA WPISU -->
		<section class="subpage-section single-post-section">
			<div class="subpage-grid">
				<article class="subpage-column single-post-content">
					<h1><?php the_title(); ?></h1>

					<div class="single-post-meta">
						<span><?php echo esc_html( get_the_category_list( ', ' ) ? wp_strip_all_tags( get_the_category_list( ', ' ) ) : get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
					</div>

					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'single-post-image', 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
					<?php endif; ?>

					<div class="single-post-body" data-post-content-gallery="<?php echo esc_attr( get_the_ID() ); ?>">
						<?php echo wp_kses_post( mariuszkowal_wordpress_clean_code_line_breaks( apply_filters( 'the_content', get_the_content() ) ) ); ?>
					</div>

					<div class="single-post-actions">
						<a class="button button--primary" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">
							<?php esc_html_e( 'WRÓĆ DO BLOGA', 'mariuszkowal-wordpress' ); ?>
						</a>
					</div>

					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</article>

				<aside class="subpage-column blog-sidebar single-post-sidebar" aria-label="<?php esc_attr_e( 'Sidebar wpisu', 'mariuszkowal-wordpress' ); ?>">
					<?php mariuszkowal_wordpress_blog_sidebar( 'single-post-search' ); ?>
				</aside>
			</div>
		</section>

	<?php endwhile; ?>
</main>

<?php
get_footer();
