<!-- components/StatsBar.vue -->
<script setup>
import { computed } from 'vue'

const props = defineProps({
  meta: { type: Object, default: null }
})

const hasStats = computed(() =>
  props.meta?.counts && Object.keys(props.meta.counts).length > 0
)

const stats = computed(() => ({
  photos: props.meta?.counts?.photos || 0,
  videos: props.meta?.counts?.videos || 0,
  notes:  props.meta?.counts?.notes  || 0,
}))
</script>

<template>
  <div v-if="hasStats" class="stats-bar">
    <div class="stat">
      <span class="stat-dot photo" aria-hidden="true"></span>
      <div>
        <div class="stat-num">{{ stats.photos }}</div>
        <div class="stat-label">Photos</div>
      </div>
    </div>
    <div class="stat">
      <span class="stat-dot video" aria-hidden="true"></span>
      <div>
        <div class="stat-num">{{ stats.videos }}</div>
        <div class="stat-label">Videos</div>
      </div>
    </div>
    <div class="stat">
      <span class="stat-dot note" aria-hidden="true"></span>
      <div>
        <div class="stat-num">{{ stats.notes }}</div>
        <div class="stat-label">Notes</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.stats-bar {
  display: flex;
  background: var(--color-ink);
  border-bottom: 1px solid var(--color-ink-3);
}

.stat {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 28px;
  border-right: 1px solid var(--color-ink-3);
}

.stat:last-child {
  border-right: none;
}

.stat-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}

.stat-dot.photo { background: var(--color-type-photo); }
.stat-dot.video { background: var(--color-type-video); }
.stat-dot.note  { background: var(--color-type-note);  }

.stat-num {
  font-size: 22px;
  font-weight: 600;
  color: var(--color-sand);
  line-height: 1;
  animation: fadeUp 0.4s ease-out both;
}

.stat-label {
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--color-muted);
  margin-top: 3px;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}

@media (max-width: 640px) {
  .stat { padding: 16px; gap: 8px; }
  .stat-num { font-size: 18px; }
}
</style>
