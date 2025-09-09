<template>
  <button
    :class="buttonClasses"
    :disabled="disabled"
    :style="{ cursor: disabled ? 'not-allowed' : 'pointer' }"
    @click="$emit('click', $event)"
  >
    <component
      v-if="icon"
      :is="icon"
      :class="iconClasses"
    />
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'success', 'error', 'warning', 'info'].includes(value)
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
  disabled: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['click'])

const buttonClasses = computed(() => {
  const baseClasses = 'rounded-full backdrop-blur-md border font-semibold shadow transition-all duration-300 hover:shadow-lg hover:-translate-y-1 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100 focus:outline-none focus:ring-2 focus:ring-offset-2'
  
  // Add cursor pointer with higher specificity
  const cursorClass = props.disabled ? 'cursor-not-allowed' : 'cursor-pointer'
  
  const variantClasses = {
    primary: 'bg-white/80 border-white/30 text-gray-900 hover:bg-white/90 hover:border-white/50',
    secondary: 'bg-white/60 border-indigo-500/30 text-indigo-600 hover:bg-indigo-50/60 hover:border-indigo-500/50',
    success: 'bg-emerald-500/80 border-emerald-500/30 text-white hover:bg-emerald-600/90 hover:border-emerald-500/50',
    error: 'bg-red-500/80 border-red-500/30 text-white hover:bg-red-600/90 hover:border-red-500/50',
    warning: 'bg-amber-500/80 border-amber-500/30 text-white hover:bg-amber-600/90 hover:border-amber-500/50',
    info: 'bg-blue-500/80 border-blue-500/30 text-white hover:bg-blue-600/90 hover:border-blue-500/50'
  }
  
  const sizeClasses = {
    sm: 'px-4 py-2 text-sm',
    md: 'px-6 py-3 text-base',
    lg: 'px-8 py-4 text-lg'
  }
  
  return `${baseClasses} ${cursorClass} ${variantClasses[props.variant]} ${sizeClasses[props.size]}`
})

const iconClasses = computed(() => {
  const baseIconClasses = 'inline-block transition-transform duration-200'
  const sizeIconClasses = {
    sm: 'w-4 h-4 mr-2',
    md: 'w-5 h-5 mr-2',
    lg: 'w-6 h-6 mr-2'
  }
  
  return `${baseIconClasses} ${sizeIconClasses[props.size]}`
})
</script>
