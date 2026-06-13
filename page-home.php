<?php
/**
 * Template Name: Strona Główna
 *
 * @package MARIUSZKOWAL_WordPress
 */

get_header( 'home' );

/* POLA ACF PRO: STRONA GŁÓWNA - SEKCJA START */
$start_heading          = function_exists( 'get_field' ) ? get_field( 'start_heading' ) : '';
$start_description      = function_exists( 'get_field' ) ? get_field( 'start_description' ) : '';
$start_primary_button   = function_exists( 'get_field' ) ? get_field( 'start_primary_button' ) : array();
$start_secondary_button = function_exists( 'get_field' ) ? get_field( 'start_secondary_button' ) : array();

/* POLA ACF PRO: STRONA GŁÓWNA - SEKCJA O MNIE */
$about_section_heading     = function_exists( 'get_field' ) ? get_field( 'about_section_heading' ) : '';
$about_section_image       = function_exists( 'get_field' ) ? get_field( 'about_section_image' ) : array();
$about_content_heading     = function_exists( 'get_field' ) ? get_field( 'about_content_heading' ) : '';
$about_content_description = function_exists( 'get_field' ) ? get_field( 'about_content_description' ) : '';
$about_image_id            = 0;
$about_image_url           = '';
$about_image_alt           = '';

if ( is_array( $about_section_image ) && ! empty( $about_section_image['ID'] ) ) {
	$about_image_id = (int) $about_section_image['ID'];
} elseif ( is_numeric( $about_section_image ) ) {
	$about_image_id = (int) $about_section_image;
}

if ( $about_image_id ) {
	$about_image_url = wp_get_attachment_image_url( $about_image_id, 'large' );
	$about_image_alt = get_post_meta( $about_image_id, '_wp_attachment_image_alt', true );
} elseif ( is_array( $about_section_image ) && ! empty( $about_section_image['url'] ) ) {
	$about_image_url = ! empty( $about_section_image['sizes']['large'] ) ? $about_section_image['sizes']['large'] : $about_section_image['url'];
}

$about_has_content = $about_section_heading || $about_image_url || $about_content_heading || $about_content_description;

/* POLA ACF PRO: STRONA GŁÓWNA - SEKCJA USŁUGI */
$services_section_heading = function_exists( 'get_field' ) ? get_field( 'services_section_heading' ) : '';
$services_cards           = function_exists( 'get_field' ) ? get_field( 'services_cards' ) : array();
$services_cards           = is_array( $services_cards ) ? $services_cards : array();
$services_visible_cards   = array();

foreach ( $services_cards as $service_card ) {
	$service_icon        = ! empty( $service_card['service_icon'] ) ? $service_card['service_icon'] : '';
	$service_title       = ! empty( $service_card['service_title'] ) ? $service_card['service_title'] : '';
	$service_description = ! empty( $service_card['service_description'] ) ? $service_card['service_description'] : '';

	if ( ! $service_icon && ! $service_title && ! $service_description ) {
		continue;
	}

	$services_visible_cards[] = $service_card;
}

$services_has_content = $services_section_heading || $services_visible_cards;

