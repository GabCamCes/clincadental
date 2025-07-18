<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-surface-dark dark:border-dark-border dark:text-white dark:placeholder-gray-400 dark:focus:ring-primary-400 dark:focus:border-primary-400"
        v-model="model"
        ref="input"
    />
</template>
