#!/bin/bash

# Lancer PHP-FPM
php-fpm &

# Lancer nginx (il écoute sur 3000 dans notre conf)
nginx -g "daemon off;"
