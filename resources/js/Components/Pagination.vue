<template>
  <nav v-if="links.length > 3" class="flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
      <Link
        v-if="links[0].url"
        :href="links[0].url"
        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
      >
        Previous
      </Link>
      <Link
        v-if="links[links.length - 1].url"
        :href="links[links.length - 1].url"
        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
      >
        Next
      </Link>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Showing page <span class="font-medium">{{ currentPage }}</span>
        </p>
      </div>
      <div>
        <span class="relative z-0 inline-flex shadow-sm rounded-md">
          <Link
            v-for="(link, index) in links"
            :key="index"
            :href="link.url"
            :class="[
              'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
              link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              index === 0 ? 'rounded-l-md' : '',
              index === links.length - 1 ? 'rounded-r-md' : '',
              !link.url ? 'cursor-not-allowed opacity-50' : '',
            ]"
            :preserve-scroll="true"
            v-html="link.label"
          />
        </span>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  links: Array,
});

const currentPage = computed(() => {
  const activeLink = props.links.find(link => link.active);
  return activeLink ? activeLink.label : 1;
});
</script>