<template>
    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold text-primary">
                Leave Applications
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">
                    <i class="fas fa-plus" ></i>
                        Apply
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Resumption Date</th>
                                <th>Days</th>
                                <th>Leave Type</th>
                                <th>Year</th>
                                <th>Duty Reliever</th>
                                <th>Approval</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr v-for="leave in leaves.data" :key="leave.id" class="text-center">
                                <div v-if="leave.status == 2">
                                    <td> <a href="#" @click="tellme(leave)">{{leave.start_date}}</a> </td>
                                </div>
                                <div v-else-if="leave.status == 0">
                                    <td> <a href="#" @click="tellme(leave)">{{leave.start_date}}</a> </td>
                                </div>
                                <div v-else>
                                    <td v-text="leave.start_date"></td>
                                </div>
                                
                                <td v-text="getEnddate(leave.start_date, leave.days)"></td>
                                <td v-text="getResumedate(leave.start_date, leave.days) "></td>
                                <td v-text="leave.days"></td>
                                <td v-text="leavetype[leave.leave_type]"></td>
                                <td v-text="leave.year"></td>
                                <td v-text="getName(leave.duty_reliever)"></td>
                                <td v-text="getName(leave.approval_id)"></td>
                                <td v-text="status[leave.status]"></td>
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
        </div>

        <!-- Add leave form Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Leave Form</h4>
                        <button type="button" class="close" data-dismiss="modal" @click="clear">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" @submit.prevent="onSubmit"  autocomplete="off" @keydown="clear_error">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Start Date:</label>
                                        <input type="date" v-model="start" class="form-control form-control-user" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="text-primary font-weight-bold">No. of Days:</label>
                                        <input type="number" v-model="days" class="form-control form-control-user" max="30" min="1" >
                                      
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Leave Year:</label>
                                        <select v-model="year" class="form-control">
                                            <option v-for="(key, value) in leave_year" :value="key">
                                                {{key}}
                                            </option>
                                        </select>          
                                    </div>
                                </div>    
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Leave Type:</label>
                                        <select v-model="leave_type" class="form-control"> 
                                            <option v-for="(key, value) in leavetype" :value="value">
                                                {{key}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Duty Relievier:</label>
                                        <select v-model="duty_reliever" class="form-control">
                                            <option v-for="user in users" :value="user.id">{{user.firstname}}{{' '}}{{user.lastname}} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Approved By:</label>
                                        <select v-model="approval_id" class="form-control">
                                            <option v-for="user in approvals" :value="user.id">{{user.firstname}}</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>

                            <div class="form-group text-center">
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Save</button>
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

        <!-- Add leave form Modal -->
        <div class="modal" id="editModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Leave Form</h4>
                        <button type="button" class="close" data-dismiss="modal" @click="clear">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" @submit.prevent="onUpdate"  autocomplete="off" @keydown="clear_error">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Start Date:</label>
                                        <input type="date" v-model="start" class="form-control form-control-user" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="text-primary font-weight-bold">No. of Days:</label>
                                        <input type="number" v-model="days" class="form-control form-control-user" max="30" min="1" >
                                      
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Leave Year:</label>
                                        <select v-model="year" class="form-control">
                                            <option v-for="(key, value) in leave_year" :value="key">
                                                {{key}}
                                            </option>
                                        </select>          
                                    </div>
                                </div>    
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Leave Type:</label>
                                        <select v-model="leave_type" class="form-control"> 
                                            <option v-for="(key, value) in leavetype" :value="value">
                                                {{key}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Duty Relievier:</label>
                                        <select v-model="duty_reliever" class="form-control">
                                            <option v-for="user in users" :value="user.id">{{user.firstname}}{{' '}}{{user.lastname}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="" class="text-primary font-weight-bold">Approved By:</label>
                                        <select v-model="approval_id" class="form-control">
                                            <option v-for="user in approvals" :value="user.id">{{user.firstname}}</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>

                            <div class="form-group text-center">
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Save</button>
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
                start: '',
                days: '',
                year: [],
                leave_year: [],
                leave_type: '',
                leavetype: ["Annual", "Casual", "Maternity", "Paternity", "Study", "Sick", "Sabbatical"],
                duty_reliever: '',
                approval_id: '',
                approvals: [],
                hdays: [],
                rdays: [],
                status: ["Open", "Pending", "Rejected", "Approved"],
                leaves: [],
                users : [],
                user: '' ,
                oldstartdate: '',
                olddays: '',
                leaveid: ''
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
            onSubmit(){
                axios.post('/leave',{
                    start_date : this.start,
                    days : this.days,
                    leave_type: this.leave_type,
                    year : this.year,
                    duty_reliever : this.duty_reliever,
                    approval_id : this.approval_id,
                    status: 1
                })
                .then(this.onSuccess)
                .catch(error => {
                    alert(error.response.data.message);
                     $("#myModal").modal("hide");
                });
               
              //  this.message = "I am here";
            },
            onSuccess(response) {
                alert(response.data.message);
                //alert(response.data.error);
                this.getLeaves();
                this.clear();
                $("#myModal").modal("hide");
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
            clear(){
                this.start = this.days = this.year = this.leave_type = this.duty_reliever = this.approval_id = '';
               // this.message = this.error = '';
            },
            clear_error(){
                this.message = this.error = '';
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
                        return this.users[i].firstname;
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
            tellme(leave)
            {
                this.start = leave.start_date;
                this.days = leave.days;
                this.year = leave.year;
                this.duty_reliever = leave.duty_reliever;
                this.approval_id = leave.approval_id;
                this.leave_type = leave.leave_type;
                this.leaveid = leave.id;
                this.olddays = leave.days;
                this.oldstartdate = leave.start_date;
                $("#editModal").modal("show");
            },
            onUpdate(){
                axios.put('/leave/'+this.leaveid,{
                    start_date : this.start,
                    days : this.days,
                    leave_type: this.leave_type,
                    year : this.year,
                    duty_reliever : this.duty_reliever,
                    approval_id : this.approval_id,
                    oldstartdate : this.oldstartdate,
                    olddays : this.olddays,
                    status: 1
                })
                .then(response =>{
                    alert(response.data.message);
                    $("#editModal").modal("hide");
                    this.getLeaves();
                })
                .catch(error => {
                    alert(error.response.data.message);
                     $("#myModal").modal("hide");
                     //this.getLeaves();
                });                
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
        }
    }
</script>
