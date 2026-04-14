<script setup>
import { ref } from 'vue';

const props = defineProps({
  profile: Object,
  projects: Array,
  workExperiences: Array,
  educations: Array,
});

// Nav tabs — visual state only, all sections always visible
const tabs = ['Home', 'Projects', 'Work', 'Blog', 'Lists', 'Reading', 'Timeline', 'CV'];
const activeTab = ref('Home');

function setTab(tab) {
  if (tab === 'CV') {
    if (props.profile?.cv_url) {
      window.open(props.profile.cv_url, '_blank');
    }
    return;
  }
  activeTab.value = tab;
}

// Expandable project rows
const openIndex = ref(null);
function toggleProject(i) {
  openIndex.value = openIndex.value === i ? null : i;
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
</script>

<template>
  <main class="mx-auto max-w-3xl px-3 py-6">

    <!-- ─── PROFILE HEADER ─── -->
    <section class="mb-6">
      <div class="flex justify-between items-start">

        <!-- Left: name / role / bullets / social -->
        <div class="flex-1">
          <h1 class="text-4xl font-bold font-display text-foreground mb-1">
            {{ profile?.name || 'Belinze (Newtone) Ojing' }}
          </h1>

          <!-- Role · Subtitle · Location -->
          <div class="flex items-center gap-2 text-muted-foreground text-sm mb-3 flex-wrap">
            <span class="font-medium text-foreground">
              {{ profile?.title || 'Software and Cloud Engineer' }}
            </span>
            <span v-if="profile?.subtitle" class="italic text-muted-foreground">
              {{ profile.subtitle }}
            </span>
            <span class="flex items-center gap-1">
              <!-- MapPin icon -->
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              {{ profile?.location || 'Nairobi, Kenya' }}
            </span>
          </div>

          <!-- Bullet list -->
          <ul class="space-y-1 text-sm text-muted-foreground mb-4">
            <li class="flex items-center gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
              {{ profile?.current_company
                ? (profile.current_company === 'Jamii Telecommunications Ltd'
                    ? 'Software Associate at Jamii Telecommunications Ltd'
                    : 'Software Engineer at ' + profile.current_company)
                : 'Software Associate at Jamii Telecommunications Ltd' }}
            </li>
            <li class="flex items-center gap-2">
              <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
              Building software, cloud &amp; data solutions
            </li>
          </ul>

          <!-- Social buttons -->
          <div class="flex gap-2 flex-wrap">
            <!-- Twitter/X -->
            <a
              v-if="profile?.twitter_url"
              :href="profile.twitter_url"
              target="_blank"
              rel="noopener"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-border text-sm text-muted-foreground hover:border-foreground/30 transition-colors cursor-pointer"
            >
              Twitter/X
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </a>
            <span
              v-else
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-border text-sm text-muted-foreground hover:border-foreground/30 transition-colors cursor-pointer"
            >
              Twitter/X
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </span>

            <!-- GitHub -->
            <a
              v-if="profile?.github_url"
              :href="profile.github_url"
              target="_blank"
              rel="noopener"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-border text-sm text-muted-foreground hover:border-foreground/30 transition-colors cursor-pointer"
            >
              GitHub
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </a>

            <!-- LinkedIn -->
            <a
              v-if="profile?.linkedin_url"
              :href="profile.linkedin_url"
              target="_blank"
              rel="noopener"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-border text-sm text-muted-foreground hover:border-foreground/30 transition-colors cursor-pointer"
            >
              LinkedIn
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </a>

            <!-- Say hello (email) -->
            <a
              v-if="profile?.email"
              :href="'mailto:' + profile.email"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-primary bg-primary/10 text-primary text-sm font-medium hover:bg-primary/20 transition-colors"
            >
              <!-- Mail icon -->
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              Say hello
            </a>
          </div>
        </div>

        <!-- Right: profile photo -->
        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-border shrink-0 ml-4">
          <img
            v-if="profile?.profile_photo"
            :src="getPhotoUrl(profile.profile_photo)"
            :alt="profile?.name || 'Belinze'"
            class="w-full h-full object-cover"
          />
          <!-- Placeholder initials when no photo uploaded -->
          <div
            v-else
            class="w-full h-full bg-secondary flex items-center justify-center text-2xl font-bold font-display text-foreground"
          >
            B
          </div>
        </div>

      </div>
    </section>

    <!-- ─── NAV TABS ─── -->
    <nav class="mb-8 border-b border-border">
      <div class="flex items-center gap-1 overflow-x-auto">
        <button
          v-for="tab in tabs"
          :key="tab"
          @click="setTab(tab)"
          class="px-3 py-2 text-sm font-medium transition-colors whitespace-nowrap rounded-t-md"
          :class="activeTab === tab
            ? 'text-foreground border-b-2 border-foreground'
            : 'text-muted-foreground hover:text-foreground'"
        >
          {{ tab }}
        </button>
      </div>
    </nav>

    <!-- ─── SOFTWARE / PROJECTS SECTION ─── -->
    <section class="mb-12">
      <h2 class="text-4xl font-bold font-display text-foreground mb-1">Software</h2>

      <!-- Empty state -->
      <div
        v-if="!projects || projects.length === 0"
        class="py-10 text-center text-muted-foreground text-sm"
      >
        Projects coming soon — check back later.
      </div>

      <!-- Project rows -->
      <div v-else class="divide-y divide-border">
        <button
          v-for="(project, i) in projects"
          :key="project.id"
          @click="toggleProject(i)"
          class="flex items-start justify-between w-full gap-4 py-4 text-left group"
        >
          <!-- Logo + name + description -->
          <div class="flex items-start gap-3 flex-1 min-w-0">
            <div
              class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 overflow-hidden mt-0.5"
              :class="project.bg_color || 'bg-lime-100'"
            >
              <img
                v-if="project.logo_url"
                :src="getLogoUrl(project.logo_url)"
                :alt="project.name"
                class="w-full h-full object-cover rounded-lg"
              />
              <span v-else class="text-xs font-bold text-lime-900">
                {{ project.name.charAt(0) }}
              </span>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-foreground group-hover:text-primary transition-colors font-medium text-sm">
                {{ project.name }}
              </div>
              <p class="text-muted-foreground text-sm mt-0.5 line-clamp-2">
                {{ project.description }}
              </p>
            </div>
          </div>

          <!-- Date + chevron -->
          <span class="text-muted-foreground text-sm shrink-0 mt-0.5">{{ project.date }}</span>
          <svg
            class="w-4 h-4 text-muted-foreground shrink-0 mt-1 transition-transform"
            :class="openIndex === i ? 'rotate-180' : ''"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
      </div>
    </section>

    <!-- ─── WORK SECTION ─── -->
    <section class="mb-12">
      <h2 class="text-4xl font-bold font-display text-foreground mb-4">Work</h2>
      <div class="space-y-6">
        <div
          v-for="work in workExperiences"
          :key="work.id"
          class="flex gap-4 items-start"
        >
          <!-- Company logo -->
          <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0 bg-secondary border border-border">
            <img
              v-if="work.logo_url"
              :src="getLogoUrl(work.logo_url)"
              :alt="work.company"
              class="w-full h-full object-cover"
            />
            <div
              v-else
              class="w-full h-full flex items-center justify-center font-bold font-display text-foreground"
            >
              {{ work.company.charAt(0) }}
            </div>
          </div>

          <!-- Details -->
          <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start gap-2">
              <div>
                <h3 class="text-sm font-medium text-foreground">
                  {{ work.role }} @ {{ work.company }}
                </h3>
                <p class="text-sm text-muted-foreground mt-1">{{ work.description }}</p>
              </div>
            </div>
            <span class="text-xs text-muted-foreground mt-1 block">{{ work.period }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- ─── EDUCATION SECTION ─── -->
    <section class="mb-12">
      <h2 class="text-4xl font-bold font-display text-foreground mb-4">Education</h2>
      <div class="space-y-3">
        <div v-for="edu in educations" :key="edu.id">
          <h3 class="text-sm font-medium text-foreground">
            {{ edu.degree }}<span v-if="edu.field">, In {{ edu.field }}</span>, {{ edu.institution }}
          </h3>
          <p class="text-xs text-muted-foreground">{{ edu.period }}</p>
        </div>
      </div>
    </section>

  </main>
</template>
