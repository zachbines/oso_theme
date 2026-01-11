<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package oso_theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">

  <?php wp_head(); ?>
  <script>
  <?php $clientid = 'a0c3ad09e2fce0c8806ea93c22324f6df285e9fc'; //'d5ba1ecddf7bc67d3cbc89e297cc3329537cb706';
			  $token = 'fcd90c0a052c41b154b7065ee4256ab6'; //'50b67d3af3ef7f3d04a0d602c5fbb08d';
			  $secret = 'cDTvZ0JchTIsvAXboCTHrRFaHIXZl4UD643KEM107/1yx3P7ErizM3WzSZqEAG2RFrnJw6OH5OrJslH8JS2/Xj3802uGfDlZbqr+OgMrbZnt2InptURJ+GoAxQgMN3PQ'; //'26415lBaiF6NKwIy6/cEEWJIizBP7xxohjP5/f3g5kYiMPUAVQbMw/0vcgxlGRossgRD/i8Q9rAFkqbNAg3XyVSbVRtCrn1kHCMKq2bObeUfllHHRNnujso6mOpcOKRG'; 
			  $creds = $clientid.':'.$secret;
			  $encoded_creds = base64_encode($creds); 
			  $vimeo_user_id = '121740749';
		?>
  window.creds = "<?= $encoded_creds ?>";
  window.token = "<?= $token ?>";
  window.vimeo_uid = "<?= $vimeo_user_id ?>";
  </script>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'oso_theme' ); ?></a>

    <header id="masthead" class="site-header">
      <video class="header-video" id="headerVideo1" muted playsinline loop>
        <source src="/wp-content/uploads/2026/01/OSOA_WAVE_BLACK_02-compressed.mp4" type="video/mp4">
      </video>
      <video class="header-video" id="headerVideo2" muted playsinline loop>
        <source src="/wp-content/uploads/2026/01/OSOA_WAVE_BLACK_02-compressed.mp4" type="video/mp4">
      </video>
      <div class="header-content">
        <div class="site-branding">
          <div id="headerLogoImage" class="header-logo-image">
            <?php the_custom_logo(); ?>
          </div>
          <?php 
          if ( is_front_page() && is_home() ) :
          ?>
          <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
              rel="home"><?php bloginfo( 'name' ); ?></a></h1>
          <?php
				else :
					?>
          <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"
              rel="home"><?php bloginfo( 'name' ); ?></a></p>
          <?php
				endif;
				$oso_theme_description = get_bloginfo( 'description', 'display' );
				if ( $oso_theme_description || is_customize_preview() ) :
					?>
          <p class="site-description">
            <?php echo $oso_theme_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
          <?php endif; ?>
        </div><!-- .site-branding -->
        <nav id="site-navigation" class="main-navigation">
          <button class="menu-toggle" aria-controls="primary-menu"
            aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'oso_theme' ); ?></button>
          <?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
					)
				);
				?>
        </nav><!-- #site-navigation -->
      </div>
    </header><!-- #masthead -->