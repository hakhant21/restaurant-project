<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
import { usePermission } from "@/Composables/permissions.js";
import OrderLogo from "@/Components/OrderLogo.vue";
import SaleIcon from "@/Components/SaleIcon.vue";
const { hasRole } = usePermission();
const props = defineProps({
  data: {
    type: Array,
    default: {},
  },
});
</script>

<template>
  <Head title="Dashboard" />

  <template v-if="hasRole('admin') || hasRole('vendor')">
    <AdminLayout>
      <div class="mt-12">
        <div class="mb-12 grid gap-y-10 gap-x-6 md:grid-cols-2 xl:grid-cols-4">
          <div
            class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md"
          >
            <div
              class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-blue-600 to-blue-400 text-white shadow-blue-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center"
            >
              <OrderLogo />
            </div>
            <div class="p-4 text-right">
              <p
                class="block antialiased font-sans text-sm leading-normal font-normal text-blue-gray-600"
              >
                Total Order : {{ props.data["total_order"] }}
              </p>
              <h4
                class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900"
              >
                {{ props.data["change"] }}/ {{ props.data["percentage"] }}
              </h4>
            </div>
            <div class="border-t border-blue-gray-50 p-4">
              <p
                class="block antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600"
              >
                <strong class="text-blue-300"
                  >Monthly Order:{{ props.data["monthly_total_order"] }}</strong
                >
              </p>
            </div>
            <div class="border-t border-blue-gray-50 p-4">
              <p
                class="block antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600"
              >
                <strong class="text-blue-300"
                  >Daily Order: {{ props.data["daily_order"] }}</strong
                >
              </p>
            </div>
          </div>

          <div
            class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md"
          >
            <div
              class="bg-clip-border mx-4 rounded-xl overflow-hidden bg-gradient-to-tr from-orange-600 to-orange-400 text-white shadow-orange-500/40 shadow-lg absolute -mt-4 grid h-16 w-16 place-items-center"
            >
              <SaleIcon />
            </div>
            <div class="p-4 text-right">
              <p
                class="block antialiased font-sans text-sm leading-normal font-normal text-blue-gray-600"
              >
                Sales
              </p>
              <h4
                class="block antialiased tracking-normal font-sans text-2xl font-semibold leading-snug text-blue-gray-900"
              >
                {{ props.data["total_amount"] }}
              </h4>
            </div>
            <div class="border-t border-blue-gray-50 p-4">
              <p
                class="block antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600"
              >
                <strong class="text-yellow-800"
                  >Monthly Sale : {{ props.data["monthlyTotalAmount"] }}</strong
                >
              </p>
            </div>
            <div class="border-t border-blue-gray-50 p-4">
              <p
                class="block antialiased font-sans text-base leading-relaxed font-normal text-blue-gray-600"
              >
                <strong class="text-yellow-800"
                  >Daily Sale: {{ props.data["daily_total_amount"] }} /Ks</strong
                >
              </p>
            </div>
          </div>
        </div>

        <div class="mb-4 grid grid-cols-1 gap-6">
          <div
            class="relative flex flex-col bg-clip-border rounded-xl bg-white text-gray-700 shadow-md overflow-hidden xl:col-span-2"
          >
            <h1 class="mt-4 py-2 px-4">Top 5 Items</h1>
            <div
              class="relative bg-clip-border rounded-xl overflow-hidden bg-transparent text-gray-700 shadow-none m-0 flex items-center justify-between p-6"
            ></div>

            <div class="p-6 px-0 pt-0 pb-2 py-12">
              <table class="w-full table-auto">
                <thead>
                  <tr>
                    <th class="border-blue-gray-50 py-3 px-6 text-left">
                      <p
                        class="block antialiased font-sans text-[11px] font-medium uppercase text-blue-gray-400"
                      >
                        Item Name
                      </p>
                    </th>
                    <th class="border-blue-gray-50 py-3 px-6 text-left">
                      <p
                        class="block antialiased font-sans text-[11px] font-medium uppercase text-blue-gray-400"
                      >
                        Price
                      </p>
                    </th>
                    <th class="border-blue-gray-50 py-3 px-6 text-left">
                      <p
                        class="block antialiased font-sans text-[11px] font-medium uppercase text-blue-gray-400"
                      >
                        Qty
                      </p>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in props.data['top_menu_items']" :key="item.id">
                    <td class="py-3 px-5 border-b border-blue-gray-50">
                      <div class="flex items-center gap-4">
                        <p
                          class="block antialiased font-sans text-sm leading-normal text-blue-gray-900 font-bold"
                        >
                          {{ item.name }}
                        </p>
                      </div>
                    </td>

                    <td class="py-3 px-5 border-b border-blue-gray-50">
                      <p
                        class="block antialiased font-sans text-xs font-medium text-blue-gray-600"
                      >
                        {{ item.price }}
                      </p>
                    </td>
                    <td class="py-3 px-5 border-b border-blue-gray-50">
                      <p
                        class="block antialiased font-sans text-xs font-medium text-blue-gray-600"
                      >
                        {{ item.total_quantity }}
                      </p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  </template>
</template>
