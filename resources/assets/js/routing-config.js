routes = function (Vue) {

    return [
        {
            path: '/',
            redirect: '/trips'
        },
        {
            path: '/trips',
            component: Vue.component('trips-view'),
        },
        {
            path: '/cars',
            component: Vue.component('cars-view'),
        },
        {
            path: '/cars/:id',
            component: Vue.component('car-view')
        },
        {
            path: '/new-car',
            component: Vue.component('new-car-view')
        },
        {
            path: '/new-trip',
            component: Vue.component('new-trip-view')
        }
    ]
}