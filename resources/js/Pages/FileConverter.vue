<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";

const filename = ref();

const previewFile = ref();

const file = ref()

const uploadedFile = (e) => {
    const reader = new FileReader();
    reader.onload = (event) => {
        file.value = event.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);

    console.log(file.value);
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
</script>

<template>
    <Head title="Converter" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                File Converter
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="previewFile !== undefined" class="mx-auto">
                            <div class="w-1/2">
                                <embed
                                    id="fred"
                                    style="border: 1px solid #666ccc"
                                    title="PDF in an i-Frame"
                                    :src="previewFile"
                                    frameborder="1"
                                    scrolling="auto"
                                    width="100%"
                                    height="600px"
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
                                'hover:bg-sky-400 active:bg-sky-200 bg-sky-300 text-gray-900':
                                    previewFile !== undefined,
                                'bg-gray-300 text-gray-500':
                                    previewFile === undefined,
                            }"
                            class="px-4 py-2 rounded-full"
                            :disabled="previewFile === undefined"
                        >
                            Convert Files
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
