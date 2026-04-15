<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  profile:        Object,
  projects:       Array,
  workExperiences:Array,
  educations:     Array,
  blogPosts:      Array,
  lists:          Array,
  readingItems:   Array,
  timelineEvents: Array,
});

// ── Dark mode ──────────────────────────────────────────────────────────────
const isDark = ref(false);

onMounted(() => {
  isDark.value = document.documentElement.classList.contains('dark');
});

function toggleDark() {
  isDark.value = !isDark.value;
  document.documentElement.classList.toggle('dark', isDark.value);
  localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
}

// ── Nav tabs ───────────────────────────────────────────────────────────────
const TABS = ['Home', 'Projects', 'Work', 'Blog', 'Lists', 'Reading', 'Timeline', 'CV'];
const activeTab = ref('Home');

function setTab(tab) {
  if (tab === 'CV') {
    if (props.profile?.cv_url) window.open(props.profile.cv_url, '_blank');
    return;
  }
  activeTab.value = tab;
}

// ── Expandable project rows ────────────────────────────────────────────────
const openProject = ref(null);
function toggleProject(i) {
  openProject.value = openProject.value === i ? null : i;
}

// ── Expandable list items ──────────────────────────────────────────────────
const openList = ref(null);
function toggleList(i) {
  openList.value = openList.value === i ? null : i;
}

// ── Reading filter ─────────────────────────────────────────────────────────
const readingFilter = ref('all');

const filteredReading = computed(() => {
  if (readingFilter.value === 'all') return props.readingItems;
  return props.readingItems?.filter(r => r.status === readingFilter.value) ?? [];
});

// ── Timeline grouped by year ───────────────────────────────────────────────
const timelineByYear = computed(() => {
  const map = {};
  for (const event of props.timelineEvents ?? []) {
    const y = event.event_year;
    if (!map[y]) map[y] = [];
    map[y].push(event);
  }
  return Object.entries(map).sort(([a], [b]) => b - a);
});

// ── Helpers ────────────────────────────────────────────────────────────────
function getPhotoUrl(photo) {
  if (!photo) return null;
  return photo.startsWith('http') ? photo : '/storage/' + photo;
}

function getLogoUrl(logo) {
  if (!logo) return null;
  return logo.startsWith('http') ? logo : '/storage/' + logo;
}

const statusLabel = { want_to_read: 'Want to read', currently_reading: 'Reading', completed: 'Completed' };
const catLabel    = { career: 'Career', education: 'Education', life: 'Life', other: 'Other' };
const catColor    = {
  career:    'bg-primary/10 text-primary',
  education: 'bg-violet-500/10 text-violet-500',
  life:      'bg-green-500/10 text-green-600',
  other:     'bg-muted text-muted-foreground',
};
</script>

