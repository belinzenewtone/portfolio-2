<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ lists: Array });

function destroy(id) {
  if (!confirm('Delete this list and all its items?')) return;
  router.delete(route('admin.lists.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold font-display text-foreground">Lists</h2>
          <p class="text-sm mt-0.5 text-muted-foreground">{{ lists.length }} list{{ lists.length !== 1 ? 's' : '' }}</p>
        </div>
        <Link :href="route('admin.lists.create')" class="admin-btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          New List
        </Link>
      </div>

      <div class="section-card !p-0 overflow-hidden">
        <div v-if="lists.length === 0" class="py-16 text-center text-muted-foreground">
          <div class="text-4xl mb-3">📋</div>
          <p class="text-sm font-medium">No lists yet</p>
          <Link :href="route('admin.lists.create')" class="text-xs mt-1 text-primary hover:underline">Create your first list</Link>
        </div>

        <table v-else class="w-full text-sm">
          <thead>
            <tr class="border-b border-border bg-muted/60">
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">List</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden sm:table-cell">Items</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden md:table-cell">Status</th>
              <th class="text-right px-4 py-3 text-xs font-semibold text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr v-for="list in lists" :key="list.id" class="hover:bg-muted/40 transition-colors">
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <span class="text-lg">{{ list.emoji || '📋' }}</span>
                  <div>
                    <div class="font-medium text-foreground">{{ list.title }}</div>
                    <div v-if="list.description" class="text-xs text-muted-foreground truncate max-w-xs">{{ list.description }}</div>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3 hidden sm:table-cell text-muted-foreground">{{ list.items_count }} item{{ list.items_count !== 1 ? 's' : '' }}</td>
              <td class="px-4 py-3 hidden md:table-cell">
                <span :class="list.is_published ? 'bg-green-500/10 text-green-600 border-green-500/20' : 'bg-muted text-muted-foreground border-border'"
                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border">
                  {{ list.is_published ? 'Published' : 'Hidden' }}
                </span>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-3">
                  <Link :href="route('admin.lists.edit', list.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                  <button @click="destroy(list.id)" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>
