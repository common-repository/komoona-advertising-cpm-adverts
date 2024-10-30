<?php

if(!class_exists('Kmn_Post')) : // declare Kmn_Post class only if class has not been defined (working with Komoona Ads and Komoona AdSense plugin)

    class Kmn_Post {

         /**
         * Update pluing based on user settings from post
         * @param <string> $plugin_type: Komoona pluign type
         */
        static public function komoona_plugin_update($plugin_type, $plugin_id) {
            // widget ad unit snippet
            if(get_magic_quotes_gpc()) {
                $widget_code = stripslashes($_POST['kmn_tag']);
                $position = stripslashes($_POST['kmn_position']);
            }
            else {
                $widget_code = $_POST['kmn_tag'];
                $position = $_POST['kmn_position'];
            }

            // update options
            update_option($plugin_type . $plugin_id . '_komoona_tag', $widget_code);
            update_option($plugin_type . $plugin_id . '_position', $position);
        }

        /**
         * Create account on Komoona server based on user settings from post
         * @param <string> $plugin_type: Komoona pluign type
         */
        static public function komoona_create_account($plugin_type, $plugin_id) {
            
            $magic_quotes = get_magic_quotes_gpc();
    
            // new account parameters
            if ($magic_quotes) {
                $params = array (
                    'username' => stripslashes($_POST['kmn_username']), 
                    'direct'   => stripslashes($_POST['kmn_direct_sales']), 
                    'toc'      => stripslashes($_POST['kmn_toc']),
                    'siteurl'  => site_url(),
                    'paypal'   => stripslashes($_POST['kmn_paypal']),
                    'price'    => stripslashes($_POST['kmn_price']),
                    'position' => stripslashes($_POST['kmn_position']),
                    'floor_price' => stripslashes($_POST['kmn_floor_price']),
                    'currency' => stripslashes($_POST['kmn_currency']),
                    'type'     => $plugin_type, 
                    'version'  => constant(strtoupper($plugin_type) . '_VERSION')
                );
            }
            else {
                $params = array (
                    'username' => $_POST['kmn_username'], 
                    'direct'   => $_POST['kmn_direct_sales'], 
                    'toc'      => $_POST['kmn_toc'],
                    'siteurl'  => site_url(),
                    'paypal'   => $_POST['kmn_paypal'],
                    'price'    => $_POST['kmn_price'],
                    'position' => $_POST['kmn_position'],
                    'floor_price'  => $_POST['kmn_floor_price'],
                    'currency' => $_POST['kmn_currency'],
                    'type'     => $plugin_type, 
                    'version'  => constant(strtoupper($plugin_type) . '_VERSION')
                );
            }

            switch($plugin_type) {
                case Kmn_Func::KOMOONA_ADSENSE:
                    $params['adsense'] = $magic_quotes ? stripslashes($_POST['kmn_adsense']) : $_POST['kmn_adsense'];
                    break;
                case Kmn_Func::KOMOONA_ADS:
                    $params['adsize'] = $magic_quotes ? stripslashes($_POST['kmn_adsize']) : $_POST['kmn_adsize'];
                    $params['script'] = $magic_quotes ? stripslashes($_POST['kmn_adsense']) : $_POST['kmn_adsense'];
                    break;
                default:
                    $params['adsize'] = $magic_quotes ? stripslashes($_POST['kmn_adsize']) : $_POST['kmn_adsize'];
                    $params['script'] = $magic_quotes ? stripslashes($_POST['kmn_adsense']) : $_POST['kmn_adsense'];
                    break;
            }

            // call Komoona server - if curl supported, use ssl. else standard HTTP call
            $curl = Kmn_Func::get_curl_version();
            if(isset($curl)) {

                // server rest API destination
                $komoona_srv = parse_url(KOMOONA_SERVER_URL);

                // new account password
                $params['password'] = $magic_quotes ? stripslashes($_POST['kmn_password']) : $_POST['kmn_password'];

                try {
                    if(isset($komoona_srv['port'])) {
                        $port = $komoona_srv['port'];
                    }
                    else {
                        $port = $komoona_srv['scheme'] === 'https' ? 443 : 80;
                    }

                    $rest = Kmn_Rest::connect($komoona_srv['host'], $port, $komoona_srv['scheme'] === 'https' ? Kmn_Rest::HTTPS : Kmn_Rest::HTTP);

                    $method = $komoona_srv['path'] . 'swp_signup';
                    $pos = strpos($method, '/');
                    if($pos === 0) {
                        $method = substr_replace($method, '', $pos, 1);
                    }

                    // get result from Komoona server
                    $result = $rest->post($method, $params);

                    // validate results
                    $r = json_decode($result);
                    if($r->status === 'success') {
                        // update the plugin options
                        update_option($plugin_type . $plugin_id. '_komoona_tag', $r->komoona_tag);
                        update_option($plugin_type . $plugin_id. '_username', $params['username']);
                        update_option($plugin_type . $plugin_id. '_position', $params['position']);
                    }

                    // echo result to page
                    echo $result;
                }
                catch(Kmn_Rest_Exception $e) {
                    // show error 
                    echo json_encode(array ('error' => $e->__toString()));
                }

                die(); // this is required to return a proper result from the ajax call
            }
            else {

                try {
                    // direct call to server (random password will be set on server)
                    $server_url = str_replace('https://', 'http://', KOMOONA_SERVER_URL);
                    $result = Kmn_Rest::http_post($server_url . 'wp_signup', $params);

                    // validate results
                    $r = json_decode($result);
                    if($r->status === 'success') {

                        // update the plugin options
                        update_option($plugin_type . $plugin_id. '_komoona_tag', $r->komoona_tag);
                        update_option($plugin_type . $plugin_id. '_username', $params['username']);
                    }

                    // echo result to page
                    echo $result;
                }
                catch(Kmn_Rest_Exception $e) {
                    // show error 
                    echo json_encode(array ('error' => $e->__toString()));
                }

                die(); // this is required to return a proper result from the ajax call
            }
        }
        
        /**
         * Add new Komoona site based on user settings from post
         * @param type $plugin_type 
         */
        static public function komoona_add_site($plugin_type, $plugin_id) {

            $magic_quotes = get_magic_quotes_gpc();

            // new account parameters
            if($magic_quotes) {
                $params = array (
                    'username' => stripslashes($_POST['kmn_a_username']),
                    'direct'   => stripslashes($_POST['kmn_a_direct_sales']),
                    'position' => stripslashes($_POST['kmn_a_position']),
                    'floor_price' => stripslashes($_POST['kmn_a_floor_price']),
                    'siteurl'  => site_url(),
                    'price'    => stripslashes($_POST['kmn_a_price']),
                    'paypal'   => stripslashes($_POST['kmn_a_paypal']),
                    'currency' => stripslashes($_POST['kmn_a_currency']),
                    'type'     => $plugin_type,
                    'version'  => constant(strtoupper($plugin_type) . '_VERSION')
                );
            }
            else {
                $params = array (
                    'username' => $_POST['kmn_a_username'],
                    'direct'   => $_POST['kmn_a_direct_sales'],
                    'position' => $_POST['kmn_a_position'],
                    'floor_price' => $_POST['kmn_a_floor_price'],
                    'siteurl'  => site_url(),
                    'price'    => $_POST['kmn_a_price'],
                    'paypal'   => $_POST['kmn_a_paypal'],
                    'currency' => $_POST['kmn_a_currency'],
                    'type'     => $plugin_type,
                    'version'  => constant(strtoupper($plugin_type) . '_VERSION')
                );
            }

            switch($plugin_type) {
                case Kmn_Func::KOMOONA_ADSENSE:
                    $params['adsense'] = $magic_quotes ? stripslashes($_POST['kmn_a_adsense']) : $_POST['kmn_a_adsense'];
                    break;
                case Kmn_Func::KOMOONA_ADS:
                    $params['adsize'] = $magic_quotes ? stripslashes($_POST['kmn_a_adsize']) : $_POST['kmn_a_adsize'];
                    $params['cpm'] = $magic_quotes ? stripslashes($_POST['kmn_cpm']) : $_POST['kmn_a_cpm'];
                    $params['script'] = $magic_quotes ? stripslashes($_POST['kmn_a_adsense']) : $_POST['kmn_a_adsense'];
                    break;
                default:
                    $params['adsize'] = $magic_quotes ? stripslashes($_POST['kmn_a_adsize']) : $_POST['kmn_a_adsize'];
                    $params['script'] = $magic_quotes ? stripslashes($_POST['kmn_a_adsense']) : $_POST['kmn_a_adsense'];
                    $params['cpm'] = 'cpm';
                    break;
            }

            // call Komoona server - if curl supported, use ssl. else standard HTTP call
            $curl = Kmn_Func::get_curl_version();
            if(isset($curl)) {

                // server rest API destination
                $komoona_srv = parse_url(KOMOONA_SERVER_URL);

                // new account password
                $params['password'] = $magic_quotes ? stripslashes($_POST['kmn_a_password']) : $_POST['kmn_a_password'];

                try {
                    if(isset($komoona_srv['port'])) {
                        $port = $komoona_srv['port'];
                    }
                    else {
                        $port = $komoona_srv['scheme'] === 'https' ? 443 : 80;
                    }

                    $rest = Kmn_Rest::connect($komoona_srv['host'], $port, $komoona_srv['scheme'] === 'https' ? Kmn_Rest::HTTPS : Kmn_Rest::HTTP);

                    $method = $komoona_srv['path'] . 'swp_add_tag';
                    $pos = strpos($method, '/');
                    if($pos === 0) {
                        $method = substr_replace($method, '', $pos, 1);
                    }

                    // get result from Komoona server
                    $result = $rest->post($method, $params);

                    // validate results
                    $r = json_decode($result);
                    if($r->status === 'success') {
                        // update the plugin options
                        update_option($plugin_type . $plugin_id. '_komoona_tag', $r->komoona_tag);
                        update_option($plugin_type . $plugin_id. '_username', $params['username']);
                        update_option($plugin_type . $plugin_id. '_position', $params['position']);
                    }

                    // echo result to page
                    echo $result;
                }
                catch(Kmn_Rest_Exception $e) {
                    // show error 
                    echo json_encode(array ('error' => $e->__toString()));
                }

                die(); // this is required to return a proper result from the ajax call
            }
            else {

                echo json_encode(array ('error' => 'Your server is missing cURL extension. This extension must be install before you can access the Komoona server.'));
                die(); // this is required to return a proper result from the ajax call
            }
        }
    } // end of Kmn_Post class

endif; // Kmn_Post exists
// kmn post class
?>