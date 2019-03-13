# laravel-api

A simple API boilerplate using the Laravel framework.

Made by [Wellington Aguiar](https://github.com/wellcda).

## Requirements

Docker https://docs.docker.com/install/

Docker Compose https://docs.docker.com/compose/install/

## Installation

This boilerplate is fully dockerized, to install it you must fulfil the requirements listed above.

**Before starting the installation adjust the environment files with your desired users and passwords!**

1. Create the .env file and make sure it is configured properly
```bash
cp .env.example .env
```

2. Build the Docker images
```bash
docker-compose up --build
```

3. Then execute the shell script to set up Laravel enviroment. If you're using Windows, open `app-install.sh` and execute the commands manually.
```bash
bash app-install.sh
```

After those commands, you're good to develop your API.

If you're having trouble with Postgres users after building your images for the first time, remove the volume and build the images again.
```
$ docker-compose down
$ docker rm laravel-api_dbdata
$ docker-compose up --build
```

After the containers are up you can access the API at http://localhost:8000/ and Adminer at http://localhost:8080/.

## Commands and Aliases
Start the containers
```bash
docker-compose up
```

Shut down the containers
```bash
docker-compose down
```

Execute a command inside 'app' workspace
```bash
docker-compose exec app
```

Clear composer cache
```bash
docker-compose exec app composer clearcache && docker-compose exec app composer dumpautoload
```

Useful aliases
```bash
alias app='docker-compose exec app'
alias appc='docker-compose exec app composer clearcache && docker-compose exec app composer dumpautoload'
alias dcu='docker-compose up'
alias dcd='docker-compose down'
```

## Built With

* [Laravel 5.8](https://laravel.com/docs/5.8)
* [PostgreSQL](https://www.postgresql.org/docs/)
* [PostGIS](https://postgis.net/)
* [Adminer](https://www.adminer.org/)
* [Laravel Passport](https://github.com/laravel/passport)
* [Laratrust](https://github.com/santigarcor/laratrust)

## License
[ISC](https://choosealicense.com/licenses/isc/)
