<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package oso_theme
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="footer-top">
				<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php dynamic_sidebar( 'footer-2' ); ?>
				<section id="spacer"></section>
				<?php dynamic_sidebar( 'footer-3' ); ?>
		</div>
		<div class="footer-bottom">
			<?php the_custom_logo(); ?>
			<div class="site-info">
					<?php
					/* translators: 1: Theme name, 2: Theme author. */
					printf( esc_html__( 'Copyright &copy; %1$s %2$s', 'oso_theme' ), date("Y"), 'OSO' );
					?>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
