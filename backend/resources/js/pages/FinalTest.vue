<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">Tēmas gala tests</h2>
    
    <!-- Test Setup Form -->
    <form v-if="!current" class="flex gap-2 mb-4" @submit.prevent="start">
      <input v-model.number="topicId" type="number" min="1" class="border px-2 py-1 rounded-sm" placeholder="Topic ID" />
      <input v-model.number="questionCount" type="number" min="1" class="border px-2 py-1 rounded-sm" placeholder="Jaut. skaits" />
      <select v-model.number="grade" class="border px-2 py-1 rounded-sm">
        <option value="">Visas klases</option>
        <option v-for="g in 12" :key="g" :value="g">{{ g }}. klase</option>
      </select>
      <button class="px-3 py-1.5 bg-black text-white rounded-sm">Sākt</button>
    </form>

    <!-- Test in Progress -->
    <div v-if="current && !current.submitted_at">
      <div class="mb-4 p-4 bg-gray-50 rounded">
        <div class="flex justify-between items-center mb-2">
          <span class="text-sm">Tests #{{ current.id }} | Jautājumi: {{ current.total_questions }}</span>
          <div v-if="timeLeft !== null" class="text-sm font-mono">Atlikušais laiks: {{ timeLeft }}s</div>
        </div>
        
        <!-- Questions -->
        <div v-if="questions.length > 0" class="space-y-4">
          <div v-for="(question, index) in questions" :key="question.id" class="border p-4 rounded">
            <h3 class="font-medium mb-2">{{ index + 1 }}. {{ question.question_text }}</h3>
            <div class="space-y-2">
              <label v-for="answer in question.answers" :key="answer.id" class="flex items-center space-x-2">
                <input 
                  type="radio" 
                  :name="`question_${question.id}`" 
                  :value="answer.id"
                  v-model="answers[question.id]"
                  class="rounded"
                />
                <span>{{ answer.answer_text }}</span>
              </label>
            </div>
          </div>
          
          <button 
            @click="submitTest" 
            :disabled="submitting || !allQuestionsAnswered"
            class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:bg-gray-400"
          >
            {{ submitting ? 'Iesniedz...' : 'Iesniegt testu' }}
          </button>
        </div>
        
        <!-- Loading Questions -->
        <div v-else class="text-center py-4">
          <div class="text-gray-500">Ielādē jautājumus...</div>
        </div>
      </div>
    </div>

    <!-- Test Results -->
    <div v-if="current && current.submitted_at" class="p-4 bg-gray-50 rounded">
      <h3 class="text-lg font-semibold mb-2">Testa rezultāti</h3>
      <div class="space-y-2">
        <div>Pareizi atbildēti: {{ current.score_correct }} / {{ current.score_total }}</div>
        <div>Procents: {{ Math.round((current.score_correct / current.score_total) * 100) }}%</div>
        <div class="font-semibold">
          <span v-if="current.passed" class="text-green-600">NOKĀRTOTS ✅</span>
          <span v-else class="text-red-600">NEKĀRTOTS ❌</span>
        </div>
      </div>
      <button @click="goToDashboard" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
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


