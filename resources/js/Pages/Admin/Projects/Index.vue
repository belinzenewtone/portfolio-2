<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ projects: Array });

function destroy(id) {
  if (!confirm('Delete this project?')) return;
  router.delete(route('admin.projects.destroy', id));
}

function getLogoUrl(logo) {
  if (!logo) return null;
  return logo.startsWith('http') ? logo : '/storage/' + logo;
}
</script>

<template>
  <AdminLayout>
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold font-display text-foreground">Projects</h2>
          <p class="text-sm mt-0.5 text-muted-foreground">{{ projects.length }} project{{ projects.length !== 1 ? 's' : '' }}</p>
        </div>
        <Link :href="route('admin.projects.create')" class="admin-btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Project
        </Link>
      </div>

      <div class="section-card !p-0 overflow-hidden">
        <div v-if="projects.length === 0" class="py-16 text-center text-muted-foreground">
          <div class="text-4xl mb-3">📁</div>
          <p class="text-sm font-medium">No projects yet</p>
          <Link :href="route('admin.projects.create')" class="text-xs mt-1 text-primary hover:underline">Add your first project</Link>
        </div>

        <table v-else class="w-full text-sm">
          <thead>
            <tr class="border-b border-border bg-muted">
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Logo</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Name</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden sm:table-cell">Description</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden md:table-cell">Date</th>
              <th class="text-center px-4 py-3 text-xs font-semibold text-muted-foreground">Visible</th>
              <th class="text-right px-4 py-3 text-xs font-semibold text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr v-for="project in projects" :key="project.id" class="hover:bg-muted/50 transition-colors">
              <td class="px-4 py-3">
                <div class="w-9 h-9 rounded-xl overflow-hidden flex items-center justify-center text-xs font-bold border border-border" :class="project.bg_color || 'bg-blue-50'">
                  <img v-if="project.logo_url" :src="getLogoUrl(project.logo_url)" :alt="project.name" class="w-full h-full object-contain"/>
                  <span v-else class="text-primary">{{ project.name.charAt(0) }}</span>
                </div>
              </td>
              <td class="px-4 py-3 font-medium text-foreground">{{ project.name }}</td>
              <td class="px-4 py-3 hidden sm:table-cell max-w-xs truncate text-muted-foreground">{{ project.description || '—' }}</td>
              <td class="px-4 py-3 hidden md:table-cell whitespace-nowrap text-muted-foreground">
                {{ project.date ? new Date(project.date).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' }) : '—' }}
              </td>
              <td class="px-4 py-3 text-center">
                <span class="inline-block w-2 h-2 rounded-full" :class="project.is_visible ? 'bg-primary' : 'bg-border'" :title="project.is_visible ? 'Visible' : 'Hidden'"></span>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-3">
                  <Link :href="route('admin.projects.edit', project.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                  <button @click="destroy(project.id)" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>
