import './bootstrap';

Echo.channel('my-channel-test')
    .listen('.my-event-test', (e) => {
        console.log('event fired');
        alert('event fired');
    });
