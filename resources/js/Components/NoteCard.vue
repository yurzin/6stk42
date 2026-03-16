<template>
  <div class="card note-card">
    <span class="card-badge badge-note">note</span>
    <span v-if="item.is_pinned" class="pin-icon">📌</span>
    <div class="note-color-bar" :style="{ background: item.color }"></div>
    <div class="card-body">
      <h2 class="card-title">{{ item.title }}</h2>
      <p class="card-text">{{ item.excerpt }}</p>
      <div class="card-tags">
        <span class="tag" v-for="t in item.tags" :key="t">#{{ t }}</span>
      </div>
      <div class="card-meta">
        <span class="meta-author">{{ item.author || 'Unknown' }}</span>
        <span class="meta-sep"></span>
        <span class="meta-date">{{ formatDate(item.created_at) }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  item:       { type: Object,   required: true },
  formatDate: { type: Function, required: true },
})
</script>