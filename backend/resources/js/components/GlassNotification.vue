<template>
  <Transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="opacity-0 translate-x-full"
    enter-to-class="opacity-100 translate-x-0"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="opacity-100 translate-x-0"
    leave-to-class="opacity-0 translate-x-full"
  >
    <div
      v-if="show"
      :class="notificationClasses"
    >
      <div class="flex items-start space-x-3">
        <!-- Icon -->
        <div class="flex-shrink-0">
          <div :class="iconContainerClasses">
            <component
              :is="iconComponent"
              class="w-5 h-5"
            />
          </div>
        </div>
        
        <!-- Content -->
        <div class="flex-1 min-w-0">
          <h4 v-if="title" class="text-sm font-medium text-gray-900">
            {{ title }}
          </h4>
          <p class="text-sm text-gray-600">
            <slot>{{ message }}</slot>
          </p>
        </div>
        
        <!-- Actions -->
        <div class="flex-shrink-0 flex space-x-2">
          <button
            v-if="actionText"
            @click="$emit('action')"
            class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors"
          >
            {{ actionText }}
          </button>
          
          <button
            v-if="dismissible"
            @click="$emit('dismiss')"
            class="p-1 hover:bg-gray-100/50 rounded-full transition-colors"
          >
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: true
  },
  type: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
  },
  title: {
    type: String,
    default: ''
  },
  message: {
    type: String,
    default: ''
  },
  actionText: {
    type: String,
    default: ''
  },
  dismissible: {
    type: Boolean,
    default: true
  },
  duration: {
    type: Number,
    default: 5000
  }
})

const emit = defineEmits(['action', 'dismiss'])

const notificationClasses = computed(() => {
  const baseClasses = 'rounded-xl backdrop-blur-md border p-4 shadow-lg max-w-sm w-full'
  
  const typeClasses = {
    success: 'bg-emerald-50/80 border-emerald-200/50',
    error: 'bg-red-50/80 border-red-200/50',
    warning: 'bg-amber-50/80 border-amber-200/50',
    info: 'bg-blue-50/80 border-blue-200/50'
  }
  
  return `${baseClasses} ${typeClasses[props.type]}`
})

const iconContainerClasses = computed(() => {
  const baseClasses = 'w-8 h-8 rounded-full flex items-center justify-center'
  
  const typeClasses = {
    success: 'bg-emerald-100 text-emerald-600',
    error: 'bg-red-100 text-red-600',
    warning: 'bg-amber-100 text-amber-600',
    info: 'bg-blue-100 text-blue-600'
  }
  
  return `${baseClasses} ${typeClasses[props.type]}`
})

const iconComponent = computed(() => {
  const icons = {
    success: 'svg',
    error: 'svg',
    warning: 'svg',
    info: 'svg'
  }
  return icons[props.type]
})
</script>
