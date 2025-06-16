<template>
    <Navbar></Navbar>
    <div>
        <h1 style="text-align: center;font-size: 25px;">STUDENTS</h1>
        <div v-if="loading" class="text-center text-blue-600">Loading students...</div>
        <div v-else>
            <button @click="openModal(null)" class="bg-teal-500 text-white px-4 py-2 rounded mb-4">
                Add students
            </button>
            <table  class="table-auto w-full border-collapse border border-gray-200" >
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Image</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Phone</th>
                    <th class="border border-gray-300 px-4 py-2">Address</th>
                    <th class="border border-gray-300 px-4 py-2">Class</th>
                    <th class="border border-gray-300 px-4 py-2">Section</th>
                    <th class="border border-gray-300 px-4 py-2">Manager</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="std in students">
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ std.id }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ std.name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center"><img :src="'public/' + std.image" height="100" width="100" alt=""></td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ std.email }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ std.phone }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ std.address }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ std.classes?.name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ std.sections?.name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center"> <button @click="openModal(std)"
                                class="text-teal-500 hover:text-teal-700">Edit</button>
                            |
                            <button @click="deleteStudent(std.id)"
                                class="text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            </tbody>
            </table>
            <div v-if="isModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded shadow-lg w-1/3">
                    <h2 class="text-xl font-semibold mb-4">{{ isEditing ? 'Edit Student' : 'Add Student' }}</h2>

                    <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold">Name</label>
                            <input type="text" id="name" v-model="form.name"
                                class="w-full p-2 border border-gray-300 rounded" placeholder="Student Name" required />
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-sm font-semibold">Image</label>
                            <input type="file" id="image" name="image" @change="handleImageUpload"
                                class="w-full p-2 border border-gray-300 rounded" accept="image/*"/>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-semibold">Email</label>
                            <input type="text" id="email" v-model="form.email"
                                class="w-full p-2 border border-gray-300 rounded" placeholder="Student Email" required />
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-semibold">Phone</label>
                            <input type="text" id="phone" v-model="form.phone"
                                class="w-full p-2 border border-gray-300 rounded" placeholder="Student Email" required />
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-semibold">Address</label>
                            <input type="text" id="address" v-model="form.address"
                                class="w-full p-2 border border-gray-300 rounded" placeholder="Student Email" required />
                        </div>

                        <div class="mb-4">
                            <label for="classes" class="block text-sm font-semibold">Classes</label>
                            <select id="classes"  v-model="form.class_id" @change="fetchSections()" class="w-full p-2 border rounded" required>
                                <option v-for="cls in classes" :key="cls.id" :value="cls.id">
                                    {{ cls.name }}
                                </option>
                            </select>
                        </div>

                        <div v-if="selected" class="mb-4">Loading sections...</div>

                        <div v-if="form.class_id && !this.selected" class="mb-4">
                            <label for="sections" class="block text-sm font-semibold">Sections</label>
                            <select id="sections" v-model="form.section_id" class="w-full p-2 border rounded" required>
                                <option v-for="sec in sections" :key="sec.id" :value="sec.id">
                                    {{ sec.name }}
                                </option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" @click="closeModal" class="mr-4 text-gray-500">Cancel</button>
                            <button type="submit" class="bg-teal-500 text-dark px-4 py-2 rounded">
                                {{ isEditing ? 'Save Student' : 'Add Student' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    // import DataTable from 'datatables.net-vue3'
    // import Select from 'datatables.net-select';
    // DataTable.use(Select);

    import axios from 'axios';
    import Navbar from '../Includes/Navbar.vue';
    export default {
        components:{
            Navbar,axios,
        },

        data() {
            return {

                students:[],
                classes:[],
                selected: false,
                loading: true,
                error: null,
                sections:[],
                isModalOpen: false, // Modal visibility state
                isEditing: false, // Whether we're editing an existing category
                form: {
                    id: null,
                    name: '',
                    image:'',
                    email: '',
                    phone: '',
                    address:'',
                    section_id: '',
                    class_id:''
                },
            }
        },

        mounted() {

        this.fetchStudents();
        this.fetchClasses();
        this.fetchSections();

        },

         methods:{
            handleImageUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    this.form.image = file;
                    console.log("Selected file:", file);
                }
            },

            async fetchStudents() {
                try {
                // Make the GET request to the public/api endpoint
                const response = await axios.get("http://localhost/apistd_manager/public/api/students");
                this.students = response.data; // Assign data to categories
                console.log(this.students);

                } catch (error) {
                console.error("Error fetching students:", error);
                this.error = "Failed to load students. Please try again later.";
                }finally {
                this.loading = false; // Stop loading spinner
                }
            },

            async fetchSections() {
                if (!this.form.class_id) {
                    console.log('khong lay duoc');
                    return;
                }
                console.log(this.form.class_id);
                this.selected= true;
                try {
                    const res = await axios.get(`http://localhost/apistd_manager/public/api/sections/${this.form.class_id}`);
                    console.log(res.data);
                    this.sections = res.data;
                } catch (err) {
                    console.error('Lỗi khi tải sections:', err);
                } finally {
                    this.selected = false;
                }
            },


            async fetchClasses() {
                try {
                // Make the GET request to the public/api endpoint
                const response = await axios.get("http://localhost/apistd_manager/public/api/classes");
                this.classes = response.data; // Assign data to categories
                console.log(this.classes);
                } catch (error) {
                console.error("Error fetching classes:", error);
                this.error = "Failed to load classes. Please try again later.";
                }
            },

            async handleSubmit() {
                if (this.isEditing) { //Edit
                    await this.updateStudent();
                } else { //Add
                    await this.addStudent();
                }
                this.closeModal();
            },

             async addStudent() {

                try {
                    const formData = new FormData();
                    for (const key in this.form) {
                        formData.append(key, this.form[key]);
                    }
                    console.log("Sending data:", JSON.parse(JSON.stringify(this.form)));



                    const create = await axios.post("http://localhost/apistd_manager/public/api/students", formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });

                    this.fetchStudents();

                } catch (error) {
                    console.error("Error adding customer:", error);
                    console.log("Validation errors:", error.response?.data);
                    this.error = "Failed to add customer. Please try again later.";
                }
            },

            async updateStudent() {
                 try {
                    const formData = new FormData();
                    for (const key in this.form) {
                        if (key === 'image') {
                            if (this.form.image instanceof File) {
                                formData.append('image', this.form.image);
                            }
                        } else {
                            formData.append(key, this.form[key]);
                        }
                    }

                    formData.append('_method', 'PUT');
                    for (const pair of formData.entries()) {
                        console.log(pair[0], pair[1]);
                    }

                    const update = await axios.post(`http://localhost/apistd_manager/public/api/students/${this.form.id}`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data','Accept': 'application/json'}
                    });

                    this.fetchStudents();

                } catch (error) {
                    console.error("Error updating customer:", error);
                    this.error = "Failed to update customer. Please try again later.";
                }
            },

            openModal(std) {
                if (std) {
                    this.isEditing = true;
                    this.form = { ...std};
                } else {
                    this.isEditing = false;
                    this.form = { id: null, name: '', image: '', email: '', phone: '',address: '', class_id: '', section_id: ''}; // Reset form for adding
                }
                this.isModalOpen = true;
            },

            closeModal() {
                this.isModalOpen = false;
            },


            async deleteStudent(id) {
                console.log(id);
                if (!confirm('Are you sure you want to delete this student?')) {
                    return;
                }
                try {
                    await axios.delete(`http://localhost/apistd_manager/public/api/students/${id}`);
                    this.fetchStudents();
                } catch (error) {
                    console.error('Error deleting section:', error);
                }
            },

            }
    }
</script>

<style lang="scss" scoped>

</style>
