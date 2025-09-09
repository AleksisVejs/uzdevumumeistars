<template>
  <div class="min-h-screen bg-gradient-to-b from-white via-gray-50 to-white py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Profils</h1>
        <p class="text-gray-600">Pārvaldiet savu konta informāciju un skatiet savu progresu</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Information -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Personal Information Card -->
          <GlassCard>
            <template #header>
              <div class="flex items-center space-x-3">
                <User class="w-6 h-6 text-indigo-500" />
                <h2 class="text-xl font-semibold text-gray-900">Personīgā informācija</h2>
              </div>
            </template>
            
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Vārds</label>
                  <GlassInput 
                    v-model="profileForm.name" 
                    placeholder="Ievadiet savu vārdu"
                    :disabled="!isEditing"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">E-pasts</label>
                  <GlassInput 
                    v-model="profileForm.email" 
                    type="email"
                    placeholder="Ievadiet savu e-pastu"
                    :disabled="!isEditing"
                  />
                </div>
              </div>
              
              <div class="flex justify-end space-x-3">
                <GlassButton 
                  v-if="!isEditing"
                  variant="secondary"
                  @click="startEditing"
                >
                  Rediģēt profilu
                </GlassButton>
                <template v-else>
                  <GlassButton 
                    variant="secondary"
                    @click="cancelEditing"
                  >
                    Atcelt
                  </GlassButton>
                  <GlassButton 
                    variant="primary"
                    @click="updateProfile"
                    :disabled="isUpdating"
                  >
                    <RefreshCw v-if="isUpdating" class="w-4 h-4 mr-2 animate-spin" />
                    {{ isUpdating ? 'Saglabā...' : 'Saglabāt izmaiņas' }}
                  </GlassButton>
                </template>
              </div>
            </div>
          </GlassCard>

          <!-- Statistics Card -->
          <GlassCard>
            <template #header>
              <div class="flex items-center space-x-3">
                <BarChart3 class="w-6 h-6 text-emerald-500" />
                <h2 class="text-xl font-semibold text-gray-900">Statistika</h2>
              </div>
            </template>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-full mx-auto mb-3">
                  <Star class="w-6 h-6 text-emerald-500" />
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ user?.xp || 0 }}</p>
                <p class="text-sm text-gray-600">Kopējais XP</p>
              </div>
              
              <div class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-full mx-auto mb-3">
                  <Award class="w-6 h-6 text-indigo-500" />
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ completedTests }}</p>
                <p class="text-sm text-gray-600">Pabeigtie testi</p>
              </div>
              
              <div class="text-center">
                <div class="flex items-center justify-center w-12 h-12 bg-amber-100 rounded-full mx-auto mb-3">
                  <Flame class="w-6 h-6 text-amber-500" />
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ currentStreak }}</p>
                <p class="text-sm text-gray-600">Dienu sērija</p>
              </div>
            </div>
          </GlassCard>

          <!-- Recent Activity Card -->
          <GlassCard>
            <template #header>
              <div class="flex items-center space-x-3">
                <Target class="w-6 h-6 text-blue-500" />
                <h2 class="text-xl font-semibold text-gray-900">Nesena aktivitāte</h2>
              </div>
            </template>
            
            <div v-if="recentActivity.length === 0" class="text-center py-8">
              <Target class="w-12 h-12 text-gray-300 mx-auto mb-4" />
              <p class="text-gray-500">Nav nesenas aktivitātes</p>
            </div>
            
            <div v-else class="space-y-4">
              <div 
                v-for="activity in recentActivity" 
                :key="activity.id"
                class="flex items-center space-x-4 p-4 bg-white/40 rounded-xl border border-white/30"
              >
                <div class="flex-shrink-0">
                  <div :class="[
                    'w-10 h-10 rounded-full flex items-center justify-center',
                    activity.type === 'test_completed' ? 'bg-emerald-100' : 'bg-blue-100'
                  ]">
                    <component 
                      :is="activity.type === 'test_completed' ? Award : Target" 
                      :class="[
                        'w-5 h-5',
                        activity.type === 'test_completed' ? 'text-emerald-500' : 'text-blue-500'
                      ]"
                    />
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900">{{ activity.description }}</p>
                  <p class="text-sm text-gray-500">{{ formatDate(activity.created_at) }}</p>
                </div>
                <div v-if="activity.xp_gained" class="flex items-center space-x-1 text-emerald-600">
                  <Star class="w-4 h-4" />
                  <span class="text-sm font-medium">+{{ activity.xp_gained }}</span>
                </div>
              </div>
            </div>
          </GlassCard>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Profile Picture Card -->
          <GlassCard>
            <template #header>
              <h3 class="text-lg font-semibold text-gray-900">Profilu attēls</h3>
            </template>
            
            <div class="text-center">
              <div class="w-24 h-24 bg-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <User class="w-12 h-12 text-white" />
              </div>
              <p class="text-sm text-gray-600 mb-4">Profilu attēli drīzumā</p>
            </div>
          </GlassCard>

          <!-- Badges Card -->
          <GlassCard>
            <template #header>
              <div class="flex items-center space-x-2">
                <Award class="w-5 h-5 text-amber-500" />
                <h3 class="text-lg font-semibold text-gray-900">Nozīmes</h3>
              </div>
            </template>
            
            <div v-if="badges.length === 0" class="text-center py-4">
              <Award class="w-8 h-8 text-gray-300 mx-auto mb-2" />
              <p class="text-sm text-gray-500">Vēl nav nozīmju</p>
            </div>
            
            <div v-else class="grid grid-cols-2 gap-3">
              <div 
                v-for="badge in badges" 
                :key="badge.id"
                class="text-center p-3 bg-white/40 rounded-xl border border-white/30"
              >
                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-2">
                  <Award class="w-4 h-4 text-amber-500" />
                </div>
                <p class="text-xs font-medium text-gray-900">{{ badge.name }}</p>
              </div>
            </div>
          </GlassCard>

          <!-- Quick Actions Card -->
          <GlassCard>
            <template #header>
              <h3 class="text-lg font-semibold text-gray-900">Ātrās darbības</h3>
            </template>
            
            <div class="space-y-3">
              <GlassButton 
                variant="secondary" 
                size="sm" 
                class="w-full justify-start"
                @click="$router.push('/self-test')"
              >
                <Target class="w-4 h-4 mr-2" />
                Veikt pašpārbaudi
              </GlassButton>
              
              <GlassButton 
                variant="secondary" 
                size="sm" 
                class="w-full justify-start"
                @click="$router.push('/final-test')"
              >
                <Award class="w-4 h-4 mr-2" />
                Veikt gala testu
              </GlassButton>
              
              <GlassButton 
                variant="secondary" 
                size="sm" 
                class="w-full justify-start"
                @click="$router.push('/settings')"
              >
                <Settings class="w-4 h-4 mr-2" />
                Iestatījumi
              </GlassButton>
            </div>
          </GlassCard>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { 
  User, 
  BarChart3, 
  Star, 
  Award, 
  Flame, 
  Target, 
  Settings, 
  RefreshCw 
} from 'lucide-vue-next'

