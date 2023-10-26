<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Pagination from "@/Components/Pagination.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import "@vuepic/vue-datepicker/dist/main.css";
import { usePaginateSerialNumber } from "@/Composables/pagination.js";

const props = defineProps({
  details: {
    type: Object,
    default: {},
  },
});

const getSerialNumber = (index) => {
  let number = usePaginateSerialNumber({
    current_page: props.details.meta.current_page,
    per_page: props.details.meta.per_page,
    index,
  });
  return number;
};
</script>

<template>
  <Head title="Order Lists" />

  <AdminLayout>
    <div class="max-w-7xl min-w-full mx-auto py-4">
      <div class="flex justify-between mb-4">
        <h1>Order Lists</h1>
        <Link :href="route('orders.index')" class="py-1 px-4 rounded-lg bg-slate-800 text-white hover:bg-slate-700 hover:text-slate-500">Go Back</Link>
      </div>
      <div>
        <Table>
          <template #header>
            <TableRow>
              <TableHeaderCell>ID</TableHeaderCell>
              <TableHeaderCell>Order Number</TableHeaderCell>
              <TableHeaderCell>Menu Item Name</TableHeaderCell>
              <TableHeaderCell>Quantity</TableHeaderCell>
              <TableHeaderCell>Unit Price</TableHeaderCell>
              <TableHeaderCell>Total Amount</TableHeaderCell>
            </TableRow>
          </template>
          <template #default>
            <TableRow
              v-for="(detail, index) in props.details.data"
              :key="detail.id"
              class="border-b"
            >
              <TableDataCell>{{ getSerialNumber(index) }}</TableDataCell>
              <TableDataCell>{{ detail.order_number }}</TableDataCell>
              <TableDataCell>{{ detail.menu_item_name }}</TableDataCell>
              <TableDataCell>{{ detail.quantity }}</TableDataCell>
              <TableDataCell>{{ detail.unit_price }}</TableDataCell>
              <TableDataCell>{{ detail.total_price }}</TableDataCell>
            </TableRow>
          </template>
        </Table>
        <Pagination :links="props.details.meta.links" />
        <div class="mt-80"></div>
      </div>
    </div>
  </AdminLayout>
</template>
