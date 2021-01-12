<?php
/*
   Plugin Name: Content Powered Call to Action
   Plugin URI: https://www.contentpowered.com
   Version: 3.0
   Author: Content Powered
   Description: A powerful and versatile call to action plugin that enables you to promote specific products or services for each individual blog post. Licensed to clients of Content Powered.
   Text Domain: content-powered-call-to-action
   License: GPLv3
  */

/*
    This following part of this file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

$ContentPoweredCallToAction_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function ContentPoweredCallToAction_noticePhpVersionWrong() {
    global $ContentPoweredCallToAction_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Content Powered Call to Action" requires a newer version of PHP to be running.',  'content-powered-call-to-action').
            '<br/>' . __('Minimal version of PHP required: ', 'content-powered-call-to-action') . '<strong>' . $ContentPoweredCallToAction_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'content-powered-call-to-action') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function ContentPoweredCallToAction_PhpVersionCheck() {
    global $ContentPoweredCallToAction_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $ContentPoweredCallToAction_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'ContentPoweredCallToAction_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function ContentPoweredCallToAction_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('content-powered-call-to-action', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// Initialize i18n
add_action('plugins_loadedi','ContentPoweredCallToAction_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (ContentPoweredCallToAction_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('content-powered-call-to-action_init.php');
    ContentPoweredCallToAction_init(__FILE__);
}
