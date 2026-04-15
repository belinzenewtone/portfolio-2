<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  profile: Object,
});

const form = useForm({
  name: props.profile?.name ?? '',
  title: props.profile?.title ?? '',
  subtitle: props.profile?.subtitle ?? '',
  location: props.profile?.location ?? '',
  bio: props.profile?.bio ?? '',
  email: props.profile?.email ?? '',
  github_url: props.profile?.github_url ?? '',
  linkedin_url: props.profile?.linkedin_url ?? '',
  twitter_url: props.profile?.twitter_url ?? '',
  current_company: props.profile?.current_company ?? '',
  years_experience: props.profile?.years_experience ?? '',
  cv_url: props.profile?.cv_url ?? '',
});

const photoForm = useForm({
  photo: null,
});

const photoPreview = ref(null);

function submit() {
  form.put(route('admin.profile.update'));
}

function onPhotoChange(e) {
  const file = e.target.files[0];
  if (!file) return;
  photoForm.photo = file;
  const reader = new FileReader();
  reader.onload = (ev) => { photoPreview.value = ev.target.result; };
  reader.readAsDataURL(file);
}

function uploadPhoto() {
  photoForm.post(route('admin.profile.photo'));
}

function getPhotoUrl(photo) {
  if (!photo) return null;
  if (photo.startsWith('http')) return photo;
  return '/storage/' + photo;
}
</script>

<template>
  <AdminLayout>
    <div class="max-w-2xl">
      <div class="mb-6">
        <h2 class="text-xl font-bold" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
          Edit Profile
        </h2>
        <p class="text-sm mt-1" style="color: hsl(220, 5%, 46%);">Update your public portfolio profile.</p>
      </div>

      <!-- Photo upload -->
      <div class="rounded-xl border p-5 mb-5" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <h3 class="text-sm font-semibold mb-4" style="color: hsl(220, 14%, 10%);">Profile Photo</h3>
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-full overflow-hidden flex-shrink-0 flex items-center justify-center font-bold text-lg"
            style="background-color: hsl(82, 77%, 40%); color: white;">
            <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" alt="Preview"/>
            <img v-else-if="profile?.profile_photo" :src="getPhotoUrl(profile.profile_photo)" class="w-full h-full object-cover" alt="Photo"/>
            <span v-else>{{ profile?.name ? profile.name.charAt(0) : 'B' }}</span>
          </div>
          <div class="flex-1">
            <form @submit.prevent="uploadPhoto" class="flex items-center gap-2">
              <input
                type="file"
                accept="image/*"
                @change="onPhotoChange"
                class="text-xs text-gray-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-lime-50 file:text-lime-700 hover:file:bg-lime-100"
              />
              <button
                type="submit"
                :disabled="!photoForm.photo || photoForm.processing"
                class="px-3 py-1.5 text-xs font-medium rounded text-white disabled:opacity-50"
                style="background-color: hsl(82, 77%, 40%);"
              >
                Upload
              </button>
            </form>
            <p v-if="photoForm.errors.photo" class="text-xs text-red-600 mt-1">{{ photoForm.errors.photo }}</p>
            <p class="text-xs mt-1" style="color: hsl(220, 5%, 46%);">JPG, PNG, GIF up to 2MB.</p>
          </div>
        </div>
      </div>

      <!-- Profile form -->
      <div class="rounded-xl border p-5" style="background-color: white; border-color: hsl(30, 10%, 90%);">
        <form @submit.prevent="submit" class="space-y-4">
          <!-- Name + Title row -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Full Name *</label>
              <input v-model="form.name" type="text" required class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</p>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Title *</label>
              <input v-model="form.title" type="text" required class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.title" class="text-xs text-red-600 mt-1">{{ form.errors.title }}</p>
            </div>
          </div>

          <!-- Subtitle -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Subtitle / Tags</label>
            <input v-model="form.subtitle" type="text" placeholder="e.g. Software · Cloud · Data" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            <p class="text-xs mt-0.5" style="color: hsl(220, 5%, 46%);">Separate tags with ·</p>
          </div>

          <!-- Location + Current Company -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Location</label>
              <input v-model="form.location" type="text" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Current Company</label>
              <input v-model="form.current_company" type="text" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            </div>
          </div>

          <!-- Bio -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Bio</label>
            <textarea v-model="form.bio" rows="4" class="w-full px-3 py-2 text-sm rounded-md border outline-none resize-none" style="border-color: hsl(30, 10%, 90%);"></textarea>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Email</label>
            <input v-model="form.email" type="email" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            <p v-if="form.errors.email" class="text-xs text-red-600 mt-1">{{ form.errors.email }}</p>
          </div>

          <!-- Social URLs -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">GitHub URL</label>
              <input v-model="form.github_url" type="url" placeholder="https://github.com/..." class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.github_url" class="text-xs text-red-600 mt-1">{{ form.errors.github_url }}</p>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">LinkedIn URL</label>
              <input v-model="form.linkedin_url" type="url" placeholder="https://linkedin.com/in/..." class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.linkedin_url" class="text-xs text-red-600 mt-1">{{ form.errors.linkedin_url }}</p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Twitter URL</label>
              <input v-model="form.twitter_url" type="url" placeholder="https://twitter.com/..." class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
              <p v-if="form.errors.twitter_url" class="text-xs text-red-600 mt-1">{{ form.errors.twitter_url }}</p>
            </div>
            <div>
              <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">Years of Experience</label>
              <input v-model="form.years_experience" type="text" placeholder="e.g. 3+" class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            </div>
          </div>

          <!-- CV URL -->
          <div>
            <label class="block text-xs font-medium mb-1" style="color: hsl(220, 14%, 10%);">CV / Resume URL</label>
            <input v-model="form.cv_url" type="url" placeholder="https://..." class="w-full px-3 py-2 text-sm rounded-md border outline-none" style="border-color: hsl(30, 10%, 90%);"/>
            <p v-if="form.errors.cv_url" class="text-xs text-red-600 mt-1">{{ form.errors.cv_url }}</p>
          </div>

          <!-- Submit -->
          <div class="flex justify-end pt-2">
            <button
              type="submit"
              :disabled="form.processing"
              class="px-5 py-2 text-sm font-semibold rounded-md text-white disabled:opacity-60 transition-opacity"
              style="background-color: hsl(82, 77%, 40%);"
            >
              {{ form.processing ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
