var app = angular.module('app', ['ngRoute', 'angular-oauth2', 'app.controllers', 'app.services']);

angular.module('app.controllers', ['ngMessages', 'angular-oauth2']);
angular.module('app.services', ['ngResource']);

app.provider('appConfig', function () {
    var config = {
        baseUrl: window.location.origin
    }

    return {
        config: config,
        $get: function () {
            return config;
        }
    }
});

app.config([
    '$routeProvider', '$httpProvider',
    'OAuthProvider', 'OAuthTokenProvider', 'appConfigProvider',
    function ($routeProvider, $httpProvider, OAuthProvider, OAuthTokenProvider, appConfigProvider) {

        //se for json pega o que esta dentro da chave data
        $httpProvider.defaults.transformResponse = function (data, headers) {
            var headersGetter = headers();
            if (headersGetter['content-type'] == 'application/json' ||
                headersGetter['content-type'] == 'text/json') {
                var dataJson = JSON.parse(data);
                if(dataJson.hasOwnProperty('data')) {
                    dataJson = dataJson.data;
                }
                return dataJson;
            }
            return data;
        };

        $routeProvider
            .when('/login', {
                templateUrl: 'build/views/login.html',
                controller: 'LoginController'
            })
            .when('/home', {
                templateUrl: 'build/views/home.html',
                controller: 'HomeController'
            })
            .when('/clients', {
                templateUrl: 'build/views/client/list.html',
                controller: 'ClientListController'
            });

        OAuthProvider.configure({
            baseUrl: appConfigProvider.config.baseUrl,
            grantPath: '/oauth/access_token',
            clientId: 'angular_app',
            clientSecret: 'secret' // optional
        });

        OAuthTokenProvider.configure({
            name: 'token',
            options: {
                secure: false //desativa pois nao estamos com https ativo
            }
        });
    }]);

app.run(['$rootScope', '$window', 'OAuth', function ($rootScope, $window, OAuth) {
    $rootScope.$on('oauth:error', function (event, rejection) {
        // Ignore `invalid_grant` error - should be catched on `LoginController`.
        if ('invalid_grant' === rejection.data.error) {
            return;
        }

        // Refresh token when a `invalid_token` error occurs.
        if ('invalid_token' === rejection.data.error) {
            return OAuth.getRefreshToken();
        }

        // Redirect to `/login` with the `error_reason`.
        return $window.location.href = '/login?error_reason=' + rejection.data.error;
    });
}]);