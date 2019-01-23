# laravel-api

A simple API boilerplate using the Laravel framework.

Made by [Wellington Aguiar](https://github.com/wellcda).

## Requirements

Docker https://docs.docker.com/install/

Docker Compose https://docs.docker.com/compose/install/

## Instalation

This boilerplate is fully dockerized, to install it you must fulfil the requirements listed above.

**Before starting the installation I recommend adjusting the environment files with your desired users and passwords!**

First, build the docker images with
```bash
docker-compose up --build
```

Then execute the shell script to set up Laravel enviroment
```bash
bash app-install.sh
```

After those two commands, you're good to develop your API.

## Built With

* [Larevel 5.7](https://laravel.com/docs/5.7)
* [PostgreSQL](https://www.postgresql.org/docs/)
* [Adminer](https://www.adminer.org/)

## License
[ISC](https://choosealicense.com/licenses/isc/)
