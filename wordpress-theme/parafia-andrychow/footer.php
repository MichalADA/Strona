<?php
/**
 * Stopka.
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
				<h2><?php bloginfo( 'name' ); ?></h2>
				<p><?php echo nl2br( esc_html( parafia_opt( 'adres' ) ) ); ?><br>
				<?php
				$tel = parafia_opt( 'telefon' );
				if ( $tel ) :
					?>
					<?php esc_html_e( 'tel.', 'parafia' ); ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $tel ) ); ?>"><?php echo esc_html( $tel ); ?></a>
				<?php endif; ?>
				</p>
			</div>
			<nav aria-label="<?php esc_attr_e( 'Menu w stopce', 'parafia' ); ?>">
				<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1, 'fallback_cb' => false ) ); ?>
			</nav>
			<div>
				<h2><?php esc_html_e( 'Kancelaria parafialna', 'parafia' ); ?></h2>
				<p><?php echo nl2br( esc_html( parafia_opt( 'kancelaria_godziny' ) ) ); ?></p>
			</div>
		</div>
		<hr class="rule">
		<p class="footer-legal">
			<?php if ( parafia_opt( 'tryb_demo' ) ) : ?>
				<?php esc_html_e( 'Wersja demonstracyjna — projekt nowej strony parafii. Serwis nie jest oficjalną stroną parafii.', 'parafia' ); ?>
			<?php endif; ?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
