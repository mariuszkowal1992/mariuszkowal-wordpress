<?php
/**
 * Comments template.
 *
 * @package MARIUSZKOWAL_WordPress
 */

if ( post_password_required() ) {
	return;
}

if ( ! function_exists( 'mariuszkowal_wordpress_comment' ) ) {
	function mariuszkowal_wordpress_comment( $comment, $args, $depth ) {
		$comment_icon = $comment->user_id === (int) get_post_field( 'post_author', get_the_ID() ) ? 'fa-solid fa-user-gear' : 'fa-solid fa-user';
		?>

		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<article class="comment-body">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<span class="avatar">
							<i class="<?php echo esc_attr( $comment_icon ); ?>" aria-hidden="true"></i>
						</span>
						<b class="fn"><?php echo esc_html( get_comment_author() ); ?></b>
					</div>

					<div class="comment-metadata">
						<time datetime="<?php echo esc_attr( get_comment_time( 'c' ) ); ?>">
							<?php
							printf(
								esc_html__( '%1$s o %2$s', 'mariuszkowal-wordpress' ),
								esc_html( get_comment_date( 'j F Y' ) ),
								esc_html( get_comment_time( 'H:i' ) )
							);
							?>
						</time>
					</div>
				</footer>

				<div class="comment-content">
					<?php comment_text(); ?>
				</div>

				<div class="reply">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'reply_text' => __( 'Odpowiedz', 'mariuszkowal-wordpress' ),
							)
						)
					);
					?>
				</div>
			</article>
		<?php
	}
}
?>

<section class="comments-area" id="comments">
	<?php if ( have_comments() ) : ?>
		<h2><?php esc_html_e( 'Komentarze', 'mariuszkowal-wordpress' ); ?></h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 0,
					'callback'    => 'mariuszkowal_wordpress_comment',
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => __( 'Poprzednie komentarze', 'mariuszkowal-wordpress' ),
				'next_text' => __( 'Następne komentarze', 'mariuszkowal-wordpress' ),
			)
		);
		?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'          => __( 'Dodaj Komentarz', 'mariuszkowal-wordpress' ),
			'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'    => '</h3>',
			'class_form'           => 'comment-form',
			'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Twój adres e-mail nie zostanie opublikowany. Wymagane pola są oznaczone gwiazdką.', 'mariuszkowal-wordpress' ) . '</p>',
			'comment_field'        => '<label for="comment-text">' . esc_html__( 'Komentarz *', 'mariuszkowal-wordpress' ) . '</label><textarea id="comment-text" name="comment" rows="7" required></textarea>',
			'submit_button'        => '<button class="button button--primary" name="%1$s" type="submit" id="%2$s">%4$s</button>',
			'label_submit'         => __( 'DODAJ KOMENTARZ', 'mariuszkowal-wordpress' ),
		)
	);
	?>
</section>
