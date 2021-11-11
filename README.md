Use the following steps to run the application

1. run composer install
2. copy .env.example and rename to .env
3. add your db configuration and add your mailtrap sandbox credentials in .env file
4. run php artisan key:generate
5. run php artisan migrate --seed
6. import postman collection from postman-collection folder for testing the APIs

sign in as admin credentials:

email: admin@admin.com
password: admin

And You are Good to Go :)
