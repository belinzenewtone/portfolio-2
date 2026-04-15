<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
  workExperience: Object,
});

const isEditing = !!props.workExperience;

const form = useForm({
  company: props.workExperience?.company ?? '',
  role: props.workExperience?.role ?? '',
  description: props.workExperience?.description ?? '',
  period_start: props.workExperience?.period_start ? props.workExperience.period_start.substring(0, 10) : '',
  period_end: props.workExperience?.period_end ? props.workExperience.period_end.substring(0, 10) : '',
  is_current: props.workExperience?.is_current ?? false,
  location: props.workExperience?.location ?? '',
  employment_type: props.workExperience?.employment_type ?? 'Full-time',
  sort_order: props.workExperience?.sort_order ?? 0,
  logo: null,
});

const logoPreview = ref(null);

const employmentTypes = ['Full-time', 'Part-time', 'Internship', 'Contract', 'Freelance'];

watch(() => form.is_current, (val) => {
  if (val) form.period_end = '';
});

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
    form.post(route('admin.work-experiences.update', props.workExperience.id), { method: 'put' });
  } else {
    form.post(route('admin.work-experiences.store'));
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
          :href="route('admin.work-experiences.index')"
          class="text-sm hover:underline"
          style="color: hsl(220, 5%, 46%);"
        >
          &larr; Work Experiences
        </Link>
        <span style="color: hsl(220, 5%, 46%);">/</span>
        <h2 class="text-xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
          {{ isEditing ? 'Edit Experience' : 'New Experience' }}
        </h2>
      </div>

      <div class="rounded-xl border p-5" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <form @submit.prevent="submit" class="space-y-4">
          <!-- Company + Role -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Company *</label>
              <input v-model="form.company" type="text" required class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.company" class="text-xs text-red-600 mt-1">{{ form.errors.company }}</p>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Role / Position *</label>
              <input v-model="form.role" type="text" required class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.role" class="text-xs text-red-600 mt-1">{{ form.errors.role }}</p>
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Description</label>
            <textarea v-model="form.description" rows="4" class="w-full px-3 py-2 text-sm rounded-md border outline-none resize-none" style="border-color: hsl(30, 10%, 90%);"></textarea>
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
              <input
                v-model="form.period_end"
                type="date"
                :disabled="form.is_current"
                class="w-full px-3 py-2 text-sm rounded-md border outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                style="border-color: hsl(30, 10%, 90%);"
              />
            </div>
          </div>

          <!-- Is current -->
          <div class="flex items-center gap-2">
            <input
              v-model="form.is_current"
              type="checkbox"
              id="is_current"
              class="w-4 h-4 rounded"
              style="accent-color: hsl(82, 77%, 40%);"
            />
            <label for="is_current" class="text-sm" style="color: hsl(220, 14%, 10%);">I currently work here</label>
          </div>

          <!-- Location + Employment type -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Location</label>
              <input v-model="form.location" type="text" placeholder="e.g. Nairobi, Kenya · On-site" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Employment Type</label>
              <select v-model="form.employment_type" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);">
                <option v-for="type in employmentTypes" :key="type" :value="type">{{ type }}</option>
              </select>
            </div>
          </div>

          <!-- Sort order + Logo -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Sort Order</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Company Logo</label>
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-md overflow-hidden flex items-center justify-center text-xs font-bold flex-shrink-0" style="background-color: hsl(30, 15%, 93%); color: hsl(82, 77%, 28%);">
                  <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-contain" alt="Preview"/>
                  <img v-else-if="workExperience?.logo_url" :src="getLogoUrl(workExperience.logo_url)" class="w-full h-full object-contain" alt="Logo"/>
                  <span v-else>{{ form.company ? form.company.charAt(0) : '?' }}</span>
                </div>
                <input type="file" accept="image/*" @change="onLogoChange" class="text-xs text-gray-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-lime-50 file:text-lime-700 hover:file:bg-lime-100"/>
              </div>
              <p v-if="form.errors.logo" class="text-xs text-red-600 mt-1">{{ form.errors.logo }}</p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-2 pt-2">
            <Link
              :href="route('admin.work-experiences.index')"
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
              {{ form.processing ? 'Saving...' : (isEditing ? 'Update Experience' : 'Create Experience') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
