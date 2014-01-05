'use strict';
(function () {
  describe('Controller: AllshowCtrl', function () {

    // load the controller's module
    beforeEach(module('tilosApp'));

    var createController;
    var scope;
    var $httpBackend;

    // Initialize the controller and a mock scope
    beforeEach(inject(function ($injector, $rootScope) {
      scope = $rootScope.$new();

      var $controller = $injector.get('$controller');

      createController = function () {
        return $controller('AllshowCtrl', {
            '$scope': $rootScope,
            'API_SERVER_ENDPOINT': "http://server"
          }
        );
      };
      $httpBackend = $injector.get('$httpBackend');
    }));

    it('show classify returned shows', function () {
      $httpBackend.expectGET('http://server/api/v0/show').respond([
        {id: 1, type: 1},
        {id: 2, type: 0},
        {id: 3, type: 0}

      ]);
      var controller = createController();
      $httpBackend.flush();
      expect(scope.shows.talk.length).toEqual(1);
      expect(scope.shows.sound.length).toEqual(2);
    });
  });
})();
