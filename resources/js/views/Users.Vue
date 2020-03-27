<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header font-weight-bold text-primary">
                        Users Management
                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-left"> Name </th>
                                    <th>Username</th>
                                    <th >Staff ID</th>
                                    <th colspan="3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id" class="text-center">
                                    <td v-text="user.firstname + ' ' + user.lastname" class="text-left"></td>
                                    <td v-text="user.username"></td>
                                    <td v-text="user.staff_id"></td>
                                    <td><a @click="editUser(user)" ><i class="fa fa-edit" style="font-size:20px; color:dodgerblue"></i></a></td>
                                    <td>
                                        <a @click="password(user.id)"><i class="fas fa-user-cog" style="font-size:20px; color:green"></i>                                </a>
                                    </td>
                                    <td>
                                        <a @click="confirm(user.id)"><i class="fa fa-trash" style="font-size:20px; color:red"></i>                                </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-2">
                         
                            <pagination :data="users" @pagination-change-page="fetchData">
                                <span slot="prev-nav">&lt; Previous</span>
                                <span slot="next-nav">Next &gt;</span>
                            </pagination>

                        </div>
                    </div>
                </div>
            </div>
        </div>
  
        <!-- Edit Category form Modal -->
        <div class="modal" id="editModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">User Activation</h4>
                        <button type="button" class="close text-red" data-dismiss="modal" @click="clear">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" @submit.prevent="onUpdate(user_id)"  autocomplete="off" @keydown="clear_error">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">Fullname:</span>
                                        </div>
                                        <input type="text" v-model="fullname" class="form-control form-control-user" disabled>
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">Staff ID:</span>
                                        </div>
                                        <input type="text" v-model="staffid" class="form-control form-control-user" disabled>
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">Department:</span>
                                        </div>
                                        <input type="text" v-model="department" class="form-control form-control-user" disabled>
                                       
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">Email:</span>
                                        </div>
                                        <input type="text" v-model="email" class="form-control form-control-user" disabled>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        
                                        <select v-model="category_id" class="form-control">
                                            <option v-for="category in categories" :value="category.id">{{category.category}}</option>
                                        </select>                                      
                                    
                                    </div>
                                    <div class="form-group">
                                        
                                        <div class="custom-control custom-checkbox">      
                                            <input type="checkbox" class="custom-control-input" id="permission" v-model="permission">                                         
                                            <label class="custom-control-label" for="permission">Approval Right</label>
                                        </div>
                                      
                                    </div>
                                    <hr>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">Assign</button>               
                                    <button type="button" class="btn btn-danger btn-user btn-block" data-dismiss="modal">Close</button>               

                                </div>
                            
                            </div>



                        </form>

                    </div>

                </div>
            </div>
        </div>
        <!-- The delete post Modal  -->
        <div class="modal" id="resetUser">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Attention User</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>Do you want to Reset this User's password to "password" ? </p>
                    </div>
        
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" @click="onReset" class="btn btn-success" data-dismiss="modal">Yes</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">No</button>
                    </div>
        
                </div>
            </div>
        </div>
        <!-- The delete post Modal  -->
        <div class="modal" id="deleteUser">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Attention User</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>Do you want to delete this User? </p>
                    </div>
        
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" @click="onDelete" class="btn btn-success" data-dismiss="modal">Yes</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">No</button>
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
                data: [],
                users: {},
                user_id: '',
                fullname: '',
                staffid: '',
                department: '',
                email: '',
                category: '',
                categories: [],
                permission: '',
                category_id: '',
               // password: 'password',
                message: '',
                error: ''
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.fetchData();
            this.fetchCat();
        },
        methods:{
            fetchData(page = 1) {
                // display departments..
                axios.get('/user?page=' + page)
                .then(({ data }) => this.users = data);
            },
            fetchCat(){
                // get categories..
                axios.get('/catlist')
                .then(({ data }) => this.categories = data);
            },
            onSuccess(response) {
                alert(response.data.message);
                this.fetchData();
            },
            clear(){
                this.message = '';
                this.category = '';
                this.days = '';
                this.error = '';
            },
            clear_error(){
                this.error = '';
                this.message = '';
                
            },
            editUser(user){
                $("#editModal").modal("show");
                this.fullname = user.firstname + ' ' + user.lastname;
                this.staffid = user.staff_id;
                this.department = user.department.department;
                this.email = user.email;
                this.permission = user.permission;
                this.category_id = user.category_id;
                this.user_id = user.id;
            },
            onUpdate(user_id) {            
                axios.put('/user/' + user_id, {
                  category_id : this.category_id,
                  permission : this.permission
                })
                .then(this.onSuccess)
                .catch(error => {
                    alert(error.response.data.message)
                });
                $("#editModal").modal("hide");
            },
            password(id) {
                this.user_id = id;
                $("#resetUser").modal("show");
            },
            onReset() {
                //alert(id);
                axios.put('/user/' + this.user_id, {
                  password : 'password'
                })
                .then(response => {alert(response.data.message)})
                .catch(error => {
                    alert(error.response.data.message)
                });
            },
            confirm(id) {
                //alert(id);
                this.user_id = id;
                $("#deleteUser").modal("show");
            },
            onDelete() {
                //   alert(this.post_id);
                axios.delete('/user/' + this.user_id)
                .then(response => {
                    alert(response.data.message);
                    this.fetchData();
                });
            } 
        }
    }
</script>
