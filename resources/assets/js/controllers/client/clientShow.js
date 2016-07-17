angular.module('app.controllers')
    .controller('ClientShowController',
        ['$scope', 'Client', '$routeParams',
            function ($scope, Client, $routeParams) {
                $scope.client = new Client.get({id: $routeParams.id});
            }]);