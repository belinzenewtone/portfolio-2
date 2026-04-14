<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  projects: Array,
});

function destroy(id) {
  if (!confirm('Delete this project?')) return;
  router.delete(route('admin.projects.destroy', id));
}

function getLogoUrl(logo) {
  if (!logo) return null;
  if (logo.startsWith('http')) return logo;
  return '/storage/' + logo;
}
</script>

<template>
  <AdminLayout>
    <div>
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
            Projects
          </h2>
          <p class="text-sm mt-0.5" style="color: hsl(220, 5%, 46%);">{{ projects.length }} project{{ projects.length !== 1 ? 's' : '' }}</p>
        </div>
        <Link
          :href="route('admin.projects.create')"
          class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-md text-white"
          style="background-color: hsl(82, 77%, 40%);"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Project
        </Link>
      </div>

      <!-- Table -->
      <div class="rounded-xl border overflow-hidden" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <div v-if="projects.length === 0" class="py-16 text-center" style="color: hsl(220, 5%, 46%);">
          <div class="text-4xl mb-3">📁</div>
          <p class="text-sm font-medium">No projects yet</p>
          <Link :href="route('admin.projects.create')" class="text-xs mt-1 hover:underline" style="color: hsl(82, 77%, 40%);">Add your first project</Link>
        </div>

        <table v-else class="w-full text-sm">
          <thead>
            <tr style="border-bottom: 1px solid hsl(30, 10%, 90%); background-color: hsl(30, 10%, 94%);">
              <th class="text-left px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Logo</th>
              <th class="text-left px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Name</th>
              <th class="text-left px-4 py-2.5 text-xs font-semibold hidden sm:table-cell" style="color: hsl(220, 5%, 46%);">Description</th>
              <th class="text-left px-4 py-2.5 text-xs font-semibold hidden md:table-cell" style="color: hsl(220, 5%, 46%);">Date</th>
              <th class="text-center px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Visible</th>
              <th class="text-right px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="project in projects"
              :key="project.id"
              style="border-bottom: 1px solid hsl(30, 10%, 90%);"
              class="last:border-0 hover:bg-gray-50 transition-colors"
            >
              <td class="px-4 py-3">
                <div class="w-8 h-8 rounded-md overflow-hidden flex items-center justify-center text-xs font-bold" :class="project.bg_color || 'bg-lime-100'" style="color: hsl(82, 77%, 28%);">
                  <img v-if="project.logo_url" :src="getLogoUrl(project.logo_url)" :alt="project.name" class="w-full h-full object-contain"/>
                  <span v-else>{{ project.name.charAt(0) }}</span>
                </div>
              </td>
              <td class="px-4 py-3 font-medium" style="color: hsl(220, 14%, 10%);">{{ project.name }}</td>
              <td class="px-4 py-3 hidden sm:table-cell max-w-xs truncate" style="color: hsl(220, 5%, 46%);">
                {{ project.description || '—' }}
              </td>
              <td class="px-4 py-3 hidden md:table-cell whitespace-nowrap" style="color: hsl(220, 5%, 46%);">
                {{ project.date ? new Date(project.date).toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'}) : '—' }}
              </td>
              <td class="px-4 py-3 text-center">
                <span
                  class="inline-block w-5 h-5 rounded-full"
                  :style="project.is_visible ? 'background-color: #65a30d;' : 'background-color: #d1d5db;'"
                  :title="project.is_visible ? 'Visible' : 'Hidden'"
                ></span>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-2">
                  <Link
                    :href="route('admin.projects.edit', project.id)"
                    class="text-xs font-medium hover:underline"
                    style="color: hsl(82, 77%, 40%);"
                  >
                    Edit
                  </Link>
                  <button
                    @click="destroy(project.id)"
                    class="text-xs font-medium hover:underline text-red-500"
                  >
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>
