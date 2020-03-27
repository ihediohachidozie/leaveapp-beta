<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header font-weight-bold text-primary">
                        User Leave Summary
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Days Utilized</th>
                                        <th>Days Outstanding</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="leave in leaves.data" :key="leave.id">
                                        <td v-text="leave.year"></td>
                                        <td v-text="leave.days"></td>
                                        <td v-text="leave.outsdays"></td>
                                    </tr>
                                </tbody> 
                            </table>
                        </div>
                        <div class="mt-2">
                            
                            <pagination :data="leaves" @pagination-change-page="getLeave">
                                <span slot="prev-nav">&lt; Previous</span>
                                <span slot="next-nav">Next &gt;</span>
                            </pagination>

                        </div>

                    </div>
                </div>
            </div>
        </div>




    </div>

</template>

<script>
    export default {
        data() {
            return {
                leaves: [],
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getLeave();
            
        },
        methods:{
            getLeave(page = 1) {
                // display departments..
                axios.get('/userleavesummary?page=' + page)
                .then(({ data }) => this.leaves = data);
            }
        }
    }
</script>
