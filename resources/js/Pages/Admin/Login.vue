<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  password: '',
});

function submit() {
  form.post(route('admin.login.submit'));
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center px-4" style="background-color: hsl(30, 20%, 97%);">
    <div class="w-full max-w-sm">
      <!-- Header -->
      <div class="text-center mb-8">
        <div
          class="w-12 h-12 rounded-xl mx-auto mb-4 flex items-center justify-center font-bold text-white text-lg"
          style="background-color: hsl(82, 77%, 40%); font-family: 'Space Grotesk', sans-serif;"
        >
          B
        </div>
        <h1 class="text-2xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
          Admin Login
        </h1>
        <p class="text-sm mt-1" style="color: hsl(220, 5%, 46%);">
          Enter your password to access the admin panel.
        </p>
      </div>

      <!-- Card -->
      <div class="rounded-xl p-6 shadow-sm border" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label for="password" class="block text-sm font-medium mb-1.5" style="color: hsl(220, 14%, 10%);">
              Password
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              autocomplete="current-password"
              required
              placeholder="Enter admin password"
              class="w-full px-3 py-2 text-sm rounded-md border outline-none transition-colors focus:ring-2"
              :style="`border-color: ${form.errors.password ? 'rgb(239,68,68)' : 'hsl(30, 10%, 90%)'}; focus: hsl(82, 77%, 40%);`"
              style="border-color: hsl(30, 10%, 90%);"
            />
            <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-600">
              {{ form.errors.password }}
            </p>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-2 px-4 text-sm font-semibold rounded-md text-white transition-opacity disabled:opacity-60"
            style="background-color: hsl(82, 77%, 40%);"
          >
            <span v-if="form.processing">Signing in...</span>
            <span v-else>Sign in</span>
          </button>
        </form>
      </div>

      <div class="text-center mt-4">
        <a :href="route('home')" class="text-xs hover:underline" style="color: hsl(220, 5%, 46%);">
          &larr; Back to portfolio
        </a>
      </div>
    </div>
  </div>
</template>
