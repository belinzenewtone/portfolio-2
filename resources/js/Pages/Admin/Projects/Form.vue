<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ project: Object });
const isEditing = !!props.project;

const form = useForm({
  name:        props.project?.name        ?? '',
  description: props.project?.description ?? '',
  date:        props.project?.date        ? props.project.date.substring(0, 10) : '',
  project_url: props.project?.project_url ?? '',
  bg_color:    props.project?.bg_color    ?? 'bg-blue-100',
  sort_order:  props.project?.sort_order  ?? 0,
  is_visible:  props.project?.is_visible  ?? true,
  logo:        null,
});

const logoPreview = ref(null);

const bgColorOptions = [
  { value: 'bg-blue-100',   label: 'Blue' },
  { value: 'bg-sky-100',    label: 'Sky' },
  { value: 'bg-indigo-100', label: 'Indigo' },
  { value: 'bg-violet-100', label: 'Violet' },
  { value: 'bg-pink-100',   label: 'Pink' },
  { value: 'bg-green-100',  label: 'Green' },
  { value: 'bg-yellow-100', label: 'Yellow' },
  { value: 'bg-orange-100', label: 'Orange' },
  { value: 'bg-gray-100',   label: 'Gray' },
];

function onLogoChange(e) {
  const file = e.target.files[0];
  if (!file) return;
  form.logo = file;
  const reader = new FileReader();
  reader.onload = ev => { logoPreview.value = ev.target.result; };
  reader.readAsDataURL(file);
}

function submit() {
  if (isEditing) {
    form.post(route('admin.projects.update', props.project.id), { method: 'put' });
  } else {
    form.post(route('admin.projects.store'));
  }
}

function getLogoUrl(logo) {
  if (!logo) return null;
  return logo.startsWith('http') ? logo : '/storage/' + logo;
}
</script>

<template>
  <AdminLayout>
    <template #back>
      <Link :href="route('admin.projects.index')"
        class="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Projects
      </Link>
    </template>

    <div class="max-w-xl space-y-5">
      <h2 class="text-xl font-bold font-display text-foreground">{{ isEditing ? 'Edit Project' : 'New Project' }}</h2>

      <div class="section-card">
        <form @submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">

          <div>
            <label class="admin-label">Project Name *</label>
            <input v-model="form.name" type="text" required class="admin-input" :class="{'border-red-400': form.errors.name}"/>
            <p v-if="form.errors.name" class="admin-error">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="admin-label">Description</label>
            <textarea v-model="form.description" rows="3" class="admin-input resize-none"></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Date</label>
              <input v-model="form.date" type="date" class="admin-input"/>
            </div>
            <div>
              <label class="admin-label">Project URL</label>
              <input v-model="form.project_url" type="url" placeholder="https://…" class="admin-input" :class="{'border-red-400': form.errors.project_url}"/>
              <p v-if="form.errors.project_url" class="admin-error">{{ form.errors.project_url }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Background Colour</label>
              <select v-model="form.bg_color" class="admin-input">
                <option v-for="opt in bgColorOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
              </select>
            </div>
            <div>
              <label class="admin-label">Sort Order</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="admin-input"/>
            </div>
          </div>

          <!-- Logo upload -->
          <div>
            <label class="admin-label">Logo / Icon</label>
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center text-xs font-bold border border-border shrink-0" :class="form.bg_color || 'bg-blue-100'">
                <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-contain" alt="Preview"/>
                <img v-else-if="project?.logo_url" :src="getLogoUrl(project.logo_url)" class="w-full h-full object-contain" alt="Logo"/>
                <span v-else class="text-primary">{{ form.name ? form.name.charAt(0) : '?' }}</span>
              </div>
              <input type="file" accept="image/*" @change="onLogoChange"
                class="text-xs text-muted-foreground file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"/>
            </div>
            <p v-if="form.errors.logo" class="admin-error">{{ form.errors.logo }}</p>
          </div>

          <!-- Visible -->
          <div class="flex items-center gap-2">
            <input v-model="form.is_visible" type="checkbox" id="is_visible" class="w-4 h-4 rounded accent-primary"/>
            <label for="is_visible" class="text-sm text-foreground">Visible on portfolio</label>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <Link :href="route('admin.projects.index')" class="admin-btn-outline">Cancel</Link>
            <button type="submit" :disabled="form.processing"
              class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-primary text-primary-foreground disabled:opacity-60 hover:bg-primary/90 transition-all">
              {{ form.processing ? 'Saving…' : (isEditing ? 'Update Project' : 'Create Project') }}
            </button>
          </div>
        </form>
      </div>

    </div>
  </AdminLayout>
</template>
