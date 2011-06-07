<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <link rel="stylesheet" href="blueprint/screen.css" media="screen"/>
  <link rel="stylesheet" href="blueprint/print.css" media="print"/>
  <link rel="stylesheet" href="css/css.css" />
  
  <link rel="shortcut icon" type="image/x-icon" href="images/icon1.png" />
  <!--[if IE]>
  <link rel="stylesheet" href="blueprint/ie.css" media="projection,screen"/>
  <![endif]-->
  <script src="js/jquery-1.5.js" type="text/javascript"></script>
  <script type="text/javascript" >
  $(document).ready(function() {
    $(".tab-content").hide();
    $("ul.tab li:first").addClass("active").show(); 
    $(".tab-content:first").show(); 
    $("ul.tab li").click(function() {
      $("ul.tab li").removeClass("active"); 
      $(this).addClass("active"); 
      $(".tab-content").hide(); 
      var activeTab = $(this).find("a").attr("href"); 
      $(activeTab).fadeIn();
      return false;
    });
  });
    function clearText(field){
      if (field.defaultValue == field.value) field.value = '';
      else if (field.value == '') field.value = field.defaultValue;
      } 
  </script>
  <script>
  function clearText(field){
  if (field.defaultValue == field.value) field.value = '';
  else if (field.value == '') field.value = field.defaultValue;
  }
  </script>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php //bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<!--
<div id="wrapper" class="hfeed">
	<div id="header">
		<div id="masthead">
			<div id="branding" role="banner">
				<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
				<<?php echo $heading_tag; ?> id="site-title">
					<span>
						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</span>
				</<?php echo $heading_tag; ?>>
				<div id="site-description"><?php bloginfo( 'description' ); ?></div>

				<?php
					// Check if this is a post or page, if it has a thumbnail, and if it's a big one
					if ( is_singular() && current_theme_supports( 'post-thumbnails' ) &&
							has_post_thumbnail( $post->ID ) &&
							( /* $src, $width, $height */ $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' ) ) &&
							$image[1] >= HEADER_IMAGE_WIDTH ) :
						// Houston, we have a new header image!
						echo get_the_post_thumbnail( $post->ID );
					elseif ( get_header_image() ) : ?>
						<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
					<?php endif; ?>
			</div><

			<div id="access" role="navigation">
			  <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
				<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentyten' ); ?>"><?php _e( 'Skip to content', 'twentyten' ); ?></a></div>
				<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
				<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
			</div>
		</div>
	</div>
-->
<div class="blog-header">
    <!--yrunhii blog header ehlel-->
	<div class="container">
            <!--header ehlel-->
            <div class="header">
                <!--top-header ehlel-->
                <div class="top-header">
                    <div class="logo"><a href="index.php"><img src="images/blog-logo.jpg"></a></div>
                    <div class="search-main">
                      <!--
                        <form action="" id="SearchForm">
                            <div>
                                <label>
                                <input type="text" value="ХАЙЛТ..." class="input">
                                </label>
                                <input type="image" src="images/input-img.gif" class="button">
                            </div>
                        </form>-->
                        <?php //get_search_form(); ?>
                        
                        <form id="SearchForm" action="http://localhost/Photostore/website/blog/" method="get" role="search">
                          <div>
                            <label>
                              <input class="input" type="text" name="s" onblur="clearText(this)" onfocus="clearText(this)" value="Search...">
                            </label>
                          <input type="image" src="images/input-img.gif" class="button">
                          </div>
                        </form>
                    </div>
                </div>
                <!--top-header tugsgul-->
                <!--menu ehlel-->
                <div class="menu">
                				<?php wp_nav_menu( array( 'container_class' => '', 'theme_location' => 'primary' ) ); ?>
                </div>
                <!--menu tugsgul-->
            </div>
            <!--header tugsgul-->
	 </div>
     <!--yrunhii blog header tugsgul-->
     </div>
	
