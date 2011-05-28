<?php
/* 
 * Plugins Language Switcher plugin Lirary
 * general staff used in more than one .php file
 * Author: Vladimir Garagulya vladimir@shinephp.com
 */

if (! defined("WPLANG")) {
  die;  // Silence is golden, direct call is prohibited
}

$pluginslangswitch_siteURL = get_option( 'siteurl' );

// Pre-2.6 compatibility
if ( !defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', $pluginslangswitch_siteURL . '/wp-content' );
if ( !defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( !defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( !defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

$pluginslangswitch_PluginDirName = substr(dirname(__FILE__), strlen(WP_PLUGIN_DIR)+1, strlen(__FILE__)-strlen(WP_PLUGIN_DIR)-1);

define('PLUGINS_LANG_SWITCH_PLUGIN_URL', WP_PLUGIN_URL.'/'.$pluginslangswitch_PluginDirName);
define('PLUGINS_LANG_SWITCH_PLUGIN_DIR', WP_PLUGIN_DIR.'/'.$pluginslangswitch_PluginDirName);
define('PLUGINS_LANG_SWITCH_WP_PLUGINS_URL', $pluginslangswitch_siteURL.'/wp-admin');
define('PLUGINS_LANG_SWITCH_ERROR', 'Error is encountered');

$tmp = dirname(__FILE__);
if(strpos($tmp, '/', 0)!==false) {
	define('PLUGINS_LANG_SWITCH_WINDOWS_SERVER', false);
  define('PLUGINS_LANG_SWITCH_DIR_SLASH', '/');
} else {
	define('PLUGINS_LANG_SWITCH_WINDOWS_SERVER', true);
  define('PLUGINS_LANG_SWITCH_DIR_SLASH', '\\');
}


function pluginslangswitch_optionSelected($value, $etalon) {
  $selected = '';
  if ($value==$etalon) {
    $selected = 'selected="selected"';
  }

  return $selected;
}


function pluginslangswitch_scanPluginsDirectory($path, $all_languages, &$languages) {
  if (substr($path, strlen($path)-1, 1)!=PLUGINS_LANG_SWITCH_DIR_SLASH) {
    $path .= PLUGINS_LANG_SWITCH_DIR_SLASH;
  }
  $dir = @opendir($path);
  if ($dir) {
    while($fileName = readdir($dir)) {
      if ($fileName == '.' || $fileName == '..') {
        continue;
      }
      $ext = pathinfo($fileName, PATHINFO_EXTENSION);
      if ($ext=='mo') {
        $baseName = pathinfo($fileName, PATHINFO_BASENAME);
        $baseName = substr($baseName, 0, strlen($baseName)-strlen($ext)-1);
        $pos = strrpos($baseName, '-');
        if ($pos>0) {
          $langLocale = substr($baseName, $pos + 1);
          if (isset($all_languages[$langLocale])) {
            $languages[$langLocale] = $all_languages[$langLocale];
          }
        }
      } else {
        $fileName = $path.$fileName;
        if (is_dir($fileName)) {
          pluginslangswitch_scanPluginsDirectory($fileName, $all_languages, $languages);
        }
      }
    }
    closedir($dir);
  }
}
// end of pluginslangswitch_scanSite()



function pluginslangswitch_get_plugins_languages() {

$all_languages =
  array(
  'af'=>'Afrikaans',
  'al'=>'Albanian',
  'ar'=>'Arabic – عربي',
  'bn_BD'=>'Bangla - Bengali',
  'be_BY'=>'Belarusian - Беларуская - Biełaruskaja',
  'bs_BA'=>'Bosnian - Bosanski',
  'eu'=>'Basque - Euskara',
  'bg_BG'=>'Bulgarian - Български',
  'ca'=>'Catalan - Català',
  'zh_CN'=>'Chinese - 中文',
  'hr'=>'Croatian - Hrvatski',
  'cs_CZ'=>'Czech - Čeština',
  'da_DK'=>'Danish - Dansk',
  'nl_NL'=>'Dutch - Nederlands (nl_NL)',
  'eo'=>'Esperanto',
  'et'=>'Estonian - Eesti',
  'fo'=>'Faroese',
  'fi'=>'Finnish - Suomi',
  'fr_FR'=>'French - Français',
  'gl_ES'=>'Galician - Galego',
  'ge_GE'=>'Georgian',
  'zh_HK'=>'Hong Kong (香港)',
  'de_DE'=>'German - Deutsch',
  'el'=>'Greek',
  'en_En'=>'English',
  'fr_FR'=>'French',
  'he_IL'=>'Hebrew',
  'hi_IN'=>'Hindi',
  'hu_HU'=>'Hungarian - Magyar',
  'is_IS'=>'Icelandic',
  'id_ID'=>'Indonesian - Bahasa Indonesia',
  'it_IT'=>'Italian',
  'ja'=>'Japanese - 日本語',
  'km_KH'=>'Khmer(Cambodian Language)',
  'ko_KR'=>'Korean - 한국어',
  'lv'=>'Latvian (Latviešu)',
  'lt_LT'=>'Lithuanian - Lietuviškai',
  'mk_MK'=>'Macedonian - Македонски',
  'mg_MG'=>'Malagasy',
  'ms_MY'=>'Malay – Bahasa Melayu',
  'ni_ID'=>'Nias - Li Niha',
  'nb_NO'=>'Norwegian',
  'nn_NO'=>'Nynorsk',
  'fa_IR'=>'Persian',
  'pl_PL'=>'Polish - Polski',
  'pt_PT'=>'European Portuguese',
  'pt_BR'=>'Brazilian Portuguese',
  'ro_RO'=>'Romanian - Română',
  'ru_RU'=>'Russian - Русский',
  'sr_RS'=>'Serbian — Српски',
  'si_LK'=>'Sinhala',
  'sk_SK'=>'Slovak – Slovenčina',
  'sl_SI'=>'Slovenian - Slovenščina',
  'es_ES'=>'Spanish - Español',
  'su_ID'=>'Sundanese - Basa Sunda',
  'sv_SE'=>'Swedish - Svenska',
  'tg'=>'Tajik',
  'th'=>'Thai',
  'tr'=>'Turkish - Türkçe',
  'zh_TW'=>'Taiwan - 台灣',
  'uk'=>'Ukrainian - Українська',
  'uz_UZ'=>'Uzbek - O‘zbekcha',
  'vi'=>'Vietnamse - Tiếng Việt',
  'cy'=>'Welsh - Cymraeg'
    );

  $plugins_languages = array('en_En'=>'English');

  pluginslangswitch_scanPluginsDirectory(WP_PLUGIN_DIR, $all_languages, $plugins_languages);
  
  asort($plugins_languages);
  
  return $plugins_languages;
}
// end of pluginslangswitch_get_plugins_languages()

?>
