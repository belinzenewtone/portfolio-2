<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  project: Object,
});

const isEditing = !!props.project;

const form = useForm({
  name: props.project?.name ?? '',
  description: props.project?.description ?? '',
  date: props.project?.date ? props.project.date.substring(0, 10) : '',
  project_url: props.project?.project_url ?? '',
  bg_color: props.project?.bg_color ?? 'bg-lime-100',
  sort_order: props.project?.sort_order ?? 0,
  is_visible: props.project?.is_visible ?? true,
  logo: null,
});

const logoPreview = ref(null);

const bgColorOptions = [
  { value: 'bg-lime-100', label: 'Lime Green' },
  { value: 'bg-blue-100', label: 'Blue' },
  { value: 'bg-purple-100', label: 'Purple' },
  { value: 'bg-yellow-100', label: 'Yellow' },
  { value: 'bg-pink-100', label: 'Pink' },
  { value: 'bg-orange-100', label: 'Orange' },
  { value: 'bg-teal-100', label: 'Teal' },
  { value: 'bg-gray-100', label: 'Gray' },
];

function onLogoChange(e) {
  const file = e.target.files[0];
  if (!file) return;
  form.logo = file;
  const reader = new FileReader();
  reader.onload = (ev) => { logoPreview.value = ev.target.result; };
  reader.readAsDataURL(file);
}

function submit() {
  if (isEditing) {
    form.post(route('admin.projects.update', props.project.id), {
      method: 'put',
    });
  } else {
    form.post(route('admin.projects.store'));
  }
}

function getLogoUrl(logo) {
  if (!logo) return null;
  if (logo.startsWith('http')) return logo;
  return '/storage/' + logo;
}
</script>

<template>
  <AdminLayout>
    <div class="max-w-xl">
      <div class="flex items-center gap-3 mb-6">
        <Link
          :href="route('admin.projects.index')"
          class="text-sm hover:underline"
          style="color: hsl(220, 5%, 46%);"
        >
          &larr; Projects
        </Link>
        <span style="color: hsl(220, 5%, 46%);">/</span>
        <h2 class="text-xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
          {{ isEditing ? 'Edit Project' : 'New Project' }}
        </h2>
      </div>

      <div class="rounded-xl border p-5" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <form @submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">
          <!-- Name -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Project Name *</label>
            <input v-model="form.name" type="text" required class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Description</label>
            <textarea v-model="form.description" rows="3" class="w-full px-3 py-2 text-sm rounded-md border outline-none resize-none" style="border-color: hsl(30, 10%, 90%);"></textarea>
          </div>

          <!-- Date + URL -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Date</label>
              <input v-model="form.date" type="date" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Project URL</label>
              <input v-model="form.project_url" type="url" placeholder="https://..." class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.project_url" class="text-xs text-red-600 mt-1">{{ form.errors.project_url }}</p>
            </div>
          </div>

          <!-- BG Color + Sort Order -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Background Color</label>
              <select v-model="form.bg_color" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);">
                <option v-for="opt in bgColorOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Sort Order</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            </div>
          </div>

          <!-- Logo upload -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Logo / Icon</label>
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-md overflow-hidden flex items-center justify-center text-xs font-bold flex-shrink-0" :class="form.bg_color || 'bg-lime-100'" style="color: hsl(82, 77%, 28%);">
                <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-contain" alt="Preview"/>
                <img v-else-if="project?.logo_url" :src="getLogoUrl(project.logo_url)" class="w-full h-full object-contain" alt="Logo"/>
                <span v-else>{{ form.name ? form.name.charAt(0) : '?' }}</span>
              </div>
              <input
                type="file"
                accept="image/*"
                @change="onLogoChange"
                class="text-xs text-gray-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-lime-50 file:text-lime-700 hover:file:bg-lime-100"
              />
            </div>
            <p v-if="form.errors.logo" class="text-xs text-red-600 mt-1">{{ form.errors.logo }}</p>
          </div>

          <!-- Visible checkbox -->
          <div class="flex items-center gap-2">
            <input
              v-model="form.is_visible"
              type="checkbox"
              id="is_visible"
              class="w-4 h-4 rounded"
              style="accent-color: hsl(82, 77%, 40%);"
            />
            <label for="is_visible" class="text-sm" style="color: hsl(220, 14%, 10%);">Visible on portfolio</label>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-2 pt-2">
            <Link
              :href="route('admin.projects.index')"
              class="px-4 py-2 text-sm font-medium rounded-md border"
              style="border-color: hsl(30, 10%, 90%); color: hsl(220, 14%, 10%);"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-5 py-2 text-sm font-semibold rounded-md text-white disabled:opacity-60"
              style="background-color: hsl(82, 77%, 40%);"
            >
              {{ form.processing ? 'Saving...' : (isEditing ? 'Update Project' : 'Create Project') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
