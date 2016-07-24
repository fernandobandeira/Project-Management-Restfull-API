angular.module('app.controllers')
    .controller('ProjectRemoveController',
        ['$scope', 'Project', '$routeParams', '$location',
            function ($scope, Project, $routeParams, $location) {
                $scope.project = new Project.get({id: $routeParams.id});

                $scope.remove = function () {
                    $scope.project.$delete().then(function () {
                        $location.path('/projects');
                    });
                }
            }]);