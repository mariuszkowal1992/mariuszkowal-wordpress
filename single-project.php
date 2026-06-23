<?php
/**
 * Single project template.
 *
 * @package MARIUSZKOWAL_WordPress
 */

get_header();
?>

<main>
	<?php
	while ( have_posts() ) :
		the_post();

		/* POLA ACF PRO: PROJEKT */
		$project_goal           = function_exists( 'get_field' ) ? get_field( 'project_goal' ) : '';
		$project_implementation = function_exists( 'get_field' ) ? get_field( 'project_implementation' ) : '';
		$project_gallery        = function_exists( 'get_field' ) ? get_field( 'project_gallery' ) : array();
		$project_date           = function_exists( 'get_field' ) ? get_field( 'project_date' ) : '';
		$project_client         = function_exists( 'get_field' ) ? get_field( 'project_client' ) : '';
		$project_github_link    = function_exists( 'get_field' ) ? get_field( 'project_github_link' ) : array();
		$project_live_link      = function_exists( 'get_field' ) ? get_field( 'project_live_link' ) : array();
		$project_technologies   = function_exists( 'get_field' ) ? get_field( 'project_technologies' ) : '';
		$project_categories     = get_the_terms( get_the_ID(), 'project-category' );
		$project_category_names = array();
		$project_date_display   = '';

		$project_gallery        = is_array( $project_gallery ) ? $project_gallery : array();
		$project_gallery_images = array();
		$project_github_link    = is_array( $project_github_link ) ? $project_github_link : array();
		$project_live_link      = is_array( $project_live_link ) ? $project_live_link : array();

		if ( $project_categories && ! is_wp_error( $project_categories ) ) {
			$project_category_names = wp_list_pluck( $project_categories, 'name' );
		}

		if ( $project_date ) {
			$project_date_object = DateTime::createFromFormat( 'Ymd', $project_date );

			if ( ! $project_date_object ) {
				$project_date_object = date_create( $project_date );
			}

			$project_date_display = $project_date_object ? wp_date( 'd.m.Y', $project_date_object->getTimestamp() ) : $project_date;
		}

		foreach ( $project_gallery as $project_gallery_image ) {
			$project_gallery_image_id = 0;

			if ( is_array( $project_gallery_image ) && ! empty( $project_gallery_image['ID'] ) ) {
				$project_gallery_image_id = (int) $project_gallery_image['ID'];
			} elseif ( is_numeric( $project_gallery_image ) ) {
				$project_gallery_image_id = (int) $project_gallery_image;
			}

			if ( ! $project_gallery_image_id ) {
				continue;
			}

			$project_gallery_large_url = wp_get_attachment_image_url( $project_gallery_image_id, 'large' );
			$project_gallery_full_url  = wp_get_attachment_image_url( $project_gallery_image_id, 'full' );
			$project_gallery_thumb_url = wp_get_attachment_image_url( $project_gallery_image_id, 'medium' );
			$project_gallery_metadata  = wp_get_attachment_metadata( $project_gallery_image_id );
			$project_gallery_width     = ! empty( $project_gallery_metadata['width'] ) ? (int) $project_gallery_metadata['width'] : 0;
			$project_gallery_height    = ! empty( $project_gallery_metadata['height'] ) ? (int) $project_gallery_metadata['height'] : 0;

			if ( ! $project_gallery_large_url || ! $project_gallery_full_url || ! $project_gallery_thumb_url ) {
				continue;
			}

			$project_gallery_images[] = array(
				'id'        => $project_gallery_image_id,
				'large_url' => $project_gallery_large_url,
				'full_url'  => $project_gallery_full_url,
				'thumb_url' => $project_gallery_thumb_url,
				'alt'       => get_post_meta( $project_gallery_image_id, '_wp_attachment_image_alt', true ),
				'caption'   => wp_get_attachment_caption( $project_gallery_image_id ),
				'is_large'  => $project_gallery_width > 800 || $project_gallery_height > 600,
			);
		}

		$project_has_info = $project_category_names || $project_date_display || $project_client || ( ! empty( $project_github_link['url'] ) && ! empty( $project_github_link['title'] ) ) || ( ! empty( $project_live_link['url'] ) && ! empty( $project_live_link['title'] ) ) || $project_technologies;
		?>

		<!-- SEKCJA PROJEKTU -->
		<section class="subpage-section single-project-section">
			<div class="subpage-grid">
				<article class="subpage-column single-project-content">
					<h1><?php the_title(); ?></h1>

					<?php if ( $project_gallery_images ) : ?>
						<div class="project-gallery" data-project-gallery>
							<div class="project-gallery__stage">
								<div class="project-gallery__track">
									<?php foreach ( $project_gallery_images as $project_gallery_image ) : ?>
										<a class="project-gallery__slide" href="<?php echo esc_url( $project_gallery_image['full_url'] ); ?>" data-fancybox="project-gallery-<?php the_ID(); ?>" data-caption="<?php echo esc_attr( $project_gallery_image['caption'] ); ?>">
											<img class="<?php echo $project_gallery_image['is_large'] ? 'project-gallery__image--contain' : ''; ?>" src="<?php echo esc_url( $project_gallery_image['large_url'] ); ?>" alt="<?php echo esc_attr( $project_gallery_image['alt'] ); ?>">
										</a>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="project-gallery__thumbs" aria-label="<?php esc_attr_e( 'Miniatury projektu', 'mariuszkowal-wordpress' ); ?>">
								<?php foreach ( $project_gallery_images as $project_gallery_index => $project_gallery_image ) : ?>
									<button class="<?php echo 0 === $project_gallery_index ? 'is-active' : ''; ?>" type="button" aria-label="<?php echo esc_attr( sprintf( __( 'Pokaż screen projektu %d', 'mariuszkowal-wordpress' ), $project_gallery_index + 1 ) ); ?>" data-full="<?php echo esc_url( $project_gallery_image['full_url'] ); ?>">
										<img src="<?php echo esc_url( $project_gallery_image['thumb_url'] ); ?>" alt="<?php echo esc_attr( $project_gallery_image['alt'] ); ?>">
									</button>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $project_goal || $project_implementation ) : ?>
						<div class="single-project-body">
							<?php if ( $project_goal ) : ?>
								<h2><?php esc_html_e( 'CEL PROJEKTU', 'mariuszkowal-wordpress' ); ?></h2>
								<?php echo wp_kses_post( $project_goal ); ?>
							<?php endif; ?>

							<?php if ( $project_implementation ) : ?>
								<h2><?php esc_html_e( 'JAK TO ZOSTAŁO ZREALIZOWANE', 'mariuszkowal-wordpress' ); ?></h2>
								<?php echo wp_kses_post( $project_implementation ); ?>
							<?php endif; ?>

							<div class="single-project-actions">
								<a class="button button--primary" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">
									<?php esc_html_e( 'WRÓĆ DO PORTFOLIO', 'mariuszkowal-wordpress' ); ?>
								</a>
							</div>
						</div>
					<?php endif; ?>
				</article>

				<?php if ( $project_has_info ) : ?>
					<aside class="subpage-column single-project-sidebar" aria-label="<?php esc_attr_e( 'Sidebar projektu', 'mariuszkowal-wordpress' ); ?>">
						<div class="sidebar-widget project-info-widget">
							<h3><?php esc_html_e( 'INFORMACJE DOTYCZĄCE PROJEKTU', 'mariuszkowal-wordpress' ); ?></h3>

							<dl class="project-info-list">
								<?php if ( $project_category_names ) : ?>
									<div>
										<dt><?php esc_html_e( 'KATEGORIA', 'mariuszkowal-wordpress' ); ?></dt>
										<dd><?php echo esc_html( implode( ', ', $project_category_names ) ); ?></dd>
									</div>
								<?php endif; ?>

								<?php if ( $project_date_display ) : ?>
									<div>
										<dt><?php esc_html_e( 'DATA REALIZACJI', 'mariuszkowal-wordpress' ); ?></dt>
										<dd><?php echo esc_html( $project_date_display ); ?></dd>
									</div>
								<?php endif; ?>

								<?php if ( $project_client ) : ?>
									<div>
										<dt><?php esc_html_e( 'KLIENT', 'mariuszkowal-wordpress' ); ?></dt>
										<dd><?php echo esc_html( $project_client ); ?></dd>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $project_github_link['url'] ) && ! empty( $project_github_link['title'] ) ) : ?>
									<div>
										<dt><?php esc_html_e( 'LINK GITHUB', 'mariuszkowal-wordpress' ); ?></dt>
										<dd>
											<a href="<?php echo esc_url( $project_github_link['url'] ); ?>" target="<?php echo esc_attr( ! empty( $project_github_link['target'] ) ? $project_github_link['target'] : '_self' ); ?>">
												<?php echo esc_html( $project_github_link['title'] ); ?>
											</a>
										</dd>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $project_live_link['url'] ) && ! empty( $project_live_link['title'] ) ) : ?>
									<div>
										<dt><?php esc_html_e( 'LINK LIVE', 'mariuszkowal-wordpress' ); ?></dt>
										<dd>
											<a href="<?php echo esc_url( $project_live_link['url'] ); ?>" target="<?php echo esc_attr( ! empty( $project_live_link['target'] ) ? $project_live_link['target'] : '_self' ); ?>">
												<?php echo esc_html( $project_live_link['title'] ); ?>
											</a>
										</dd>
									</div>
								<?php endif; ?>

								<?php if ( $project_technologies ) : ?>
									<div>
										<dt><?php esc_html_e( 'UŻYTE TECHNOLOGIE', 'mariuszkowal-wordpress' ); ?></dt>
										<dd><?php echo esc_html( $project_technologies ); ?></dd>
									</div>
								<?php endif; ?>
							</dl>
						</div>
					</aside>
				<?php endif; ?>
			</div>
		</section>

	<?php endwhile; ?>
</main>

<?php
get_footer();
