<script setup>
import { reactive, ref, watch } from "vue";
import debounce from "lodash/debounce";
import Modal from "@/Components/Modal.vue";
import Table from "@/Components/Table.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import TableRow from "@/Components/TableRow.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { usePaginateSerialNumber } from "@/Composables/pagination.js";
import Pagination from "@/Components/Pagination.vue";
const props = defineProps({
  menu_items: {
    type: Object,
    default: {},
  },
  filters: {
    type: Object,
    default: {},
  },
});
const filtermenu = ref(props.filters.filtermenu);
const filtername = ref(props.filters.filtername);
watch(
  [filtermenu, filtername],
  debounce(function ([filtermenuValue, filternameValue]) {
    router.get(
      route("menu_items.index"),
      {
        filtermenu: filtermenuValue,
        filtername: filternameValue,
      },
      {
        preserveState: true,
        replace: true,
      }
    );
  })
);
const form = useForm({});
const showConfirmDeleteMenuItemModal = ref(false);

const deleteMenuItem = async (item) => {
  showConfirmDeleteMenuItemModal.value = true;
  form.delete(route("menu_items.delete", item), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      closeModal();
      toast("Successfully deleted menu item!", {
        autoClose: 1000,
      });
    },
  });
};

const closeModal = () => {
  showConfirmDeleteMenuItemModal.value = false;
};
const getSerialNumber = (index) => {
  let number = usePaginateSerialNumber({
    current_page: props.menu_items.meta.current_page,
    per_page: props.menu_items.meta.per_page,
    index,
  });
  return number;
};
</script>

<template>
  <Head title="Menu Items" />

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

          <div class="w-full max-w-md mx-auto">
            <div class="items-center p-2">
              <InputLabel for="item_name" value="Item Name" class="mr-2 text-gray-900" />
              <div class="flex-grow">
                <TextInput
                  id="item_name"
                  v-model="filtername"
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
        <Link
          :href="route('menu_items.create')"
          class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        >
          Add Menu Item
        </Link>
      </div>
      <div class="mt-5">
        <Table>
          <template #header>
            <TableRow>
              <TableHeaderCell>ID</TableHeaderCell>
              <TableHeaderCell>Image</TableHeaderCell>
              <TableHeaderCell>Menu</TableHeaderCell>
              <TableHeaderCell>Menu Item Name</TableHeaderCell>
              <TableHeaderCell>Price</TableHeaderCell>
              <TableHeaderCell>Action</TableHeaderCell>
            </TableRow>
          </template>
          <template #default>
            <TableRow
              v-for="(item, index) in props.menu_items.data"
              :key="item.id"
              class="border-b"
            >
              <TableDataCell>{{ getSerialNumber(index) }}</TableDataCell>
              <TableDataCell>
                <img :src="item.image" :alt="item.name" class="h-10 w-10 rounded-lg" />
              </TableDataCell>
              <TableDataCell>{{ item.menu_name }}</TableDataCell>
              <TableDataCell>{{ item.name }}</TableDataCell>
              <TableDataCell>{{ item.price }}</TableDataCell>
              <TableDataCell>
                <Link
                  :href="route('menu_items.edit', item)"
                  class="text-green-400 hover:text-green-300 pr-2"
                >
                  Edit
                </Link>
                <button @click="deleteMenuItem" class="text-red-400 hover:text-red-300">
                  Delete
                </button>
                <Modal :show="showConfirmDeleteMenuItemModal" @close="closeModal">
                  <div class="p-6">
                    <h2 class="text-lg font-semibold text-slate-800">
                      Are you sure to delete this menu item?
                    </h2>
                    <div class="mt-6 flex justify-start space-x-4">
                      <DangerButton @click="deleteMenuItem(item)">Delete</DangerButton>
                      <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    </div>
                  </div>
                </Modal>
              </TableDataCell>
            </TableRow>
          </template>
        </Table>
        <Pagination :links="props.menu_items.meta.links" />
      </div>
    </div>
  </AdminLayout>
</template>
