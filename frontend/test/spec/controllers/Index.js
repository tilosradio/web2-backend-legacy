'use strict';
(function () {
  describe('Controller: IndexCtrl', function () {

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
        return $controller('IndexCtrl', {
            '$scope': $rootScope,
            'API_SERVER_ENDPOINT': "http://server"
          }
        );
      };
      $httpBackend = $injector.get('$httpBackend');
    }));

    it('should retrieve list of news', function () {
      $httpBackend.expectGET('http://server/api/v0/text/news/list').respond([
        {id: 1, title: "asd", content: "asd"},
        {id: 2, title: "qwe", content: "asd"}


      ]);
      var controller = createController();
      $httpBackend.flush();
      expect(scope.news.length).toEqual(2);
      expect(scope.news[1].title).toEqual("qwe");

    });
  });
})();
