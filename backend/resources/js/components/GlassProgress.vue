<template>
  <div class="w-full">
    <div v-if="label" class="flex justify-between items-center mb-2">
      <span class="text-sm font-medium text-gray-700">{{ label }}</span>
      <span class="text-sm text-gray-500">{{ displayValue }}%</span>
    </div>
    
    <div class="w-full h-2 rounded-full bg-white/30">
      <div
        :class="progressClasses"
        :style="{ width: `${Math.min(100, Math.max(0, value))}%` }"
      />
    </div>
    
    <div v-if="showSteps && steps" class="flex justify-between mt-2">
      <span
        v-for="(step, index) in steps"
        :key="index"
        :class="stepClasses(index)"
        class="text-xs"
      >
        {{ step }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  value: {
    type: Number,
    default: 0
  },
  max: {
    type: Number,
    default: 100
  },
  label: {
    type: String,
    default: ''
  },
  color: {
    type: String,
    default: 'emerald',
    validator: (value) => ['emerald', 'blue', 'indigo', 'purple', 'pink', 'red', 'amber'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  showSteps: {
    type: Boolean,
    default: false
  },
  steps: {
    type: Array,
    default: () => []
  }
})

const displayValue = computed(() => {
  return Math.round((props.value / props.max) * 100)
})

const progressClasses = computed(() => {
  const baseClasses = 'h-2 rounded-full transition-all duration-500 ease-out'
  
  const colorClasses = {
    emerald: 'bg-emerald-400',
    blue: 'bg-blue-400',
    indigo: 'bg-indigo-400',
    purple: 'bg-purple-400',
    pink: 'bg-pink-400',
    red: 'bg-red-400',
    amber: 'bg-amber-400'
  }
  
  const sizeClasses = {
    sm: 'h-1',
    md: 'h-2',
    lg: 'h-3'
  }
  
  return `${baseClasses} ${colorClasses[props.color]} ${sizeClasses[props.size]}`
})

const stepClasses = (index) => {
  const currentStep = Math.floor((props.value / props.max) * props.steps.length)
  return index <= currentStep ? 'text-gray-700 font-medium' : 'text-gray-400'
}
</script>
