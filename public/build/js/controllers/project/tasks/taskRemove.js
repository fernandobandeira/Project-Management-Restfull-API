angular.module('app.controllers')
    .controller('ProjectTasksRemoveController',
        ['$scope', 'ProjectTask', '$routeParams', '$location',
            function ($scope, ProjectTask, $routeParams, $location) {
                $scope.task = new ProjectTask.get({id: $routeParams.id, idTask: $routeParams.idTask});

                $scope.remove = function () {
                    $scope.task.$delete({id: $routeParams.id,idTask: $scope.task.id}).then(function () {
                        $location.path('/projects/' + $routeParams.id + '/tasks');
                    });
                }
            }]);