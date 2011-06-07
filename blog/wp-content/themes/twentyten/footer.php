
	<div id="footer" role="contentinfo">

    <div class="container">
      
        	<div class="top-footer">
            	<div class="logo"><a href="index.php"><img src="images/logo.png"></a></div>
             
            </div>
            <div class="contents">
                	<div class="left">
                    	
                        	                        
                        <?php
                          // A second sidebar for widgets, just because.
                          if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>

                                <?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
                  

                          <?php endif; ?>
                      
                         <?php
                          // A second sidebar for widgets, just because.
                          if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>

                                <?php dynamic_sidebar( 'second-footer-widget-area' ); ?>


                        <?php endif; ?>
                    </div>
                    <div class="right">
                    	<?php
                          // A second sidebar for widgets, just because.
                          if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>

                                <?php dynamic_sidebar( 'third-footer-widget-area' ); ?>


                        <?php endif; ?>
                        
                         <?php
                          // A second sidebar for widgets, just because.
                          if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>

                                <?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>


                        <?php endif; ?>
                        
                    </div>
                </div>
                
                
        </div>
</div><!-- #wrapper -->
<!--
<?php
                  include('../database.php'); 
                  include('../functions.php');
                  //include('../config_public.php');
                  //$lang_path = "../language/";
                  //$ses_lang = $_SESSSION['lang'];
                  include("../language/English".".php");
                  //include('../footer.php');
                
                ?>-->
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
