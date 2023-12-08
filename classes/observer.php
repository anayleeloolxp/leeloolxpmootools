<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Admin settings and defaults
 *
 * @package tool_leeloolxp_sync
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author Leeloo LXP <info@leeloolxp.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_leeloolxpmootools;

use core_user;
use curl;
use moodle_url;

/**
 * Plugin to sync users on new enroll, groups, trackign of activity view to LeelooLXP account of the Moodle Admin
 */
class observer {
    /**
     * Triggered when user_loggedin.
     *
     * @param \core\event\user_loggedin $events
     */
    public static function user_loggedin(\core\event\user_loggedin $event) {

        $mootoolsenable = get_config('local_leeloolxpmootools')->enable;
        $mootoolsleeloourl = get_config('local_leeloolxpmootools')->leeloourl;

        if ($mootoolsenable && $mootoolsleeloourl) {
            global $USER;
            if (isloggedin() && !is_siteadmin($USER)) {
                global $DB, $CFG;

                $user = $DB->get_record('user', ['id' => $event->objectid]);

                $userid = $user->id;
                $username = $user->username;
                $password = $user->password;

                require_once($CFG->libdir . '/filelib.php');

                // Initialize the core\curl class.
                $curl = new curl;

                // The endpoint of the external system you're notifying.
                $url = $mootoolsleeloourl . '/api/login';

                // Prepare the data payload.
                $payload = array(
                    'mootools' => $userid,
                    'username' => $username,
                    'password' => $password,
                );

                // Convert payload array to JSON.
                $jsonPayload = json_encode($payload);

                // Set the appropriate headers for a JSON POST request.
                $headers = array('Content-Type: application/json');

                // Perform the cURL POST request.
                $result = $curl->post($url, $jsonPayload, array('CURLOPT_HTTPHEADER' => $headers));

                $res_arr = json_decode($result);
                if ($res_arr->status == 'success') {
                    $mootools_token = $res_arr->token;
                    setcookie('mootools_token', $mootools_token, time() + (86400 * 30), '/');
                    setcookie('mootools_login_response', $result, time() + (86400 * 30), '/');
                } else {
                    setcookie('mootools_token', '', time() - 3600, '/');
                    setcookie('mootools_login_response', '', time() - 3600, '/');
                }
            } else {
                setcookie('mootools_token', '', time() - 3600, '/');
                setcookie('mootools_login_response', '', time() - 3600, '/');
            }
        } else {
            setcookie('mootools_token', '', time() - 3600, '/');
            setcookie('mootools_login_response', '', time() - 3600, '/');
        }
    }

    /**
     * Triggered when user_loggedout.
     *
     * @param \core\event\user_loggedout $events
     */
    public static function user_loggedout(\core\event\user_loggedout $event) {
        $mootoolsenable = get_config('local_leeloolxpmootools')->enable;
        $mootoolsleeloourl = get_config('local_leeloolxpmootools')->leeloourl;

        if ($mootoolsenable && $mootoolsleeloourl) {
            if (isset($_COOKIE['mootools_token'])) {
                $mootools_token = $_COOKIE['mootools_token'];

                global $CFG;

                require_once($CFG->libdir . '/filelib.php');

                // Initialize the core\curl class.
                $curl = new curl;

                // The endpoint of the external system you're notifying.
                $url = $mootoolsleeloourl . '/api/logout';

                // Prepare the data payload.
                $payload = array();

                // Convert payload array to JSON.
                $jsonPayload = json_encode($payload);

                // Set the appropriate headers for a JSON POST request.
                $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $mootools_token);

                // Perform the cURL POST request.
                $result = $curl->post($url, $jsonPayload, array('CURLOPT_HTTPHEADER' => $headers));

                // Delete the cookie
                setcookie('mootools_token', '', time() - 3600, '/');  // This sets the cookie to expire in the past, effectively deleting it
                setcookie('mootools_login_response', '', time() - 3600, '/');
            }
        }
    }
}
