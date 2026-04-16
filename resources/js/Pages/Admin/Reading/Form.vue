<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ item: Object });
const isEditing = !!props.item;

const form = useForm({
  title:        props.item?.title      ?? '',
  author:       props.item?.author     ?? '',
  cover_url:    props.item?.cover_url  ?? '',
  cover_image:  null,
  status:       props.item?.status     ?? 'want_to_read',
  year_read:    props.item?.year_read  ?? '',
  rating:       props.item?.rating     ?? '',
  notes:        props.item?.notes      ?? '',
  buy_url:      props.item?.buy_url    ?? '',
  sort_order:   props.item?.sort_order ?? 0,
});

const coverPreview = ref(props.item?.cover_url || null);

function onCoverChange(e) {
  const file = e.target.files[0];
  if (!file) return;
  form.cover_image = file;
  form.cover_url = ''; // clear URL field when uploading a file
  const reader = new FileReader();
  reader.onload = ev => { coverPreview.value = ev.target.result; };
  reader.readAsDataURL(file);
}

function submit() {
  if (isEditing) {
    form.post(route('admin.reading.update', props.item.id), { method: 'put' });
  } else {
    form.post(route('admin.reading.store'));
  }
}
</script>

<template>
  <AdminLayout>
    <template #back>
      <Link :href="route('admin.reading.index')"
        class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Reading
      </Link>
    </template>

    <div class="max-w-xl space-y-5">
      <h2 class="text-xl font-bold font-display text-foreground">
        {{ isEditing ? 'Edit Book' : 'Add Book' }}
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
              <label class="admin-label">Author</label>
              <input v-model="form.author" type="text" class="admin-input"/>
            </div>
            <div>
              <label class="admin-label">Status *</label>
              <select v-model="form.status" class="admin-input">
                <option value="want_to_read">Want to read</option>
                <option value="currently_reading">Currently reading</option>
                <option value="completed">Completed</option>
              </select>
            </div>
          </div>

          <!-- Cover image: upload or URL -->
          <div>
            <label class="admin-label">Cover Image</label>
            <div class="flex items-start gap-3">
              <!-- Preview -->
              <div class="w-14 h-20 rounded-lg overflow-hidden shrink-0 border border-border bg-muted flex items-center justify-center">
                <img v-if="coverPreview" :src="coverPreview" alt="Cover" class="w-full h-full object-cover"/>
                <span v-else class="text-2xl text-muted-foreground">📖</span>
              </div>
              <div class="flex-1 space-y-2">
                <div>
                  <label class="text-xs text-muted-foreground mb-1 block">Upload file</label>
                  <input type="file" accept="image/*" @change="onCoverChange"
                    class="text-xs text-muted-foreground file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"/>
                </div>
                <div>
                  <label class="text-xs text-muted-foreground mb-1 block">Or paste URL</label>
                  <input v-model="form.cover_url" type="url" placeholder="https://…" class="admin-input text-xs"
                    @input="coverPreview = form.cover_url || null"
                    :class="{'border-red-400': form.errors.cover_url}"/>
                </div>
              </div>
            </div>
            <p v-if="form.errors.cover_url" class="admin-error mt-1">{{ form.errors.cover_url }}</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="admin-label">Year Read</label>
              <input v-model.number="form.year_read" type="number" min="1900" max="2100" class="admin-input"/>
            </div>
            <div>
              <label class="admin-label">Rating (1–5)</label>
              <input v-model.number="form.rating" type="number" min="1" max="5" class="admin-input"/>
            </div>
            <div>
              <label class="admin-label">Sort Order</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="admin-input"/>
            </div>
          </div>

          <div>
            <label class="admin-label">Notes</label>
            <textarea v-model="form.notes" rows="3" class="admin-input resize-none" placeholder="Your thoughts on this book..."></textarea>
          </div>

          <div>
            <label class="admin-label">Buy / Info URL</label>
            <input v-model="form.buy_url" type="url" placeholder="https://amazon.com/…" class="admin-input" :class="{'border-red-400': form.errors.buy_url}"/>
            <p v-if="form.errors.buy_url" class="admin-error">{{ form.errors.buy_url }}</p>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <Link :href="route('admin.reading.index')" class="admin-btn-outline">Cancel</Link>
            <button type="submit" :disabled="form.processing"
              class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-primary text-primary-foreground disabled:opacity-60 hover:bg-primary/90 transition-all">
              {{ form.processing ? 'Saving…' : (isEditing ? 'Update' : 'Add Book') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
