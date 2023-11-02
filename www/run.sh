#!/bin/bash

cd /var/www
composer install

export START_TIME = $(date +"%Y-%m-%d %T");
