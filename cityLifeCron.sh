#!/bin/bash

php /var/www/citylife/yii sitemap-cities/index
php /var/www/citylife/yii afisha-parser/parse-kino
php /var/www/citylife/yii karabas-parser/parse-concert