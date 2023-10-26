<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import SidebarLink from "@/Components/SidebarLink.vue";
import { usePermission } from "@/Composables/permissions.js";
import { mdiAccount } from "@mdi/js";
import MenuStoreLogo from "@/Components/MenuStoreLogo.vue";
import PromotionIcon from "@/Components/PromotionIcon.vue";

const { hasRole } = usePermission();

const isOpen = ref(false);

const toggleSidebar = () => {
  isOpen.value = !isOpen.value;
};
</script>

<template>
  <div class="overflow-hidden">
    <!-- component -->
    <aside
      class="ml-[0%] fixed z-10 top-0 pb-3 w-full flex flex-col justify-between h-screen border-r bg-white transition duration-300 md:w-4/12 lg:ml-0 lg:w-[25%] xl:w-[20%] 2xl:w-[15%]"
      :class="{ 'ml-[100%]': !isOpen, 'ml-100': isOpen }"
    >
      <div>
        <div class="mx-auto items-center mt-5">
          <h1 class="text-3xl text-center font-bold text-slate-600 uppercase">
            {{ $page.props.auth.user.name }}
          </h1>
        </div>

        <ul class="space-y-2 tracking-wide mt-8">
          <li>
            <SidebarLink :href="route('dashboard')" :active="false">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-9 h-9"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                />
              </svg>

              <span class="-mr-1 font-medium">Dashboard</span>
            </SidebarLink>
            <li>
              <SidebarLink
                :href="route('home_menu')"
                :active="route().current('home_menu')"
              >
                <MenuStoreLogo />
                <span>Menu Store</span>
              </SidebarLink>
              <li>
              <SidebarLink
                :href="route('orders.index')"
                :active="route().current('orders.index')"
              >
                <svg
                  fill="#c3ee70"
                  stroke-width="1.5"
                  stroke="#c3e170"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 256 241"
                  class="w-9 h-9"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="20" />
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M254,188V2H2v186h111v29H75v22h106v-22h-38v-29H254z M19,19h217v151H19L19,19z M75.397,68.237l8.796,68.658h29.376 l8.71-68.658H86.327l-7.173-24.85l-3.928,1.11l6.832,23.654L75.397,68.237z M126.463,108.287c0,0-1.11-9.991,14.176-9.991h24.338 c15.2,0,14.176,9.991,14.176,9.991H126.463z M179.066,126.903c0,0,1.025,9.991-14.176,9.991h-24.252 c-15.286,0-14.176-9.991-14.176-9.991H179.066z M128.769,122.036c-2.476,0-4.526-1.964-4.526-4.526c0-2.476,1.964-4.526,4.526-4.526 h48.334c2.476,0,4.526,1.964,4.526,4.526c0,2.476-2.049,4.526-4.526,4.526H128.769z M182.653,150.387H73.347v-8.283h109.306V150.387 z"
                    />
                  </g>
                </svg>

                <span class="-mr-1 font-medium">Order</span>
              </SidebarLink>
            </li>
            </li>
          </li>
          <template v-if="hasRole('admin')">
            <li>
              <SidebarLink
                :href="route('menus.index')"
                :active="route().current('menus.index')"
              >
                <svg
                  class="w-9 h-9"
                  version="1.1"
                  id="_x32_"
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                  viewBox="-51.2 -51.2 614.40 614.40"
                  xml:space="preserve"
                  fill="#918712"
                  stroke="#918712"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></g>
                  <g id="SVGRepo_iconCarrier">
                    <g>
                      <path
                        d="M418.089,47.697l-15.133-24.248c3.545-2.263,5.913-6.214,5.913-10.726C408.869,5.696,403.18,0,396.147,0 H112.098C98.843,0,86.765,5.402,78.092,14.089c-8.688,8.652-14.306,20.759-14.306,33.993v422.023 c0,23.134,18.762,41.895,41.896,41.895h307.237c19.49,0,35.296-15.792,35.296-35.275V82.467 C448.215,64.742,435.099,50.24,418.089,47.697z M151.738,180.34l27.996,33.145c2.2,2.614,5.886,3.118,8.225,1.136l1.149-0.96 c2.34-1.983,2.48-5.703,0.266-8.316l-28.15-33.334c-5.676-6.718-3.293-12.169,0.616-15.469c3.909-3.3,9.682-4.736,15.357,1.983 l28.15,33.334c2.2,2.613,5.899,3.11,8.239,1.128l1.135-0.96c2.34-1.976,2.48-5.696,0.266-8.309l-27.996-33.152 c-10.159-12.029,5.66-24.682,15.581-12.926c11.896,14.082,26.441,31.309,26.441,31.309c18.341,21.565,18.412,31.12,16.408,44.355 c-1.611,10.663-4.288,18.243,7.09,31.722l3.615,4.274l-15.959,18.895l-6.292-7.433c-11.377-13.48-19.294-12.114-30.083-12.303 c-13.382-0.238-22.784-1.934-40.971-23.616c0,0-14.544-17.228-26.44-31.31C126.461,181.783,141.579,168.304,151.738,180.34z M204.829,448.226h-9.038v-27.33l-8.968,18.972h-6.46l-9.234-19.077v27.436h-9.024V400.06h8.659l12.821,27.309l12.568-27.309h8.674 V448.226z M250.115,408.376h-22.489v11.448h19.183v8.253h-19.183v11.833h22.489v8.316h-31.513V400.06h31.513V408.376z M298.315,448.226h-7.972l-19.757-30.084v30.084h-9.024V400.06h7.973l19.756,30.02v-30.02h9.024V448.226z M269.199,302.586 l15.96-18.902l47.01,55.676l-18.622,15.743L269.199,302.586z M346.601,431.656c0,2.508-0.462,4.834-1.345,6.914 c-0.897,2.088-2.158,3.896-3.741,5.367c-1.584,1.464-3.475,2.62-5.619,3.447c-2.13,0.812-4.456,1.226-6.908,1.226 c-2.438,0-4.764-0.414-6.908-1.226c-2.144-0.827-4.021-1.99-5.576-3.454c-1.57-1.472-2.816-3.272-3.699-5.36 c-0.897-2.08-1.345-4.406-1.345-6.914V400.06h9.024v31.274c0,2.823,0.77,5.051,2.298,6.613c1.541,1.583,3.572,2.347,6.207,2.347 s4.68-0.77,6.249-2.354c1.57-1.584,2.326-3.748,2.326-6.607V400.06h9.038V431.656z M367.086,181.405 c-6.418,15.644-17.95,44.347-30.841,59.627c-29.985,37.608-30.392,3.538-55.108,31.94l-69.359,82.131l-18.537-15.665l-0.309,0.182 c0,0,0.084-0.106,0.21-0.253c2.928-3.461,44.179-52.306,79.672-94.348c35.114-41.58,64.426-76.302,70.605-83.609 C356.087,146.396,377.105,157.052,367.086,181.405z M90.198,48.65v-0.568c0-5.57,2.382-11.14,6.529-15.28 c4.134-4.147,9.57-6.396,15.371-6.396h261.573l13.886,22.244H90.198z"
                      ></path>
                    </g>
                  </g>
                </svg>
                <span class="-mr-1 font-medium">Menus</span>
              </SidebarLink>
            </li>
            <li>
              <SidebarLink
                :href="route('menu_items.index')"
                :active="route().current('menu_items.index')"
              >
                <svg
                  fill="#c3ee70"
                  stroke-width="1.5"
                  stroke="#c3e170"
                  class="w-9 h-9"
                  viewBox="0 0 1024 1024"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g id="SVGRepo_bgCarrier" stroke-width="0" />
                  <g
                    id="SVGRepo_tracerCarrier"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <g id="SVGRepo_iconCarrier">
                    <path
                      d="M244.976 431.099H194.8c-28.278 0-51.2-22.922-51.2-51.2v-139.09c0-28.278 22.922-51.2 51.2-51.2h248.095c28.278 0 51.2 22.922 51.2 51.2v139.09c0 28.278-22.922 51.2-51.2 51.2h-44.503v541.88c0 28.278-22.922 51.2-51.2 51.2h-51.016c-28.278 0-51.2-22.922-51.2-51.2v-541.88zm197.918-200.53H194.799a10.238 10.238 0 00-10.24 10.24v139.09c0 5.657 4.583 10.24 10.24 10.24h70.656c11.311 0 20.48 9.169 20.48 20.48v562.36c0 5.657 4.583 10.24 10.24 10.24h51.016c5.657 0 10.24-4.583 10.24-10.24v-562.36c0-11.311 9.169-20.48 20.48-20.48h64.983c5.657 0 10.24-4.583 10.24-10.24v-139.09c0-5.657-4.583-10.24-10.24-10.24z"
                    />
                    <path
                      d="M143.36 21.503v244.736c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48V21.503c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48zm103.578 0v179.886c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48V21.503c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48zm103.424 0v179.886c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48V21.503c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48zm102.604 0v234.496c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48V21.503c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48zm210.35 508.989c-28.278 0-51.2-22.922-51.2-51.2V215.039c0-118.762 96.274-215.04 215.04-215.04 28.278 0 51.2 22.922 51.2 51.2v213.504h-20.48v-20.48l.492 40.96-.512-20.48h20.48v708.803c0 28.278-22.922 51.2-51.2 51.2h-72.448c-28.278 0-51.2-22.922-51.2-51.2V530.493h-40.172zm174.08-479.293c0-5.657-4.583-10.24-10.24-10.24-96.144 0-174.08 77.939-174.08 174.08v264.253c0 5.657 4.583 10.24 10.24 10.24h60.652c11.311 0 20.48 9.169 20.48 20.48v463.493c0 5.657 4.583 10.24 10.24 10.24h72.448c5.657 0 10.24-4.583 10.24-10.24V264.702l.02-213.504zm20.46 193.024h.512c27.307 0 27.307 40.96 0 40.96h-.492c-11.311 0-20.48-9.169-20.48-20.48v-.924c.483-10.882 9.458-19.556 20.46-19.556z"
                    />
                  </g>
                </svg>

                <span class="-mr-1 font-medium">Menu Items</span>
              </SidebarLink>
            </li>

            <li>
              <SidebarLink
                :href="route('promotions.index')"
                :active="route().current('promotions.index')"
              >
                <PromotionIcon />

                <span class="-mr-1 font-medium">Promotions</span>
              </SidebarLink>
            </li>

            <li>
              <SidebarLink
                :href="route('users.index')"
                :active="route().current('users.index')"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-9 h-9"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                  />
                </svg>

                <span class="-mr-1 font-medium">Users</span>
              </SidebarLink>
            </li>
            <li>
              <SidebarLink
                :href="route('roles.index')"
                :active="route().current('roles.index')"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-9 h-9"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>

                <span class="-mr-1 font-medium">Roles</span>
              </SidebarLink>
            </li>
            <li>
              <SidebarLink
                :href="route('permissions.index')"
                :active="route().current('permissions.index')"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-9 h-9"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33"
                  />
                </svg>

                <span class="-mr-1 font-medium">Permissions</span>
              </SidebarLink>
            </li>
          </template>
        </ul>
      </div>

      <div>
        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group cursor-pointer"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-9 h-9"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
            />
          </svg>
          <span class="group-hover:text-gray-700">Logout</span>
        </Link>
      </div>
    </aside>
    <div class="ml-auto mb-6 lg:w-[85%] xl:w-[75%] 2xl:w-[85%]">
      <div class="sticky top-0 h-16 border-b bg-white lg:py-2.5">
        <div class="flex items-center justify-between 2xl:container">
          <button @click="toggleSidebar" class="w-12 h-16 border-r lg:hidden">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-9 h-9"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
          </button>
        </div>
      </div>

      <div class="px-6 2xl:container">
        <slot />
      </div>
    </div>
  </div>
</template>
