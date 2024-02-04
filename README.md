<p align="center">
<a href="https://github.com/HPWebdeveloper/laravel-failed-jobs/actions"><img src="https://github.com/HPWebdeveloper/laravel-failed-jobs/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/HPWebdeveloper/laravel-failed-jobs"><img src="https://img.shields.io/packagist/v/HPWebdeveloper/laravel-failed-jobs" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/HPWebdeveloper/laravel-failed-jobs"><img src="https://img.shields.io/packagist/l/HPWebdeveloper/laravel-failed-jobs" alt="License"></a>
</p>

# Laravel Failed Jobs

## Introduction:

If you're running an application with a queue driver other than Redis, which is exclusively supported by [Laravel Horizon](https://github.com/laravel/horizon), you might be missing out on the elegant features that Horizon offers. Laravel Horizon is known for its elegance and a wide range of implemented features. One of its standout features is its ability to present detailed information about failed job payloads and automatically load new failed jobs.

In your specific application, if you've been longing for a similar Horizon-like UI to monitor failed_jobs, the Laravel-Failed-Jobs package has got you covered. This package simplifies the process of visualizing failed jobs.

![Screenshot 2024-02-01 at 7 55 42 PM](https://github.com/HPWebdeveloper/laravel-failed-jobs/assets/16323354/2ec7ebad-1ad9-4927-8bff-5ce4002e1a7c)

## Key Benefits:

- Seamless Integration: You can seamlessly integrate the Laravel-Failed-Jobs package into your project, even if you are already using Laravel Horizon. There's no conflict between the two. 
- While Horizon primarily reads and writes data to Redis, Laravel-Failed-Jobs retrieves data from the failed_jobs table.
- With Laravel-Failed-Jobs, you can enjoy the convenience of monitoring failed jobs in your application, regardless of your queue driver. This package brings the power and elegance of Laravel Horizon's failed job handling to your specific setup.


As you correctly understood, it's important to note that the Laravel-Failed-Jobs package focuses solely on enhancing the visualization of failed jobs and does not offer managing of faild jobs or the comprehensive set of robust features found in Laravel Horizon.


## Installation:
You may install Laravel-Failed-Jobs package into your project using the Composer package manager:

```bash
composer require hpwebdeveloper/laravel-failed-jobs
```

After installing Laravel-Failed-Jobs, publish its assets using the failedjobs:install Artisan command:

```bash
php artisan failedjobs:install
```
## Dashboard

The Laravel-Failed-Jobs dashboard is accessible through the `/failedjobs` route by default. However, if you wish to define a new path, you can do so by setting the `FAILEDJOBS_PATH` variable in the `.env` file.

## Dashboard Authorization

Find `app/Providers/FailedJobsServiceProvider.php` and then follow the [same document of Horizon](https://laravel.com/docs/10.x/horizon#dashboard-authorization) to secure the dashboard in production environment.

![Screenshot 2024-02-01 at 7 54 17 PM](https://github.com/HPWebdeveloper/laravel-failed-jobs/assets/16323354/05abc4ab-ede6-4e90-b713-bc540015435d)

![Screenshot 2024-02-01 at 7 55 27 PM](https://github.com/HPWebdeveloper/laravel-failed-jobs/assets/16323354/30e1dd9e-316b-4d8e-80a4-ef7df195bbcd)


## Licensing

This repository uses two licenses:

- The original codebase is distributed under the MIT License (MIT) (Copyright (c) Taylor Otwell), which you can find in the [LICENSE](https://github.com/HPWebdeveloper/laravel-failed-jobs/blob/main/LICENSE.md) file.

- Any modifications made to the original codebase are subject to our own license, which you can find in the [LICENSE](https://github.com/HPWebdeveloper/laravel-failed-jobs/blob/main/LICENSE.md) file.
