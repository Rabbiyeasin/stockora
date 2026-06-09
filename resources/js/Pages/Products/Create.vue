<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Product</h2>
    </template>

    <div class="py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <div>
              <label class="label">Product Image</label>
              <input
                type="file"
                accept="image/*"
                @change="handleImageUpload"
                class="input"
              />
              <div v-if="imagePreview" class="mt-4">
                <img :src="imagePreview" class="h-40 w-40 object-cover rounded-lg" />
              </div>
              <div v-if="form.errors.image" class="error-text">{{ form.errors.image }}</div>
            </div>

            <div>
              <label for="name" class="label">Product Name</label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                class="input"
                :class="{ 'input-error': form.errors.name }"
                required
              />
              <div v-if="form.errors.name" class="error-text">{{ form.errors.name }}</div>
            </div>

            <div>
              <label for="store_id" class="label">Store</label>
              <select
                id="store_id"
                v-model="form.store_id"
                class="input"
                :class="{ 'input-error': form.errors.store_id }"
                required
              >
                <option value="">Select Store</option>
                <option v-for="store in stores" :key="store.id" :value="store.id">
                  {{ store.name }}
                </option>
              </select>
              <div v-if="form.errors.store_id" class="error-text">{{ form.errors.store_id }}</div>
            </div>

            <div>
              <label for="category_id" class="label">Category (Optional)</label>
              <select
                id="category_id"
                v-model="form.category_id"
                class="input"
              >
                <option :value="null">None</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>

            <div>
              <label for="description" class="label">Description (Optional)</label>
              <textarea
                id="description"
                v-model="form.description"
                rows="3"
                class="input"
              ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="buy_price" class="label">Buy Price (৳)</label>
                <input
                  id="buy_price"
                  v-model="form.buy_price"
                  type="number"
                  step="0.01"
                  min="0"
                  class="input"
                  :class="{ 'input-error': form.errors.buy_price }"
                  required
                />
                <div v-if="form.errors.buy_price" class="error-text">{{ form.errors.buy_price }}</div>
              </div>

              <div>
                <label for="sell_price" class="label">Sell Price (৳)</label>
                <input
                  id="sell_price"
                  v-model="form.sell_price"
                  type="number"
                  step="0.01"
                  min="0"
                  class="input"
                  :class="{ 'input-error': form.errors.sell_price }"
                  required
                />
                <div v-if="form.errors.sell_price" class="error-text">{{ form.errors.sell_price }}</div>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="alert_qty" class="label">Alert Quantity</label>
                <input
                  id="alert_qty"
                  v-model="form.alert_qty"
                  type="number"
                  min="0"
                  class="input"
                  required
                />
              </div>

              <div>
                <label for="initial_stock" class="label">Initial Stock</label>
                <input
                  id="initial_stock"
                  v-model="form.initial_stock"
                  type="number"
                  min="0"
                  class="input"
                  :class="{ 'input-error': form.errors.initial_stock }"
                  required
                />
                <div v-if="form.errors.initial_stock" class="error-text">{{ form.errors.initial_stock }}</div>
              </div>
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
                Create Product
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
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  categories: Array,
  stores: Array,
});

const clientId = computed(() => route().params.client_id);
const imagePreview = ref(null);

const form = useForm({
  store_id: '',
  category_id: null,
  name: '',
  description: '',
  buy_price: '',
  sell_price: '',
  alert_qty: 10,
  initial_stock: 0,
  image: null,
});

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const submit = () => {
  form.post(route('app.products.store', { client_id: clientId.value }), {
    forceFormData: true,
  });
};
</script>