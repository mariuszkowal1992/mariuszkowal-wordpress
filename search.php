<?php
/**
 * Search results template.
 *
 * @package MARIUSZKOWAL_WordPress
 */

get_header();

$search_query_text          = get_search_query();
$search_current_page_number = isset( $_GET['search-page'] ) ? max( 1, (int) $_GET['search-page'] ) : 1;
$search_results_query       = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		's'              => $search_query_text,
		'posts_per_page' => 4,
		'paged'          => $search_current_page_number,
	)
);
?>

<main>
	<!-- SEKCJA WYNIKÓW WYSZUKIWANIA -->
	<section class="subpage-section search-results-section">
		<h1><?php esc_html_e( 'Wyniki Wyszukiwania', 'mariuszkowal-wordpress' ); ?></h1>

		<p class="search-results-summary">
			<?php esc_html_e( 'Wyniki wyszukiwania dla:', 'mariuszkowal-wordpress' ); ?>
			<span><?php echo esc_html( $search_query_text ); ?></span>
		</p>

		<div class="subpage-grid">
			<div class="subpage-column">
				<?php if ( $search_results_query->have_posts() ) : ?>
					<div class="blog-list">
						<?php
						while ( $search_results_query->have_posts() ) :
							$search_results_query->the_post();
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

					<form id="search-pagination-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
						<input type="hidden" name="s" value="<?php echo esc_attr( $search_query_text ); ?>">
					</form>

					<nav class="pagination" aria-label="<?php esc_attr_e( 'Paginacja wyników wyszukiwania', 'mariuszkowal-wordpress' ); ?>">
						<?php for ( $search_page = 1; $search_page <= max( 1, (int) $search_results_query->max_num_pages ); $search_page++ ) : ?>
							<button class="<?php echo $search_current_page_number === $search_page ? 'is-active' : ''; ?>" form="search-pagination-form" type="submit" name="search-page" value="<?php echo esc_attr( $search_page ); ?>" aria-current="<?php echo $search_current_page_number === $search_page ? 'page' : 'false'; ?>">
								<?php echo esc_html( $search_page ); ?>
							</button>
						<?php endfor; ?>
					</nav>
				<?php else : ?>
					<div class="search-empty">
						<h2><?php esc_html_e( 'BRAK WYNIKÓW', 'mariuszkowal-wordpress' ); ?></h2>
						<p><?php esc_html_e( 'Nie znaleziono wpisów pasujących do podanej frazy. Spróbuj wpisać inne słowo kluczowe.', 'mariuszkowal-wordpress' ); ?></p>
					</div>
				<?php endif; ?>
			</div>

			<aside class="subpage-column blog-sidebar" aria-label="<?php esc_attr_e( 'Sidebar wyszukiwania', 'mariuszkowal-wordpress' ); ?>">
				<?php mariuszkowal_wordpress_blog_sidebar( 'search-results-input' ); ?>
			</aside>
		</div>
	</section>
</main>

<?php
wp_reset_postdata();
get_footer();
