<?php
/* 
 * Plugins Language Switcher plugin Settings form
 * Author: Vladimir Garagulya vladimir@shinephp.com
 */

if (!defined('PLUGINS_LANG_SWITCH_PLUGIN_URL')) {
  die;  // Silence is golden, direct call is prohibited
}


$pluginslangswitch_languages = pluginslangswitch_get_plugins_languages();

$pluginslangswitch_language = get_option('plugins_lang_switch_language', 'en_EN');


function pluginslangswitch_displayBoxStart($title, $style='') {
?>
			<div class="postbox" style="<?php echo $style; ?>" >
				<h3 style="cursor:default;"><span><?php echo $title; ?></span></h3>
				<div class="inside">
<?php
}
// 	end of pluginslangswitch_displayBoxStart()


function pluginslangswitch_displayBoxEnd() {
?>
				</div>
			</div>
<?php
}
// end of pluginslangswitch_displayBoxEnd()


$mess = '';
$shinephpFavIcon = PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/vladimir.png';

?>
  <form method="post" action="options.php">
				<div id="poststuff" class="metabox-holder">
					<div class="has-sidebar" >
						<div id="post-body-content" class="has-sidebar-content">

<?php
    settings_fields('plugins-lang-switch-options');
?>

						<div id="post-body-content" class="has-sidebar-content">
<?php
	pluginslangswitch_displayBoxStart(__('Select plugins interface back-end language', 'plugins-lang-switch'));
?>
              <select name="plugins_lang_switch_language" id="plugins_lang_switch_language">
<?
  foreach ($pluginslangswitch_languages as $key=>$lang) {
?>
                <option value="<?php echo $key; ?>" <? echo pluginslangswitch_optionSelected($pluginslangswitch_language, $key); ?> ><?php echo $lang; ?></option>
<?php
  }
?>
              </select>
<?php
  pluginslangswitch_displayBoxEnd();
?>
      <div class="fli submit" style="padding-top: 0px;">
          <input type="submit" name="submit" value="<?php _e('Update', 'plugins-lang-switch'); ?>" title="<?php _e('Save Changes', 'plugins-lang-switch'); ?>" />
      </div>
			</div>
						<div id="pls-about" style="clear: both;">
									<?php pluginslangswitch_displayBoxStart(__('About this Plugin:', 'plugins-lang-switch'),'float: left; display: block; width: 200px;'); ?>
											<a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo $shinephpFavIcon; ?>);" target="_blank" href="http://www.shinephp.com/"><?php _e("Author's website", 'plugins-lang-switch'); ?></a>
											<a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/plswitch-icon.png'; ?>" target="_blank" href="http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/"><?php _e('Plugin webpage', 'plugins-lang-switch'); ?></a>
											<a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/changelog-icon.png'; ?>);" target="_blank" href="http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/#changelog"><?php _e('Changelog', 'plugins-lang-switch'); ?></a>
											<a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/faq-icon.png'; ?>)" target="_blank" href="http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/#faq"><?php _e('FAQ', 'plugins-lang-switch'); ?></a>
                      <a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/donate-icon.png'; ?>)" target="_blank" href="http://www.shinephp.com/donate"><?php _e('Donate', 'plugins-lang-switch'); ?></a>
									<?php pluginslangswitch_displayBoxEnd(); ?>
									<?php pluginslangswitch_displayBoxStart(__('Greetings:','plugins-lang-switch'),'float: left; display: inline; margin-left: 10px; width: 300px;'); ?>
                      <a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo $shinephpFavIcon; ?>);" target="_blank" title="<?php _e("It's me, the author", 'plugins-lang-switch'); ?>" href="http://www.shinephp.com/">Vladimir</a>
                      <a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/rene.png'; ?>);" target="_blank" title="<?php _e('for the help with Dutch translation', 'thankyou'); ?>" href="http://wpwebshop.com">Rene</a>
                      <a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/christian.png'; ?>);" target="_blank" title="<?php _e("For the help with German translation",'pgc');?>" href="http://www.irc-junkie.org">Christian</a>
                      <a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/myfox.png'; ?>);" target="_blank" title="<?php _e("For the help with Italian translation",'pgc');?>" href="http://www.myfox.org">Maurizio</a>
									<?php _e('Do you wish to see your name with link to your site here? You are welcome! Your help with translation and new ideas are very appreciated.', 'plugins-lang-switch');
									pluginslangswitch_displayBoxEnd();
                  pluginslangswitch_displayBoxStart(__('More plugins from','plugins-lang-switch').' <a href="http://www.shinephp.com" title="ShinePHP.com">ShinePHP.com</a>', 'float: left; display: inline; margin-left: 10px; width: 350px;');
      if (file_exists(ABSPATH.WPINC.'/rss.php')) {
        include_once(ABSPATH.WPINC.'/rss.php');
        $rss = fetch_rss('http://www.shinephp.com/category/shinephp-wordpress-plugins/feed/');
        if ($rss && $rss->items && sizeof($rss->items) > 0) {
          echo '<ul>';
          foreach ((array) $rss->items as $item) {
            $title = htmlentities($item['title'], ENT_QUOTES, "UTF-8");
            $link = $item['link'];
            echo '<li><a href="'.$link.'">'.$title.'</a></li>';
          }
          echo '</ul>';
        } else {
          echo '<ul><li>'.__('No items found.', 'plugins-lang-switch') . '</li></ul>';
        }
        echo '<hr/>';
        echo '<span style="font-size: 12px; font-weight: bold;">'.__('Recent Posts:','plugins-lang-switch').'</span><br/>';
        $rss = fetch_rss('http://feeds.feedburner.com/shinephp');
        if ($rss && $rss->items && sizeof($rss->items) > 0) {
          echo '<ul>';
          $rss->items = array_slice($rss->items, 0, 5);
          foreach ((array) $rss->items as $item) {
            $title = htmlentities($item['title'], ENT_QUOTES, "UTF-8");
            $link = $item['link'];
            $date = date('F j, Y', strtotime($item['pubdate']));
            echo '<li><a href="'.$link.'">'.$title.'</a>&ndash; <span class="rss-date">'.$date.'</span></li>';
          }
          echo '</ul>';
        } else {
          echo '<ul><li>'.__('No items found.', 'plugins-lang-switch') . '</li></ul>';
        }
      }
      pluginslangswitch_displayBoxEnd();
?>
						</div>

  </div>
  </div>
  </div>
    </form>

