<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  stats: Object,
});

const statCards = [
  {
    label: 'Projects',
    key: 'projects',
    href: 'admin.projects.index',
    createHref: 'admin.projects.create',
    icon: 'folder',
    color: '#65a30d',
    bg: '#f7fee7',
  },
  {
    label: 'Work Experiences',
    key: 'work_experiences',
    href: 'admin.work-experiences.index',
    createHref: 'admin.work-experiences.create',
    icon: 'briefcase',
    color: '#2563eb',
    bg: '#eff6ff',
  },
  {
    label: 'Educations',
    key: 'educations',
    href: 'admin.educations.index',
    createHref: 'admin.educations.create',
    icon: 'graduation',
    color: '#7c3aed',
    bg: '#f5f3ff',
  },
];
</script>

<template>
  <AdminLayout>
    <div class="max-w-4xl">
      <div class="mb-6">
        <h2 class="text-xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
          Dashboard
        </h2>
        <p class="text-sm mt-1" style="color: hsl(220, 5%, 46%);">
          Overview of your portfolio content.
        </p>
      </div>

      <!-- Stats grid -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div
          v-for="card in statCards"
          :key="card.key"
          class="rounded-xl border p-5"
          style="background-color: white; border-color: hsl(30, 10%, 90%);"
        >
          <div class="flex items-center justify-between mb-3">
            <div
              class="w-9 h-9 rounded-lg flex items-center justify-center"
              :style="`background-color: ${card.bg};`"
            >
              <!-- Folder -->
              <svg v-if="card.icon === 'folder'" class="w-4 h-4" :style="`color: ${card.color};`" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
              </svg>
              <!-- Briefcase -->
              <svg v-else-if="card.icon === 'briefcase'" class="w-4 h-4" :style="`color: ${card.color};`" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="2" y="7" width="20" height="14" rx="2" stroke-width="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
              </svg>
              <!-- Graduation -->
              <svg v-else-if="card.icon === 'graduation'" class="w-4 h-4" :style="`color: ${card.color};`" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 11.2V17a9 9 0 01-18 0v-5.8c.456.08.908.198 1.84.578L12 14z"/>
              </svg>
            </div>
          </div>
          <div class="text-3xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
            {{ stats[card.key] ?? 0 }}
          </div>
          <div class="text-sm mt-0.5" style="color: hsl(220, 5%, 46%);">{{ card.label }}</div>
          <div class="flex gap-2 mt-3">
            <Link
              :href="route(card.href)"
              class="text-xs font-medium hover:underline"
              :style="`color: ${card.color};`"
            >
              Manage &rarr;
            </Link>
            <span style="color: hsl(220, 5%, 46%);">·</span>
            <Link
              :href="route(card.createHref)"
              class="text-xs font-medium hover:underline"
              :style="`color: ${card.color};`"
            >
              Add new
            </Link>
          </div>
        </div>
      </div>

      <!-- Quick actions -->
      <div class="rounded-xl border p-5" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <h3 class="text-sm font-semibold mb-3" style="color: hsl(220, 14%, 10%);">Quick Actions</h3>
        <div class="flex flex-wrap gap-2">
          <Link
            :href="route('admin.profile.edit')"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md border transition-colors hover:bg-gray-50"
            style="border-color: hsl(30, 10%, 90%); color: hsl(220, 14%, 10%);"
          >
            Edit Profile
          </Link>
          <Link
            :href="route('admin.projects.create')"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md border transition-colors hover:bg-gray-50"
            style="border-color: hsl(30, 10%, 90%); color: hsl(220, 14%, 10%);"
          >
            Add Project
          </Link>
          <Link
            :href="route('admin.work-experiences.create')"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md border transition-colors hover:bg-gray-50"
            style="border-color: hsl(30, 10%, 90%); color: hsl(220, 14%, 10%);"
          >
            Add Work Experience
          </Link>
          <Link
            :href="route('admin.educations.create')"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md border transition-colors hover:bg-gray-50"
            style="border-color: hsl(30, 10%, 90%); color: hsl(220, 14%, 10%);"
          >
            Add Education
          </Link>
          <Link
            :href="route('home')"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md text-white transition-opacity"
            style="background-color: hsl(82, 77%, 40%);"
          >
            View Portfolio &rarr;
          </Link>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
