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

const form = useForm({});
const showConfirmDeletePromotionModal = ref(false);

const props = defineProps({
  promotions: {
    type: Object,
    default: {},
  },
  filters: {
    type: Object,
    default: {},
  },
});

const filterPromotion = ref(props.filters.filterPromotion);

watch(
  [filterPromotion],
  debounce(function ([filterPromotionValue]) {
    router.get(
      route("promotions.index"),
      {
        filterPromotion: filterPromotionValue,
      },
      {
        preserveState: true,
        replace: true,
      }
    );
  })
);
const deletePromotion = async (promotion) => {
  showConfirmDeletePromotionModal.value = true;
  form.delete(route("promotions.delete", promotion), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      closeModal();
      toast("Successfully deleted promotion!", {
        autoClose: 1000,
      });
    },
  });
};

const closeModal = () => {
  showConfirmDeletePromotionModal.value = false;
};
</script>

<template>
  <Head title="Promotions" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <!-- start filter -->
      <div class="flex flex-start max-w-7xl mx-auto py-4">
        <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0">
          <div class="w-full max-w-md mx-auto">
            <div class="items-center p-2">
              <InputLabel
                for="promotion"
                value="Promotion Name"
                class="mr-2 text-gray-400"
              />
              <div class="flex-grow">
                <TextInput
                  id="promotion"
                  v-model="filterPromotion"
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
        <h1>Promotions</h1>
        <Link
          :href="route('promotions.create')"
          class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        >
          Add Promotion
        </Link>
      </div>
      <div class="mt-5">
        <Table>
          <template #header>
            <TableRow>
              <TableHeaderCell>ID</TableHeaderCell>
              <TableHeaderCell>Name</TableHeaderCell>
              <TableHeaderCell>Percentage</TableHeaderCell>
              <TableHeaderCell>Start Date</TableHeaderCell>
              <TableHeaderCell>End Date</TableHeaderCell>
              <TableHeaderCell>Promotion Type</TableHeaderCell>
              <TableHeaderCell>Status</TableHeaderCell>
              <TableHeaderCell>Action</TableHeaderCell>
            </TableRow>
          </template>
          <template #default>
            <TableRow
              v-for="promotion in props.promotions.data"
              :key="promotion.id"
              class="border-b"
            >
              <TableDataCell>{{ promotion.id }}</TableDataCell>
              <TableDataCell>{{ promotion.name }}</TableDataCell>
              <TableDataCell>{{ promotion.discount_percentage }}</TableDataCell>
              <TableDataCell>{{ promotion.start_date }}</TableDataCell>
              <TableDataCell>{{ promotion.end_date }}</TableDataCell>
              <TableDataCell>{{ promotion.type }}</TableDataCell>
              <TableDataCell>{{ promotion.status }}</TableDataCell>
              <TableDataCell>
                <Link
                  :href="route('promotions.edit', promotion)"
                  class="text-green-400 hover:text-green-300 pr-2"
                >
                  Edit
                </Link>
                <button @click="deletePromotion" class="text-red-400 hover:text-red-300">
                  Delete
                </button>
                <Modal :show="showConfirmDeletePromotionModal" @close="closeModal">
                  <div class="p-6">
                    <h2 class="text-lg font-semibold text-slate-800">
                      Are you sure to delete this promotion?
                    </h2>
                    <div class="mt-6 flex justify-start space-x-4">
                      <DangerButton @click="deletePromotion(promotion)"
                        >Delete</DangerButton
                      >
                      <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    </div>
                  </div>
                </Modal>
              </TableDataCell>
            </TableRow>
          </template>
        </Table>
      </div>
    </div>
  </AdminLayout>
</template>
