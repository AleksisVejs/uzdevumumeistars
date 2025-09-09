<template>
  <div :class="badgeClasses">
    <component
      v-if="icon"
      :is="icon"
      :class="iconClasses"
    />
    <span v-if="text || $slots.default" class="font-medium">
      <slot>{{ text }}</slot>
    </span>
    <button
      v-if="dismissible"
      @click="$emit('dismiss')"
      class="ml-2 hover:bg-white/20 rounded-full p-1 transition-colors"
    >
      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  text: {
    type: String,
    default: ''
  },
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'success', 'error', 'warning', 'info', 'indigo'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  icon: {
    type: [String, Object, Function],
    default: null
  },
  dismissible: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['dismiss'])

const badgeClasses = computed(() => {
  const baseClasses = 'inline-flex items-center rounded-full backdrop-blur-sm font-medium transition-all'
  
  const variantClasses = {
    default: 'bg-gray-100/80 text-gray-700',
    success: 'bg-emerald-100/80 text-emerald-700',
    error: 'bg-red-100/80 text-red-700',
    warning: 'bg-amber-100/80 text-amber-700',
    info: 'bg-blue-100/80 text-blue-700',
    indigo: 'bg-indigo-100/80 text-indigo-700'
  }
  
  const sizeClasses = {
    sm: 'px-2 py-1 text-xs',
    md: 'px-3 py-1.5 text-sm',
    lg: 'px-4 py-2 text-base'
  }
  
  return `${baseClasses} ${variantClasses[props.variant]} ${sizeClasses[props.size]}`
})

const iconClasses = computed(() => {
  const baseIconClasses = 'inline-block mr-1 transition-transform duration-200'
  const sizeIconClasses = {
    sm: 'w-3 h-3',
    md: 'w-4 h-4',
    lg: 'w-5 h-5'
  }
  
  return `${baseIconClasses} ${sizeIconClasses[props.size]}`
})
</script>
