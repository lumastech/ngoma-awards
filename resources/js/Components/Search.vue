<template>
    <div class="flex rounded-md border border-primary-400 bg-white overflow-hidden">
        <input type="search" @keyup="search" v-model="searchInput" class="border-0 bg-none flex-auto" :placeholder="`Seach ${uri.split('/')[0]}`">
        <button @click="search" class="px-2 border-l bg-primary-50 hover:bg-primary-500 hover:text-white text-primary-500 transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
</template>
<script>
import axios from 'axios';
import { ref } from 'vue';
export default {
    props: {
        uri: {
            type: String,
            default: ''
        }
    },
    setup(props, {emit}) {
        const url = props.uri;
        const searchInput = ref(null);
        const search = () => {
            console.log(url)
            // search logic
            axios.get(url, {
                params: {
                    search: searchInput.value
                }
            }).then(res => {
                emit('res', res.data)
            }).catch(err => {
                console.log(err);
            })
        }

        return {search, searchInput}
    }
}
</script>