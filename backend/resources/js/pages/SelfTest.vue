<template>
  <div class="container-main py-8">
    <h2 class="text-page-title mb-6">Adaptīvais pašpārbaudes tests</h2>
    
    <!-- Test in Progress -->
    <div v-if="current && !current.submitted_at">
      <GlassCard class="mb-6">
        <div class="flex justify-between items-center mb-4">
          <span class="text-small">Pašpārbaudes tests #{{ current.id }} | Jautājumi: {{ current.total_questions }}</span>
          <div class="text-sm text-sky-500">Nav laika ierobežojuma</div>
        </div>
        
        <!-- Questions -->
        <div v-if="questions.length > 0" class="space-y-6">
          <GlassCard 
            v-for="(question, index) in questions" 
            :key="question.id" 
            variant="elevated"
            padding="md"
          >
            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ index + 1 }}. {{ question.question_text }}</h3>
            <div class="text-small mb-4">Grūtība: {{ getDifficultyLabel(question.difficulty) }} | Punkti: {{ question.points }}</div>
            <div class="space-y-3">
              <label v-for="answer in question.answers" :key="answer.id" class="flex items-center space-x-3 p-3 rounded-xl border border-gray-300 hover:border-emerald-400 hover:bg-emerald-50/60 transition-all duration-200 cursor-pointer">
                <input 
                  type="radio" 
                  :name="`question_${question.id}`" 
                  :value="answer.id"
                  v-model="answers[question.id]"
                  class="w-5 h-5 text-emerald-500 focus:ring-emerald-200 focus:ring-2"
                />
                <span class="text-body">{{ answer.answer_text }}</span>
              </label>
            </div>
          </GlassCard>
          
          <GlassButton 
            @click="submitTest" 
            :disabled="submitting || !allQuestionsAnswered"
            variant="primary"
            size="lg"
            class="w-full"
          >
            {{ submitting ? 'Iesniedz...' : 'Iesniegt pašpārbaudes testu' }}
          </GlassButton>
        </div>
        
        <!-- Loading Questions -->
        <div v-else class="text-center py-8">
          <div class="text-body">Ielādē jautājumus...</div>
        </div>
      </GlassCard>
    </div>

    <!-- Test Results -->
    <GlassCard v-if="current && current.submitted_at" variant="gradient">
      <h3 class="text-section-title mb-4">Pašpārbaudes testa rezultāti</h3>
      <div class="space-y-3 mb-6">
        <div class="text-body">Pareizi atbildēti: {{ current.score_correct }} / {{ current.score_total }}</div>
        <div class="text-body">Procents: {{ Math.round((current.score_correct / current.score_total) * 100) }}%</div>
        <div v-if="xpAwarded" class="text-emerald-500 font-semibold text-lg">
          Iegūti XP: {{ xpAwarded }}
        </div>
      </div>
      
      <!-- Performance Analysis -->
      <GlassCard v-if="performanceAnalysis" variant="elevated" class="mt-6">
        <h4 class="text-xl font-semibold text-gray-900 mb-3">Veiktspējas analīze</h4>
        <div class="text-body space-y-2">
          <div v-if="performanceAnalysis.strengths.length > 0">
            <strong class="text-emerald-500">Stiprās puses:</strong> {{ performanceAnalysis.strengths.join(', ') }}
          </div>
          <div v-if="performanceAnalysis.weaknesses.length > 0">
            <strong class="text-red-500">Vājās puses:</strong> {{ performanceAnalysis.weaknesses.join(', ') }}
          </div>
        </div>
      </GlassCard>
      
      <GlassButton 
        @click="goToDashboard" 
        variant="primary"
        :icon="Home"
        class="mt-6"
      >
        Atpakaļ uz sākumlapu
      </GlassButton>
    </GlassCard>
  </div>
</template>

<script setup>
import { onMounted, ref, computed, onBeforeUnmount } from 'vue'
import { useStore } from 'vuex'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import { Home } from 'lucide-vue-next'

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
        store.dispatch('toast/error', {
          title: 'Kļūda',
          message: 'Neizdevās ielādēt testu. Tēma var būt bloķēta.'
        })
        router.push({ name: 'dashboard' })
        return
      }
    }
    // Fetch questions for the existing test
    try {
      await store.dispatch('tests/fetchQuestions')
    } catch (error) {
      console.error('Error fetching questions:', error)
      store.dispatch('toast/error', {
        title: 'Kļūda',
        message: 'Neizdevās ielādēt jautājumus.'
      })
      router.push({ name: 'dashboard' })
    }
  } else {
    // If no test_id, redirect to dashboard
    router.push({ name: 'dashboard' })
  }
})
</script>
