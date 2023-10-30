<?php

class leeloo_admin_setting_configtext extends admin_setting_configtext {
    public function write_setting($data) {
        $status = parent::write_setting($data); // Call the parent's saving mechanism.
        if ($status === '') { // Check if parent write was successful.
            $this->get_install_data($data);
        }
        return $status;
    }

    private function get_install_data($data) {
        global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        // Initialize the core\curl class.
        $curl = new curl;

        // The endpoint of the external system you're notifying.
        $url = 'https://mootools.epic1academy.com/api/get_install.php';

        // Prepare the data payload.
        $payload = array(
            'moodleurl' => $CFG->wwwroot
        );

        // Convert payload array to JSON.
        $jsonPayload = json_encode($payload);

        // Set the appropriate headers for a JSON POST request.
        $headers = array('Content-Type: application/json');

        // Perform the cURL POST request.
        $result = $curl->post($url, $jsonPayload, array('CURLOPT_HTTPHEADER' => $headers));

        $res_arr = json_decode($result);

        if (isset($res_arr->status) && isset($res_arr->status) != '') {
            if ($res_arr->status == 'true') {
                set_config('leeloourl', $res_arr->url, 'local_leeloolxpmootools');
                return;
            }
        }

        set_config('leeloourl', '', 'local_leeloolxpmootools');
    }

    public function output_html($data, $query = '') {
        return '<input type="hidden" size="'
            . $this->size .
            '" id="' .
            $this->get_id() .
            '" name="' .
            $this->get_full_name() .
            '" value="' . s($data) . '" />';
    }
}
