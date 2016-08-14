angular.module('app.controllers')
    .controller('ProjectFilesListController', ['$scope', 'ProjectFile', '$routeParams',
        function ($scope, ProjectFile, $routeParams) {
        $scope.files = ProjectFile.query({id: $routeParams.id});
        $scope.project_id = $routeParams.id;
    }]);