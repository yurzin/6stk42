<script setup>
import { defineAsyncComponent } from 'vue'
import AppHeader from '../Components/AppHeader.vue'
import HeroSection from '../Components/HeroSection.vue'
import StatsBar from '../Components/StatsBar.vue'
import MediaFeed from '../Components/MediaFeed.vue'
import { useMediaFeed } from '../composables/useMediaFeed.js'
import { useFormatDate } from '../composables/useFormatDate.js'

// ── Константы страницы ─────────────────────────────────────────────────────
const TABS = [
  { key: 'feed',  label: 'All' },
  { key: 'photo', label: 'Photos' },
  { key: 'video', label: 'Videos' },
  { key: 'note',  label: 'Notes' },
]

// ── Composables ────────────────────────────────────────────────────────────
const { formatDate } = useFormatDate()
const {
  items,
  meta,
  loading,
  loadingMore,
  error,
  activeTab,
  fetchData,
  loadMore,
  setTab
} = useMediaFeed()

// Если нужно лениво загружать компоненты
// const StatsBar = defineAsyncComponent(() => import('../components/StatsBar.vue'))
</script>

<template>
  <div>
    <AppHeader
        :tabs="TABS"
        :activeTab="activeTab"
        @update:activeTab="setTab"
    />

    <HeroSection />

    <StatsBar :meta="meta" />

    <MediaFeed
        :items="items"
        :meta="meta"
        :loading="loading"
        :loadingMore="loadingMore"
        :error="error"
        :formatDate="formatDate"
        @retry="fetchData"
        @loadMore="loadMore"
    />
  </div>
</template>

<style scoped>
/* Стили компонента */
</style>