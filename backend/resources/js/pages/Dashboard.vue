<template>
  <div class="min-h-screen">
    <!-- Authentication Forms -->
    <div v-if="!isAuthenticated">
      <LoginForm v-if="authMode === 'login'" @switch-to-register="authMode = 'register'" />
      <RegisterForm v-else @switch-to-login="authMode = 'login'" />
    </div>

    <!-- Dashboard Content -->
    <div v-else class="container-main py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-page-title mb-2">Uzdevumu Meistars</h1>
        <p class="text-body">Latvijas matemātikas mācību platforma</p>
      </div>

      <!-- Student Welcome Section -->
      <GlassCard variant="gradient" class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <div class="flex-1">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-2">
              {{ getGreeting() }}, {{ user.name || 'Student' }}! 👋
            </h2>
            <p class="text-gray-700 mb-4 text-sm sm:text-base">
              {{ getMotivationalMessage() }}
            </p>
            <div class="flex flex-wrap gap-2">
              <GlassBadge
                :text="`${completedTopics}/${totalTopics} tēmas`"
                variant="success"
                :icon="CheckCircle"
                size="sm"
              />
              <GlassBadge
                :text="`${currentStreak} dienu sērija`"
                variant="warning"
                :icon="Flame"
                size="sm"
              />
            </div>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-6">
            <div class="text-center">
              <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-emerald-400 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-2">
                <span class="text-xl sm:text-2xl font-bold text-white">{{ Math.floor(xp / 100) }}</span>
              </div>
              <p class="text-xs sm:text-sm text-gray-600">Līmenis</p>
            </div>
          </div>
        </div>
      </GlassCard>

      <!-- Progress Overview -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <!-- Overall Progress -->
        <GlassCard class="lg:col-span-2">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Jūsu progress</h3>
            <div class="text-right">
              <span class="text-2xl font-bold text-emerald-600">{{ overallProgress }}%</span>
              <p class="text-sm text-gray-500">Kopējais progress</p>
            </div>
          </div>
          <GlassProgress 
            :value="overallProgress" 
            color="emerald" 
            size="lg"
            :show-steps="true"
            :steps="['Sākums', 'Pusceļš', 'Gandrīz gatavs', 'Eksperts']"
          />
          <div class="grid grid-cols-2 gap-4 mt-4">
            <div class="text-center">
              <div class="text-lg font-semibold text-indigo-600">{{ completedTopics }}</div>
              <div class="text-sm text-gray-500">Nokārtotas tēmas</div>
            </div>
            <div class="text-center">
              <div class="text-lg font-semibold text-purple-600">{{ totalTestsCompleted }}</div>
              <div class="text-sm text-gray-500">Pabeigtie testi</div>
            </div>
          </div>
        </GlassCard>

        <!-- XP and Badges -->
        <GlassCard>
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Sasniegumi</h3>
          <div class="space-y-4">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center">
                <Star class="w-5 h-5 text-white" />
              </div>
              <div>
                <div class="text-xl font-semibold text-gray-900">{{ xp }} XP</div>
                <div class="text-sm text-gray-500">Kopējie punkti</div>
              </div>
            </div>
            
            <div v-if="badges.length > 0">
              <h4 class="text-sm font-medium text-gray-700 mb-2">Nozīmītes:</h4>
              <div class="flex flex-wrap gap-1">
                <GlassBadge
                  v-for="badge in badges.slice(0, 3)" 
                  :key="badge.id"
                  :text="badge.name"
                  variant="indigo"
                  :icon="Award"
                  size="sm"
                />
                <div v-if="badges.length > 3" class="text-xs text-gray-500 self-center">
                  +{{ badges.length - 3 }} vēl
                </div>
              </div>
            </div>
          </div>
        </GlassCard>
      </div>

      <!-- Quick Actions & Recent Activity -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-8">
        <!-- Quick Actions -->
        <GlassCard>
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Ātrās darbības</h3>
          <div class="space-y-3">
            <GlassButton 
              v-if="nextUnlockedTopic"
              @click="startSelfTest(nextUnlockedTopic.id, 7)"
              variant="primary"
              :icon="Play"
              class="w-full justify-start"
            >
              Turpināt mācīties: {{ nextUnlockedTopic.name }}
            </GlassButton>
            <GlassButton 
              v-if="failedTopics.length > 0"
              @click="retryFailedTopic"
              variant="warning"
              :icon="RotateCcw"
              class="w-full justify-start"
            >
              Atkārtot neizdevušos testus ({{ failedTopics.length }})
            </GlassButton>
            <GlassButton 
              @click="viewAllTopics"
              variant="secondary"
              :icon="BookOpen"
              class="w-full justify-start"
            >
              Apskatīt visas tēmas
            </GlassButton>
          </div>
        </GlassCard>

        <!-- Recent Activity -->
        <GlassCard>
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Nesenas aktivitātes</h3>
          <div class="space-y-3">
            <div v-if="recentActivity.length === 0" class="text-center py-4 text-gray-500">
              <BookOpen class="w-8 h-8 mx-auto mb-2 opacity-50" />
              <p>Vēl nav aktivitāšu</p>
              <p class="text-sm">Sāciet ar pirmo testu!</p>
            </div>
            <div 
              v-for="activity in recentActivity.slice(0, 3)" 
              :key="activity.id"
              class="flex items-center space-x-3 p-2 rounded-lg bg-white/30"
            >
              <div :class="getActivityIconClass(activity.type)" class="w-8 h-8 rounded-full flex items-center justify-center">
                <component :is="getActivityIcon(activity.type)" class="w-4 h-4 text-white" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">{{ activity.description }}</p>
                <p class="text-xs text-gray-500">{{ formatTimeAgo(activity.created_at) }}</p>
              </div>
              <div v-if="activity.xp" class="text-sm font-semibold text-emerald-600">
                +{{ activity.xp }} XP
              </div>
            </div>
          </div>
        </GlassCard>
      </div>

      <!-- Study Tips Section -->
      <GlassCard variant="gradient" class="mb-8">
        <div class="flex items-start space-x-4">
          <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center flex-shrink-0">
            <Zap class="w-6 h-6 text-white" />
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Mācīšanās padomi</h3>
            <p class="text-gray-700 mb-3">
              {{ getStudyTip() }}
            </p>
            <div class="flex flex-wrap gap-2">
              <GlassBadge
                text="Regulāra prakse"
                variant="info"
                size="sm"
              />
              <GlassBadge
                text="Atkārtojiet materiālu"
                variant="info"
                size="sm"
              />
              <GlassBadge
                text="Neļaujiet sevi mulsināt"
                variant="info"
                size="sm"
              />
            </div>
          </div>
        </div>
      </GlassCard>

      <!-- Topics Grid -->
      <div class="topics-section mb-6">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900 mb-2">Tēmas</h2>
        <p class="text-sm sm:text-base text-gray-600">Apskatiet teoriju un sāciet testus kārtībā!</p>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        <router-link 
          v-for="t in topics" 
          :key="t.id" 
          :to="{ name: 'topic-detail', params: { id: t.id } }"
          class="block"
        >
          <GlassCard 
            :variant="isTopicLocked(t.id) ? 'default' : 'elevated'"
            :clickable="!isTopicLocked(t.id)"
            class="border-l-4 transition-all duration-300 h-full"
            :class="[
              getTopicBorderColor(t.id),
              isTopicLocked(t.id) ? 'opacity-75' : 'hover:scale-[1.02]'
            ]"
          >
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ t.name }}</h3>
              <div class="flex items-center space-x-2 mb-3">
                <GlassBadge
                  :text="`Līmenis ${t.prerequisite_level}`"
                  variant="default"
                  size="sm"
                />
                <GlassBadge
                  :text="getStatusText(t.id)"
                  :variant="getStatusBadgeVariant(t.id)"
                  size="sm"
                />
              </div>
            </div>
          </div>
          
          <!-- Action Buttons -->
          <div class="space-y-2 sm:space-y-3">
            <!-- Locked Topic Message -->
            <div v-if="isTopicLocked(t.id)" class="text-center py-4">
              <div class="flex items-center justify-center space-x-2 text-gray-500 mb-3">
                <Lock class="w-5 h-5" />
                <span class="text-sm font-medium">Testi bloķēti</span>
              </div>
              <p class="text-xs text-gray-400 mb-3">Nokārtojiet iepriekšējās tēmas, lai atbloķētu testus</p>
              <GlassButton 
                @click="viewTheory(t.id)"
                variant="secondary"
                size="sm"
                :icon="BookOpen"
                class="w-full"
              >
                Apskatīt teoriju
              </GlassButton>
            </div>
            
            <!-- Unlocked Topic Buttons -->
            <div v-else class="space-y-2">
              <!-- Grade 7 Buttons -->
              <div class="grid grid-cols-2 gap-2">
                <GlassButton 
                  v-if="canStartTest(t.id, 7)"
                  @click="startSelfTest(t.id, 7)"
                  variant="primary"
                  size="sm"
                  :icon="BookOpen"
                  class="w-full text-xs sm:text-sm"
                >
                  Self 7.kl
                </GlassButton>
                <GlassButton 
                  v-else-if="hasQuestionsForGrade(t.id, 7)"
                  variant="primary"
                  size="sm"
                  :icon="BookOpen"
                  class="w-full opacity-50 cursor-not-allowed text-xs sm:text-sm"
                  disabled
                >
                  Self 7.kl
                </GlassButton>
                
                <router-link 
                  v-if="canStartTest(t.id, 7)"
                  :to="{ name: 'final-test', query: { topic_id: t.id, grade: 7 } }" 
                  class="w-full"
                >
                  <GlassButton 
                    variant="secondary"
                    size="sm"
                    :icon="Target"
                    class="w-full text-xs sm:text-sm"
                  >
                    Gala 7.kl
                  </GlassButton>
                </router-link>
                <GlassButton 
                  v-else-if="hasQuestionsForGrade(t.id, 7)"
                  variant="secondary"
                  size="sm"
                  :icon="Target"
                  class="w-full opacity-50 cursor-not-allowed text-xs sm:text-sm"
                  disabled
                >
                  Gala 7.kl
                </GlassButton>
              </div>
              
              <!-- Grade 10 Buttons -->
              <div class="grid grid-cols-2 gap-2">
                <GlassButton 
                  v-if="canStartTest(t.id, 10)"
                  @click="startSelfTest(t.id, 10)"
                  variant="info"
                  size="sm"
                  :icon="BookOpen"
                  class="w-full text-xs sm:text-sm"
                >
                  Self 10.kl
                </GlassButton>
                <GlassButton 
                  v-else-if="hasQuestionsForGrade(t.id, 10)"
                  variant="info"
                  size="sm"
                  :icon="BookOpen"
                  class="w-full opacity-50 cursor-not-allowed text-xs sm:text-sm"
                  disabled
                >
                  Self 10.kl
                </GlassButton>
                
                <router-link 
                  v-if="canStartTest(t.id, 10)"
                  :to="{ name: 'final-test', query: { topic_id: t.id, grade: 10 } }" 
                  class="w-full"
                >
                  <GlassButton 
                    variant="secondary"
                    size="sm"
                    :icon="Target"
                    class="w-full text-xs sm:text-sm"
                  >
                    Gala 10.kl
                  </GlassButton>
                </router-link>
                <GlassButton 
                  v-else-if="hasQuestionsForGrade(t.id, 10)"
                  variant="secondary"
                  size="sm"
                  :icon="Target"
                  class="w-full opacity-50 cursor-not-allowed text-xs sm:text-sm"
                  disabled
                >
                  Gala 10.kl
                </GlassButton>
              </div>
            </div>
          </div>
          </GlassCard>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { 
  BookOpen, Target, Star, Award, Lock, 
  CheckCircle, Flame, Play, RotateCcw, Clock, Trophy, Zap
} from 'lucide-vue-next'

