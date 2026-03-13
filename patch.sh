#!/bin/sh
# Добавляем error reporting в начало index.php
sed -i '2a error_reporting(E_ALL);' /app/public/index.php
sed -i '3a ini_set("display_errors", 1);' /app/public/index.php
echo "Done"
cat /app/public/index.php | head -10
