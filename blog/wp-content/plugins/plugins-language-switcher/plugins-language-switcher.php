<?php
/*
Plugin Name: Plugins Language Switcher
Plugin URI: http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/
Description: It changes language locale value according to your selection for plugins interface at admin back-end only.
Version: 1.0.2
Author: Vladimir Garagulya
Author URI: http://www.shinephp.com
Text Domain: plugins-lang-switch
Domain Path: ./lang/
*/

/*
Copyright 2010  Vladimir Garagulya  (email: vladimir@shinephp.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if (!function_exists("get_option")) {
  die;  // Silence is golden, direct call is prohibited
}

global $wp_version;

$exit_msg = __('Plugins Language Switcher requires WordPress 2.9 or newer.','plugins-lang-switch').'<a href="http://codex.wordpress.org/Upgrading_WordPress">'.__('Please update!','plugins-lang-switch').'</a>';

if (version_compare($wp_version,"2.9","<")) {
	return ($exit_msg);
}

require_once('plugins-lang-switch_lib.php');

load_plugin_textdomain('plugins-lang-switch','', $pluginslangswitch_PluginDirName.'/lang');


function pluginslangswitch_optionsPage() {

  if (!current_user_can('activate_plugins')) {
    die('action is forbidden');
  }

  global $pluginslangswitch_siteURL;

  $pluginslangswitch_language = get_option('plugins_lang_switch_language');
  
?>

<div class="wrap">
  <div class="icon32" id="icon-options-general"><br/></div>
    <h2><?php _e('Plugins Language Switcher', 'plugins-lang-switch'); ?></h2>
		<?php require_once('plugins-lang-switch_options.php'); ?>
  </div>
<?php

}
// end of pluginslangswitch_optionsPage()


// Install plugin
function pluginslangswitch_install() {
	
  add_option('plugins_lang_switch_language', WPLANG);
  
}
// end of pluginslangswitch_install()


function pluginslangswitch_init() {

  if (function_exists('register_setting')) {
    register_setting('plugins-lang-switch-options', 'plugins_lang_switch_language');
  }
}
// end of pluginslangswitch_init()


function pluginslangswitch_action_links($links, $file) {
    if ($file == plugin_basename(dirname(__FILE__).'/plugins-language-switcher.php')){
        $settings_link = "<a href='options-general.php?page=plugins-language-switcher.php'>".__('Settings','plugins-lang-switch')."</a>";
        array_unshift( $links, $settings_link );
    }
    return $links;
}
// end of pluginslangswitch_action_links


function pluginslangswitch_row_meta($links, $file) {
  if ($file == plugin_basename(dirname(__FILE__).'/plugins-language-switcher.php')){
		$links[] = '<a target="_blank" href="http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/#changelog">'.__('Changelog', 'plugins-lang-switch').'</a>';
	}
	return $links;
} // end of pluginslangswitch_row_meta


function pluginslangswitch_adminCssAction() {

  wp_enqueue_style('pluginslangswitch_admin_css', PLUGINS_LANG_SWITCH_PLUGIN_URL.'/css/plugins-lang-switch.css', array(), false, 'screen');

}
// end of pluginslangswitch_adminCssAction()


function pluginslangswitch_settings_menu() {
	if ( function_exists('add_options_page') ) {
    $pluginslangswitch_page = add_options_page('Plugins Language Switcher', 'Plugins Language Switcher', 9, basename(__FILE__), 'pluginslangswitch_optionsPage');
		add_action( "admin_print_styles-$pluginslangswitch_page", 'pluginslangswitch_adminCssAction' );
	}
}
// end of pluginslangswitch_settings_menu()


function pluginslangswitch_locale($locale) {

  $pluginslangswitch_language = get_option('plugins_lang_switch_language');
  if (isset($pluginslangswitch_language) && $pluginslangswitch_language) {
    return $pluginslangswitch_language;
  } else {
    return $locale;
  }
}
// end of pluginslangswitch_locale()

if (is_admin()) {
  // activation action
  register_activation_hook(__FILE__, "pluginslangswitch_install");

  add_action('admin_init', 'pluginslangswitch_init');
  // add a Settings link in the installed plugins page
  add_filter('plugin_action_links', 'pluginslangswitch_action_links', 10, 2);
  add_filter('plugin_row_meta', 'pluginslangswitch_row_meta', 10, 2);
  add_action('admin_menu', 'pluginslangswitch_settings_menu');

  $pluginslangswitch_language = get_option('plugins_lang_switch_language', 'en_EN');

  add_filter('locale', 'pluginslangswitch_locale', 10, 2);
}


?>
