<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page  = usePage();
const flash = computed(() => page.props.flash || {});
const sidebarOpen = ref(false);

const navGroups = [
  {
    label: 'General',
    links: [
      { name: 'Dashboard', href: route('admin.dashboard'),    icon: 'dashboard' },
      { name: 'Profile',   href: route('admin.profile.edit'), icon: 'user' },
    ],
  },
  {
    label: 'Content',
    links: [
      { name: 'Projects',    href: route('admin.projects.index'),         icon: 'folder' },
      { name: 'Work',        href: route('admin.work-experiences.index'), icon: 'briefcase' },
      { name: 'Education',   href: route('admin.educations.index'),       icon: 'graduation' },
      { name: 'Blog',        href: route('admin.blog.index'),             icon: 'pencil' },
      { name: 'Lists',       href: route('admin.lists.index'),            icon: 'list' },
      { name: 'Reading',     href: route('admin.reading.index'),          icon: 'book' },
      { name: 'Timeline',    href: route('admin.timeline.index'),         icon: 'clock' },
    ],
  },
];

function logout() { router.post(route('admin.logout')); }

function isActive(href) {
  return page.url.startsWith(new URL(href).pathname);
}
</script>

<template>
  <div class="min-h-screen flex bg-background">

    <!-- ── Mobile overlay ── -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-20 bg-black/60 lg:hidden"
      @click="sidebarOpen = false"
    />

    <!-- ── Sidebar ── -->
    <aside
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
      class="fixed lg:static inset-y-0 left-0 z-30 w-52 flex flex-col
             bg-[hsl(0,0%,5%)] border-r border-white/[0.06]
             transition-transform duration-200 ease-in-out min-h-screen"
    >
      <!-- Brand -->
      <div class="px-4 py-4 border-b border-white/[0.06]">
        <div class="text-white font-display font-bold text-sm leading-tight tracking-wide">Belinze</div>
        <div class="text-[10px] mt-0.5 text-primary font-semibold uppercase tracking-widest">Admin Panel</div>
      </div>

      <!-- Nav groups -->
      <nav class="flex-1 px-2 py-3 overflow-y-auto space-y-4">
        <div v-for="group in navGroups" :key="group.label">
          <p class="px-2 mb-1 text-[10px] font-semibold uppercase tracking-widest text-white/25 select-none">
            {{ group.label }}
          </p>
          <div class="space-y-0.5">
            <Link
              v-for="link in group.links"
              :key="link.name"
              :href="link.href"
              @click="sidebarOpen = false"
              class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium transition-all"
              :class="isActive(link.href)
                ? 'bg-primary text-white shadow-sm'
                : 'text-white/45 hover:text-white hover:bg-white/[0.06]'"
            >
              <!-- icons -->
              <svg v-if="link.icon === 'dashboard'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="2"/>
                <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="2"/>
                <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="2"/>
              </svg>
              <svg v-else-if="link.icon === 'user'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <svg v-else-if="link.icon === 'folder'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
              </svg>
              <svg v-else-if="link.icon === 'briefcase'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="2" y="7" width="20" height="14" rx="2" stroke-width="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
              </svg>
              <svg v-else-if="link.icon === 'graduation'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422A12.083 12.083 0 0121 11.2V17a9 9 0 01-18 0v-5.8c.456.08.908.198 1.84.578L12 14z"/>
              </svg>
              <svg v-else-if="link.icon === 'pencil'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
              <svg v-else-if="link.icon === 'list'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
              </svg>
              <svg v-else-if="link.icon === 'book'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
              <svg v-else-if="link.icon === 'clock'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" stroke-width="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v5l3 3"/>
              </svg>
              <span>{{ link.name }}</span>
            </Link>
          </div>
        </div>
      </nav>

      <!-- Bottom -->
      <div class="px-2 py-3 border-t border-white/[0.06]">
        <button
          @click="logout"
          class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs font-medium
                 text-white/40 hover:text-red-400 hover:bg-white/[0.06] transition-all text-left"
        >
          <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Sign out
        </button>
      </div>
    </aside>

    <!-- ── Main area ── -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

      <!-- Top bar -->
      <header class="bg-card border-b border-border px-4 lg:px-6 py-3 flex items-center gap-4 shrink-0">
        <!-- Mobile hamburger -->
        <button
          class="lg:hidden p-1.5 rounded-lg text-muted-foreground hover:bg-muted transition-colors"
          @click="sidebarOpen = !sidebarOpen"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>

        <div class="flex-1 flex items-center gap-2 min-w-0">
          <!-- Back button slot -->
          <slot name="back" />
        </div>

        <span class="text-xs text-muted-foreground hidden sm:block">
          Logged in as <span class="font-semibold text-foreground">Administrator</span>
        </span>
      </header>

      <!-- Flash messages -->
      <transition enter-active-class="transition duration-200" enter-from-class="opacity-0 -translate-y-1" enter-to-class="opacity-100 translate-y-0">
        <div v-if="flash.success"
          class="mx-4 lg:mx-6 mt-4 px-4 py-3 rounded-xl text-sm font-medium
                 text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20
                 border border-green-200 dark:border-green-800 flex items-center gap-2">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          {{ flash.success }}
        </div>
      </transition>
      <transition enter-active-class="transition duration-200" enter-from-class="opacity-0 -translate-y-1" enter-to-class="opacity-100 translate-y-0">
        <div v-if="flash.error"
          class="mx-4 lg:mx-6 mt-4 px-4 py-3 rounded-xl text-sm font-medium
                 text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/20
                 border border-red-200 dark:border-red-800 flex items-center gap-2">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
          {{ flash.error }}
        </div>
      </transition>

      <!-- Page content -->
      <main class="flex-1 p-4 lg:p-6 overflow-auto">
        <slot />
      </main>
    </div>
  </div>
</template>
