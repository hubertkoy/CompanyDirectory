import {createApp} from 'vue';

const app = createApp({
    data: () => ({
        username: 'userName_1',
    }),
    template: '<h1>Hello {{ username }} Vue!</h1>',
}).mount('#app');

window.app = app;