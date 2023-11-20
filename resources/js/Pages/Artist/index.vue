<template>
    <Head title="artists" />
    <div class="max-w-7xl mx-auto px-2">
        <div class="md:flex justify-between gap-4 mb-4 place-items-center">
            <h2>ARTISTS</h2>
            <div class="flex gap-2">
                <Search :uri="uri" @res="(res) =>searchResponse(res)" class="flex" />
                <button @click="artistCreate" class="rounded bg-primary-500 text-white hover:bg-primary-600 transition border border-primary-500 px-2 md:px-4 py-2">
                    <i class="fa-solid fa-plus"></i> <span>Add Artist</span>
                </button>
            </div>
        </div>

        <div class="shadow rounded bg-white p-2 overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border-b border-gray-200 px-2 py-1"></th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Name</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Award Category</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Votes</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr v-for="artist in searcing? searchData.data : artists.data" :key="artist.id" class="hover:bg-gray-50 transition">
                        <td class="border-b border-gray-200 px-2 py-1">
                            <i class="fa-solid fa-user"></i>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ artist.name }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ artist.awards_category.name }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ artist.votes_count }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-right">
                            <button @click="editArtist(artist.name, artist.awards_category.id, artist.id)" class="p-2 text-sky-500">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button @click="showDeleteItem(true, artist)" class="p-2 text-red-500">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Paginate :data="searcing? searchData : artists" @search="(res) =>searchResponse(res)" />
        </div>
    </div>

    <!-- add artist -->
    <DialogModal :show="showArtistModal" :closeable="true">
        <template v-slot:title>
            <h4>Add an artist</h4>
        </template>
        <template v-slot:content>
            <form @submit.prevent="submit" action="#" method="post" class="grid md:grid-cols-2 gap-4 bg-white rounded-md p-4">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" v-model="artist.name" class="block border border-primary-400 rounded placeholder-gray-400 w-full" placeholder="James Phiri">
                    <span v-if="errors.name" class="text-red-500">{{ errors.name }}</span>
                </div>

                <div class="form-group">
                    <label for="awards_category_id">Award Category</label>
                    <select name="awards_category_id" v-model="artist.awards_category_id" id="awards_category_id" class="block border border-primary-400 rounded placeholder-gray-400 w-full">
                        <option v-for="award_category in award_categories" :key="award_category.id" :value="award_category.id">{{ award_category.name }}</option>
                    </select>
                    <span v-if="errors.awards_category_id" class="text-red-500">{{ errors.awards_category_id }}</span>
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
            <h4>Delete Artist</h4>
        </template>
        <template v-slot:content>
            <p>Are you sure you want to delete this artist?</p>
        </template>
        <template v-slot:footer>
            <button @click="deleteDialog = false" class="text-gray-500 hover:bg-primary-100 px-4 rounded transition">Cancel</button>
            <button @click="deleteItem" class="border border-primary-400 rounded placeholder-gray-400 bg-primary-500 text-white px-4 py-2 shadow-md hover:bg-primary-600 transition">Delete</button>
            <!-- <Link :href="route('artists.destroy', artist)" method="delete" class="text-red-500" as="button" type="button">
                <i class="fa-solid fa-trash-can"></i>
            </Link> -->
        </template>
    </ConfirmationModal>

    <Loader :loader="artist.processing" />
</template>

<script>
import DashboardLaout from '@/Layouts/DashboardLayout.vue';
import { ref } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DialogModal from '@/Components/DialogModal.vue';
import Search from '@/Components/Search.vue';
import Paginate from '@/Components/Paginate.vue';

export default {
    components: {
    DashboardLaout, Link, Head,
    ConfirmationModal,
    DialogModal,
    Search,
    Paginate
},
    props: {
        artists: Object,
        errors: Object,
        award_categories: Object,
        uri: String
    },
    layout: DashboardLaout,
    setup() {
        const searchData = ref(null);
        const searcing = ref(false);
        const artist = useForm({
            name: '',
            awards_category_id: '',
        });

        const showArtistModal = ref(false);
        const editting = ref(false);
        const deleteDialog = ref(false);

        const editArtist = (name, awards_category_id, id) => {
            artist.name = name;
            artist.awards_category_id = awards_category_id;
            artist.id = id;
            showArtistModal.value = true;
            editting.value = true;
        }

        const artistCreate = () => {
            artist.name = '';
            artist.awards_category_id = '';
            artist.id = '';
            showArtistModal.value = true;
            editting.value = false;
        }

        const submit = () => {
            if (editting.value) {
                updateArtist();
                return;
            }
            artist.post(route('artists.store'), {
                onSuccess: () => {
                    artist.reset();
                    editting.value = false;
                    showArtistModal.value = false;
                },
            });
        };
        const updateArtist = () => {
            artist.put(route('artists.update', artist), {
                onSuccess: () => {
                    artist.reset();
                    editting.value = false;
                    showArtistModal.value = false;
                },
            });
        };


        const deleteItem = () => {
            artist.delete(route('artists.destroy', artist), {
                onSuccess: () => {
                    artist.reset();
                    editting.value = false;
                    deleteDialog.value = false;
                },
            });
        };

        const showDeleteItem = (action = false, item = null) => {
            if (action) {
                artist.name = item.name;
                artist.awards_category_id = item.awards_category_id;
                artist.id = item.id;
                deleteDialog.value = action;
                return;
            }
            deleteDialog.value = action;
        }

        const searchResponse = (res) => {
            searchData.value = res;
            console.log(searchData.value)
            searcing.value = true;
        }

        return {
            confirm, artist, submit, showArtistModal, editting, editArtist, updateArtist, artistCreate, deleteItem,
            showDeleteItem,
            deleteDialog, editting, searchResponse, searchData, searcing
        }
    }
};
</script>