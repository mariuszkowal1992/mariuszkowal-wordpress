<?php
/**
 * Theme footer.
 *
 * @package MARIUSZKOWAL_WordPress
 */

$cookie_notice_text               = function_exists( 'get_field' ) ? get_field( 'cookie_notice_text', 'option' ) : '';
$cookie_notice_privacy            = function_exists( 'get_field' ) ? get_field( 'cookie_notice_privacy_link', 'option' ) : '';
$cookie_notice_accept_button_text = function_exists( 'get_field' ) ? get_field( 'cookie_notice_accept_button_text', 'option' ) : '';
$cookie_notice_reject_button_text = function_exists( 'get_field' ) ? get_field( 'cookie_notice_reject_button_text', 'option' ) : '';
$cookie_notice_privacy_url        = '';

if ( is_array( $cookie_notice_privacy ) && ! empty( $cookie_notice_privacy['url'] ) ) {
	$cookie_notice_privacy_url = $cookie_notice_privacy['url'];
} elseif ( is_string( $cookie_notice_privacy ) ) {
	$cookie_notice_privacy_url = $cookie_notice_privacy;
}

?>

<?php if ( $cookie_notice_text ) : ?>
	<div class="cookie-notice" data-cookie-notice hidden>
		<div class="cookie-notice__content">
			<p><?php echo wp_kses_post( $cookie_notice_text ); ?></p>

			<div class="cookie-notice__actions">
				<?php if ( $cookie_notice_privacy_url ) : ?>
					<a class="button button--outline" href="<?php echo esc_url( $cookie_notice_privacy_url ); ?>">
						<?php esc_html_e( 'Polityka Prywatności', 'mariuszkowal-wordpress' ); ?>
					</a>
				<?php endif; ?>

				<button class="button button--outline" type="button" data-cookie-notice-reject>
					<?php echo esc_html( $cookie_notice_reject_button_text ? $cookie_notice_reject_button_text : __( 'NIE AKCEPTUJĘ', 'mariuszkowal-wordpress' ) ); ?>
				</button>

				<button class="button button--primary" type="button" data-cookie-notice-accept>
					<?php echo esc_html( $cookie_notice_accept_button_text ? $cookie_notice_accept_button_text : __( 'AKCEPTUJĘ', 'mariuszkowal-wordpress' ) ); ?>
				</button>
			</div>
		</div>
	</div>
<?php endif; ?>

<button class="scroll-top" type="button" aria-label="<?php esc_attr_e( 'Przewiń do góry', 'mariuszkowal-wordpress' ); ?>" data-scroll-top hidden>
	<i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
</button>

<footer id="colophon" class="site-footer">
	<p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
</footer>

<?php wp_footer(); ?>
</body>
</html>
