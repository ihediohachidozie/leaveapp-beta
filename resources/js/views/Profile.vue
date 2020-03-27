<template>
    <div class="container">
        


        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-2">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                    </div>
                    
                    <div class="card-body">
                        <form action="" @submit.prevent="onChangeInfo()" >

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="text" class="form-control form-control-user"  v-model="firstname">
                                
                                </div>
                                <div class="col-sm-6">
                                <input type="text" class="form-control form-control-user" v-model="lastname">
                                
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" v-model="email">
                                
                            </div>
                            <div class="form-group"> 
                                <select v-model="department_id" class="form-control">
                                    <option v-for="item in departments" :value="item.id">{{item.department}}</option>
                                </select>   
                            </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                Change Info
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card shadow mb-2">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Change Password</h6>
                    </div>

                    <div class="card-body">
                        <form action=""  @submit.prevent="onChangePW()">

                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" v-model="current_password" placeholder="Current Password">
                                
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user" v-model="password" placeholder="New Password">
                                
                                </div>
                                <div class="col-sm-6">
                                <input type="password" class="form-control form-control-user" v-model="password_confirmation" placeholder="Repeat New Password">
                                
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Change Password
                            </button>
                        </form>
                    </div>


                </div>
            </div>
            <div class="col-lg-6">

                <div class="card shadow mb-2 ">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{firstname +' ' + lastname}}</h6>
                </div>
                <div class="card-body text-center">
            
                    <div class="mx-auto" v-if="imageURL">
                        <img :src="imageURL" class="card-img-top rounded-circle mb-2" alt="Profile image"  style="width:50%">
                    </div>
                    <div v-else class="mx-auto">
                        <img src="/theme/img/default.jpg" class="card-img-top rounded-circle mb-2" alt="Profile image"  style="width:50%">
                    </div>

                    <h5 class="card-title text-gray mb-3"></h5>
                    <div class="row card-text">
                    <div class="col text-md font-weight-bold">Department <br> <span class="text-sm text-danger">{{dept}}</span> </div>
                    <div class="col text-md font-weight-bold">Category <br> <span class="text-sm text-danger">{{category}}</span> </div>
                    <div class="col text-md font-weight-bold">Staff Id <br> <span class="text-sm text-danger">{{staffid}}</span> </div>
                    </div>
                        
                </div>
                </div>

                <div class="my-2"></div>
                <div class="card shadow mb-2">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Change Image</h6>
                </div>

                <div class="card-body">
                        


                            
                            <div class="my-2"><input style="display: none" type="file" name="image" @change="onImageChange" ref="fileInput"></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col"><button class="btn btn-success btn-block" @click="$refs.fileInput.click()">Select Image</button></div>
                                    <div class="col"><button class="btn btn-primary btn-block" @click="onUpload" :disabled="check" >Upload</button></div>
                                </div>
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
                data: [],
                image: '',
                imageURL:'',
                firstname: '',
                lastname: '',
                email: '',
                dept: '',
                departments: [],
                category: '',
                staffid: '',
                department_id: '',
                id: '',
                password: '',
                password_confirmation: '',
                current_password: '',
                message: '',
                check: true
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getUser();
            this.getDept();
        },
        methods:{
            getUser(){
                axios.get('/profile')
                .then(({ data }) => {
                    this.firstname = data[0].firstname;
                    this.lastname = data[0].lastname;
                    this.email = data[0].email;
                    this.department_id = data[0].department_id;
                    this.dept = data[0].department.department;
                    this.staffid = data[0].staff_id;
                    this.id = data[0].id;
                    this.imageURL = data[0].image;
                    this.category = data[0].category.category;

                });
            },
            getDept(){
                axios.get('/dept')
                .then(({data}) => this.departments = data);
            },
            onChangeInfo() {
                axios.put('/profile/'+this.id, {
                    firstname: this.firstname,
                    lastname: this.lastname,
                    department_id : this.department_id,
                    email: this.email
                })
                .then(response => {
                    alert(response.data.message);                 
                })
                .catch(error => {
                    alert(error.response.data.message)
                });
                this.getUser();  
            },
            onChangePW(){
                //alert(this.id);
                axios.put('/profile/'+this.id, {
                    current_password: this.current_password,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                })
                .then(response => {
                    this.password = this.password_confirmation = this.current_password = '';
                    alert(response.data.message);
                })
                .catch(error => {
                    alert(error.response.data.message)
                });
                this.getUser();  
            },
            onImageChange(e) {
               
                const file = e.target.files[0];
                this.image = file;
                this.imageURL = URL.createObjectURL(file);
                this.check = false;
            },
            onUpload(){
                let currentObj = this;
                const formData = new FormData();   
                formData.append('image', this.image, this.image.name);
                
                const config = {
                    headers: { 'Content-Type': 'multipart/form-data' }
                }

                axios.post('/profile', formData, config)
                .then(function (response) {
                    currentObj.success = response.data.message;
                    alert(currentObj.success);
                })
                .catch(function (error) {
                    currentObj.output = error;
                    alert(currentObj.output);
                }); 
                this.check = true;               
            }
        }
    }
</script>
