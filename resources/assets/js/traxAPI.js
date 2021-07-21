

    // Mock endpoints to be changed with actual REST API implementation
let traxAPI = {
    getCarsEndpoint() {
        return '/api/mock-get-cars'
    },
    getCarEndpoint(id) {
        return '/api/mock-get-car' + '/' + id;
    },
    addCarEndpoint() {
        return '/api/mock-add-car';
    },
    deleteCarEndpoint(id) {
        return '/api/mock-delete-car' + '/' + id;
    },
    getTripsEndpoint() {
        return '/api/mock-get-trips';
    },
    addTripEndpoint() {
        return 'api/mock-add-trip'
    }
}



export { traxAPI };