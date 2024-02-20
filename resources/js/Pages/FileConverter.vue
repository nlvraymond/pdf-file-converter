<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";

const previewFile = ref([]);

const uploadedFile = (e) => {
    previewFile.value = e.target.files[0];
};

const sendFile = () => {
    const formData = new FormData();

    formData.append("file", previewFile.value);

    router.post(route("file-converter.store"), formData);
};
</script>

<template>
    <Head title="Converter" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <input
                            type="file"
                            accept="application/pdf"
                            @change="uploadedFile"
                        />

                        <br /><br /><br />
                        <button
                            @click="sendFile"
                            class="bg-sky-200 px-4 py-2 rounded-full text-gray-900"
                        >
                            Convert File
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
