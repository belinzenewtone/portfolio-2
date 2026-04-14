<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const flash = computed(() => page.props.flash || {});
const sidebarOpen = ref(false);

const navLinks = [
  { name: 'Dashboard', href: route('admin.dashboard'), icon: 'dashboard' },
  { name: 'Profile', href: route('admin.profile.edit'), icon: 'user' },
  { name: 'Projects', href: route('admin.projects.index'), icon: 'folder' },
  { name: 'Work Experiences', href: route('admin.work-experiences.index'), icon: 'briefcase' },
  { name: 'Educations', href: route('admin.educations.index'), icon: 'graduation' },
];

function logout() {
  router.post(route('admin.logout'));
}

function isActive(href) {
  return page.url.startsWith(new URL(href).pathname);
}
</script>

<template>
  <div class="min-h-screen flex" style="background-color: hsl(30, 10%, 94%);">
    <!-- Sidebar -->
    <aside
      class="w-56 flex-shrink-0 flex flex-col"
      style="background-color: hsl(220, 14%, 10%); min-height: 100vh;"
    >
      <!-- Logo / Brand -->
      <div class="px-5 py-5 border-b" style="border-color: rgba(255,255,255,0.08);">
        <Link :href="route('home')" class="block">
          <div class="text-white font-display font-bold text-base leading-tight">Belinze</div>
          <div class="text-xs mt-0.5" style="color: #65a30d;">Admin Panel</div>
        </Link>
      </div>

      <!-- Nav -->
      <nav class="flex-1 px-3 py-4 space-y-0.5">
        <Link
          v-for="link in navLinks"
          :key="link.name"
          :href="link.href"
          class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm transition-colors"
          :class="isActive(link.href)
            ? 'text-white font-medium'
            : 'text-gray-400 hover:text-white hover:bg-white/5'"
          :style="isActive(link.href) ? 'background-color: #65a30d;' : ''"
        >
          <!-- Dashboard icon -->
          <svg v-if="link.icon === 'dashboard'" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/>
            <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/>
            <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/>
            <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2"/>
          </svg>
          <!-- User icon -->
          <svg v-else-if="link.icon === 'user'" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          <!-- Folder icon -->
          <svg v-else-if="link.icon === 'folder'" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
          </svg>
          <!-- Briefcase icon -->
          <svg v-else-if="link.icon === 'briefcase'" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="2" y="7" width="20" height="14" rx="2" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
          </svg>
          <!-- Graduation icon -->
          <svg v-else-if="link.icon === 'graduation'" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 11.2V17a9 9 0 01-18 0v-5.8c.456.08.908.198 1.84.578L12 14z"/>
          </svg>
          <span>{{ link.name }}</span>
        </Link>
      </nav>

      <!-- Bottom: view site + logout -->
      <div class="px-3 py-4 border-t space-y-0.5" style="border-color: rgba(255,255,255,0.08);">
        <Link
          :href="route('home')"
          class="flex items-center gap-2.5 px-3 py-2 rounded-md text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors"
        >
          <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
          </svg>
          View Site
        </Link>
        <button
          @click="logout"
          class="w-full flex items-center gap-2.5 px-3 py-2 rounded-md text-sm text-gray-400 hover:text-red-400 hover:bg-white/5 transition-colors text-left"
        >
          <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Logout
        </button>
      </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Top bar -->
      <header class="bg-white border-b px-6 py-3 flex items-center justify-between" style="border-color: hsl(30, 10%, 90%);">
        <h1 class="text-sm font-medium text-gray-500">Admin Panel</h1>
        <div class="text-sm text-gray-500">
          Logged in as <span class="font-medium text-gray-800">Administrator</span>
        </div>
      </header>

      <!-- Flash messages -->
      <div v-if="flash.success" class="mx-6 mt-4 px-4 py-3 rounded-md text-sm font-medium text-green-800 bg-green-50 border border-green-200 flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ flash.success }}
      </div>
      <div v-if="flash.error" class="mx-6 mt-4 px-4 py-3 rounded-md text-sm font-medium text-red-800 bg-red-50 border border-red-200 flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ flash.error }}
      </div>

      <!-- Page content -->
      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>
