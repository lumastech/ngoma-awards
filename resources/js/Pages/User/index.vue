<template>
    <Head title="Users" />
    <div class="max-w-7xl mx-auto px-2">
        <div class="flex justify-between gap-4 mb-4 place-items-center">
            <h2>STAFFS</h2>
            <Link :href="route('users.create')" class="rounded bg-primary-500 text-white hover:bg-primary-600 transition border border-primary-500 px-4 py-2">
                <i class="fa-solid fa-plus"></i>
                <span>Add Staff</span>
            </Link>
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
                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 transition">
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
                            <Link :href="route('users.destroy', user.id)" method="delete" class="text-red-500" as="button" type="button" :onBefore="confirm" >
                                <i class="fa-solid fa-trash-can"></i>
                            </Link>

                            <ConfirmationModal :show="0" >
                                <template v-slot:title>
                                    <h4>Confirm action</h4>
                                </template>
                                <template v-slot:content>
                                    <p>Are you sure you want to delete this user?</p>
                                </template>
                                <template v-slot:footer>
                                    <button @click="close" class="text-gray-500 hover:bg-primary-100 px-4 rounded transition">Cancel</button>
                                </template>
                            </ConfirmationModal>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import DashboardLaout from '@/Layouts/DashboardLayout.vue';
// import Inertia from '@inertiajs/vue3'
import { Link, Head } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

export default {
    components: {
    DashboardLaout, Link, Head,
    ConfirmationModal
},
    props: {
        users: Object,
    },
    layout: DashboardLaout,
    setup() {
        const confirm = () => window.confirm('Are you sure you want to delete this user?');

        return {
            confirm
        }
    }
};
</script>