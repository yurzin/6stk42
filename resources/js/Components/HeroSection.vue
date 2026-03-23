<!-- components/HeroSection.vue -->
<script setup>
// Компонент без логики
</script>

<template>
  <section class="hero">
    <p class="hero-eyebrow">Мой личный медиа-центр</p>
    <h1 class="hero-title">
      Весь мой <em>контент</em>,<br>в одном месте.
    </h1>
    <p class="hero-sub">
      Фотографии, видео и заметки — в едином формате, с возможностью поиска, в красивом оформлении.
    </p>
  </section>
</template>

<style scoped>
.hero {
  padding: 52px 28px 40px;
  background: var(--color-ink);
  border-bottom: 1px solid var(--color-ink-3);
}

.hero-eyebrow {
  font-size: 10px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--color-gold);
  margin-bottom: 16px;
  font-weight: 500;
}

.hero-title {
  font-family: var(--font-serif);
  font-size: 3rem;
  font-weight: 400;
  line-height: 1.15;
  color: var(--color-sand);
  max-width: 540px;
  margin-bottom: 16px;
}

.hero-title em {
  color: var(--color-gold);
  font-style: italic;
}

.hero-sub {
  font-size: 14px;
  color: var(--color-muted);
  max-width: 380px;
  line-height: 1.65;
}

@media (max-width: 640px) {
  .hero { padding: 36px 16px 28px; }
  .hero-title { font-size: 2.1rem; }
}
</style>
