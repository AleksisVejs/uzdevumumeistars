<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">Adaptīvais pašpārbaudes tests</h2>
    
    <!-- Test in Progress -->
    <div v-if="current && !current.submitted_at">
      <div class="mb-4 p-4 bg-blue-50 rounded">
        <div class="flex justify-between items-center mb-2">
          <span class="text-sm">Pašpārbaudes tests #{{ current.id }} | Jautājumi: {{ current.total_questions }}</span>
          <div class="text-sm text-blue-600">Nav laika ierobežojuma</div>
        </div>
        
        <!-- Questions -->
        <div v-if="questions.length > 0" class="space-y-4">
          <div v-for="(question, index) in questions" :key="question.id" class="border p-4 rounded bg-white">
            <h3 class="font-medium mb-2">{{ index + 1 }}. {{ question.question_text }}</h3>
            <div class="text-xs text-gray-500 mb-2">Grūtība: {{ getDifficultyLabel(question.difficulty) }} | Punkti: {{ question.points }}</div>
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
            class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400"
          >
            {{ submitting ? 'Iesniedz...' : 'Iesniegt pašpārbaudes testu' }}
          </button>
        </div>
        
        <!-- Loading Questions -->
        <div v-else class="text-center py-4">
          <div class="text-gray-500">Ielādē jautājumus...</div>
        </div>
      </div>
    </div>

    <!-- Test Results -->
    <div v-if="current && current.submitted_at" class="p-4 bg-green-50 rounded">
      <h3 class="text-lg font-semibold mb-2">Pašpārbaudes testa rezultāti</h3>
      <div class="space-y-2">
        <div>Pareizi atbildēti: {{ current.score_correct }} / {{ current.score_total }}</div>
        <div>Procents: {{ Math.round((current.score_correct / current.score_total) * 100) }}%</div>
        <div v-if="xpAwarded" class="text-green-600 font-semibold">
          Iegūti XP: {{ xpAwarded }}
        </div>
      </div>
      
      <!-- Performance Analysis -->
      <div v-if="performanceAnalysis" class="mt-4 p-3 bg-white rounded border">
        <h4 class="font-medium mb-2">Veiktspējas analīze</h4>
        <div class="text-sm space-y-1">
          <div v-if="performanceAnalysis.strengths.length > 0">
            <strong>Stiprās puses:</strong> {{ performanceAnalysis.strengths.join(', ') }}
          </div>
          <div v-if="performanceAnalysis.weaknesses.length > 0">
            <strong>Vājās puses:</strong> {{ performanceAnalysis.weaknesses.join(', ') }}
          </div>
        </div>
      </div>
      
      <button @click="goToDashboard" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Atpakaļ uz sākumlapu
      </button>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed, onBeforeUnmount } from 'vue'
import { useStore } from 'vuex'
import { useRoute, useRouter } from 'vue-router'

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
    // Fetch questions for the existing test
    await store.dispatch('tests/fetchQuestions')
  }
})
</script>
