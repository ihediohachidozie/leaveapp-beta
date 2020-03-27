<template>
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold text-primary">
                Approved Leave
                <button type="button" class="btn btn-danger float-right" @click="getLeaves()">
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
                        <table class="table table-striped" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-left">Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <!-- <th>Resumption Date</th> -->
                                    <th>Days</th>
                                    <th>Leave Type</th>
                                    <th>Year</th>
                                    <!-- <th>Duty Reliever</th> -->
                                    <th>Approval</th>
                                    <th>Action</th> 
                                </tr>
                            </thead>
                            <tbody> 
                                <tr v-for="leave in leaves.data" :key="leave.id" class="text-center">
                                    <td v-text="getName(leave.user_id)" class="text-left"></td>
                                    
                                    <td> {{leave.start_date}} </td>
                                    
                                    
                                    <td v-text="getEnddate(leave.start_date, leave.days)"></td>
                                    <!--  <td v-text="getResumedate(leave.start_date, leave.days) "></td> -->
                                        <td v-text="leave.days"></td>
                                        <td v-text="leavetype[leave.leave_type]"></td>
                                        <td v-text="leave.year"></td>
                                    <!--  <td v-text="getName(leave.duty_reliever)"></td> -->
                                    <td v-text="getName(leave.approval_id)"></td>
                                    <td> <a href="#" @click="onStatus(leave.id)"><i class="fas fa-recycle" style="font-size:20px; color:green;"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                   

                        <div class="mt-2">
                            
                            <pagination :data="leaves" @pagination-change-page="getLeaves">
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
                message: '',
                error: '',
               // start: '',
               // days: '',
                year: '',
                leave_year: [],
               // leave_type: '',
                leavetype: ["Annual", "Casual", "Maternity", "Paternity", "Study", "Sick", "Sabbatical"],
                duty_reliever: '',
                user_id: '',
                approvals: [],
                hdays: [],
                rdays: [],
                status: ["Open", "Pending", "Rejected", "Approved"],
                app_status: '',
                comment: '',
                firstname: '',
                leaves: [],
                users : [],
                leaveid: '',
                //user: '' ,
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.getUsers();
            this.getHolidays();
            this.getLeaves();
            this.getApprovals();
            this.getYear();


        },
        methods:{

            onStatus(id){
                axios.put('/leave.status/'+id,{
                    status : 0,
                })
                .then(this.onSuccess)
                .catch(error => {
                    alert(error.response.data.message);
                });
                
            },
            onSuccess(response) {
                alert(response.data.message);
                //alert(response.data.error);
                this.getLeaves();
            },
            getUsers(){
                axios.get('/users')
                .then(({data}) => this.users = data);              
            },
            getApprovals(){
                axios.get('/approvals')
                .then(({data}) => this.approvals = data);              
            },

            getHolidays(){
                axios.get('/holidays')
                    .then(({data}) => this.hdays = data);
            },
            getLeaves(page = 1){
                axios.get('/leave?page=' + page)
                .then(({data}) => this.leaves = data);
            },
            getEnddate(s,d){
                //alert(s);
                var n = new Date(s);
                var x = 0;
              //  var d = d;
                var z, u, b, c;
                var w = this.hdays;
                var t = [];

                // Public Holidays...
                for(b of w){
                    c = new Date(b).toLocaleDateString();
                    t.push(c);
                }

                var i = 1;

                while(x < d - 1)
                {
                    n.setDate(n.getDate() + i);

                    z = n.getDay(); // week day in numeric value

                    if(z != 0 && z != 6)
                    {
                        if(t.includes(n.toLocaleDateString()) == false)
                        {
                            x++;

                        }
                    }
                }
                return n.toLocaleDateString();                
            },
            getResumedate(s,d){
                var n = new Date(s);
                var x = 0;
               // var d = this.days;
                var z, u, b, c;
                var w = this.hdays;
                var t = [];

                // Public Holidays...
                for(b of w){
                    c = new Date(b).toLocaleDateString();
                    t.push(c);
                }

                var i = 1;

                while(x < d)
                {
                    n.setDate(n.getDate() + i);

                    z = n.getDay(); // week day in numeric value

                    if(z != 0 && z != 6)
                    {
                        if(t.includes(n.toLocaleDateString()) == false)
                        {
                            x++;

                        }
                    }
                }
                return n.toLocaleDateString();                
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
              //  alert(this.users[0].id);

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
                    axios.get('/leave.search/'+this.user_id+'/'+this.year)
                    .then(({data}) => this.leaves = data);
                    
                }
                else if (this.user_id != ""){
                    axios.get('/leave.searchuser/'+this.user_id)
                    .then(({data}) => this.leaves = data);                    
                }
                else if (this.year != ""){
                    axios.get('/leave.searchyear/'+this.year)
                    .then(({data}) => this.leaves = data);                    
                }
                else{
                    alert("Please select the staff name and leave year");
                }
               this.getResult();
                
            }
        },
        computed:{
            resume(){

                // resumption date
                var n = new Date('2020-02-10');
                var x = 0;
                var d = 10;
                var z, u, b, c;
                var w = this.hdays;
                var t = [];

                // Public Holidays...
                for(b of w){
                    c = new Date(b).toLocaleDateString();
                    t.push(c);
                }

                var i = 1;

                while(x < d)
                {
                    n.setDate(n.getDate() + i);

                    z = n.getDay(); // week day in numeric value

                    if(z != 0 && z != 6)
                    {
                        if(t.includes(n.toLocaleDateString()) == false)
                        {
                            x++;

                        }
                    }
                }
                return n.toLocaleDateString();
            },

            end_date(){
                // ending date
                var n = new Date("2020-02-11");
                var x = 0;
                var d = 10;
                var z, u, b, c;
                var w = this.hdays;
                var t = [];

                // Public Holidays...
                for(b of w){
                    c = new Date(b).toLocaleDateString();
                    t.push(c);
                }

                var i = 1;

                while(x < d - 1)
                {
                    n.setDate(n.getDate() + i);

                    z = n.getDay(); // week day in numeric value

                    if(z != 0 && z != 6)
                    {
                        if(t.includes(n.toLocaleDateString()) == false)
                        {
                            x++;

                        }
                    }
                }
                return n.toLocaleDateString();
            },
            getArray(){
                //alert(this.hdays);

                var i;
                var x=this.hdays.length;
                for(i=0; i < x; i++){
                    this.hdays[i] = this.hdays[i];
                } 
                return this.hdays;
            },
 
            getUser(){
                var i;
                var x=this.users.length;
                for(i=0; i < x; i++){
                    this.users[i] = this.users[i];
                    //x += 1;
                } 
                return this.users;              
            },
            getResult(){
               // $("#myModal").modal("hide");
                return this.leaves;

            }


        }

        

    }
</script>
