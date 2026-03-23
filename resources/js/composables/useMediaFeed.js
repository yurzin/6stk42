import { ref, computed, watch, onMounted } from 'vue'

const API_BASE = '/api'
const LIMIT    = 20

export function useMediaFeed() {
  const items       = ref([])
  const meta        = ref(null)
  const loading     = ref(false)
  const loadingMore = ref(false)
  const error       = ref(null)
  const activeTab   = ref('index')
  const offset      = ref(0)

  const endpoint = computed(() => {
    const map = { index: '/index', photo: '/photos', video: '/videos', note: '/notes' }
    return API_BASE + (map[activeTab.value] ?? '/index')
  })

  async function fetchData(append = false) {
    error.value = null

    if (append) {
      loadingMore.value = true
    } else {
      loading.value = true
      items.value   = []
      offset.value  = 0
    }

    try {
      const url = `${endpoint.value}?limit=${LIMIT}&offset=${offset.value}`
      const res = await fetch(url)
      if (!res.ok) throw new Error(`HTTP ${res.status}`)
      const json = await res.json()

      if (append) items.value.push(...(json.data ?? []))
      else        items.value = json.data ?? []

      meta.value = json.meta ?? null
    } catch (e) {
      error.value = e.message || 'Failed to load content'
    } finally {
      loading.value     = false
      loadingMore.value = false
    }
  }

  async function loadMore() {
    offset.value += LIMIT
    await fetchData(true)
  }

  function setTab(tab) {
    if (tab === activeTab.value) return
    activeTab.value = tab
  }

  watch(activeTab, () => fetchData())
  onMounted(() => fetchData())

  return { items, meta, loading, loadingMore, error, activeTab, fetchData, loadMore, setTab }
}
