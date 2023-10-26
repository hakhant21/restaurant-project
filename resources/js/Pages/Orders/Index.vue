<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { reactive, ref, watch } from "vue";
import debounce from "lodash/debounce";
import Modal from "@/Components/Modal.vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Pagination from "@/Components/Pagination.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { usePaginateSerialNumber } from "@/Composables/pagination.js";

const props = defineProps({
  orders: {
    type: Object,
    default: {},
  },
  total_amount: {
    type: Object,
    default: {}
  },
  filters: {
    type: Object,
    default: {},
  },
});

const showOrderDetailModal = ref(false);
const selectedOrder = ref({});
const orderDate = ref(props.filters.orderDate);
const orderNumber = ref(props.filters.orderNumber);
const paymentType = ref(props.filters.paymentType);

const showOrder = async (order) => {
  showOrderDetailModal.value = true;
  selectedOrder.value = order;
};

const closeModal = () => {
  showOrderDetailModal.value = false;
};
watch(
  [orderDate, orderNumber, paymentType],
  debounce(function ([orderDateValue, orderNumberValue,paymentTypeValue]) {
    router.get(
      route("orders.index"),
      {
        orderNumber: orderNumberValue,
        orderDate: orderDateValue,
        paymentType: paymentTypeValue
      },
      {
        preserveState: true,
        replace: true,
      }
    );
  })
);
const getSerialNumber = (index) => {
  let number = usePaginateSerialNumber({
    current_page: props.orders.meta.current_page,
    per_page: props.orders.meta.per_page,
    index,
  });
  return number;
};
</script>

<template>
  <Head title="Order Lists" />

  <AdminLayout>
    <div class="max-w-7xl min-w-full mx-auto py-4">
      <!-- start filter -->
      <div class="flex flex-start max-w-7xl mx-auto py-4">
        <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0">
          <div class="w-full max-w-md mx-auto">
            <div class="items-center p-2">
              <InputLabel for="order" value="Order Number" class="mr-2 text-gray-400" />
              <div class="flex-grow">
                <TextInput
                  id="menu"
                  v-model="orderNumber"
                  type="text"
                  class="mt-1 block w-full bg-gray-50 outline-none py-2"
                />
              </div>
            </div>
          </div>
          <div class="z-10 w-full max-w-md mx-auto">
            <div class="items-center p-2">
              <InputLabel
                for="paymentType"
                value="Payment Type"
                class="mr-2 text-gray-400"
              />
              <select name="paymentType" id="paymentType" v-model="paymentType" class="mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Choose Payment Type</option>
                    <option value="kbzpay">KBZ PAY</option>
                    <option value="cash">CASH</option>
              </select>
            </div>
          </div>
          <div class="z-10 w-full max-w-md mx-auto">
            <div class="items-center p-2">
              <InputLabel
                for="order_date"
                value="Order Date"
                class="mr-2 text-gray-400"
              />
              <VueDatePicker
                class="mt-1 w-full bg-gray-50 outline-none"
                v-model="orderDate"
                range
                :enable-time-picker="false"
              />
            </div>
          </div>

        </div>
      </div>

      <!-- End Filter -->
      <div class="flex justify-between mb-4">
        <h1 class="font-semibold text-lg">Order Lists</h1>
        <h2 class="font-semibold text-lg">Sale Amount : {{ props.total_amount }}</h2>
      </div>
      <div>
        <Table>
          <template #header>
            <TableRow>
              <TableHeaderCell>ID</TableHeaderCell>
              <TableHeaderCell>Invoice No</TableHeaderCell>
              <TableHeaderCell>Order Date</TableHeaderCell>
              <TableHeaderCell>Payment Type</TableHeaderCell>
              <TableHeaderCell>Service Charges</TableHeaderCell>
              <TableHeaderCell>Discount</TableHeaderCell>
              <TableHeaderCell>Promotion</TableHeaderCell>
              <TableHeaderCell>Tax</TableHeaderCell>
              <TableHeaderCell>Grand Total</TableHeaderCell>
              <TableHeaderCell>Total Amount</TableHeaderCell>
              <TableHeaderCell>Action</TableHeaderCell>
            </TableRow>
          </template>
          <template #default>
            <TableRow
              v-for="(order, index) in props.orders.data"
              :key="order.id"
              class="border-b"
            >
              <TableDataCell>{{ getSerialNumber(index) }}</TableDataCell>
              <TableDataCell>{{ order.order_number }}</TableDataCell>
              <TableDataCell>{{ order.order_date }}</TableDataCell>
              <TableDataCell>{{ order.payment_type }}</TableDataCell>
              <TableDataCell>{{ order.service_charges }}</TableDataCell>
              <TableDataCell>{{ order.discount }}</TableDataCell>
              <TableDataCell>{{ order.promotion }}</TableDataCell>
              <TableDataCell>{{ order.tax }}</TableDataCell>
              <TableDataCell>{{ order.grand_total }}</TableDataCell>
              <TableDataCell>{{ order.total_amount }}</TableDataCell>
              <TableDataCell>
                <Link :href="route('orders.show', order)" class="py-2 px-4 rounded-lg bg-slate-800 text-white hover:bg-slate-700 hover:text-slate-500">View</Link>
              </TableDataCell>
            </TableRow>
          </template>
        </Table>
        <Pagination :links="props.orders.meta.links" />
        <div class="mt-80"></div>
      </div>
    </div>
  </AdminLayout>
</template>
