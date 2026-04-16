<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ skill: Object });
const isEditing = !!props.skill;

const form = useForm({
  name:       props.skill?.name       ?? '',
  category:   props.skill?.category   ?? 'other',
  icon_url:   props.skill?.icon_url   ?? '',
  color:      props.skill?.color      ?? 'bg-muted',
  sort_order: props.skill?.sort_order ?? 0,
  is_visible: props.skill?.is_visible ?? true,
});

const categories = [
  { value: 'language',  label: 'Programming Language' },
  { value: 'framework', label: 'Framework / Library' },
  { value: 'cloud',     label: 'Cloud / DevOps' },
  { value: 'database',  label: 'Database' },
  { value: 'tool',      label: 'Tool / Software' },
  { value: 'other',     label: 'Other' },
];

function submit() {
  if (isEditing) {
    form.put(route('admin.skills.update', props.skill.id));
  } else {
    form.post(route('admin.skills.store'));
  }
}
</script>

<template>
  <AdminLayout>
    <template #back>
      <Link :href="route('admin.skills.index')"
        class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Skills
      </Link>
    </template>

    <div class="max-w-lg space-y-5">
      <h2 class="text-xl font-bold font-display text-foreground">{{ isEditing ? 'Edit Skill' : 'Add Skill' }}</h2>

      <div class="section-card">
        <form @submit.prevent="submit" class="space-y-4">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Name *</label>
              <input v-model="form.name" type="text" required placeholder="e.g. Laravel" class="admin-input" :class="{'border-red-400': form.errors.name}"/>
              <p v-if="form.errors.name" class="admin-error">{{ form.errors.name }}</p>
            </div>
            <div>
              <label class="admin-label">Category *</label>
              <select v-model="form.category" class="admin-input">
                <option v-for="c in categories" :key="c.value" :value="c.value">{{ c.label }}</option>
              </select>
            </div>
          </div>

          <div>
            <label class="admin-label">Icon URL</label>
            <input v-model="form.icon_url" type="url" placeholder="https://cdn.jsdelivr.net/…/laravel.svg" class="admin-input"/>
            <p class="admin-hint">Use a CDN link (devicons, simpleicons.org) for a logo. Leave blank to show initial.</p>
            <div v-if="form.icon_url" class="mt-2 flex items-center gap-2">
              <img :src="form.icon_url" alt="preview" class="w-8 h-8 object-contain border border-border rounded p-0.5" @error="e => e.target.style.display='none'"/>
              <span class="text-xs text-muted-foreground">Preview</span>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Sort Order</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="admin-input"/>
            </div>
            <div class="flex items-center gap-2 pt-5">
              <input v-model="form.is_visible" type="checkbox" id="is_visible" class="w-4 h-4 rounded accent-primary"/>
              <label for="is_visible" class="text-sm text-foreground">Visible on portfolio</label>
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <Link :href="route('admin.skills.index')" class="admin-btn-outline">Cancel</Link>
            <button type="submit" :disabled="form.processing"
              class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-primary text-primary-foreground disabled:opacity-60 hover:bg-primary/90 transition-all">
              {{ form.processing ? 'Saving…' : (isEditing ? 'Update Skill' : 'Add Skill') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