const store = useStore()

const isEditing = ref(false)
const isUpdating = ref(false)
const profileForm = ref({
  name: '',
  email: ''
})

const user = computed(() => store.state.auth.user)
const completedTests = ref(0)
const currentStreak = ref(0)
const recentActivity = ref([])
const badges = ref([])

const startEditing = () => {
  isEditing.value = true
  profileForm.value = {
    name: user.value?.name || '',
    email: user.value?.email || ''
  }
}

const cancelEditing = () => {
  isEditing.value = false
  profileForm.value = {
    name: user.value?.name || '',
    email: user.value?.email || ''
  }
}

const updateProfile = async () => {
  if (!profileForm.value.name || !profileForm.value.email) {
    store.dispatch('toast/error', {
      title: 'Validation Error',
      message: 'Please fill in all required fields'
    })
    return
  }

  isUpdating.value = true
  try {
    await store.dispatch('profile/updateProfile', profileForm.value)
    store.dispatch('toast/success', {
      title: 'Success',
      message: 'Profile updated successfully'
    })
    isEditing.value = false
  } catch (error) {
    store.dispatch('toast/error', {
      title: 'Error',
      message: error.response?.data?.message || 'Failed to update profile'
    })
  } finally {
    isUpdating.value = false
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const loadProfileData = async () => {
  try {
    await Promise.all([
      store.dispatch('profile/fetchProfileData'),
      store.dispatch('gamification/fetch')
    ])
    
    // Set local data from store
    const gamificationData = store.state.gamification
    completedTests.value = gamificationData.progress?.length || 0
    currentStreak.value = gamificationData.streak || 0
    badges.value = gamificationData.badges || []
    recentActivity.value = gamificationData.recentActivity || []
  } catch (error) {
    console.error('Error loading profile data:', error)
  }
}

onMounted(() => {
  loadProfileData()
})
</script>
