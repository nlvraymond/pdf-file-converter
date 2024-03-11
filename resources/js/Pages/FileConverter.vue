<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";

const files = defineProps(["files"]);

const previewFile = ref();
const numberConvert = ref(1);

const loadConvert = ref(false);

const uploadedFile = (e) => {
    let file;
    const reader = new FileReader();
    reader.onload = (event) => {
        file = event.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);

    previewFile.value = e.target.files[0];
};

const sendFile = () => {
    const formData = new FormData();

    formData.append("file", previewFile.value);
    formData.append("number", numberConvert.value);

    loadConvert.value = true;
    router.post(route("file-converter.store"), formData, {
        onSuccess: () => {
            loadConvert.value = false;

            location.reload();
        },
    });
};
const deleteFile = (selectedFile) => {
    router.delete(route("file-converter.destroy", selectedFile));
};

const downloadSelectedFile = async (selectedImage) => {
    const image = await fetch(
        window.location.origin + "/storage/images/" + selectedImage
    );
    const imageBlog = await image.blob();
    const imageURL = URL.createObjectURL(imageBlog);

    const link = document.createElement("a");
    link.href = imageURL;
    link.download = selectedImage;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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

        <div v-if="loadConvert" class="mt-10 flex ml-60">
            <svg
                aria-hidden="true"
                class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                viewBox="0 0 100 101"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor"
                />
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill"
                />
            </svg>
            <span class="text-white p-1">Loading...</span>
        </div>

        <div
            :class="{
                'py-12': !loadConvert,
                'py-6': loadConvert,
            }"
            class="py-12 pb-2"
        >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex">
                            <input
                                type="file"
                                accept="application/pdf"
                                @change="uploadedFile"
                            />

                            <div class="w-1/3">
                                <label for="number_convert"
                                    >How many file conversion?</label
                                >
                                <input
                                    name="number_convert"
                                    placeholder="Number of file/s to be converted"
                                    type="number"
                                    class="text-black w-full mx-auto rounded-md"
                                    v-model="numberConvert"
                                />
                            </div>
                        </div>

                        <br /><br />
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

        <div
            :class="{
                'py-12': !loadConvert,
                'py-6': loadConvert,
            }"
            class="py-12 pt-2"
        >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="overflow-x-auto">
                            <table
                                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                            >
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                >
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Filename
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Uploaded file
                                        </th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="file in files.files"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                    >
                                        <th
                                            scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                        >
                                            {{
                                                file.filename.includes("pdf")
                                                    ? file.original_file +
                                                      " (sanitized)"
                                                    : file.filename
                                            }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ file.original_file }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a
                                                @click="
                                                    downloadSelectedFile(
                                                        file.filename
                                                    )
                                                "
                                                class="flex hover:text-blue-500 cursor-pointer"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-download m-1"
                                                    width="17"
                                                    height="17"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    fill="none"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <path
                                                        stroke="none"
                                                        d="M0 0h24v24H0z"
                                                        fill="none"
                                                    />
                                                    <path
                                                        d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"
                                                    />
                                                    <path d="M7 11l5 5l5 -5" />
                                                    <path d="M12 4l0 12" />
                                                </svg>
                                                <p class="p-1">Download</p>
                                            </a>
                                        </td>
                                        <td>
                                            <a
                                                class="flex hover:text-red-700 cursor-pointer text-red-500"
                                                @click="deleteFile(file.id)"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-trash m-1"
                                                    width="17"
                                                    height="17"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    fill="none"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <path
                                                        stroke="none"
                                                        d="M0 0h24v24H0z"
                                                        fill="none"
                                                    />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path
                                                        d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"
                                                    />
                                                    <path
                                                        d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"
                                                    />
                                                </svg>
                                                <p class="p-1">Delete</p>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
