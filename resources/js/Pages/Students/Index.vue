<template>
    <Navbar></Navbar>
    <h1 style="text-align: center;font-size: 25px;">Students</h1>
        <div v-if="loading" class="text-center text-blue-600">Loading Students...</div>
        <div v-else>
            <button @click="openModal(null)" class="bg-teal-500 text-white px-4 py-2 rounded mb-4">
                Add Student
            </button>
            <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Image</th>
                    <th class="border border-gray-300 px-4 py-2">Phone</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Address</th>
                    <th class="border border-gray-300 px-4 py-2">Manage</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="students in customers" >
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ customer.id }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ customer.name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center"><img :src="'public/' + customer.image" height="100" width="100" alt=""></td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ customer.phone }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ customer.email }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ customer.address }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center"> <button @click="openModal(customer)"
                                class="text-teal-500 hover:text-teal-700">Edit</button>
                            |
                            <button @click="deleteCustomer(customer.id)"
                                class="text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
</template>

<script>
    import Navbar from '../Includes/Navbar.vue';
    import axios from 'axios';
    export default {
        components:{
            Navbar,axios
        },

        data(){
            return {
                students: [],
                loading: true,
                error: null,
                isModalOpen:false,
                isEditing:false,
                form:{
                    id:null,
                    name:'',
                    phone:'',
                    email:'',
                    address:'',
                    image:''
                },
            }
        },

        mounted(){
            this.fetchStudents();
        },


        methods:{
             async fetchStudents(){
                try{
                    const show  = await axios.get("http://localhost/students/api/students");
                    this.students = show.data;
                    console.log(this.students);
                }catch(error){
                    console.error("Error fetching categories:", error);
                    this.error = "Failed to load categories. Please try again later.";
                }finally {
                    this.loading = false; // Stop loading spinner
                }
            },
        },



    }
</script>

<style lang="scss" scoped>

</style>