/* POLA ACF PRO: STRONA GŁÓWNA - SEKCJA PORTFOLIO */
$portfolio_section_heading = function_exists( 'get_field' ) ? get_field( 'portfolio_section_heading' ) : '';
$home_portfolio_projects   = new WP_Query(
	array(
		'post_type'      => 'project',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

/* POLA ACF PRO: STRONA GŁÓWNA - SEKCJA BLOG */
$blog_section_heading = function_exists( 'get_field' ) ? get_field( 'blog_section_heading' ) : '';
$home_blog_posts      = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

/* POLA ACF PRO: STRONA GŁÓWNA - SEKCJA KONTAKT */
$contact_section_heading = function_exists( 'get_field' ) ? get_field( 'contact_section_heading' ) : '';
$contact_email           = function_exists( 'get_field' ) ? get_field( 'contact_email' ) : '';
$contact_phone           = function_exists( 'get_field' ) ? get_field( 'contact_phone' ) : '';
$contact_address         = function_exists( 'get_field' ) ? get_field( 'contact_address' ) : '';
$contact_working_hours   = function_exists( 'get_field' ) ? get_field( 'contact_working_hours' ) : '';
$contact_form_shortcode  = function_exists( 'get_field' ) ? get_field( 'contact_form_shortcode' ) : '';
$contact_phone_link      = $contact_phone ? preg_replace( '/[^0-9+]/', '', $contact_phone ) : '';

$contact_has_details = $contact_email || $contact_phone || $contact_address || $contact_working_hours;
$contact_has_content = $contact_section_heading || $contact_has_details || $contact_form_shortcode;
?>
<main>
        <!-- SEKCJA START -->
        <section class="start-section reveal-on-scroll" id="start">
            <div class="start-column start-content">
                <?php if ( $start_heading ) : ?>
                    <h1><?php echo esc_html( $start_heading ); ?></h1>
                <?php endif; ?>
                <?php if ( $start_description ) : ?>
                    <p>
                        <?php echo wp_kses_post( $start_description ); ?>
                    </p>
                <?php endif; ?>
                <?php if ( ( is_array( $start_primary_button ) && ! empty( $start_primary_button['url'] ) && ! empty( $start_primary_button['title'] ) ) || ( is_array( $start_secondary_button ) && ! empty( $start_secondary_button['url'] ) && ! empty( $start_secondary_button['title'] ) ) ) : ?>
                <div class="start-actions">
                    <?php if ( is_array( $start_primary_button ) && ! empty( $start_primary_button['url'] ) && ! empty( $start_primary_button['title'] ) ) : ?>
                    <a class="button button--primary" href="<?php echo esc_url( $start_primary_button['url'] ); ?>" target="<?php echo esc_attr( ! empty( $start_primary_button['target'] ) ? $start_primary_button['target'] : '_self' ); ?>">
                        <?php echo esc_html( $start_primary_button['title'] ); ?>
                    </a>
                    <?php endif; ?>
                    <?php if ( is_array( $start_secondary_button ) && ! empty( $start_secondary_button['url'] ) && ! empty( $start_secondary_button['title'] ) ) : ?>
                    <a class="button button--outline" href="<?php echo esc_url( $start_secondary_button['url'] ); ?>" target="<?php echo esc_attr( ! empty( $start_secondary_button['target'] ) ? $start_secondary_button['target'] : '_self' ); ?>">
                        <?php echo esc_html( $start_secondary_button['title'] ); ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="start-column start-code">
                <div class="code-editor" aria-label="Przykładowy kod WordPress">
                    <div class="code-editor__bar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <pre><code>&lt;?php
add_action('wp_enqueue_scripts', function () {
    $theme_uri = get_template_directory_uri();

    wp_enqueue_style('mk-style', get_stylesheet_uri());
    wp_enqueue_script('mk-main', $theme_uri . '/assets/js/main.js');
});

add_filter('excerpt_length', function () {
    return 18;
});

add_theme_support('post-thumbnails');</code></pre>
                </div>
            </div>
        </section>
        <?php if ( $about_has_content ) : ?>
            <!-- SEKCJA O MNIE -->
            <section class="page-section about-section reveal-on-scroll" id="o-mnie">
                <?php if ( $about_section_heading ) : ?>
                    <h2><?php echo esc_html( $about_section_heading ); ?></h2>
                <?php endif; ?>

                <?php if ( $about_image_url || $about_content_heading || $about_content_description ) : ?>
                    <div class="about-grid">
                        <?php if ( $about_image_url ) : ?>
                            <div class="about-column about-column--image">
                                <figure class="about-image">
                                    <img src="<?php echo esc_url( $about_image_url ); ?>" alt="<?php echo esc_attr( $about_image_alt ); ?>" loading="lazy" decoding="async">
                                </figure>
                            </div>
                        <?php endif; ?>

                        <?php if ( $about_content_heading || $about_content_description ) : ?>
                            <div class="about-column about-column--text">
                                <?php if ( $about_content_heading ) : ?>
                                    <h3><?php echo esc_html( $about_content_heading ); ?></h3>
                                <?php endif; ?>

                                <?php if ( $about_content_description ) : ?>
                                    <?php echo wp_kses_post( $about_content_description ); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
        <?php if ( $services_has_content ) : ?>
            <!-- SEKCJA USŁUGI -->
            <section class="page-section services-section reveal-on-scroll" id="uslugi">
                <?php if ( $services_section_heading ) : ?>
                    <h2><?php echo esc_html( $services_section_heading ); ?></h2>
                <?php endif; ?>

                <?php if ( $services_visible_cards ) : ?>
                    <div class="services-grid">
                        <?php foreach ( $services_visible_cards as $service_card ) : ?>
                            <?php
                            $service_icon        = ! empty( $service_card['service_icon'] ) ? $service_card['service_icon'] : '';
                            $service_title       = ! empty( $service_card['service_title'] ) ? $service_card['service_title'] : '';
                            $service_description = ! empty( $service_card['service_description'] ) ? $service_card['service_description'] : '';
                            ?>
                            <article class="service-card reveal-on-scroll">
                                <?php if ( $service_icon ) : ?>
                                    <i class="<?php echo esc_attr( $service_icon ); ?>" aria-hidden="true"></i>
                                <?php endif; ?>

                                <?php if ( $service_title ) : ?>
                                    <h5><?php echo esc_html( $service_title ); ?></h5>
                                <?php endif; ?>

                                <?php if ( $service_description ) : ?>
                                    <p><?php echo wp_kses_post( $service_description ); ?></p>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
        <!-- SEKCJA PORTFOLIO -->
        <section class="page-section portfolio-section reveal-on-scroll" id="portfolio">
            <?php if ( $portfolio_section_heading ) : ?>
                <h2><?php echo esc_html( $portfolio_section_heading ); ?></h2>
            <?php endif; ?>

            <?php if ( $home_portfolio_projects->have_posts() ) : ?>
                <div class="portfolio-grid">
                    <?php
                    while ( $home_portfolio_projects->have_posts() ) :
                        $home_portfolio_projects->the_post();
                        ?>

                        <article class="portfolio-card reveal-on-scroll">
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
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="content-empty-message"><?php esc_html_e( 'Nie ma żadnych projektów w portfolio.', 'mariuszkowal-wordpress' ); ?></p>
            <?php endif; ?>

            <div class="portfolio-actions">
                <a class="button button--primary" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">
                    <?php esc_html_e( 'WIĘCEJ PROJEKTÓW', 'mariuszkowal-wordpress' ); ?>
                </a>
            </div>
        </section>
        <!-- SEKCJA BLOG -->
        <section class="page-section blog-section reveal-on-scroll" id="blog">
            <?php if ( $blog_section_heading ) : ?>
                <h2><?php echo esc_html( $blog_section_heading ); ?></h2>
            <?php endif; ?>

            <?php if ( $home_blog_posts->have_posts() ) : ?>
                <div class="blog-grid">
                    <?php
                    while ( $home_blog_posts->have_posts() ) :
                        $home_blog_posts->the_post();
                        ?>

                        <article class="blog-card reveal-on-scroll">
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
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="content-empty-message"><?php esc_html_e( 'Nie ma żadnych wpisów na blogu.', 'mariuszkowal-wordpress' ); ?></p>
            <?php endif; ?>

            <div class="blog-actions">
                <a class="button button--primary" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">
                    <?php esc_html_e( 'WIĘCEJ POSTÓW', 'mariuszkowal-wordpress' ); ?>
                </a>
            </div>
        </section>
        <?php if ( $contact_has_content ) : ?>
            <!-- SEKCJA KONTAKT -->
            <section class="page-section contact-section reveal-on-scroll" id="kontakt">
                <?php if ( $contact_section_heading ) : ?>
                    <h2><?php echo esc_html( $contact_section_heading ); ?></h2>
                <?php endif; ?>

                <?php if ( $contact_has_details || $contact_form_shortcode ) : ?>
                    <div class="contact-grid">
                        <?php if ( $contact_has_details ) : ?>
                            <div class="contact-column contact-details">
                                <h3><?php esc_html_e( 'Dane Kontaktowe', 'mariuszkowal-wordpress' ); ?></h3>

                                <ul class="contact-list">
                                    <?php if ( $contact_email ) : ?>
                                        <li>
                                            <span><?php esc_html_e( 'Adres E-mail', 'mariuszkowal-wordpress' ); ?></span>
                                            <a href="<?php echo esc_url( 'mailto:' . sanitize_email( $contact_email ) ); ?>"><?php echo esc_html( $contact_email ); ?></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ( $contact_phone ) : ?>
                                        <li>
                                            <span><?php esc_html_e( 'Nr Telefonu', 'mariuszkowal-wordpress' ); ?></span>
                                            <a href="<?php echo esc_url( 'tel:' . $contact_phone_link ); ?>"><?php echo esc_html( $contact_phone ); ?></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ( $contact_address ) : ?>
                                        <li>
                                            <span><?php esc_html_e( 'Adres', 'mariuszkowal-wordpress' ); ?></span>
                                            <p><?php echo esc_html( $contact_address ); ?></p>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ( $contact_working_hours ) : ?>
                                        <li>
                                            <span><?php esc_html_e( 'Godziny Pracy', 'mariuszkowal-wordpress' ); ?></span>
                                            <p><?php echo esc_html( $contact_working_hours ); ?></p>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if ( $contact_form_shortcode ) : ?>
                            <div class="contact-column contact-form-wrapper">
                                <div class="contact-form contact-form--cf7">
                                    <?php echo do_shortcode( $contact_form_shortcode ); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>
<?php
get_footer();
