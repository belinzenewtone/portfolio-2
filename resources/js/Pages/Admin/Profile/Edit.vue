<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const CLOUD_NAME     = 'd718lc3o';
const UPLOAD_PRESET  = 'porfolio';

const props = defineProps({ profile: Object });
const page  = usePage();

const flash = computed(() => page.props.flash);

// ── Main profile form ──────────────────────────────────────────────────────
const form = useForm({
  name:              props.profile?.name              ?? '',
  title:             props.profile?.title             ?? '',
  subtitle:          props.profile?.subtitle          ?? '',
  location:          props.profile?.location          ?? '',
  bio:               props.profile?.bio               ?? '',
  email:             props.profile?.email             ?? '',
  github_url:        props.profile?.github_url        ?? '',
  linkedin_url:      props.profile?.linkedin_url      ?? '',
  twitter_url:       props.profile?.twitter_url       ?? '',
  current_company:   props.profile?.current_company   ?? '',
  years_experience:  props.profile?.years_experience  ?? '',
  cv_url:            props.profile?.cv_url            ?? '',
  is_available:      props.profile?.is_available      ?? true,
  availability_text: props.profile?.availability_text ?? 'Open to opportunities',
  show_availability: props.profile?.show_availability ?? true,
  footer_email:      props.profile?.footer_email      ?? '',
  footer_name:       props.profile?.footer_name       ?? '',
});

function submit() { form.put(route('admin.profile.update')); }

// ── Photo upload (direct → Cloudinary → save URL) ─────────────────────────
const photoPreview    = ref(props.profile?.profile_photo ?? null);
const photoUploading  = ref(false);
const photoError      = ref('');
const photoForm       = useForm({ photo_url: '' });

async function onPhotoChange(e) {
  const file = e.target.files[0];
  if (!file) return;

  // Instant local preview
  const reader = new FileReader();
  reader.onload = ev => { photoPreview.value = ev.target.result; };
  reader.readAsDataURL(file);

  photoUploading.value = true;
  photoError.value     = '';

  try {
    const fd = new FormData();
    fd.append('file', file);
    fd.append('upload_preset', UPLOAD_PRESET);

    const res  = await fetch(`https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/upload`, { method: 'POST', body: fd });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error?.message ?? 'Upload failed');

    photoForm.photo_url = data.secure_url;
    photoForm.post(route('admin.profile.photo'), {
      preserveScroll: true,
      onFinish: () => { photoUploading.value = false; },
      onError:  () => { photoUploading.value = false; },
    });
  } catch (err) {
    photoError.value    = err.message ?? 'Upload failed. Please try again.';
    photoUploading.value = false;
  }
}

function getPhotoUrl(p) {
  if (!p) return null;
  return p.startsWith('http') ? p : '/storage/' + p;
}

// ── CV upload (direct → Cloudinary → save URL) ────────────────────────────
const cvUploading = ref(false);
const cvError     = ref('');
const cvForm      = useForm({ cv_url: '' });
const removeForm  = useForm({});

const currentCvUrl  = ref(props.profile?.cv_url ?? '');
const currentCvName = computed(() => {
  if (!currentCvUrl.value) return '';
  try {
    const parts = new URL(currentCvUrl.value).pathname.split('/');
    return decodeURIComponent(parts[parts.length - 1]);
  } catch {
    return 'CV uploaded';
  }
});

async function onCvChange(e) {
  const file = e.target.files[0];
  if (!file) return;

  cvUploading.value = true;
  cvError.value     = '';

  try {
    const fd = new FormData();
    fd.append('file', file);
    fd.append('upload_preset', UPLOAD_PRESET);

    const res  = await fetch(`https://api.cloudinary.com/v1_1/${CLOUD_NAME}/auto/upload`, { method: 'POST', body: fd });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error?.message ?? 'Upload failed');

    cvForm.cv_url = data.secure_url;
    cvForm.post(route('admin.profile.cv'), {
      preserveScroll: true,
      onSuccess: () => { currentCvUrl.value = data.secure_url; cvUploading.value = false; },
      onError:   () => { cvUploading.value = false; },
    });
  } catch (err) {
    cvError.value    = err.message ?? 'Upload failed. Please try again.';
    cvUploading.value = false;
  }
}

function removeCV() {
  removeForm.delete(route('admin.profile.cv.remove'), {
    preserveScroll: true,
    onSuccess: () => { currentCvUrl.value = ''; form.cv_url = ''; },
  });
}
</script>

