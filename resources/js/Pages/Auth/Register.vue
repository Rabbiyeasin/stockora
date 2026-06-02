<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Start your 14-day free trial
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="submit">
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="business_name" class="label">Business Name</label>
            <input
              id="business_name"
              v-model="form.business_name"
              type="text"
              required
              class="input"
              :class="{ 'input-error': form.errors.business_name }"
              placeholder="Your Business Name"
            />
            <div v-if="form.errors.business_name" class="error-text">
              {{ form.errors.business_name }}
            </div>
          </div>

          <div>
            <label for="name" class="label">Your Name</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="input"
              :class="{ 'input-error': form.errors.name }"
              placeholder="John Doe"
            />
            <div v-if="form.errors.name" class="error-text">
              {{ form.errors.name }}
            </div>
          </div>

          <div>
            <label for="email" class="label">Email Address</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              autocomplete="email"
              required
              class="input"
              :class="{ 'input-error': form.errors.email }"
              placeholder="you@example.com"
            />
            <div v-if="form.errors.email" class="error-text">
              {{ form.errors.email }}
            </div>
          </div>

          <div>
            <label for="phone" class="label">Phone Number (Optional)</label>
            <input
              id="phone"
              v-model="form.phone"
              type="tel"
              class="input"
              :class="{ 'input-error': form.errors.phone }"
              placeholder="+880 1XXX XXXXXX"
            />
            <div v-if="form.errors.phone" class="error-text">
              {{ form.errors.phone }}
            </div>
          </div>

          <div>
            <label for="password" class="label">Password</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              autocomplete="new-password"
              required
              class="input"
              :class="{ 'input-error': form.errors.password }"
              placeholder="••••••••"
            />
            <div v-if="form.errors.password" class="error-text">
              {{ form.errors.password }}
            </div>
          </div>

          <div>
            <label for="password_confirmation" class="label">Confirm Password</label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              autocomplete="new-password"
              required
              class="input"
              :class="{ 'input-error': form.errors.password_confirmation }"
              placeholder="••••••••"
            />
            <div v-if="form.errors.password_confirmation" class="error-text">
              {{ form.errors.password_confirmation }}
            </div>
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="form.processing"
            class="btn btn-primary w-full disabled:opacity-50"
          >
            <span v-if="form.processing">Creating account...</span>
            <span v-else>Create account</span>
          </button>
        </div>

        <div class="text-center">
          <Link :href="route('login')" class="text-sm text-indigo-600 hover:text-indigo-500">
            Already have an account? Sign in
          </Link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  business_name: '',
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>