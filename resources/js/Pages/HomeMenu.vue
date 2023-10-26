<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { reactive, ref, watch } from "vue";
import debounce from "lodash/debounce";
import { toast } from "vue3-toastify";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { usePermission } from "@/Composables/permissions.js";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";

import SecondaryButton from "@/Components/SecondaryButton.vue";
const { hasRole } = usePermission();
const props = defineProps({
  menu_items: {
    type: Object,
    default: {},
  },
  menus: {
    type: Object,
    default: {},
  },
  order_number: {
    type: String,
    default: {},
  },
  cart_items: {
    type: Object,
    default: {},
  },
  total_amount: {
    type: Array,
    default: {},
  },
  promotion_amount: {
    type: Array,
    default: {},
  },
  total_discount_amount: {
    type: Array,
    default: {},
  },

  filters: {
    type: Object,
    default: {},
  },
});

const paidRef = ref(parseInt(0));
const filteritem = ref(props.filters.filteritem);
const filtername = ref(props.filters.filtername);
const filtermenu = ref(props.filters.filtermenu ?? "All");
let includeTax = ref(true);
const serviceChargeRef = ref(parseInt(0));
const taxAmountRef = ref(parseInt(5));
const includeServiceCharge = ref(false);
const showOrderCartModal = ref(false);
const cartPaymentForm = useForm({
  grand_total_amount: "",
  paid_by: "",
  changed: "",
  total_amount: "",
  includeTax: "",
  taxAmount: "",
  includeServiceCharge: "",
  serviceChargeRef: "",
  promotion_amount: "",
  payment_type: false,
  total_discount_amount: "",
});
const form = useForm({});
const clearCart = async () => {
  paidRef.value = parseInt(0);
  await router.post(route("clear_carts"), {
    preserveScroll: true,
    preserveState: false,
    onSuccess: () => {
      toast("Successfully remove!", {
        autoClose: 1000,
      });
    },
  });
};

const removeFromCart = (item) => {
  form.delete(route("item_remove_from_cart", item), {
    preserveState: true,
    replace: true,
    onSuccess: () => {
      toast("Successfully remove!", {
        autoClose: 1000,
      });
    },
  });
};

const addToCart = async (item) => {
  await form.get(route("item_add_to_cart", item), {
    preserveScroll: true,
    preserveState: true,
    replace: true,
    onSuccess: () => {
      toast("Successfully Add!", {
        autoClose: 1000,
      });
    },
  });
};
const orderConfirm = async (order_number) => {
  cartPaymentForm.includeTax = includeTax;
  cartPaymentForm.includeServiceCharge = includeServiceCharge;
  cartPaymentForm.serviceChargeRef = calculateServiceCharge();
  cartPaymentForm.total_amount = calculateSubTotal();
  cartPaymentForm.grand_total_amount = props.total_amount;
  cartPaymentForm.total_discount_amount = props.total_discount_amount;
  cartPaymentForm.promotion_amount = props.promotion_amount;
  cartPaymentForm.paid_by = calculatePaid();
  cartPaymentForm.changed = calculateChangeTotal(calculatePaid());
  cartPaymentForm.promotion_amount = props.promotion_amount;
  cartPaymentForm.taxAmount = includeTax.value == true ? calculateTax() : 0;
  await cartPaymentForm.post(route("cart_order_confirm", order_number), {
    preserveScroll: true,
    preserveState: true,
    replace: true,
    onSuccess: () => {
      showOrderCartModal.value = false;
      toast("Successfully Paid!", {
        autoClose: 1000,
      });
      paidRef.value = parseInt(0);
    },
  });
};

watch(
  [filtermenu, filteritem, filtername],
  debounce(function ([filtermenuValue, filteritemValue, filternameValue]) {
    router.get(
      route("home_menu"),
      {
        filtermenu: filtermenuValue,
        filtername: filternameValue,
        filteritem: filteritemValue,
      },
      {
        preserveState: true,
        replace: true,
      }
    );
  })
);
const incrementQuantity = (cart) => {
  form.get(route("item_quantity_increment", cart), {
    preserveState: true,
    replace: true,
  });
};
let calculatePaid = () => {
  let paid = 0;
  paid = parseInt(paidRef.value);
  return paid;
};
const decrementQuantity = (cart) => {
  form.get(route("item_quantity_decrement", cart), {
    preserveState: true,
  });
};

