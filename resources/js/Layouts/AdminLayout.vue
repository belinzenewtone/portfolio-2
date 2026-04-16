<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page           = usePage();
const flash          = computed(() => page.props.flash || {});
const unreadMessages = computed(() => page.props.unreadMessages || 0);
const sidebarOpen    = ref(false);

// ── Toast system ──────────────────────────────────────────────────────────
const toast = ref(null);   // { type: 'success'|'error'|'info', message }
let toastTimer = null;

const toastConfig = {
  success: {
    classes: 'bg-[hsl(142,72%,18%)] border-green-700/60 text-green-200',
    icon: 'M5 13l4 4L19 7',
  },
  error: {
    classes: 'bg-[hsl(0,62%,18%)] border-red-700/60 text-red-200',
    icon: 'M6 18L18 6M6 6l12 12',
  },
  info: {
    classes: 'bg-[hsl(217,60%,18%)] border-primary/40 text-blue-200',
    icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
  },
};

function showToast(type, message) {
  clearTimeout(toastTimer);
  toast.value = { type, message };
  toastTimer = setTimeout(() => { toast.value = null; }, 4000);
}

function dismissToast() {
  clearTimeout(toastTimer);
  toast.value = null;
}

// Watch flash messages from server and convert to toasts
watch(flash, (f) => {
  if (f.success) showToast('success', f.success);
  else if (f.error) showToast('error', f.error);
  else if (f.info)  showToast('info',  f.info);
}, { immediate: true });

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
      { name: 'Skills',      href: route('admin.skills.index'),           icon: 'star' },
      { name: 'Work',        href: route('admin.work-experiences.index'), icon: 'briefcase' },
      { name: 'Education',   href: route('admin.educations.index'),       icon: 'graduation' },
      { name: 'Blog',        href: route('admin.blog.index'),             icon: 'pencil' },
      { name: 'Lists',       href: route('admin.lists.index'),            icon: 'list' },
      { name: 'Reading',     href: route('admin.reading.index'),          icon: 'book' },
      { name: 'Timeline',    href: route('admin.timeline.index'),         icon: 'clock' },
    ],
  },
  {
    label: 'Inbox',
    links: [
      { name: 'Messages',    href: route('admin.messages.index'),         icon: 'mail' },
    ],
  },
];

function logout() { router.post(route('admin.logout')); }

function isActive(href) {
  const path = new URL(href).pathname.replace(/\/$/, '');
  const current = page.url.split('?')[0].replace(/\/$/, '');
  // Dashboard lives at exactly /admin — use exact match so it doesn't
  // light up for every other /admin/* route
  if (path === '/admin') return current === '/admin';
  return current === path || current.startsWith(path + '/');
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
              <svg v-else-if="link.icon === 'star'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
              </svg>
              <svg v-else-if="link.icon === 'mail'" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <span class="flex-1">{{ link.name }}</span>
              <span v-if="link.icon === 'mail' && unreadMessages > 0"
                class="ml-auto text-[10px] font-bold bg-red-500 text-white rounded-full px-1.5 py-0.5 leading-none">
                {{ unreadMessages }}
              </span>
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

      <!-- Page content -->
      <main class="flex-1 p-4 lg:p-6 overflow-auto">
        <slot />
      </main>
    </div>
  </div>

  <!-- ── Toast portal — fixed top-center ── -->
  <teleport to="body">
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-3 scale-95"
      enter-to-class="opacity-100 translate-y-0 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0 scale-100"
      leave-to-class="opacity-0 -translate-y-2 scale-95"
    >
      <div
        v-if="toast"
        :class="toastConfig[toast.type]?.classes"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999]
               flex items-center gap-3 px-5 py-3 rounded-2xl border
               shadow-2xl shadow-black/40 backdrop-blur-sm
               text-sm font-medium min-w-[260px] max-w-sm"
        role="alert"
      >
        <!-- Icon -->
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" :d="toastConfig[toast.type]?.icon"/>
        </svg>

        <!-- Message -->
        <span class="flex-1">{{ toast.message }}</span>

        <!-- Dismiss -->
        <button @click="dismissToast"
          class="shrink-0 opacity-60 hover:opacity-100 transition-opacity ml-1">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>

        <!-- Auto-dismiss progress bar -->
        <div class="absolute bottom-0 left-0 h-0.5 rounded-full bg-current opacity-30"
          style="animation: toast-shrink 4s linear forwards;">
        </div>
      </div>
    </transition>
  </teleport>
</template>

<style>
@keyframes toast-shrink {
  from { width: 100%; }
  to   { width: 0%; }
}
</style>
