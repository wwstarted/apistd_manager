<template>
    <Navbar></Navbar>
    <div>
        <h1 style="text-align: center;font-size: 25px;">CLASSES</h1>
        <div v-if="loading" class="text-center text-blue-600">Loading classes...</div>
        <div v-else>
            <button @click="openModal(null)" class="bg-teal-500 text-white px-4 py-2 rounded mb-4">
                Add Class
            </button>
            <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Manager</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="cls in classes" :key="cls.id">
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ cls.id }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ cls.name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center"> <button @click="openModal(cls)"
                                class="text-teal-500 hover:text-teal-700">Edit</button>
                            |
                            <button @click="deleteClass(cls.id)"
                                class="text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            </tbody>
            </table>
            <div v-if="isModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded shadow-lg w-1/3">
                    <h2 class="text-xl font-semibold mb-4">{{ isEditing ? 'Edit Class' : 'Add Class' }}</h2>

                    <form @submit.prevent="handleSubmit">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold">Name</label>
                            <input type="text" id="name" v-model="form.name"
                                class="w-full p-2 border border-gray-300 rounded" placeholder="Class Name" required />
                        </div>
                         <div class="flex justify-end">
                            <button type="button" @click="closeModal" class="mr-4 text-gray-500">Cancel</button>
                            <button type="submit" class="bg-teal-500 text-dark px-4 py-2 rounded">
                                {{ isEditing ? 'Save Changes' : 'Add Class' }}
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
            Navbar,axios
        },

        data() {
            return {
                classes: [],
                loading: true,
                error: null,
                isModalOpen: false, // Modal visibility state
                isEditing: false, // Whether we're editing an existing category
                form: {
                    id: null,
                    name: ''
                },
            }
        },

        mounted() {
            this.fetchClasses();
        },

        methods:{
            async fetchClasses() {
                try {
                // Make the GET request to the public/api endpoint
                const response = await axios.get("http://localhost/apistd_manager/public/api/classes");
                this.classes = response.data; // Assign data to categories
                console.log(this.classes);
                } catch (error) {
                console.error("Error fetching categories:", error);
                this.error = "Failed to load categories. Please try again later.";
                } finally {
                this.loading = false; // Stop loading spinner
                }
            },

            async handleSubmit() {
                if (this.isEditing) { //Edit
                    await this.updateClass();
                } else { //Add
                    await this.addClass();
                }
                this.closeModal();
            },

            async addClass() {
                try {
                    const response = await axios.post("http://localhost/apistd_manager/public/api/classes",this.form);
                    this.fetchClasses(); // Đảm bảo danh sách được cập nhật trước khi cập nhật DataTable

                } catch (error) {
                    console.error("Error adding class:", error);
                }
            },

            async updateClass() {
                try {
                    const response = await axios.put(`http://localhost/apistd_manager/public/api/classes/${this.form.id}`, this.form);
                    this.fetchClasses();
                } catch (error) {
                    console.error("Error updating class:", error);
                }
            },

            openModal(cls) {
                if (cls) {
                    this.isEditing = true;
                    this.form = { ...cls}; // Pre-fill form for editing
                } else {
                    this.isEditing = false;
                    this.form = { id: null, name: ''}; // Reset form for adding
                }
                this.isModalOpen = true;
            },

            closeModal() {
                this.isModalOpen = false;
            },

            async deleteClass(id) {
                try {
                    if (confirm('Are you sure you want to delete this brand?')) {
                        // Make the DELETE request to the public/api
                        await axios.delete(`http://localhost/apistd_manager/public/api/classes/${id}`);
                        this.fetchClasses();
                        alert('Class deleted successfully.');
                    }
                } catch (error) {
                    console.error('Error deleting class:', error);
                    alert('Failed to delete class.');
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
