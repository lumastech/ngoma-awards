<template>
<Head title="UPDATE Staff" />
<div class="max-w-7xl mx-auto px-2">
    <div class="flex justify-between gap-4 mb-4 place-items-center">
        <h2>UPDATE STAFF</h2>
        <Link :href="route('artists.index')" class="rounded bg-primary-500 text-white hover:bg-primary-600 transition border border-primary-500 px-4 py-2">
            <i class="fa-solid fa-plus"></i>
            <span>Back to Artists</span>
        </Link>
    </div>

    <!-- create artist form -->
    <form @submit.prevent="submit(artist)" action="#" method="post" class="grid grid-cols-2 gap-4 bg-white rounded-md p-4">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" v-model="artist.name" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="James Phiri">
            <span v-if="errors.name" class="text-red-500">{{ errors.name }}</span>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" v-model="artist.email" name="email" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="james@email.com">
            <span v-if="errors.email" class="text-red-500">{{ errors.email }}</span>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" v-model="artist.phone" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="0971234567">
            <span v-if="errors.phone" class="text-red-500">{{ errors.phone }}</span>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role"  v-model="artist.role" id="role" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="select artist role">
                <option value="" disabled>select artist role</option>
                <option value="admin">Admin</option>
                <option value="staff" selected>Staff</option>
                <option value="artist">artist</option>
            </select>
            <span v-if="errors.role" class="text-red-500">{{ errors.role }}</span>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status"  v-model="artist.status" id="role" class="block border border-primary-400 rounded placeholder-gray-400 w-full">
                <option value="" disabled>select artist status</option>
                <option value="active" selected>Active</option>
                <option value="inactive" >Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
            <span v-if="errors.status" class="text-red-500">{{ errors.status }}</span>
        </div>

        <div class="form-group md:col-span-2">
            <label for="address">Address</label>
            <textarea v-model="artist.address" name="address" id="address" rows="5" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="Kabulonga, Lusaka"></textarea>
            <span v-if="errors.address" class="text-red-500">{{ errors.address }}</span>
        </div>
        <button class="block border border-primary-400 rounded placeholder-gray-400 w-full bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Submit</button>
    </form>

</div>
</template>

<script>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

export default {
    components: {
        DashboardLayout, Link, Head
    },
    props: {
        artist: Object,
        errors: Object,
    },
    layout: DashboardLayout,

    setup() {
        const form = useForm({
            name: '',
            email: '',
            phone: '',
            role: '',
            status: '',
            address: '',
        });

        const submit = (artist) => {
            form.name = artist.name
            form.email = artist.email
            form.phone = artist.phone
            form.role = artist.role
            form.status = artist.status
            form.address = artist.address
            console.log(form);
            form.put(route('artists.update', artist.id));
        };

        return {
            form,
            submit,
        };
    },
};
</script>
