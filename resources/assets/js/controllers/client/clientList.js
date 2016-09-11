angular.module('app.controllers')
    .controller('ClientListController', ['$scope', 'Client', function ($scope, Client) {
      Client.query({
          limit: 8,
          orderBy: 'created_at',
          sortedBy: 'desc'
      }, function(response) {
          $scope.clients = response.data;
      });
    }]);
