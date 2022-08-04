require('./bootstrap');

import Vue from 'vue';
import board from './components/board';

Vue.component('board', board);

const app = new Vue({
    el: '#app',
});
