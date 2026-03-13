<!-- components/VideoCard.vue -->
<script setup>
import { computed } from 'vue'

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  formatDate: {
    type: Function,
    required: true
  }
})

// Вычисляемые свойства для удобства
const hasThumbnail = computed(() => !!props.item.thumbnail)
const hasDescription = computed(() => !!props.item.description)
const hasTags = computed(() => props.item.tags && props.item.tags.length > 0)
const hasDuration = computed(() => !!props.item.duration_human)
const authorName = computed(() => props.item.author || 'Unknown')
</script>

<template>
  <div class="card video-card">
    <span class="card-badge badge-video">video</span>

    <!-- Видео превью с длительностью -->
    <div class="video-thumbnail-wrapper">
      <img
          v-if="hasThumbnail"
          :src="item.thumbnail"
          :alt="item.title"
          class="card-media"
          loading="lazy"
      />
      <div v-else class="card-media-placeholder">🎬</div>
      <span v-if="hasDuration" class="duration-pill">{{ item.duration_human }}</span>
    </div>

    <!-- Контент -->
    <div class="card-body">
      <h2 class="card-title">{{ item.title }}</h2>

      <p v-if="hasDescription" class="card-text">{{ item.description }}</p>

      <!-- Теги -->
      <div v-if="hasTags" class="card-tags">
        <span
            v-for="tag in item.tags"
            :key="tag"
            class="tag"
        >
          #{{ tag }}
        </span>
      </div>

      <!-- Мета-информация -->
      <div class="card-meta">
        <span class="meta-author">{{ authorName }}</span>
        <span class="meta-sep"></span>
        <span class="meta-date">{{ formatDate(item.created_at) }}</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.video-card {
  position: relative;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.video-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
}

.card-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  z-index: 1;
}

.badge-video {
  background: #e74c3c;
  color: white;
}

.video-thumbnail-wrapper {
  position: relative;
  width: 100%;
  height: 200px;
  overflow: hidden;
}

.card-media {
  width: 100%;
  height: 100%;
  object-fit: cover;
  background: #f5f5f5;
  transition: transform 0.3s;
}

.video-card:hover .card-media {
  transform: scale(1.05);
}

.card-media-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
  color: white;
  font-size: 4rem;
}

.duration-pill {
  position: absolute;
  bottom: 12px;
  right: 12px;
  padding: 4px 8px;
  background: rgba(0, 0, 0, 0.8);
  color: white;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 500;
  letter-spacing: 0.5px;
  backdrop-filter: blur(4px);
}

.card-body {
  padding: 1.5rem;
}

.card-title {
  margin: 0 0 0.5rem;
  font-size: 1.25rem;
  font-weight: 600;
  color: #333;
}

.card-text {
  margin: 0 0 1rem;
  color: #666;
  line-height: 1.5;
  font-size: 0.95rem;
}

.card-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.tag {
  padding: 2px 8px;
  background: #f0f0f0;
  border-radius: 12px;
  font-size: 0.75rem;
  color: #666;
}

.card-meta {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #999;
}

.meta-author {
  font-weight: 500;
}

.meta-sep {
  width: 4px;
  height: 4px;
  background: #ddd;
  border-radius: 50%;
}

.meta-date {
  color: #999;
}

@media (max-width: 768px) {
  .video-thumbnail-wrapper {
    height: 180px;
  }

  .card-body {
    padding: 1rem;
  }

  .card-title {
    font-size: 1.1rem;
  }
}
</style>