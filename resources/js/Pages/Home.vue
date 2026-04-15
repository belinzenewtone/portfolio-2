<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  profile: Object,
  projects: Array,
  workExperiences: Array,
  educations: Array,
});

// ── Dark mode ──────────────────────────────────────────────────────────────
const isDark = ref(false);

onMounted(() => {
  isDark.value = document.documentElement.classList.contains('dark');
});

function toggleDark() {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
}

// ── Nav tabs ───────────────────────────────────────────────────────────────
const tabs = ['Home', 'Projects', 'Work', 'Education', 'CV'];
const activeTab = ref('Home');

function setTab(tab) {
  if (tab === 'CV') {
    if (props.profile?.cv_url) window.open(props.profile.cv_url, '_blank');
    return;
  }
  activeTab.value = tab;
  const sectionMap = {
    Home: 'section-home',
    Projects: 'section-projects',
    Work: 'section-work',
    Education: 'section-education',
  };
  const el = document.getElementById(sectionMap[tab]);
  if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ── Expandable project rows ────────────────────────────────────────────────
const openIndex = ref(null);
function toggleProject(i) {
  openIndex.value = openIndex.value === i ? null : i;
}

// ── Helpers ────────────────────────────────────────────────────────────────
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

function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
}
</script>

<template>
  <div class="min-h-screen bg-background">
    <main class="mx-auto max-w-3xl px-4 py-10 space-y-5">

      <!-- ─── PROFILE CARD ─────────────────────────────────────────────── -->
      <section id="section-home" class="section-card scroll-mt-6">
        <div class="flex justify-between items-start gap-4">

          <!-- Left: name / role / bullets / social -->
          <div class="flex-1 min-w-0">
            <h1 class="text-4xl font-bold font-display text-foreground mb-1 leading-tight">
              {{ profile?.name || 'Belinze (Newtone) Ojing' }}
            </h1>

            <div class="flex items-center gap-2 text-sm mb-3 flex-wrap">
              <span class="font-semibold text-primary">
                {{ profile?.title || 'Software and Cloud Engineer' }}
              </span>
              <span v-if="profile?.subtitle" class="text-muted-foreground">
                · {{ profile.subtitle }}
              </span>
              <span class="flex items-center gap-1 text-muted-foreground">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ profile?.location || 'Nairobi, Kenya' }}
              </span>
            </div>

            <ul class="space-y-1 text-sm text-muted-foreground mb-5">
              <li class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
                {{ profile?.current_company
                  ? 'Currently at ' + profile.current_company
                  : 'Software Associate at Jamii Telecommunications Ltd' }}
              </li>
              <li class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
                Building software, cloud &amp; data solutions
              </li>
            </ul>

            <!-- Social links -->
            <div class="flex gap-2 flex-wrap">
              <a v-if="profile?.github_url" :href="profile.github_url" target="_blank" rel="noopener" class="social-pill">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                </svg>
                GitHub
              </a>
              <a v-if="profile?.linkedin_url" :href="profile.linkedin_url" target="_blank" rel="noopener" class="social-pill">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
                LinkedIn
              </a>
              <a v-if="profile?.twitter_url" :href="profile.twitter_url" target="_blank" rel="noopener" class="social-pill">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.63L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
                X / Twitter
              </a>
              <a v-if="profile?.email" :href="'mailto:' + profile.email"
                class="social-pill !border-primary/40 !text-primary hover:!bg-primary/10">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Say hello
              </a>
            </div>
          </div>

          <!-- Right: dark toggle + photo -->
          <div class="flex flex-col items-center gap-3 shrink-0">
            <button
              @click="toggleDark"
              :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
              class="w-8 h-8 rounded-full flex items-center justify-center border border-border
                     text-muted-foreground hover:text-primary hover:border-primary/40
                     transition-all bg-card"
            >
              <!-- Sun icon (dark mode active → click to go light) -->
              <svg v-if="isDark" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="4"/>
                <path stroke-linecap="round"
                  d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
              </svg>
              <!-- Moon icon (light mode active → click to go dark) -->
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
              </svg>
            </button>

            <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-primary/30 shadow-md shrink-0">
              <img
                v-if="profile?.profile_photo"
                :src="getPhotoUrl(profile.profile_photo)"
                :alt="profile?.name || 'Belinze'"
                class="w-full h-full object-cover"
              />
              <div v-else
                class="w-full h-full bg-primary/10 flex items-center justify-center text-2xl font-bold font-display text-primary">
                {{ (profile?.name || 'B').charAt(0) }}
              </div>
            </div>
          </div>
        </div>

        <!-- Bio -->
        <p v-if="profile?.bio"
          class="mt-5 text-sm text-muted-foreground leading-relaxed border-l-2 border-primary/40 pl-4 bg-primary/5 py-2 rounded-r-lg">
          {{ profile.bio }}
        </p>
      </section>

      <!-- ─── NAV TABS ─────────────────────────────────────────────────── -->
      <nav class="section-card !p-1">
        <div class="flex items-center gap-0.5 overflow-x-auto">
          <button
            v-for="tab in tabs"
            :key="tab"
            @click="setTab(tab)"
            class="px-4 py-2 text-sm font-medium transition-all whitespace-nowrap rounded-lg flex-1"
            :class="activeTab === tab
              ? 'bg-primary text-white shadow-sm'
              : 'text-muted-foreground hover:text-foreground hover:bg-muted'"
          >
            {{ tab }}
          </button>
        </div>
      </nav>

      <!-- ─── PROJECTS CARD ─────────────────────────────────────────────── -->
      <section id="section-projects" class="section-card scroll-mt-6">
        <h2 class="text-2xl font-bold font-display text-foreground mb-1">Software</h2>
        <p class="text-sm text-muted-foreground mb-5">Things I've built</p>

        <div v-if="!projects || projects.length === 0"
          class="py-12 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
          Projects coming soon — check back later.
        </div>

        <div v-else class="divide-y divide-border">
          <div v-for="(project, i) in projects" :key="project.id">
            <!-- Row button -->
            <button
              @click="toggleProject(i)"
              class="flex items-start justify-between w-full gap-4 py-4 text-left group"
            >
              <div class="flex items-start gap-3 flex-1 min-w-0">
                <div
                  class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 overflow-hidden mt-0.5 border border-border"
                  :class="project.bg_color || 'bg-blue-50'"
                >
                  <img v-if="project.logo_url" :src="getLogoUrl(project.logo_url)" :alt="project.name"
                    class="w-full h-full object-cover rounded-xl"/>
                  <span v-else class="text-xs font-bold text-primary">{{ project.name.charAt(0) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-foreground group-hover:text-primary transition-colors font-medium text-sm">
                    {{ project.name }}
                  </div>
                  <p class="text-muted-foreground text-xs mt-0.5 line-clamp-1">{{ project.description }}</p>
                </div>
              </div>
              <div class="flex items-center gap-2 shrink-0 mt-0.5">
                <span class="text-muted-foreground text-xs">{{ project.date ? formatDate(project.date) : '' }}</span>
                <svg
                  class="w-4 h-4 text-muted-foreground transition-transform duration-200"
                  :class="openIndex === i ? 'rotate-180 text-primary' : ''"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </button>

            <!-- Expanded panel -->
            <div v-if="openIndex === i"
              class="pb-4 pl-12 pr-2 -mt-1 text-sm text-muted-foreground space-y-3 animate-fade-in">
              <p class="leading-relaxed">{{ project.description }}</p>
              <a v-if="project.project_url" :href="project.project_url" target="_blank" rel="noopener"
                class="inline-flex items-center gap-1.5 text-primary hover:underline text-xs font-medium">
                View project
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- ─── WORK CARD ─────────────────────────────────────────────────── -->
      <section id="section-work" class="section-card scroll-mt-6">
        <h2 class="text-2xl font-bold font-display text-foreground mb-1">Work Experience</h2>
        <p class="text-sm text-muted-foreground mb-6">Where I've worked</p>

        <div class="space-y-7">
          <div v-for="work in workExperiences" :key="work.id" class="flex gap-4 items-start">
            <!-- Logo -->
            <div class="w-11 h-11 rounded-xl overflow-hidden shrink-0 bg-secondary border border-border
                        flex items-center justify-center font-bold font-display text-foreground text-sm">
              <img v-if="work.logo_url" :src="getLogoUrl(work.logo_url)" :alt="work.company"
                class="w-full h-full object-cover"/>
              <span v-else class="text-primary">{{ work.company.charAt(0) }}</span>
            </div>

            <!-- Details -->
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-baseline gap-x-2 gap-y-0.5 mb-1.5">
                <h3 class="text-sm font-semibold text-foreground">{{ work.role }}</h3>
                <span class="text-muted-foreground text-sm">@ {{ work.company }}</span>
              </div>
              <!-- Badges -->
              <div class="flex flex-wrap gap-1.5 mb-2.5">
                <span v-if="work.is_current"
                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                         bg-primary/10 text-primary border border-primary/20">
                  Current
                </span>
                <span v-if="work.employment_type"
                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs
                         text-muted-foreground border border-border bg-muted">
                  {{ work.employment_type }}
                </span>
                <span v-if="work.location"
                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs
                         text-muted-foreground border border-border bg-muted">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  {{ work.location }}
                </span>
              </div>
              <p class="text-sm text-muted-foreground leading-relaxed">{{ work.description }}</p>
              <span class="text-xs text-muted-foreground mt-1.5 block">{{ work.period }}</span>
            </div>
          </div>
        </div>
      </section>

      <!-- ─── EDUCATION CARD ───────────────────────────────────────────── -->
      <section id="section-education" class="section-card scroll-mt-6">
        <h2 class="text-2xl font-bold font-display text-foreground mb-1">Education</h2>
        <p class="text-sm text-muted-foreground mb-6">My academic background</p>

        <div class="space-y-6">
          <div v-for="edu in educations" :key="edu.id" class="flex gap-4 items-start">
            <div class="w-11 h-11 rounded-xl overflow-hidden shrink-0 bg-secondary border border-border
                        flex items-center justify-center font-bold font-display text-primary text-sm">
              <img v-if="edu.logo_url" :src="getLogoUrl(edu.logo_url)" :alt="edu.institution"
                class="w-full h-full object-cover"/>
              <span v-else>{{ edu.institution.charAt(0) }}</span>
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="text-sm font-semibold text-foreground">
                {{ edu.degree }}<span v-if="edu.field">, {{ edu.field }}</span>
              </h3>
              <p class="text-sm text-muted-foreground">{{ edu.institution }}</p>
              <p v-if="edu.description" class="text-xs text-muted-foreground mt-1.5 leading-relaxed">
                {{ edu.description }}
              </p>
              <p class="text-xs text-muted-foreground mt-1">{{ edu.period }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- ─── FOOTER ────────────────────────────────────────────────────── -->
      <footer class="py-4 text-center">
        <p class="text-xs text-muted-foreground">
          Built by
          <a v-if="profile?.github_url" :href="profile.github_url" target="_blank" rel="noopener"
            class="text-primary hover:underline font-medium">
            {{ profile?.name || 'Belinze' }}
          </a>
          <span v-else class="text-foreground font-medium">{{ profile?.name || 'Belinze' }}</span>
        </p>
      </footer>

    </main>
  </div>
</template>
