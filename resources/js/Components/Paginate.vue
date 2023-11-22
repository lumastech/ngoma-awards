<template>
    <!-- paginate -->
    <div class="flex justify-between items-center my-4">
        <div class="flex-1 flex justify-between sm:hidden">
            <a href="#"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Previous
            </a>
            <a href="#"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Next
            </a>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ data.from }}</span>
                    to
                    <span class="font-medium">{{ data.to }}</span>
                    of
                    <span class="font-medium">{{ data.total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <Link v-for="link in data.links" :key="link.label" :href="link.url" v-html="link.label"
                        class="z-10 relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                        :class="{'bg-indigo-50 border-indigo-500 text-indigo-600 ':link.active, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50':!link.active}">
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import {Link} from '@inertiajs/vue3'
export default {
    props: {
        data: Object
    },
    components: {Link}
}
</script>