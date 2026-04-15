<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';

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
onMounted(() => { isDark.value = document.documentElement.classList.contains('dark'); });

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
  // Scroll active tab into view on mobile
  nextTick(() => {
    const el = document.querySelector(`[data-tab="${tab}"]`);
    el?.scrollIntoView({ inline: 'center', behavior: 'smooth', block: 'nearest' });
  });
}

// ── Expandable project rows ────────────────────────────────────────────────
const openProject = ref(null);
function toggleProject(i) { openProject.value = openProject.value === i ? null : i; }

// ── Expandable list items ──────────────────────────────────────────────────
const openList = ref(null);
function toggleList(i) { openList.value = openList.value === i ? null : i; }

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
function getPhotoUrl(p) { return p ? (p.startsWith('http') ? p : '/storage/' + p) : null; }
function getLogoUrl(l)  { return l ? (l.startsWith('http') ? l : '/storage/' + l) : null; }

const statusLabel = { want_to_read: 'Want to read', currently_reading: 'Reading', completed: 'Done' };
const catLabel    = { career: 'Career', education: 'Education', life: 'Life', other: 'Other' };
const catColor    = {
  career:    'bg-primary/10 text-primary',
  education: 'bg-violet-500/10 text-violet-500',
  life:      'bg-green-500/10 text-green-600',
  other:     'bg-muted text-muted-foreground',
};
</script>

