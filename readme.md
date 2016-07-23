# Project Management Restfull API
[![StyleCI](https://styleci.io/repos/58147256/shield)](https://styleci.io/repos/58147256)
[![Dependency Status](https://www.versioneye.com/user/projects/5792664bb7463b0037915d43/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/5792664bb7463b0037915d43)

Esta é uma restfull API desenvolvida com o Laravel 5.2, com o objetivo de aprender a criar aplicações restfull da maneira correta.

##Instalação

###Desenvolvimento

####Backend
```
composer install
cp .env.example .env
php artisan key:generate
//configure o .env e coloque o app_debug=true
php artisan migrate --seed
```

####Frontend
```
npm install
bower install
gulp watch-dev
```

####Credenciais
Os seeders criam algumas credenciais padrões para serem utilizadas nos testes, elas podem ser alteradas nos seguintes arquivos:
```php
//UserTableSeeder.php
[
    'name'           => 'Fernando',
    'email'          => 'fernando.bandeira94@gmail.com',
    'password'       => bcrypt(123456),
    'remember_token' => str_random(10),
]

//OAuthClientSeeder.php
['id' => 'angular_app', 'secret' => 'secret', 'name' => 'Aplicação AngularJS']
```

###Produção

####Backend
```
composer install
cp .env.example .env
php artisan key:generate
//configure o .env e deixe o app_debug=false
php artisan migrate
```

####Frontend
```
npm install
bower install
gulp
```

O repositório ja está pronto para um ambiente de produção, com o resultado final do gulp na pasta public e o .env.example está com o app_debug=false.
