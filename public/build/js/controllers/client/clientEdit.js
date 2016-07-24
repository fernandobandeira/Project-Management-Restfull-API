angular.module('app.controllers')
    .controller('ClientEditController',
        ['$scope', 'Client', '$routeParams', '$location',
            function ($scope, Client, $routeParams, $location) {
                $scope.client = new Client.get({id: $routeParams.id});

                $scope.save = function () {
                    if ($scope.form.$valid) {
                        Client.update({id: $scope.client.id}, $scope.client, function () {
                            $location.path('/clients');
                        });
                    }
                }
            }]);