const priceFormat = (price) => {
  // Implement the priceFormat logic here
  return parseInt(price);
};

let calculateChangeTotal = (paid_amount) => {
  let $total = parseInt(paid_amount) - calculateSubTotal();
  if ($total >= 0 && parseInt(paid_amount) >= calculateSubTotal()) {
    return parseInt($total);
  } else {
    return "-";
  }
};

const showOrderCart = () => {
  showOrderCartModal.value = true;
};
const closeOrderCartModal = () => {
  showOrderCartModal.value = false;
};

const changeStatus = (status) => {
  filtermenu.value = status;
};
let calculateTax = () => {
  const tax = props.total_amount * (parseInt(taxAmountRef.value) / 100);
  if (tax && includeTax.value == true) {
    return parseInt(tax);
  } else {
    return 0;
  }
};
let calculateSubTotal = () => {
  let total_sub_amount = 0;
  total_sub_amount =
    parseInt(props.total_amount) +
    calculateTax() +
    calculateServiceCharge() -
    parseInt(props.promotion_amount) -
    props.total_discount_amount;
  // calculateTax +
  // parseInt(props.promotion_amount) -
  // parse(props.total_discount_amount);
  return total_sub_amount;
};
let calculateServiceCharge = () => {
  if (includeServiceCharge.value == true) {
    if (serviceChargeRef.value != "") {
      return parseInt(serviceChargeRef.value);
    } else {
      return 0;
    }
  }
  return 0;
};
</script>

