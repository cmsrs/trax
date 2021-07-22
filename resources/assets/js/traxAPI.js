

    // Mock endpoints to be changed with actual REST API implementation
let traxAPI = {
    getCarsEndpoint() {
        //return '/api/mock-get-cars'
        return '/api/get-cars';
    },
    getCarEndpoint(id) {
        return '/api/get-car' + '/' + id;
        //return '/api/mock-get-car' + '/' + id;        
    },
    addCarEndpoint() {
        return '/api/add-car';        
        //return '/api/mock-add-car';
    },
    deleteCarEndpoint(id) {
        return '/api/delete-car' + '/' + id;        
        //return '/api/mock-delete-car' + '/' + id;
    },
    getTripsEndpoint() {
        return '/api/get-trips';
        //return '/api/mock-get-trips';        
    },
    addTripEndpoint() {
        return 'api/add-trip';
        //return 'api/mock-add-trip'        
    }
}


export { traxAPI };