<template>
  <AdminLayout>
    <div class="max-w-2xl space-y-5">

      <div>
        <h2 class="text-xl font-bold font-display text-foreground">Edit Profile</h2>
        <p class="text-sm mt-1 text-muted-foreground">Update your public portfolio profile.</p>
      </div>

      <!-- ── Profile Photo ── -->
      <div class="section-card">
        <h3 class="text-sm font-semibold text-foreground mb-4">Profile Photo</h3>
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-full overflow-hidden shrink-0 border-2 border-primary/30 flex items-center justify-center font-bold text-lg bg-primary/10 text-primary font-display">
            <img v-if="photoPreview" :src="getPhotoUrl(photoPreview) ?? photoPreview" class="w-full h-full object-cover" alt="Preview"/>
            <span v-else>{{ profile?.name?.charAt(0) || 'B' }}</span>
          </div>
          <div class="flex-1">
            <label class="inline-flex items-center gap-2 cursor-pointer">
              <span class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-all">
                {{ photoUploading ? 'Uploading…' : 'Choose photo' }}
              </span>
              <input type="file" accept="image/*" class="sr-only" :disabled="photoUploading" @change="onPhotoChange"/>
            </label>
            <p class="text-xs mt-1.5 text-muted-foreground">JPG, PNG, GIF up to 10 MB. Uploads directly to Cloudinary.</p>
            <p v-if="photoError" class="text-xs text-red-500 mt-1">{{ photoError }}</p>
            <p v-if="photoForm.errors.photo_url" class="text-xs text-red-500 mt-1">{{ photoForm.errors.photo_url }}</p>
          </div>
        </div>
      </div>

      <!-- ── CV / Resume Upload ── -->
      <div class="section-card">
        <h3 class="text-sm font-semibold text-foreground mb-1">CV / Resume</h3>
        <p class="text-xs text-muted-foreground mb-4">Visitors click "CV" in your portfolio nav to download it.</p>

        <!-- Current CV -->
        <div v-if="currentCvUrl" class="flex items-center gap-3 mb-4 p-3 rounded-xl bg-green-500/10 border border-green-500/20">
          <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-green-700 dark:text-green-400">CV uploaded</p>
            <a :href="currentCvUrl" target="_blank" class="text-xs text-green-600 hover:underline truncate block">{{ currentCvName }}</a>
          </div>
          <button type="button" @click="removeCV" :disabled="removeForm.processing"
            class="text-xs text-red-500 hover:text-red-600 font-semibold shrink-0 disabled:opacity-50">
            Remove
          </button>
        </div>

        <!-- Upload area -->
        <label class="block cursor-pointer">
          <div class="border-2 border-dashed border-border hover:border-primary/50 rounded-xl p-6 text-center transition-colors"
               :class="cvUploading ? 'opacity-60 pointer-events-none' : ''">
            <svg class="w-8 h-8 mx-auto mb-2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
            </svg>
            <p class="text-sm font-semibold text-foreground">
              {{ cvUploading ? 'Uploading to Cloudinary…' : currentCvUrl ? 'Replace CV' : 'Upload CV' }}
            </p>
            <p class="text-xs text-muted-foreground mt-1">PDF, DOC, DOCX up to 10 MB</p>
          </div>
          <input type="file" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
            class="sr-only" :disabled="cvUploading" @change="onCvChange"/>
        </label>

        <p v-if="cvError" class="text-xs text-red-500 mt-2">{{ cvError }}</p>
        <p v-if="cvForm.errors.cv_url" class="text-xs text-red-500 mt-2">{{ cvForm.errors.cv_url }}</p>
      </div>

      <!-- ── Profile form ── -->
      <div class="section-card">
        <form @submit.prevent="submit" class="space-y-4">

          <!-- Name + Title -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Full Name *</label>
              <input v-model="form.name" type="text" required class="admin-input" :class="{'border-red-400': form.errors.name}"/>
              <p v-if="form.errors.name" class="admin-error">{{ form.errors.name }}</p>
            </div>
            <div>
              <label class="admin-label">Title *</label>
              <input v-model="form.title" type="text" required class="admin-input" :class="{'border-red-400': form.errors.title}"/>
              <p v-if="form.errors.title" class="admin-error">{{ form.errors.title }}</p>
            </div>
          </div>

          <!-- Subtitle -->
          <div>
            <label class="admin-label">Subtitle / Tags</label>
            <input v-model="form.subtitle" type="text" placeholder="e.g. Software · Cloud · Data" class="admin-input"/>
            <p class="admin-hint">Separate tags with ·</p>
          </div>

          <!-- Location + Company -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">Location</label>
              <input v-model="form.location" type="text" class="admin-input"/>
            </div>
            <div>
              <label class="admin-label">Current Company</label>
              <input v-model="form.current_company" type="text" class="admin-input"/>
            </div>
          </div>

          <!-- Bio -->
          <div>
            <label class="admin-label">Bio</label>
            <textarea v-model="form.bio" rows="4" class="admin-input resize-none"></textarea>
          </div>

          <!-- Email -->
          <div>
            <label class="admin-label">Email</label>
            <input v-model="form.email" type="email" class="admin-input" :class="{'border-red-400': form.errors.email}"/>
            <p v-if="form.errors.email" class="admin-error">{{ form.errors.email }}</p>
          </div>

          <!-- Social URLs -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">GitHub URL</label>
              <input v-model="form.github_url" type="url" placeholder="https://github.com/…" class="admin-input" :class="{'border-red-400': form.errors.github_url}"/>
              <p v-if="form.errors.github_url" class="admin-error">{{ form.errors.github_url }}</p>
            </div>
            <div>
              <label class="admin-label">LinkedIn URL</label>
              <input v-model="form.linkedin_url" type="url" placeholder="https://linkedin.com/in/…" class="admin-input" :class="{'border-red-400': form.errors.linkedin_url}"/>
              <p v-if="form.errors.linkedin_url" class="admin-error">{{ form.errors.linkedin_url }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="admin-label">X / Twitter URL</label>
              <input v-model="form.twitter_url" type="url" placeholder="https://x.com/…" class="admin-input" :class="{'border-red-400': form.errors.twitter_url}"/>
              <p v-if="form.errors.twitter_url" class="admin-error">{{ form.errors.twitter_url }}</p>
            </div>
            <div>
              <label class="admin-label">Years of Experience</label>
              <input v-model="form.years_experience" type="text" placeholder="e.g. 3+" class="admin-input"/>
            </div>
          </div>

          <!-- Availability badge -->
          <div class="border border-border rounded-xl p-4 space-y-3 bg-muted/30">
            <p class="text-sm font-semibold text-foreground">Availability Badge</p>
            <div>
              <label class="admin-label">Badge Text</label>
              <input v-model="form.availability_text" type="text" maxlength="100"
                placeholder="e.g. Open to opportunities" class="admin-input"/>
              <p class="admin-hint">Shown as a small pill on your profile. Max 100 characters.</p>
            </div>
            <div class="flex flex-wrap gap-4">
              <label class="flex items-center gap-2 cursor-pointer">
                <input v-model="form.is_available" type="checkbox" class="w-4 h-4 rounded accent-green-500"/>
                <span class="text-sm text-foreground">Green (available)</span>
                <span class="text-xs text-muted-foreground">— uncheck for amber (busy)</span>
              </label>
            </div>
            <div class="flex items-center gap-2">
              <input v-model="form.show_availability" type="checkbox" id="show_availability" class="w-4 h-4 rounded accent-primary"/>
              <label for="show_availability" class="text-sm text-foreground">Show badge on portfolio</label>
            </div>
          </div>

          <!-- Footer settings -->
          <div class="border border-border rounded-xl p-4 space-y-3 bg-muted/30">
            <p class="text-sm font-semibold text-foreground">Footer</p>
            <div>
              <label class="admin-label">Footer Email *</label>
              <input v-model="form.footer_email" type="email" required
                placeholder="you@email.com" class="admin-input"
                :class="{'border-red-400': form.errors.footer_email}"/>
              <p v-if="form.errors.footer_email" class="admin-error">{{ form.errors.footer_email }}</p>
              <p class="admin-hint">Shown as a clickable mailto link in the footer.</p>
            </div>
            <div>
              <label class="admin-label">Copyright Name</label>
              <input v-model="form.footer_name" type="text" placeholder="Your Name" class="admin-input"/>
              <p class="admin-hint">Appears as "© {{ new Date().getFullYear() }} [name]".</p>
            </div>
          </div>

          <div class="flex justify-end pt-2">
            <button type="submit" :disabled="form.processing"
              class="px-5 py-2.5 text-sm font-semibold rounded-xl bg-primary text-primary-foreground disabled:opacity-60 hover:bg-primary/90 transition-all">
              {{ form.processing ? 'Saving…' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>

    </div>
  </AdminLayout>
</template>
