import _ from 'lodash';

window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// window.$ = require('jquery');
// window.jQuery = require('jquery');

// require('bootstrap');
// require('datatables.net-bs4');
// require('datatables.net-buttons-bs4');

import Echo from "laravel-echo"

// window.Pusher = require('pusher-js');
import pusher from 'pusher-js'
window.Echo = new Echo({
    broadcaster: "pusher",
    key:process.env.MIX_PUSHER_APP_KEY,
    cluster:process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: false,
    wsHost: window.location.hostname,
    wsPort: 6001,
    encrypted:false,
    // disableStats: true,
    enabledTransports: ['ws', 'wss']
});
