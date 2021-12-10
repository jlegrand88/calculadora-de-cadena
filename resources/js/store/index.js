import { createStore } from 'vuex'

import authentication from './modules/authentication';

// Create a new store instance.
const store = createStore({
  modules: {
    authentication
},
})
