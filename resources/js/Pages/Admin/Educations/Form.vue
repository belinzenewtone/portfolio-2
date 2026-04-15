<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  education: Object,
});

const isEditing = !!props.education;

const form = useForm({
  institution: props.education?.institution ?? '',
  degree: props.education?.degree ?? '',
  field: props.education?.field ?? '',
  description: props.education?.description ?? '',
  period_start: props.education?.period_start ? props.education.period_start.substring(0, 10) : '',
  period_end: props.education?.period_end ? props.education.period_end.substring(0, 10) : '',
  sort_order: props.education?.sort_order ?? 0,
  logo: null,
});

const logoPreview = ref(null);

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
    form.post(route('admin.educations.update', props.education.id), { method: 'put' });
  } else {
    form.post(route('admin.educations.store'));
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
          :href="route('admin.educations.index')"
          class="text-sm hover:underline"
          style="color: hsl(220, 5%, 46%);"
        >
          &larr; Educations
        </Link>
        <span style="color: hsl(220, 5%, 46%);">/</span>
        <h2 class="text-xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
          {{ isEditing ? 'Edit Education' : 'New Education' }}
        </h2>
      </div>

      <div class="rounded-xl border p-5" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <form @submit.prevent="submit" class="space-y-4">
          <!-- Institution -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Institution *</label>
            <input v-model="form.institution" type="text" required class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            <p v-if="form.errors.institution" class="text-xs text-red-600 mt-1">{{ form.errors.institution }}</p>
          </div>

          <!-- Degree + Field -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Degree *</label>
              <input v-model="form.degree" type="text" required placeholder="e.g. Bachelor of Science" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.degree" class="text-xs text-red-600 mt-1">{{ form.errors.degree }}</p>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Field of Study</label>
              <input v-model="form.field" type="text" placeholder="e.g. Computer Science" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Description</label>
            <textarea v-model="form.description" rows="3" class="w-full px-3 py-2 text-sm rounded-md border outline-none resize-none" style="border-color: hsl(30, 10%, 90%);"></textarea>
          </div>

          <!-- Period -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Start Date *</label>
              <input v-model="form.period_start" type="date" required class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.period_start" class="text-xs text-red-600 mt-1">{{ form.errors.period_start }}</p>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">End Date</label>
              <input v-model="form.period_end" type="date" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p class="text-xs mt-0.5" style="color: hsl(220, 5%, 46%);">Leave blank if ongoing.</p>
            </div>
          </div>

          <!-- Sort order + Logo -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Sort Order</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Institution Logo</label>
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-md overflow-hidden flex items-center justify-center text-xs font-bold flex-shrink-0" style="background-color: hsl(30, 15%, 93%); color: hsl(82, 77%, 28%);">
                  <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-contain" alt="Preview"/>
                  <img v-else-if="education?.logo_url" :src="getLogoUrl(education.logo_url)" class="w-full h-full object-contain" alt="Logo"/>
                  <span v-else>{{ form.institution ? form.institution.charAt(0) : '?' }}</span>
                </div>
                <input type="file" accept="image/*" @change="onLogoChange" class="text-xs text-gray-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-lime-50 file:text-lime-700 hover:file:bg-lime-100"/>
              </div>
              <p v-if="form.errors.logo" class="text-xs text-red-600 mt-1">{{ form.errors.logo }}</p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-2 pt-2">
            <Link
              :href="route('admin.educations.index')"
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
              {{ form.processing ? 'Saving...' : (isEditing ? 'Update Education' : 'Create Education') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
