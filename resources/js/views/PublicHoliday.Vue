<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header font-weight-bold text-primary">
                        Public Holidays
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">
                            <i class="fas fa-plus" ></i>
                             Add
                        </button>

                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Description </th>
                                    <th class="text-center">Date</th>
                                    <th colspan="2" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ph in publicholidays.data" :key="ph.id" class="text-center">
                                    <td v-text="ph.description" class="text-left"></td>
                                    <td v-text="ph.date"></td>
                                    <td><a @click="editPH(ph)" ><i class="fa fa-edit" style="font-size:24px; color:dodgerblue"></i></a></td>
                                    <td>
                                        <a @click="confirm(ph.id)">
                                            <i class="fa fa-trash" style="font-size:24px; color:red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-2">
                         
                            <pagination :data="publicholidays" @pagination-change-page="fetchData">
                                <span slot="prev-nav">&lt; Previous</span>
                                <span slot="next-nav">Next &gt;</span>
                            </pagination>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Public Holiday form Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">New Public Holiday</h4>
                        <button type="button" class="close text-red" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" @submit.prevent="onSubmit"  autocomplete="off" @keydown="clear_error">
                            <div class="form-group">
                                <input type="text" v-model="description" class="form-control form-control-user" placeholder="Description" >
                            </div>
                            <div class="form-group">   
                                <input type="date" v-model="dates" class="form-control form-control-user">
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

        <!-- Edit Public Holiday form Modal -->
        <div class="modal" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Edit Public Holiday</h4>
                        <button type="button" class="close text-red" data-dismiss="modal" @click="clear">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" @submit.prevent="onUpdate(publicholiday_id)"  autocomplete="off" @keydown="clear_error">
                            <div class="form-group">
                                <input type="text" v-model="description" class="form-control form-control-user" placeholder="Description" >
                            </div>
                            <div class="form-group">   
                                <input type="date" v-model="dates" class="form-control form-control-user">
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
        <div class="modal" id="deletePH">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Attention User</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>Do you want to delete this Public Holiday record? </p>
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
                publicholidays: {},
                publicholiday_id: '',
                description: '',
                dates: '',
                message: '',
                error: ''
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.fetchData();
        },
        methods:{
            fetchData(page = 1) {
                // display departments..
                axios.get('/public_holiday?page=' + page)
                .then(({ data }) => this.publicholidays = data);
            },
            onSubmit(){
                this.clear_error();
                if(this.description && this.dates)
                {
                    // save the data...
                    axios.post('/public_holiday', {
                        description: this.description,
                        date: this.dates
                    })
                    .then(this.onSuccess)
                    .catch(error => {
                        this.error = error.response.data.message
                    });
                    
                }
                else{
                    this.error = "Description & Date fields cannot be empty!!"
                }
            },
            onSuccess(response) {
                this.message = response.data.message;
                this.description = '';
                this.dates = '';
                this.fetchData();
            },
            clear(){
                this.message = '';
                this.description = '';
                this.dates = '';
                this.error = '';
            },
            clear_error(){
                this.error = '';
                this.message = '';
                
            },
            editPH(ph){
                $("#editModal").modal("show");
                this.description = ph.description;
                this.publicholiday_id = ph.id;
                this.dates = ph.date;
                this.clear_error();
            },
            onUpdate(publicholiday_id) {
               //alert(this.publicholiday_id);
                 axios.put('/public_holiday/'+ publicholiday_id, {
                  description : this.description,
                  date : this.dates
                })
                .then(this.onSuccess)
                .catch(error => {
                    alert(error.response.data.message)
                }); 
                $("#editModal").modal("hide");
            },
            confirm(id) {
                //alert(id);
                this.publicholiday_id = id;
                $("#deletePH").modal("show");
            },
            onDelete() {
                //   alert(this.post_id);
                axios.delete('/public_holiday/' + this.publicholiday_id)
                .then(response => {
                    alert(response.data.message);
                    this.fetchData();
                });
            } 
        }
    }
</script>
