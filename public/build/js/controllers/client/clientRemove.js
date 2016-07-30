angular.module('app.controllers')
    .controller('ClientRemoveController',
        ['$scope', 'Client', '$routeParams', '$location',
            function ($scope, Client, $routeParams, $location) {
                $scope.client = new Client.get({id: $routeParams.id});

                $scope.remove = function () {
                    $scope.client.$delete().then(function () {
                        $location.path('/clients');
                    });
                }
            }]);