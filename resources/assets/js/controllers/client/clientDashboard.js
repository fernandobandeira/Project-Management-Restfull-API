angular.module('app.controllers')
    .controller('ClientDashboardController', ['$scope', 'Client', '$routeParams', '$location',
        function($scope, Client, $routeParams, $location) {
            $scope.client = {};

            Client.query({
                limit: 8,
                orderBy: 'created_at',
                sortedBy: 'desc'
            }, function(response) {
                $scope.clients = response.data;
            });

            $scope.showClient = function(client) {
                $scope.client = client;
            };
        }
    ]);
