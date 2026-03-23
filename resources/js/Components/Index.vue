<!-- pages/Index.vue (или Home.vue — зависит от роутера) -->
<script setup>
import AppHeader   from '../components/AppHeader.vue'
import HeroSection from '../components/HeroSection.vue'
import StatsBar    from '../components/StatsBar.vue'
import MediaFeed   from '../components/MediaFeed.vue'
import { useMediaFeed }  from '../composables/useMediaFeed.js'
import { useFormatDate } from '../composables/useFormatDate.js'

const TABS = [
  { key: 'index', label: 'All' },
  { key: 'photo', label: 'Photos' },
  { key: 'video', label: 'Videos' },
  { key: 'note',  label: 'Notes' },
]

const { formatDate } = useFormatDate()
const {
  items, meta, loading, loadingMore, error,
  activeTab, fetchData, loadMore, setTab,
} = useMediaFeed()
</script>

<template>
  <div class="page">
    <AppHeader
        :tabs="TABS"
        :active-tab="activeTab"
        @update:active-tab="setTab"
    />
    <HeroSection />
    <StatsBar :meta="meta" />
    <MediaFeed
        :items="items"
        :meta="meta"
        :loading="loading"
        :loading-more="loadingMore"
        :error="error"
        :format-date="formatDate"
        @retry="fetchData"
        @load-more="loadMore"
    />
  </div>
</template>

<style scoped>
.page {
  min-height: 100vh;
  background: var(--color-ink);
}
</style>
