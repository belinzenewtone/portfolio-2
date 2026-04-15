<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({ workExperience: Object });
const isEditing = !!props.workExperience;

const form = useForm({
  company:         props.workExperience?.company         ?? '',
  role:            props.workExperience?.role            ?? '',
  description:     props.workExperience?.description     ?? '',
  period_start:    props.workExperience?.period_start    ? props.workExperience.period_start.substring(0, 10) : '',
  period_end:      props.workExperience?.period_end      ? props.workExperience.period_end.substring(0, 10)   : '',
  is_current:      props.workExperience?.is_current      ?? false,
  location:        props.workExperience?.location        ?? '',
  employment_type: props.workExperience?.employment_type ?? 'Full-time',
  sort_order:      props.workExperience?.sort_order      ?? 0,
  logo:            null,
});

const logoPreview = ref(null);
const employmentTypes = ['Full-time', 'Part-time', 'Internship', 'Contract', 'Freelance'];

watch(() => form.is_current, val => { if (val) form.period_end = ''; });

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
    form.post(route('admin.work-experiences.update', props.workExperience.id), { method: 'put' });
  } else {
    form.post(route('admin.work-experiences.store'));
  }
}

function getLogoUrl(logo) {
  if (!logo) return null;
  return logo.startsWith('http') ? logo : '/storage/' + logo;
}
</script>

<template>
  <AdminLayout>
    <div class="max-w-xl space-y-5">

      <div class="flex items-center gap-3">
        <Link :href="route('admin.work-experiences.index')" class="text-sm text-muted-foreground hover:text-primary transition-colors">← Work Experience</Link>
        <span class="text-muted-foreground">/</span>
        <h2 class="text-xl font-bold font-display text-foreground">{{ isEditing ? 'Edit Experience' : 'New Experience' }}</h2>
      </div>

      <div class="section-card">
        <form @submit.prevent="submit" class="space-y-4">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Company *</label>
              <input v-model="form.company" type="text" required class="admin-input" :class="{'border-red-400': form.errors.company}"/>
              <p v-if="form.errors.company" class="admin-error">{{ form.errors.company }}</p>
            </div>
            <div>
              <label class="admin-label">Role / Position *</label>
              <input v-model="form.role" type="text" required class="admin-input" :class="{'border-red-400': form.errors.role}"/>
              <p v-if="form.errors.role" class="admin-error">{{ form.errors.role }}</p>
            </div>
          </div>

          <div>
            <label class="admin-label">Description</label>
            <textarea v-model="form.description" rows="4" class="admin-input resize-none"></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Start Date *</label>
              <input v-model="form.period_start" type="date" required class="admin-input" :class="{'border-red-400': form.errors.period_start}"/>
              <p v-if="form.errors.period_start" class="admin-error">{{ form.errors.period_start }}</p>
            </div>
            <div>
              <label class="admin-label">End Date</label>
              <input v-model="form.period_end" type="date" :disabled="form.is_current"
                class="admin-input disabled:opacity-50 disabled:cursor-not-allowed"/>
              <p class="admin-hint">Leave blank if current.</p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <input v-model="form.is_current" type="checkbox" id="is_current" class="w-4 h-4 rounded accent-primary"/>
            <label for="is_current" class="text-sm text-foreground">I currently work here</label>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Location</label>
              <input v-model="form.location" type="text" placeholder="e.g. Nairobi, Kenya · On-site" class="admin-input"/>
            </div>
            <div>
              <label class="admin-label">Employment Type</label>
              <select v-model="form.employment_type" class="admin-input">
                <option v-for="t in employmentTypes" :key="t" :value="t">{{ t }}</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Sort Order</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="admin-input"/>
            </div>
            <div>
              <label class="admin-label">Company Logo</label>
              <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center text-xs font-bold border border-border shrink-0 bg-muted text-primary">
                  <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-contain" alt="Preview"/>
                  <img v-else-if="workExperience?.logo_url" :src="getLogoUrl(workExperience.logo_url)" class="w-full h-full object-contain" alt="Logo"/>
                  <span v-else>{{ form.company ? form.company.charAt(0) : '?' }}</span>
                </div>
                <input type="file" accept="image/*" @change="onLogoChange"
                  class="text-xs text-muted-foreground file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"/>
              </div>
              <p v-if="form.errors.logo" class="admin-error">{{ form.errors.logo }}</p>
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <Link :href="route('admin.work-experiences.index')" class="admin-btn-outline">Cancel</Link>
            <button type="submit" :disabled="form.processing"
              class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-primary text-primary-foreground disabled:opacity-60 hover:bg-primary/90 transition-all">
              {{ form.processing ? 'Saving…' : (isEditing ? 'Update Experience' : 'Create Experience') }}
            </button>
          </div>

        </form>
      </div>
    </div>
  </AdminLayout>
</template>
