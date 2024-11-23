# About this package

Transform your existing PHP Application into a deployable docker container based on Alpine Linux.

# What you'll get

- PHP 8.3
- Nginx 1.26.2
- Curl

### PHP Modules

- php83-fpm
- php83-json
- php83-ldap
- php83-curl
- php83-phar
- php83-pdo
- php83-iconv
- php83-mbstring
- php83-pdo_mysql
- php83-simplexml
- php83-dom
- php83-ctype
- php83-tokenizer
- php83-xml
- php83-xmlwriter
- php83-session
- php83-pecl-xdebug

Everything together is just as big as ~92.4Mb, which makes it ideal for fast and storage friendly CI/CD Pipelines. 
With npm and Node.js installed it is barely reaching ~150Mb.

# Requirements

- Linux (Windows is coming soon, WSL is working fine)
- PHP (maybe removed as a requirement in the future)
- Docker
- Make

# Installation

## Composer

```bash
$> composer require fkde/aspx
```

From your application root, call the following command:

```bash
$> ./vendor/bin/aspx install
```

After a few seconds you should be informed about the container being started. 
You should now be able to call https://localhost in your favorite browser.

When the installation finished you will notice a few changes in your project.
There should be several new files and a folder added.

### Added files

| Entity                 | Description                                                                                                                                                                                                 |
|------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **docker/:**           | <span style="font-size: .7rem">This folder contains your container definition. You can change anything you want and just run `make install` again afterward.</span>                                         |
| **docker-compose.yml** | <span style="font-size: .7rem">This file contains the service definitions for docker.</span>                                                                                                                |
| **.env:**              | <span style="font-size: .7rem">Is created only when there is no .env present.</span>                                                                                                                        |
| **Makefile:**          | <span style="font-size: .7rem">This file provides a set of useful helpers, like a switch for Xdebug. Fore more information, take a look at the origin of this project: https://github.com/fkde/aspx </span> |

You are now theoretically able to develop your application within Docker. 
Practically, you'll probably need a database to store your visitors' data.

# Attaching a Database

We can utilize the `docker-compose.yml` to get a database attached.
Just uncomment the additional service and provide your credentials in the .env file.

If you had your own .env file before, just add the required variables to it.

# FAQ

### Why do I need PHP on my Host Machine installed?

As this is a Composer package, and Composer itself depends on PHP this is something which is not that easy to achieve. 
However, it is planned to remove this dependency in the future by using another docker container with only php and composer to get the initial setup done.

### Is it possible to run Laravel with this setup?
Yes of course, Laravel works like a charm with this setup. Just keep in mind that you need to have npm and NodeJS somewhere installed. 
It is possible to do that in the container though. It also decreases the resulting image size significantly (~124Mb instead of ~1.66Gb)

### What does ASPX mean?
ASPX is nearly an acronym and stands for **A**lpine **S**upervisor **P**HP Ngin**X**.
