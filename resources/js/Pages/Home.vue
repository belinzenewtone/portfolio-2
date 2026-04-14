<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  profile: Object,
  projects: Array,
  workExperiences: Array,
  educations: Array,
});

const activeTab = ref('home');
const expandedProject = ref(null);

const tabs = [
  { id: 'home', label: 'Home' },
  { id: 'projects', label: 'Projects' },
  { id: 'work', label: 'Work' },
  { id: 'education', label: 'Education' },
  { id: 'cv', label: 'CV' },
];

function setTab(id) {
  if (id === 'cv') {
    if (props.profile?.cv_url) {
      window.open(props.profile.cv_url, '_blank');
    }
    return;
  }
  activeTab.value = id;
}

function toggleProject(id) {
  expandedProject.value = expandedProject.value === id ? null : id;
}

function getPhotoUrl(photo) {
  if (!photo) return null;
  if (photo.startsWith('http')) return photo;
  return '/storage/' + photo;
}

function getLogoUrl(logo) {
  if (!logo) return null;
  if (logo.startsWith('http')) return logo;
  return '/storage/' + logo;
}

const showHome = computed(() => activeTab.value === 'home');
const showProjects = computed(() => activeTab.value === 'projects' || activeTab.value === 'home');
const showWork = computed(() => activeTab.value === 'work');
const showEducation = computed(() => activeTab.value === 'education');
</script>

