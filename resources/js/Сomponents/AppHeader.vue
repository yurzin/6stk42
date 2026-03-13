<script setup>
// defineProps и defineEmits автоматически импортируются
const props = defineProps({
  tabs: { type: Array, required: true },
  activeTab: { type: String, required: true }
})

const emit = defineEmits(['update:activeTab'])
</script>

<template>
  <header class="header">
    <div class="logo">MediaVault</div>
    <div class="header-sep"></div>
    <nav class="filter-tabs">
      <button
          v-for="tab in tabs"
          :key="tab.key"
          class="filter-tab"
          :class="{ active: activeTab === tab.key }"
          @click="emit('update:activeTab', tab.key)"
      >{{ tab.label }}</button>
    </nav>
  </header>
</template>