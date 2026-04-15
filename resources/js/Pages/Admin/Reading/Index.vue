<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ items: Array });

const statusLabel = { want_to_read: 'Want to read', currently_reading: 'Reading', completed: 'Completed' };
const statusColor = {
  want_to_read:      'bg-muted text-muted-foreground border-border',
  currently_reading: 'bg-primary/10 text-primary border-primary/20',
  completed:         'bg-green-500/10 text-green-600 border-green-500/20',
};

function destroy(id) {
  if (!confirm('Delete this reading item?')) return;
  router.delete(route('admin.reading.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold font-display text-foreground">Reading</h2>
          <p class="text-sm mt-0.5 text-muted-foreground">{{ items.length }} item{{ items.length !== 1 ? 's' : '' }}</p>
        </div>
        <Link :href="route('admin.reading.create')" class="admin-btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Book
        </Link>
      </div>

      <div class="section-card !p-0">
        <div v-if="items.length === 0" class="py-16 text-center text-muted-foreground">
          <div class="text-4xl mb-3">📚</div>
          <p class="text-sm font-medium">No reading items yet</p>
          <Link :href="route('admin.reading.create')" class="text-xs mt-1 text-primary hover:underline">Add your first book</Link>
        </div>

        <div class="overflow-x-auto" v-else><table class="w-full min-w-[520px] text-sm">
          <thead>
            <tr class="border-b border-border bg-muted/60">
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Book</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden sm:table-cell">Author</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden md:table-cell">Status</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden md:table-cell">Rating</th>
              <th class="text-right px-4 py-3 text-xs font-semibold text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr v-for="item in items" :key="item.id" class="hover:bg-muted/40 transition-colors">
              <td class="px-4 py-3">
                <div class="font-medium text-foreground">{{ item.title }}</div>
                <div v-if="item.year_read" class="text-xs text-muted-foreground mt-0.5">{{ item.year_read }}</div>
              </td>
              <td class="px-4 py-3 hidden sm:table-cell text-muted-foreground">{{ item.author || '—' }}</td>
              <td class="px-4 py-3 hidden md:table-cell">
                <span :class="statusColor[item.status]"
                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border">
                  {{ statusLabel[item.status] }}
                </span>
              </td>
              <td class="px-4 py-3 hidden md:table-cell text-muted-foreground text-xs">
                {{ item.rating ? '★'.repeat(item.rating) + '☆'.repeat(5 - item.rating) : '—' }}
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-3">
                  <Link :href="route('admin.reading.edit', item.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                  <button @click="destroy(item.id)" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table></div>
      </div>
    </div>
  </AdminLayout>
</template>
