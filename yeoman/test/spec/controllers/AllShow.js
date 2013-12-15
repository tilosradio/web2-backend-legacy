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
        {id: 2, type: 0}
      ]);
      var controller = createController();
      $httpBackend.flush();
      chai.expect(scope.shows.talk.length).to.equal(1);
      chai.expect(scope.shows.sound.length).to.equal(1);
    });
  });
})();
