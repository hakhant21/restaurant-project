<script setup>
import { reactive, ref, watch } from "vue";
import debounce from "lodash/debounce";
import { toast } from "vue3-toastify";
import Table from "@/Components/Table.vue";
import Modal from "@/Components/Modal.vue";
import TableRow from "@/Components/TableRow.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import DangerButton from "@/Components/DangerButton.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { usePaginateSerialNumber } from "@/Composables/pagination.js";
import Pagination from "@/Components/Pagination.vue";
const form = useForm({});
const showConfirmDeleteMenuModal = ref(false);

const props = defineProps({
  menus: {
    type: Object,
    default: {},
  },
  filters: {
    type: Object,
    default: {},
  },
});

const filtermenu = ref(props.filters.filtermenu);

watch(
  [filtermenu],
  debounce(function ([filtermenuValue]) {
    router.get(
      route("menus.index"),
      {
        filtermenu: filtermenuValue,
      },
      {
        preserveState: true,
        replace: true,
      }
    );
  })
);
const deleteMenu = async (menu) => {
  showConfirmDeleteMenuModal.value = true;
  form.delete(route("menus.delete", menu), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      closeModal();
      toast("Successfully deleted menu!", {
        autoClose: 1000,
      });
    },
  });
};

const closeModal = () => {
  showConfirmDeleteMenuModal.value = false;
};
const getSerialNumber = (index) => {
  let number = usePaginateSerialNumber({
    current_page: props.menus.meta.current_page,
    per_page: props.menus.meta.per_page,
    index,
  });
  return number;
};
</script>

<template>
  <Head title="Menus" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <!-- start filter -->
      <div class="flex flex-start max-w-7xl mx-auto py-4">
        <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0">
          <div class="w-full max-w-md mx-auto">
            <div class="items-center p-2">
              <InputLabel for="manu" value="Menu Name" class="mr-2 text-gray-400" />
              <div class="flex-grow">
                <TextInput
                  id="menu"
                  v-model="filtermenu"
                  type="text"
                  class="mt-1 block w-full bg-gray-50 outline-none"
                  autocomplete=""
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- End Filter -->
      <div class="flex justify-between">
        <h1>Menus</h1>
        <Link
          :href="route('menus.create')"
          class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        >
          Add Menu
        </Link>
      </div>
      <div class="mt-5">
        <Table>
          <template #header>
            <TableRow>
              <TableHeaderCell>ID</TableHeaderCell>
              <TableHeaderCell>Image</TableHeaderCell>
              <TableHeaderCell>Name</TableHeaderCell>
              <TableHeaderCell>Action</TableHeaderCell>
            </TableRow>
          </template>
          <template #default>
            <TableRow
              v-for="(menu, index) in props.menus.data"
              :key="menu.id"
              class="border-b"
            >
              <TableDataCell>{{ getSerialNumber(index) }}</TableDataCell>
              <TableDataCell>
                <img :src="menu.image" :alt="menu.name" class="w-10 h-10 rounded-lg" />
              </TableDataCell>
              <TableDataCell>{{ menu.name }}</TableDataCell>
              <TableDataCell>
                <Link
                  :href="route('menus.edit', menu)"
                  class="text-green-400 hover:text-green-300 pr-2"
                >
                  Edit
                </Link>
                <button @click="deleteMenu" class="text-red-400 hover:text-red-300">
                  Delete
                </button>
                <Modal :show="showConfirmDeleteMenuModal" @close="closeModal">
                  <div class="p-6">
                    <h2 class="text-lg font-semibold text-slate-800">
                      Are you sure to delete this menu?
                    </h2>
                    <div class="mt-6 flex justify-start space-x-4">
                      <DangerButton @click="deleteMenu(menu)">Delete</DangerButton>
                      <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    </div>
                  </div>
                </Modal>
              </TableDataCell>
            </TableRow>
          </template>
        </Table>
        <Pagination :links="props.menus.meta.links" />
      </div>
    </div>
  </AdminLayout>
</template>
