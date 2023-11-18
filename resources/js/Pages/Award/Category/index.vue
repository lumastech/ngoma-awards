<template>
    <Head title="categories" />
    <div class="max-w-7xl mx-auto px-2">
        <div class="flex justify-between gap-4 mb-4 place-items-center">
            <h2>CATEGORIES</h2>
            <button @click="artistCreate" class="rounded bg-primary-500 text-white hover:bg-primary-600 transition border border-primary-500 px-4 py-2">
                <i class="fa-solid fa-plus"></i> <span>Add A Category</span>
            </button>
        </div>

        <div class="shadow rounded bg-white p-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border-b border-gray-200 px-2 py-1"></th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Name</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Artist</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Award</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr v-for="category in categories.data" :key="category.id" class="hover:bg-gray-50 transition">
                        <td class="border-b border-gray-200 px-2 py-1">
                            <i class="fa-solid fa-user"></i>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ category.name }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ category.artist }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ category.award.name }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-right">
                            <button @click="editArtist(category.name, category.award.id, category.id)" class="p-2 text-sky-500">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button @click="showDeleteItem(true, category)" class="p-2 text-red-500">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- add category -->
    <DialogModal :show="showArtistModal" :closeable="true">
        <template v-slot:title>
            <h4>Add an category</h4>
        </template>
        <template v-slot:content>
            <form @submit.prevent="submit" action="#" method="post" class="grid md:grid-cols-2 gap-4 bg-white rounded-md p-4">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" v-model="category.name" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="James Phiri">
                    <span v-if="errors.name" class="text-red-500">{{ errors.name }}</span>
                </div>

                <div class="form-group">
                    <label for="award_id">Award Category</label>
                    <select name="award_id" v-model="category.award_id" id="award_id" class="block border border-primary-400 rounded placeholder-gray-400 w-full">
                        <option v-for="award in awards" :key="award.id" :value="award.id">{{ award.name }}</option>
                    </select>
                    <span v-if="errors.award_id" class="text-red-500">{{ errors.award_id }}</span>
                </div>
            </form>
        </template>
        <template v-slot:footer>
            <button @click="showArtistModal = false" class="text-gray-500 hover:bg-primary-100 px-4 rounded transition">Cancel</button>
            <button v-if="!editting" @click="submit" class="border border-primary-400 rounded placeholder-gray-400 bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Submit</button>
            <button v-if="editting" @click="updateArtist" class="border border-primary-400 rounded placeholder-gray-400 bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Update</button>
        </template>
    </DialogModal>


    <!-- confirm dialog -->
    <ConfirmationModal :show="deleteDialog" :closeable="true">
        <template v-slot:title>
            <h4>Delete Category</h4>
        </template>
        <template v-slot:content>
            <p>Are you sure you want to delete this category?</p>
        </template>
        <template v-slot:footer>
            <button @click="deleteDialog = false" class="text-gray-500 hover:bg-primary-100 px-4 rounded transition">Cancel</button>
            <button @click="deleteItem" class="border border-primary-400 rounded placeholder-gray-400 bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Delete</button>
        </template>
    </ConfirmationModal>
    <Loader :loader="category.processing" />
</template>

<script>
import DashboardLaout from '@/Layouts/DashboardLayout.vue';
import { ref } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DialogModal from '@/Components/DialogModal.vue';

export default {
    components: {
    DashboardLaout, Link, Head,
        ConfirmationModal,
        DialogModal,
},
    props: {
        categories: Object,
        errors: Object,
        award_categories: Object,
        awards: Object,
    },
    layout: DashboardLaout,
    setup() {
        const category = useForm({
            name: '',
            award_id: '',
        });

        const showArtistModal = ref(false);
        const editting = ref(false);
        const deleteDialog = ref(false);

        const editArtist = (name, award_id, id) => {
            category.name = name;
            category.award_id = award_id;
            category.id = id;
            console.log(category.id);
            showArtistModal.value = true;
            editting.value = true;
        }

        const artistCreate = () => {
            category.name = '';
            category.award_id = '';
            category.id = '';
            showArtistModal.value = true;
            editting.value = false;
        }

        const submit = () => {
            category.post(route('categories.store'), {
                onSuccess: () => {
                    category.reset();
                    editting.value = false;
                    showArtistModal.value = false;
                },
            });
        };
        const updateArtist = () => {
            category.put(route('categories.update', category), {
                onSuccess: () => {
                    category.reset();
                    editting.value = false;
                    showArtistModal.value = false;
                },
            });
        };


        const deleteItem = () => {
            category.delete(route('categories.destroy', category), {
                onSuccess: () => {
                    category.reset();
                    editting.value = false;
                    deleteDialog.value = false;
                },
            });
        };

        const showDeleteItem = (action = false, item = null) => {
            if (action) {
                category.name = item.name;
                category.award_id = item.award_id;
                category.id = item.id;
                deleteDialog.value = action;
                return;
            }
            deleteDialog.value = action;
        }
        const confirm = () => window.confirm('Are you sure you want to delete this category?');

        return {
            confirm, category, submit, showArtistModal, editting, editArtist, updateArtist, artistCreate, deleteItem,
            showDeleteItem,
            deleteDialog,
        }
    }
};
</script>