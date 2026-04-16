<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({ username: '', password: '' });
const page = usePage();

// Catch session-expiry / lockout errors passed via withErrors on redirect
const flashError = computed(() => page.props.errors?.password || null);

function submit() {
  form.post(route('admin.login.submit'), { preserveScroll: true });
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center px-4 bg-background">
    <div class="w-full max-w-sm">

      <!-- Header -->
      <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-xl mx-auto mb-4 flex items-center justify-center font-bold text-white text-lg bg-primary font-display">
          B
        </div>
        <h1 class="text-2xl font-bold font-display text-foreground">Admin Login</h1>
        <p class="text-sm mt-1 text-muted-foreground">Sign in with your admin credentials.</p>
      </div>

      <!-- Card -->
      <div class="section-card">
        <form @submit.prevent="submit" class="space-y-4">

          <!-- Username -->
          <div>
            <label for="username" class="block text-xs font-semibold mb-1.5 text-foreground">Username</label>
            <input
              id="username"
              v-model="form.username"
              type="text"
              autocomplete="username"
              required
              placeholder="Enter admin username"
              class="admin-input"
              :class="form.errors.username ? 'border-red-400 focus:border-red-400 focus:ring-red-200' : ''"
            />
            <p v-if="form.errors.username" class="mt-1.5 text-xs text-red-500">{{ form.errors.username }}</p>
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-xs font-semibold mb-1.5 text-foreground">Password</label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              autocomplete="current-password"
              required
              placeholder="Enter admin password"
              class="admin-input"
              :class="form.errors.password ? 'border-red-400 focus:border-red-400 focus:ring-red-200' : ''"
            />
            <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-500">{{ form.errors.password }}</p>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-2.5 px-4 text-sm font-semibold rounded-xl text-primary-foreground bg-primary hover:bg-primary/90 disabled:opacity-60 transition-all"
          >
            {{ form.processing ? 'Signing in…' : 'Sign in' }}
          </button>
        </form>
      </div>

      <div class="text-center mt-4">
        <a :href="route('home')" class="text-xs text-muted-foreground hover:text-primary transition-colors">
          ← Back to portfolio
        </a>
      </div>
    </div>
  </div>
</template>
