<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";

const filename = ref();

const previewFile = ref([]);

const uploadedFile = (e) => {
    previewFile.value = e.target.files[0];
};

const sendFile = () => {
    const formData = new FormData();

    formData.append("file", previewFile.value);

    router.post(route("file-converter.store"), formData, {
        onSuccess: (response) => {
            filename.value = response.props;
        },
    });
};

const file = "storage/images/";
const jpgFile = window.location.origin + "/storage/images/";
</script>

<template>
    <Head title="Converter" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">File Converter</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="filename !== undefined" class="flex">
                            <div class="w-1/2">
                                <embed
                                    class="w-full h-[700px]"
                                    scrolling="no"
                                    :src="file + filename.pdf"
                                    frameborder="0"
                                />
                            </div>
                            <div class="w-1/2">
                                <img
                                    class="w-full h-[700px]"
                                    :src="jpgFile + filename.jpg"
                                    alt=""
                                />
                            </div>
                        </div>

                        <input
                            type="file"
                            accept="application/pdf"
                            @change="uploadedFile"
                        />

                        <br /><br /><br />
                        <button
                            @click="sendFile"
                            :class="{
                                'hover:bg-sky-400 active:bg-sky-200 bg-sky-300 text-gray-900': previewFile.length !== 0,
                                'bg-gray-300 text-gray-500': previewFile.length === 0
                            }"
                            class="px-4 py-2 rounded-full"
                            :disabled="previewFile.length === 0"
                        >
                            Convert Files
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
