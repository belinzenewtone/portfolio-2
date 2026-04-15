<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({ stats: Object });

const statCards = [
  { label: 'Projects',         key: 'projects',         href: 'admin.projects.index',         createHref: 'admin.projects.create',         icon: 'folder',     color: 'text-primary',  bg: 'bg-primary/10' },
  { label: 'Work Experience',  key: 'work_experiences', href: 'admin.work-experiences.index', createHref: 'admin.work-experiences.create', icon: 'briefcase',  color: 'text-sky-500',   bg: 'bg-sky-50' },
  { label: 'Education',        key: 'educations',       href: 'admin.educations.index',       createHref: 'admin.educations.create',       icon: 'graduation', color: 'text-violet-500', bg: 'bg-violet-50' },
];
</script>

<template>
  <AdminLayout>
    <div class="max-w-4xl space-y-6">

      <!-- Page title -->
      <div>
        <h2 class="text-xl font-bold font-display text-foreground">Dashboard</h2>
        <p class="text-sm mt-1 text-muted-foreground">Overview of your portfolio content.</p>
      </div>

      <!-- Stats grid -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div v-for="card in statCards" :key="card.key" class="section-card">
          <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" :class="card.bg">
              <svg v-if="card.icon === 'folder'" class="w-5 h-5" :class="card.color" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
              </svg>
              <svg v-else-if="card.icon === 'briefcase'" class="w-5 h-5" :class="card.color" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="2" y="7" width="20" height="14" rx="2" stroke-width="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
              </svg>
              <svg v-else-if="card.icon === 'graduation'" class="w-5 h-5" :class="card.color" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 11.2V17a9 9 0 01-18 0v-5.8c.456.08.908.198 1.84.578L12 14z"/>
              </svg>
            </div>
          </div>
          <div class="text-3xl font-bold font-display text-foreground">{{ stats[card.key] ?? 0 }}</div>
          <div class="text-sm mt-0.5 text-muted-foreground">{{ card.label }}</div>
          <div class="flex gap-3 mt-4 pt-3 border-t border-border">
            <Link :href="route(card.href)" class="text-xs font-semibold text-primary hover:underline">Manage →</Link>
            <Link :href="route(card.createHref)" class="text-xs font-semibold text-muted-foreground hover:text-primary transition-colors">+ Add new</Link>
          </div>
        </div>
      </div>

      <!-- Quick actions -->
      <div class="section-card">
        <h3 class="text-sm font-semibold text-foreground mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-2">
          <Link :href="route('admin.profile.edit')"
            class="admin-btn-outline">Edit Profile</Link>
          <Link :href="route('admin.projects.create')"
            class="admin-btn-outline">+ Add Project</Link>
          <Link :href="route('admin.work-experiences.create')"
            class="admin-btn-outline">+ Add Work Experience</Link>
          <Link :href="route('admin.educations.create')"
            class="admin-btn-outline">+ Add Education</Link>
          <Link :href="route('home')"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-all">
            View Portfolio →
          </Link>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>
