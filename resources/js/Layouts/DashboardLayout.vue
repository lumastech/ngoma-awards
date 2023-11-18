<template>
    <div class="fixed w-full h-full">
        <div class="md:flex h-full bg-sky-50">
            <aside @click="sideToggle" class="z-50 md:w-[60px] xl:w-[300px] shrink-0 bg-primary-50 py-7 h-full md:m-2 rounded-md overflow-x-hidden overflow-y-auto absolute md:static md:translate-x-0 -translate-x-full transition">
                <nav class="flex flex-col px-2 md:px-2">
                    <a href="/dashboard" class="flex flex-nowrap place-items-center overflow-hidden text-primary-500 px-4 py-2 rounded md:rounded-lg bg-white shadow-md my-1 hover:bg-primary-500 hover:text-white transition">
                        <i class="fa-solid fa-home w-7 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="/users" class="flex flex-nowrap place-items-center overflow-hidden text-primary-500 px-4 py-2 rounded md:rounded-lg bg-white shadow-sm my-1 hover:bg-primary-500 hover:text-white transition">
                        <i class="fa-solid fa-user w-7 mr-3"></i>
                        Users
                    </a>
                    <a href="/artists" class="flex flex-nowrap place-items-center overflow-hidden text-primary-500 px-4 py-2 rounded md:rounded-lg bg-white shadow-sm my-1 hover:bg-primary-500 hover:text-white transition">
                        <i class="fa-solid fa-ticket w-7 mr-3"></i>
                        Artists
                    </a>
                    <a href="/awards" class="flex flex-nowrap place-items-center overflow-hidden text-primary-500 px-4 py-2 rounded md:rounded-lg bg-white shadow-sm my-1 hover:bg-primary-500 hover:text-white transition">
                        <i class="fa-solid fa-ticket w-7 mr-3"></i>
                        Awards
                    </a>
                    <a href="/categories" class="flex flex-nowrap place-items-center overflow-hidden text-primary-500 px-4 py-2 rounded md:rounded-lg bg-white shadow-sm my-1 hover:bg-primary-500 hover:text-white transition">
                        <i class="fa-solid fa-file-invoice-dollar w-7 mr-3"></i>
                        Award Categories
                    </a>
                </nav>
            </aside>
            <main class="flex-auto h-full text-gray-500">
                <div class="flex justify-between p-4 mb-4 place-items-center border-b">
                    <h2 class="text-gray-500">DASHBOARD</h2>
                    <div class="flex space-x-4">
                        <p class="rounded-full p-2 bg-primary-50 border border-primary-400 w-10 h-10 flex place-items-center justify-center text-primary-500 ">
                            <i class="fa-solid fa-user"></i>
                        </p>
                        <Link href="/logout" as="button" type="button" method="post" class="rounded-md p-2 bg-primary-50 border border-primary-400  h-10 flex gap-2 place-items-center justify-center text-primary-500 hover:bg-primary-500 hover:text-white transition ">
                            <i class="fa-solid fa-sign-out"></i>
                            <span class="hidden md:inline">Logout</span>
                        </Link>
                        <!-- menu button -->
                        <button @click="sideToggle" class="md:hidden rounded-md p-2 bg-primary-50 border border-primary-400  h-10 flex gap-2 place-items-center justify-center text-primary-500 hover:bg-primary-500 hover:text-white transition ">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                    </div>
                </div>
                <div class="pt-10 h-full w-full overflow-auto pb-32 md:pb-32">
                    <slot></slot>
                </div>
            </main>
        </div>
    </div>
</template>
<script>
import { Link } from '@inertiajs/vue3'
export default {
    components: { Link },
    setup() {
        const sideToggle = () => {
            const aside = document.querySelector('aside');
            aside.classList.toggle('-translate-x-full');
        }
        return {
            sideToggle
        }
    }
}
</script>
