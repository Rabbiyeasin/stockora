<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Product</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        
                        <!-- Image -->
                        <div>
                            <label class="label">Product Image</label>
                            <input 
                                type="file" 
                                accept="image/*"
                                @change="handleImageUpload"
                                class="input"
                            />
                            <div v-if="imagePreview || product.image" class="mt-4">
                                <img 
                                    :src="imagePreview || `/storage/${product.image}`" 
                                    class="h-40 w-40 object-cover rounded-lg"
                                />
                            </div>
                        </div>

                        <!-- SKU -->
                        <div>
                            <label class="label">SKU</label>
                            <input 
                                type="text" 
                                :value="product.sku" 
                                class="input bg-gray-100" 
                                readonly
                            />
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="label">Product Name</label>
                            <input 
                                v-model="form.name"
                                id="name"
                                type="text"
                                class="input"
                                required
                            />
                        </div>

                        <!-- Store -->
                        <div>
                            <label for="store_id" class="label">Store</label>
                            <select v-model="form.store_id" id="store_id" class="input" required>
                                <option v-for="store in stores" :key="store.id" :value="store.id">
                                    {{ store.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="label">Category</label>
                            <select v-model="form.category_id" id="category_id" class="input">
                                <option value="">None</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="label">Description</label>
                            <textarea 
                                v-model="form.description"
                                id="description"
                                rows="3"
                                class="input"
                            ></textarea>
                        </div>

                        <!-- Prices -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="buy_price" class="label">Buy Price</label>
                                <input 
                                    v-model="form.buy_price"
                                    id="buy_price"
                                    type="number"
                                    step="0.01"
                                    class="input"
                                    required
                                />
                            </div>
                            <div>
                                <label for="sell_price" class="label">Sell Price</label>
                                <input 
                                    v-model="form.sell_price"
                                    id="sell_price"
                                    type="number"
                                    step="0.01"
                                    class="input"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Alert Qty & Status -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="alert_qty" class="label">Alert Quantity</label>
                                <input 
                                    v-model="form.alert_qty"
                                    id="alert_qty"
                                    type="number"
                                    class="input"
                                    required
                                />
                            </div>
                            <div>
                                <label for="is_active" class="label">Status</label>
                                <select v-model="form.is_active" class="input">
                                    <option :value="true">Active</option>
                                    <option :value="false">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Current Stock (Read Only) -->
                        <div>
                            <label class="label">Current Stock</label>
                            <input 
                                type="text" 
                                :value="product.inventory?.quantity ?? 0" 
                                class="input bg-gray-100" 
                                readonly
                            />
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <Link 
                                :href="route('app.products.index', { client_id: clientId })" 
                                class="btn btn-secondary"
                            >
                                Cancel
                            </Link>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="btn btn-primary"
                            >
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    product: Object,
    categories: Array,
    stores: Array,
});

const clientId = computed(() => usePage().url.split('/')[2]); // Gets client_id from URL

const form = useForm({
    store_id: props.product.store_id,
    category_id: props.product.category_id,
    name: props.product.name,
    description: props.product.description,
    buy_price: props.product.buy_price,
    sell_price: props.product.sell_price,
    alert_qty: props.product.alert_qty,
    is_active: props.product.is_active,
    image: null,
});

const imagePreview = ref(null);

const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

form.put(route('app.products.update', {
    client_id: clientId.value,
    product: props.product.id,
}), {
    forceFormData: true,
    onError: (errors) => {
        console.log('Validation errors:', errors);
    },
});

const submit = () => {
    console.log('FORM DATA:', form.data());

    form.put(route('app.products.update', {
        client_id: clientId.value,
        product: props.product.id
    }), {
        forceFormData: true,
    });
};
</script>