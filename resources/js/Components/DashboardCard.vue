<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  to: { type: String, required: true },
  label: { type: String, required: true }
})
</script>

<template>
  <Link
    :href="to"
    class="dashboard-card block rounded shadow px-6 py-6 font-semibold text-center transition hover:shadow-lg bg-white dark:bg-[#232323] border border-[var(--color-primary)]"
    :style="{ color: 'var(--color-primary)' }"
  >
    {{ label }}
  </Link>
</template>

<style>
.dashboard-card {
  cursor: pointer;
  transition: background 0.3s, color 0.3s, border 0.3s, box-shadow 0.3s;
}
.dashboard-card:hover {
  filter: brightness(0.96);
  box-shadow: 0 4px 18px 0 rgba(80,80,80,0.11);
}
</style>

