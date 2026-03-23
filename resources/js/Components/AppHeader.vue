<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'

const props = defineProps({
  tabs:      { type: Array,  required: true },
  activeTab: { type: String, required: true }
})
const emit = defineEmits(['update:activeTab'])

const router    = useRouter()
const isLogged  = computed(() => !!localStorage.getItem('token'))

function logout() {
  localStorage.removeItem('token')
  router.push('/login')
}
</script>

<template>
  <header class="header">
    <div class="logo">Сайт-галерея</div>

    <nav class="tabs" role="tablist">
      <button
          v-for="tab in tabs"
          :key="tab.key"
          class="tab"
          :class="{ active: activeTab === tab.key }"
          role="tab"
          :aria-selected="activeTab === tab.key"
          @click="emit('update:activeTab', tab.key)"
      >{{ tab.label }}</button>
    </nav>

    <button v-if="isLogged" class="btn-logout" @click="logout">
      Выйти →
    </button>
  </header>
</template>

<style scoped>
.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 28px;
  height: 52px;
  background: var(--color-ink);
  border-bottom: 1px solid var(--color-ink-3);
  position: sticky;
  top: 0;
  z-index: 100;
}

.logo {
  font-family: var(--font-serif);
  font-size: 18px;
  font-style: italic;
  color: var(--color-gold);
  letter-spacing: 0.5px;
  user-select: none;
}

.tabs {
  display: flex;
  gap: 4px;
}

.tab {
  padding: 5px 14px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 500;
  letter-spacing: 0.8px;
  text-transform: uppercase;
  cursor: pointer;
  color: var(--color-muted);
  background: transparent;
  border: 1px solid transparent;
  transition: background 0.15s, color 0.15s, border-color 0.15s;
}

.tab:hover { color: var(--color-sand); }

.tab.active {
  background: var(--color-ink-3);
  color: var(--color-sand);
  border-color: var(--color-ink-3);
}

.btn-logout {
  font-family: 'Courier New', monospace;
  font-size: 10px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: var(--color-muted);
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 4px 0;
  transition: color 0.15s;
}

.btn-logout:hover { color: var(--color-gold); }

@media (max-width: 640px) {
  .header { padding: 0 16px; }
  .tab { padding: 4px 10px; font-size: 10px; }
}
</style>