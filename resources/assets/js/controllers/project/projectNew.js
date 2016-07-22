angular.module('app.controllers')
    .controller('ProjectNewController',
        ['$scope', 'Project', 'Client', '$location', function ($scope, Project, Client, $location) {
        $scope.project = new Project();
        $scope.clients = Client.query();

        $scope.save = function() {
            if($scope.form.$valid) {
                $scope.project.$save().then(function () {
                    $location.path('/projects');
                });
            }
        }
    }]);