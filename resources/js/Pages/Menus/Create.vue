<script setup>
import { toast } from 'vue3-toastify';
import TextInput from '@/Components/TextInput.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    name: '',
    image: ''
});


const createMenu = async () => {
    await form.post(route('menus.store'), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onSuccess: () => {
            toast('Successfully created a new menu!', {
                autoClose: 1000,
            })
        }
    });
}



</script>

<template>
  <Head title="Create Menu" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-4">
            <div class="flex justify-between">
                <h1>Create Menu</h1>
                <Link :href="route('menus.index')" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Go Back
                </Link>
            </div>
        </div>
        <div class="mt-5">
            <form @submit.prevent="createMenu">
                <div class="my-4">
                    <InputLabel for="name" value="Name"/>
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name"/>
                </div>

                <div class="my-4">
                    <InputLabel for="image" value="Image"/>
                    <input
                        id="name"
                        @input="form.image = $event.target.files[0]"
                        type="file"
                        class="mt-1 block w-full py-2 px-2"
                        autocomplete="image"
                    />
                    <InputError class="mt-2" :message="form.errors.image"/>
                </div>
                <div class="my-4">
                    <PrimaryButton :disabled="form.processing">Create</PrimaryButton>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
