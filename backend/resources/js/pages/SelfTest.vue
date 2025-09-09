<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h2 class="text-2xl font-semibold text-gray-100 mb-6">Adaptīvais pašpārbaudes tests</h2>
    
    <!-- Test in Progress -->
    <div v-if="current && !current.submitted_at">
      <div class="mb-6 rounded-2xl bg-gray-800 shadow-sm border border-gray-700 p-6 hover:shadow-md transition">
        <div class="flex justify-between items-center mb-4">
          <span class="text-sm text-gray-300">Pašpārbaudes tests #{{ current.id }} | Jautājumi: {{ current.total_questions }}</span>
          <div class="text-sm text-sky-400">Nav laika ierobežojuma</div>
        </div>
        
        <!-- Questions -->
        <div v-if="questions.length > 0" class="space-y-6">
          <div v-for="(question, index) in questions" :key="question.id" class="rounded-2xl bg-gray-800 shadow-sm border border-gray-700 p-6 hover:shadow-md transition">
            <h3 class="text-xl font-semibold text-gray-200 mb-3">{{ index + 1 }}. {{ question.question_text }}</h3>
            <div class="text-sm text-gray-400 mb-4">Grūtība: {{ getDifficultyLabel(question.difficulty) }} | Punkti: {{ question.points }}</div>
            <div class="space-y-3">
              <label v-for="answer in question.answers" :key="answer.id" class="flex items-center space-x-3 p-3 rounded-xl border border-gray-600 hover:border-emerald-400 hover:bg-emerald-900/20 transition-all duration-200 cursor-pointer">
                <input 
                  type="radio" 
                  :name="`question_${question.id}`" 
                  :value="answer.id"
                  v-model="answers[question.id]"
                  class="w-5 h-5 text-emerald-500 focus:ring-emerald-200 focus:ring-2"
                />
                <span class="text-base text-gray-300 leading-relaxed">{{ answer.answer_text }}</span>
              </label>
            </div>
          </div>
          
          <button 
            @click="submitTest" 
            :disabled="submitting || !allQuestionsAnswered"
            class="w-full rounded-full bg-emerald-500 text-white px-6 py-3 font-semibold shadow hover:bg-emerald-600 active:scale-95 transition disabled:bg-gray-600 disabled:active:scale-100"
          >
            {{ submitting ? 'Iesniedz...' : 'Iesniegt pašpārbaudes testu' }}
          </button>
        </div>
        
        <!-- Loading Questions -->
        <div v-else class="text-center py-8">
          <div class="text-base text-gray-300">Ielādē jautājumus...</div>
        </div>
      </div>
    </div>

    <!-- Test Results -->
    <div v-if="current && current.submitted_at" class="rounded-2xl bg-gray-800 shadow-sm border border-gray-700 p-6 hover:shadow-md transition">
      <h3 class="text-2xl font-semibold text-gray-100 mb-4">Pašpārbaudes testa rezultāti</h3>
      <div class="space-y-3 mb-6">
        <div class="text-base text-gray-300">Pareizi atbildēti: {{ current.score_correct }} / {{ current.score_total }}</div>
        <div class="text-base text-gray-300">Procents: {{ Math.round((current.score_correct / current.score_total) * 100) }}%</div>
        <div v-if="xpAwarded" class="text-emerald-400 font-semibold text-lg">
          Iegūti XP: {{ xpAwarded }}
        </div>
      </div>
      
      <!-- Performance Analysis -->
      <div v-if="performanceAnalysis" class="mt-6 p-4 rounded-2xl bg-gray-700 border border-gray-600">
        <h4 class="text-xl font-semibold text-gray-200 mb-3">Veiktspējas analīze</h4>
        <div class="text-base text-gray-300 space-y-2">
          <div v-if="performanceAnalysis.strengths.length > 0">
            <strong class="text-emerald-400">Stiprās puses:</strong> {{ performanceAnalysis.strengths.join(', ') }}
          </div>
          <div v-if="performanceAnalysis.weaknesses.length > 0">
            <strong class="text-red-400">Vājās puses:</strong> {{ performanceAnalysis.weaknesses.join(', ') }}
          </div>
        </div>
      </div>
      
      <button @click="goToDashboard" class="mt-6 rounded-full bg-emerald-500 text-white px-6 py-3 font-semibold shadow hover:bg-emerald-600 active:scale-95 transition">
        Atpakaļ uz sākumlapu
      </button>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed, onBeforeUnmount } from 'vue'
import { useStore } from 'vuex'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const store = useStore()
const route = useRoute()
const router = useRouter()

const current = computed(() => store.state.tests.current)
const questions = computed(() => store.state.tests.questions)
const submitting = computed(() => store.state.tests.submitting)

const answers = ref({})
const xpAwarded = ref(0)
const performanceAnalysis = ref(null)

const allQuestionsAnswered = computed(() => {
  if (questions.value.length === 0) return false
  return questions.value.every(q => answers.value[q.id])
})

const getDifficultyLabel = (difficulty) => {
  const labels = {
    'easy': 'Viegli',
    'medium': 'Vidēji',
    'hard': 'Grūti'
  }
  return labels[difficulty] || difficulty
}

const submitTest = async () => {
  if (!allQuestionsAnswered.value) return
  
  // Send actual answers for validation
  const res = await store.dispatch('tests/submitSelf', { 
    answers: answers.value
  })
  
  if (res) {
    xpAwarded.value = res.xp_awarded || 0
    performanceAnalysis.value = res.performance_analysis || null
    
    // Refresh gamification data
    await store.dispatch('gamification/fetch')
  }
}

const goToDashboard = () => {
  router.push({ name: 'dashboard' })
}

onMounted(async () => {
  const testId = route.query.test_id
  if (testId) {
    // If test is not in store, fetch it first
    if (!current.value || current.value.id != testId) {
      try {
        const { data } = await axios.get(`/tests/${testId}`)
        store.commit('tests/setCurrent', data)
      } catch (error) {
        console.error('Error fetching test:', error)
        router.push({ name: 'dashboard' })
        return
      }
    }
    // Fetch questions for the existing test
    await store.dispatch('tests/fetchQuestions')
  } else {
    // If no test_id, redirect to dashboard
    router.push({ name: 'dashboard' })
  }
})
</script>
