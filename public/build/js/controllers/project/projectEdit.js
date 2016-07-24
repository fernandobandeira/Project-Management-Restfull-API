angular.module('app.controllers')
    .controller('ProjectEditController', ['$scope', 'Project', 'Client', '$routeParams', '$location', 'appConfig', '$cookies',
        function($scope, Project, Client, $routeParams, $location, appConfig, $cookies) {
            $scope.project = new Project.get({
                id: $routeParams.id
            }, function(projectAssigned) {
                $scope.project.client_id = projectAssigned.client.data.id;
            });
            $scope.clients = Client.query();
            $scope.status = appConfig.project.status;

            $scope.save = function() {
                if ($scope.form.$valid) {
                    $scope.project.owner_id = $cookies.getObject('user').id;
                    Project.update({
                        id: $scope.project.id
                    }, $scope.project, function() {
                        $location.path('/projects');
                    });
                }
            }
        }
    ]);
