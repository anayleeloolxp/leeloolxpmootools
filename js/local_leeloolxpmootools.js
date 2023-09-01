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

        let arid = $('#leeloolxpmootools-js-vars').data('arid');
        let courseid = $('#leeloolxpmootools-js-vars').data('courseid');
        let modname = $('#leeloolxpmootools-js-vars').data('modname');
        let os = $('#leeloolxpmootools-js-vars').data('os');
        let browser = $('#leeloolxpmootools-js-vars').data('browser');

        leeloolxpssourl = 'https://mootools.epicmindarena.com?mootoolsleeloourl='+mootoolsleeloourl+'&mootoolstoken='+mootoolstoken;

        window.addEventListener('message', function(event) {

            if (event.origin !== 'https://mootools.epicmindarena.com') return;

            var receivedData = JSON.parse(event.data);

            if (receivedData.style === 'modal') {
                // Show data in the modal
                $('.leeloolxpmootools-modal-body').text(JSON.stringify(receivedData, null, 2));
                $('.leeloolxpmootools-modal').fadeIn();
            } else if (receivedData.style === 'notification') {
                // Show data in the notification
                $('.leeloolxpmootools-notification-body').text(JSON.stringify(receivedData, null, 2));
                $('.leeloolxpmootools-notification').fadeIn().delay(5000).fadeOut();
            }

        }, false);

        // Close modal when 'x' is clicked
        $('.leeloolxpmootools-modal-close').on('click', function() {
            $('.leeloolxpmootools-modal').fadeOut();
        });

        // Close modal when clicking outside
        $(document).on('click', function(event) {
            if ($(event.target).hasClass('leeloolxpmootools-modal')) {
                $('.leeloolxpmootools-modal').fadeOut();
            }
        });

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
                // console.log(response);
            });
        }

        // Call the function immediately upon page load
        updateClockAjaxRequest();

        // Set an interval to call the function every 5 minutes (300000 milliseconds)
        setInterval(updateClockAjaxRequest, 60000);

        if(arid != 0){

            arTrackAjaxRequest(arid, 'start');

            setInterval( function() {
                arTrackAjaxRequest(arid, 'update');
            } , 60000);

            $(window).on('beforeunload', function() {
                arTrackAjaxRequest(arid, 'stop');
            });
        }

        // Define your AJAX function
        function arTrackAjaxRequest(arid, task) {
            console.log(task);
            var settings = {
                "url": mootoolsleeloourldecoded + "/api/attendance/update_ar_time",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Authorization": "Bearer " + mootoolstoken
                },
                "data": {
                    "instanceid": arid,
                    "instancetype": 'ar',
                    "ar_type": modname,
                    "courseid": courseid,
                    "os": os,
                    "browser": browser,
                    "apifrom": 'Moodle',
                    "task": task
                },
            };

            $.ajax(settings).done(function(response) {
                // console.log(response);
            });
        }

    });

});
