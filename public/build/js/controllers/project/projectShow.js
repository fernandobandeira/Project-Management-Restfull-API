angular.module('app.controllers')
    .controller('ProjectShowController',
        ['$scope', 'Project', '$routeParams',
            function ($scope, Project, $routeParams) {
                $scope.project = new Project.get({id: $routeParams.id});
            }]);