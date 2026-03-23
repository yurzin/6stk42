<!-- Pages/Admin/RegisterPage.vue -->
<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'

const router   = useRouter()
const loading  = ref(false)
const apiError = ref(null)

const form = reactive({ name: '', email: '', password: '' })
const errors = reactive({ name: null, email: null, password: null })

function resetErrors() {
  errors.name = errors.email = errors.password = null
  apiError.value = null
}

async function submit() {
  resetErrors()
  loading.value = true
  try {
    const res  = await fetch(`${window.__APP_CONFIG__.apiBase}/admin/register`, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(form),
    })
    const data = await res.json()
    if (!res.ok) {
      if (res.status === 422 && data.errors) Object.assign(errors, data.errors)
      else apiError.value = data.message ?? 'Ошибка сервера'
      return
    }
    localStorage.setItem('token', data.token)
    router.push('/')
  } catch {
    apiError.value = 'Нет соединения с сервером'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="reg-page">

    <!-- Декоративная сетка на фоне -->
    <div class="reg-page__grid" aria-hidden="true">
      <span v-for="n in 30" :key="n" class="reg-page__cell" />
    </div>

    <div class="reg-card">

      <!-- Заголовок -->
      <div class="reg-card__head">
        <span class="reg-card__label">Admin / Register</span>
        <h1 class="reg-card__title">Новый<br>аккаунт</h1>
        <div class="reg-card__rule" />
      </div>

      <!-- Ошибка API -->
      <transition name="alert">
        <div v-if="apiError" class="reg-alert">
          <span class="reg-alert__icon">!</span>
          {{ apiError }}
        </div>
      </transition>

      <!-- Форма -->
      <form class="reg-form" @submit.prevent="submit" novalidate>

        <div class="reg-field" :class="{ 'reg-field--err': errors.name }">
          <label class="reg-field__label" for="name">Имя</label>
          <input
              id="name"
              v-model.trim="form.name"
              class="reg-field__input"
              type="text"
              autocomplete="name"
              placeholder="Иван Иванов"
          />
          <transition name="err">
            <span v-if="errors.name" class="reg-field__msg">{{ errors.name }}</span>
          </transition>
        </div>

        <div class="reg-field" :class="{ 'reg-field--err': errors.email }">
          <label class="reg-field__label" for="email">Email</label>
          <input
              id="email"
              v-model.trim="form.email"
              class="reg-field__input"
              type="email"
              autocomplete="email"
              placeholder="ivan@example.com"
          />
          <transition name="err">
            <span v-if="errors.email" class="reg-field__msg">{{ errors.email }}</span>
          </transition>
        </div>

        <div class="reg-field" :class="{ 'reg-field--err': errors.password }">
          <label class="reg-field__label" for="password">Пароль</label>
          <input
              id="password"
              v-model="form.password"
              class="reg-field__input"
              type="password"
              autocomplete="new-password"
              placeholder="Минимум 8 символов"
          />
          <transition name="err">
            <span v-if="errors.password" class="reg-field__msg">{{ errors.password }}</span>
          </transition>
        </div>

        <button class="reg-btn" type="submit" :disabled="loading">
          <span class="reg-btn__text">
            {{ loading ? 'Регистрация…' : 'Зарегистрироваться' }}
          </span>
          <span class="reg-btn__spinner" v-if="loading" />
          <span class="reg-btn__arrow" v-else>→</span>
        </button>

      </form>

      <p class="reg-card__foot">
        Уже есть аккаунт?
        <RouterLink class="reg-card__link" to="/login">Войти</RouterLink>
      </p>

    </div>
  </div>
</template>

<style scoped>
/* ── Tokens (совместимы с var(--color-*) из проекта) ─────────────── */
.reg-page {
  --ink:    var(--color-ink,    #0f0f0f);
  --ink-2:  var(--color-ink-2,  #1a1a1a);
  --ink-3:  var(--color-ink-3,  #2a2a2a);
  --gold:   var(--color-gold,   #c9a84c);
  --sand:   var(--color-sand,   #e8dcc8);
  --muted:  var(--color-muted,  #666);
  --error:  var(--color-type-video, #e05a5a);
}

/* ── Page ─────────────────────────────────────────────────────────── */
.reg-page {
  position: relative;
  min-height: 100vh;
  background: var(--ink);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 16px;
  overflow: hidden;
}

/* Декоративная сетка */
.reg-page__grid {
  position: absolute;
  inset: 0;
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  grid-template-rows: repeat(5, 1fr);
  pointer-events: none;
  opacity: 0.06;
}
.reg-page__cell {
  border: 1px solid var(--sand);
}

/* ── Card ─────────────────────────────────────────────────────────── */
.reg-card {
  position: relative;
  width: 100%;
  max-width: 420px;
  background: var(--ink-2);
  border: 1px solid var(--ink-3);
  padding: 48px 40px 40px;
  animation: card-in 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
}

@keyframes card-in {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Head ─────────────────────────────────────────────────────────── */
.reg-card__label {
  display: block;
  font-family: 'Courier New', monospace;
  font-size: 10px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 12px;
  animation: fade-up 0.4s 0.1s both;
}

.reg-card__title {
  font-family: Georgia, 'Times New Roman', serif;
  font-size: 42px;
  font-weight: 400;
  line-height: 1.05;
  color: var(--sand);
  margin: 0 0 20px;
  letter-spacing: -0.5px;
  animation: fade-up 0.4s 0.15s both;
}

.reg-card__rule {
  height: 1px;
  background: linear-gradient(90deg, var(--gold) 0%, transparent 70%);
  margin-bottom: 28px;
  animation: rule-in 0.6s 0.2s both;
}

@keyframes rule-in {
  from { transform: scaleX(0); transform-origin: left; }
  to   { transform: scaleX(1); }
}

/* ── Alert ────────────────────────────────────────────────────────── */
.reg-alert {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  border: 1px solid var(--error);
  color: var(--error);
  font-size: 12px;
  letter-spacing: 0.3px;
  margin-bottom: 20px;
}

.reg-alert__icon {
  flex-shrink: 0;
  width: 18px;
  height: 18px;
  border: 1px solid var(--error);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 700;
}

.alert-enter-active, .alert-leave-active { transition: opacity 0.2s, transform 0.2s; }
.alert-enter-from, .alert-leave-to { opacity: 0; transform: translateY(-6px); }

/* ── Field ────────────────────────────────────────────────────────── */
.reg-field {
  margin-bottom: 20px;
  animation: fade-up 0.4s both;
}
.reg-field:nth-child(1) { animation-delay: 0.2s; }
.reg-field:nth-child(2) { animation-delay: 0.25s; }
.reg-field:nth-child(3) { animation-delay: 0.3s; }

.reg-field__label {
  display: block;
  font-family: 'Courier New', monospace;
  font-size: 10px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 6px;
  transition: color 0.15s;
}

.reg-field:focus-within .reg-field__label {
  color: var(--gold);
}

.reg-field__input {
  width: 100%;
  background: var(--ink);
  border: 1px solid var(--ink-3);
  color: var(--sand);
  font-family: 'Courier New', monospace;
  font-size: 14px;
  padding: 11px 14px;
  outline: none;
  transition: border-color 0.15s;
  box-sizing: border-box;
}

.reg-field__input::placeholder {
  color: var(--muted);
  opacity: 0.6;
}

.reg-field__input:focus {
  border-color: var(--gold);
}

.reg-field--err .reg-field__input {
  border-color: var(--error);
}

.reg-field__msg {
  display: block;
  font-family: 'Courier New', monospace;
  font-size: 10px;
  color: var(--error);
  margin-top: 5px;
  letter-spacing: 0.3px;
}

.err-enter-active, .err-leave-active { transition: opacity 0.15s, transform 0.15s; }
.err-enter-from, .err-leave-to { opacity: 0; transform: translateY(-4px); }

/* ── Button ───────────────────────────────────────────────────────── */
.reg-btn {
  width: 100%;
  margin-top: 8px;
  padding: 13px 20px;
  background: transparent;
  border: 1px solid var(--gold);
  color: var(--gold);
  font-family: 'Courier New', monospace;
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: background 0.2s, color 0.2s;
  animation: fade-up 0.4s 0.35s both;
}

.reg-btn:hover:not(:disabled) {
  background: var(--gold);
  color: var(--ink);
}

.reg-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.reg-btn__spinner {
  width: 14px;
  height: 14px;
  border: 1.5px solid currentColor;
  border-top-color: transparent;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* ── Footer ───────────────────────────────────────────────────────── */
.reg-card__foot {
  margin: 24px 0 0;
  font-family: 'Courier New', monospace;
  font-size: 11px;
  color: var(--muted);
  letter-spacing: 0.5px;
  text-align: center;
  animation: fade-up 0.4s 0.4s both;
}

.reg-card__link {
  color: var(--gold);
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: border-color 0.15s;
}

.reg-card__link:hover {
  border-bottom-color: var(--gold);
}

@keyframes fade-up {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>