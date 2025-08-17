
commands 

load:permissions

load:menu

php8.1 artisan module:seed AdminUser

php8.1 artisan load:permissions

php8.1 artisan assign:role

php8.1 artisan load:pages

email:template

load:system-config
php artisan module:seed AccessGroup --class=AdminUserPermissionsSeeder


