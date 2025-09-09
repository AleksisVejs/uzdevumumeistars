<template>
  <div class="rounded-2xl bg-white/60 backdrop-blur-md border border-white/30 shadow-md p-6 hover:shadow-lg transition-all duration-300">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-xl font-semibold text-gray-800">{{ title }}</h3>
      <div v-if="showPeriod" class="flex space-x-2">
        <button
          v-for="period in periods"
          :key="period"
          :class="periodButtonClasses(period)"
          @click="$emit('period-change', period)"
        >
          {{ period }}
        </button>
      </div>
    </div>
    
    <div class="space-y-3">
      <div
        v-for="(item, index) in items"
        :key="item.id || index"
        :class="leaderboardItemClasses(index)"
      >
        <div class="flex items-center space-x-4">
          <!-- Rank -->
          <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center">
            <div v-if="index < 3" :class="rankClasses(index)">
              <Trophy class="w-5 h-5 text-white" />
            </div>
            <span v-else class="text-sm font-medium text-gray-500">{{ index + 1 }}</span>
          </div>
          
          <!-- Avatar -->
          <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
              <span class="text-white font-semibold text-sm">{{ item.name?.charAt(0) || '?' }}</span>
            </div>
          </div>
          
          <!-- Name and Details -->
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ item.name || 'Anonymous' }}</p>
            <p v-if="item.details" class="text-xs text-gray-500">{{ item.details }}</p>
          </div>
          
          <!-- Score/Value -->
          <div class="flex-shrink-0 text-right">
            <p class="text-sm font-semibold text-gray-900">{{ item.score || item.value || 0 }}</p>
            <p v-if="item.subtitle" class="text-xs text-gray-500">{{ item.subtitle }}</p>
          </div>
        </div>
      </div>
    </div>
    
    <div v-if="showViewAll" class="mt-6 text-center">
      <button
        class="rounded-full bg-white/60 backdrop-blur-md border border-indigo-500/30 text-indigo-600 px-6 py-3 font-semibold hover:bg-indigo-50/60 hover:border-indigo-500/50 hover:-translate-y-1 active:scale-95 transition-all text-sm"
        @click="$emit('view-all')"
      >
        View All
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Trophy } from 'lucide-vue-next'

const props = defineProps({
  title: {
    type: String,
    default: 'Leaderboard'
  },
  items: {
    type: Array,
    default: () => []
  },
  showPeriod: {
    type: Boolean,
    default: false
  },
  periods: {
    type: Array,
    default: () => ['Today', 'Week', 'Month', 'All Time']
  },
  showViewAll: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['period-change', 'view-all'])

const leaderboardItemClasses = (index) => {
  const baseClasses = 'leaderboard-item'
  const topThreeClasses = index < 3 ? 'ring-2 ring-orange-200' : ''
  return `${baseClasses} ${topThreeClasses}`
}

const rankClasses = (index) => {
  const baseClasses = 'w-8 h-8 rounded-full flex items-center justify-center'
  const colorClasses = {
    0: 'bg-gradient-to-br from-yellow-400 to-yellow-500', // Gold
    1: 'bg-gradient-to-br from-gray-300 to-gray-400',     // Silver
    2: 'bg-gradient-to-br from-amber-600 to-amber-700'    // Bronze
  }
  return `${baseClasses} ${colorClasses[index]}`
}

const periodButtonClasses = (period) => {
  // This would need to be managed by parent component for active state
  return 'px-3 py-1 text-xs rounded-full bg-white/60 backdrop-blur-sm border border-white/30 text-gray-600 hover:bg-white/80 transition-all'
}
</script>
