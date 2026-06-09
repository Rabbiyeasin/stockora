<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-indigo-600">SaaS Platform</h1>
            </div>

            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
              <NavLink 
                v-if="hasPermission('dashboard.view')"
                :href="route('app.dashboard', { client_id: clientId })" 
                :active="route().current('app.dashboard')"
              >
                Dashboard
              </NavLink>
              
              <NavLink 
                v-if="hasPermission('products.view')"
                :href="route('app.products.index', { client_id: clientId })" 
                :active="route().current('app.products.*')"
              >
                Products
              </NavLink>
              
              <NavLink 
                v-if="hasPermission('sales.view')"
                href="#" 
                :active="false"
              >
                Sales
              </NavLink>
              
              <NavLink 
                v-if="hasPermission('pos.access')"
                href="#" 
                :active="false"
              >
                POS
              </NavLink>
              
              <NavLink 
                v-if="hasPermission('customers.view')"
                href="#" 
                :active="false"
              >
                Customers
              </NavLink>
              
              <NavLink 
                v-if="hasPermission('reports.view')"
                href="#" 
                :active="false"
              >
                Reports
              </NavLink>
              
              <NavLink 
                v-if="hasPermission('settings.view')"
                href="#" 
                :active="false"
              >
                Settings
              </NavLink>
            </div>
          </div>

          <div class="hidden sm:flex sm:items-center sm:ml-6">
            <div class="ml-3 relative" v-click-outside="closeDropdown">
              <div>
                <button
                  @click="showingDropdown = !showingDropdown"
                  type="button"
                  class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                >
                  <div class="flex items-center">
                    <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">
                      {{ userInitials }}
                    </div>
                    <span class="ml-2">{{ $page.props.auth.user.name }}</span>
                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </button>
              </div>

              <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <div
                  v-show="showingDropdown"
                  class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                >
                  <div class="px-4 py-2 text-xs text-gray-400 border-b">
                    Client ID: <span class="font-mono font-bold">{{ clientId }}</span>
                  </div>
                  <div class="px-4 py-2 text-xs text-gray-400 border-b">
                    Role: <span class="font-semibold">{{ $page.props.auth.user.role?.name }}</span>
                  </div>
                  <DropdownLink href="#" @click="showingDropdown = false">
                    Profile
                  </DropdownLink>
                  <DropdownLink 
                    v-if="hasPermission('settings.view')"
                    href="#" 
                    @click="showingDropdown = false"
                  >
                    Settings
                  </DropdownLink>
                  <div class="border-t border-gray-100"></div>
                  <DropdownLink :href="route('logout')" method="post" as="button" @click="showingDropdown = false">
                    Log Out
                  </DropdownLink>
                </div>
              </Transition>
            </div>
          </div>

          <div class="-mr-2 flex items-center sm:hidden">
            <button
              @click="showingMobileMenu = !showingMobileMenu"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
            >
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{ 'hidden': showingMobileMenu, 'inline-flex': !showingMobileMenu }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{ 'hidden': !showingMobileMenu, 'inline-flex': showingMobileMenu }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div :class="{ 'block': showingMobileMenu, 'hidden': !showingMobileMenu }" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <ResponsiveNavLink 
            v-if="hasPermission('dashboard.view')"
            :href="route('app.dashboard', { client_id: clientId })" 
            :active="route().current('app.dashboard')"
          >
            Dashboard
          </ResponsiveNavLink>
          
          <ResponsiveNavLink 
            v-if="hasPermission('products.view')"
            href="#" 
            :active="false"
          >
            Products
          </ResponsiveNavLink>
          
          <ResponsiveNavLink 
            v-if="hasPermission('sales.view')"
            href="#" 
            :active="false"
          >
            Sales
          </ResponsiveNavLink>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
          <div class="px-4">
            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
          </div>

          <div class="mt-3 space-y-1">
            <ResponsiveNavLink href="#" :active="false">
              Profile
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
              Log Out
            </ResponsiveNavLink>
          </div>
        </div>
      </div>
    </nav>

    <header v-if="$slots.header" class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header>

    <main>
      <slot />
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { usePermission } from '@/Composables/usePermission';
import NavLink from '@/Components/NavLink.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const { hasPermission } = usePermission();

const showingDropdown = ref(false);
const showingMobileMenu = ref(false);

const page = usePage();
const clientId = computed(() => route().params.client_id);

const userInitials = computed(() => {
  const name = page.props.auth.user.name;
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
});

const closeDropdown = () => {
  showingDropdown.value = false;
};

const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  },
};
</script>