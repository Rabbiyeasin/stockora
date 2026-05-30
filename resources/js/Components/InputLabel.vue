<template>
    <label :for="forInput" class="block text-sm font-medium text-gray-700">
        <slot />
    </label>
</template>

<script setup>
defineProps({
    forInput: {
        type: String,
        default: ''
    }
})
</script>