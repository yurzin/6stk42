<!-- components/MediaFeed.vue -->
<script setup>
import PhotoCard from './PhotoCard.vue'
import VideoCard from './VideoCard.vue'
import NoteCard  from './NoteCard.vue'

const props = defineProps({
  items:       { type: Array,    required: true },
  meta:        { type: Object,   default: null },
  loading:     { type: Boolean,  default: false },
  loadingMore: { type: Boolean,  default: false },
  error:       { type: String,   default: null },
  formatDate:  { type: Function, required: true },
})

const emit = defineEmits(['retry', 'load-more'])
</script>

<template>
  <main class="feed-wrap">

    <!-- Loading -->
    <div v-if="loading" class="state-loading">
      <span class="spinner"></span>
      <p>Loading content…</p>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="state-error">
      <p>{{ error }}</p>
      <button class="btn-retry" @click="emit('retry')">Retry</button>
    </div>

    <!-- Feed list -->
    <template v-else>
      <transition-group name="row" tag="ul" class="feed-list">
        <template v-for="item in items" :key="item.type + '-' + item.id">
          <PhotoCard v-if="item.type === 'photo'" :item="item" :format-date="formatDate" />
          <VideoCard v-else-if="item.type === 'video'" :item="item" :format-date="formatDate" />
          <NoteCard  v-else-if="item.type === 'note'"  :item="item" :format-date="formatDate" />
        </template>
      </transition-group>

      <!-- Empty -->
      <div v-if="!items.length" class="state-empty">
        <p class="empty-icon">◻</p>
        <p>No content found</p>
      </div>

      <!-- Load more -->
      <div v-if="meta?.has_more" class="load-more-row">
        <button
            class="btn-load-more"
            :disabled="loadingMore"
            @click="emit('load-more')"
        >{{ loadingMore ? 'Loading…' : 'Load more' }}</button>
      </div>
    </template>

  </main>
</template>

<style scoped>
.feed-wrap {
  background: var(--color-ink);
  min-height: 300px;
}

/* List reset */
.feed-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

/* States */
.state-loading,
.state-error,
.state-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding: 80px 24px;
  color: var(--color-muted);
  font-size: 14px;
}

.spinner {
  width: 24px;
  height: 24px;
  border: 2px solid var(--color-ink-3);
  border-top-color: var(--color-gold);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

.state-error { color: var(--color-type-video); }

.btn-retry {
  padding: 8px 24px;
  border: 1px solid var(--color-type-video);
  background: transparent;
  color: var(--color-type-video);
  border-radius: 2px;
  font-size: 12px;
  letter-spacing: 0.8px;
  text-transform: uppercase;
  cursor: pointer;
}

.empty-icon {
  font-size: 32px;
  color: var(--color-ink-3);
}

.load-more-row {
  display: flex;
  justify-content: center;
  padding: 32px;
}

.btn-load-more {
  padding: 10px 36px;
  border: 1px solid var(--color-ink-3);
  background: transparent;
  color: var(--color-muted);
  font-size: 11px;
  letter-spacing: 1px;
  text-transform: uppercase;
  border-radius: 2px;
  cursor: pointer;
  transition: color 0.15s, border-color 0.15s;
}

.btn-load-more:hover:not(:disabled) {
  color: var(--color-sand);
  border-color: var(--color-muted);
}

.btn-load-more:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

/* Row transition */
.row-enter-active { transition: opacity 0.3s ease, transform 0.3s ease; }
.row-enter-from   { opacity: 0; transform: translateY(12px); }
</style>
