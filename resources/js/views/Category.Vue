<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header font-weight-bold text-primary">
                        Staff Category
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">
                            <i class="fas fa-plus" ></i>
                             Add
                        </button>

                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Category </th>
                                    <th class="text-center">Days Approved</th>
                                    <th colspan="2" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cat in categories.data" :key="cat.id" class="text-center">
                                    <td v-text="cat.category" class="text-left"></td>
                                    <td v-text="cat.days"></td>
                                    <td><a @click="editCat(cat)" ><i class="fa fa-edit" style="font-size:24px; color:dodgerblue"></i></a></td>
                                    <td>
                                        <a @click="confirm(cat.id)">
                                            <i class="fa fa-trash" style="font-size:24px; color:red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-2">
                         
                            <pagination :data="categories" @pagination-change-page="fetchCat">
                                <span slot="prev-nav">&lt; Previous</span>
                                <span slot="next-nav">Next &gt;</span>
                            </pagination>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Category form Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">New Staff Category</h4>
                        <button type="button" class="close text-red" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" @submit.prevent="onSubmit"  autocomplete="off" @keydown="clear_error">
                            <div class="form-group">
                                <input type="text" v-model="category" class="form-control form-control-user" placeholder="Category" >
                            </div>
                            <div class="form-group">   
                                <input type="number" v-model="days" max="30" min="1" class="form-control form-control-user" placeholder="Approved Days" value="">
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

        <!-- Edit Category form Modal -->
        <div class="modal" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title text-white">Edit Staff Category</h4>
                        <button type="button" class="close text-red" data-dismiss="modal" @click="clear">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="" @submit.prevent="onUpdate(category_id)"  autocomplete="off" @keydown="clear_error">
                            <div class="form-group">
                                <input type="text" v-model="category" class="form-control form-control-user" placeholder="Category" >
                            </div>
                            <div class="form-group">   
                                <input type="number" v-model="days" max="30" min="1" class="form-control form-control-user" placeholder="Approved Days" value="">
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
        <div class="modal" id="deleteCat">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Attention User</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p>Do you want to delete this Category? </p>
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
                categories: {},
                category_id: '',
                category: '',
                days: '',
                message: '',
                error: ''
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.fetchCat();
        },
        methods:{
            fetchCat(page = 1) {
                // display departments..
                axios.get('/category?page=' + page)
                .then(({ data }) => this.categories = data);
            },
            onSubmit(){
                if(this.category && this.days)
                {
                    // save the data...
                    axios.post('/category', {
                        category: this.category,
                        days: this.days
                    })
                    .then(this.onSuccess)
                    .catch(error => {
                        this.error = error.response.data.message
                    });
                    
                }
                else{
                    this.error = "Category & Approved Days fields cannot be empty!!"
                }
            },
            onSuccess(response) {
                this.message = response.data.message;
                this.category = '';
                this.days = '';
                this.fetchCat();
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
            editCat(cat){
                $("#editModal").modal("show");
                this.category = cat.category;
                this.category_id = cat.id;
                this.days = cat.days;
                this.clear_error();
            },
            onUpdate(category_id) {
               // alert(this.category_id);
                axios.put('/category/' + category_id, {
                  category : this.category,
                  days : this.days
                })
                .then(this.onSuccess)
                .catch(error => {
                    alert(error.response.data.message)
                });
                $("#editModal").modal("hide");
            },
            confirm(id) {
                //alert(id);
                this.category_id = id;
                $("#deleteCat").modal("show");
            },
            onDelete() {
                //   alert(this.post_id);
                axios.delete('/category/' + this.category_id)
                .then(response => {
                    alert(response.data.message);
                    this.fetchCat();
                });
            } 
        }
    }
</script>
