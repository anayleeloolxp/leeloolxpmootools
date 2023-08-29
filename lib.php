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
 * Local Library file for additional Functions
 *
 * @package    local_leeloolxpmootools
 * @copyright  2020 Leeloo LXP (https://leeloolxp.com)
 * @author     Leeloo LXP <info@leeloolxp.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Add Leeloo Icon by js
 */
function local_leeloolxpmootools_before_footer() {
    $mootoolsenable = get_config('local_leeloolxpmootools')->enable;
    $mootoolsleeloourl = get_config('local_leeloolxpmootools')->leeloourl;

    if (isset($_COOKIE['mootools_token'])) {
        $mootoolstoken = $_COOKIE['mootools_token'];
    }

    if ($mootoolsenable && $mootoolsleeloourl && $mootoolstoken) {
        global $USER;
        if (isloggedin() && !is_siteadmin($USER)) {
            global $PAGE;

            echo '<div id="leeloolxpmootools-js-vars" data-mootoolsleeloourl="' . base64_encode($mootoolsleeloourl) . '" data-mootoolstoken="' . $mootoolstoken . '"></div>';

            //$PAGE->requires->css(new moodle_url('/local/leeloolxpmootools/styles.css'));
            $PAGE->requires->js(new moodle_url('/local/leeloolxpmootools/js/local_leeloolxpmootools.js'));
            echo '<button id="local_leeloolxpmootools_button">Open MooTools</button>';
            echo '<div class="local_leeloolxpmootools_wrapper"><div id="local_leeloolxpmootools_wrapper_close">X</div><div id="local_leeloolxpmootools_frame"></div></div>';

            echo '<div class="leeloolxpmootools-modal" style="display:none;">
                <div class="leeloolxpmootools-modal-content">
                    <span class="leeloolxpmootools-modal-close">&times;</span>
                    <p class="leeloolxpmootools-modal-body"></p>
                </div>
            </div>';

            echo '<div class="leeloolxpmootools-notification" style="display:none;">
                <p class="leeloolxpmootools-notification-body"></p>
            </div>';
        }
    }
}
