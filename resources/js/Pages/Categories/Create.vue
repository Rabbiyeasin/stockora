<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Category</h2>
    </template>

    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <div>
              <label for="name" class="label">Category Name</label>
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
              <label for="parent_id" class="label">Parent Category (Optional)</label>
              <select
                id="parent_id"
                v-model="form.parent_id"
                class="input"
                :class="{ 'input-error': form.errors.parent_id }"
              >
                <option :value="null">None (Main Category)</option>
                <option v-for="category in parentCategories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <div v-if="form.errors.parent_id" class="error-text">{{ form.errors.parent_id }}</div>
            </div>

            <div class="flex items-center justify-end space-x-4">
              <Link
                :href="route('app.categories.index', { client_id: clientId })"
                class="btn btn-secondary"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="btn btn-primary"
              >
                Create Category
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  parentCategories: Array,
});

const clientId = computed(() => route().params.client_id);

const form = useForm({
  name: '',
  parent_id: null,
});

const submit = () => {
  form.post(route('app.categories.store', { client_id: clientId.value }));
};
</script>