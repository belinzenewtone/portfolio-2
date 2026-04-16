<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ skills: Array });

const catLabel = { language:'Language', framework:'Framework', cloud:'Cloud', tool:'Tool', database:'Database', other:'Other' };
const catColor = {
  language:  'bg-blue-500/10 text-blue-600 border-blue-500/20',
  framework: 'bg-violet-500/10 text-violet-600 border-violet-500/20',
  cloud:     'bg-sky-500/10 text-sky-600 border-sky-500/20',
  tool:      'bg-orange-500/10 text-orange-600 border-orange-500/20',
  database:  'bg-green-500/10 text-green-600 border-green-500/20',
  other:     'bg-muted text-muted-foreground border-border',
};

function destroy(id) {
  if (!confirm('Delete this skill?')) return;
  router.delete(route('admin.skills.destroy', id));
}
</script>

<template>
  <AdminLayout>
    <div>
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold font-display text-foreground">Skills</h2>
          <p class="text-sm mt-0.5 text-muted-foreground">{{ skills.length }} skill{{ skills.length !== 1 ? 's' : '' }}</p>
        </div>
        <Link :href="route('admin.skills.create')" class="admin-btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Skill
        </Link>
      </div>

      <div class="section-card !p-0">
        <div v-if="skills.length === 0" class="py-16 text-center text-muted-foreground">
          <div class="text-4xl mb-3">⚡</div>
          <p class="text-sm font-medium">No skills yet</p>
          <Link :href="route('admin.skills.create')" class="text-xs mt-1 text-primary hover:underline">Add your first skill</Link>
        </div>

        <div class="overflow-x-auto" v-else><table class="w-full min-w-[480px] text-sm">
          <thead>
            <tr class="border-b border-border bg-muted/60">
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground">Skill</th>
              <th class="text-left px-4 py-3 text-xs font-semibold text-muted-foreground hidden sm:table-cell">Category</th>
              <th class="text-center px-4 py-3 text-xs font-semibold text-muted-foreground hidden md:table-cell">Order</th>
              <th class="text-center px-4 py-3 text-xs font-semibold text-muted-foreground">Visible</th>
              <th class="text-right px-4 py-3 text-xs font-semibold text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr v-for="skill in skills" :key="skill.id" class="hover:bg-muted/40 transition-colors">
              <td class="px-4 py-3">
                <div class="flex items-center gap-2.5">
                  <img v-if="skill.icon_url" :src="skill.icon_url" :alt="skill.name" class="w-6 h-6 object-contain shrink-0"/>
                  <div v-else class="w-6 h-6 rounded bg-primary/10 flex items-center justify-center text-xs font-bold text-primary shrink-0">
                    {{ skill.name.charAt(0) }}
                  </div>
                  <span class="font-medium text-foreground">{{ skill.name }}</span>
                </div>
              </td>
              <td class="px-4 py-3 hidden sm:table-cell">
                <span :class="catColor[skill.category] || catColor.other"
                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border capitalize">
                  {{ catLabel[skill.category] || skill.category }}
                </span>
              </td>
              <td class="px-4 py-3 hidden md:table-cell text-center text-muted-foreground text-xs">{{ skill.sort_order }}</td>
              <td class="px-4 py-3 text-center">
                <span class="inline-block w-2 h-2 rounded-full" :class="skill.is_visible ? 'bg-green-500' : 'bg-border'"></span>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-3">
                  <Link :href="route('admin.skills.edit', skill.id)" class="text-xs font-semibold text-primary hover:underline">Edit</Link>
                  <button @click="destroy(skill.id)" class="text-xs font-semibold text-red-500 hover:underline">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table></div>
      </div>
    </div>
  </AdminLayout>
</template>
