<?php
/*
  Plugin Name: Komoona Advertising - CPM Adverts
  Plugin URI: https://www.komoona.com/users/registration/platform/wordpress
  Description: <strong>To finish the installation</strong> click 'Activate' and select the position you'd like for 'Komoona Ads' to be displayed.
  Tags: ad, ads, advert, adverts, banner, banners, advertise, advertising, wordpress advertising, blog advertising, site advertising, display advertising, CPM advertising, adverts, make money, earn money, money, monetize, monetization
  Version: 2.0
  Author: The Komoona Team
  Author URI: http://www.komoona.com
 */

/*
  Copyright 2011 Komoona (email : Support@Komoona.com)

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define('KOMOONA_SERVER_URL', 'https://www.komoona.com/api/');
define('KOMOONA_PLUGIN_URL', plugin_dir_url(__FILE__));

// komoona functions
include_once dirname(__FILE__) . '/Komoona_Func.php';

// komoona post
include_once dirname(__FILE__) . '/Komoona_Post.php';
    
// get plug in type (ads\adsense)
$plugin_type = Kmn_Func::komoona_plugin_type(basename(__FILE__));

// define plugin version
define(strtoupper($plugin_type) . '_VERSION', '1.4');

// Update Komoona settings: post from the settings page
if($_POST[$plugin_type . '_hidden'] === 'Y') {

    Kmn_Post::komoona_plugin_update($plugin_type, '');
}

// Create new Komoona account
if($_POST[$plugin_type . '_create'] === 'Y') {

    // komoona rest client
    include_once dirname(__FILE__) . '/Komoona_Rest.php';

    Kmn_Post::komoona_create_account($plugin_type, '');
}

// Create new Komoona site
if($_POST[$plugin_type . '_add_site'] === 'Y') {

    // komoona rest client
    include_once dirname(__FILE__) . '/Komoona_Rest.php';

    Kmn_Post::komoona_add_site($plugin_type, '');
    
} // end of create new site post request

// Manage plug in from admin panel
if(is_admin()) {

    // create plugin configuration menu
    add_action('admin_menu', 'Kmn_Func::' . $plugin_type . '_plugin_init');

    $plugin = plugin_basename(__FILE__);

    // add a “Settings” link directly on the plugins page
    add_filter("plugin_action_links_$plugin", 'Kmn_Func::' . $plugin_type . '_settings_link');

    // 'install' section: register default options
    register_activation_hook(__FILE__, 'Kmn_Func::' . $plugin_type . '_activate');

    // 'un-install' section: remove plugin options from the data store
    register_uninstall_hook(__FILE__, 'Kmn_Func::' . $plugin_type . '_uninstall');
} // admin

// Komoona widget
include_once dirname(__FILE__) . '/Komoona_Widget.php';
add_action('widgets_init', 'Kmn_Func::' . $plugin_type . '_register_widget');

//end of file ?>