<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ messages: Array });

const expanded = ref(null);

function toggle(id) { expanded.value = expanded.value === id ? null : id; }

function destroy(id) {
  if (!confirm('Delete this message?')) return;
  router.delete(route('admin.messages.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <div class="mb-6">
        <h2 class="text-xl font-bold font-display text-foreground">Messages</h2>
        <p class="text-sm mt-0.5 text-muted-foreground">Contact form submissions</p>
      </div>

      <div class="section-card !p-0">
        <div v-if="messages.length === 0" class="py-16 text-center text-muted-foreground">
          <div class="text-4xl mb-3">📭</div>
          <p class="text-sm font-medium">No messages yet</p>
          <p class="text-xs mt-1 text-muted-foreground">When visitors send you a message, it will appear here.</p>
        </div>

        <div v-else class="divide-y divide-border">
          <div v-for="msg in messages" :key="msg.id"
            class="transition-colors"
            :class="!msg.read_at ? 'bg-primary/5' : ''">
            <button @click="toggle(msg.id)"
              class="w-full flex items-start gap-3 px-4 py-3.5 text-left hover:bg-muted/40 transition-colors">
              <!-- Unread dot -->
              <span class="w-2 h-2 rounded-full shrink-0 mt-1.5"
                :class="!msg.read_at ? 'bg-primary' : 'bg-transparent'"></span>
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center justify-between gap-2">
                  <span class="text-sm font-semibold text-foreground">{{ msg.name }}</span>
                  <span class="text-xs text-muted-foreground shrink-0">{{ new Date(msg.created_at).toLocaleDateString('en-GB', {day:'numeric',month:'short',year:'numeric'}) }}</span>
                </div>
                <p class="text-xs text-muted-foreground">{{ msg.email }}</p>
                <p class="text-xs text-muted-foreground mt-0.5 truncate">{{ msg.message }}</p>
              </div>
              <svg class="w-4 h-4 text-muted-foreground shrink-0 transition-transform duration-200 mt-0.5"
                :class="expanded === msg.id ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>

            <!-- Expanded body -->
            <div v-if="expanded === msg.id" class="px-4 pb-4 pl-9">
              <div class="bg-muted/50 rounded-xl p-4 text-sm text-foreground leading-relaxed whitespace-pre-wrap border border-border">
                {{ msg.message }}
              </div>
              <div class="flex items-center gap-3 mt-3">
                <a :href="'mailto:' + msg.email + '?subject=Re: Your message'"
                  class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary hover:underline">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  Reply via email
                </a>
                <button @click="destroy(msg.id)" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
