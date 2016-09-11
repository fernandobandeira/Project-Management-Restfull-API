angular.module('app.controllers')
    .controller('ProjectDashboardController', ['$scope', 'Project', function($scope, Project) {
        $scope.project = {};

        Project.query({
            limit: 5,
            orderBy: 'created_at',
            sortedBy: 'desc'
        }, function(response) {
            $scope.projects = response.data;
        });

        $scope.showProject = function(project) {
            $scope.project = project;
        };
    }]);
