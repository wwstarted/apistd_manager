<template>
    <Navbar></Navbar>
    <div>
        <h1 style="text-align: center;font-size: 25px;">SECTIONS</h1>
        <div v-if="loading" class="text-center text-blue-600">Loading sections...</div>
        <div v-else>
            <button @click="openModal(null)" class="bg-teal-500 text-white px-4 py-2 rounded mb-4">
                Add section
            </button>
            <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Class</th>
                    <th class="border border-gray-300 px-4 py-2">Manager</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="section in sections">
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ section.id }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ section.name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">{{ section.classes?.name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center"> <button @click="openModal(section)"
                                class="text-teal-500 hover:text-teal-700">Edit</button>
                            |
                            <button @click="deleteSection(section.id)"
                                class="text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            </tbody>
            </table>
            <div v-if="isModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded shadow-lg w-1/3">
                    <h2 class="text-xl font-semibold mb-4">{{ isEditing ? 'Edit Section' : 'Add Section' }}</h2>

                    <form @submit.prevent="handleSubmit">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold">Name</label>
                            <input type="text" id="name" v-model="form.name"
                                class="w-full p-2 border border-gray-300 rounded" placeholder="Section Name" required />
                        </div>
                        <!-- <div class="mb-4">
                            <label for="classes" class="block text-sm font-semibold">Classes</label>
                            <input type="text" id="classes" v-model="form.class_id"
                                class="w-full p-2 border border-gray-300 rounded" placeholder="Class Name" required />
                        </div> -->
                         <div class="mb-4">
                            <label for="classes" class="block text-sm font-semibold">Classes</label>
                            <select id="classes" v-model="form.class_id" class="w-full p-2 border rounded" required>
                                <option v-for="cls in classes" :key="cls.id" :value="cls.id">
                                    {{ cls.name }}
                                </option>
                            </select>
                        </div>
                         <div class="flex justify-end">
                            <button type="button" @click="closeModal" class="mr-4 text-gray-500">Cancel</button>
                            <button type="submit" class="bg-teal-500 text-dark px-4 py-2 rounded">
                                {{ isEditing ? 'Save Section' : 'Add Section' }}
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
                sections: [],
                classes:[],
                loading: true,
                error: null,
                isModalOpen: false, // Modal visibility state
                isEditing: false, // Whether we're editing an existing category
                form: {
                    id: null,
                    name: '',
                    class_id:''
                },
            }
        },

        mounted() {
        this.fetchSections(); // Fetch categories when component is mounted
        this.fetchClasses();
        },

         methods:{
            async fetchSections() {
                try {
                // Make the GET request to the API endpoint
                const response = await axios.get("http://localhost/apistd_manager/public/api/sections");
                this.sections = response.data; // Assign data to categories
                console.log(this.sections);

                } catch (error) {
                console.error("Error fetching categories:", error);
                this.error = "Failed to load categories. Please try again later.";
                } finally {
                this.loading = false; // Stop loading spinner
                }
            },
            async fetchClasses() {
                try {
                // Make the GET request to the API endpoint
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
                    await this.updateSection();
                } else { //Add
                    await this.addSection();
                }
                this.closeModal();
            },

             async addSection() {
                try {
                    console.log(this.form);
                    const response = await axios.post("http://localhost/apistd_manager/public/api/sections",this.form);
                    this.fetchSections();
                } catch (error) {
                    console.error("Error adding class:", error);
                }
            },

             async updateSection() {
                try {
                    console.log(this.form);
                    const response = await axios.put(`http://localhost/apistd_manager/public/api/sections/${this.form.id}`, this.form);
                    this.fetchSections();
                } catch (error) {
                    console.error("Error updating class:", error);
                }
            },

             openModal(section) {
                if (section) {
                    this.isEditing = true;
                    this.form = { ...section}; // Pre-fill form for editing
                } else {
                    this.isEditing = false;
                    this.form = { id: null, name: '',class_id: ''}; // Reset form for adding
                }
                this.isModalOpen = true;
            },

            closeModal() {
                this.isModalOpen = false;
            },

            async deleteSection(id) {
                console.log(id);
                if (!confirm('Are you sure you want to delete this section?')) {
                    return;
                }
                try {
                    await axios.delete(`http://localhost/apistd_manager/public/api/sections/${id}`);
                    this.fetchSections();
                } catch (error) {
                    console.error('Error deleting section:', error);
                }
            },

            }
    }
</script>

<style lang="scss" scoped>

</style>
