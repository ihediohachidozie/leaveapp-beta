<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header font-weight-bold text-primary">
                        All Users' Leave Summary
                        <button type="button" class="btn btn-danger float-right" @click="getLeave()">
                            <i class="fas fa-sync"></i>
                                Refresh
                        </button>
                        <button type="button" class="btn btn-primary float-right mr-2" data-toggle="modal" data-target="#myModal">
                            <i class="fas fa-filter"></i>
                                Filter
                        </button>
                    </div>
 
                    <div class="card-body">
                        <div v-if="leaves.total != 0">
                            <div class="table-responsive">
                                <table class="table table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Name</th>
                                            <th>Year</th>
                                            <th>Days Utilized</th>
                                            <th>Days Outstanding</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="leave in leaves.data" :key="leave.id">
                                            <td v-text="getName(leave.user_id)" class="text-left"></td>
                                            <td v-text="leave.year"></td>
                                            <td v-text="leave.days"></td>
                                            <td v-text="leave.outsdays"></td>
                                        </tr>
                                    </tbody> 
                                </table>
                                <div class="mt-2">
                                    
                                    <pagination :data="leaves" @pagination-change-page="getLeave">
                                        <span slot="prev-nav">&lt; Previous</span>
                                        <span slot="next-nav">Next &gt;</span>
                                    </pagination>

                                </div>
                            </div>

                        </div>
                        <div v-else>
                            <p class="text-center">No Record Found</p>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- Add leave filter form Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Search Form</h4>
                        <button type="button" class="close" data-dismiss="modal" @click="clear">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="text-danger center">Select either one or both to search:</div>
                        <form action="" @submit.prevent="onSearch"  autocomplete="off">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                   <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Staff:</label>
                                        <select v-model="user_id" class="form-control">
                                            <option value=""></option>
                                            <option v-for="user in users" :value="user.id">{{user.firstname}}{{ }} {{user.lastname}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Leave Year:</label>
                                        <select v-model="year" class="form-control">
                                            <option value=""></option>
                                            <option v-for="(key, value) in leave_year" :value="key">
                                                {{key}}
                                            </option>
                                        </select>          
                                    </div>
                                </div> 
                            </div>
         
                            <div class="form-group text-center">
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Search</button>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal" @click="clear">Close</button>
                                    </div>
                                </div>

                            </div>

                        </form>
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
                users : [],
                year: '',
                leave_year: [],
                user_id: '',
                year: ''
                
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getLeave();
            this.getUsers();
            this.getYear();;

            
        },
        methods:{

            getLeave(page = 1) {
                // display departments..
                axios.get('/alluserleavesum?page=' + page)
                .then(({ data }) => this.leaves = data);
            },
            getUsers(){
                axios.get('/users')
                .then(({data}) => this.users = data);              
            },
            getName(n){
                var x = 0;
                var i;
                var y = this.users.length;
                for(i=0; i < y; i++ ){
                    if(n == this.users[i].id){
                        return this.users[i].firstname + ' ' + this.users[i].lastname;
                    }
                }
            },
            getYear(){
                var i;
                var x = 0;
                for(i = 2007; i < 2050; i++){
                    this.leave_year[x] = i;
                    x += 1;
                }
                return this.leave_year;
            },
            clear(){
                this.user_id = this.year = '';
            },
            onSearch(){
                if(this.user_id != "" && this.year != ""){
                    axios.get('/leavesum.search/'+this.user_id+'/'+this.year)
                    .then(({data}) => this.leaves = data);
                    
                }
                else if(this.user_id != ""){
                    axios.get('/leavesum.searchuser/'+this.user_id)
                    .then(({data}) => this.leaves = data);                    
                }
                else if(this.year != ""){
                    axios.get('/leavesum.searchyear/'+this.year)
                    .then(({data}) => this.leaves = data);                    
                }
                else{
                    alert("Please select the staff name and leave year");
                }
               this.getResult();
               
            }
        },
        computed:{
            getResult(){
               // $("#myModal").modal("hide");
                return this.leaves;

            }
        }
    }
</script>
