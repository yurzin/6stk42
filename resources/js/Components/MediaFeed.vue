<template>
  <main class="main">

    <!-- Loading -->
    <div v-if="loading" class="state-loading">
      <div class="spinner"></div>
      <p>Loading content…</p>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="state-error">
      <p>⚠️ {{ error }}</p>
      <button class="load-more" style="margin-top:20px" @click="emit('retry')">Retry</button>
    </div>

    <!-- Feed -->
    <template v-else>
      <transition-group name="list" tag="div" class="masonry">
        <template v-for="item in items" :key="item.type + '-' + item.id">
          <PhotoCard v-if="item.type === 'photo'" :item="item" :format-date="formatDate" />
          <VideoCard v-else-if="item.type === 'video'" :item="item" :format-date="formatDate" />
          <NoteCard  v-else-if="item.type === 'note'"  :item="item" :format-date="formatDate" />
        </template>
      </transition-group>

      <!-- Empty -->
      <div v-if="!items.length" class="state-empty">
        <p style="font-size:40px;margin-bottom:12px">🗂</p>
        <p>No content found</p>
      </div>

      <!-- Load more -->
      <button
          v-if="meta?.has_more"
          class="load-more"
          :disabled="loadingMore"
          @click="emit('load-more')"
      >{{ loadingMore ? 'Loading…' : 'Load more' }}</button>
    </template>

  </main>
</template>

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