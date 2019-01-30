Nova.booting((Vue, router) => {
    Vue.component('index-dynamic-select', require('./components/IndexField'));
    Vue.component('detail-dynamic-select', require('./components/DetailField'));
    Vue.component('form-dynamic-select', require('./components/FormField'));
})
