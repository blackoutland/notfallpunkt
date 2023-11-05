#!/bin/bash

cd /var/www
composer install
composer dump
export START_TIME = $(date +"%Y-%m-%d %T");
