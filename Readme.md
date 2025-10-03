
1.      git clone <repo url>

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
 