<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package oso_theme
 */

 $issingleview = false;
 $isradioview = false;
 if (isset($_GET['id'])) {
    $issingleview = true;
    $v_id = $_GET['id'];

	if (str_contains($v_id,'soundcloud')){
		$isradioview = true;
		$feed_url = 'https://feeds.soundcloud.com/users/soundcloud:users:1117667029/sounds.rss';
		$content = file_get_contents($feed_url);
		
		$data = array();
	
		// Instantiate XML element
		$a = new SimpleXMLElement($content);
		foreach($a->channel->item as $entry) {
	
			$guid = (string)$entry->guid;
	
			if ($guid==$v_id){		
				// Your response in array
				$tracknum = explode('tracks/',$guid)[1];
				$radioresult = array(
					'track' => 'tracks/'.$tracknum,
					'title' => (string)$entry->title,
					'link' => (string)$entry->link,
					'desc' => (string)$entry->description
				);
				break;
				//echo "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
			}
		}
	}
 }

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
        <?php oso_theme_post_thumbnail(); ?>
        <div class="entry-header-row">
            <?php the_title( '<h1 class="entry-title '.$post->post_name.'"><a href="/index.php/work">', '</a></h1>' ); ?>
			
            <ul class="works-nav">
				<h3>Categories</h3>
				<li class="current"><a href="#" id="toggle-works-nav"></a></li>
            <?php
            $categories = get_terms( array(
                'taxonomy' => 'category',
                // 'orderby' => 'name',
                // 'order'   => 'ASC',
                'hide_empty' => false
            ) );
            
            foreach( $categories as $category ) {
                $category_link = sprintf( 
                    '<li><a href="%1$s" alt="%2$s">%3$s</a></li>',
                    esc_url( '/index.php/work/#'.$category->slug ),
                    esc_attr( sprintf( __( 'View all videos in %s', 'oso' ), $category->name ) ),
                    esc_html( $category->name )
                );
                echo $category_link;
            } ?>
            </ul>
        </div>
	</header><!-- .entry-header -->



	<div class="entry-content">


		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'oso_theme' ),
				'after'  => '</div>',
			)
		);
		?>
        <?php if ($issingleview) { ?>
			<h2 class="video_title"></h2>
			<?php if ($isradioview) { ?>
				<iframe width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/<?= $radioresult["track"] ?>&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=true&sharing=false"></iframe><div style="font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;"><!--a href="https://soundcloud.com/user-548729736" title="Oso Audio" target="_blank" style="color: #cccccc; text-decoration: none;">Oso Audio</a> · <a href="<?= $radioresult["link"] ?>" title="<?= $radioresult["title"] ?>" target="_blank" style="color: #cccccc; text-decoration: none;"><?= $radioresult["title"] ?></a--></div>
				<div class="video_details">
					<div class="video_description"><?= $radioresult["desc"] ?></div>
					<div class="video_tags"></div>
				</div>
			<?php } else { ?>
				<div id="v_feature" data-vimeo-id="<?= $v_id ?>" class="featurevideo"></div>
				<div class="video_details">
					<div class="video_description"></div>
					<div class="video_tags"></div>
				</div>
			<?php } ?>
		<?php } else { ?>
		    <div class="video_grid"></div>
            <div class="video_paging">
                <div><span class="video_showing">0</span> OF <span class="video_total">0</span></div>
                <button>See more</button>
            </div>
        <?php } ?>
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

<?php
	include 'js-templates.php';

	wp_enqueue_script( 'oso-theme-scripts', get_template_directory_uri() . '/js/works-scripts.js', array('wp-api'), _S_VERSION, true );
?>