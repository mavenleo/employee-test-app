<template>
    <div v-if="loading" class="loader">
        <div class="text-center">
            <h3>Loading data...</h3>
            <p><i class="fa fa-spinner fa-spin"></i></p>
        </div>
    </div>
    <table v-else  id="employee">
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
                            <div class="image ml-3"><img :src="item.image" alt=""></div>
                        </div>
                    </td>
                    <td>
                        {{ item.name }}
                        <span>{{ item.job_title }}</span>
                    </td>
                    <td>
                        ₦{{ crToString(item.current_salary) }}
                        <small v-if="item.last_salary_change > 0" class="badge badge-pill badge-success">+{{ item.last_salary_change }}</small>
                        <small v-if="item.last_salary_change < 0" class="badge badge-pill badge-danger">{{ item.last_salary_change }}</small>
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
                loading: true,
                noData: false
            }
        },
        created() {
            axios.get('/list-employees').then(res => {
                this.loading = false;
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
            timeSince(time) {

                switch (typeof time) {
                    case 'number':
                        break;
                    case 'string':
                        time = +new Date(time);
                        break;
                    default:
                        time = +new Date();
                }
                let time_formats = [
                    [60, 'seconds', 1],
                    [120, '1 minute'],
                    [3600, 'minutes', 60],
                    [7200, '1 hour', '1 hour from now'],
                    [86400, 'hours', 3600],
                    [172800, '1 day', 'Tomorrow'],
                    [604800, 'days', 86400],
                    [1209600, '1 week', 'Next week'], // 60*60*24*7*4*2
                    [2419200, 'weeks', 604800], // 60*60*24*7*4, 60*60*24*7
                    [4838400, '1 month', 'Next month'], // 60*60*24*7*4*2
                    [29030400, 'months', 2419200], // 60*60*24*7*4*12, 60*60*24*7*4
                    [58060800, 'Last year', 'Next year'], // 60*60*24*7*4*12*2
                    [2903040000, 'years', 29030400], // 60*60*24*7*4*12*100, 60*60*24*7*4*12
                    [5806080000, 'Last century', 'Next century'], // 60*60*24*7*4*12*100*2
                    [58060800000, 'centuries', 2903040000] // 60*60*24*7*4*12*100*20, 60*60*24*7*4*12*100
                ];
                let seconds = (+new Date() - time) / 1000,
                    list_choice = 1;

                if (seconds === 0) {
                    return 'Moments ago'
                }
                let i = 0,
                    format;
                while (format = time_formats[i++])
                    if (seconds < format[0]) {
                        if (typeof format[2] == 'string')
                            return format[list_choice];
                        else
                            return Math.floor(seconds / format[2]) + ' ' + format[1];
                    }
                return time;
            },
            crToString(n) {
                if (n < 1000000) {
                    n = n / 1000;
                    n = n.toFixed(2) + "K";
                } else {
                    n = n / 1000000;
                    n = n.toFixed(2) + "M";
                }
                return n;
            },
        }
    }
</script>
