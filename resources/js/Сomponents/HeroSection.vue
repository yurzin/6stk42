<!-- components/HeroSection.vue -->
<script setup>
// Компонент без логики
</script>

<template>
  <section class="hero">
    <p class="hero-eyebrow">Your personal media hub</p>
    <h1 class="hero-title">
      All your <em>content</em>,<br>in one place.
    </h1>
    <p class="hero-sub">
      Photos, videos, and notes — unified, searchable, beautifully presented.
    </p>
  </section>
</template>

<style scoped>
.hero {
  text-align: center;
  padding: 4rem 2rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.hero-eyebrow {
  font-size: 1rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 1rem;
  opacity: 0.9;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  line-height: 1.2;
}

.hero-title em {
  font-style: italic;
  color: #ffd700;
}

.hero-sub {
  font-size: 1.25rem;
  max-width: 600px;
  margin: 0 auto;
  opacity: 0.9;
}

@media (max-width: 768px) {
  .hero {
    padding: 3rem 1.5rem;
  }

  .hero-title {
    font-size: 2.5rem;
  }

  .hero-sub {
    font-size: 1.1rem;
  }
}
</style>