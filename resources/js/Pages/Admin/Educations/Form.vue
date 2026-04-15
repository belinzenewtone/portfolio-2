<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ education: Object });
const isEditing = !!props.education;

const form = useForm({
  institution:  props.education?.institution  ?? '',
  degree:       props.education?.degree       ?? '',
  field:        props.education?.field        ?? '',
  description:  props.education?.description  ?? '',
  period_start: props.education?.period_start ? props.education.period_start.substring(0, 10) : '',
  period_end:   props.education?.period_end   ? props.education.period_end.substring(0, 10)   : '',
  sort_order:   props.education?.sort_order   ?? 0,
  logo:         null,
});

const logoPreview = ref(null);

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
    form.post(route('admin.educations.update', props.education.id), { method: 'put' });
  } else {
    form.post(route('admin.educations.store'));
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
        <Link :href="route('admin.educations.index')" class="text-sm text-muted-foreground hover:text-primary transition-colors">← Education</Link>
        <span class="text-muted-foreground">/</span>
        <h2 class="text-xl font-bold font-display text-foreground">{{ isEditing ? 'Edit Education' : 'New Education' }}</h2>
      </div>

      <div class="section-card">
        <form @submit.prevent="submit" class="space-y-4">

          <div>
            <label class="admin-label">Institution *</label>
            <input v-model="form.institution" type="text" required class="admin-input" :class="{'border-red-400': form.errors.institution}"/>
            <p v-if="form.errors.institution" class="admin-error">{{ form.errors.institution }}</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Degree *</label>
              <input v-model="form.degree" type="text" required placeholder="e.g. Bachelor of Science" class="admin-input" :class="{'border-red-400': form.errors.degree}"/>
              <p v-if="form.errors.degree" class="admin-error">{{ form.errors.degree }}</p>
            </div>
            <div>
              <label class="admin-label">Field of Study</label>
              <input v-model="form.field" type="text" placeholder="e.g. Computer Science" class="admin-input"/>
            </div>
          </div>

          <div>
            <label class="admin-label">Description</label>
            <textarea v-model="form.description" rows="3" class="admin-input resize-none"></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Start Date *</label>
              <input v-model="form.period_start" type="date" required class="admin-input" :class="{'border-red-400': form.errors.period_start}"/>
              <p v-if="form.errors.period_start" class="admin-error">{{ form.errors.period_start }}</p>
            </div>
            <div>
              <label class="admin-label">End Date</label>
              <input v-model="form.period_end" type="date" class="admin-input"/>
              <p class="admin-hint">Leave blank if ongoing.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Sort Order</label>
              <input v-model.number="form.sort_order" type="number" min="0" class="admin-input"/>
            </div>
            <div>
              <label class="admin-label">Institution Logo</label>
              <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center text-xs font-bold border border-border shrink-0 bg-muted text-primary">
                  <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-contain" alt="Preview"/>
                  <img v-else-if="education?.logo_url" :src="getLogoUrl(education.logo_url)" class="w-full h-full object-contain" alt="Logo"/>
                  <span v-else>{{ form.institution ? form.institution.charAt(0) : '?' }}</span>
                </div>
                <input type="file" accept="image/*" @change="onLogoChange"
                  class="text-xs text-muted-foreground file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"/>
              </div>
              <p v-if="form.errors.logo" class="admin-error">{{ form.errors.logo }}</p>
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <Link :href="route('admin.educations.index')" class="admin-btn-outline">Cancel</Link>
            <button type="submit" :disabled="form.processing"
              class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-primary text-primary-foreground disabled:opacity-60 hover:bg-primary/90 transition-all">
              {{ form.processing ? 'Saving…' : (isEditing ? 'Update Education' : 'Create Education') }}
            </button>
          </div>

        </form>
      </div>
    </div>
  </AdminLayout>
</template>