<template>
  <Head title="Home Store" />

  <template v-if="hasRole('admin') || hasRole('vendor')">
    <AdminLayout>
      <div class="container mx-auto px-5 bg-white">
        <div class="flex lg:flex-row flex-col-reverse shadow-lg">
          <!-- left section -->
          <div class="w-full lg:w-3/5 min-h-screen shadow-lg">
            <!-- header -->
            <div class="flex flex-row justify-between items-center px-5 mt-5">
              <div class="text-gray-800">
                <div class="font-bold text-xl text-orange-300">EM-9 FOOD & HOUSE</div>
              </div>
              <div class="flex items-center">
                <div class="text-sm text-center mr-4">
                  <div class="relative">
                    <input
                      type="text"
                      placeholder="Search By Item Name"
                      class="py-2 px-3 pr-10 border rounded-lg focus:outline-none"
                      v-model="filtername"
                    />
                  </div>
                </div>
              </div>
            </div>
            <!-- end header -->
            <!-- categories -->

            <div class="mt-5 flex flex-row px-5 overflow-x-auto">
              <button
                type="button"
                @click="changeStatus('All')"
                :class="filtermenu == 'All' ? 'bg-yellow-600 text-white' : 'bg-white'"
                class="px-5 py-1 rounded-2xl text-sm font-semibold mr-4"
              >
                <span class="px-5 py-1 rounded-2xltext-sm mr-4"> All </span>
              </button>
              <div v-for="menu in props.menus" :key="menu.id">
                <button
                  type="button"
                  @click="changeStatus(menu.slug)"
                  :class="filtermenu == menu.slug ? 'bg-blue-600 text-white' : 'bg-white'"
                  class="uppercase px-5 py-1 rounded-2xl text-sm font-semibold mr-4"
                >
                  <span>
                    {{ menu.name }}
                  </span>
                </button>
              </div>
            </div>
            <!-- end categories -->
            <!-- products -->
            <div class="grid grid-cols-3 gap-4 px-5 mt-5 overflow-y-auto h-3/4">
              <div
                class="px-3 py-3 flex flex-col border border-gray-200 rounded-md h-32 justify-between"
                v-for="item in props.menu_items"
                :key="item.id"
              >
                <button @click="addToCart(item)">
                  <div>
                    <div class="font-bold text-gray-800 text-left">
                      {{ item.name }}
                    </div>
                    <!-- <span class="font-light text-sm text-gray-400">{{ item.price }}</span> -->
                  </div>
                  <div class="flex flex-row justify-between items-center">
                    <span class="self-end font-bold text-lg text-yellow-500">{{
                      item.price
                    }}</span>
                    <img
                      :src="item.image"
                      class="h-14 w-14 object-cover rounded-md"
                      :alt="item.name"
                    />
                  </div>
                </button>
              </div>
            </div>
            <!-- end products -->
          </div>
          <!-- end left section -->
          <!-- right section -->
          <div class="w-full lg:w-2/5">
            <!-- header -->
            <div class="flex flex-row items-center justify-between px-5 mt-5">
              <div class="font-bold text-xl">Current Order</div>
              <div class="font-semibold">
                <!-- <span class="px-4 py-2 rounded-md bg-red-100 text-red-500"
                  >Clear All</span
                >
                <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-800"
                  >Setting</span
                > -->
              </div>
            </div>
            <!-- end header -->
            <!-- order list -->
            <div class="px-5 py-4 mt-5 overflow-y-auto h-64">
              <div
                class="flex flex-row justify-between items-center mb-4"
                v-for="cart in props.cart_items"
                :key="cart.id"
              >
                <div class="flex flex-row items-center w-2/5">
                  <!-- <img
                    :src="cart.image"
                    class="w-10 h-10 object-cover rounded-md"
                    alt=""
                  /> -->
                  <span class="ml-4 font-semibold text-sm">{{ cart.item_name }}</span>
                </div>
                <div class="w-32 flex justify-between">
                  <button type="button" @click="decrementQuantity(cart)">
                    <span class="px-3 py-1 rounded-md bg-gray-300">-</span>
                  </button>
                  <span class="font-semibold mx-4">{{ cart.quantity }}</span>
                  <button type="button" @click="incrementQuantity(cart)">
                    <span class="px-3 py-1 rounded-md bg-gray-300">+</span>
                  </button>
                </div>
                <div class="font-semibold text-lg w-16 text-center">
                  {{ cart.total_amount }}
                </div>
              </div>
            </div>
            <!-- end order list -->
            <!-- totalItems -->
            <div class="px-5 mt-5">
              <div class="py-4 rounded-md shadow-lg">
                <div class="px-4 flex justify-between">
                  <span class="font-semibold text-sm">Grand Total</span>
                  <span class="font-bold">{{ priceFormat(props.total_amount) }}</span>
                </div>
                <div class="px-4 flex justify-between">
                  <span class="font-semibold text-sm">Discount(-)</span>
                  <span class="font-bold">{{ props.total_discount_amount }}</span>
                </div>
                <div class="px-4 flex justify-between">
                  <span class="font-semibold text-sm">Promotion(-)</span>
                  <span class="font-bold">{{ props.promotion_amount }}</span>
                </div>
                <div v-if="includeTax == true" class="px-4 flex justify-between">
                  <span class="font-semibold text-sm">Sales Tax(+)</span>
                  <span class="font-bold">{{ calculateTax() }}</span>
                </div>
                <div
                  v-if="includeServiceCharge == true"
                  class="px-4 flex justify-between"
                >
                  <span class="font-semibold text-sm">Service Charge(+)</span>
                  <span class="font-bold">{{ calculateServiceCharge() }}</span>
                </div>
                <div class="px-4 flex justify-between">
                  <span class="font-semibold text-sm">Paid</span>
                  <span class="font-bold">
                    <TextInput
                      id="name"
                      v-model="paidRef"
                      type="number"
                      min="0"
                      inputmode="numeric"
                      class="block w-25 text-right"
                  /></span>
                </div>
                <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                  <span class="font-semibold text-2xl">Tax</span>
                  <input type="checkbox" v-model="includeTax" />
                  <TextInput
                    v-if="includeTax == true"
                    id="name"
                    type="number"
                    v-model="taxAmountRef"
                    inputmode="numeric"
                    class="block w-25 text-right"
                  />
                </div>
                <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                  <span class="font-semibold text-2xl">Service Charge</span>

                  <input type="checkbox" v-model="includeServiceCharge" />
                  <TextInput
                    v-if="includeServiceCharge == true"
                    id="name"
                    type="number"
                    v-model="serviceChargeRef"
                    inputmode="numeric"
                    class="block w-25 text-right"
                  />
                </div>
                <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                  <span class="font-semibold text-2xl">Total</span>
                  <span class="font-bold text-2xl">{{ calculateSubTotal() }}/Ks</span>
                  <span class="font-semibold text-2xl">Changed</span>
                  <span class="font-bold text-2xl"
                    >{{ calculateChangeTotal(paidRef) }}/Ks</span
                  >
                </div>
              </div>
            </div>
            <!-- end total -->
            <!-- cash -->
            <div class="px-5 mt-5">
              <div class="rounded-md shadow-lg px-4 py-4">
                <div class="flex flex-row justify-between items-center">
                  <div class="flex flex-col">
                    <!-- <span class="uppercase text-xs font-semibold">cashless credit</span>
                    <span class="text-xl font-bold text-yellow-500">$32.50</span>
                    <span class="text-xs text-gray-400">Available</span> -->
                  </div>
                  <div class="px-4 py-3 bg-gray-300 text-gray-800 rounded-md font-bold">
                    <button type="button" @click="clearCart">Cancel</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- end cash -->
            <!-- button pay-->
            <div class="px-5 mt-5">
              <div
                v-if="
                  calculateChangeTotal(paidRef) == 0 || calculateChangeTotal(paidRef) > 0
                "
              >
                <div
                  v-if="priceFormat(props.total_amount) > 0"
                  class="px-4 py-4 rounded-md shadow-lg text-center bg-yellow-500 text-white font-semibold my-6"
                >
                  <button type="button" @click="showOrderCart">Pay</button>
                </div>
              </div>
            </div>
            <!-- end button pay -->
          </div>
          <!-- end right section -->
        </div>
      </div>

      <!-- View Order -->
      <Modal
        class="content-center"
        :show="showOrderCartModal"
        @close="closeOrderCartModal"
      >
        <div class="px-5 py-4 mt-5 overflow-y-auto h-64">
          <div
            class="flex flex-row justify-between items-center mb-4"
            v-for="cart in props.cart_items"
            :key="cart.id"
          >
            <div class="flex flex-row items-center w-2/5">
              <!-- <img
                src="https://source.unsplash.com/4u_nRgiLW3M/600x600"
                class="w-10 h-10 object-cover rounded-md"
                alt=""
              /> -->
              <span class="ml-4 font-semibold text-sm">{{ cart.item_name }}</span>
            </div>
            <div class="w-32 flex justify-between">
              <button type="button" @click="decrementQuantity(cart)">
                <span class="px-3 py-1 rounded-md bg-gray-300">-</span>
              </button>
              <span class="font-semibold mx-4">{{ cart.quantity }}</span>
              <button type="button" @click="incrementQuantity(cart)">
                <span class="px-3 py-1 rounded-md bg-gray-300">+</span>
              </button>
            </div>
            <div class="font-semibold text-lg w-16 text-center">
              {{ cart.total_amount }}
            </div>
          </div>
        </div>
        <!-- end order list -->
        <!-- totalItems -->
        <div class="px-5 mt-5">
          <div class="py-4 rounded-md shadow-lg">
            <div class="px-4 flex justify-between">
              <span class="font-semibold text-sm">Subtotal</span>
              <span class="font-bold">{{ priceFormat(props.total_amount) }}</span>
            </div>
            <div class="px-4 flex justify-between">
              <span class="font-semibold text-sm">Discount</span>
              <span class="font-bold">{{ props.total_discount_amount }}</span>
            </div>
            <div class="px-4 flex justify-between">
              <span class="font-semibold text-sm">Sales Tax</span>
              <span class="font-bold">{{ calculateTax() }}</span>
            </div>
            <div class="px-4 flex justify-between">
              <span class="font-semibold text-sm">Paid</span>
              <span class="font-bold">
                <TextInput
                  id="name"
                  v-model="paidRef"
                  type="number"
                  inputmode="numeric"
                  class="block w-25 text-right"
              /></span>
            </div>
            <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-start">
              <span class="font-semibold text-xl">Payment Type</span>
              <input
                type="checkbox"
                v-model="cartPaymentForm.payment_type"
                class="mx-2"
              />
              KBZPay
            </div>

            <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
              <span class="font-semibold text-2xl">Total</span>
              <span class="font-bold text-2xl"
                >{{ priceFormat(props.total_amount) }}/Ks</span
              >
            </div>
            <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
              <span class="font-semibold text-2xl">Changed</span>
              <span class="font-bold text-2xl"
                >{{ calculateChangeTotal(paidRef) }}/Ks</span
              >
            </div>
          </div>
        </div>

        <div class="px-5 mt-5">
          <div
            v-if="calculateChangeTotal(paidRef) == 0 || calculateChangeTotal(paidRef) > 0"
          >
            <div
              class="px-4 mb-5 py-4 rounded-md shadow-lg text-center bg-green-500 text-white font-semibold"
            >
              <button type="button" @click="orderConfirm(props.order_number)">
                Confirm
              </button>
            </div>
          </div>
        </div>
      </Modal>
    </AdminLayout>
  </template>
</template>
