<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ post: Object });
const isEditing = !!props.post;

const form = useForm({
  title:           props.post?.title           ?? '',
  slug:            props.post?.slug            ?? '',
  excerpt:         props.post?.excerpt         ?? '',
  content:         props.post?.content         ?? '',
  cover_image_url: props.post?.cover_image_url ?? '',
  external_url:    props.post?.external_url    ?? '',
  published_at:    props.post?.published_at    ? props.post.published_at.substring(0, 10) : '',
  is_published:    props.post?.is_published    ?? false,
  sort_order:      props.post?.sort_order      ?? 0,
});

function submit() {
  if (isEditing) {
    form.put(route('admin.blog.update', props.post.id));
  } else {
    form.post(route('admin.blog.store'));
  }
}
</script>

<template>
  <AdminLayout>
    <template #back>
      <Link :href="route('admin.blog.index')"
        class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Blog
      </Link>
    </template>

    <div class="max-w-2xl space-y-5">
      <h2 class="text-xl font-bold font-display text-foreground">
        {{ isEditing ? 'Edit Post' : 'New Post' }}
      </h2>

      <div class="section-card">
        <form @submit.prevent="submit" class="space-y-4">

          <div>
            <label class="admin-label">Title *</label>
            <input v-model="form.title" type="text" required class="admin-input" :class="{'border-red-400': form.errors.title}"/>
            <p v-if="form.errors.title" class="admin-error">{{ form.errors.title }}</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Slug</label>
              <input v-model="form.slug" type="text" placeholder="auto-generated from title" class="admin-input"/>
              <p class="admin-hint">Leave blank to auto-generate.</p>
            </div>
            <div>
              <label class="admin-label">Published Date</label>
              <input v-model="form.published_at" type="date" class="admin-input"/>
            </div>
          </div>

          <div>
            <label class="admin-label">Excerpt</label>
            <textarea v-model="form.excerpt" rows="2" class="admin-input resize-none" placeholder="Short summary shown in listings..."></textarea>
          </div>

          <div>
            <label class="admin-label">Content</label>
            <textarea v-model="form.content" rows="8" class="admin-input resize-y font-mono text-xs" placeholder="Full post content (Markdown or HTML)..."></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Cover Image URL</label>
              <input v-model="form.cover_image_url" type="url" placeholder="https://…" class="admin-input" :class="{'border-red-400': form.errors.cover_image_url}"/>
              <p v-if="form.errors.cover_image_url" class="admin-error">{{ form.errors.cover_image_url }}</p>
            </div>
            <div>
              <label class="admin-label">External URL</label>
              <input v-model="form.external_url" type="url" placeholder="https://… (links to external article)" class="admin-input" :class="{'border-red-400': form.errors.external_url}"/>
              <p v-if="form.errors.external_url" class="admin-error">{{ form.errors.external_url }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Sort Order</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="admin-input"/>
            </div>
            <div class="flex items-center gap-2 pt-5">
              <input v-model="form.is_published" type="checkbox" id="is_published" class="w-4 h-4 rounded accent-primary"/>
              <label for="is_published" class="text-sm text-foreground">Published</label>
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <Link :href="route('admin.blog.index')" class="admin-btn-outline">Cancel</Link>
            <button type="submit" :disabled="form.processing"
              class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-primary text-primary-foreground disabled:opacity-60 hover:bg-primary/90 transition-all">
              {{ form.processing ? 'Saving…' : (isEditing ? 'Update Post' : 'Create Post') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
