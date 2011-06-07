<?php
/**
 * The template for displaying Tag Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

	<div class="container">
    <div id="main">
      <?php get_sidebar(); ?>
      
        <div class="right-main">

          <h1 class="page-title"><?php
            printf( __( 'Tag Archives: %s', 'twentyten' ), '<span>' . single_tag_title( '', false ) . '</span>' );
          ?></h1>

            <?php
            /* Run the loop for the tag archive to output the posts
             * If you want to overload this in a child theme then include a file
             * called loop-tag.php and that will be used instead.
             */
             get_template_part( 'loop', 'tag' );
            ?>
        </div>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