<template>
  <div class="min-h-screen" style="background-color: hsl(30, 20%, 97%);">
    <div class="max-w-3xl mx-auto px-3 py-6">

      <!-- PROFILE HEADER (shown on home tab) -->
      <section v-if="showHome" class="mb-8">
        <!-- Top row: photo + name/title -->
        <div class="flex items-start gap-4 mb-5">
          <!-- Profile photo -->
          <div class="flex-shrink-0">
            <div
              class="w-20 h-20 rounded-full overflow-hidden flex items-center justify-center text-2xl font-bold"
              style="background-color: hsl(82, 77%, 40%); color: white;"
            >
              <img
                v-if="profile?.profile_photo"
                :src="getPhotoUrl(profile.profile_photo)"
                :alt="profile.name"
                class="w-full h-full object-cover"
              />
              <span v-else>{{ profile?.name ? profile.name.charAt(0) : 'B' }}</span>
            </div>
          </div>

          <!-- Name + title -->
          <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold leading-tight" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
              {{ profile?.name || 'Belinze (Newtone) Ojing' }}
            </h1>
            <p class="text-base mt-0.5" style="color: hsl(220, 5%, 46%);">
              {{ profile?.title || 'Software and Cloud Engineer' }}
            </p>
            <!-- Subtitle badges -->
            <div v-if="profile?.subtitle" class="flex flex-wrap gap-1.5 mt-2">
              <span
                v-for="tag in profile.subtitle.split('·').map(s => s.trim()).filter(Boolean)"
                :key="tag"
                class="text-xs px-2 py-0.5 rounded-full font-medium"
                style="background-color: hsl(30, 15%, 93%); color: hsl(220, 14%, 10%);"
              >{{ tag }}</span>
            </div>
          </div>
        </div>

        <!-- Location -->
        <div v-if="profile?.location" class="flex items-center gap-1.5 text-sm mb-4" style="color: hsl(220, 5%, 46%);">
          <!-- Pin icon -->
          <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          <span>{{ profile.location }}</span>
        </div>

        <!-- Bio -->
        <p v-if="profile?.bio" class="text-sm leading-relaxed mb-5" style="color: hsl(220, 14%, 10%);">
          {{ profile.bio }}
        </p>

        <!-- Social buttons -->
        <div class="flex flex-wrap gap-2">
          <!-- GitHub -->
          <a
            v-if="profile?.github_url"
            :href="profile.github_url"
            target="_blank"
            rel="noopener"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-md border transition-colors hover:bg-gray-50"
            style="border-color: hsl(30, 10%, 90%); color: hsl(220, 14%, 10%);"
          >
            <!-- GitHub icon -->
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0 1 12 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
            </svg>
            GitHub
          </a>

          <!-- LinkedIn -->
          <a
            v-if="profile?.linkedin_url"
            :href="profile.linkedin_url"
            target="_blank"
            rel="noopener"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-md border transition-colors hover:bg-blue-50"
            style="border-color: hsl(30, 10%, 90%); color: hsl(220, 14%, 10%);"
          >
            <!-- LinkedIn icon -->
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
            LinkedIn
          </a>

          <!-- Say hello (email) -->
          <a
            v-if="profile?.email"
            :href="'mailto:' + profile.email"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-md transition-colors"
            style="background-color: hsl(82, 77%, 40%); color: white;"
          >
            <!-- Mail icon -->
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Say hello
          </a>
        </div>

        <!-- Current role badge -->
        <div v-if="profile?.current_company" class="mt-4 inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full" style="background-color: hsl(82, 77%, 40%, 0.12); color: hsl(82, 77%, 28%);">
          <span class="w-1.5 h-1.5 rounded-full bg-lime-600 inline-block"></span>
          Currently at {{ profile.current_company }}
        </div>
      </section>

      <!-- NAV TABS -->
      <nav class="flex gap-0 mb-6 border-b" style="border-color: hsl(30, 10%, 90%);">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="setTab(tab.id)"
          class="px-4 py-2 text-sm font-medium transition-colors relative"
          :class="activeTab === tab.id
            ? 'text-lime-600'
            : 'text-gray-500 hover:text-gray-800'"
          :style="tab.id === 'cv' ? 'color: hsl(220, 5%, 46%);' : ''"
        >
          {{ tab.label }}
          <span
            v-if="activeTab === tab.id && tab.id !== 'cv'"
            class="absolute bottom-0 left-0 right-0 h-0.5 rounded-full"
            style="background-color: hsl(82, 77%, 40%);"
          ></span>
        </button>
      </nav>

      <!-- PROJECTS SECTION -->
      <section v-if="showProjects" class="mb-8">
        <h2 class="text-3xl font-bold mb-4" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
          Projects
        </h2>

        <!-- Empty state -->
        <div v-if="!projects || projects.length === 0" class="py-10 text-center rounded-lg border" style="border-color: hsl(30, 10%, 90%); background-color: hsl(30, 15%, 93%);">
          <div class="text-3xl mb-2">🛠</div>
          <p class="text-sm font-medium" style="color: hsl(220, 14%, 10%);">Projects coming soon</p>
          <p class="text-xs mt-1" style="color: hsl(220, 5%, 46%);">Check back later for updates.</p>
        </div>

        <!-- Projects list -->
        <div v-else class="space-y-2">
          <div
            v-for="project in projects"
            :key="project.id"
            class="rounded-lg border overflow-hidden transition-shadow hover:shadow-sm cursor-pointer"
            style="border-color: hsl(30, 10%, 90%); background-color: white;"
            @click="toggleProject(project.id)"
          >
            <div class="flex items-center gap-3 px-4 py-3">
              <!-- Logo -->
              <div
                class="w-9 h-9 rounded-md flex-shrink-0 flex items-center justify-center overflow-hidden text-xs font-bold"
                :class="project.bg_color || 'bg-lime-100'"
                style="color: hsl(82, 77%, 28%);"
              >
                <img v-if="project.logo_url" :src="getLogoUrl(project.logo_url)" :alt="project.name" class="w-full h-full object-contain"/>
                <span v-else>{{ project.name.charAt(0) }}</span>
              </div>

              <!-- Name + date -->
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                  <span class="text-sm font-semibold truncate" style="color: hsl(220, 14%, 10%);">{{ project.name }}</span>
                </div>
                <div v-if="project.date" class="text-xs mt-0.5" style="color: hsl(220, 5%, 46%);">{{ project.date }}</div>
              </div>

              <!-- Chevron -->
              <svg
                class="w-4 h-4 flex-shrink-0 transition-transform"
                :class="expandedProject === project.id ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                style="color: hsl(220, 5%, 46%);"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </div>

            <!-- Expanded description -->
            <div v-if="expandedProject === project.id" class="px-4 pb-4 border-t" style="border-color: hsl(30, 10%, 90%);">
              <p v-if="project.description" class="text-sm mt-3 leading-relaxed" style="color: hsl(220, 14%, 10%);">
                {{ project.description }}
              </p>
              <a
                v-if="project.project_url"
                :href="project.project_url"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-1 text-xs font-medium mt-3 hover:underline"
                style="color: hsl(82, 77%, 40%);"
                @click.stop
              >
                View project
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- WORK SECTION -->
      <section v-if="showWork" class="mb-8">
        <h2 class="text-3xl font-bold mb-4" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
          Work
        </h2>

        <div class="space-y-4">
          <div
            v-for="work in workExperiences"
            :key="work.id"
            class="flex gap-4 py-4 border-b last:border-0"
            style="border-color: hsl(30, 10%, 90%);"
          >
            <!-- Company logo -->
            <div
              class="w-10 h-10 rounded-md flex-shrink-0 flex items-center justify-center overflow-hidden text-sm font-bold"
              style="background-color: hsl(30, 15%, 93%); color: hsl(82, 77%, 28%);"
            >
              <img v-if="work.logo_url" :src="getLogoUrl(work.logo_url)" :alt="work.company" class="w-full h-full object-contain"/>
              <span v-else>{{ work.company.charAt(0) }}</span>
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <div>
                  <div class="text-sm font-semibold" style="color: hsl(220, 14%, 10%);">{{ work.role }}</div>
                  <div class="text-sm" style="color: hsl(220, 5%, 46%);">{{ work.company }}</div>
                </div>
                <div class="text-right flex-shrink-0">
                  <div class="text-xs whitespace-nowrap" style="color: hsl(220, 5%, 46%);">{{ work.period }}</div>
                  <div v-if="work.employment_type" class="text-xs mt-0.5 px-1.5 py-0.5 rounded" style="background-color: hsl(30, 15%, 93%); color: hsl(220, 5%, 46%);">
                    {{ work.employment_type }}
                  </div>
                </div>
              </div>
              <div v-if="work.location" class="flex items-center gap-1 text-xs mt-1" style="color: hsl(220, 5%, 46%);">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ work.location }}
              </div>
              <p v-if="work.description" class="text-sm mt-2 leading-relaxed" style="color: hsl(220, 14%, 10%);">
                {{ work.description }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- EDUCATION SECTION -->
      <section v-if="showEducation" class="mb-8">
        <h2 class="text-3xl font-bold mb-4" style="font-family: 'Space Grotesk', sans-serif; color: hsl(220, 14%, 10%);">
          Education
        </h2>

        <div class="space-y-4">
          <div
            v-for="edu in educations"
            :key="edu.id"
            class="flex gap-4 py-4 border-b last:border-0"
            style="border-color: hsl(30, 10%, 90%);"
          >
            <!-- Institution logo -->
            <div
              class="w-10 h-10 rounded-md flex-shrink-0 flex items-center justify-center overflow-hidden text-sm font-bold"
              style="background-color: hsl(30, 15%, 93%); color: hsl(82, 77%, 28%);"
            >
              <img v-if="edu.logo_url" :src="getLogoUrl(edu.logo_url)" :alt="edu.institution" class="w-full h-full object-contain"/>
              <span v-else>{{ edu.institution.charAt(0) }}</span>
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <div>
                  <div class="text-sm font-semibold" style="color: hsl(220, 14%, 10%);">{{ edu.institution }}</div>
                  <div class="text-sm" style="color: hsl(220, 5%, 46%);">
                    {{ edu.degree }}<span v-if="edu.field"> · {{ edu.field }}</span>
                  </div>
                </div>
                <div class="text-xs whitespace-nowrap flex-shrink-0" style="color: hsl(220, 5%, 46%);">{{ edu.period }}</div>
              </div>
              <p v-if="edu.description" class="text-sm mt-2 leading-relaxed" style="color: hsl(220, 14%, 10%);">
                {{ edu.description }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Footer -->
      <footer class="pt-6 border-t text-center" style="border-color: hsl(30, 10%, 90%);">
        <p class="text-xs" style="color: hsl(220, 5%, 46%);">
          &copy; {{ new Date().getFullYear() }} {{ profile?.name || 'Belinze (Newtone) Ojing' }}. All rights reserved.
        </p>
      </footer>

    </div>
  </div>
</template>
