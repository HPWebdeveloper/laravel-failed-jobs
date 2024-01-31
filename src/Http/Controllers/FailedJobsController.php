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


    /**
     * Paginate the failed jobs for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    protected function paginate(Request $request)
    {
        return $this->jobs->getFailed($request->query('starting_at') ?: -1)->map(function ($job) {
            return $this->decode($job);
        });
    }

    /**
     * Paginate the failed jobs for the request and tag.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tag
     * @return \Illuminate\Support\Collection
     */
    protected function paginateByTag(Request $request, $tag)
    {
        $jobIds = $this->tags->paginate(
            'failed:'.$tag, ($request->query('starting_at') ?: -1) + 1, 50
        );

        $startingAt = $request->query('starting_at', 0);

        return $this->jobs->getJobs($jobIds, $startingAt)->map(function ($job) {
            return $this->decode($job);
        });
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

    /**
     * Decode the given job.
     *
     * @param  object  $job
     * @return object
     */
    protected function decode($job)
    {
        $job->payload = json_decode($job->payload);

        $job->exception = mb_convert_encoding($job->exception, 'UTF-8');

        $job->context = json_decode($job->context);

        $job->retried_by = collect(json_decode($job->retried_by))
                    ->sortByDesc('retried_at')->values();

        return $job;
    }
}
