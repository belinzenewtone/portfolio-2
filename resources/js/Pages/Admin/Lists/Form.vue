<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ list: Object });
const isEditing = !!props.list;

const form = useForm({
  title:        props.list?.title        ?? '',
  description:  props.list?.description  ?? '',
  emoji:        props.list?.emoji        ?? '📋',
  is_published: props.list?.is_published ?? true,
  sort_order:   props.list?.sort_order   ?? 0,
  items:        props.list?.items?.map(i => ({ text: i.text, url: i.url || '', note: i.note || '' })) ?? [],
});

function addItem() {
  form.items.push({ text: '', url: '', note: '' });
}

function removeItem(index) {
  form.items.splice(index, 1);
}

function submit() {
  if (isEditing) {
    form.put(route('admin.lists.update', props.list.id));
  } else {
    form.post(route('admin.lists.store'));
  }
}
</script>

<template>
  <AdminLayout>
    <template #back>
      <Link :href="route('admin.lists.index')"
        class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Lists
      </Link>
    </template>

    <div class="max-w-2xl space-y-5">
      <h2 class="text-xl font-bold font-display text-foreground">
        {{ isEditing ? 'Edit List' : 'New List' }}
      </h2>

      <div class="section-card">
        <form @submit.prevent="submit" class="space-y-4">

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
              <label class="admin-label">Title *</label>
              <input v-model="form.title" type="text" required class="admin-input" :class="{'border-red-400': form.errors.title}"/>
              <p v-if="form.errors.title" class="admin-error">{{ form.errors.title }}</p>
            </div>
            <div>
              <label class="admin-label">Emoji</label>
              <input v-model="form.emoji" type="text" maxlength="4" class="admin-input text-center text-xl" placeholder="📋"/>
            </div>
          </div>

          <div>
            <label class="admin-label">Description</label>
            <textarea v-model="form.description" rows="2" class="admin-input resize-none" placeholder="What's in this list?"></textarea>
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

          <!-- Items -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="admin-label !mb-0">Items</label>
              <button type="button" @click="addItem"
                class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add item
              </button>
            </div>

            <div v-if="form.items.length === 0" class="text-xs text-muted-foreground py-3 text-center border border-dashed border-border rounded-lg">
              No items yet — click "Add item" to start.
            </div>

            <div class="space-y-2">
              <div v-for="(item, i) in form.items" :key="i"
                class="grid grid-cols-1 sm:grid-cols-[1fr_1fr_auto] gap-2 p-3 bg-muted/40 rounded-lg border border-border">
                <div>
                  <input v-model="item.text" type="text" placeholder="Item text *" required
                    class="admin-input text-xs"/>
                </div>
                <div>
                  <input v-model="item.url" type="url" placeholder="URL (optional)" class="admin-input text-xs"/>
                </div>
                <button type="button" @click="removeItem(i)"
                  class="self-center p-1.5 rounded-lg text-red-400 hover:bg-red-500/10 transition-colors">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
                <div class="sm:col-span-2">
                  <input v-model="item.note" type="text" placeholder="Note (optional)" class="admin-input text-xs"/>
                </div>
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <Link :href="route('admin.lists.index')" class="admin-btn-outline">Cancel</Link>
            <button type="submit" :disabled="form.processing"
              class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-primary text-primary-foreground disabled:opacity-60 hover:bg-primary/90 transition-all">
              {{ form.processing ? 'Saving…' : (isEditing ? 'Update List' : 'Create List') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
