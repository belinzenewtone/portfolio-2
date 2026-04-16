<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ profile: Object });

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
});

const photoForm = useForm({ photo: null });
const photoPreview = ref(null);

function submit() { form.put(route('admin.profile.update')); }

function onPhotoChange(e) {
  const file = e.target.files[0];
  if (!file) return;
  photoForm.photo = file;
  const reader = new FileReader();
  reader.onload = ev => { photoPreview.value = ev.target.result; };
  reader.readAsDataURL(file);
}

function uploadPhoto() { photoForm.post(route('admin.profile.photo')); }

function getPhotoUrl(photo) {
  if (!photo) return null;
  return photo.startsWith('http') ? photo : '/storage/' + photo;
}
</script>

<template>
  <AdminLayout>
    <div class="max-w-2xl space-y-5">

      <div>
        <h2 class="text-xl font-bold font-display text-foreground">Edit Profile</h2>
        <p class="text-sm mt-1 text-muted-foreground">Update your public portfolio profile.</p>
      </div>

      <!-- ── Photo upload ── -->
      <div class="section-card">
        <h3 class="text-sm font-semibold text-foreground mb-4">Profile Photo</h3>
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-full overflow-hidden shrink-0 border-2 border-primary/30 flex items-center justify-center font-bold text-lg bg-primary/10 text-primary font-display">
            <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" alt="Preview"/>
            <img v-else-if="profile?.profile_photo" :src="getPhotoUrl(profile.profile_photo)" class="w-full h-full object-cover" alt="Photo"/>
            <span v-else>{{ profile?.name?.charAt(0) || 'B' }}</span>
          </div>
          <div class="flex-1">
            <form @submit.prevent="uploadPhoto" class="flex items-center gap-2 flex-wrap">
              <input
                type="file" accept="image/*" @change="onPhotoChange"
                class="text-xs text-muted-foreground file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all"
              />
              <button type="submit" :disabled="!photoForm.photo || photoForm.processing"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary text-primary-foreground disabled:opacity-50 hover:bg-primary/90 transition-all">
                Upload
              </button>
            </form>
            <p v-if="photoForm.errors.photo" class="text-xs text-red-500 mt-1">{{ photoForm.errors.photo }}</p>
            <p class="text-xs mt-1 text-muted-foreground">JPG, PNG, GIF up to 2MB.</p>
          </div>
        </div>
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

          <!-- CV URL -->
          <div>
            <label class="admin-label">CV / Resume URL</label>
            <input v-model="form.cv_url" type="url" placeholder="https://…" class="admin-input" :class="{'border-red-400': form.errors.cv_url}"/>
            <p v-if="form.errors.cv_url" class="admin-error">{{ form.errors.cv_url }}</p>
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
