Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'nova-eloquent-note',
            path: '/nova-eloquent-note',
            component: require('./components/Tool'),
        },
    ])
})