const store = useStore()
const router = useRouter()
const topics = computed(() => store.state.topics.list)
const xp = computed(() => store.state.gamification.xp)
const badges = computed(() => store.state.gamification.badges)
const progress = computed(() => store.state.gamification.progress)
const user = computed(() => store.state.auth.user)

// Track which topics have questions for each grade
const topicQuestionAvailability = ref({})

// Authentication state
const isAuthenticated = computed(() => store.state.auth.isAuthenticated)
const authMode = ref('login')

// New computed properties for improved dashboard
const completedTopics = computed(() => {
  return progress.value.filter(p => p.status === 'passed').length
})

const totalTopics = computed(() => topics.value.length)

const overallProgress = computed(() => {
  if (totalTopics.value === 0) return 0
  return Math.round((completedTopics.value / totalTopics.value) * 100)
})

const currentStreak = computed(() => {
  return store.state.gamification.currentStreak || 0
})

const totalTestsCompleted = computed(() => {
  return store.state.gamification.totalTestsCompleted || 0
})

const nextUnlockedTopic = computed(() => {
  return topics.value.find(topic => {
    const topicProgress = progress.value.find(p => p.topic_id === topic.id)
    return topicProgress && topicProgress.status === 'unlocked'
  })
})

const failedTopics = computed(() => {
  return topics.value.filter(topic => {
    const topicProgress = progress.value.find(p => p.topic_id === topic.id)
    return topicProgress && topicProgress.status === 'failed'
  })
})

