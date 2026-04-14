<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  workExperiences: Array,
});

function destroy(id) {
  if (!confirm('Delete this work experience?')) return;
  router.delete(route('admin.work-experiences.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
            Work Experiences
          </h2>
          <p class="text-sm mt-0.5" style="color: hsl(220, 5%, 46%);">{{ workExperiences.length }} entr{{ workExperiences.length !== 1 ? 'ies' : 'y' }}</p>
        </div>
        <Link
          :href="route('admin.work-experiences.create')"
          class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-md text-white"
          style="background-color: hsl(82, 77%, 40%);"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Experience
        </Link>
      </div>

      <div class="rounded-xl border overflow-hidden" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <div v-if="workExperiences.length === 0" class="py-16 text-center" style="color: hsl(220, 5%, 46%);">
          <div class="text-4xl mb-3">💼</div>
          <p class="text-sm font-medium">No work experiences yet</p>
          <Link :href="route('admin.work-experiences.create')" class="text-xs mt-1 hover:underline" style="color: hsl(82, 77%, 40%);">Add your first entry</Link>
        </div>

        <table v-else class="w-full text-sm">
          <thead>
            <tr style="border-bottom: 1px solid hsl(30, 10%, 90%); background-color: hsl(30, 10%, 94%);">
              <th class="text-left px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Company</th>
              <th class="text-left px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Role</th>
              <th class="text-left px-4 py-2.5 text-xs font-semibold hidden md:table-cell" style="color: hsl(220, 5%, 46%);">Period</th>
              <th class="text-left px-4 py-2.5 text-xs font-semibold hidden sm:table-cell" style="color: hsl(220, 5%, 46%);">Type</th>
              <th class="text-right px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="work in workExperiences"
              :key="work.id"
              style="border-bottom: 1px solid hsl(30, 10%, 90%);"
              class="last:border-0 hover:bg-gray-50 transition-colors"
            >
              <td class="px-4 py-3 font-medium" style="color: hsl(220, 14%, 10%);">{{ work.company }}</td>
              <td class="px-4 py-3" style="color: hsl(220, 14%, 10%);">{{ work.role }}</td>
              <td class="px-4 py-3 hidden md:table-cell whitespace-nowrap" style="color: hsl(220, 5%, 46%);">
                <span v-if="work.is_current" class="inline-flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-lime-500 inline-block"></span>
                  Current
                </span>
                <span v-else>
                  {{ work.period_start ? new Date(work.period_start).toLocaleDateString('en-GB', {month:'short', year:'numeric'}) : '' }}
                  –
                  {{ work.period_end ? new Date(work.period_end).toLocaleDateString('en-GB', {month:'short', year:'numeric'}) : '' }}
                </span>
              </td>
              <td class="px-4 py-3 hidden sm:table-cell" style="color: hsl(220, 5%, 46%);">
                {{ work.employment_type || '—' }}
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-2">
                  <Link
                    :href="route('admin.work-experiences.edit', work.id)"
                    class="text-xs font-medium hover:underline"
                    style="color: hsl(82, 77%, 40%);"
                  >Edit</Link>
                  <button
                    @click="destroy(work.id)"
                    class="text-xs font-medium hover:underline text-red-500"
                  >Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>
