## Introduction

This repository aims to supply a small as possible docker environment, readily preconfigured with PHP and Nginx.
Both processes (PHP and Nginx) are supervised which also is the first process (PID 1). The size
of the image currently is 92.8 MB.

## <a name="providedPackages">Provided packages</a>

Alpine 3.20.1

- supervisor
- curl
- php83-fpm
- php83-json
- php83-ldap
- php83-curl
- php83-pdo
- php83-pdo_mysql
- php83-pdo_sqlite
- php83-simplexml
- php83-dom
- php83-ctype
- php83-tokenizer
- php83-xml
- php83-xmlwriter
- php83-session
- php83-pecl-xdebug
- composer
- nginx

## Required packages

- make
- docker
- openssl

## Installation

First, copy the file `.env.dist` into the same directory but name it `.env`. 
Open the newly created dotenv file and adjust the containing environment variables 
to your needs.

Example:
```bash
PROJECT_NAME=this-is-my-new-project-name
```

This variable will be used for the container, the image, the docker network and to identify 
which container should be used when using the make commands.

> **_NOTICE_**<br /> All 'make' commands require to be executed from the root directory, not from the ./app nor ./bin directory.

After setting your project name, execute the following command in your shell.
```bash
./your-project-name $> make first-install
```

This will initially pull the Alpine image and proceeds to install the mentioned 
packages [as listed above](#a-nameprovidedpackagesprovided-packagesa).

## Development

Place your code inside the ```app``` folder. Everything in there is
being mounted into the container. The Webserver is pointing to a
file named ```index.php``` in the ```public``` folder. The absolute
path within the container is ```/var/www/public```.

You can use the existing ```bootstrap.php``` to start with your application.
Usually you'll create a ```src``` folder beside the ```public``` and ```vendor``` folder.
The ```src``` folder is where your application logic lives.

You can also include assets like JavaScript or CSS in an absolute way, 
like ```/css/main.css```. As initially mentioned, the ```public``` folder is the servers root directory.

## Connect to the container

```bash
./your-project-name $> make ssh
```

## Run commands within the container

If you need to execute your own commands within the container, you can use the provided
shell script in the ```bin``` directory. It also respects the user permissions as it's
running with the containers ```www``` user. Because of this, your host machine will have the correct 
user and permissions set when files were created within the container.

Example:
```bash
./your-project-name $> ./bin/run.sh "composer install"
```

You can also run commands as root, which is sometimes useful.

Example:
```bash
./your-project-name $> ./bin/run.sh "apt-get install" true
```

## Debugging with Xdebug

Execute the following command to enable Xdebug:
```bash
./your-project-name $> make xdebug-on
```

Disable it with the following command:
```bash
./your-project-name $> make xdebug-off
```

> **_NOTICE_**<br /> The Xdebug service is listening on Port 9003, please respect that in your configuration.

## Rebuilding the container/image

In some cases you have to rebuild your image and container, especially if you're adding
files within the ```rootfs``` directory. In a regular setup you have to delete the whole stack
and rebuild it to prevent the docker layer cache from kicking in. Because of the way the
image is build in this repository, Docker is able to detect changes in the source of every layer.
This means you can safely just run ```make install``` again and rely on getting your
expected changes into the container. The cache is still active for unchanged layers though.

## Default URL

Call [localhost](https://localhost) in your favorite Browser.
You probably have to accept the custom certificate warning.
