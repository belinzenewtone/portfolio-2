<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ event: Object });
const isEditing = !!props.event;

const form = useForm({
  title:       props.event?.title       ?? '',
  description: props.event?.description ?? '',
  event_date:  props.event?.event_date  ? props.event.event_date.substring(0, 10) : '',
  category:    props.event?.category    ?? 'career',
  emoji:       props.event?.emoji       ?? '📌',
  sort_order:  props.event?.sort_order  ?? 0,
});

function submit() {
  if (isEditing) {
    form.put(route('admin.timeline.update', props.event.id));
  } else {
    form.post(route('admin.timeline.store'));
  }
}
</script>

<template>
  <AdminLayout>
    <template #back>
      <Link :href="route('admin.timeline.index')"
        class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Timeline
      </Link>
    </template>

    <div class="max-w-xl space-y-5">
      <h2 class="text-xl font-bold font-display text-foreground">
        {{ isEditing ? 'Edit Event' : 'Add Event' }}
      </h2>

      <div class="section-card">
        <form @submit.prevent="submit" class="space-y-4">

          <div>
            <label class="admin-label">Title *</label>
            <input v-model="form.title" type="text" required class="admin-input" :class="{'border-red-400': form.errors.title}"/>
            <p v-if="form.errors.title" class="admin-error">{{ form.errors.title }}</p>
          </div>

          <div>
            <label class="admin-label">Description</label>
            <textarea v-model="form.description" rows="3" class="admin-input resize-none" placeholder="What happened?"></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-1">
              <label class="admin-label">Date *</label>
              <input v-model="form.event_date" type="date" required class="admin-input" :class="{'border-red-400': form.errors.event_date}"/>
              <p v-if="form.errors.event_date" class="admin-error">{{ form.errors.event_date }}</p>
            </div>
            <div>
              <label class="admin-label">Category *</label>
              <select v-model="form.category" class="admin-input">
                <option value="career">Career</option>
                <option value="education">Education</option>
                <option value="life">Life</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div>
              <label class="admin-label">Emoji</label>
              <input v-model="form.emoji" type="text" maxlength="4" class="admin-input text-center text-xl" placeholder="📌"/>
            </div>
          </div>

          <div>
            <label class="admin-label">Sort Order</label>
            <input v-model.number="form.sort_order" type="number" min="0" class="admin-input w-32"/>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <Link :href="route('admin.timeline.index')" class="admin-btn-outline">Cancel</Link>
            <button type="submit" :disabled="form.processing"
              class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-primary text-primary-foreground disabled:opacity-60 hover:bg-primary/90 transition-all">
              {{ form.processing ? 'Saving…' : (isEditing ? 'Update Event' : 'Add Event') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
