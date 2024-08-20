import Vue from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faSync, faUser } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// Add icons to the library
library.add(faSync, faUser);

// Register the component globally
Vue.component('font-awesome-icon', FontAwesomeIcon);