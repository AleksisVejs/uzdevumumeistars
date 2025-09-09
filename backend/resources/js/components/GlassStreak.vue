<template>
  <div class="streak-card">
    <div class="flex-shrink-0">
      <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
      </div>
    </div>
    
    <div class="flex-1 min-w-0">
      <div class="flex items-center space-x-2">
        <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
        <span class="text-2xl font-bold text-orange-500">{{ count }}</span>
      </div>
      <p class="text-sm text-gray-600">{{ subtitle }}</p>
      
      <div v-if="showProgress" class="mt-2">
        <div class="flex justify-between text-xs text-gray-500 mb-1">
          <span>Progress</span>
          <span>{{ progress }}%</span>
        </div>
        <div class="w-full bg-white/30 rounded-full h-1.5">
          <div
            class="bg-gradient-to-r from-orange-400 to-red-500 h-1.5 rounded-full transition-all duration-500"
            :style="{ width: `${progress}%` }"
          />
        </div>
      </div>
    </div>
    
    <div v-if="showBadge" class="flex-shrink-0">
      <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center">
        <span class="text-sm font-bold text-orange-600">{{ badgeText }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    default: 'Streak'
  },
  subtitle: {
    type: String,
    default: 'Keep it going!'
  },
  count: {
    type: Number,
    default: 0
  },
  progress: {
    type: Number,
    default: 0
  },
  showProgress: {
    type: Boolean,
    default: false
  },
  showBadge: {
    type: Boolean,
    default: false
  },
  badgeText: {
    type: String,
    default: '🔥'
  }
})
</script>
