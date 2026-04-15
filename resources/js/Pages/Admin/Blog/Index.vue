<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ posts: Array });

function destroy(id) {
  if (!confirm('Delete this post?')) return;
  router.delete(route('admin.blog.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold font-display text-foreground">Blog</h2>
          <p class="text-sm mt-0.5 text-muted-foreground">{{ posts.length }} post{{ posts.length !== 1 ? 's' : '' }}</p>
        </div>
        <Link :href="route('admin.blog.create')" class="admin-btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          New Post
        </Link>
      </div>

      <div class="section-card !p-0 overflow-hidden">
        <div v-if="posts.length === 0" class="py-16 text-center text-muted-foreground">
          <div class="text-4xl mb-3">✍️</div>
          <p class="text-sm font-medium">No blog posts yet</p>
          <Link :href="route('admin.blog.create')" class="text-xs mt-1 text-primary hover:underline">Write your first post</Link>
        </div>

        <table v-else class="w-full text-sm">
          <thead>
            <tr class="border-b border-border bg-muted/60">
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Title</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden sm:table-cell">Published</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden md:table-cell">Status</th>
              <th class="text-right px-4 py-3 text-xs font-semibold text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr v-for="post in posts" :key="post.id" class="hover:bg-muted/40 transition-colors">
              <td class="px-4 py-3">
                <div class="font-medium text-foreground truncate max-w-xs">{{ post.title }}</div>
                <div v-if="post.excerpt" class="text-xs text-muted-foreground mt-0.5 truncate max-w-xs">{{ post.excerpt }}</div>
              </td>
              <td class="px-4 py-3 hidden sm:table-cell text-muted-foreground text-xs">
                {{ post.published_at ? new Date(post.published_at).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' }) : '—' }}
              </td>
              <td class="px-4 py-3 hidden md:table-cell">
                <span :class="post.is_published
                  ? 'bg-green-500/10 text-green-600 border-green-500/20'
                  : 'bg-muted text-muted-foreground border-border'"
                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border">
                  {{ post.is_published ? 'Published' : 'Draft' }}
                </span>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-3">
                  <Link :href="route('admin.blog.edit', post.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                  <button @click="destroy(post.id)" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>
