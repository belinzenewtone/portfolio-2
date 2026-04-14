<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  educations: Array,
});

function destroy(id) {
  if (!confirm('Delete this education entry?')) return;
  router.delete(route('admin.educations.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
            Educations
          </h2>
          <p class="text-sm mt-0.5" style="color: hsl(220, 5%, 46%);">{{ educations.length }} entr{{ educations.length !== 1 ? 'ies' : 'y' }}</p>
        </div>
        <Link
          :href="route('admin.educations.create')"
          class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-md text-white"
          style="background-color: hsl(82, 77%, 40%);"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Education
        </Link>
      </div>

      <div class="rounded-xl border overflow-hidden" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <div v-if="educations.length === 0" class="py-16 text-center" style="color: hsl(220, 5%, 46%);">
          <div class="text-4xl mb-3">🎓</div>
          <p class="text-sm font-medium">No education entries yet</p>
          <Link :href="route('admin.educations.create')" class="text-xs mt-1 hover:underline" style="color: hsl(82, 77%, 40%);">Add your first entry</Link>
        </div>

        <table v-else class="w-full text-sm">
          <thead>
            <tr style="border-bottom: 1px solid hsl(30, 10%, 90%); background-color: hsl(30, 10%, 94%);">
              <th class="text-left px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Institution</th>
              <th class="text-left px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Degree</th>
              <th class="text-left px-4 py-2.5 text-xs font-semibold hidden sm:table-cell" style="color: hsl(220, 5%, 46%);">Field</th>
              <th class="text-left px-4 py-2.5 text-xs font-semibold hidden md:table-cell" style="color: hsl(220, 5%, 46%);">Period</th>
              <th class="text-right px-4 py-2.5 text-xs font-semibold" style="color: hsl(220, 5%, 46%);">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="edu in educations"
              :key="edu.id"
              style="border-bottom: 1px solid hsl(30, 10%, 90%);"
              class="last:border-0 hover:bg-gray-50 transition-colors"
            >
              <td class="px-4 py-3 font-medium" style="color: hsl(220, 14%, 10%);">{{ edu.institution }}</td>
              <td class="px-4 py-3" style="color: hsl(220, 14%, 10%);">{{ edu.degree }}</td>
              <td class="px-4 py-3 hidden sm:table-cell" style="color: hsl(220, 5%, 46%);">{{ edu.field || '—' }}</td>
              <td class="px-4 py-3 hidden md:table-cell whitespace-nowrap" style="color: hsl(220, 5%, 46%);">
                {{ edu.period_start ? new Date(edu.period_start).getFullYear() : '' }}
                –
                {{ edu.period_end ? new Date(edu.period_end).getFullYear() : 'Present' }}
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-2">
                  <Link
                    :href="route('admin.educations.edit', edu.id)"
                    class="text-xs font-medium hover:underline"
                    style="color: hsl(82, 77%, 40%);"
                  >Edit</Link>
                  <button
                    @click="destroy(edu.id)"
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
