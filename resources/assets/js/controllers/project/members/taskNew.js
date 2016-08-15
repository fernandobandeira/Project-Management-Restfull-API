angular.module('app.controllers')
    .controller('ProjectTasksNewController', [
        '$scope', '$routeParams', '$location','appConfig','ProjectTask',
        function($scope, $routeParams, $location, appConfig, ProjectTask){
            $scope.task = new ProjectTask();
            $scope.status = appConfig.projectTask.status;

            $scope.start_date = {
                status: {
                    opened: false
                }
            };

            $scope.due_date = {
                status: {
                    opened: false
                }
            };

            $scope.openStartDatePicker = function($event){
                $scope.start_date.status.opened = true;
            };

            $scope.openDueDatePicker = function($event){
                $scope.due_date.status.opened = true;
            };

            $scope.save = function(){
                if($scope.form.$valid) {
                    $scope.task.$save({id: $routeParams.id}).then(function () {
                        $location.path('/projects/' + $routeParams.id + '/tasks');
                    })
                }
            }
    }]);