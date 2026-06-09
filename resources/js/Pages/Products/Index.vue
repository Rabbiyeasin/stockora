<template>
    <AppLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
                <Link 
                    :href="route('app.products.create', { client_id: clientId })" 
                    class="btn btn-primary"
                >
                    + New Product
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">SKU</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Product Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Store</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ product.sku }}</td>
                                    <td class="px-6 py-4">{{ product.name }}</td>
                                    <td class="px-6 py-4">{{ product.category?.name || '—' }}</td>
                                    <td class="px-6 py-4">{{ product.store?.name || '—' }}</td>
                                    <td class="px-6 py-4">{{ product.inventory?.quantity ?? 0 }}</td>
                                    <td class="px-6 py-4">
                                        <span :class="product.is_active ? 'text-emerald-600' : 'text-red-600'">
                                            {{ product.is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link 
                                            :href="route('app.products.edit', { 
                                                client_id: clientId, 
                                                product: product.id 
                                            })" 
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            Edit
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

// Better way to get client_id
const clientId = computed(() => page.url.split('/')[2] || '100002');
</script>