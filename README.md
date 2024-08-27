# Accordion-nuxt
 Accordion app powered by Laravel 11 API and Nuxt 2
## Prerquisties
Node 14.x or 16.x, PHP 8.2 or 8.3, recommended Laragon or other localhost alternatives (WAMP or XAMPP) 
## Installation
### Nuxt installation
1. Change directory to front-accordion in terminal ```cd front-accordion```
2. Install packages with ```npm install```
3. Launch app with ```npm run dev```
Extra if you want to deploy for production use ```npm run build``` & ```npm run start```
### Laravel installaion
1. Change directory to front-accordion in terminal ```cd back-end-accordion```
2. Install composer back-end dependencies - ```composer install```
3. Copy .env file from example ```cp .env.example .env```
4. Insert your prefered DB credentials to .env file or use Sqlite default one
5. Run ```php artisan key:generate```
6. Run ```php artisan migrate```. If you want test data run ```php artisan migrate --seed``` 
7. Laragon users: just launch Laragon (projects are started for you by default). Other localhost providers you must run ```php artisan serve``` command to launch the server

 
