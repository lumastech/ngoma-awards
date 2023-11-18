<template>
     <teleport to="body">
         <div v-show="loader" class="fixed w-full h-full top-0 lef-0 bg-black/50 z-50">
             <div class="flex h-full place-items-center">
                 <div class="flex-auto"></div>
                 <div class="flex-auto">
                     <div class="flex justify-center items-center h-full">
                         <div class="flex flex-col justify-center items-center rounded-md bg-white px-4 py-12">
                             <div class="loader"></div>
                         </div>
                     </div>
                 </div>
                 <div class="flex-auto">
                 </div>
             </div>
         </div>

     </teleport>
</template>

<script>
import { ref } from "vue";
export default {
    name: "Loader",
    props: {
        loader: {
            type: Boolean,
            default: false
        }
    },
}
</script>
<style>
/* HTML: <div class="loader"></div> */
.loader {
  width: 120px;
  height: 22px;
  border-radius: 40px;
  color: #468bff;
  border: 2px solid;
  position: relative;
}
.loader::before {
  content: "";
  position: absolute;
  margin: 2px;
  width: 25%;
  top: 0;
  bottom: 0;
  left: 0;
  border-radius: inherit;
  background: currentColor;
  animation: l3 1s infinite linear;
}
@keyframes l3 {
    50% {left:100%;transform: translateX(calc(-100% - 4px))}
}
</style>