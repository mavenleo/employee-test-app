<template>
    <div class="content p-3">
        <div class="container-fluid mt-4">
            <form v-on:submit.prevent="createEmployee()">
                <div class="row">
                    <div class="col-md-12 mb-4 text-center">
                        <p v-if="showError" class="alert-warning alert p-4">{{ err }}</p>
                        <div class="image">
                            <img v-if="image" v-bind:src="image" ref="profileImage" />
                            <img v-else src="https://s3.us-east-2.amazonaws.com/glorioustube/images/7669105f27f162dc1ae6b5f061220db7.png" ref="profileImage" />
                            <input
                                @change="onFileChange"
                                type="file"
                                style="display:none"
                                ref="imageContent"
                            />
                            <!--			<p><a @click="ChangePicture" >Change Picture</a></p>-->
                            <p><a @click="$refs.imageContent.click()">Change Picture</a></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Employee Name</label>
                            <input v-model="name" type="text" placeholder="Employee Name" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Job Title</label>
                            <input v-model="jobTitle" type="text" placeholder="Job Title" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Job Status</label>
                            <input v-model="jobStatus" type="text" placeholder="Job Status" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Job Type</label>
                            <select v-model="jobType" class="form-control">
                                <option value="Select" disabled>Select Job Type</option>
                                <option value="Full time">Full time</option>
                                <option value="Part time">Part time</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Employee Salary</label>
                            <input type="text" v-model="salary" placeholder="Employee Salary" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <button :disabled="submittingNow" class="add_btn">
                                {{ submitText }}
                                <span v-if="submittingNow"
                                ><i class="fa fa-spinner fa-spin"></i
                                ></span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<style scoped>
    .content{
        background: #ffffff;
        border: none;
        border-radius: 15px;
        width: 100%;
    }
    .image{
        margin: 0 auto;
    }
    .image img{
        height: 50px;
        width: 50px;
        border-radius: 50%;
    }
    * a{
        cursor: pointer;
    }
</style>
<script>
    const axios = require('axios');
    export default {
        data:function () {
            return {
                employees: [],
                edit: '/edit-details/',
                noData: false,
                image: '',
                name: '',
                salary: '',
                jobType: 'Select',
                jobTitle: '',
                jobStatus: '',
                err: '',
                showError: false,
                submittingNow: false,
                submitText: 'Submit'
            }
        },
        created() {
            axios.get('/list-employees').then(res => {
                this.employees = res.data.data;
            });
        },
        methods: {
            onFileChange(e) {
                const file = e.target.files[0];
                let formData = new FormData();
                formData.append("file", file);
                formData.append("upload_preset", "yvqzsrl6");
                this.$toast.info("Uploading profile picture", "");
                axios({
                    url: `https://api.cloudinary.com/v1_1/dypj8zqbm/image/upload`,
                    method: "POST",
                    headers: {
                        "Content-Type": undefined,
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    data: formData
                })
                    .then(res => {
                        if (res.status === 200) {
                            this.$refs.profileImage.src = URL.createObjectURL(file);
                            this.image = res.data.secure_url;
                        } else {
                            console.info("oops, something went wrong", res);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                    });
            },
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace(',', '.')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            setError: function(err){
                this.$toast.warning(err, "Error");
            },
            createEmployee(){
                if(this.name == ""){
                    this.setError("Enter your employee name");
                    return;
                }
                if(this.jobTitle == ""){
                    this.setError("Enter employee job title to continue");
                    return;
                }
                if(this.salary == ""){
                    this.setError("Enter employee salary");
                    return;
                }
                if(this.image == ""){
                    this.setError("Upload employee image");
                    return;
                }
                this.submittingNow = true;
                this.submitText = "Submitting ";
                const dataObj = {
                    salary: this.salary,
                    image: this.image,
                    job_type: this.jobType,
                    job_title: this.jobTitle,
                    job_status: this.jobStatus,
                    name: this.name
                };
                console.log(dataObj)
                axios.post('/create-employee', dataObj).then(res => {
                    this.submittingNow = false;
                    this.submitText = "Submit";
                    this.$toast.success(res.data.message, "Created");
                    location.href = '/';
                }).catch(err => {
                    this.submittingNow = false;
                    this.submitText = "Submit";
                    this.$toast.warning(err.message, "Error");
                    this.setError(err.message);
                })
            }
        }
    }
</script>
