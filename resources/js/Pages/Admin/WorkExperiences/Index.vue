<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ workExperiences: Array });

function destroy(id) {
  if (!confirm('Delete this work experience?')) return;
  router.delete(route('admin.work-experiences.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold font-display text-foreground">Work Experience</h2>
          <p class="text-sm mt-0.5 text-muted-foreground">{{ workExperiences.length }} entr{{ workExperiences.length !== 1 ? 'ies' : 'y' }}</p>
        </div>
        <Link :href="route('admin.work-experiences.create')" class="admin-btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Experience
        </Link>
      </div>

      <div class="section-card !p-0">
        <div v-if="workExperiences.length === 0" class="py-16 text-center text-muted-foreground">
          <div class="text-4xl mb-3">💼</div>
          <p class="text-sm font-medium">No work experience yet</p>
          <Link :href="route('admin.work-experiences.create')" class="text-xs mt-1 text-primary hover:underline">Add your first entry</Link>
        </div>

        <div class="overflow-x-auto" v-else><table class="w-full min-w-[520px] text-sm">
          <thead>
            <tr class="border-b border-border bg-muted">
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Company</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Role</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden md:table-cell">Period</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden sm:table-cell">Type</th>
              <th class="text-right px-4 py-3 text-xs font-semibold text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr v-for="work in workExperiences" :key="work.id" class="hover:bg-muted/50 transition-colors">
              <td class="px-4 py-3 font-medium text-foreground">
                <div class="flex items-center gap-2">
                  {{ work.company }}
                  <span v-if="work.is_current" class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">Current</span>
                </div>
              </td>
              <td class="px-4 py-3 text-foreground">{{ work.role }}</td>
              <td class="px-4 py-3 hidden md:table-cell whitespace-nowrap text-muted-foreground">
                {{ work.period_start ? new Date(work.period_start).toLocaleDateString('en-GB', { month:'short', year:'numeric' }) : '' }}
                –
                {{ work.is_current ? 'Present' : (work.period_end ? new Date(work.period_end).toLocaleDateString('en-GB', { month:'short', year:'numeric' }) : '') }}
              </td>
              <td class="px-4 py-3 hidden sm:table-cell text-muted-foreground">{{ work.employment_type || '—' }}</td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-3">
                  <Link :href="route('admin.work-experiences.edit', work.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                  <button @click="destroy(work.id)" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table></div>
      </div>
    </div>
  </AdminLayout>
</template>
