<p align="center">
<a href="https://github.com/HPWebdeveloper/laravel-failed-jobs/actions"><img src="https://github.com/HPWebdeveloper/laravel-failed-jobs/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/HPWebdeveloper/laravel-failed-jobs"><img src="https://img.shields.io/packagist/v/HPWebdeveloper/laravel-failed-jobs" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/HPWebdeveloper/laravel-failed-jobs"><img src="https://img.shields.io/packagist/l/HPWebdeveloper/laravel-failed-jobs" alt="License"></a>
</p>

# Laravel Failed Jobs

## Introduction:

If you're running an application with a queue driver other than Redis or dispatch a job to 
[a particular connection](https://laravel.com/docs/10.x/queues#dispatching-to-a-particular-connection) 
other than Redis, which is exclusively supported by [Laravel Horizon](https://laravel.com/docs/10.x/horizon), 
you might be missing out on the elegant features that Horizon offers. 
Laravel Horizon is known for its elegance and a wide range of implemented features. 
One of its standout features is its ability to present detailed information about failed job payloads 
and automatically load new failed jobs.

In your specific application, if you've been longing for a similar Horizon-like UI to monitor failed jobs, 
the Laravel-Failed-Jobs package has got you covered. This package streamlines the visualization of failed jobs, 
eliminating the need to connect to a database client and search through `failed_jobs` records to identify 
the cause of a job's failure.

![Screenshot 2024-02-01 at 7 55 42 PM](https://github.com/HPWebdeveloper/laravel-failed-jobs/assets/16323354/2ec7ebad-1ad9-4927-8bff-5ce4002e1a7c)

## Key Benefits:

- Seamless Integration: You can seamlessly integrate the Laravel-Failed-Jobs package into your project, 
even if you are already using Laravel Horizon. There's no conflict between the two. 
- While Horizon primarily reads and writes data to Redis, Laravel-Failed-Jobs retrieves 
data from the failed_jobs table.
- With Laravel-Failed-Jobs, you can enjoy the convenience of monitoring failed jobs in your application, 
regardless of your queue driver. This package brings the elegance 
of Laravel Horizon's failed jobs UI to your specific setup.
- Remote Connection Mode: This package offers two operational modes, each with its distinct setup. 
The Basic mode setup enabling dashboard authentication mirror that of Laravel Horizon. 
Remote mode is particularly beneficial for applications functioning solely as API service providers, 
where there is no user model or admin user. 
In such scenarios, the Gate definition within `FailedJobsServiceProvider`, 
which typically restricts access the Laravel-Failed-Jobs dashboard in non-local environments, 
is not applicable.
Remote mode enables access to the main application from a separate Laravel application, 
hosted on a distinct URL address, in both local and production environments.

As you correctly understood, it's important to note that the Laravel-Failed-Jobs package focuses 
solely on enhancing the visualization of failed jobs and does not offer managing of failed jobs or 
the comprehensive set of robust features found in Laravel Horizon.

## I - Installation (basic mode):
If you have already installed Laravel Horizon, you can still install Laravel-Failed-Jobs without any conflict.
Also it follows the same installation process as Laravel Horizon.

You may install Laravel-Failed-Jobs package into your project using the Composer package manager:

```bash
composer require hpwebdeveloper/laravel-failed-jobs
```

After installing Laravel-Failed-Jobs, publish the assets using the `failedjobs:install` Artisan command.
```bash
php artisan failedjobs:install
```
This command will automatically publish the `failedjobs` config file, `FailedJobsServiceProvider` 
service provider and also the resource view files into the `public/vendor/failedjobs` directory.

To upgrade the package, you need to use the following command after upgrading via composer:

```bash
php artisan failedjobs:publish
```

You may need to modify the `FailedJobsServiceProvider` to determine 
who can access the dashboard in production environment.


## Dashboard

The Laravel-Failed-Jobs dashboard is accessible through the `/failedjobs` route by default. 
However, if you wish to define a new path, you can do so by setting the `FAILEDJOBS_PATH` variable in the `.env` file.

## Dashboard Authorization

The`app/Providers/FailedJobsServiceProvider.php` class applies Laravel `Gate` to determine 
who can access FailedJobs in non-local environments. You need to follow the 
[same document of Horizon](https://laravel.com/docs/10.x/horizon#dashboard-authorization) 
to secure the dashboard in production environment.

![Screenshot 2024-02-01 at 7 54 17 PM](https://github.com/HPWebdeveloper/laravel-failed-jobs/assets/16323354/05abc4ab-ede6-4e90-b713-bc540015435d)

![Screenshot 2024-02-01 at 7 55 27 PM](https://github.com/HPWebdeveloper/laravel-failed-jobs/assets/16323354/30e1dd9e-316b-4d8e-80a4-ef7df195bbcd)

## II - Installation (remote mode):
Remote mode enables access to the main application from a separate Laravel application,
hosted on a distinct URL address, in both local and production environments.

### Prepare package in both applications
To use this package in remote mode, you need to install the package in both main and remote applications.

You may install the Laravel-Failed-Jobs package into your project using the composer package manager:

Note: currently the remote feature is under `feature/remote-connect` branch.
```bash
composer require hpwebdeveloper/laravel-failed-jobs:dev-feature/remote-connect
```
Then install the assets using the `failedjobs:install` Artisan command.
```bash
php artisan failedjobs:install
```

### Prepare the environments variables

In this mode you need to propertly configure the following environment variables in both applications:

```bash
    'axios_base_url' => env('AXIOS_BASE_URL', ''),
    'server_access_token' => env('FAILEDJOBS_SERVER_ACCESS_TOKEN'),
    'dashboard_access_token' => env('FAILEDJOBS_DASHBOARD_ACCESS_TOKEN'),
```

`axios_base_url` is the base URL of the main application which you have to set in the local/remote application.
Setting this variable is mandatory in remote mode. 

`server_access_token` is the access token to access the main application from the remote application. 
It is mandatory to set this variable in the main application `.env` file. 
It is mandatory to set `dashboard_access_token` variable in the 
remote application equal to the value of the `server_access_token` in the main application.

### Secure the endpoint
As in the `failedjobs` config file defined, the dashboard is accessible through the `/failedjobs` route 
by default.
But it is recommended to change it when using the package in the remote mode. 

It is simply possible by setting the `FAILEDJOBS_PATH` variable in the `.env` 
file of both applications with a hash value and then define that value as a path in the `cors` config file 
of the main application.

Modifying the `cors` config file in the main application is required because in the remote mode we are
dealing with two different applications served in two different URLs. 
Laravel automatically respond to Cross-Origin Resource Sharing (CORS) 
OPTIONS HTTP requests with values that you configure in the `cors` config file. 
Read more about [CORS](https://laravel.com/docs/10.x/routing#cors).

Hence in summary
- Set the `FAILEDJOBS_PATH` variable in the `.env` file of the **main** application 
with a hash value like `failedjobs_4a5b6c7d`
- Set the `FAILEDJOBS_PATH` variable in the `.env` file of the **remote** application 
with a hash value like `failedjobs_4a5b6c7d`
- Open the `cors.php` config file of the main application and modify the following code accordingly:
```php
// before
        'paths' => ['api/*', 'sanctum/csrf-cookie'],
// after
        'paths' => ['failedjobs_4a5b6c7d/*', 'api/*', 'sanctum/csrf-cookie'],
```

Then access the dashboard using the following URL: `http://your-local-application.test/failedjobs_4a5b6c7d`.

As your main application is configured like `APP_ENV=production` 
the Failed-Jobs dashboard is not accessible in the production environment.
While you can access the dashboard in the local environment through this URL: `http://your-local-application.test/failedjobs_4a5b6c7d`.

Of course you can access the dashboard in the main application 
if you set the `axios_base_url` variable in the main application `.env` file as well.

## Licensing

This repository uses two licenses:

- The original codebase is distributed under the MIT License (MIT) (Copyright (c) Taylor Otwell), 
which you can find in the [LICENSE](https://github.com/HPWebdeveloper/laravel-failed-jobs/blob/main/LICENSE.md) file.

- Any modifications made to the original codebase are subject to our own license, 
which you can find in the [LICENSE](https://github.com/HPWebdeveloper/laravel-failed-jobs/blob/main/LICENSE.md) file.
