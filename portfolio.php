<?php
/**
 * Template Name: Portfolio
 *
 * @package MARIUSZKOWAL_WordPress
 */

get_header();

$portfolio_page_id             = get_queried_object_id();
$portfolio_page_permalink      = get_permalink( $portfolio_page_id );
$portfolio_category_query      = isset( $_GET['project-category'] ) ? sanitize_title( wp_unslash( $_GET['project-category'] ) ) : '';
$portfolio_selected_category   = 'wszystkie' === $portfolio_category_query ? '' : $portfolio_category_query;
$portfolio_current_page_number = isset( $_GET['portfolio-page'] ) ? max( 1, (int) $_GET['portfolio-page'] ) : 1;
$portfolio_terms               = get_terms(
	array(
		'taxonomy'   => 'project-category',
		'hide_empty' => true,
	)
);

if ( is_wp_error( $portfolio_terms ) ) {
	$portfolio_terms = array();
}

$portfolio_query_args = array(
	'post_type'      => 'project',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'paged'          => $portfolio_current_page_number,
);

if ( $portfolio_selected_category ) {
	$portfolio_query_args['tax_query'] = array(
		array(
			'taxonomy' => 'project-category',
			'field'    => 'slug',
			'terms'    => $portfolio_selected_category,
		),
	);
}

$portfolio_projects = new WP_Query( $portfolio_query_args );
?>

<main>
	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<!-- SEKCJA PORTFOLIO -->
		<section class="subpage-section portfolio-page-section">
			<h1><?php the_title(); ?></h1>

			<div class="subpage-grid">
				<div class="subpage-column">
					<?php if ( $portfolio_projects->have_posts() ) : ?>
						<div class="portfolio-list">
							<?php
							while ( $portfolio_projects->have_posts() ) :
								$portfolio_projects->the_post();
								?>

								<article class="portfolio-card">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'large', array( 'class' => 'portfolio-card__image', 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
									<?php endif; ?>

									<div class="portfolio-card__content">
										<h5><?php the_title(); ?></h5>

										<?php if ( get_the_excerpt() ) : ?>
											<p><?php echo esc_html( get_the_excerpt() ); ?></p>
										<?php endif; ?>

										<a class="button button--outline" href="<?php the_permalink(); ?>">
											<?php esc_html_e( 'WIĘCEJ INFORMACJI', 'mariuszkowal-wordpress' ); ?>
										</a>
									</div>
								</article>

							<?php endwhile; ?>
						</div>

						<form id="portfolio-pagination-form" action="<?php echo esc_url( $portfolio_page_permalink ); ?>" method="get">
							<input type="hidden" name="project-category" value="<?php echo esc_attr( $portfolio_selected_category ? $portfolio_selected_category : 'wszystkie' ); ?>">
						</form>

						<nav class="pagination" aria-label="<?php esc_attr_e( 'Paginacja projektów', 'mariuszkowal-wordpress' ); ?>" data-pagination-controls>
							<?php for ( $portfolio_page = 1; $portfolio_page <= max( 1, (int) $portfolio_projects->max_num_pages ); $portfolio_page++ ) : ?>
								<button class="<?php echo $portfolio_current_page_number === $portfolio_page ? 'is-active' : ''; ?>" form="portfolio-pagination-form" type="submit" name="portfolio-page" value="<?php echo esc_attr( $portfolio_page ); ?>" aria-current="<?php echo $portfolio_current_page_number === $portfolio_page ? 'page' : 'false'; ?>">
									<?php echo esc_html( $portfolio_page ); ?>
								</button>
							<?php endfor; ?>
						</nav>
					<?php else : ?>
						<div class="search-empty">
							<h2><?php esc_html_e( 'BRAK WYNIKÓW', 'mariuszkowal-wordpress' ); ?></h2>
							<p><?php esc_html_e( 'Nie ma żadnych projektów w portfolio.', 'mariuszkowal-wordpress' ); ?></p>
						</div>
					<?php endif; ?>
				</div>

				<aside class="subpage-column portfolio-sidebar" aria-label="<?php esc_attr_e( 'Sidebar portfolio', 'mariuszkowal-wordpress' ); ?>">
					<div class="sidebar-widget">
						<h3><?php esc_html_e( 'Filtruj Według Kategorii', 'mariuszkowal-wordpress' ); ?></h3>

						<form class="filter-buttons" action="<?php echo esc_url( $portfolio_page_permalink ); ?>" method="get">
							<button class="<?php echo $portfolio_selected_category ? '' : 'is-active'; ?>" type="submit" name="project-category" value="wszystkie" data-filter="all" aria-pressed="<?php echo $portfolio_selected_category ? 'false' : 'true'; ?>">
								<?php esc_html_e( 'Wszystkie', 'mariuszkowal-wordpress' ); ?>
							</button>

							<?php foreach ( $portfolio_terms as $portfolio_term ) : ?>
								<button class="<?php echo $portfolio_selected_category === $portfolio_term->slug ? 'is-active' : ''; ?>" type="submit" name="project-category" value="<?php echo esc_attr( $portfolio_term->slug ); ?>" data-filter="<?php echo esc_attr( $portfolio_term->slug ); ?>" aria-pressed="<?php echo $portfolio_selected_category === $portfolio_term->slug ? 'true' : 'false'; ?>">
									<?php echo esc_html( $portfolio_term->name ); ?>
								</button>
							<?php endforeach; ?>
						</form>
					</div>

					<?php if ( is_active_sidebar( 'portfolio_ad_1' ) ) : ?>
						<?php dynamic_sidebar( 'portfolio_ad_1' ); ?>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'portfolio_ad_2' ) ) : ?>
						<?php dynamic_sidebar( 'portfolio_ad_2' ); ?>
					<?php endif; ?>
				</aside>
			</div>
		</section>

		<?php
	endwhile;
	wp_reset_postdata();
	?>
</main>

<?php
get_footer();
