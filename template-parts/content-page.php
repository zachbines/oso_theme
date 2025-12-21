<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package oso_theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if (get_field('featurevideo')) { ?>
			<div class="featurewrapper">
				<div id="v_feature" data-vimeo-id="<?= get_field('featurevideo') ?>" data-vimeo-background="true" class="featurevideo"></div>
			</div>
			<div class="v_controls">
				<button id="v_playpause"></button>
				<button id="v_volume"></button>
			</div>
		<?php } else { ?>
			<?php oso_theme_post_thumbnail(); ?>
		<?php } ?>
		<?php if (is_front_page()) { ?>
		<span class="home-tagline"><em>CREATIVE Post audio</em> and <em>music</em><br><em>studio in</em> Toronto, Ontario.</span>
		<?php } ?>
	</header><!-- .entry-header -->



	<div class="entry-content">
		<?php if (get_field('featuretrio1')) { ?>
			<h1 class="entry-title <?php echo $post->post_name; ?>">
				Featured <em>Work</em>
			</h1>

			<div class="featured_grid" id="feature-grid" data-vlist="<?php
				if (get_field('featuretrio1')) { echo get_field('featuretrio1'); } 
				if (get_field('featuretrio2')) { echo ','.get_field('featuretrio2'); } 
				// if (get_field('featuretrio3')) { echo ','.get_field('featuretrio3'); } 
				?>"></div>

		<?php } ?>
		
		<h1 class="entry-title <?php echo $post->post_name; ?>">
			<?php $t = get_the_title(); 
			$pieces = explode(" ",$t);
			$last = array_pop($pieces);
			?>
			<?= implode(" ",$pieces) ?> <em><?= $last ?></em>
		</h1>

		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'oso_theme' ),
				'after'  => '</div>',
			)
		);
		?>
		<div class="video_grid"></div>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'oso_theme' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->


<?php if (is_front_page()) {
	include 'js-templates.php';

	// include jquery modal scripts.
	wp_enqueue_script( 'jquery-modal-script', "https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js", array('jquery'), '0.9.1', true );
	wp_enqueue_style( 'jquery-modal-style', "https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css", array(), '0.9.1' );
	
	wp_enqueue_script( 'oso-theme-scripts', get_template_directory_uri() . '/js/home-scripts.js', array('wp-api'), _S_VERSION, true );
} ?>