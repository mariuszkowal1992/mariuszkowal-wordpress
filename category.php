<?php
/**
 * Category archive template.
 *
 * @package MARIUSZKOWAL_WordPress
 */

get_header();

$category_object              = get_queried_object();
$category_current_page_number = isset( $_GET['category-page'] ) ? max( 1, (int) $_GET['category-page'] ) : 1;
$category_posts_query         = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'cat'            => $category_object instanceof WP_Term ? (int) $category_object->term_id : 0,
		'posts_per_page' => 4,
		'paged'          => $category_current_page_number,
	)
);
?>

<main>
	<!-- SEKCJA KATEGORII BLOGA -->
	<section class="subpage-section search-results-section">
		<h1><?php single_cat_title(); ?></h1>

		<p class="search-results-summary">
			<?php esc_html_e( 'Wpisy z kategorii:', 'mariuszkowal-wordpress' ); ?>
			<span><?php single_cat_title(); ?></span>
		</p>

		<div class="subpage-grid">
			<div class="subpage-column">
				<?php if ( $category_posts_query->have_posts() ) : ?>
					<div class="blog-list">
						<?php
						while ( $category_posts_query->have_posts() ) :
							$category_posts_query->the_post();
							?>

							<article class="blog-card">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
								<?php endif; ?>

								<div class="blog-card__content">
									<h5><?php the_title(); ?></h5>

									<div class="blog-card__meta">
										<span><?php echo esc_html( get_the_category_list( ', ' ) ? wp_strip_all_tags( get_the_category_list( ', ' ) ) : __( 'Blog', 'mariuszkowal-wordpress' ) ); ?></span>
										<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
									</div>

									<?php if ( get_the_excerpt() ) : ?>
										<p><?php echo esc_html( get_the_excerpt() ); ?></p>
									<?php endif; ?>

									<a class="button button--outline" href="<?php the_permalink(); ?>">
										<?php esc_html_e( 'CZYTAJ DALEJ', 'mariuszkowal-wordpress' ); ?>
									</a>
								</div>
							</article>

						<?php endwhile; ?>
					</div>

					<form id="category-pagination-form" action="<?php echo esc_url( get_category_link( $category_object ) ); ?>" method="get"></form>

					<nav class="pagination" aria-label="<?php esc_attr_e( 'Paginacja wpisów kategorii', 'mariuszkowal-wordpress' ); ?>">
						<?php for ( $category_page = 1; $category_page <= max( 1, (int) $category_posts_query->max_num_pages ); $category_page++ ) : ?>
							<button class="<?php echo $category_current_page_number === $category_page ? 'is-active' : ''; ?>" form="category-pagination-form" type="submit" name="category-page" value="<?php echo esc_attr( $category_page ); ?>" aria-current="<?php echo $category_current_page_number === $category_page ? 'page' : 'false'; ?>">
								<?php echo esc_html( $category_page ); ?>
							</button>
						<?php endfor; ?>
					</nav>
				<?php else : ?>
					<div class="search-empty">
						<h2><?php esc_html_e( 'BRAK WPISÓW', 'mariuszkowal-wordpress' ); ?></h2>
						<p><?php esc_html_e( 'Nie znaleziono wpisów w tej kategorii.', 'mariuszkowal-wordpress' ); ?></p>
					</div>
				<?php endif; ?>
			</div>

			<aside class="subpage-column blog-sidebar" aria-label="<?php esc_attr_e( 'Sidebar kategorii', 'mariuszkowal-wordpress' ); ?>">
				<?php mariuszkowal_wordpress_blog_sidebar( 'category-search-input' ); ?>
			</aside>
		</div>
	</section>
</main>

<?php
wp_reset_postdata();
get_footer();
