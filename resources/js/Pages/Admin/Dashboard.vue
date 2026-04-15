<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({ stats: Object });

const statCards = [
  { label: 'Projects',    key: 'projects',         route: 'admin.projects.index',         icon: 'folder',     color: 'text-blue-500 bg-blue-500/10' },
  { label: 'Work',        key: 'work_experiences',  route: 'admin.work-experiences.index', icon: 'briefcase',  color: 'text-sky-500 bg-sky-500/10' },
  { label: 'Education',   key: 'educations',        route: 'admin.educations.index',       icon: 'graduation', color: 'text-violet-500 bg-violet-500/10' },
  { label: 'Blog Posts',  key: 'blog_posts',        route: 'admin.blog.index',             icon: 'pencil',     color: 'text-pink-500 bg-pink-500/10' },
  { label: 'Lists',       key: 'lists',             route: 'admin.lists.index',            icon: 'list',       color: 'text-orange-500 bg-orange-500/10' },
  { label: 'Reading',     key: 'reading',           route: 'admin.reading.index',          icon: 'book',       color: 'text-green-500 bg-green-500/10' },
  { label: 'Timeline',    key: 'timeline',          route: 'admin.timeline.index',         icon: 'clock',      color: 'text-amber-500 bg-amber-500/10' },
];
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">

      <div>
        <h2 class="text-xl font-bold font-display text-foreground">Dashboard</h2>
        <p class="text-sm text-muted-foreground mt-0.5">Overview of your portfolio content</p>
      </div>

      <!-- Stat grid -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        <Link
          v-for="card in statCards"
          :key="card.key"
          :href="route(card.route)"
          class="section-card !p-4 flex items-center gap-3 hover:border-primary/30 hover:shadow-md transition-all group"
        >
          <div :class="card.color" class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0">
            <svg v-if="card.icon === 'folder'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
            </svg>
            <svg v-else-if="card.icon === 'briefcase'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <rect x="2" y="7" width="20" height="14" rx="2" stroke-width="2"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
            </svg>
            <svg v-else-if="card.icon === 'graduation'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.422A12.083 12.083 0 0121 11.2V17a9 9 0 01-18 0v-5.8c.456.08.908.198 1.84.578L12 14z"/>
            </svg>
            <svg v-else-if="card.icon === 'pencil'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <svg v-else-if="card.icon === 'list'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            <svg v-else-if="card.icon === 'book'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <svg v-else-if="card.icon === 'clock'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="9" stroke-width="2"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v5l3 3"/>
            </svg>
          </div>
          <div>
            <div class="text-2xl font-bold font-display text-foreground leading-none">{{ stats[card.key] ?? 0 }}</div>
            <div class="text-xs text-muted-foreground mt-0.5 group-hover:text-primary transition-colors">{{ card.label }}</div>
          </div>
        </Link>
      </div>

      <!-- Quick add -->
      <div class="section-card">
        <h3 class="text-sm font-semibold text-foreground mb-3">Quick add</h3>
        <div class="flex flex-wrap gap-2">
          <Link :href="route('admin.projects.create')"         class="admin-btn-outline text-xs">+ Project</Link>
          <Link :href="route('admin.work-experiences.create')" class="admin-btn-outline text-xs">+ Work</Link>
          <Link :href="route('admin.blog.create')"             class="admin-btn-outline text-xs">+ Post</Link>
          <Link :href="route('admin.lists.create')"            class="admin-btn-outline text-xs">+ List</Link>
          <Link :href="route('admin.reading.create')"          class="admin-btn-outline text-xs">+ Book</Link>
          <Link :href="route('admin.timeline.create')"         class="admin-btn-outline text-xs">+ Event</Link>
          <Link :href="route('admin.educations.create')"       class="admin-btn-outline text-xs">+ Education</Link>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>
