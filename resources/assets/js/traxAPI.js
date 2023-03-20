// Mock endpoints to be changed with actual REST API implementation

const urlPrefix = '/api';

let traxAPI = {
  // Cars
  getCarEndpoint(id) {
    return `${urlPrefix}/car/${id}`;
  },
  getCarsEndpoint() {
    return `${urlPrefix}/cars`;
  },
  addCarEndpoint() {
    return `${urlPrefix}/car/create`;
  },
  deleteCarEndpoint(id) {
    return `${urlPrefix}/car/delete/${id}`;
  },
  // Cars trips
  getTripEndpoint(id) {
    return `${urlPrefix}/car/trip/${id}`;
  },
  getTripsEndpoint() {
    return `${urlPrefix}/cars/trips`;
  },
  addTripEndpoint() {
    return `${urlPrefix}/car/trip/create`;
  },
  deleteTripEndpoint(id) {
    return `${urlPrefix}/car/trip/delete/${id}`;
  }
};

export {traxAPI};
