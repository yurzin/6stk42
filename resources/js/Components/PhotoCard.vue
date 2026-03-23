<!-- components/PhotoCard.vue -->
<script setup>
import { computed } from 'vue'

const props = defineProps({
  item:       { type: Object,   required: true },
  formatDate: { type: Function, required: true },
})
console.log(props.item);
const hasThumbnail  = computed(() => !!props.item.thumbnail)
const hasDescription = computed(() => !!props.item.description)
const hasTags       = computed(() => props.item.tags?.length > 0)
const authorName    = computed(() => props.item.author || 'Unknown')
</script>

<template>
  <li class="card-row photo-row">

    <!-- Thumbnail -->
    <div class="card-thumb">
      <img v-if="hasThumbnail" :src="item.thumbnail" :alt="item.title" loading="lazy" />
      <span v-else class="card-thumb-placeholder">🖼</span>
    </div>

    <!-- Body -->
    <div class="card-body">
      <span class="card-badge badge-photo">photo</span>
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
/* Базовые стили строки берутся из card-row.css, подключённого глобально.
   Здесь только type-специфичные переопределения. */

.badge-photo {
  background: rgba(74, 127, 165, 0.18);
  color: #7ab4d8;
}
</style>
