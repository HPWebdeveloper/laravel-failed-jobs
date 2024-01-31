<script type="text/ecmascript-6">
export default {
    /**
     * The component's data.
     */
    data() {
        return {
            ready: false,
            loadingNewEntries: false,
            hasNewEntries: false,
            page: 1,
            perPage: 50,
            totalPages: 1,
            jobs: [],
            latestJobUuid: null,
        };
    },

    /**
     * Prepare the component.
     */
    mounted() {
        document.title = "FailedJobs - Failed Jobs";

        this.loadJobs();

        this.refreshJobsPeriodically();
    },

    /**
     * Clean after the component is destroyed.
     */
    destroyed() {
        clearInterval(this.interval);
    },


    /**
     * Watch these properties for changes.
     */
    watch: {
        '$route'() {
            this.page = 1;

            this.loadJobs();
        },
    },


    methods: {

        /**
         * Check for new failed jobs.
         */
        checkForNewJobs() {
            if (!this.$root.autoLoadsNewEntries) {
                this.$http.get(FailedJobs.basePath + `/api/?page=1&perPage=1`)
                    .then(response => {
                        const newLatestJob = response.data.data[0];
                        if (newLatestJob && newLatestJob.uuid !== this.latestJobUuid) {
                            this.hasNewEntries = true;
                        }
                    });
            }
        },


        loadNewEntries() {
            this.page = 1;
            this.loadJobs(false);
            this.hasNewEntries = false;
            this.latestJobUuid = null; // Reset latest job UUID
        },


        /**
         * Refresh the jobs every period of time.
         */
        refreshJobsPeriodically() {
            this.interval = setInterval(() => {
                this.checkForNewJobs(); // Check for new jobs
                this.loadJobs(true); // Load jobs without resetting the ready state
            }, 3000);
        },

        /**
         * Load the jobs of the given tag.
         */
        loadJobs(refreshing = false) {
            if (!refreshing) {
                this.ready = false;
            }

            this.$http.get(FailedJobs.basePath + `/api/?page=${this.page}&perPage=${this.perPage}`)
                .then(response => {
                    // Only update jobs list if auto-loading is enabled or it's not a refresh
                    if (this.$root.autoLoadsNewEntries || !refreshing) {
                        this.jobs = response.data.data;
                        this.totalPages = response.data.last_page;
                    }

                    // Set the latest job UUID for new entries check
                    if (this.jobs.length > 0 && !this.latestJobUuid) {
                        this.latestJobUuid = this.jobs[0].uuid;
                    }

                    this.ready = true;
                });
        },

        /**
         * Load the jobs for the previous page.
         */
        previous() {
            if (this.page > 1) {
                this.page--;
                this.loadJobs();
            }
        },

        /**
         * Load the jobs for the next page.
         */
        next() {
            if (this.page < this.totalPages) {
                this.page++;
                this.loadJobs();
            }
        },
    }
}
</script>

<template>
    <div>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>Failed Jobs</h5>
            </div>

            <div v-if="!ready" class="d-flex align-items-center justify-content-center card-bg-secondary p-5 bottom-radius">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="icon spin mr-2 fill-text-color">
                    <path d="M12 10a2 2 0 0 1-3.41 1.41A2 2 0 0 1 10 8V0a9.97 9.97 0 0 1 10 10h-8zm7.9 1.41A10 10 0 1 1 8.59.1v2.03a8 8 0 1 0 9.29 9.29h2.02zm-4.07 0a6 6 0 1 1-7.25-7.25v2.1a3.99 3.99 0 0 0-1.4 6.57 4 4 0 0 0 6.56-1.42h2.1z"></path>
                </svg>

                <span>Loading...</span>
            </div>


            <div v-if="ready && jobs.length == 0" class="d-flex flex-column align-items-center justify-content-center card-bg-secondary p-5 bottom-radius">
                <span>There aren't any failed jobs.</span>
            </div>

            <table v-if="ready && jobs.length > 0" class="table table-hover table-sm mb-0">
                <thead>
                <tr>
                    <th>Job</th>
                    <th>Failed At</th>
                </tr>
                </thead>

                <tbody>
                <tr v-if="hasNewEntries" key="newEntries" class="dontanimate">
                    <td colspan="100" class="text-center card-bg-secondary py-1">
                        <small><a href="#" v-on:click.prevent="loadNewEntries" v-if="!loadingNewEntries">Load New Entries</a></small>

                        <small v-if="loadingNewEntries">Loading...</small>
                    </td>
                </tr>

                <tr v-for="job in jobs" :key="job.id">
                    <td>
                        <router-link :title="job.payload.displayName" :to="{ name: 'failed-jobs-preview', params: { jobId: job.uuid }}">
                            {{ job.payload.displayName }}
                        </router-link>
                        <br>
                        <small class="text-muted">
                            Queue: {{job.queue}}
                            | Attempts: {{ job.payload.attempts }}
                        </small>
                    </td>

                    <td class="table-fit">
                        {{ job.failed_at }}
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-if="ready && jobs.length" class="p-3 d-flex justify-content-between border-top">
                <button @click="previous" class="btn btn-secondary btn-md" :disabled="page==1">Previous</button>
                <button @click="next" class="btn btn-secondary btn-md" :disabled="page>=totalPages">Next</button>
            </div>
        </div>

    </div>
</template>
