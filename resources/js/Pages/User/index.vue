<template>
    <Head title="Users" />
    <div class="max-w-7xl mx-auto px-2">
        <div class="md:flex justify-between gap-4 mb-4 place-items-center">
            <h2>STAFFS</h2>
            <div class="flex gap-2">
                <Search uri="users/search" @res="(res) =>searchResponse(res)" />
                <Link :href="route('users.create')" class="rounded bg-primary-500 text-white hover:bg-primary-600 transition border border-primary-500 px-4 py-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Staff</span>
                </Link>
            </div>
        </div>

        <div class="shadow rounded bg-white p-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border-b border-gray-200 px-2 py-1"></th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Name</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Email</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Phone</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Role</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Status</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr v-for="user in searcing? searchData.data : users.data" :key="user.id" class="hover:bg-gray-50 transition">
                        <td class="border-b border-gray-200 px-2 py-1">
                            <i class="fa-solid fa-user"></i>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ user.name }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">
                            <a href="mailto:m@d.c">{{ user.email }}</a>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-1">{{ user.phone }}</td>
                        <td class="border-b border-gray-200 px-2 py-1">{{ user.role }}</td>
                        <td class="border-b border-gray-200 px-2 py-1">{{ user.status }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-right">
                            <Link :href="route('users.edit', user.id)" class="p-2 text-sky-500">
                                <i class="fa-solid fa-edit"></i>
                            </Link>
                            <button @click="showDeleteItem(true, user)" class="p-2 text-red-500">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <!-- confirm dialog -->
    <ConfirmationModal :show="deleteDialog" :closeable="true">
        <template v-slot:title>
            <h4>Delete {{ user.name }}</h4>
        </template>
        <template v-slot:content>
            <p>Are you sure you want to delete this User ?</p>
        </template>
        <template v-slot:footer>
            <button @click="deleteDialog = false" class="text-gray-500 hover:bg-primary-100 px-4 rounded transition">Cancel</button>
            <button @click="deleteItem" class="border border-primary-400 rounded placeholder-gray-400 bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Delete</button>
        </template>
    </ConfirmationModal>
</template>

<script>
import DashboardLaout from '@/Layouts/DashboardLayout.vue';
import { ref } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import Search from '@/Components/Search.vue';

export default {
    components: {
    DashboardLaout, Link, Head,
        ConfirmationModal,
        Search,
},
    props: {
        users: Object,
    },
    layout: DashboardLaout,
    setup(props) {
        const searchData = ref(null);
        const searcing = ref(false);
        const deleteDialog = ref(false);
        const editting = ref(false);
        const user = useForm({
            name: '',
            id: '',
        });

        const searchResponse = (res) => {
            searchData.value = res;
            searcing.value = true;
        }

        const deleteItem = () => {
            user.delete(route('users.destroy', user), {
                onSuccess: () => {
                    user.reset();
                    editting.value = false;
                    deleteDialog.value = false;
                },
            });
        };

        const showDeleteItem = (action = false, item = null) => {
            if (action) {
                user.name = item.name;
                user.id = item.id;
                deleteDialog.value = action;
                return;
            }
            deleteDialog.value = action;
        }

        return {
            confirm, deleteDialog, deleteItem, showDeleteItem, user, editting, searchResponse, searchData, searcing
        }
    }
};
</script>