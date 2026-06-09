<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Category
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <form @submit.prevent="submit" class="p-6 space-y-6">

            <!-- Name -->
            <div>
              <label class="label">Category Name</label>

              <input
                v-model="form.name"
                type="text"
                class="input"
                :class="{ 'input-error': form.errors.name }"
                required
              />

              <div v-if="form.errors.name" class="error-text">
                {{ form.errors.name }}
              </div>
            </div>

            <!-- Parent -->
            <div>
              <label class="label">Parent Category (Optional)</label>

              <select
                v-model="form.parent_id"
                class="input"
                :class="{ 'input-error': form.errors.parent_id }"
              >
                <option :value="null">None (Main Category)</option>

                <option
                  v-for="cat in parentCategories"
                  :key="cat.id"
                  :value="cat.id"
                >
                  {{ cat.name }}
                </option>
              </select>

              <div v-if="form.errors.parent_id" class="error-text">
                {{ form.errors.parent_id }}
              </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
              <Link
                :href="route('app.categories.index', { client_id })"
                class="btn btn-secondary"
              >
                Cancel
              </Link>

              <button
                type="submit"
                :disabled="form.processing"
                class="btn btn-primary"
              >
                Update Category
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed } from 'vue'

const props = defineProps({
  category: Object,
  parentCategories: Array,
  client_id: String,
})

const client_id = props.client_id

const form = useForm({
  name: props.category?.name ?? '',
  parent_id: props.category?.parent_id ?? null,
})

const submit = () => {
  form.put(
    route('app.categories.update', {
      client_id,
      categoryId: props.category.id,
    })
  )
}
</script>