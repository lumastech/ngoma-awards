<template>
    <Head title="awards" />
    <div class="max-w-7xl mx-auto px-2">
        <div class="flex justify-between gap-4 mb-4 place-items-center">
            <h2>AWARDS</h2>
            <button @click="awardCreate" class="rounded bg-primary-500 text-white hover:bg-primary-600 transition border border-primary-500 px-4 py-2">
                <i class="fa-solid fa-plus"></i> <span>Add An Award</span>
            </button>
        </div>

        <div class="shadow rounded bg-white p-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border-b border-gray-200 px-2 py-1"></th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Name</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr v-for="award in awards.data" :key="award.id" class="hover:bg-gray-50 transition">
                        <td class="border-b border-gray-200 px-2 py-1">
                            <i class="fa-solid fa-user"></i>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ award.name }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-right">
                            <button @click="editAward(award.name, award.id)" class="p-2 text-sky-500">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button @click="showDeleteItem(true, award)" class="p-2 text-red-500">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- add award -->
    <DialogModal :show="showAwardModal" :closeable="true">
        <template v-slot:title>
            <h4>Add an award</h4>
        </template>
        <template v-slot:content>
            <form @submit.prevent="submit" action="#" method="post" class="grid md:grid-cols-2 gap-4 bg-white rounded-md p-4">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" v-model="award.name" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="Award name">
                    <span v-if="errors.name" class="text-red-500">{{ errors.name }}</span>
                </div>
            </form>
        </template>
        <template v-slot:footer>
            <button @click="showAwardModal = false" class="text-gray-500 hover:bg-primary-100 px-4 rounded transition">Cancel</button>
            <button v-if="!editting" @click="submit" class="border border-primary-400 rounded placeholder-gray-400 bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Submit</button>
            <button v-if="editting" @click="updateAward" class="border border-primary-400 rounded placeholder-gray-400 bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Update</button>
        </template>
    </DialogModal>


    <!-- confirm dialog -->
    <ConfirmationModal :show="deleteDialog" :closeable="true">
        <template v-slot:title>
            <h4>Delete Award</h4>
        </template>
        <template v-slot:content>
            <p>Are you sure you want to delete this award?</p>
        </template>
        <template v-slot:footer>
            <button @click="deleteDialog = false" class="text-gray-500 hover:bg-primary-100 px-4 rounded transition">Cancel</button>
            <button @click="deleteItem" class="border border-primary-400 rounded placeholder-gray-400 bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Delete</button>
            <!-- <Link :href="route('awards.destroy', award)" method="delete" class="text-red-500" as="button" type="button">
                <i class="fa-solid fa-trash-can"></i>
            </Link> -->
        </template>
    </ConfirmationModal>
    <Loader :loader="award.processing" />
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
        awards: Object,
        errors: Object,
        award_categories: Object,
    },
    layout: DashboardLaout,
    setup() {
        const award = useForm({
            name: '',
        });

        const showAwardModal = ref(false);
        const editting = ref(false);
        const deleteDialog = ref(false);

        const editAward = (name, id) => {
            award.name = name;
            award.id = id;
            console.log(award.id);
            showAwardModal.value = true;
            editting.value = true;
        }

        const awardCreate = () => {
            award.name = '';
            award.id = '';
            showAwardModal.value = true;
            editting.value = false;
        }

        const submit = () => {
            if (editting.value) {
                updateAward();
                return;
            }
            award.post(route('awards.store'), {
                onSuccess: () => {
                    award.reset();
                    editting.value = false;
                    showAwardModal.value = false;
                },
            });
        };
        const updateAward = () => {
            award.put(route('awards.update', award), {
                onSuccess: () => {
                    award.reset();
                    editting.value = false;
                    showAwardModal.value = false;
                },
            });
        };


        const deleteItem = () => {
            award.delete(route('awards.destroy', award), {
                onSuccess: () => {
                    award.reset();
                    editting.value = false;
                    deleteDialog.value = false;
                },
            });
        };

        const showDeleteItem = (action = false, item = null) => {
            if (action) {
                award.name = item.name;
                award.id = item.id;
                deleteDialog.value = action;
                return;
            }
            deleteDialog.value = action;
        }
        const confirm = () => window.confirm('Are you sure you want to delete this award?');

        return {
            confirm, award, submit, showAwardModal, editting, editAward, updateAward, awardCreate, deleteItem,
            showDeleteItem,
            deleteDialog,
        }
    }
};
</script>