const recentActivity = computed(() => {
  return store.state.gamification.recentActivity || []
})

const getTopicStatus = (topicId) => {
  const topicProgress = progress.value.find(p => p.topic_id === topicId)
  if (topicProgress) {
    return topicProgress.status
  }
  
  // No progress record exists - check if this should be unlocked by default
  const topic = topics.value.find(t => t.id === topicId)
  if (topic && topic.prerequisite_level === 1) {
    return 'unlocked' // First level topics are unlocked by default
  }
  
  return 'locked'
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

const getStatusBadgeVariant = (topicId) => {
  const status = getTopicStatus(topicId)
  switch (status) {
    case 'passed': return 'success'
    case 'unlocked': return 'info'
    case 'failed': return 'error'
    default: return 'warning'
  }
}

const getStatusText = (topicId) => {
  const status = getTopicStatus(topicId)
  switch (status) {
    case 'passed': return 'Nokārtots'
    case 'unlocked': return 'Atvērts'
    case 'failed': return 'Neizdevās'
    default: return 'Tikai teorija'
  }
}

const hasQuestionsForGrade = (topicId, grade) => {
  const key = `${topicId}-${grade}`
  return topicQuestionAvailability.value[key] || false
}

const isTopicLocked = (topicId) => {
  const status = getTopicStatus(topicId)
  return status === 'locked'
}

const canStartTest = (topicId, grade) => {
  return !isTopicLocked(topicId) && hasQuestionsForGrade(topicId, grade)
}

const loadQuestionAvailability = async () => {
  if (!isAuthenticated.value) return
  
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
  // Check if topic is locked before attempting to start test
  if (isTopicLocked(topicId)) {
    store.dispatch('toast/warning', {
      title: 'Tēma ir bloķēta',
      message: 'Vispirms nokārtojiet iepriekšējās tēmas.'
    })
    return
  }

  try {
    const test = await store.dispatch('tests/startSelf', { 
      topic_id: topicId, 
      question_count: 10,
      grade: grade
    })
    
    if (test) {
      store.dispatch('toast/success', {
        title: 'Pašpārbaudes tests sākts',
        message: 'Veiksmi testā!'
      })
      // Navigate to self-test page
      router.push({ name: 'self-test', query: { test_id: test.id } })
    }
  } catch (error) {
    console.error('Error starting self-test:', error)
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: 'Neizdevās sākt pašpārbaudes testu. Pārbaudiet, vai tēma nav bloķēta.'
    })
  }
}


