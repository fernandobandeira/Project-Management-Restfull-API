angular.module('app.controllers')
    .controller('ProjectEditController',
        ['$scope', 'Project', 'Client', '$routeParams', '$location',
            function ($scope, Project, Client, $routeParams, $location) {
                $scope.project = new Project.get({id: $routeParams.id}, function(projectAssigned) {
                    $scope.project.client_id = projectAssigned.client.data.id;
                    $scope.project.due_date = new Date(projectAssigned.due_date);
                });
                $scope.clients = Client.query();

                $scope.save = function () {
                    if ($scope.form.$valid) {
                        Project.update({id: $scope.project.id}, $scope.project, function () {
                            $location.path('/projects');
                        });
                    }
                }
            }]);