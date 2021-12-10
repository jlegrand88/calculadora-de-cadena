import Axios from "axios";

const state = {
    user: {},
    loginAttemptErrors: null
}

const getters = {
    getLoginAttemptErrors(state){
        return state.loginAttemptErrors;
    }
}

const mutations = {
    setUser(state,value){
        state.user = value;
    },
    setLoginAttemptErrors(state,value){
        state.loginAttemptErrors = value;
    }
}

const actions = {
    getUser( {commit} ){
        axios.get('api/user')
            .then(response => {
                // eslint-disable-next-line
                commit('setUser', response.data.data)
            })
            .catch(error => {
                // eslint-disable-next-line
                commit('setUser', {});
                window.location.href="login";
            })
            .finally(() => {
            });
    },
    login( { state, commit }, user ) {
        Axios.post("api/login", {
            nickname: user.nickname,
            password: user.password,
        }).then( response => {
            if(response.data.api_token){
                localStorage.setItem(
                    "api_token",
                    response.data.api_token
                )
                window.location.href = '/home';
            }
        }).catch(error => {
            if(error.response.data.hasOwnProperty('errors')){
                let newArray = Object.values(error.response.data.errors).map(function(x){
                    return x[0];
                });
                commit('setLoginAttemptErrors', newArray);
            }
        })
    },
    logout: function (user) {
        return axios.post('api/logout').then(response => {
            // eslint-disable-next-line
        }).catch(error => {
            // eslint-disable-next-line
            return error;
        }).finally(() => {
            window.location.href="login";
        });
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
