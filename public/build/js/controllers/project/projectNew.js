angular.module('app.controllers')
    .controller('ProjectNewController', ['$scope', 'Project', 'Client', '$location', 'appConfig', '$cookies',
        function($scope, Project, Client, $location, appConfig, $cookies) {

            $scope.project = new Project();
            $scope.clients = Client.query();
            $scope.status = appConfig.project.status;

            $scope.save = function() {
                if ($scope.form.$valid) {
                    $scope.project.owner_id = $cookies.getObject('user').id;
                    $scope.project.$save().then(function() {
                        $location.path('/projects');
                    });
                }
            }
        }
    ]);
