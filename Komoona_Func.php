<?php

if(!class_exists('Kmn_Func')) : // declare Kmn_Func class only if class has not been defined (working with Komoona Ads and Komoona AdSense plugin)

    class Kmn_Func {

        const KOMOONA_ADS = 'komoona_ads';
        const KOMOONA_ADSENSE = 'komoona_adsense';
        const KOMOONA_CPM = 'komoona_cpm';
        
        static public function komoona_plugin_type($basename) {
            $type = strtolower(str_replace('.php', '', $basename));
            return $type === Kmn_Func::KOMOONA_CPM ? Kmn_Func::KOMOONA_ADS : $type;
        }

        static public function komoona_ads_activate() {
            Kmn_Func::komoona_activate(Kmn_Func::KOMOONA_ADS);
        }

        static public function komoona_ads_uninstall() {
            Kmn_Func::komoona_uninstall(Kmn_Func::KOMOONA_ADS);
        }

        static public function komoona_ads_settings_link($links) {
            return Kmn_Func::komoona_settings_link(Kmn_Func::KOMOONA_ADS, $links);
        }

        static public function komoona_ads_plugin_init() {
            Kmn_Func::komoona_plugin_init(Kmn_Func::KOMOONA_ADS);
        }

        static public function komoona_ads_options_engugue() {
            Kmn_Func::komoona_options_engugue(Kmn_Func::KOMOONA_ADS);
        }

        static public function register_komoona_ads_settings() {
            Kmn_Func::register_komoona_settings(Kmn_Func::KOMOONA_ADS);
        }

        static public function komoona_ads_register_widget() {
            Kmn_Func::komoona_register_widget(Kmn_Func::KOMOONA_ADS);
        }

        static public function komoona_ads_plugin_options() {
            Kmn_Func::komoona_plugin_options(Kmn_Func::KOMOONA_ADS);
        }

        static public function komoona_adsense_activate() {
            Kmn_Func::komoona_activate(Kmn_Func::KOMOONA_ADSENSE);
        }

        static public function komoona_adsense_uninstall() {
            Kmn_Func::komoona_uninstall(Kmn_Func::KOMOONA_ADSENSE);
        }

        static public function komoona_adsense_settings_link($links) {
            return Kmn_Func::komoona_settings_link(Kmn_Func::KOMOONA_ADSENSE, $links);
        }

        static public function komoona_adsense_plugin_init() {
            Kmn_Func::komoona_plugin_init(Kmn_Func::KOMOONA_ADSENSE);
        }

        static public function komoona_adsense_options_engugue() {
            Kmn_Func::komoona_options_engugue(Kmn_Func::KOMOONA_ADSENSE);
        }

        static public function register_komoona_adsense_settings() {
            Kmn_Func::register_komoona_settings(Kmn_Func::KOMOONA_ADSENSE);
        }

        static public function komoona_adsense_register_widget() {
            Kmn_Func::komoona_register_widget(Kmn_Func::KOMOONA_ADSENSE);
        }

        static public function komoona_adsense_plugin_options() {
            Kmn_Func::komoona_plugin_options(Kmn_Func::KOMOONA_ADSENSE);
        }

        /**
         * get an array which indicate the current position of the ads and the current plugin type
         */
        static public function get_komoona_current_position() {
            $plugins = array(Kmn_Func::KOMOONA_ADS, Kmn_Func::KOMOONA_ADSENSE);
            foreach($plugins as $plugin) {
                if(strlen(get_option($plugin . '_position')) && strlen(get_option($plugin . '_komoona_tag'))) {
                    $position = get_option($plugin . '_position');
                    break;
                }
            }
            return array('position' => $position, 'plugin' => $plugin);
        }
        
        /**
         * Get the Komoona tag from WP data store
         * @return <string> Komoona tag 
         */
        static public function get_komoona_tag()
        {
            $position = Kmn_Func::get_komoona_current_position();
            
            $komoona_tag = get_option($position['plugin'] . '_komoona_tag');
            $komoona_tag = stripslashes($komoona_tag);
            
            return $komoona_tag;
        }

        /**
         * This action filter will echo the Komoona tag 
         */
        static public function echo_widget() {
            echo Kmn_Func::get_komoona_tag();
        }
        
        /**
         * This function is used to add the ads to a WordPress Filter
         * It adds the placement after the content of the Filter
         * @param type $content - The content to which we will add the script
         * @return the new edited content
         */
        static public function filter_widget_after_content($content) {
            return $content . Kmn_Func::get_komoona_tag();
        }
               
        /**
         * add and remove the actions and filters depending on current position of the ads
         */
        static public function get_widget_action()
        {
            $position = Kmn_Func::get_komoona_current_position();
            switch ($position['position'])
            {
                case 'HEADER':
                    remove_action('the_post', array('Kmn_Func','echo_widget'));
                    remove_action('wp_footer', array('Kmn_Func','echo_widget'));
                    remove_filter('the_content', array('Kmn_Func','filter_widget_after_content'));
                    remove_filter('the_title', array('Kmn_Func','filter_widget_after_content'));
                    add_action('wp_head', array('Kmn_Func','echo_widget'));
                    break;
                case 'FOOTER':
                    remove_action('wp_head', array('Kmn_Func','echo_widget'));
                    remove_action('the_post', array('Kmn_Func','echo_widget'));
                    remove_filter('the_content', array('Kmn_Func','filter_widget_after_content'));
                    remove_filter('the_title', array('Kmn_Func','filter_widget_after_content'));
                    add_action('wp_footer', array('Kmn_Func','echo_widget'));
                    break;
                case 'POST_TOP':
                    remove_action('wp_head', array('Kmn_Func','echo_widget'));
                    remove_action('wp_footer', array('Kmn_Func','echo_widget'));
                    remove_filter('the_content', array('Kmn_Func','filter_widget_after_content'));
                    remove_filter('the_title', array('Kmn_Func','filter_widget_after_content'));
                    add_action('the_post', array('Kmn_Func','echo_widget'));
                    break;
                case 'POST_BOTTOM':
                    remove_action('wp_head', array('Kmn_Func','echo_widget'));
                    remove_action('wp_footer', array('Kmn_Func','echo_widget'));
                    remove_action('the_post', array('Kmn_Func','echo_widget'));
                    remove_filter('the_title', array('Kmn_Func','filter_widget_after_content'));
                    add_filter('the_content', array('Kmn_Func','filter_widget_after_content'));
                    break;
                case 'BOTOOM_OF_TITLE':
                    remove_action('wp_head', array('Kmn_Func','echo_widget'));
                    remove_action('wp_footer', array('Kmn_Func','echo_widget'));
                    remove_action('the_post', array('Kmn_Func','echo_widget'));
                    remove_filter('the_content', array('Kmn_Func','filter_widget_after_content'));
                    add_filter('the_title', array('Kmn_Func','filter_widget_after_content'));
                    break;
                case 'SIDEBAR':
                    remove_action('wp_head', array('Kmn_Func','echo_widget'));
                    remove_action('wp_footer', array('Kmn_Func','echo_widget'));
                    remove_action('the_post', array('Kmn_Func','echo_widget'));
                    remove_filter('the_content', array('Kmn_Func','filter_widget_after_content'));
                    remove_filter('the_title', array('Kmn_Func','filter_widget_after_content'));
                    break;
                default:
                    break;
            }
        }
        
        /**
         * Get cURL extension version (if installed)
         */
        static public function get_curl_version() {
            $curl_version = NULL;

            $extensions = get_loaded_extensions();
            if($extensions) {
                foreach($extensions as $extension) {
                    if($extension === 'curl' && function_exists('curl_version')) {
                        $curl_version = curl_version();
                    }
                }
            }

            return $curl_version;
        }
        
        /**
         * Get the collection of installed Komoona plugins
         * @return <array> installed Komoona plugins
         */
        static public function komoona_installed_plugins()
        {
            $installed = array();
            
            $plugins = array(Kmn_Func::KOMOONA_ADS, Kmn_Func::KOMOONA_ADSENSE);
            foreach($plugins as $plugin) {
                if(strlen(get_option($plugin . '_username')) != 0 || strlen(get_option($plugin . '_komoona_tag')) != 0) {
                    $installed[$plugin] = $plugin;
                }
            }
            
            return $installed;
        }

        /**
         * Get the plugin name based on its type
         */
        static public function komoona_plugin_name($type) {
            $name = '';
            
            switch ($type) {
                case Kmn_Func::KOMOONA_ADS:
                    $name = 'Komoona Ads';
                    break;
                case Kmn_Func::KOMOONA_ADSENSE;
                    $name = 'Komoona AdSense Companion';
                    break;
                    break;
                default:
                    $name = 'Komoona Ads';
                    break;
            }
            
            return $name;
        }
        
        /**
         * Register Komoona plugin default options to the data store. This function is called when the
         * plug in is activate
         */
        static private function komoona_activate($type) {
            // add default options to the data store
            add_option($type . '_widget_layout_name', '');
            add_option($type . '_komoona_tag', '');
            add_option($type . '_username', '');
            add_option($type . '_position', 'SIDEBAR');
        }

        /**
         * Remove Komoona plugin options from the data store. This function is called when the
         * plug in is deactivate
         */
        static private function komoona_uninstall($type) {
            // remove plugin optioins from the data store
            delete_option($type . '_widget_layout_name');
            delete_option($type . '_komoona_tag');
            delete_option($type . '_username');
            delete_option($type . '_position');
            
            remove_action('wp_head', array('Kmn_Func','echo_widget'));
            remove_action('wp_footer', array('Kmn_Func','echo_widget'));
            remove_action('the_post', array('Kmn_Func','echo_widget'));
            remove_filter('the_content', array('Kmn_Func','filter_widget_after_content'));
            remove_filter('the_title', array('Kmn_Func','filter_widget_after_content'));
        }

        /**
         * Add settings link on plugin page
         */
        static private function komoona_settings_link($type, $links) {
            $settings_link = sprintf('<a href="options-general.php?page=%s_options">Settings</a>', $type);
            array_unshift($links, $settings_link);
            return $links;
        }

        /**
         * Init the Komoona plugin settings page
         */
        static private function komoona_plugin_init($type) {

            //call register settings function
            add_action('admin_init', 'Kmn_Func::register_' . $type . '_settings');

            $page_title = Kmn_Func::komoona_plugin_name($type);
            
            $menu_title = '';
            switch ($type) {
                case Kmn_Func::KOMOONA_ADS:
                    $menu_title = 'Komoona Ads';
                    break;
                case Kmn_Func::KOMOONA_ADSENSE;
                    $menu_title = 'Komoona AdSense';
                    break;
                default:
                    $menu_title = 'Komoona Ads';
                    break;
            }

            $page = add_options_page($title, $menu_title, 'manage_options', $type . '_options', 'Kmn_Func::' . $type . '_plugin_options');
            add_action('admin_print_styles-' . $page, 'Kmn_Func::' . $type . '_options_engugue');
            
           // load the language file
            load_plugin_textdomain('komoona', false, dirname(plugin_basename( __FILE__ ) ) . '/languages');
        }

        /**
         * Enqueue css and scripts from the Komoona options page
         */
        static private function komoona_options_engugue($type) {
            wp_register_style('komoona.min.css', KOMOONA_PLUGIN_URL . '/resources/komoona.min.css');
            wp_enqueue_style('komoona.min.css');
            wp_register_script('jquery.bt.js', KOMOONA_PLUGIN_URL . '/resources/jquery.bt.js', array ('jquery'));
            wp_enqueue_script('jquery.bt.js');
            wp_register_script('komoona.min.js', KOMOONA_PLUGIN_URL . '/resources/komoona.min.js', array ('jquery'));
            wp_enqueue_script('komoona.min.js');
        }

        /**
         * Register default pluign setting and its sanitization callback
         */
        static private function register_komoona_settings($type) {
            register_setting('komoona-settings-group', $type . '_komoona_tag');
            register_setting('komoona-settings-group', $type . '_position');
        }

        /**
         * @param type $type 
         */
        static private function komoona_register_widget($type) {
            $widget = '';

            switch ($type) {
                case Kmn_Func::KOMOONA_ADS:
                    $widget = 'Komoona_Ads_Widget';
                    break;
                case Kmn_Func::KOMOONA_ADSENSE;
                    $widget = 'Komoona_AdSense_Widget';
                    break;
                default:
                    $widget = 'Komoona_Ads_Widget';
                    break;
            }

            return register_widget($widget);
        }

        /**
         * Get the collection of supported ad positions
         * @param type $currency default selected
         */
        static private function komoona_get_positions($current_position) {
            echo '<option ' . ($current_position === 'SIDEBAR' ? 'selected="selected"' : '') .  ' value="SIDEBAR">' . translate('Page sidebar', 'komoona') . '</option>';
            echo '<option ' . ($current_position === 'HEADER' ? 'selected="selected"' : '') . ' value="HEADER">' . translate('Page header', 'komoona') . '</option>';
            echo '<option ' . ($current_position === 'FOOTER' ? 'selected="selected"' : '') . ' value="FOOTER">' . translate('Page footer', 'komoona') . '</option>';
            echo '<option ' . ($current_position === 'POST_TOP' ? 'selected="selected"' : '') . 'value="POST_TOP">' . translate('Top of post', 'komoona') . '</option>';
            echo '<option ' . ($current_position === 'POST_BOTTOM' ? 'selected="selected"' : '') . 'value="POST_BOTTOM">' . translate('Bottom of post', 'komoona') . '</option>';
            echo '<option ' . ($current_position === 'BOTOOM_OF_TITLE' ? 'selected="selected"' : '') . 'value="BOTOOM_OF_TITLE">' . translate('Below post title', 'komoona') . '</option>';
        }
        
        /**
         * Get the collection of supported currencies
         * @param type $currency default selected
         */
        static private function komoona_get_currencies($currency) {
            
            $currencies = array (
                'AUD' => 'Australian Dollar',
                'BRL' => 'Brazilian Real', // (only for Brazilian members)',
                'CAD' => 'Canadian Dollar',
                'CZK' => 'Czech Koruna',
                'DKK' => 'Danish Krone',
                'EUR' => 'Euro',
                'HKD' => 'Hong Kong Dollar',
                'HUF' => 'Hungarian Forint',
                'ILS' => 'Israeli New Shekel',
                'JPY' => 'Japanese Yen',
                'MYR' => 'Malaysian Ringgit', // (only for Malaysian members)',
                'MXN' => 'Mexican Peso',
                'NOK' => 'Norwegian Krone',
                'NZD' => 'New Zealand Dollar',
                'PHP' => 'Philippine Peso',
                'PLN' => 'Polish Zloty',
                'GBP' => 'British Pound',
                'SGD' => 'Singapore Dollar',
                'SEK' => 'Swedish Krona',
                'CHF' => 'Swiss Franc',
                'TWD' => 'New Taiwan Dollar',
                'THB' => 'Thai Baht',
                'TRY' => 'Turkish Lira', // (only for Turkish members)',
                'USD' => 'U.S. Dollar'
            );
            
            // render to page
            foreach ($currencies as $currency_key => $currency_name)
            {
                echo '<option ' . ($currency === $currency_key ? 'selected="selected"' : '') . ' value="' . $currency_key . '">' . translate($currency_name, 'komoona') . '</option>';
            }
        }
        
        /**
         * Get the list of supported ad units
		 * @param string $type plugin type
         * @param string $adsize default selected
         */
        static private function komoona_get_ad_size($type, $adsize) {
            echo '<option ' . ($adsize === '728x90' ? 'selected="selected"' : '') . ' value="728x90">' . translate('728x90', 'komoona') . '</option>';
            echo '<option ' . ($adsize === '160x600' ? 'selected="selected"' : '') . ' value="160x600">' . translate('160x600', 'komoona') . '</option>';
            echo '<option ' . ($adsize === '300x250' ? 'selected="selected"' : '') . ' value="300x250">' . translate('300x250', 'komoona') . '</option>';
        }

        /**
         * Create the Komoona options page
         */
        static private function komoona_plugin_options($type) {
            if(!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            ?>
            <div class="wrap">
            <?php
                // check if this plugin (or any Komoona plugin) are already installed in this WordPress instance
                $installed = Kmn_Func::komoona_installed_plugins();
                                
                // indicate the one of Komoona plugins is installed
                $komoona_installed = FALSE;
                
                // loop over all komoona plugins to find if already installed
                $plugins = array(Kmn_Func::KOMOONA_ADS, Kmn_Func::KOMOONA_ADSENSE);
                foreach($plugins as $plugin) {
                    if($plugin !== $type) {
                        $komoona_installed = $komoona_installed || isset ($installed[$plugin]);
                    }
                }
                
                $plugin_installed = isset ($installed[$type]);
                 
                if(!$plugin_installed && !$komoona_installed) : // Komoona not installed 
            ?>
                <div id="kmn-create-account">
                    <h2><?php _e('Create Komoona Account', 'komoona'); ?></h2>
                    <h4><?php _e('Hi there - Welcome to Komoona! Please provide the following details', 'komoona'); ?>:</h4>
                    <?php 
                        $curl = Kmn_Func::get_curl_version();
                        if(isset($curl)) : 
                    ?>
                        <a style="font-size: 100%;" onclick="return false;" href="javascript:void(0);" rel="kmn-account-exists"><?php _e('Already Have Komoona Account?', 'komoona'); ?></a>
                    <?php endif; ?>
                    <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" id="create-kmn-account">
                        <input type="text" id="kmn-registration-type" name="kmn_registration_type" style="display: none;" value="<?php echo $type; ?>" />
                        <?php 
                            settings_fields('komoona-registration-group');
                            $post = $_SERVER['REQUEST_METHOD'] === 'POST'; 
                        ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php _e('Your Email', 'komoona'); ?></th>
                                <?php
                                    if($post) {
                                        $username = $_POST['kmn_username'];
                                    }
                                    else {
                                        $current_user = wp_get_current_user();
                                        $username = $current_user->user_email;
                                    }
                                ?>
                                <td>
                                    <input type="text" id="kmn-username" name="kmn_username" style="width:250px;" tabindex="1" value="<?php echo $username; ?>" />
                                    <a id="kmn-username-error" rel="validation-error" title="<?php _e('Please insert valid email address', 'komoona'); ?>" href="javascript:void(0);" onclick="return false;"><img src="<?php echo KOMOONA_PLUGIN_URL; ?>resources/clear.png" alt="" height="21px" width="1px" border="0" /></a>
                                    <a onclick="return false;" href="javascript:void(0);" rel="help-tip" title="<?php _e('Your email will also serve as your Komoona username', 'komoona'); ?>">
                                        <img width="12px" height="12px" alt="" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/question.jpg'; ?>">
                                    </a>
                                </td>
                            </tr>
                            <?php
                                $curl = Kmn_Func::get_curl_version();
                                if($curl) :
                            ?>
                            <tr valign="top">
                                <th scope="row"><?php _e('Password'); ?></th>
                                <td>
                                    <input type="password" id="kmn-password" name="kmn_password" style="width:250px" tabindex="2" value="" autocomplete="off" />
                                    <a id="kmn-password-error" rel="validation-error" title="<?php _e('Your password must contain at least 7 characters.', 'komoona'); ?>" href="javascript:void(0);" onclick="return false;"><img src="<?php echo KOMOONA_PLUGIN_URL; ?>resources/clear.png" alt="" height="21px" width="1px" border="0" /></a>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if($type !== Kmn_Func::KOMOONA_ADSENSE) : ?>
                            <tr valign="top">
                                <th scope="row"><?php _e('Single Ad Unit Size', 'komoona'); ?></th>
                                <td>
                                <?php $adsize = $post ? $_POST['kmn_adsize'] : '300x250'; ?>
                                    <select id="kmn-adsize" name="kmn_adsize" tabindex="3" style="height:23px;width:250px;">
                                        <?php Kmn_Func::komoona_get_ad_size($type, $adsize); ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <table class="form-table">
                            <?php endif; ?>
                            <tr valign="top">
                                <th scope="row"><?php _e('Ads Position', 'komoona'); ?></th>
                                <td>
                                    <select class="kmn-position" tabindex="4" name="kmn_position" style="height:23px;width:250px;">
                                        <?php $current_position = get_option($type . '_position'); ?>
                                        <?php Kmn_Func::komoona_get_positions($current_position); ?>
                                    </select>
                                    <a onclick="return false;" href="javascript:void(0);" rel="help-tip" title="<?php _e('Select where on your site you want ads to display', 'komoona'); ?>">
                                        <img width="12px" height="12px" alt="" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/question.jpg'; ?>">
                                    </a>
                                    <p class="kmn-position-msg" style="background-color:#ffd;border:1px solid #E6DB55;padding:0 5px;margin:5px 0 0;display:none;width:450px;"><?php _e('To place'); ?> <?php echo Kmn_Func::komoona_plugin_name($type); ?> <?php _e('on sidebar, open the', 'komoona'); ?> <a href="widgets.php" target="_blank"><?php _e('Widgets', 'komoona'); ?></a> <?php _e('page after registration and drag and drop Komoona from the `Available Widget` list to your `Sidebar`.', 'komoona'); ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php if($type === Kmn_Func::KOMOONA_ADSENSE) : _e('AdSense RPM (optional)', 'komoona'); else : _e('Floor Price (optional)', 'komoona'); endif; ?></th>
                                <td>
                                    <input id="kmn-floor-price" name="kmn_floor_price" style="width:100px" tabindex="<?php echo ($type === Kmn_Func::KOMOONA_ADSENSE) ? 4 : 5; ?>" value="" autocomplete="off" />
                                    <a id="kmn-floor-price-error" rel="validation-error" title="" href="javascript:void(0);" onclick="return false;"><img src="<?php echo KOMOONA_PLUGIN_URL; ?>resources/clear.png" alt="" height="21px" width="1px" border="0" /></a>
                                    <a onclick="return false;" href="javascript:void(0);" rel="<?php echo ($type === Kmn_Func::KOMOONA_ADSENSE) ? 'rpm' : 'help' ;?>-tip" <?php echo $type !== Kmn_Func::KOMOONA_ADSENSE ? 'title="Komoona helps publishers maximize their income by setting a &lsquo;Floor Price&rsquo;. If our ads don&rsquo;t match or beat your &lsquo;Floor Price&rsquo; we will serve whatever tag you define as &lsquo;Pass-back tag&rsquo; (below)"' : 'title="Ad RPM"'; ?>>
                                        <img width="12px" height="12px" alt="" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/question.jpg'; ?>">
                                    </a>
                                    <?php if($type === Kmn_Func::KOMOONA_ADSENSE) : ?>
                                        <p id="rpm-help-text" style="display: none;">
                                            <?php _e('Komoona helps you earn higher income without creating new ad placemats on your page.', 'komoona'); ?><br/><?php _e('Set your current AdSense', 'komoona'); ?> <a href="https://support.google.com/adsense/bin/answer.py?hl=en&amp;answer=112032" target="_blank"><?php _e('&lsquo;Ad RPM&rsquo;', 'komoona'); ?></a> <?php _e('as the floor price and Komoona will replace AdSense only when we have ads that match or beat this floor – creating a true win-win situation.', 'komoona'); ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <?php if($type === Kmn_Func::KOMOONA_ADS): ?>
                                <th scope="row">
                                    <?php _e('Set your Pass-back script', 'komoona'); ?>
                                    <a style="margin-left:3px;" onclick="return false;" href="javascript:void(0);" rel="help-tip" title="<?php _e('If our ads don&rsquo;t  match or beat your &lsquo;Floor Price&rsquo; (above), Komoona will serve this &lsquo;Pass-back tag&rsquo;. Make sure to copy/paste the entire tag of the network you are currently using (i.e AdSense)', 'komoona'); ?>">
                                        <img width="12px" height="12px" alt="" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/question.jpg'; ?>">
                                    </a>
                                </th>
                                <?php else : ?>
                                <th scope="row"><?php _e('Paste Your Entire AdSense Code', 'komoona'); ?></th>
                                <?php endif; ?>
                                <td>
                                    <textarea id="kmn-adsense" name="kmn_adsense" style="float:left;width:500px;height:170px;" tabindex="<?php echo ($type === Kmn_Func::KOMOONA_ADSENSE) ? 5 : 6; ?>" class="code" cols="50" rows="10"></textarea>
                                    <a id="kmn-adsense-error" rel="validation-error" title="" href="javascript:void(0);" onclick="return false;"><img src="<?php echo KOMOONA_PLUGIN_URL; ?>resources/clear.png" alt="" height="50px" width="1px" border="0" /></a>
                                    <?php if($type === Kmn_Func::KOMOONA_ADSENSE): ?> 
                                        <a style="clear:left;float:left;font-size: 70%;" onclick="return false;" href="javascript:void(0);" rel="adsense-tip" title=""><?php _e('Where do I get the AdSense code?', 'komoona'); ?></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                        <br/>
                        <div style="width:300px;margin-top:10px;">
                            <input id="kmn-toc" type="checkbox" style="float: left;" value="toc" name="kmn_toc" checked="checked" />
                            <a id="kmn-toc-error" rel="validation-error" href="javascript:void(0);" onclick="return false;" title="<?php _e('You must agree to the Terms of Service to create an account', 'komoona'); ?>"><img src="<?php echo KOMOONA_PLUGIN_URL; ?>resources/clear.png" alt="" height="8px" width="1px" border="0" /></a>
                            <label for="kmn-toc" style="padding:0 5px;"><?php _e('I have read and agree to the', 'komoona'); ?></label>
                            <a target="_blank" href="https://www.komoona.com/komoona-tos"><?php _e('Terms of Service', 'komoona'); ?></a>
                        </div>
                        <div style="padding-top:5px;">
                            <input id="kmn-submit" type="submit" class="button-primary" value="<?php _e('Create', 'komoona'); ?>" />
                        </div>
                        <input type="hidden" name="<?php echo $type; ?>_create" value="Y" />
                    </form>
                    <!-- animation gif for ajax calls  -->
                    <div id="wait-div"  style="position:absolute;left:25%;top:20%;display:none;">
                        <img src="<?php echo KOMOONA_PLUGIN_URL . 'resources/ajax-loader.gif'; ?>" alt="" width="22px" height="22px" style="padding-left:90px;" />
                        <p style="font-weight:bold;"><?php _e('Please wait while we create your account', 'komoona'); ?></p>
                    </div>
                </div> <!-- komoona create account -->
            <?php Kmn_Func::komoona_add_tag($type); ?>        
            <?php else: 
                // The plugin is installed: show the options page 
                Kmn_Func::komoona_options_page($type);
            endif; // komoona is already installed ?>
            <?php if($type === Kmn_Func::KOMOONA_ADSENSE) : ?>
                <div id="adsense-help-text" style="display:none;width:480px;">
                    <img id="help-text-close" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/'; ?>modal_close.png" width="28px" height="28px" alt="close" style=" cursor:pointer;position: absolute;top:-2%;right: 0" onclick="jQuery('a[rel=adsense-tip]').btOff();" />
                    <div style="font-size: 85%;padding-bottom: 10px;width:470px;">
                        <p style="color: black;"><?php _e('To get your Google AdSense code, sign into your Google AdSense account and create new ad unit or edit an existing one.<br/><br/>The code should look similar to the image below:', 'komoona'); ?></p>
                    </div>
                    <img src="<?php echo KOMOONA_PLUGIN_URL . 'resources/'; ?>sense-code.jpg" width="470px" height="240px" alt="adsense code" class="" />
                    <div style="font-size: 70%;">
                        <a href="https://www.google.com/adsense/support" target="_blank"><?php _e('AdSense Help', 'komoona'); ?></a>
                    </div>
                </div>
            <?php endif; // adsense help    ?>
        </div> <!-- .wrapper -->
        <?php
        } // end of komoona_plugin_options
        
        /**
         * Render Installed plugin options page
         * @param <string> $type plugin type
         */
        static private function komoona_options_page($type) { ?>
            <h2><?php echo Kmn_Func::komoona_plugin_name($type) . ' Options'; ?></h2>
            <div class="kmn-advanced">
                <h3 class="kmn-advanced-header"><?php _e('Plugin Settings' , 'komoona'); ?></h3>
                <a style="float:right;top:5px;position:relative;" rel="kmn-expand-div" href="javascript:void(0);">
                    <img title="collapse" alt="collapse" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/'; ?>collapse.png">
                </a>
                <div id="kmn-advanced-settings" style="clear:both;">
                    <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                        <?php settings_fields('komoona-settings-group'); ?>
                        <table class="form-table">
                           <tr valign="top">
                                <th style="width:350px;" scope="row"><?php _e("Select where on your site you want ads to display", 'komoona'); ?></th>
                                <td>
                                    <select class="kmn-position" name="kmn_position" style="height:23px;width:150px;">
                                        <?php $current_position = get_option($type . '_position'); ?>
                                        <?php Kmn_Func::komoona_get_positions($current_position); ?>
                                    </select>
                                    <a onclick="return false;" href="javascript:void(0);" rel="help-tip" title="<?php _e('Select the position on your site to show ads', 'komoona'); ?>">
                                        <img width="12px" height="12px" alt="" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/question.jpg'; ?>">
                                    </a>
                                    <p class="kmn-position-msg" style="background-color:#ffd;border:1px solid #E6DB55;padding:0 5px;margin:5px 0 0;display:none;width:400px;"><?php _e('To place Komoona Ads on sidebar, open the', 'komoona'); ?> <a href="widgets.php" target="_blank"><?php _e('Widgets', 'komoona'); ?></a> <?php _e('page and drag and drop Komoona from the `Available Widget` list to your `Sidebar`.', 'komoona'); ?></p>
                                </td>
                            </tr> 
                        </table>
                        <table class="form-table">
                            <tr valign="top">
                                <th style="width:350px;" scope="row">
                                    <?php _e("Or copy the 'Komoona Tag' to your site's template code according to where you want the ads to display", 'komoona'); ?><br/>
                                </th>
                                <td>
                                    <textarea id="kmn-tag" name="kmn_tag" style="float:left;width:400px;height:170px;resize:none;" tabindex="2" class="code" cols="50" rows="10"><?php Kmn_Func::echo_widget(); ?></textarea>
                                    <p style="font-style:italic;font-size:80%;padding:0;margin:5px 0 0;">(<?php _e("Read our", 'komoona'); ?> <a target="_blank" href="https://www.komoona.com/support/how-to-implement-komoona"><?php _e('manual installation guide', 'komoona'); ?></a>)</p>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="<?php echo $type; ?>_hidden" value="Y" />
                        <p class="submit">
                            <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'komoona'); ?>" />
                        </p>
                    </form>
                </div>
                <br style="clear:both;"/>
            </div>
        <?php } // end of komoona_options_page
         
        static private function komoona_add_tag($type) { ?>
            <div id="kmn-add-tag"  style="display:none;">
                <h2><?php _e('Create New Komoona Tag', 'komoona'); ?></h2>
                <h4>
                    <?php _e('Before you can start using the Komoona plugin, you need to create new tag. Please provide the following details:', 'komoona'); ?>
                </h4>
                <a style="font-size: 100%;" onclick="return false;" href="javascript:void(0);" rel="kmn-create-account"><?php _e('Don\'t Have Account on Komoona?'); ?></a>
                <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" id="add-kmn-site">
                    <input type="text" id="kmn-a-registration-type" name="kmn_a_registration_type" style="display: none;" value="<?php echo $type; ?>" />
                    <?php settings_fields('komoona-registration-group'); ?>
                    <?php $post = $_SERVER['REQUEST_METHOD'] === 'POST'; ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e('Komoona Username'); ?></th>
                            <?php
                            if($post) {
                                $username = $_POST['kmn_username'];
                            }
                            else {
                                $current_user = wp_get_current_user();
                                $username = $current_user->user_email;
                            }
                            ?>
                            <td>
                                <input type="text" id="kmn-a-username" name="kmn_a_username" style="width:250px;" tabindex="1" value="<?php echo $username; ?>" />
                                <a id="kmn-a-username-error" rel="validation-error" title="<?php _e('Please insert valid email address', 'komoona'); ?>" href="javascript:void(0);" onclick="return false;"><img src="<?php echo KOMOONA_PLUGIN_URL; ?>resources/clear.png" alt="" height="21px" width="1px" border="0" /></a>
                                <a onclick="return false;" href="javascript:void(0);" rel="help-tip" title="<?php _e("Insert your existing Komoona username and password", 'komoona'); ?>">
                                    <img width="12px" height="12px" alt="" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/question.jpg'; ?>">
                                </a>
                            </td>
                        </tr>
                        <?php
                        $curl = Kmn_Func::get_curl_version();
                        if($curl) :
                            ?>
                            <tr valign="top">
                                <th scope="row"><?php _e('Password', 'komoona'); ?></th>
                                <td>
                                    <input type="password" id="kmn-a-password" name="kmn_a_password" style="width:250px" tabindex="2" value="" autocomplete="off" />
                                    <a id="kmn-a-password-error" rel="validation-error" title="<?php _e('Please insert a valid Komoona password', 'komoona'); ?>" href="javascript:void(0);" onclick="return false;"><img src="<?php echo KOMOONA_PLUGIN_URL; ?>resources/clear.png" alt="" height="21px" width="1px" border="0" /></a>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if($type !== Kmn_Func::KOMOONA_ADSENSE) : ?>
                            <tr valign="top">
                                <th scope="row"><?php _e('Single Ad Unit Size', 'komoona'); ?></th>
                                <td>
                                    <?php $adsize = $post ? $_POST['kmn_a_adsize'] : '300x250'; ?>
                                    <select id="kmn-a-adsize" name="kmn_a_adsize" tabindex="3" style="height:23px;width:250px;">
                                        <?php Kmn_Func::komoona_get_ad_size($type, $adsize); ?>
                                    </select>
                                </td>
                            </tr>
                    </table>
                    <table class="form-table">
                        <?php endif; ?>
                            <tr valign="top">
                                <th scope="row"><?php _e('Ads Position', 'komoona'); ?></th>
                                <td>
                                    <select class="kmn-position" tabindex="4" name="kmn_a_position" style="height:23px;width:250px;">
                                        <?php $current_position = get_option($type . '_position'); ?>
                                        <?php Kmn_Func::komoona_get_positions($current_position); ?>
                                    </select>
                                    <a onclick="return false;" href="javascript:void(0);" rel="help-tip" title="<?php _e('Select where on your site you want ads to display', 'komoona'); ?>">
                                        <img width="12px" height="12px" alt="" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/question.jpg'; ?>">
                                    </a>
                                    <p class="kmn-position-msg" style="background-color:#ffd;border:1px solid #E6DB55;padding:0 5px;margin:5px 0 0;display:none;width:450px;"><?php _e('To place'); ?> <?php echo Kmn_Func::komoona_plugin_name($type); ?> <?php _e('on sidebar, open the', 'komoona'); ?> <a href="widgets.php" target="_blank"><?php _e('Widgets', 'komoona'); ?></a> <?php _e('page after registration and drag and drop Komoona from the `Available Widget` list to your `Sidebar`.', 'komoona'); ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php $message = ($type === Kmn_Func::KOMOONA_ADSENSE) ? 'AdSense RPM (optional)' : 'Floor Price (optional)'; _e($message); ?></th>
                                <td>
                                    <input id="kmn-a-floor-price" name="kmn_a_floor_price" style="width:100px" tabindex="<?php echo ($type === Kmn_Func::KOMOONA_ADSENSE) ? 4 : 5; ?>" value="" autocomplete="off" />
                                    <a id="kmn-a-floor-price-error" rel="validation-error" title="" href="javascript:void(0);" onclick="return false;"><img src="<?php echo KOMOONA_PLUGIN_URL; ?>resources/clear.png" alt="" height="21px" width="1px" border="0" /></a>
                                    <a onclick="return false;" href="javascript:void(0);" rel="<?php echo ($type === Kmn_Func::KOMOONA_ADSENSE) ? 'rpm' : 'help' ;?>-tip" <?php echo $type !== Kmn_Func::KOMOONA_ADSENSE ? 'title="Komoona helps publishers maximize their income by setting a &lsquo;Floor Price&rsquo;. If our ads don&rsquo;t match or beat your &lsquo;Floor Price&rsquo; we will serve whatever tag you define as &lsquo;Pass-back tag&rsquo; (below)"' : 'title="Ad RPM"'; ?>>
                                        <img width="12px" height="12px" alt="" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/question.jpg'; ?>">
                                    </a>
                                    <?php if(($type === Kmn_Func::KOMOONA_ADSENSE)) : ?>
                                        <p id="rpm-help-text" style="display: none;">
                                            <?php _e('Komoona helps you earn higher income without creating new ad placemats on your page.', 'komoona'); ?><br/><?php _e('Set your current AdSense', 'komoona'); ?> <a href="https://support.google.com/adsense/bin/answer.py?hl=en&amp;answer=112032" target="_blank"><?php _e('&lsquo;Ad RPM&rsquo;', 'komoona'); ?></a> <?php _e('as the floor price and Komoona will replace AdSense only when we have ads that match or beat this floor – creating a true win-win situation.' , 'komoona'); ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <?php if($type !== Kmn_Func::KOMOONA_ADSENSE) : ?>
                                    <th scope="row">
                                        <?php _e('Set your Pass-back script', 'komoona'); ?>
                                        <a style="margin-left:3px;" onclick="return false;" href="javascript:void(0);" rel="help-tip" title="<?php _e('If our ads don&rsquo;t  match or beat your &lsquo;Floor Price&rsquo; (above), Komoona will serve this &lsquo;Pass-back tag&rsquo;. Make sure to copy/paste the entire tag of the network you are currently using (i.e AdSense)', 'komoona'); ?>">
                                            <img width="12px" height="12px" alt="" src="<?php echo KOMOONA_PLUGIN_URL . 'resources/question.jpg'; ?>">
                                        </a>
                                    </th>
                                <?php else : ?> 
                                    <th scope="row"><?php _e('Paste Your Entire AdSense Code', 'komoona'); ?></th>
                                <?php endif; ?>
                                <td>
                                    <textarea id="kmn-a-adsense" name="kmn_a_adsense" style="float:left;width:500px;height:170px;" tabindex="<?php echo ($type === Kmn_Func::KOMOONA_ADSENSE) ? 5 : 6; ?>" class="code" cols="50" rows="10"></textarea>
                                    <a id="kmn-a-adsense-error" rel="validation-error" title="" href="javascript:void(0);" onclick="return false;"><img src="<?php echo KOMOONA_PLUGIN_URL; ?>resources/clear.png" alt="" height="50px" width="1px" border="0" /></a>
                                    <?php if($type === Kmn_Func::KOMOONA_ADSENSE) : ?>
                                        <a style="clear:left;float:left;font-size: 70%;" onclick="return false;" href="javascript:void(0);" rel="adsense-tip" title=""><?php _e('Where do I get the AdSense code?'); ?></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                    </table>
                    <div style="padding-top:5px;">
                        <input id="kmn-a-submit" type="submit" class="button-primary" value="<?php _e('Create Tag', 'komoona'); ?>" />
                    </div>
                    <input type="hidden" name="<?php echo $type; ?>_add_site" value="Y" />
                </form>
                <div id="wait-div-a"  style="position:absolute;left:25%;top:20%;display:none;">
                    <img src="<?php echo KOMOONA_PLUGIN_URL . 'resources/ajax-loader.gif'; ?>" alt="" width="22px" height="22px" style="padding-left:90px;" />
                    <p style="font-weight:bold;"><?php _e('Please wait while we validate your account', 'komoona'); ?></p>
                </div>
            </div> <!-- kmn-add-tag -->
            <?php
        } // end of komoona_add_tag function       
    } // end of Kmn_Func class

endif; // Kmn_Func exists
if ( class_exists('Kmn_Func') ) {
        call_user_func(array('Kmn_Func','get_widget_action'));
}
// kmn func class
?>