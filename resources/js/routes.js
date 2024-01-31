export default [
    {
        path: '/',
        name: 'dashboard',
        component: require('./screens/failedJobs/index').default,
    },
    {
        path: '/:jobId',
        name: 'failed-jobs-preview',
        component: require('./screens/failedJobs/job').default,
    },
];
