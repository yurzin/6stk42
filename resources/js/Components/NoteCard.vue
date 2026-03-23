<!-- components/NoteCard.vue -->
<script setup>
import { computed } from 'vue'

const props = defineProps({
  item:       { type: Object,   required: true },
  formatDate: { type: Function, required: true },
})

const hasTags    = computed(() => props.item.tags?.length > 0)
const authorName = computed(() => props.item.author || 'Unknown')

// Цвет акцента заметки (из поля item.color) с fallback
const accentColor = computed(() => props.item.color || 'var(--color-type-note)')
</script>

<template>
  <li class="card-row note-row">

    <!-- "Thumbnail" — текстовый превью с цветовым акцентом -->
    <div class="card-thumb note-thumb" :style="{ '--accent': accentColor }">
      <span v-if="item.is_pinned" class="pin-dot" aria-label="Pinned"></span>
      <p class="note-preview">{{ item.excerpt || item.title }}</p>
    </div>

    <!-- Body -->
    <div class="card-body">
      <span class="card-badge badge-note">note</span>
      <h2 class="card-title">{{ item.title }}</h2>
      <p v-if="item.excerpt" class="card-text">{{ item.excerpt }}</p>
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
.badge-note {
  background: rgba(90, 140, 90, 0.18);
  color: #85bb85;
}

.note-thumb {
  border: 1px solid color-mix(in srgb, var(--accent) 30%, transparent);
  background: color-mix(in srgb, var(--accent) 8%, var(--color-ink));
  padding: 8px;
  align-items: flex-start;
  justify-content: flex-start;
  position: relative;
}

.note-preview {
  font-family: var(--font-serif);
  font-style: italic;
  font-size: 10px;
  color: var(--color-muted);
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.pin-dot {
  position: absolute;
  top: 6px;
  right: 6px;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--color-gold);
}
</style>
