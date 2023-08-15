require(['jquery'], function ($) {

    $('#local_leeloolxpmootools_button').click(function() {
        $('.local_leeloolxpmootools_wrapper').toggleClass('open');
    });

    $('#local_leeloolxpmootools_wrapper_close').click(function() {
        $('.local_leeloolxpmootools_wrapper').removeClass('open');
    });

    $(document).ready(function () {

        let mootoolsleeloourl = $('#leeloolxpmootools-js-vars').data('mootoolsleeloourl');
        let mootoolsleeloourldecoded = atob($('#leeloolxpmootools-js-vars').data('mootoolsleeloourl'));
        let mootoolstoken = $('#leeloolxpmootools-js-vars').data('mootoolstoken');

        leeloolxpssourl = 'https://mootools.epicmindarena.com?mootoolsleeloourl='+mootoolsleeloourl+'&mootoolstoken='+mootoolstoken;

        document.getElementById("local_leeloolxpmootools_frame").innerHTML = '<iframe src="' + leeloolxpssourl + '" class="leeloolxpmootools_frame"></iframe>';

        // Define your AJAX function
        function updateClockAjaxRequest() {
            var settings = {
                "url": mootoolsleeloourldecoded + "/api/attendance/update_clockout_time",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Authorization": "Bearer " + mootoolstoken
                },
            };

            $.ajax(settings).done(function(response) {
                console.log(response);
            });
        }

        // Call the function immediately upon page load
        updateClockAjaxRequest();

        // Set an interval to call the function every 5 minutes (300000 milliseconds)
        setInterval(updateClockAjaxRequest, 300000);

    });

});
