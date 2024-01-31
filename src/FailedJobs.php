<?php

namespace HPWebdeveloper\LaravelFailedJobs;

use Closure;
use Exception;
use Illuminate\Support\Facades\File;
use RuntimeException;

class FailedJobs
{
    /**
     * The callback that should be used to authenticate FailedJobs users.
     *
     * @var \Closure
     */
    public static $authUsing;


    /**
     * Indicates if FailedJobs should use the dark theme.
     *
     * @deprecated
     *
     * @var bool
     */
    public static $useDarkTheme = false;

    /**
     * Determine if the given request can access the FailedJobs dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }

    /**
     * Set the callback that should be used to authenticate FailedJobs users.
     *
     * @param  \Closure  $callback
     * @return static
     */
    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Specifies that FailedJobs should use the dark theme.
     *
     * @deprecated
     *
     * @return static
     */
    public static function night()
    {
        static::$useDarkTheme = true;

        return new static;
    }

    /**
     * Get the default JavaScript variables for FailedJobs.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        return [
            'path' => config('failedjobs.path'),
        ];
    }

    /**
     * Determine if FailedJobs's published assets are up-to-date.
     *
     * @return bool
     *
     * @throws \RuntimeException
     */
    public static function assetsAreCurrent()
    {
        $publishedPath = public_path('vendor/failedjobs/mix-manifest.json');

        if (! File::exists($publishedPath)) {
            throw new RuntimeException('FailedJobs assets are not published. Please run: php artisan failedjobs:publish');
        }

        return File::get($publishedPath) === File::get(__DIR__.'/../public/mix-manifest.json');
    }
}
