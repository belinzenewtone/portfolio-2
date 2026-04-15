<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ events: Array });

const catColor = {
  career:    'bg-primary/10 text-primary border-primary/20',
  education: 'bg-violet-500/10 text-violet-600 border-violet-500/20',
  life:      'bg-green-500/10 text-green-600 border-green-500/20',
  other:     'bg-muted text-muted-foreground border-border',
};

function destroy(id) {
  if (!confirm('Delete this event?')) return;
  router.delete(route('admin.timeline.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold font-display text-foreground">Timeline</h2>
          <p class="text-sm mt-0.5 text-muted-foreground">{{ events.length }} event{{ events.length !== 1 ? 's' : '' }}</p>
        </div>
        <Link :href="route('admin.timeline.create')" class="admin-btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Event
        </Link>
      </div>

      <div class="section-card !p-0">
        <div v-if="events.length === 0" class="py-16 text-center text-muted-foreground">
          <div class="text-4xl mb-3">📌</div>
          <p class="text-sm font-medium">No timeline events yet</p>
          <Link :href="route('admin.timeline.create')" class="text-xs mt-1 text-primary hover:underline">Add your first event</Link>
        </div>

        <div class="overflow-x-auto" v-else><table class="w-full min-w-[520px] text-sm">
          <thead>
            <tr class="border-b border-border bg-muted/60">
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Event</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden sm:table-cell">Date</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden md:table-cell">Category</th>
              <th class="text-right px-4 py-3 text-xs font-semibold text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr v-for="event in events" :key="event.id" class="hover:bg-muted/40 transition-colors">
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <span class="text-lg">{{ event.emoji || '📌' }}</span>
                  <div>
                    <div class="font-medium text-foreground">{{ event.title }}</div>
                    <div v-if="event.description" class="text-xs text-muted-foreground truncate max-w-xs">{{ event.description }}</div>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3 hidden sm:table-cell text-muted-foreground text-xs whitespace-nowrap">
                {{ event.event_date ? new Date(event.event_date).toLocaleDateString('en-GB', { month:'short', year:'numeric' }) : '—' }}
              </td>
              <td class="px-4 py-3 hidden md:table-cell">
                <span :class="catColor[event.category] || catColor.other"
                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border capitalize">
                  {{ event.category }}
                </span>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-3">
                  <Link :href="route('admin.timeline.edit', event.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                  <button @click="destroy(event.id)" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table></div>
      </div>
    </div>
  </AdminLayout>
</template>
