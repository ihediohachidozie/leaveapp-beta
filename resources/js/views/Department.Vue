<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header font-weight-bold text-primary">
                        Department
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">
                            <i class="fas fa-plus" ></i>
                             Add
                        </button>

                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Department </th>
                                    <th colspan="2" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="dept in departments.data" :key="dept.id" class="text-center">
                                    <td v-text="dept.department" class="text-left"></td>
                                    <td><a @click="editDept(dept)" ><i class="fa fa-edit" style="font-size:24px; color:dodgerblue"></i></a></td>
                                    <td>
                                        <a @click="confirm(dept.id)">
                                            <i class="fa fa-trash" style="font-size:24px; color:red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-2">
                         
                            <pagination :data="departments" @pagination-change-page="fetchDept">
                                <span slot="prev-nav">&lt; Previous</span>
                                <span slot="next-nav">Next &gt;</span>
                            </pagination>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Department form Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">New Department</h4>
                        <button type="button" class="close" data-dismiss="modal" @click="clear">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" @submit.prevent="onSubmit"  autocomplete="off" @keydown="clear_error">
                            <div class="form-group">
                                <input type="text" v-model="department" class="form-control form-control-user" placeholder="Department" >
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
                                <small class="text-success"> {{message}} </small>
                                <small class="text-danger "> {{error}} </small>
                            </div>


                        </form>
                    </div>

                </div>
            </div>
        
        </div>

        <!-- Update Department form Modal -->
        <div class="modal" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Edit Department</h4>
                        <button type="button" class="close" data-dismiss="modal" @click="clear">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" @submit.prevent="onUpdate(department_id)"  autocomplete="off" @keydown="clear_error">
                            <div class="form-group">
                                <input type="text" v-model="department" class="form-control form-control-user" placeholder="Department" >
                            </div>
                            <div class="form-group text-center">
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Update</button>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal" @click="clear">Close</button>
                                    </div>
                                </div>
                                <small class="text-success"> {{message}} </small>
                                <small class="text-danger "> {{error}} </small>
                            </div>


                        </form>
                    </div>

                </div>
            </div>
        
        </div>

        <!-- The delete post Modal  -->
        <div class="modal" id="deleteDept">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Attention User</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>Do you want to delete this Department? </p>
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
                departments: {},
                department_id: '',
                department: '',
                message: '',
                error: ''
            }
        },
        mounted() {
            console.log('Component mounted.')
           this.fetchDept();
        },
        methods:{
            fetchDept(page = 1) {
                // display departments..
                axios.get('/department?page=' + page)
                .then(({ data }) => this.departments = data);
            },
            onSubmit(){
                if(this.department)
                {
                    // save the data...
                    axios.post('/department', {
                        department: this.department
                    })
                    .then(this.onSuccess)
                    .catch(error => {
                        this.error = error.response.data.message
                    });
                    
                }
                else{
                    this.error = "Department field is empty!!"
                }
            },
            onSuccess(response) {
                this.message = response.data.message;
                this.department = '';
                this.fetchDept();
            },
            clear(){
                this.message = ''
                this.department = ''
                this.error = ''
            },
            clear_error(){
                this.error = ''
                this.message = ''
                
            },
            editDept(dept){
                
                this.department = dept.department;
                this.department_id = dept.id;
                this.clear_error();
                $("#editModal").modal("show");
            },
            onUpdate(department_id) {
               // alert(this.department_id);
                axios.put('/department/' + department_id, {
                  department : this.department
                })
                .then(this.onSuccess)
                .catch(error => {
                    alert(error.response.data.message)
                });
                $("#editModal").modal("hide");
            },
            confirm(id) {
                //alert(id);
                this.department_id = id;
                $("#deleteDept").modal("show");
            },
            onDelete() {
                //   alert(this.post_id);
                axios.delete('/department/' + this.department_id)
                .then(response => {
                    alert(response.data.message);
                    this.fetchDept();
                });
            } 
        }
    }
</script>
