# About this package

Transform your existing PHP Application into a deployable docker container based on the minimal .

# What does ASPX stand for?

ASPX is an acronym and stands for *A*lpine *S*upervisor *P*HP *N*ginx. 

# Requirements

- PHP (maybe removed as requirement in the future)
- Docker
- Make

# Installation

### Composer

```bash
$> composer require fkde/aspx
```

From your application root, call the following command:

```bash
$> ./vendor/bin/aspx install
```

After a few seconds you should be informed about the container being started. 
You may now call https://localhost in your favorite browser.

### How this works

When the installation went as smooth as expected you will notice a few changes in your project.
There should be several files and a folder added.

In detail, these are:

*docker/*: 
This folder contains your container definition. 
You can change anything you want and just run `make install` again afterward.

*.env*: 
Is created only when there is no .env present.

*Makefile*:
To provide you a set of helpers which makes it easier to develop.
Fore more information, take a look at the origin of this project:
https://github.com/fkde/aspx

# Usage

# Database

We can utilize the `docker-compose.yml` to get a database connected.
Just uncomment the additional service and provide your credentials in the .env file.

If you had your own .env file before, just add the required variables to it.

