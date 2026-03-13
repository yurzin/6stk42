// composables/useFormatDate.js
import { ref, computed } from 'vue'

export function useFormatDate() {
    function formatDate(dt) {
        if (!dt) return ''

        try {
            return new Intl.DateTimeFormat('ru-RU', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            }).format(new Date(dt))
        } catch (e) {
            console.error('Date formatting error:', e)
            return ''
        }
    }

    return {
        formatDate
    }
}