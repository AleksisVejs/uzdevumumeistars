<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Login Form -->
    <div v-if="!isLoggedIn" class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <div class="text-center">
          <h1 class="text-4xl font-bold text-gray-900 mb-2">Uzdevumu Meistars</h1>
          <p class="text-gray-600">Latvijas matemātikas mācību platforma</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-8">
          <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Pieteikties</h2>
          <form @submit.prevent="login" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">E-pasts</label>
              <input 
                v-model="email" 
                type="email" 
                required 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                placeholder="jūsu@epasts.lv"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Parole</label>
              <input 
                v-model="password" 
                type="password" 
                required 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                placeholder="Jūsu parole"
              >
            </div>
            <button 
              type="submit" 
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105"
            >
              Pieteikties
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Dashboard Content -->
    <div v-else class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-4xl font-bold text-gray-900 mb-2">Uzdevumu Meistars</h1>
          <p class="text-gray-600">Latvijas matemātikas mācību platforma</p>
        </div>
        <div class="flex space-x-3">
          <button 
            @click="refresh" 
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200"
          >
            🔄 Atsvaidzināt
          </button>
          <button 
            @click="logout" 
            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105"
          >
            Iziet
          </button>
        </div>
      </div>

      <!-- XP and Badges -->
      <div class="mb-8 p-6 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-xl text-white shadow-lg">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-bold mb-2">Jūsu sasniegumi</h2>
            <div class="flex items-center space-x-4">
              <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                  <span class="text-yellow-900 font-bold">⭐</span>
                </div>
                <span class="text-xl font-semibold">{{ xp }} XP</span>
              </div>
            </div>
          </div>
          <div v-if="badges.length > 0" class="text-right">
            <h3 class="text-lg font-semibold mb-2">Nozīmītes:</h3>
            <div class="flex flex-wrap gap-2 justify-end">
              <span 
                v-for="badge in badges" 
                :key="badge.id" 
                class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-sm font-medium shadow-md"
              >
                🏆 {{ badge.name }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Topics Grid -->
      <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Tēmas</h2>
        <p class="text-gray-600">Izvēlieties tēmu un sāciet mācīties!</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="t in topics" 
          :key="t.id" 
          class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4"
          :class="getTopicBorderColor(t.id)"
        >
          <div class="p-6">
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ t.name }}</h3>
                <div class="flex items-center space-x-2 mb-3">
                  <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                    Līmenis {{ t.prerequisite_level }}
                  </span>
                  <span 
                    class="px-2 py-1 rounded-full text-xs font-medium"
                    :class="getStatusBadgeClass(t.id)"
                  >
                    {{ getStatusText(t.id) }}
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="space-y-3">
              <div class="flex flex-wrap gap-2">
                <button 
                  @click="startSelfTest(t.id, 7)"
                  class="flex-1 px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm font-medium transition-all duration-200 transform hover:scale-105"
                >
                  📚 Self-test 7.kl
                </button>
                <button 
                  @click="startSelfTest(t.id, 10)"
                  class="flex-1 px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm font-medium transition-all duration-200 transform hover:scale-105"
                >
                  📚 Self-test 10.kl
                </button>
              </div>
              <div class="flex flex-wrap gap-2">
                <router-link 
                  :to="{ name: 'final-test', query: { topic_id: t.id, grade: 7 } }" 
                  class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-all duration-200 transform hover:scale-105 text-center"
                >
                  🎯 Gala 7.kl
                </router-link>
                <router-link 
                  :to="{ name: 'final-test', query: { topic_id: t.id, grade: 10 } }" 
                  class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-all duration-200 transform hover:scale-105 text-center"
                >
                  🎯 Gala 10.kl
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const store = useStore()
const router = useRouter()
const topics = computed(() => store.state.topics.list)
const xp = computed(() => store.state.gamification.xp)
const badges = computed(() => store.state.gamification.badges)
const progress = computed(() => store.state.gamification.progress)

const isLoggedIn = computed(() => !!localStorage.getItem('token'))

const getTopicStatus = (topicId) => {
  const topicProgress = progress.value.find(p => p.topic_id === topicId)
  return topicProgress ? topicProgress.status : 'locked'
}

const getTopicBorderColor = (topicId) => {
  const status = getTopicStatus(topicId)
  switch (status) {
    case 'passed': return 'border-green-500'
    case 'unlocked': return 'border-blue-500'
    case 'failed': return 'border-red-500'
    default: return 'border-gray-300'
  }
}

const getStatusBadgeClass = (topicId) => {
  const status = getTopicStatus(topicId)
  switch (status) {
    case 'passed': return 'bg-green-100 text-green-800'
    case 'unlocked': return 'bg-blue-100 text-blue-800'
    case 'failed': return 'bg-red-100 text-red-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}

const getStatusText = (topicId) => {
  const status = getTopicStatus(topicId)
  switch (status) {
    case 'passed': return 'Nokārtots'
    case 'unlocked': return 'Atvērts'
    case 'failed': return 'Neizdevās'
    default: return 'Bloķēts'
  }
}

const startSelfTest = async (topicId, grade) => {
  const test = await store.dispatch('tests/startSelf', { 
    topic_id: topicId, 
    question_count: 10,
    grade: grade
  })
  
  if (test) {
    // Navigate to self-test page (we'll create this)
    router.push({ name: 'self-test', query: { test_id: test.id } })
  }
}

const refresh = async () => {
  await Promise.all([
    store.dispatch('topics/fetch'),
    store.dispatch('gamification/fetch'),
  ])
}

onMounted(refresh)

import { ref } from 'vue'
import axios from 'axios'
const email = ref('test@example.com')
const password = ref('password')
const login = async () => {
  const { data } = await axios.post('/api/auth/login', { email: email.value, password: password.value })
  localStorage.setItem('token', data.token)
  axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
  await refresh()
}

const logout = () => {
  localStorage.removeItem('token')
  delete axios.defaults.headers.common['Authorization']
  window.location.reload()
}
</script>


