<template>
<Head title="Dashboard" />
    <div class="max-w-7xl mx-auto px-2">
        <div class="grid lg:grid-cols-4 grid-cols-2 gap-4 text-primary-500">
            <Link href="/" class="bg-primary-50 bg-primary-500 text-white transition hover:bg-primary-50 hover:text-primary-500 rounded-md shadow-sm p-4 flex">
                <div class="flex-1">
                    <h2 class="">Total Votes</h2>
                    <p class="text-3xl font-bold">{{ count_votes? count_votes : '00' }}</p>
                </div>
                <div class="">
                    <i class="fa-solid fa-ticket text-6xl"></i>
                </div>
            </Link>

            <Link href="/users" class="bg-primary-50 bg-primary-500 text-white transition hover:bg-primary-50 hover:text-primary-500 rounded-md shadow-sm p-4 flex">
                <div class="flex-1">
                    <h2 class="">Users</h2>
                    <p class="text-3xl font-bold">{{ count_users? count_users : '00' }}</p>
                </div>
                <div class="">
                    <i class="fa-solid fa-users text-6xl"></i>
                </div>
            </Link>

            <Link href="/artists" class="bg-primary-50 bg-primary-500 text-white transition hover:bg-primary-50 hover:text-primary-500 rounded-md shadow-sm p-4 flex">
                <div class="flex-1">
                    <h2 class="">Artists</h2>
                    <p class="text-3xl font-bold">{{ count_artists? count_artists : '00' }}</p>
                </div>
                <div class="">
                    <i class="fa-solid fa-users text-6xl"></i>
                </div>
            </Link>

            <Link href="/awards" class="bg-primary-50 bg-primary-500 text-white transition hover:bg-primary-50 hover:text-primary-500 rounded-md shadow-sm p-4 flex">
                <div class="flex-1">
                    <h2 class="">Awards</h2>
                    <p class="text-3xl font-bold">{{ count_awards? count_awards : '00' }}</p>
                </div>
                <div class="">
                    <i class="fa-solid fa-sack-dollar text-6xl"></i>
                </div>
            </Link>
        </div>
    </div>

    <div class="shadow rounded bg-white p-2 max-w-7xl mx-auto my-12 overflow-x-auto p-2">
        <div class="flex gap-2 place-items-center">
            <div class="flex-auto">
                <h2 class="text-2xl text-primary-500 font-bold mb-4">Recent Users</h2>
            </div>
            <Link href="/users" class="bg-primary-500 text-white px-2 py-1 text-sm hover:bg-primary-700 transition" >MANAGE USERS</Link>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="border-b border-gray-200 px-2 py-1"></th>
                    <th class="border-b border-gray-200 px-2 py-1 text-left">Name</th>
                    <th class="border-b border-gray-200 px-2 py-1 text-left">Email</th>
                    <th class="border-b border-gray-200 px-2 py-1 text-left">Phone</th>
                    <th class="border-b border-gray-200 px-2 py-1 text-left">Role</th>
                    <th class="border-b border-gray-200 px-2 py-1 text-left">Status</th>
                    <!-- <th class="border-b border-gray-200 px-2 py-1 text-right">Action</th> -->
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
                    <!-- <td class="border-b border-gray-200 px-2 py-1 text-right">
                        <Link :href="route('users.edit', user.id)" class="p-2 text-sky-500">
                            <i class="fa-solid fa-edit"></i>
                        </Link>
                        <button @click="showDeleteItem(true, user)" class="p-2 text-red-500">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td> -->
                </tr>
            </tbody>
        </table>
    </div>

    <div class="grid md:grid-cols-2 gap-4 max-w-7xl mx-auto mt-7 mb-4 p-2">
        <div class="shadow rounded bg-white p-2 overflow-x-auto">
            <div class="flex gap-2 place-items-center">
                <div class="flex-auto">
                    <h2 class="text-2xl text-primary-500 font-bold mb-4">Recent Artists</h2>
                </div>
                <Link href="/artists" class="bg-primary-500 text-white px-2 py-1 text-sm hover:bg-primary-700 transition" >MANAGE ARTISTS</Link>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border-b border-gray-200 px-2 py-1"></th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Name</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Award Category</th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Votes</th>
                        <!-- <th class="border-b border-gray-200 px-2 py-1 text-right">Action</th> -->
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr v-for="artist in artists.data" :key="artist.id" class="hover:bg-gray-50 transition">
                        <td class="border-b border-gray-200 px-2 py-1">
                            <i class="fa-solid fa-user"></i>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ artist.name }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ artist.awards_category.name }}</td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ artist.votes_count }}</td>
                        <!-- <td class="border-b border-gray-200 px-2 py-1 text-right">
                            <button @click="editArtist(artist.name, artist.awards_category.id, artist.id)" class="p-2 text-sky-500">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button @click="showDeleteItem(true, artist)" class="p-2 text-red-500">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="shadow rounded bg-white p-2 overflow-x-auto">
            <div class="flex gap-2 place-items-center">
                <div class="flex-auto">
                    <h2 class="text-2xl text-primary-500 font-bold mb-4">Recent Awards</h2>
                </div>
                <Link href="/awards" class="bg-primary-500 text-white px-2 py-1 text-sm hover:bg-primary-700 transition" >MANAGE AWRADS</Link>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border-b border-gray-200 px-2 py-1"></th>
                        <th class="border-b border-gray-200 px-2 py-1 text-left">Name</th>
                        <!-- <th class="border-b border-gray-200 px-2 py-1 text-right">Action</th> -->
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr v-for="award in awards.data" :key="award.id" class="hover:bg-gray-50 transition">
                        <td class="border-b border-gray-200 px-2 py-1">
                            <i class="fa-solid fa-user"></i>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-1 text-left">{{ award.name }}</td>
                        <!-- <td class="border-b border-gray-200 px-2 py-1 text-right">
                            <button @click="editAward(award.name, award.id)" class="p-2 text-sky-500">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button @click="showDeleteItem(true, award)" class="p-2 text-red-500">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import NavLink from '@/Components/NavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Layout from '@/Layouts/DashboardLayout.vue';
import { Link, Head } from '@inertiajs/vue3';
export default {
    layout: Layout,
    props: {
        count_votes: { Number },
        count_artists:{Number},
        count_users:{Number},
        count_awards: { Number },
        users: { Object },
        artists: { Object },
        awards: { Object },

    },
    components: { Link, Head, PrimaryButton, NavLink },
    setup() {

    }
}
</script>