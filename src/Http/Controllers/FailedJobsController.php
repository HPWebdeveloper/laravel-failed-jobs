<?php

namespace HPWebdeveloper\LaravelFailedJobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FailedJobsController extends Controller
{

    public function index(Request $request)
    {
        $perPage = 50;

        $failedJobs = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->paginate($perPage);

        $transformedJobs = $failedJobs->getCollection()->map(function ($job) {
            return $this->decodeDatabaseFailedJob($job);
        });

        $failedJobs->setCollection($transformedJobs);

        return response()->json($failedJobs);
    }

    /**
     * Decode the given failed job from the database.
     *
     * @param  object  $job
     * @return object
     */
    protected function decodeDatabaseFailedJob($job)
    {
        $job->payload = json_decode($job->payload);
        $job->exception = mb_convert_encoding($job->exception, 'UTF-8');

        return $job;
    }

    public function show($uuid)
    {
        $failedJob = DB::table('failed_jobs')->where('uuid', $uuid)->first();

        if (!$failedJob) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return $this->decodeDatabaseJob($failedJob);
    }

    /**
     * Decode the given job from the database.
     *
     * @param  object  $job
     * @return object
     */
    protected function decodeDatabaseJob($job)
    {
        $job->payload = json_decode($job->payload);
        $job->exception = mb_convert_encoding($job->exception, 'UTF-8');

        return $job;
    }
}
