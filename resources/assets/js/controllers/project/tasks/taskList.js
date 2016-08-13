angular.module('app.controllers')
    .controller('ProjectTasksListController', ['$scope', 'ProjectTask', '$routeParams', 'appConfig',
        function ($scope, ProjectTask, $routeParams, appConfig) {
	        $scope.task = new ProjectTask();

            $scope.save = function() {
                if($scope.form.$valid){
                    $scope.task.status = appConfig.projectTask.status[0].value;
                    $scope.task.$save({id: $routeParams.id}).then(function(){
                        $scope.task = new ProjectTask();
                        $scope.loadTask();
                    });
                }
            };

            $scope.loadTask = function() {
                $scope.tasks = ProjectTask.query({
                    id: $routeParams.id,
                    orderBy: 'id',
                    sortedBy: 'desc'
                });
            };

            $scope.loadTask();
	        $scope.project_id = $routeParams.id;
    }]);