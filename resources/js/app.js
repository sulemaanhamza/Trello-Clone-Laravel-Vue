require('./bootstrap');

import Vue from 'vue';
import VModal from 'vue-js-modal'

import board from './components/board';

Vue.use(VModal)

Vue.component('board', board);

const app = new Vue({
    el: '#app',
});
