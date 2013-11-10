'use strict';

describe('Controller: CollapseCtrl', function () {

  // load the controller's module
  beforeEach(module('tilosApp'));

  var CollapseCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    CollapseCtrl = $controller('CollapseCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
