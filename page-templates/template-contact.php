<?php
/**
 * Template Name: お問い合わせ
 *
 * 拠点マップ + 簡易フォーム（admin-post.php）。
 *
 * @package Minato
 */

get_header();

$status = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>
<main id="main" class="site-main">
	<div class="wrap contact-grid">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Contact', 'minato' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<div class="prose">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</div>
			<?php minato_map(); ?>
		</div>
		<div>
			<?php if ( 'sent' === $status ) : ?>
				<p class="notice notice--ok"><?php esc_html_e( '送信しました。内容を確認のうえご連絡します。', 'minato' ); ?></p>
			<?php elseif ( 'invalid' === $status || 'error' === $status ) : ?>
				<p class="notice notice--ng"><?php esc_html_e( '送信できませんでした。入力内容をご確認ください。', 'minato' ); ?></p>
			<?php endif; ?>
			<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="minato_contact">
				<?php wp_nonce_field( 'minato_contact', 'minato_contact_nonce' ); ?>
				<p>
					<label for="name"><?php esc_html_e( 'お名前', 'minato' ); ?></label>
					<input id="name" name="name" type="text" required>
				</p>
				<p>
					<label for="email"><?php esc_html_e( 'メール', 'minato' ); ?></label>
					<input id="email" name="email" type="email" required>
				</p>
				<p>
					<label for="message"><?php esc_html_e( '内容', 'minato' ); ?></label>
					<textarea id="message" name="message" rows="6" required></textarea>
				</p>
				<p>
					<button type="submit"><?php esc_html_e( '送信する', 'minato' ); ?></button>
				</p>
			</form>
		</div>
	</div>
</main>
<?php
get_footer();
