<template>
    <div class="m-2 md:m-10 grid grid-cols-12 lg:gap-3">
        <div id="calculadora-form-container" class="col-span-12 lg:col-span-6 mt-5 md:mt-0" >
            <form
                id="calculatorForm"
                ref="calculatorForm"
                name="calculatorForm"
                method="POST"
                @submit.prevent="submit"
                autocomplete="off"
            >
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-2 md:px-6 pt-6 pb-4 md:pt-10 md:pb-6 bg-white">
                        <div class="grid grid-cols-12 gap-6">
                            <!-- SUMMATION STRING -->
                            <div class="col-span-12">
                                <div class="relative">
                                    <label for="summationString" :class="{ 'label': true, 'active-label': isActive('summationString') }">Cadena de numeros a sumar</label>
                                    <input type="text"
                                        id="summationString"
                                        ref="summationString"
                                        name="summationString"
                                        v-model="summationString"
                                        class="mt-1 focus:focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div class="col-span-12">
                                <span class="text-green-600 font-extrabold uppercase">Resultado:</span>
                            </div>
                            <div class="col-span-12">
                                <span class="text-green-600 font-extrabold uppercase">{{ result }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <transition enter-to-class="animated fadeIn" leave-to-class="animated fadeOut">
                            <button
                                id="btnCalcular"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-bold rounded-md text-lg  text-white bg-blue-500 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700"
                                type="submit">
                                    Calcular
                                    <span class="animate-spin spinner ease-linear rounded-full
                                        border-4 border-t-4 h-4 w-4 mt-2 ml-2"
                                        v-show="showLoading"
                                    />
                            </button>
                        </transition>
                    </div>
                </div>
            </form>
        </div>
        <!-- RESULTADOS -->
        <div id="calculator-response-container" class="animated fadeInDown col-span-12 lg:col-span-6">
            <transition enter-to-class="animated fadeInDown" leave-to-class="animated fadeOutUp">
                <div id="helpOnFail" v-if="showHelpOnFail == true">
                    <div id="helpOnFailText">¡Ha ocurrido un error!</div>
                </div>
            </transition>

            <transition enter-to-class="animated fadeInDown" leave-to-class="animated fadeOutUp">
                <CalculatedHistoryGrid v-if="showLogResults" :calculated-history-grid-items="calculatedHistoryGridItems"/>
            </transition>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import CalculatedHistoryGrid from './CalculatedHistoryGrid';
    import { scroller } from 'vue-scrollto/src/scrollTo';

    const scrollTo = scroller();
    const axios = require('axios');

    export default {
        name: 'CalculatorForm',
        components: {
            CalculatedHistoryGrid
        },
        data() {
            return {
                token: '',
                // flags
                showHelpOnFail: false,
                showLoading: false,
                showLogResults: false,
                acceptConditions: false,
                // form
                summationString: '',
                // response
                calculatedHistoryGridItems: [],
                result: 0
            };
        },
        methods: {
            isActive: function (inputName) {
                if(this.$refs[inputName]){
                    var value = this.$refs[inputName].value;
                    if(value !== "") {
                        return true;
                    }else{
                        return false;
                    }
                }
                return false;
            },
            submit: async function() {
                if (this.showLoading) {
                    return;
                }
                this.calculatedHistoryGridItems = [];
                this.showLoading=true;
                this.showLogResults = false;
                this.showSendMessageSuccess = false;
                this.showHelpOnFail = false;
                // LLAMADA EL ENDPOINT SUMAR
                axios.post('/api/sumar', {
                    summationString: this.summationString
                }).catch(error => {
                    // eslint-disable-next-line
                    var e = error;
                })
                .then((response) => {
                    this.result = response.data;
                    this.showLoading=false;
                    this.showLogResults=true;
                    scrollTo('#calculator-response-container');
                });
            }
        },
        mounted: function() {
            axios.get('/api/apitoken')
            .catch(error => {
                // eslint-disable-next-line
                var e = error;
            })
            .then((response) => {
                console.log('token');
                console.log(response.data.token);
                this.token = response.data.token;
                axios.defaults.headers.common["Authorization"] = 'Bearer '+this.token;
            });
        }
    }
</script>

<style>
.spinner{
  border: solid #b9b8b8;
  border-top: solid white;
  vertical-align: middle;
}
</style>
