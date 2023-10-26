<script setup>
import TextArea from "@/Components/TextArea.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { toast } from "vue3-toastify";

const props = defineProps({
  menus: {
    type: Object,
    default: {},
  },
});

const form = useForm({
  menu_id: "",
  name: "",
  description: "",
  image: "",
  price: "",
});

const createMenuItem = async () => {
  await form.post(route("menu_items.store"), {
    preserveScroll: true,
    preserveState: true,
    replace: true,
    onSuccess: () => {
      toast("Successfully created a new menu items!", {
        autoClose: 1000,
      });
    },
  });
};
const isLetter = (e) => {
  let char = String.fromCharCode(e.keyCode);
  if (/^[A-Za-z0-9$&@#\s]+$/.test(char)) return true;
  else e.preventDefault();
};
</script>

<template>
  <Head title="Create Menu Item" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <h1>Create Menu Item</h1>
        <Link
          :href="route('menu_items.index')"
          class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        >
          Go Back
        </Link>
      </div>
    </div>
    <div class="mt-5">
      <form @submit.prevent="createMenuItem">
        <div class="my-4">
          <InputLabel for="menus" value="Menus" />
          <select
            v-model="form.menu_id"
            name="menu_id"
            id="menu_id"
            class="w-full rounded py-2"
          >
            <option value="">Choose Menus</option>
            <option v-for="menu in props.menus" :key="menu.id" :value="menu.id">
              {{ menu.name }}
            </option>
          </select>
          <InputError class="mt-2" :message="form.errors.menu_id" />
        </div>
        <div class="my-4">
          <InputLabel for="name" value="Name" />
          <TextInput
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-full"
            autocomplete="name"
            v-on:keypress="isLetter($event)"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>
        <div class="my-4">
          <InputLabel for="description" value="Description" />
          <TextArea
            id="description"
            v-model="form.description"
            type="text"
            class="mt-1 block w-full"
            autocomplete="description"
          />
          <InputError class="mt-2" :message="form.errors.description" />
        </div>
        <div class="my-4">
          <InputLabel for="price" value="Price" />
          <TextInput
            id="price"
            v-model="form.price"
            type="number"
            min="0"
            class="mt-1 block w-full"
            autocomplete="price"
          />
          <InputError class="mt-2" :message="form.errors.price" />
        </div>
        <div class="my-4">
          <InputLabel for="image" value="Image" />
          <input
            id="name"
            @input="form.image = $event.target.files[0]"
            type="file"
            class="mt-1 block w-full py-2 px-2"
            autocomplete="image"
          />
          <InputError class="mt-2" :message="form.errors.image" />
        </div>
        <div class="my-4">
          <PrimaryButton :disabled="form.processing">Create</PrimaryButton>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
