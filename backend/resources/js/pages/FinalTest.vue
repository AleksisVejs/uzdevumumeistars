<template>
  <div class="container-main py-8">
    <h2 class="text-page-title mb-6">Tēmas gala tests</h2>
    
    <!-- Test Setup Form -->
    <GlassCard v-if="!current" class="mb-6">
      <form @submit.prevent="start">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <GlassInput
            v-model.number="topicId"
            type="number"
            label="Tēmas ID"
            placeholder="Tēmas ID"
            :min="1"
          />
          <GlassInput
            v-model.number="questionCount"
            type="number"
            label="Jautājumu skaits"
            placeholder="Jautājumu skaits"
            :min="1"
          />
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Klase</label>
            <select v-model.number="grade" class="glass-input">
              <option value="">Visas klases</option>
              <option v-for="g in 12" :key="g" :value="g">{{ g }}. klase</option>
            </select>
          </div>
          <div class="flex items-end">
            <GlassButton
              type="submit"
              variant="primary"
              class="w-full"
            >
              Sākt testu
            </GlassButton>
          </div>
        </div>
      </form>
    </GlassCard>

    <!-- Test in Progress -->
    <div v-if="current && !current.submitted_at">
      <GlassCard class="mb-6">
        <div class="flex justify-between items-center mb-4">
          <span class="text-small">Tests #{{ current.id }} | Jautājumi: {{ current.total_questions }}</span>
          <div v-if="timeLeft !== null" class="text-sm font-mono text-amber-500">Atlikušais laiks: {{ timeLeft }}s</div>
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
            {{ submitting ? 'Iesniedz...' : 'Iesniegt testu' }}
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
      <h3 class="text-section-title mb-4">Testa rezultāti</h3>
      <div class="space-y-3 mb-6">
        <div class="text-body">Pareizi atbildēti: {{ current.score_correct }} / {{ current.score_total }}</div>
        <div class="text-body">Procents: {{ Math.round((current.score_correct / current.score_total) * 100) }}%</div>
        <div class="text-lg font-semibold">
          <span v-if="current.passed" class="text-emerald-500">NOKĀRTOTS ✅</span>
          <span v-else class="text-red-500">NEKĀRTOTS ❌</span>
        </div>
      </div>
      <GlassButton 
        @click="goToDashboard" 
        variant="primary"
        :icon="Home"
      >
        Atpakaļ uz sākumlapu
      </GlassButton>
    </GlassCard>
  </div>
</template>

<script setup>
import { onMounted, ref, computed, watch, onBeforeUnmount } from 'vue'
import { useStore } from 'vuex'
import { useRoute, useRouter } from 'vue-router'
import { Home } from 'lucide-vue-next'

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
  try {
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
  } catch (error) {
    console.error('Error starting final test:', error)
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: 'Neizdevās sākt gala testu. Tēma var būt bloķēta.'
    })
    router.push({ name: 'dashboard' })
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


