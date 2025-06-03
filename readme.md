# Pictionnary 

![Symfony Badge](https://img.shields.io/badge/symfony-7.2.6-blue.svg?logo=symfony&logoColor=fff&style=flat)
![Nginx Badge](https://img.shields.io/badge/nginx_alpine-1.25-green.svg?logo=nginx&logoColor=fff&style=flat)
![PHP Badge](https://img.shields.io/badge/php-8.4-yellow.svg?logo=php&logoColor=fff&style=flat)
![MySQL Badge](https://img.shields.io/badge/mysql-latest-purple.svg?logo=mysql&logoColor=fff&style=flat)



## Docker Set up

1. Install Docker on your local machine:

| OS      | Tutorial URL                                    |
| ------- | ----------------------------------------------- |
| LinuxOS | https://docs.docker.com/engine/install/ubuntu/  |
| MacOS   | https://www.docker.com/products/docker-desktop/ |


### Project Set Up

1.Clone the .env.example file to .env.local 

2.Install projet 

```
make init-project
```
This command install all container , composer and dependencies , and create the database et add fixtures

3.after clone the repository pictionnary_ia and run the command

```docker compose up -d --build```