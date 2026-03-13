<!-- components/StatsBar.vue -->
<script setup>
import { computed } from 'vue'

const props = defineProps({
  meta: {
    type: Object,
    default: null
  }
})

// Вычисляемое свойство для проверки наличия данных
const hasStats = computed(() => {
  return props.meta?.counts && Object.keys(props.meta.counts).length > 0
})

// Статистика с значениями по умолчанию
const stats = computed(() => ({
  photos: props.meta?.counts?.photos || 0,
  videos: props.meta?.counts?.videos || 0,
  notes: props.meta?.counts?.notes || 0
}))
</script>

<template>
  <div class="stats-bar" v-if="hasStats">
    <div class="stat">
      <div class="stat-icon photo">📷</div>
      <div>
        <div class="stat-val">{{ stats.photos }}</div>
        <div class="stat-lbl">Photos</div>
      </div>
    </div>

    <div class="stat">
      <div class="stat-icon video">🎬</div>
      <div>
        <div class="stat-val">{{ stats.videos }}</div>
        <div class="stat-lbl">Videos</div>
      </div>
    </div>

    <div class="stat">
      <div class="stat-icon note">📝</div>
      <div>
        <div class="stat-val">{{ stats.notes }}</div>
        <div class="stat-lbl">Notes</div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.stats-bar {
  display: flex;
  justify-content: center;
  gap: 3rem;
  padding: 2rem;
  background: white;
  border-bottom: 1px solid #eee;
}

.stat {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.stat-icon {
  font-size: 2rem;
  line-height: 1;
}

.stat-val {
  font-size: 1.5rem;
  font-weight: 600;
  color: #333;
  line-height: 1.2;
}

.stat-lbl {
  font-size: 0.875rem;
  color: #666;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Анимация при загрузке */
.stat-val {
  animation: countUp 0.5s ease-out;
}

@keyframes countUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .stats-bar {
    gap: 1.5rem;
    padding: 1.5rem;
  }

  .stat-icon {
    font-size: 1.5rem;
  }

  .stat-val {
    font-size: 1.25rem;
  }

  .stat-lbl {
    font-size: 0.75rem;
  }
}
</style>