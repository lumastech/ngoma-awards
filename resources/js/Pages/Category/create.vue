<template>
<Head title="Add Staff" />
<div class="max-w-7xl mx-auto px-2">
    <div class="flex justify-between gap-4 mb-4 place-items-center">
        <h2>ADD A STAFF</h2>
        <Link :href="route('artists.index')" class="rounded bg-primary-500 text-white hover:bg-primary-600 transition border border-primary-500 px-4 py-2">
            <i class="fa-solid fa-plus"></i>
            <span>Back to Staff</span>
        </Link>
    </div>

    <!-- create artist form -->
    <form @submit.prevent="submit" action="#" method="post" class="grid grid-cols-2 gap-4 bg-white rounded-md p-4">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" v-model="form.name" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="James Phiri">
            <span v-if="errors.name" class="text-red-500">{{ errors.name }}</span>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" v-model="form.email" name="email" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="james@email.com">
            <span v-if="errors.email" class="text-red-500">{{ errors.email }}</span>
        </div>
        <button class="block border border-primary-400 rounded placeholder-gray-400 w-full bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Submit</button>
    </form>

</div>
</template>

<script>
import DashboardLaout from '@/Layouts/DashboardLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';

export default {
    components: {
        DashboardLaout, Link, Head
    },
    props: {
        errors: Object,
    },
    layout: DashboardLaout,

    setup() {
        const form = useForm({
            name: '',
            email: '',
            phone: '',
            role: 'artist',
            status: 'active',
            address: '',
        });

        const submit = () => {
            form.post(route('artists.store'), {
                onSuccess: () => {
                    form.reset();
                },
            });
        };

        return {
            form,
            submit,
        };
    },
};
</script>