<template>
  <div class="min-h-screen bg-background">
    <main class="mx-auto max-w-2xl px-4 pt-10 pb-16 space-y-3">

      <!-- ─── PROFILE CARD ─────────────────────────────────────────────── -->
      <section class="section-card">
        <div class="flex justify-between items-start gap-4">

          <!-- Left: name / role / bullets / social -->
          <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold font-display text-foreground mb-1 leading-tight">
              {{ profile?.name || 'Belinze (Newtone) Ojing' }}
            </h1>

            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm mb-3">
              <span class="font-semibold text-primary">
                {{ profile?.title || 'Software and Cloud Engineer' }}
              </span>
              <span v-if="profile?.subtitle" class="text-muted-foreground">
                · {{ profile.subtitle }}
              </span>
              <span class="flex items-center gap-1 text-muted-foreground text-xs">
                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ profile?.location || 'Nairobi, Kenya' }}
              </span>
            </div>

            <ul class="space-y-1 text-xs text-muted-foreground mb-5">
              <li class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
                {{ profile?.current_company ? 'Currently at ' + profile.current_company : 'Software Associate at Jamii Telecommunications Ltd' }}
              </li>
              <li class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
                Building software, cloud &amp; data solutions
              </li>
            </ul>

            <!-- Social -->
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
                X
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
            <button @click="toggleDark" :title="isDark ? 'Light mode' : 'Dark mode'"
              class="w-8 h-8 rounded-full flex items-center justify-center border border-border
                     text-muted-foreground hover:text-primary hover:border-primary/40
                     transition-all bg-card">
              <svg v-if="isDark" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="4"/>
                <path stroke-linecap="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
              </svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
              </svg>
            </button>

            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-primary/30 shadow-md shrink-0">
              <img v-if="profile?.profile_photo" :src="getPhotoUrl(profile.profile_photo)"
                :alt="profile?.name" class="w-full h-full object-cover"/>
              <div v-else class="w-full h-full bg-primary/10 flex items-center justify-center text-2xl font-bold font-display text-primary">
                {{ (profile?.name || 'B').charAt(0) }}
              </div>
            </div>
          </div>
        </div>

        <!-- Bio -->
        <p v-if="profile?.bio"
          class="mt-4 text-sm text-muted-foreground leading-relaxed border-l-2 border-primary/40 pl-3 bg-primary/5 py-2 rounded-r-lg">
          {{ profile.bio }}
        </p>
      </section>

      <!-- ─── NAV TABS ─────────────────────────────────────────────────── -->
      <nav class="section-card !p-1">
        <div class="flex items-stretch">
          <button
            v-for="tab in TABS"
            :key="tab"
            @click="setTab(tab)"
            class="flex-1 py-2 text-xs font-medium transition-all whitespace-nowrap rounded-lg text-center"
            :class="activeTab === tab && tab !== 'CV'
              ? 'bg-primary text-white shadow-sm'
              : tab === 'CV'
                ? 'text-muted-foreground hover:text-primary hover:bg-muted'
                : 'text-muted-foreground hover:text-foreground hover:bg-muted'"
          >
            {{ tab }}
          </button>
        </div>
      </nav>

      <!-- ═══════════════════════════════════════════════════════════════ -->
      <!-- TAB PANELS — only the active one renders                       -->
      <!-- ═══════════════════════════════════════════════════════════════ -->

      <!-- ─── HOME ─────────────────────────────────────────────────────── -->
      <template v-if="activeTab === 'Home'">

        <!-- Education -->
        <section class="section-card">
          <h2 class="text-lg font-bold font-display text-foreground mb-1">Education</h2>
          <p class="text-xs text-muted-foreground mb-5">Academic background</p>

          <div v-if="!educations?.length" class="py-8 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
            No education entries yet.
          </div>

          <div v-else class="space-y-5">
            <div v-for="edu in educations" :key="edu.id" class="flex gap-3 items-start">
              <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 bg-secondary border border-border
                          flex items-center justify-center font-bold font-display text-primary text-sm">
                <img v-if="edu.logo_url" :src="getLogoUrl(edu.logo_url)" :alt="edu.institution" class="w-full h-full object-cover"/>
                <span v-else>{{ edu.institution.charAt(0) }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="text-sm font-semibold text-foreground">
                  {{ edu.degree }}<span v-if="edu.field">, {{ edu.field }}</span>
                </h3>
                <p class="text-xs text-muted-foreground">{{ edu.institution }}</p>
                <p v-if="edu.description" class="text-xs text-muted-foreground mt-1 leading-relaxed">{{ edu.description }}</p>
                <p class="text-xs text-muted-foreground mt-0.5">{{ edu.period }}</p>
              </div>
            </div>
          </div>
        </section>

      </template>

      <!-- ─── PROJECTS ──────────────────────────────────────────────────── -->
      <template v-if="activeTab === 'Projects'">
        <section class="section-card">
          <h2 class="text-lg font-bold font-display text-foreground mb-1">Projects</h2>
          <p class="text-xs text-muted-foreground mb-5">Things I've built</p>

          <div v-if="!projects?.length" class="py-8 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
            Projects coming soon.
          </div>

          <div v-else class="divide-y divide-border">
            <div v-for="(project, i) in projects" :key="project.id">
              <button @click="toggleProject(i)"
                class="flex items-start justify-between w-full gap-4 py-3.5 text-left group">
                <div class="flex items-start gap-3 flex-1 min-w-0">
                  <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 overflow-hidden mt-0.5 border border-border"
                    :class="project.bg_color || 'bg-blue-50'">
                    <img v-if="project.logo_url" :src="getLogoUrl(project.logo_url)" :alt="project.name" class="w-full h-full object-cover rounded-xl"/>
                    <span v-else class="text-xs font-bold text-primary">{{ project.name.charAt(0) }}</span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-foreground group-hover:text-primary transition-colors">{{ project.name }}</div>
                    <p class="text-muted-foreground text-xs mt-0.5 line-clamp-1">{{ project.description }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-2 shrink-0 mt-1">
                  <span class="text-muted-foreground text-xs hidden sm:block">{{ project.date }}</span>
                  <svg class="w-3.5 h-3.5 text-muted-foreground transition-transform duration-200"
                    :class="openProject === i ? 'rotate-180 text-primary' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </div>
              </button>

              <div v-if="openProject === i"
                class="pb-4 pl-12 pr-1 -mt-1 text-sm text-muted-foreground space-y-2">
                <p class="leading-relaxed text-xs">{{ project.description }}</p>
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
      </template>

      <!-- ─── WORK ──────────────────────────────────────────────────────── -->
      <template v-if="activeTab === 'Work'">
        <section class="section-card">
          <h2 class="text-lg font-bold font-display text-foreground mb-1">Work Experience</h2>
          <p class="text-xs text-muted-foreground mb-6">Where I've worked</p>

          <div v-if="!workExperiences?.length" class="py-8 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
            No work experience yet.
          </div>

          <div v-else class="space-y-6">
            <div v-for="work in workExperiences" :key="work.id" class="flex gap-3 items-start">
              <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 bg-secondary border border-border
                          flex items-center justify-center font-bold font-display text-primary text-sm">
                <img v-if="work.logo_url" :src="getLogoUrl(work.logo_url)" :alt="work.company" class="w-full h-full object-cover"/>
                <span v-else>{{ work.company.charAt(0) }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-baseline gap-x-1.5 gap-y-0.5 mb-1.5">
                  <h3 class="text-sm font-semibold text-foreground">{{ work.role }}</h3>
                  <span class="text-muted-foreground text-xs">@ {{ work.company }}</span>
                </div>
                <div class="flex flex-wrap gap-1.5 mb-2">
                  <span v-if="work.is_current"
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                    Current
                  </span>
                  <span v-if="work.employment_type"
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs text-muted-foreground border border-border bg-muted">
                    {{ work.employment_type }}
                  </span>
                  <span v-if="work.location"
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs text-muted-foreground border border-border bg-muted">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ work.location }}
                  </span>
                </div>
                <p v-if="work.description" class="text-xs text-muted-foreground leading-relaxed">{{ work.description }}</p>
                <span class="text-xs text-muted-foreground mt-1 block">{{ work.period }}</span>
              </div>
            </div>
          </div>
        </section>
      </template>

      <!-- ─── BLOG ──────────────────────────────────────────────────────── -->
      <template v-if="activeTab === 'Blog'">
        <section class="section-card">
          <h2 class="text-lg font-bold font-display text-foreground mb-1">Blog</h2>
          <p class="text-xs text-muted-foreground mb-5">Writing &amp; thoughts</p>

          <div v-if="!blogPosts?.length" class="py-8 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
            No posts yet — check back soon.
          </div>

          <div v-else class="divide-y divide-border">
            <a
              v-for="post in blogPosts"
              :key="post.id"
              :href="post.external_url || '#'"
              :target="post.external_url ? '_blank' : ''"
              rel="noopener"
              class="flex items-start justify-between gap-4 py-4 group hover:no-underline"
            >
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-foreground group-hover:text-primary transition-colors">
                  {{ post.title }}
                </div>
                <p v-if="post.excerpt" class="text-xs text-muted-foreground mt-0.5 line-clamp-2">{{ post.excerpt }}</p>
              </div>
              <div class="shrink-0 text-right">
                <span class="text-xs text-muted-foreground whitespace-nowrap">{{ post.published_at }}</span>
                <svg v-if="post.external_url" class="w-3.5 h-3.5 text-muted-foreground mt-1 ml-auto group-hover:text-primary transition-colors"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
              </div>
            </a>
          </div>
        </section>
      </template>

      <!-- ─── LISTS ─────────────────────────────────────────────────────── -->
      <template v-if="activeTab === 'Lists'">
        <section class="section-card">
          <h2 class="text-lg font-bold font-display text-foreground mb-1">Lists</h2>
          <p class="text-xs text-muted-foreground mb-5">Curated collections of things I like</p>

          <div v-if="!lists?.length" class="py-8 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
            No lists yet.
          </div>

          <div v-else class="divide-y divide-border">
            <div v-for="(list, i) in lists" :key="list.id">
              <button @click="toggleList(i)" class="flex items-center justify-between w-full gap-4 py-3.5 text-left group">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                  <span class="text-xl">{{ list.emoji || '📋' }}</span>
                  <div class="min-w-0">
                    <div class="text-sm font-medium text-foreground group-hover:text-primary transition-colors">{{ list.title }}</div>
                    <p v-if="list.description" class="text-xs text-muted-foreground mt-0.5 truncate">{{ list.description }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                  <span class="text-xs text-muted-foreground">{{ list.items?.length ?? 0 }} items</span>
                  <svg class="w-3.5 h-3.5 text-muted-foreground transition-transform duration-200"
                    :class="openList === i ? 'rotate-180 text-primary' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </div>
              </button>

              <div v-if="openList === i" class="pb-4 pl-10 pr-1 -mt-1 space-y-1.5">
                <div v-for="item in list.items" :key="item.id" class="flex items-start gap-2 group/item">
                  <span class="text-primary mt-1 text-xs shrink-0">→</span>
                  <div class="min-w-0">
                    <a v-if="item.url" :href="item.url" target="_blank" rel="noopener"
                      class="text-xs font-medium text-foreground hover:text-primary transition-colors">
                      {{ item.text }}
                      <svg class="inline w-2.5 h-2.5 ml-0.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                      </svg>
                    </a>
                    <span v-else class="text-xs font-medium text-foreground">{{ item.text }}</span>
                    <span v-if="item.note" class="ml-1.5 text-xs text-muted-foreground">{{ item.note }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </template>

      <!-- ─── READING ───────────────────────────────────────────────────── -->
      <template v-if="activeTab === 'Reading'">
        <section class="section-card">
          <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <div>
              <h2 class="text-lg font-bold font-display text-foreground mb-0.5">Reading</h2>
              <p class="text-xs text-muted-foreground">Books I'm reading or have read</p>
            </div>
            <!-- Filter pills -->
            <div class="flex gap-1">
              <button v-for="f in ['all', 'currently_reading', 'completed', 'want_to_read']" :key="f"
                @click="readingFilter = f"
                class="px-2.5 py-1 rounded-full text-xs font-medium transition-all"
                :class="readingFilter === f ? 'bg-primary text-white' : 'text-muted-foreground hover:text-foreground hover:bg-muted'">
                {{ f === 'all' ? 'All' : f === 'currently_reading' ? 'Reading' : f === 'completed' ? 'Done' : 'Wishlist' }}
              </button>
            </div>
          </div>

          <div v-if="!filteredReading?.length" class="py-8 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
            Nothing here yet.
          </div>

          <div v-else class="grid grid-cols-1 gap-3">
            <div v-for="book in filteredReading" :key="book.id"
              class="flex gap-3 items-start p-3 rounded-xl border border-border hover:border-primary/30 hover:bg-muted/40 transition-all">
              <!-- Cover -->
              <div class="w-12 h-16 rounded-lg overflow-hidden shrink-0 bg-muted border border-border flex items-center justify-center">
                <img v-if="book.cover_url" :src="book.cover_url" :alt="book.title" class="w-full h-full object-cover"/>
                <span v-else class="text-primary font-bold text-lg">{{ book.title.charAt(0) }}</span>
              </div>
              <!-- Info -->
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-start justify-between gap-1">
                  <a v-if="book.buy_url" :href="book.buy_url" target="_blank" rel="noopener"
                    class="text-sm font-semibold text-foreground hover:text-primary transition-colors line-clamp-1">
                    {{ book.title }}
                  </a>
                  <span v-else class="text-sm font-semibold text-foreground line-clamp-1">{{ book.title }}</span>
                  <span :class="{
                    'bg-primary/10 text-primary': book.status === 'currently_reading',
                    'bg-green-500/10 text-green-600': book.status === 'completed',
                    'bg-muted text-muted-foreground': book.status === 'want_to_read',
                  }" class="text-xs px-2 py-0.5 rounded-full font-medium shrink-0">
                    {{ statusLabel[book.status] }}
                  </span>
                </div>
                <p v-if="book.author" class="text-xs text-muted-foreground mt-0.5">{{ book.author }}</p>
                <div class="flex items-center gap-3 mt-1.5">
                  <span v-if="book.rating" class="text-xs text-amber-500">{{ '★'.repeat(book.rating) }}{{ '☆'.repeat(5 - book.rating) }}</span>
                  <span v-if="book.year_read" class="text-xs text-muted-foreground">{{ book.year_read }}</span>
                </div>
                <p v-if="book.notes" class="text-xs text-muted-foreground mt-1 leading-relaxed line-clamp-2">{{ book.notes }}</p>
              </div>
            </div>
          </div>
        </section>
      </template>

      <!-- ─── TIMELINE ──────────────────────────────────────────────────── -->
      <template v-if="activeTab === 'Timeline'">
        <section class="section-card">
          <h2 class="text-lg font-bold font-display text-foreground mb-1">Timeline</h2>
          <p class="text-xs text-muted-foreground mb-6">Key moments in my journey</p>

          <div v-if="!timelineEvents?.length" class="py-8 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
            No events yet.
          </div>

          <div v-else class="space-y-6">
            <div v-for="([year, events]) in timelineByYear" :key="year">
              <div class="flex items-center gap-3 mb-3">
                <span class="text-xs font-bold text-primary bg-primary/10 px-2.5 py-1 rounded-full">{{ year }}</span>
                <div class="flex-1 h-px bg-border"></div>
              </div>

              <div class="space-y-3 pl-4 border-l-2 border-border ml-3">
                <div v-for="event in events" :key="event.id" class="relative pl-4">
                  <!-- dot -->
                  <div class="absolute -left-[1.35rem] top-1 w-2.5 h-2.5 rounded-full bg-card border-2 border-primary"></div>
                  <div class="flex items-start gap-2">
                    <span class="text-lg leading-none mt-0.5">{{ event.emoji }}</span>
                    <div class="flex-1 min-w-0">
                      <div class="flex flex-wrap items-center gap-2 mb-0.5">
                        <span class="text-sm font-semibold text-foreground">{{ event.title }}</span>
                        <span :class="catColor[event.category]" class="text-xs px-2 py-0.5 rounded-full font-medium">
                          {{ catLabel[event.category] }}
                        </span>
                      </div>
                      <p v-if="event.description" class="text-xs text-muted-foreground leading-relaxed">{{ event.description }}</p>
                      <p class="text-xs text-muted-foreground mt-0.5">{{ event.event_date }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </template>

      <!-- ─── FOOTER ────────────────────────────────────────────────────── -->
      <footer class="py-4 text-center">
        <p class="text-xs text-muted-foreground">
          Built by
          <a v-if="profile?.github_url" :href="profile.github_url" target="_blank" rel="noopener"
            class="text-primary hover:underline font-medium">{{ profile?.name || 'Belinze' }}</a>
          <span v-else class="text-foreground font-medium">{{ profile?.name || 'Belinze' }}</span>
        </p>
      </footer>

    </main>
  </div>
</template>
