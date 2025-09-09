<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h2 class="text-2xl font-semibold text-gray-100 mb-6">Tēmas gala tests</h2>
    
    <!-- Test Setup Form -->
    <form v-if="!current" class="rounded-2xl bg-gray-800 shadow-sm border border-gray-700 p-6 hover:shadow-md transition mb-6" @submit.prevent="start">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Tēmas ID</label>
          <input 
            v-model.number="topicId" 
            type="number" 
            min="1" 
            class="w-full rounded-xl border border-gray-600 bg-gray-800 text-gray-100 placeholder-gray-400 px-4 py-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 outline-none" 
            placeholder="Tēmas ID" 
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Jautājumu skaits</label>
          <input 
            v-model.number="questionCount" 
            type="number" 
            min="1" 
            class="w-full rounded-xl border border-gray-600 bg-gray-800 text-gray-100 placeholder-gray-400 px-4 py-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 outline-none" 
            placeholder="Jautājumu skaits" 
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Klase</label>
          <select v-model.number="grade" class="w-full rounded-xl border border-gray-600 bg-gray-800 text-gray-100 px-4 py-2 focus:border-emerald-500 focus:ring focus:ring-emerald-200 outline-none">
            <option value="">Visas klases</option>
            <option v-for="g in 12" :key="g" :value="g">{{ g }}. klase</option>
          </select>
        </div>
        <div class="flex items-end">
          <button class="w-full rounded-full bg-emerald-500 text-white px-6 py-3 font-semibold shadow hover:bg-emerald-600 active:scale-95 transition">
            Sākt testu
          </button>
        </div>
      </div>
    </form>

    <!-- Test in Progress -->
    <div v-if="current && !current.submitted_at">
      <div class="mb-6 rounded-2xl bg-gray-800 shadow-sm border border-gray-700 p-6 hover:shadow-md transition">
        <div class="flex justify-between items-center mb-4">
          <span class="text-sm text-gray-300">Tests #{{ current.id }} | Jautājumi: {{ current.total_questions }}</span>
          <div v-if="timeLeft !== null" class="text-sm font-mono text-amber-400">Atlikušais laiks: {{ timeLeft }}s</div>
        </div>
        
        <!-- Questions -->
        <div v-if="questions.length > 0" class="space-y-6">
          <div v-for="(question, index) in questions" :key="question.id" class="rounded-2xl bg-gray-800 shadow-sm border border-gray-700 p-6 hover:shadow-md transition">
            <h3 class="text-xl font-semibold text-gray-200 mb-3">{{ index + 1 }}. {{ question.question_text }}</h3>
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
            {{ submitting ? 'Iesniedz...' : 'Iesniegt testu' }}
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
      <h3 class="text-2xl font-semibold text-gray-100 mb-4">Testa rezultāti</h3>
      <div class="space-y-3 mb-6">
        <div class="text-base text-gray-300">Pareizi atbildēti: {{ current.score_correct }} / {{ current.score_total }}</div>
        <div class="text-base text-gray-300">Procents: {{ Math.round((current.score_correct / current.score_total) * 100) }}%</div>
        <div class="text-lg font-semibold">
          <span v-if="current.passed" class="text-emerald-400">NOKĀRTOTS ✅</span>
          <span v-else class="text-red-400">NEKĀRTOTS ❌</span>
        </div>
      </div>
      <button @click="goToDashboard" class="rounded-full bg-emerald-500 text-white px-6 py-3 font-semibold shadow hover:bg-emerald-600 active:scale-95 transition">
        Atpakaļ uz sākumlapu
      </button>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed, watch, onBeforeUnmount } from 'vue'
import { useStore } from 'vuex'
import { useRoute, useRouter } from 'vue-router'

const store = useStore()
const route = useRoute()
const router = useRouter()

const current = computed(() => store.state.tests.current)
const questions = computed(() => store.state.tests.questions)
const submitting = computed(() => store.state.tests.submitting)

const topicId = ref(Number(route.query.topic_id) || 1)
const questionCount = ref(5) // Default to 5 questions
const grade = ref(Number(route.query.grade) || null)
const timeLeft = ref(null)
const answers = ref({})
let timer = null

const allQuestionsAnswered = computed(() => {
  if (questions.value.length === 0) return false
  return questions.value.every(q => answers.value[q.id])
})

const start = async () => {
  const t = await store.dispatch('tests/startFinal', { 
    topic_id: topicId.value, 
    question_count: questionCount.value,
    time_limit_seconds: 600, // 10 minutes
    grade: grade.value || undefined
  })
  
  if (t) {
    // Fetch questions for the test
    await store.dispatch('tests/fetchQuestions')
    
    // Start timer if time limit is set
    if (t.time_limit_seconds) {
      timeLeft.value = t.time_limit_seconds
      clearInterval(timer)
      timer = setInterval(() => {
        if (timeLeft.value > 0) timeLeft.value -= 1
        else {
          clearInterval(timer)
          // Auto-submit when time runs out
          submitTest()
        }
      }, 1000)
    }
  }
}

const submitTest = async () => {
  if (!allQuestionsAnswered.value) return
  
  // Send actual answers for validation
  const res = await store.dispatch('tests/submitFinal', { 
    answers: answers.value
  })
  
  if (res?.passed) {
    await store.dispatch('gamification/fetch')
  }
  
  clearInterval(timer)
}

const goToDashboard = () => {
  router.push({ name: 'dashboard' })
}

onMounted(() => {
  if (route.query.topic_id) {
    start()
  }
})

onBeforeUnmount(() => { 
  clearInterval(timer) 
})
</script>


