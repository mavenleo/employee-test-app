<template>
    <table id="employee">
                <thead>
                <tr class="p-5">
                    <td>
                        <label class="chkbox">
                            <input type="checkbox"/>
                            <svg class="check" width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"  clip-rule="evenodd" d="M5.9928 7.1748L2.56067 3.74316C1.97473 3.15723 1.02502 3.15723 0.439087 3.74316C-0.146362 4.3291 -0.146362 5.27832 0.439087 5.86426L4.68176 10.1074C5.0387 10.4639 5.52893 10.6035 5.99133 10.5254C6.45374 10.6035 6.94641 10.4648 7.30334 10.1074L14.8502 2.56055C15.4362 1.97461 15.4362 1.02441 14.8502 0.439453C14.2648 -0.146484 13.3151 -0.146484 12.7291 0.439453L5.9928 7.1748Z" fill="white" />
                            </svg>
                        </label>
                    </td>
                    <td>
                        EMPLOYEE
                    </td>
                    <td>
                        SALARY
                    </td>
                    <td>
                        STATUS
                    </td>
                    <td>
                        MANAGE
                    </td>
                </tr>
                </thead>
                <tbody class="scrollable">
                <tr v-for="(item, i) in employees">
                    <td>
                        <div class="flex_items">
                            <label class="chkbox">
                                <input type="checkbox"/>
                                <svg class="check" width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"  clip-rule="evenodd" d="M5.9928 7.1748L2.56067 3.74316C1.97473 3.15723 1.02502 3.15723 0.439087 3.74316C-0.146362 4.3291 -0.146362 5.27832 0.439087 5.86426L4.68176 10.1074C5.0387 10.4639 5.52893 10.6035 5.99133 10.5254C6.45374 10.6035 6.94641 10.4648 7.30334 10.1074L14.8502 2.56055C15.4362 1.97461 15.4362 1.02441 14.8502 0.439453C14.2648 -0.146484 13.3151 -0.146484 12.7291 0.439453L5.9928 7.1748Z" fill="white" />
                                </svg>
                            </label>
                            <div class="image"><img :src="item.image" alt=""></div>
                        </div>
                    </td>
                    <td>
                        {{ item.name }}
                        <span>{{ item.job_title }}</span>
                    </td>
                    <td>
                        ₦{{ formatPrice(item.current_salary) }}
                        <small v-if="item.last_salary_change > 0" class="badge badge-pill badge-success">+{{ item.last_salary_change }}</small>
                        <small v-if="item.last_salary_change < 0" class="badge badge-pill badge-danger">-{{ item.last_salary_change }}</small>
                        <span>{{ item.job_type }}</span>
                    </td>
                    <td>
                        {{ item.job_status }}
                        <span>{{ timeSince(item.created_at) }}</span>
                    </td>
                    <td>
                        <a :href="edit+item.id">
                            <i class="fa pr-4 border-right fa-pencil"></i>
                        </a>
                        <i @click="deleteItem(item.id, i)" class="fa fa-trash-o ml-4"></i>
                    </td>
                </tr>
                <tr v-if="noData">
                    <td colspan="5">
                        <div class="text-danger text-center">Nothing to show at the moment</div>
                    </td>
                </tr>
                </tbody>
            </table>
</template>

<script>
    const axios = require('axios');
    export default {
        data:function () {
            return {
                employees: [],
                edit: '/edit-details/',
                loading: false,
                noData: false
            }
        },
        created() {
            axios.get('/list-employees').then(res => {
                this.employees = res.data.data;
            });
        },
        methods: {
            deleteItem(id, index) {
                if (confirm("Do you really want to delete?")) {
                    axios.get('/delete-employee/'+id).then(res => {
                        this.employees.splice(index, 1);
                        this.$toast.success("Employee data successfully deleted", "");
                        if (this.employees.length === 0){
                            this.noData = true;
                        }
                    });
                }
            },
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace(',', '.')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            timeSince(date) {
                let seconds = Math.floor((new Date() - date) / 1000);
                let interval = Math.floor(seconds / 31536000);
                if (interval > 1) {
                    return interval + " years";
                }
                interval = Math.floor(seconds / 2592000);
                if (interval > 1) {
                    return interval + " months";
                }
                interval = Math.floor(seconds / 86400);
                if (interval > 1) {
                    return interval + " days";
                }
                interval = Math.floor(seconds / 3600);
                if (interval > 1) {
                    return interval + " hours";
                }
                interval = Math.floor(seconds / 60);
                if (interval > 1) {
                    return interval + " minutes";
                }
                return Math.floor(seconds) + " seconds";
            }
        }
    }
</script>
