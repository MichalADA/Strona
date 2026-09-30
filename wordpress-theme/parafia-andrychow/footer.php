<?php
/**
 * Stopka — markup 1:1 z prototypu: logo w złocie, adres, dwie kolumny odnośników.
 *
 * @package Parafia
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="footer">
	<div class="wrap" style="padding-block:clamp(36px,5vw,56px)">
		<div class="grid grid-3">
			<div>
				<h2 class="sr-only"><?php echo esc_html( trim( parafia_opt( 'nazwa' ) . ' ' . parafia_opt( 'wezwanie' ) ) ); ?></h2>
				<img class="footer-logo" src="<?php echo esc_url( parafia_img( 'logo-stopka.png' ) ); ?>" alt="<?php esc_attr_e( 'Parafia św. Stanisława BM w Andrychowie', 'parafia' ); ?>" width="1200" height="477" loading="lazy" />
				<p style="margin:0;line-height:1.65"><?php parafia_the_address_lines(); ?><?php if ( parafia_opt( 'telefon' ) ) : ?><br /><?php parafia_the_phone(); ?><?php endif; ?></p>
			</div>
			<nav aria-label="<?php esc_attr_e( 'Stopka — parafia', 'parafia' ); ?>">
				<?php parafia_render_footer_nav( 'footer' ); ?>
			</nav>
			<nav aria-label="<?php esc_attr_e( 'Stopka — informacje', 'parafia' ); ?>">
				<?php parafia_render_footer_nav( 'footer_info' ); ?>
			</nav>
		</div>
		<hr class="rule" style="margin-block:var(--space-6)" />
		<p style="margin:0;font-size:15px;color:#bdb6b0">
			<?php if ( parafia_opt( 'tryb_demo' ) ) : ?>
				<?php esc_html_e( 'Wersja demonstracyjna — projekt nowej strony parafii. Serwis nie jest oficjalną stroną parafii.', 'parafia' ); ?>
			<?php else : ?>
				&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( trim( parafia_opt( 'nazwa' ) . ' ' . parafia_opt( 'wezwanie' ) ) ); ?> · <?php echo esc_html( parafia_opt( 'miejscowosc' ) ); ?>
			<?php endif; ?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
