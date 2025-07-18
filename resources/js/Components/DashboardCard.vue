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
    class="card block px-6 py-6 font-semibold text-center"
  >
    {{ label }}
  </Link>
</template>



