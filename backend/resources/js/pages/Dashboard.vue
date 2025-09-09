<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Login Form -->
    <div v-if="!isLoggedIn" class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <div class="text-center">
          <h1 class="text-4xl font-semibold text-gray-100 mb-2">Uzdevumu Meistars</h1>
          <p class="text-base text-gray-300 leading-relaxed">Latvijas matemātikas mācību platforma</p>
        </div>
        <div class="rounded-2xl bg-gray-800 shadow-sm border border-gray-700 p-8 hover:shadow-md transition">
          <h2 class="text-2xl font-semibold text-gray-100 mb-6 text-center">Pieteikties</h2>
          <form @submit.prevent="login" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">E-pasts</label>
              <input 
                v-model="email" 
                type="email" 
                required 
                class="w-full rounded-xl border border-gray-600 bg-gray-800 text-gray-100 placeholder-gray-400 px-4 py-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 outline-none transition-all duration-200"
                placeholder="jūsu@epasts.lv"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Parole</label>
              <input 
                v-model="password" 
                type="password" 
                required 
                class="w-full rounded-xl border border-gray-600 bg-gray-800 text-gray-100 placeholder-gray-400 px-4 py-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 outline-none transition-all duration-200"
                placeholder="Jūsu parole"
              >
            </div>
            <button 
              type="submit" 
              class="w-full rounded-full bg-emerald-500 text-white px-6 py-3 font-semibold shadow hover:bg-emerald-600 active:scale-95 transition"
            >
              Pieteikties
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Dashboard Content -->
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-4xl font-semibold text-gray-100 mb-2">Uzdevumu Meistars</h1>
          <p class="text-base text-gray-300 leading-relaxed">Latvijas matemātikas mācību platforma</p>
        </div>
        <div class="flex space-x-3">
          <button 
            @click="refresh" 
            class="rounded-full border border-indigo-500 text-indigo-400 px-6 py-3 font-semibold hover:bg-indigo-900/20 active:scale-95 transition"
          >
            🔄 Atsvaidzināt
          </button>
          <button 
            @click="logout" 
            class="rounded-full bg-red-500 text-white px-6 py-3 font-semibold shadow hover:bg-red-600 active:scale-95 transition"
          >
            Iziet
          </button>
        </div>
      </div>

      <!-- XP and Badges -->
      <div class="mb-8 rounded-2xl bg-gray-800 shadow-sm border border-gray-700 p-6 hover:shadow-md transition">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-semibold text-gray-100 mb-2">Jūsu sasniegumi</h2>
            <div class="flex items-center space-x-4">
              <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                  <span class="text-white font-bold">⭐</span>
                </div>
                <span class="text-xl font-semibold text-gray-100">{{ xp }} XP</span>
              </div>
            </div>
          </div>
          <div v-if="badges.length > 0" class="text-right">
            <h3 class="text-lg font-semibold text-gray-200 mb-2">Nozīmītes:</h3>
            <div class="flex flex-wrap gap-2 justify-end">
              <span 
                v-for="badge in badges" 
                :key="badge.id" 
                class="rounded-full bg-indigo-900/30 text-indigo-300 p-3 flex items-center justify-center text-sm font-medium border border-indigo-700"
              >
                🏆 {{ badge.name }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Topics Grid -->
      <div class="mb-6">
        <h2 class="text-3xl font-semibold text-gray-100 mb-2">Tēmas</h2>
        <p class="text-base text-gray-300 leading-relaxed">Izvēlieties tēmu un sāciet mācīties!</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="t in topics" 
          :key="t.id" 
          class="rounded-2xl bg-gray-800 shadow-sm border border-gray-700 p-6 hover:shadow-md transition-all duration-300 hover:translate-y-[-2px] border-l-4"
          :class="getTopicBorderColor(t.id)"
        >
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-xl font-semibold text-gray-200 mb-2">{{ t.name }}</h3>
              <div class="flex items-center space-x-2 mb-3">
                <span class="px-2 py-1 bg-gray-700 text-gray-300 rounded-full text-xs font-medium">
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
                v-if="hasQuestionsForGrade(t.id, 7)"
                @click="startSelfTest(t.id, 7)"
                class="flex-1 rounded-full bg-emerald-500 text-white px-4 py-2 text-sm font-semibold shadow hover:bg-emerald-600 active:scale-95 transition"
              >
                📚 Self-test 7.kl
              </button>
              <button 
                v-if="hasQuestionsForGrade(t.id, 10)"
                @click="startSelfTest(t.id, 10)"
                class="flex-1 rounded-full bg-sky-500 text-white px-4 py-2 text-sm font-semibold shadow hover:bg-sky-600 active:scale-95 transition"
              >
                📚 Self-test 10.kl
              </button>
            </div>
            <div class="flex flex-wrap gap-2">
              <router-link 
                :to="{ name: 'final-test', query: { topic_id: t.id, grade: 7 } }" 
                class="flex-1 rounded-full border border-indigo-500 text-indigo-400 px-4 py-2 text-sm font-semibold hover:bg-indigo-900/20 active:scale-95 transition text-center"
              >
                🎯 Gala 7.kl
              </router-link>
              <router-link 
                :to="{ name: 'final-test', query: { topic_id: t.id, grade: 10 } }" 
                class="flex-1 rounded-full border border-indigo-500 text-indigo-400 px-4 py-2 text-sm font-semibold hover:bg-indigo-900/20 active:scale-95 transition text-center"
              >
                🎯 Gala 10.kl
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import axios from 'axios'

const store = useStore()
const router = useRouter()
const topics = computed(() => store.state.topics.list)
const xp = computed(() => store.state.gamification.xp)
const badges = computed(() => store.state.gamification.badges)
const progress = computed(() => store.state.gamification.progress)

// Track which topics have questions for each grade
const topicQuestionAvailability = ref({})

const isLoggedIn = computed(() => !!localStorage.getItem('token'))

const email = ref('test@example.com')
const password = ref('password')

const getTopicStatus = (topicId) => {
  const topicProgress = progress.value.find(p => p.topic_id === topicId)
  return topicProgress ? topicProgress.status : 'locked'
}

const getTopicBorderColor = (topicId) => {
  const status = getTopicStatus(topicId)
  switch (status) {
    case 'passed': return 'border-emerald-500'
    case 'unlocked': return 'border-indigo-500'
    case 'failed': return 'border-red-500'
    default: return 'border-gray-300'
  }
}

const getStatusBadgeClass = (topicId) => {
  const status = getTopicStatus(topicId)
  switch (status) {
    case 'passed': return 'bg-emerald-900/30 text-emerald-300 border border-emerald-700'
    case 'unlocked': return 'bg-indigo-900/30 text-indigo-300 border border-indigo-700'
    case 'failed': return 'bg-red-900/30 text-red-300 border border-red-700'
    default: return 'bg-gray-700 text-gray-300 border border-gray-600'
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

const hasQuestionsForGrade = (topicId, grade) => {
  const key = `${topicId}-${grade}`
  return topicQuestionAvailability.value[key] || false
}

const loadQuestionAvailability = async () => {
  if (!isLoggedIn.value) return
  
  const grades = [7, 10]
  const promises = []
  
  topics.value.forEach(topic => {
    grades.forEach(grade => {
      const key = `${topic.id}-${grade}`
      if (topicQuestionAvailability.value[key] === undefined) {
        promises.push(
          axios.get(`/topics/${topic.id}/has-questions/${grade}`)
            .then(({ data }) => {
              topicQuestionAvailability.value[key] = data.has_questions
            })
            .catch(error => {
              console.error(`Error checking questions for topic ${topic.id} grade ${grade}:`, error)
              topicQuestionAvailability.value[key] = false
            })
        )
      }
    })
  })
  
  await Promise.all(promises)
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
  if (!isLoggedIn.value) return
  try {
    await Promise.all([
      store.dispatch('topics/fetch'),
      store.dispatch('gamification/fetch'),
    ])
    // Load question availability after topics are fetched
    await loadQuestionAvailability()
  } catch (e) {
    // ignore, handled by axios interceptor / UI state
  }
}

const login = async () => {
  try {
    const { data } = await axios.post('/auth/login', { email: email.value, password: password.value })
    localStorage.setItem('token', data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
    await refresh()
  } catch (e) {
    // simple alert for now; can replace with nicer UI later
    alert('Pieteikšanās neizdevās')
  }
}

onMounted(refresh)

const logout = () => {
  localStorage.removeItem('token')
  delete axios.defaults.headers.common['Authorization']
  window.location.reload()
}
</script>