<template>
  <div class="min-h-screen bg-background text-foreground">
    <div class="mx-auto max-w-2xl">

      <!-- ─── PROFILE ───────────────────────────────────────────────────── -->
      <section class="section-card mx-4 mt-6 mb-3">
        <div class="flex items-start justify-between gap-4">

          <!-- Left: text -->
          <div class="flex-1 min-w-0">
            <h1 class="text-3xl sm:text-4xl font-bold font-display text-foreground leading-tight mb-1">
              {{ profile?.name || 'Belinze Ojing' }}
            </h1>

            <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-sm mb-2">
              <span class="font-semibold text-primary">{{ profile?.title || 'Software & Cloud Engineer' }}</span>
              <span v-if="profile?.subtitle" class="italic text-muted-foreground">/ {{ profile.subtitle }}</span>
            </div>

            <div class="flex items-center gap-1.5 text-sm text-muted-foreground mb-3">
              <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              {{ profile?.location || 'Nairobi, Kenya' }}
            </div>

            <ul class="space-y-1.5 mb-4">
              <li class="flex items-start gap-2 text-sm text-muted-foreground">
                <span class="w-2 h-2 rounded-full bg-primary shrink-0 mt-1.5"></span>
                <span>{{ profile?.current_company ? profile.current_company : 'Software Associate at Jamii Telecommunications' }}</span>
              </li>
              <li v-if="profile?.years_experience" class="flex items-start gap-2 text-sm text-muted-foreground">
                <span class="w-2 h-2 rounded-full bg-primary shrink-0 mt-1.5"></span>
                <span>{{ profile.years_experience }}+ years building software &amp; cloud solutions</span>
              </li>
              <li v-else class="flex items-start gap-2 text-sm text-muted-foreground">
                <span class="w-2 h-2 rounded-full bg-primary shrink-0 mt-1.5"></span>
                <span>Building software, cloud &amp; data solutions</span>
              </li>
            </ul>

            <!-- Social links -->
            <div class="flex flex-wrap gap-2">
              <a v-if="profile?.twitter_url" :href="profile.twitter_url" target="_blank" rel="noopener" class="social-pill">
                Twitter/X
                <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
              </a>
              <a v-if="profile?.github_url" :href="profile.github_url" target="_blank" rel="noopener" class="social-pill">
                GitHub
                <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
              </a>
              <a v-if="profile?.linkedin_url" :href="profile.linkedin_url" target="_blank" rel="noopener" class="social-pill">
                LinkedIn
                <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
              </a>
              <a v-if="profile?.email" :href="'mailto:' + profile.email"
                class="social-pill !border-primary/50 !text-primary">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Say hello
              </a>
            </div>
          </div>

          <!-- Right: dark toggle + photo -->
          <div class="flex flex-col items-center gap-2.5 shrink-0">
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

            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-primary/40 shadow-lg shrink-0">
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
          class="mt-4 text-sm text-muted-foreground leading-relaxed border-l-2 border-primary/40 pl-3 py-1">
          {{ profile.bio }}
        </p>
      </section>

      <!-- ─── STICKY NAV ────────────────────────────────────────────────── -->
      <nav class="sticky top-0 z-20 bg-background/95 backdrop-blur-sm border-b border-border">
        <div class="overflow-x-auto scrollbar-none">
          <div class="flex items-center gap-0.5 px-3 min-w-max">
            <button
              v-for="tab in TABS"
              :key="tab"
              :data-tab="tab"
              @click="setTab(tab)"
              class="flex items-center py-3 px-2 text-sm font-medium
                     whitespace-nowrap transition-colors outline-none"
              :class="activeTab === tab && tab !== 'CV'
                ? 'text-foreground'
                : 'text-muted-foreground hover:text-foreground'"
            >
              <span class="px-2 py-0.5 rounded-md transition-colors"
                :class="activeTab === tab && tab !== 'CV' ? 'bg-primary text-white' : ''">
                {{ tab }}
              </span>
            </button>
          </div>
        </div>
      </nav>

      <!-- ─── TAB PANELS ────────────────────────────────────────────────── -->
      <div class="px-4 py-3 pb-16 space-y-3">

        <!-- HOME: education -->
        <template v-if="activeTab === 'Home'">
          <div class="section-card">
            <h2 class="text-lg font-bold font-display text-foreground mb-0.5">Education</h2>
            <p class="text-xs text-muted-foreground mb-4">Academic background</p>

            <div v-if="!educations?.length"
              class="py-10 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
              No education entries yet.
            </div>

            <div v-else class="space-y-5 divide-y divide-border">
              <div v-for="edu in educations" :key="edu.id" class="flex gap-3 items-start pt-4 first:pt-0">
                <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 bg-muted border border-border
                            flex items-center justify-center font-bold text-primary text-sm">
                  <img v-if="edu.logo_url" :src="getLogoUrl(edu.logo_url)" :alt="edu.institution" class="w-full h-full object-cover"/>
                  <span v-else>{{ edu.institution.charAt(0) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                  <h3 class="text-sm font-semibold text-foreground">
                    {{ edu.degree }}<span v-if="edu.field">, {{ edu.field }}</span>
                  </h3>
                  <p class="text-sm text-muted-foreground">{{ edu.institution }}</p>
                  <p v-if="edu.description" class="text-xs text-muted-foreground mt-1 leading-relaxed">{{ edu.description }}</p>
                  <p class="text-xs text-muted-foreground mt-0.5">{{ edu.period }}</p>
                </div>
              </div>
            </div>
          </div>
        </template>

        <!-- PROJECTS -->
        <template v-if="activeTab === 'Projects'">
          <div class="section-card">
            <h2 class="text-lg font-bold font-display text-foreground mb-0.5">Software</h2>
            <p class="text-xs text-muted-foreground mb-4">Things I've built</p>

            <div v-if="!projects?.length"
              class="py-10 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
              Projects coming soon.
            </div>

            <div v-else class="divide-y divide-border">
              <div v-for="(project, i) in projects" :key="project.id">
                <button @click="toggleProject(i)"
                  class="flex items-start justify-between w-full gap-3 py-4 text-left group">
                  <div class="flex items-start gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 overflow-hidden border border-border"
                      :class="project.bg_color || 'bg-blue-50'">
                      <img v-if="project.logo_url" :src="getLogoUrl(project.logo_url)" :alt="project.name" class="w-full h-full object-cover rounded-xl"/>
                      <span v-else class="text-xs font-bold text-primary">{{ project.name.charAt(0) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="text-sm font-semibold text-foreground group-hover:text-primary transition-colors leading-snug">
                        {{ project.name }}
                      </div>
                      <p class="text-xs text-muted-foreground mt-0.5 line-clamp-2 leading-relaxed">{{ project.description }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-1.5 shrink-0 mt-0.5">
                    <span class="text-muted-foreground text-xs hidden sm:block">{{ project.date }}</span>
                    <svg class="w-4 h-4 text-muted-foreground transition-transform duration-200"
                      :class="openProject === i ? 'rotate-180 text-primary' : ''"
                      fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                  </div>
                </button>

                <div v-if="openProject === i" class="pb-4 pl-[52px] -mt-1 space-y-2">
                  <p class="text-xs text-muted-foreground leading-relaxed">{{ project.description }}</p>
                  <div class="flex items-center gap-3 flex-wrap">
                    <span v-if="project.date" class="text-xs text-muted-foreground">{{ project.date }}</span>
                    <a v-if="project.project_url" :href="project.project_url" target="_blank" rel="noopener"
                      class="inline-flex items-center gap-1 text-primary hover:underline text-xs font-medium">
                      View project
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>

        <!-- WORK -->
        <template v-if="activeTab === 'Work'">
          <div class="section-card">
            <h2 class="text-lg font-bold font-display text-foreground mb-0.5">Work Experience</h2>
            <p class="text-xs text-muted-foreground mb-4">Where I've worked</p>

            <div v-if="!workExperiences?.length"
              class="py-10 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
              No work experience yet.
            </div>

            <div v-else class="divide-y divide-border">
              <div v-for="work in workExperiences" :key="work.id" class="flex gap-3 items-start py-5 first:pt-0">
                <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 bg-muted border border-border
                            flex items-center justify-center font-bold text-primary text-sm">
                  <img v-if="work.logo_url" :src="getLogoUrl(work.logo_url)" :alt="work.company" class="w-full h-full object-cover"/>
                  <span v-else>{{ work.company.charAt(0) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex flex-wrap items-center gap-x-1.5 gap-y-0.5 mb-1">
                    <h3 class="text-sm font-semibold text-foreground">{{ work.role }}</h3>
                    <span class="text-muted-foreground text-sm">@ {{ work.company }}</span>
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
                      <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                      </svg>
                      {{ work.location }}
                    </span>
                  </div>
                  <p v-if="work.description" class="text-sm text-muted-foreground leading-relaxed">{{ work.description }}</p>
                  <p class="text-xs text-muted-foreground mt-1">{{ work.period }}</p>
                </div>
              </div>
            </div>
          </div>
        </template>

        <!-- BLOG -->
        <template v-if="activeTab === 'Blog'">
          <div class="section-card">
            <h2 class="text-lg font-bold font-display text-foreground mb-0.5">Blog</h2>
            <p class="text-xs text-muted-foreground mb-4">Writing &amp; thoughts</p>

            <div v-if="!blogPosts?.length"
              class="py-10 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
              No posts yet — check back soon.
            </div>

            <div v-else class="divide-y divide-border">
              <a v-for="post in blogPosts" :key="post.id"
                :href="post.external_url || '#'"
                :target="post.external_url ? '_blank' : ''"
                rel="noopener"
                class="flex items-start justify-between gap-3 py-4 group">
                <div class="flex-1 min-w-0">
                  <div class="text-sm font-semibold text-foreground group-hover:text-primary transition-colors leading-snug">
                    {{ post.title }}
                  </div>
                  <p v-if="post.excerpt" class="text-xs text-muted-foreground mt-0.5 line-clamp-2 leading-relaxed">{{ post.excerpt }}</p>
                </div>
                <div class="shrink-0 flex flex-col items-end gap-1 pt-0.5">
                  <span class="text-xs text-muted-foreground whitespace-nowrap">{{ post.published_at }}</span>
                  <svg v-if="post.external_url" class="w-3.5 h-3.5 text-muted-foreground group-hover:text-primary transition-colors"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                  </svg>
                </div>
              </a>
            </div>
          </div>
        </template>

        <!-- LISTS -->
        <template v-if="activeTab === 'Lists'">
          <div class="section-card">
            <h2 class="text-lg font-bold font-display text-foreground mb-0.5">Lists</h2>
            <p class="text-xs text-muted-foreground mb-4">Curated collections</p>

            <div v-if="!lists?.length"
              class="py-10 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
              No lists yet.
            </div>

            <div v-else class="divide-y divide-border">
              <div v-for="(list, i) in lists" :key="list.id">
                <button @click="toggleList(i)" class="flex items-center justify-between w-full gap-3 py-4 text-left group">
                  <div class="flex items-center gap-3 flex-1 min-w-0">
                    <span class="text-2xl leading-none shrink-0">{{ list.emoji || '📋' }}</span>
                    <div class="min-w-0">
                      <div class="text-sm font-semibold text-foreground group-hover:text-primary transition-colors">{{ list.title }}</div>
                      <p v-if="list.description" class="text-xs text-muted-foreground mt-0.5 truncate">{{ list.description }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2 shrink-0">
                    <span class="text-xs text-muted-foreground">{{ list.items?.length ?? 0 }}</span>
                    <svg class="w-4 h-4 text-muted-foreground transition-transform duration-200"
                      :class="openList === i ? 'rotate-180 text-primary' : ''"
                      fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                  </div>
                </button>

                <div v-if="openList === i" class="pb-4 -mt-1 space-y-2 pl-11">
                  <div v-for="item in list.items" :key="item.id" class="flex items-start gap-2">
                    <span class="text-primary mt-0.5 text-xs shrink-0 font-bold">→</span>
                    <div class="min-w-0">
                      <a v-if="item.url" :href="item.url" target="_blank" rel="noopener"
                        class="text-sm text-foreground hover:text-primary transition-colors font-medium">
                        {{ item.text }}
                        <svg class="inline w-3 h-3 ml-0.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                      </a>
                      <span v-else class="text-sm text-foreground">{{ item.text }}</span>
                      <span v-if="item.note" class="ml-2 text-xs text-muted-foreground">{{ item.note }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>

        <!-- READING -->
        <template v-if="activeTab === 'Reading'">
          <div class="section-card">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
              <div>
                <h2 class="text-lg font-bold font-display text-foreground mb-0.5">Reading</h2>
                <p class="text-xs text-muted-foreground">Books I'm reading or have read</p>
              </div>
              <div class="flex gap-1 flex-wrap">
                <button v-for="f in ['all', 'currently_reading', 'completed', 'want_to_read']" :key="f"
                  @click="readingFilter = f"
                  class="px-3 py-1 rounded-full text-xs font-medium transition-all border"
                  :class="readingFilter === f
                    ? 'bg-foreground text-background border-foreground'
                    : 'text-muted-foreground border-border hover:text-foreground hover:border-foreground/30'">
                  {{ f === 'all' ? 'All' : f === 'currently_reading' ? 'Reading' : f === 'completed' ? 'Done' : 'Wishlist' }}
                </button>
              </div>
            </div>

            <div v-if="!filteredReading?.length"
              class="py-10 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
              Nothing here yet.
            </div>

            <div v-else class="divide-y divide-border">
              <div v-for="book in filteredReading" :key="book.id" class="flex gap-3 items-start py-4 first:pt-0">
                <div class="w-12 h-16 rounded-lg overflow-hidden shrink-0 bg-muted border border-border flex items-center justify-center">
                  <img v-if="book.cover_url" :src="book.cover_url" :alt="book.title" class="w-full h-full object-cover"/>
                  <span v-else class="text-primary font-bold text-lg">{{ book.title.charAt(0) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex flex-wrap items-start justify-between gap-1 mb-0.5">
                    <a v-if="book.buy_url" :href="book.buy_url" target="_blank" rel="noopener"
                      class="text-sm font-semibold text-foreground hover:text-primary transition-colors leading-snug">
                      {{ book.title }}
                    </a>
                    <span v-else class="text-sm font-semibold text-foreground leading-snug">{{ book.title }}</span>
                    <span :class="{
                      'bg-primary/10 text-primary': book.status === 'currently_reading',
                      'bg-green-500/10 text-green-600': book.status === 'completed',
                      'bg-muted text-muted-foreground': book.status === 'want_to_read',
                    }" class="text-xs px-2 py-0.5 rounded-full font-medium shrink-0 border border-border">
                      {{ statusLabel[book.status] }}
                    </span>
                  </div>
                  <p v-if="book.author" class="text-xs text-muted-foreground">{{ book.author }}</p>
                  <div class="flex items-center gap-2 mt-1">
                    <span v-if="book.rating" class="text-xs text-amber-500 tracking-tight">
                      {{ '★'.repeat(book.rating) }}{{ '☆'.repeat(5 - book.rating) }}
                    </span>
                    <span v-if="book.year_read" class="text-xs text-muted-foreground">{{ book.year_read }}</span>
                  </div>
                  <p v-if="book.notes" class="text-xs text-muted-foreground mt-1 leading-relaxed line-clamp-2">{{ book.notes }}</p>
                </div>
              </div>
            </div>
          </div>
        </template>

        <!-- TIMELINE -->
        <template v-if="activeTab === 'Timeline'">
          <div class="section-card">
            <h2 class="text-lg font-bold font-display text-foreground mb-0.5">Timeline</h2>
            <p class="text-xs text-muted-foreground mb-4">Key moments in my journey</p>

            <div v-if="!timelineEvents?.length"
              class="py-10 text-center text-muted-foreground text-sm border border-dashed border-border rounded-xl">
              No events yet.
            </div>

            <div v-else class="space-y-6">
              <div v-for="([year, events]) in timelineByYear" :key="year">
                <div class="flex items-center gap-3 mb-3">
                  <span class="text-xs font-bold text-primary bg-primary/10 px-2.5 py-1 rounded-full shrink-0">{{ year }}</span>
                  <div class="flex-1 h-px bg-border"></div>
                </div>
                <div class="space-y-3 pl-3 border-l-2 border-border ml-1">
                  <div v-for="event in events" :key="event.id" class="relative pl-4">
                    <div class="absolute -left-[1.31rem] top-1.5 w-2.5 h-2.5 rounded-full bg-background border-2 border-primary"></div>
                    <div class="flex items-start gap-2">
                      <span class="text-xl leading-none mt-0.5 shrink-0">{{ event.emoji }}</span>
                      <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-0.5">
                          <span class="text-sm font-semibold text-foreground">{{ event.title }}</span>
                          <span :class="catColor[event.category]" class="text-xs px-2 py-0.5 rounded-full font-medium">
                            {{ catLabel[event.category] }}
                          </span>
                        </div>
                        <p v-if="event.description" class="text-sm text-muted-foreground leading-relaxed">{{ event.description }}</p>
                        <p class="text-xs text-muted-foreground mt-0.5">{{ event.event_date }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>

      </div>

      <!-- ─── FOOTER ─────────────────────────────────────────────────────── -->
      <footer class="px-4 py-6 border-t border-border text-center">
        <p class="text-xs text-muted-foreground">
          Built by
          <a v-if="profile?.github_url" :href="profile.github_url" target="_blank" rel="noopener"
            class="text-primary hover:underline font-medium">{{ profile?.name || 'Belinze' }}</a>
          <span v-else class="text-foreground font-medium">{{ profile?.name || 'Belinze' }}</span>
        </p>
      </footer>

    </div>
  </div>
</template>
