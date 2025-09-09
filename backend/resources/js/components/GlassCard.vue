<template>
  <div
    :class="cardClasses"
    @click="$emit('click', $event)"
  >
    <div v-if="$slots.header" class="mb-4">
      <slot name="header" />
    </div>
    
    <div class="flex-1">
      <slot />
    </div>
    
    <div v-if="$slots.footer" class="mt-4">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'gradient', 'elevated'].includes(value)
  },
  clickable: {
    type: Boolean,
    default: false
  },
  padding: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
  }
})

const emit = defineEmits(['click'])

const cardClasses = computed(() => {
  const baseClasses = 'rounded-2xl backdrop-blur-md border border-white/30 shadow-md transition-all duration-300 hover:shadow-lg'
  
  const variantClasses = {
    default: 'bg-white/60 hover:bg-white/70',
    gradient: 'bg-gradient-to-br from-white/40 via-gray-50/30 to-white/20 hover:from-white/50 hover:via-gray-50/40 hover:to-white/30',
    elevated: 'bg-white/80 hover:shadow-xl hover:-translate-y-1 hover:bg-white/90'
  }
  
  const paddingClasses = {
    sm: 'p-4 sm:p-5',
    md: 'p-6 sm:p-7',
    lg: 'p-8 sm:p-10',
    xl: 'p-10 sm:p-12'
  }
  
  const clickableClasses = props.clickable ? 'cursor-pointer hover:scale-[1.02] active:scale-[0.98]' : ''
  
  return `${baseClasses} ${variantClasses[props.variant]} ${paddingClasses[props.padding]} ${clickableClasses}`
})
</script>
