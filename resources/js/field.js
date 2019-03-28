Nova.booting((Vue, router, store) => {
    Vue.component('index-liveupdate', require('./components/IndexField'))
    Vue.component('detail-liveupdate', require('./components/DetailField'))
    Vue.component('form-liveupdate', require('./components/FormField'))
})
