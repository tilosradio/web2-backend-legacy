'use strict';

describe('Controller: AllshowCtrl', function () {

  // load the controller's module
  beforeEach(module('tilosApp'));

  var AllshowCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    AllshowCtrl = $controller('AllshowCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
