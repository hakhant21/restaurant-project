<script setup>
import { toast } from 'vue3-toastify';
import "@vuepic/vue-datepicker/dist/main.css";
import TextInput from '@/Components/TextInput.vue';
import VueDatePicker from "@vuepic/vue-datepicker";
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    name: '',
    discountPercentage: '',
    promotionDate: '',
    type: '',
    status: '',
    baseAmount: ''
});

const createPromotion = async () => {
    await form.post(route('promotions.store'), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onSuccess: () => {
            toast('Successfully created a new promotion!', {
                autoClose: 1000,
            })
        }
    });
}



</script>

<template>
  <Head title="Create Promotion" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-4">
            <div class="flex justify-between">
                <h1>Create Promotion</h1>
                <Link :href="route('promotions.index')" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                 Go Back
                </Link>
            </div>
        </div>
        <div class="mt-5 min-h-screen">
            <form @submit.prevent="createPromotion">
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
                    <InputLabel for="discountPercentage" value="Percentage"/>
                    <TextInput
                        id="name"
                        v-model="form.discountPercentage"
                        type="number"
                        class="mt-1 block w-full"
                        min="0"
                        autocomplete="discountPercentage"
                    />
                    <InputError class="mt-2" :message="form.errors.discountPercentage"/>
                </div>
                <div class="my-4">
                <InputLabel for="type" value="Choose Type" />
                <select
                    v-model="form.type"
                    name="type"
                    id="type"
                    class="w-full rounded py-2"
                >
                    <option>Choose Type</option>
                    <option value="event">Event</option>
                    <option value="promotion">Promotion</option>
                </select>
                <InputError class="mt-2" :message="form.errors.type" />
                </div>
                <div class="my-4" v-if="form.type == 'promotion'">
                    <InputLabel for="base_amount" value="Base Amount" />
                    <TextInput
                        id="baseAmount"
                        v-model="form.baseAmount"
                        type="number"
                        class="mt-1 block w-full"
                        min="0"
                        autocomplete="baseAmount"
                    />
                    <InputError class="mt-2" :message="form.errors.baseAmount" />
                </div>
                <div class="my-4">
                <InputLabel for="type" value="Choose Status" />
                <select
                    v-model="form.status"
                    name="status"
                    id="status"
                    class="w-full rounded py-2"
                >
                    <option>Choose Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <InputError class="mt-2" :message="form.errors.status" />
                </div>
                <div class="my-4">
                    <InputLabel for="promotionDate" value="Start Date / End Date"/>
                    <VueDatePicker
                        class="mt-1 w-full bg-gray-50 outline-none"
                        v-model="form.promotionDate"
                        range
                        :enable-time-picker="false"
                    />
                    <InputError class="mt-2" :message="form.errors.promotionDate"/>
                </div>
                <div class="my-4">
                    <PrimaryButton :disabled="form.processing">Create</PrimaryButton>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
