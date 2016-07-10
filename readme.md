# Project Management Restfull API
[![StyleCI](https://styleci.io/repos/58147256/shield)](https://styleci.io/repos/58147256)

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