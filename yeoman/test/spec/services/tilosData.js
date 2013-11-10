'use strict';

describe('Service: tilosData', function () {

  // load the service's module
  beforeEach(module('tilosApp'));

  // instantiate service
  var tilosData;
  beforeEach(inject(function (_tilosData_) {
    tilosData = _tilosData_;
  }));

  it('should do something', function () {
    expect(!!tilosData).toBe(true);
  });

});
