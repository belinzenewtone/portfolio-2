<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ educations: Array });

function destroy(id) {
  if (!confirm('Delete this education entry?')) return;
  router.delete(route('admin.educations.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold font-display text-foreground">Education</h2>
          <p class="text-sm mt-0.5 text-muted-foreground">{{ educations.length }} entr{{ educations.length !== 1 ? 'ies' : 'y' }}</p>
        </div>
        <Link :href="route('admin.educations.create')" class="admin-btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Education
        </Link>
      </div>

      <div class="section-card !p-0">
        <div v-if="educations.length === 0" class="py-16 text-center text-muted-foreground">
          <div class="text-4xl mb-3">🎓</div>
          <p class="text-sm font-medium">No education entries yet</p>
          <Link :href="route('admin.educations.create')" class="text-xs mt-1 text-primary hover:underline">Add your first entry</Link>
        </div>

        <div class="overflow-x-auto" v-else><table class="w-full min-w-[520px] text-sm">
          <thead>
            <tr class="border-b border-border bg-muted">
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Institution</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Degree</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden sm:table-cell">Field</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden md:table-cell">Period</th>
              <th class="text-right px-4 py-3 text-xs font-semibold text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr v-for="edu in educations" :key="edu.id" class="hover:bg-muted/50 transition-colors">
              <td class="px-4 py-3 font-medium text-foreground">{{ edu.institution }}</td>
              <td class="px-4 py-3 text-foreground">{{ edu.degree }}</td>
              <td class="px-4 py-3 hidden sm:table-cell text-muted-foreground">{{ edu.field || '—' }}</td>
              <td class="px-4 py-3 hidden md:table-cell whitespace-nowrap text-muted-foreground">
                {{ edu.period_start ? new Date(edu.period_start).getFullYear() : '' }}
                –
                {{ edu.period_end ? new Date(edu.period_end).getFullYear() : 'Present' }}
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-3">
                  <Link :href="route('admin.educations.edit', edu.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                  <button @click="destroy(edu.id)" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table></div>
      </div>
    </div>
  </AdminLayout>
</template>
