<script type="text/ecmascript-6">
import phpunserialize from 'phpunserialize'

export default {
    components: {
        'stack-trace': require('./../../components/Stacktrace').default
    },

    data() {
        return {
            ready: false,
            job: {}
        };
    },

    mounted() {
        this.loadFailedJob(this.$route.params.jobId);
        document.title = "FailedJobs - Failed Jobs";
    },

    methods: {
        loadFailedJob(id) {
            this.ready = false;
            const uri = `${FailedJobs.basePath}/api/${id}?access_token=${window.FailedJobs.access_token}`;
            this.$http.get(uri)
                .then(response => {
                    this.job = response.data;
                    console.log(this.job);
                    this.ready = true;
                });
        },

        prettyPrintJob(data) {
            try {
                return data.command && !data.command.includes('CallQueuedClosure')
                    ? phpunserialize(data.command) : data;
            } catch (err) {
                return data;
            }
        }
    }
}
</script>


<template>
    <div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 v-if="!ready">Job Preview</h5>
                <h5 v-if="ready">{{ job.payload.displayName }}</h5>
            </div>
            <div class="card-body card-bg-secondary" v-if="ready">
                <div class="row mb-2">
                    <div class="col-md-2"><strong>ID</strong></div>
                    <div class="col">{{ job.id }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-2"><strong>Queue</strong></div>
                    <div class="col">{{ job.queue }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-2"><strong>Attempts</strong></div>
                    <div class="col">{{ job.payload.attempts }}</div>
                </div>

                <div class="row mb-2" v-if="prettyPrintJob(job.payload.data).batchId">
                    <div class="col-md-2"><strong>Batch</strong></div>
                    <div class="col">
                        <router-link
                            :to="{ name: 'batches-preview', params: { batchId: prettyPrintJob(job.payload.data).batchId }}">
                            {{ prettyPrintJob(job.payload.data).batchId }}
                        </router-link>
                    </div>
                </div>


                <div class="row mb-2">
                    <div class="col-md-2"><strong>Pushed At</strong></div>
                    <div class="col">{{ readableTimestamp(job.payload.pushedAt) }}</div>
                </div>
                <div class="row">
                    <div class="col-md-2"><strong>Failed At</strong></div>
                    <div class="col">{{ job.failed_at }}</div>
                </div>

            </div>
        </div>


        <!-- Exception Section -->
        <div class="card mt-4" v-if="ready">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>Exception</h5>
            </div>
            <div>
                <stack-trace :trace="job.exception.split('\n')"></stack-trace>
            </div>
        </div>

        <!-- Payload Section -->
        <div class="card mt-4" v-if="ready">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>Payload Data</h5>
            </div>
            <div class="card-body code-bg text-white">
                <vue-json-pretty :data="prettyPrintJob(job.payload)"></vue-json-pretty>
            </div>
        </div>
    </div>
</template>
