<!-- components/VideoCard.vue -->
<script setup>
import { computed } from 'vue'

const props = defineProps({
  item:       { type: Object,   required: true },
  formatDate: { type: Function, required: true },
})

const hasThumbnail   = computed(() => !!props.item.thumbnail)
const hasDescription = computed(() => !!props.item.description)
const hasTags        = computed(() => props.item.tags?.length > 0)
const hasDuration    = computed(() => !!props.item.duration_human)
const authorName     = computed(() => props.item.author || 'Unknown')
</script>

<template>
  <li class="card-row video-row">

    <!-- Thumbnail -->
    <div class="card-thumb">
      <img v-if="hasThumbnail" :src="item.thumbnail" :alt="item.title" loading="lazy" />
      <span v-else class="card-thumb-placeholder play-icon" aria-hidden="true"></span>
      <span v-if="hasDuration" class="duration-pill">{{ item.duration_human }}</span>
    </div>

    <!-- Body -->
    <div class="card-body">
      <span class="card-badge badge-video">video</span>
      <h2 class="card-title">{{ item.title }}</h2>
      <p v-if="hasDescription" class="card-text">{{ item.description }}</p>
      <div v-if="hasTags" class="card-tags">
        <span v-for="tag in item.tags" :key="tag" class="card-tag">#{{ tag }}</span>
      </div>
    </div>

    <!-- Meta -->
    <div class="card-meta">
      <div class="meta-author">{{ authorName }}</div>
      <div class="meta-date">{{ formatDate(item.created_at) }}</div>
    </div>

  </li>
</template>

<style scoped>
.badge-video {
  background: rgba(165, 74, 74, 0.18);
  color: #d87a7a;
}

/* Play icon via CSS triangle */
.play-icon::after {
  content: '';
  display: block;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 10px 0 10px 18px;
  border-color: transparent transparent transparent var(--color-muted);
  opacity: 0.5;
  margin-left: 4px;
}

/* Duration pill */
.duration-pill {
  position: absolute;
  bottom: 5px;
  right: 5px;
  background: rgba(0, 0, 0, 0.75);
  color: var(--color-sand);
  font-size: 9px;
  padding: 2px 5px;
  border-radius: 2px;
  letter-spacing: 0.3px;
}
</style>
