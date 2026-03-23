<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'

const router  = useRouter()
const loading = ref(false)

const form = reactive({
  name:     '',
  email:    '',
  password: '',
})

const errors   = reactive({})
const apiError = ref(null)

async function submit() {
  // Сброс ошибок
  Object.keys(errors).forEach(k => delete errors[k])
  apiError.value = null
  loading.value  = true

  try {
    const res = await fetch('/api/admin/register', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(form),
    })

    const data = await res.json()

    if (!res.ok) {
      // 422 — ошибки валидации
      if (res.status === 422 && data.errors) {
        Object.assign(errors, data.errors)
      } else {
        apiError.value = data.message ?? 'Ошибка сервера'
      }
      return
    }

    // Сохраняем токен и редиректим
    localStorage.setItem('token', data.token)
    router.push({ name: 'dashboard' })

  } catch (e) {
    apiError.value = 'Нет соединения с сервером'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <form @submit.prevent="submit" novalidate>
    <div v-if="apiError" class="alert alert-danger">{{ apiError }}</div>

    <!-- Имя -->
    <div class="field" :class="{ 'has-error': errors.name }">
      <label for="name">Имя</label>
      <input
          id="name"
          v-model.trim="form.name"
          type="text"
          autocomplete="name"
          placeholder="Иван Иванов"
      />
      <span v-if="errors.name" class="error">{{ errors.name }}</span>
    </div>

    <!-- Email -->
    <div class="field" :class="{ 'has-error': errors.email }">
      <label for="email">Email</label>
      <input
          id="email"
          v-model.trim="form.email"
          type="email"
          autocomplete="email"
          placeholder="ivan@example.com"
      />
      <span v-if="errors.email" class="error">{{ errors.email }}</span>
    </div>

    <!-- Пароль -->
    <div class="field" :class="{ 'has-error': errors.password }">
      <label for="password">Пароль</label>
      <input
          id="password"
          v-model="form.password"
          type="password"
          autocomplete="new-password"
          placeholder="Минимум 8 символов"
      />
      <span v-if="errors.password" class="error">{{ errors.password }}</span>
    </div>

    <button type="submit" :disabled="loading">
      {{ loading ? 'Регистрация...' : 'Зарегистрироваться' }}
    </button>
  </form>
</template>