// New methods for improved dashboard
const getGreeting = () => {
  const hour = new Date().getHours()
  if (hour < 12) return 'Labrīt'
  if (hour < 18) return 'Labdien'
  return 'Labvakar'
}

const getMotivationalMessage = () => {
  const messages = [
    'Esiet gatavs apgūt jaunas matemātikas tēmas!',
    'Katrs solis jūs tuvāk ekspertam!',
    'Mācīšanās ir ceļš uz panākumiem!',
    'Jūsu progress ir iespaidīgs!',
    'Turpiniet mācīties un sasniedziet jaunus mērķus!'
  ]
  return messages[Math.floor(Math.random() * messages.length)]
}

const retryFailedTopic = () => {
  if (failedTopics.value.length > 0) {
    const firstFailed = failedTopics.value[0]
    startSelfTest(firstFailed.id, 7)
  }
}

const viewAllTopics = () => {
  // Scroll to topics section
  const topicsSection = document.querySelector('.topics-section')
  if (topicsSection) {
    topicsSection.scrollIntoView({ behavior: 'smooth' })
  }
}

const viewTheory = (topicId) => {
  router.push({ name: 'topic-detail', params: { id: topicId } })
}

const getActivityIcon = (type) => {
  const icons = {
    test_completed: Target,
    badge_earned: Award,
    streak_milestone: Flame,
    level_up: Star,
    topic_unlocked: BookOpen
  }
  return icons[type] || Clock
}

const getActivityIconClass = (type) => {
  const classes = {
    test_completed: 'bg-emerald-500',
    badge_earned: 'bg-indigo-500',
    streak_milestone: 'bg-orange-500',
    level_up: 'bg-purple-500',
    topic_unlocked: 'bg-blue-500'
  }
  return classes[type] || 'bg-gray-500'
}

const formatTimeAgo = (date) => {
  const now = new Date()
  const diffInMinutes = Math.floor((now - date) / (1000 * 60))
  
  if (diffInMinutes < 60) {
    return `Pirms ${diffInMinutes} min`
  } else if (diffInMinutes < 1440) {
    const hours = Math.floor(diffInMinutes / 60)
    return `Pirms ${hours} st`
  } else {
    const days = Math.floor(diffInMinutes / 1440)
    return `Pirms ${days} d`
  }
}

const getStudyTip = () => {
  const tips = [
    'Atcerieties: matemātika ir kā mūzika - vajag praksi, lai kļūtu labākam!',
    'Ja kaut kas neizdodas, nepadodieties - katrs mēģinājums jūs tuvāk atbildei!',
    'Labāk mācīties 15 minūtes katru dienu, nekā 2 stundas vienā reizē.',
    'Zīmējiet diagrammas un skices - tas palīdz saprast problēmu!',
    'Atkārtojiet formulas regulāri - tās ir jūsu matemātikas rīki!',
    'Neļaujiet sevi mulsināt sarežģītajiem vārdiem - sāciet ar pamatiem!'
  ]
  return tips[Math.floor(Math.random() * tips.length)]
}

onMounted(() => {
  if (isAuthenticated.value) {
    // Load initial data
    store.dispatch('topics/fetchAll')
    store.dispatch('gamification/fetch')
    loadQuestionAvailability()
  }
})
</script>


