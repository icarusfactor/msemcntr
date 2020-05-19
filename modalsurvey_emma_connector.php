<?php

/**
 * @package  modal_survey_emma_connector
 */

/*
 Plugin Name: Modal Survey Emma Connector Plugin
 Plugin URI: http://userspace.org
 Description: This plugin reads the Modal Survey Data email of user and sends it to Emma Mailer
 Version: 1.0.1
 Author: Daniel Yount aka "icarus factor"
 Author URI: http://userspace.org
 License: GPLv2 or later
 Text Domain: modalsurvey_emma_connector-plugin
 */

/*
 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 Copyright 2020 Daniel Yount.
 */
defined('ABSPATH')or die('Hey, what are you doing here? You silly human!');


if(!class_exists('ModalSurveyEmmaConnectorPlugin')) {

    class ModalSurveyEmmaConnectorPlugin {
        public $plugin;

        function __construct() {
            $this->plugin = plugin_basename(__FILE__);
        }

        function register() {
            add_action('admin_menu', array($this, 'add_admin_pages'));
            wp_enqueue_script('jquery');
            add_action('wp_ajax_nopriv_ajaxmsemcntr_do_something', array($this, 'msemcntr_do_something_serverside'));
            add_action('wp_ajax_ajaxmsemcntr_do_something', array($this, 'msemcntr_do_something_serverside'));
            
            /* notice green_do_something appended to action name of wp_ajax_ */
            add_action('admin_enqueue_scripts', array($this, 'msemcntr_enqueue'));
            add_action(modal_survey_action_admin_email, array(&$this, 'msemcntr_adminemail'));
            add_action(modal_survey_action_participants_create, array(&$this, 'msemcntr_usercreate'));
            add_action(modal_survey_action_participants_update, array(&$this, 'msemcntr_userupdate'));
        }

        function msemcntr_enqueue() {
            wp_enqueue_style('msemcntrbootcss', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
            wp_enqueue_script('msemcntrpopper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js');
            wp_enqueue_script('msemcntrboot', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js');
            wp_enqueue_script('ajaxmsemcntr_script', plugin_dir_url(__FILE__). 
                                                                    'assets/ajax_call_to_handle_form_submit.js');
            wp_localize_script('ajaxmsemcntr_script', 'ajaxmsemcntr_object', array('ajaxmsemcntr_url' => admin_url('admin-ajax.php'), 'if_ajaxid' => 003));
        }
        //Emma CNX Get Account data. 
        //So we need to split the name. 
        public function split_name($name) {
            $parts = explode(" ", trim($name));
            $num = count($parts);
            if($num > 1) {
                $lastname = array_pop($parts);
            } else {
                $lastname = '';
            }
            $firstname = implode(" ", $parts);
            return array('first_name' =>$firstname,
                         'last_name' =>$lastname);
            //return array($firstname, $lastname);
        }
        //Main function to send new user to email list. 
        public function msemcntr_emma_put_member($params) {
            $configObPut = json_decode(get_option('msemcntr_configs'));
            $account_id = $configObPut->accountid;
            $public_key = $configObPut->publickey;
            $private_key = $configObPut->privatekey;
            // This will crete two needed variables. $name and $email 
            extract($params);
            $emma = new Emma($account_id, $public_key, $private_key);
            // Returns True if the member was updated successfully
            try {
                $member = array();
                // Returns The member_id of the new or updated member, whether the member was added or an existing member was updated, and the status of the member. The status will be reported as ‘a’ (active), ‘e’ (error), or ‘o’ (optout).
                try {
                    $member = array();
                    $member['email'] = $email;
                    $member['fields'] = $this->split_name($name);
                    $req = $emma->membersAddSingle($member);
                }
                catch(Emma_Invalid_Response_Exception $e) {
                    exit($e->getMessage());
                }
                //DEBUG USE ONLY
                
                /* //Email will print this to make sure it looks like what I want.
                 $to = 'your@testemail.com';
                 $subject = 'Debug Info:  EMMA PUT Members';
                 $body = 'Debug Info:   EMMA PUT Members ';
                 ob_start();
                 //echo json_decode($req);
                 echo var_dump( $req );
                 $body .= ob_get_contents();
                 ob_end_clean();
                 $headers = array('Content-Type: text/html; charset=UTF-8');
                 wp_mail( $to, $subject, $body, $headers );
                 */
            }
            catch(Emma_Invalid_Response_Exception $e) {
                exit($e->getMessage());
            }
        }
        // Modal Survey  Action Hooks.         
        //TRIGGERED  Using this one for Sending to EMMA. 
        public function msemcntr_userupdate($params) {
            $this->msemcntr_emma_put_member($params);
            // FOR DEBUG USE ONLY
            
            /*                 $to = 'your@testemail.com';
             $subject = 'Debug Info: User Updated Triggered';
             $body = 'Debug Info: User Updated Triggered';
             ob_start();
             var_dump($params);
             $body .= ob_get_contents();
             ob_end_clean();
             $headers = array('Content-Type: text/html; charset=UTF-8');
             wp_mail( $to, $subject, $body, $headers );*/
        }
        // Basic POST function of return call from js 
        function msemcntr_do_something_serverside() {
            //Use this value to validate each data set is only for this plugin.
            $unique_value = $_POST['if_ajaxid'];
            if($unique_value == '003') {
                // Convert output to ajax call      
                $d4 = $_POST['element_4'];
                $d5 = $_POST['element_5'];
                $d6 = $_POST['element_6'];
                $d4conv = urlencode($d4);
                $d5conv = urlencode($d5);
                $d6conv = urlencode($d6);
                //Check if  URL data query is empty , if so send false
                if($d4 == '' OR $d5 == '' OR $d6 == '') {
                    echo "false";
                } else {
                    //Need to convert all configs to json and save in variable                                 
                    $msemcntrObj->accountid = $d4conv;
                    $msemcntrObj->publickey = $d5conv;
                    $msemcntrObj->privatekey = $d6conv;
                    $msemcntrJSON = json_encode($msemcntrObj);
                    //Will be saved to database options. 
                    update_option('msemcntr_configs', $msemcntrJSON);
                    echo "true";
                    die();
                }
            }
        }

        public function add_admin_pages() {
            add_menu_page('Emma CNX', 'Emma CNX', 'manage_options', 'modalsurvey_cnx_plugin', array($this, 'admin_index'), 'dashicons-portfolio', 110);
        }

        public function admin_index() {
            require_once plugin_dir_path(__FILE__). 'templates/admin.php';
        }

        function activate() {
            require_once plugin_dir_path(__FILE__). 'inc/msemcntr-plugin-activate.php';
            MSEMCNTRPluginActivate::activate();
        }
    }
    $MSEMCNTRPlugin = new ModalSurveyEmmaConnectorPlugin();
    $MSEMCNTRPlugin->register();
    // Emma API
    require_once plugin_dir_path(__FILE__). 'inc/Emma.php';
    // activation
    register_activation_hook(__FILE__, array($MSEMCNTRPlugin, 'activate'));
    // deactivation
    require_once plugin_dir_path(__FILE__). 'inc/msemcntr-plugin-deactivate.php';
    register_deactivation_hook(__FILE__, array('MSEMCNTRPluginDeactivate', 'deactivate'));
}
