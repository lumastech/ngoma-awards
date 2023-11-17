<template>
    <Head title="artists" />
    <div class="max-w-7xl mx-auto px-2">
        <div class="flex justify-between gap-4 mb-4 place-items-center">
            <h2>STAFFS</h2>
            <button @click="showArtistModal = true" class="rounded bg-primary-500 text-white hover:bg-primary-600 transition border border-primary-500 px-4 py-2">
                <i class="fa-solid fa-plus"></i> <span>Add An Artist</span>
            </button>
            <!-- <Link :href="route('artists.create')" class="rounded bg-primary-500 text-white hover:bg-primary-600 transition border border-primary-500 px-4 py-2">
                <i class="fa-solid fa-plus"></i> <span>Add An Artist</span>
            </Link> -->
        </div>

        <div class="shadow rounded bg-white p-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border-b border-gray-200 px-2 py-1"></th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Name</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Award Category</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr v-for="artist in artists.data" :key="artist.id" class="hover:bg-gray-50 transition">
                        <td class="border-b border-gray-200 px-2 py-1">
                            <i class="fa-solid fa-artist"></i>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ artist.name }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">
                            <a href="mailto:m@d.c">{{ artist.award_category }}</a>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-1 text-right">
                            <Link :href="route('artists.edit', artist.id)" class="p-2 text-sky-500">
                                <i class="fa-solid fa-edit"></i>
                            </Link>
                            <Link :href="route('artists.destroy', artist.id)" method="delete" class="text-red-500" as="button" type="button" :onBefore="confirm" >
                                <i class="fa-solid fa-trash-can"></i>
                            </Link>

                            <ConfirmationModal :show="0" >
                                <template v-slot:title>
                                    <h4>Confirm action</h4>
                                </template>
                                <template v-slot:content>
                                    <p>Are you sure you want to delete this artist?</p>
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

    <!-- add artist -->
    <DialogModal :show="showArtistModal" :closeable="true">
        <template v-slot:title>
            <h4>Add an artist</h4>
        </template>
        <template v-slot:content>
            <form @submit.prevent="submit" action="#" method="post" class="grid grid-cols-2 gap-4 bg-white rounded-md p-4">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" v-model="artist.name" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="James Phiri">
                    <span v-if="errors.name" class="text-red-500">{{ errors.name }}</span>
                </div>

                <div class="form-group">
                    <label for="award_category">Award Category</label>
                    <select name="award_category" v-model="artist.award_category" id="award_category" class="block border border-primary-400 rounded placeholder-gray-400 w-full">
                        <option v-for="award_category in award_categories" :key="award_category.id" :value="award_category.id">{{ award_category.name }}</option>
                    </select>
                    <span v-if="errors.award_category" class="text-red-500">{{ errors.award_category }}</span>
                </div>
            </form>
        </template>
        <template v-slot:footer>
            <button @click="showArtistModal = false" class="text-gray-500 hover:bg-primary-100 px-4 rounded transition">Cancel</button>
            <button class="border border-primary-400 rounded placeholder-gray-400 bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Submit</button>
        </template>
    </DialogModal>
</template>

<script>
import DashboardLaout from '@/Layouts/DashboardLayout.vue';
import { reactive, ref } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DialogModal from '@/Components/DialogModal.vue';

export default {
    components: {
    DashboardLaout, Link, Head,
        ConfirmationModal,
        DialogModal,
},
    props: {
        artists: Object,
        errors: Object,
        award_categories: Object,
    },
    layout: DashboardLaout,
    setup() {
        const artist = reactive({
            name: '',
            award_category: '',
        });
        const showArtistModal = ref(false);
        const submit = () => {
            artist.post(route('artists.store'), {
                onSuccess: () => {
                    artist.reset();
                },
            });
        };
        const confirm = () => window.confirm('Are you sure you want to delete this artist?');

        return {
            confirm, artist, submit, showArtistModal,
        }
    }
};
</script>