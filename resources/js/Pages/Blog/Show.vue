<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({ post: Object, profile: Object });

const isDark = ref(false);
onMounted(() => { isDark.value = document.documentElement.classList.contains('dark'); });
function toggleDark() {
  isDark.value = !isDark.value;
  document.documentElement.classList.toggle('dark', isDark.value);
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
}
</script>

<template>
  <Head :title="post.title + ' · ' + (profile?.name || 'Blog')" />
  <div class="min-h-screen bg-background text-foreground">
    <div class="mx-auto max-w-2xl px-4">

      <!-- Top bar -->
      <div class="flex items-center justify-between py-4 border-b border-border">
        <Link :href="route('home')" class="inline-flex items-center gap-1.5 text-sm text-muted-foreground hover:text-foreground transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          {{ profile?.name || 'Back' }}
        </Link>
        <button @click="toggleDark" :title="isDark ? 'Light mode' : 'Dark mode'"
          class="w-8 h-8 rounded-full flex items-center justify-center border border-border
                 text-muted-foreground hover:text-primary hover:border-primary/40 transition-all bg-card">
          <svg v-if="isDark" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="4"/>
            <path stroke-linecap="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
          </svg>
          <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
          </svg>
        </button>
      </div>

      <!-- Cover image -->
      <div v-if="post.cover_image_url" class="mt-6 rounded-2xl overflow-hidden border border-border">
        <img :src="post.cover_image_url" :alt="post.title" class="w-full h-48 sm:h-64 object-cover"/>
      </div>

      <!-- Post header -->
      <div class="mt-6 mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold font-display text-foreground leading-tight">{{ post.title }}</h1>
        <p v-if="post.published_at" class="text-sm text-muted-foreground mt-2">{{ post.published_at }}</p>
        <p v-if="post.excerpt" class="text-sm text-muted-foreground mt-2 italic border-l-2 border-primary/40 pl-3">{{ post.excerpt }}</p>
      </div>

      <!-- Post content -->
      <div class="section-card mb-6">
        <div class="prose prose-sm max-w-none text-foreground leading-relaxed whitespace-pre-wrap">{{ post.content }}</div>
        <div v-if="post.external_url" class="mt-6 pt-4 border-t border-border">
          <a :href="post.external_url" target="_blank" rel="noopener"
            class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline">
            Read the original post
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
          </a>
        </div>
      </div>

      <!-- Footer -->
      <footer class="py-8 border-t border-border text-center">
        <p class="text-xs text-muted-foreground">
          <a :href="'mailto:' + (profile?.email || 'newtonebelinzeojing@gmail.com')"
            class="hover:text-primary transition-colors">{{ profile?.email || 'newtonebelinzeojing@gmail.com' }}</a>
          <span> · </span>
          © {{ new Date().getFullYear() }} {{ profile?.name || 'Belinze Newtone' }}
        </p>
      </footer>
    </div>
  </div>
</template>
