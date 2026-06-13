<?php
/**
 * Template Name: Blog
 *
 * @package MARIUSZKOWAL_WordPress
 */

get_header();

$blog_current_page_number = isset( $_GET['blog-page'] ) ? max( 1, (int) $_GET['blog-page'] ) : 1;
$blog_posts_query         = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 4,
		'paged'          => $blog_current_page_number,
	)
);
?>

<main>
	<!-- SEKCJA BLOG -->
	<section class="subpage-section blog-page-section">
		<h1><?php the_title(); ?></h1>

		<div class="subpage-grid">
			<div class="subpage-column">
				<?php if ( $blog_posts_query->have_posts() ) : ?>
					<div class="blog-list">
						<?php
						while ( $blog_posts_query->have_posts() ) :
							$blog_posts_query->the_post();
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

					<form id="blog-pagination-form" action="<?php echo esc_url( get_permalink() ); ?>" method="get"></form>

					<nav class="pagination" aria-label="<?php esc_attr_e( 'Paginacja wpisów', 'mariuszkowal-wordpress' ); ?>">
						<?php for ( $blog_page = 1; $blog_page <= max( 1, (int) $blog_posts_query->max_num_pages ); $blog_page++ ) : ?>
							<button class="<?php echo $blog_current_page_number === $blog_page ? 'is-active' : ''; ?>" form="blog-pagination-form" type="submit" name="blog-page" value="<?php echo esc_attr( $blog_page ); ?>" aria-current="<?php echo $blog_current_page_number === $blog_page ? 'page' : 'false'; ?>">
								<?php echo esc_html( $blog_page ); ?>
							</button>
						<?php endfor; ?>
					</nav>
				<?php else : ?>
					<div class="search-empty">
						<h2><?php esc_html_e( 'BRAK WYNIKÓW', 'mariuszkowal-wordpress' ); ?></h2>
						<p><?php esc_html_e( 'Nie ma żadnych wpisów na blogu.', 'mariuszkowal-wordpress' ); ?></p>
					</div>
				<?php endif; ?>
			</div>

			<aside class="subpage-column blog-sidebar" aria-label="<?php esc_attr_e( 'Sidebar bloga', 'mariuszkowal-wordpress' ); ?>">
				<?php mariuszkowal_wordpress_blog_sidebar( 'blog-search' ); ?>
			</aside>
		</div>
	</section>
</main>

<?php
wp_reset_postdata();
get_footer();
