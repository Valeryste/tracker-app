1.      git clone https://github.com/Valeryste/tracker-app.git

2.      cp .env.example .env

Пример .env

    APP_NAME="tracker-app"
    APP_ENV=development
    APP_DEBUG=true
    
    DB_HOST=db
    DB_PORT=3306
    MYSQL_ROOT_PASSWORD=rootpassword123
    MYSQL_DATABASE=tracker_app
    MYSQL_USER=user
    MYSQL_PASSWORD=password
    
    PHP_PORT=8000
    MYSQL_PORT=3306

3.      docker-compose up -d
4.      docker-compose run --rm composer install
5.      docker-compose exec app vendor/bin/phinx migrate -c config/phinx/phinx.php
6.      docker-compose exec app vendor/bin/phinx seed:run -c config/phinx/phinx.php
Команды для базы данных:

    # Создание миграции
    docker-compose exec app vendor/bin/phinx create CreateUsersTable -c config/phinx/phinx.php

    # Выполнение миграций
    docker-compose exec app vendor/bin/phinx migrate -c config/phinx/phinx.php

    # Откат миграций
    docker-compose exec app vendor/bin/phinx rollback -c config/phinx/phinx.php

    # Запуск сидеров
    docker-compose exec app vendor/bin/phinx seed:run -c config/phinx/phinx.php

    
Пользователь админ : username: admin, password: admintest
    
 