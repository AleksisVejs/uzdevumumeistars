<template>
  <div class="min-h-screen bg-gradient-to-b from-white via-gray-50 to-white py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Iestatījumi</h1>
        <p class="text-gray-600">Pārvaldiet savus konta iestatījumus un preferences</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Account Settings -->
          <GlassCard>
            <template #header>
              <div class="flex items-center space-x-3">
                <User class="w-6 h-6 text-indigo-500" />
                <h2 class="text-xl font-semibold text-gray-900">Konta iestatījumi</h2>
              </div>
            </template>
            
            <div class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Vārds</label>
                  <GlassInput 
                    v-model="accountForm.name" 
                    placeholder="Ievadiet savu vārdu"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">E-pasts</label>
                  <GlassInput 
                    v-model="accountForm.email" 
                    type="email"
                    placeholder="Ievadiet savu e-pastu"
                  />
                </div>
              </div>
              
              <div class="flex justify-end">
                <GlassButton 
                  variant="primary"
                  @click="updateAccount"
                  :disabled="isUpdatingAccount"
                >
                  <RefreshCw v-if="isUpdatingAccount" class="w-4 h-4 mr-2 animate-spin" />
                  {{ isUpdatingAccount ? 'Saglabā...' : 'Saglabāt izmaiņas' }}
                </GlassButton>
              </div>
            </div>
          </GlassCard>

          <!-- Password Change -->
          <GlassCard>
            <template #header>
              <div class="flex items-center space-x-3">
                <Lock class="w-6 h-6 text-red-500" />
                <h2 class="text-xl font-semibold text-gray-900">Paroles maiņa</h2>
              </div>
            </template>
            
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pašreizējā parole</label>
                <GlassInput 
                  v-model="passwordForm.current_password" 
                  type="password"
                  placeholder="Ievadiet pašreizējo paroli"
                />
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Jaunā parole</label>
                  <GlassInput 
                    v-model="passwordForm.password" 
                    type="password"
                    placeholder="Ievadiet jauno paroli"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Apstipriniet jauno paroli</label>
                  <GlassInput 
                    v-model="passwordForm.password_confirmation" 
                    type="password"
                    placeholder="Apstipriniet jauno paroli"
                  />
                </div>
              </div>
              
              <div class="flex justify-end">
                <GlassButton 
                  variant="primary"
                  @click="updatePassword"
                  :disabled="isUpdatingPassword"
                >
                  <RefreshCw v-if="isUpdatingPassword" class="w-4 h-4 mr-2 animate-spin" />
                  {{ isUpdatingPassword ? 'Saglabā...' : 'Mainīt paroli' }}
                </GlassButton>
              </div>
            </div>
          </GlassCard>

          <!-- Notification Settings -->
          <GlassCard>
            <template #header>
              <div class="flex items-center space-x-3">
                <Bell class="w-6 h-6 text-blue-500" />
                <h2 class="text-xl font-semibold text-gray-900">Paziņojumu iestatījumi</h2>
              </div>
            </template>
            
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-gray-900">E-pasta paziņojumi</h3>
                  <p class="text-sm text-gray-500">Saņemt paziņojumus par jauniem testiem un sasniegumiem</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    v-model="notificationSettings.email_notifications" 
                    type="checkbox" 
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
              </div>
              
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-gray-900">Testu atgādinājumi</h3>
                  <p class="text-sm text-gray-500">Atgādinājumi par neizpildītajiem testiem</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    v-model="notificationSettings.test_reminders" 
                    type="checkbox" 
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
              </div>
              
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-gray-900">Nedēļas progresa e-pasti</h3>
                  <p class="text-sm text-gray-500">Saņemt nedēļas kopsavilkumu par jūsu progresu</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    v-model="notificationSettings.weekly_progress_emails" 
                    type="checkbox" 
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
              </div>
              
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-gray-900">Sasniegumu paziņojumi</h3>
                  <p class="text-sm text-gray-500">Paziņojumi par jauniem sasniegumiem un nozīmēm</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    v-model="notificationSettings.achievement_notifications" 
                    type="checkbox" 
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
              </div>
              
              <div class="flex justify-end">
                <GlassButton 
                  variant="primary"
                  @click="updateNotificationSettings"
                  :disabled="isUpdatingNotifications"
                >
                  <RefreshCw v-if="isUpdatingNotifications" class="w-4 h-4 mr-2 animate-spin" />
                  {{ isUpdatingNotifications ? 'Saglabā...' : 'Saglabāt iestatījumus' }}
                </GlassButton>
              </div>
            </div>
          </GlassCard>

          <!-- Danger Zone -->
          <GlassCard>
            <template #header>
              <div class="flex items-center space-x-3">
                <AlertTriangle class="w-6 h-6 text-red-500" />
                <h2 class="text-xl font-semibold text-red-600">Bīstamā zona</h2>
              </div>
            </template>
            
            <div class="space-y-4">
              <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                <h3 class="text-sm font-medium text-red-800 mb-2">Dzēst kontu</h3>
                <p class="text-sm text-red-600 mb-4">
                  Dzēšot savu kontu, visi jūsu dati tiks neatgriezeniski dzēsti. 
                  Šo darbību nevar atsaukt.
                </p>
                <GlassButton 
                  variant="error"
                  size="sm"
                  @click="showDeleteAccountModal = true"
                >
                  Dzēst kontu
                </GlassButton>
              </div>
            </div>
          </GlassCard>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Quick Settings -->
          <GlassCard>
            <template #header>
              <h3 class="text-lg font-semibold text-gray-900">Ātrās darbības</h3>
            </template>
            
            <div class="space-y-3">
              <GlassButton 
                variant="secondary" 
                size="sm" 
                class="w-full justify-start"
                @click="$router.push('/profile')"
              >
                <User class="w-4 h-4 mr-2" />
                Skatīt profilu
              </GlassButton>
              
              <GlassButton 
                variant="secondary" 
                size="sm" 
                class="w-full justify-start"
                @click="exportData"
              >
                <Download class="w-4 h-4 mr-2" />
                Eksportēt datus
              </GlassButton>
              
              <GlassButton 
                variant="secondary" 
                size="sm" 
                class="w-full justify-start"
                @click="clearCache"
              >
                <RefreshCw class="w-4 h-4 mr-2" />
                Notīrīt kešatmiņu
              </GlassButton>
            </div>
          </GlassCard>

          <!-- App Info -->
          <GlassCard>
            <template #header>
              <h3 class="text-lg font-semibold text-gray-900">Par lietotni</h3>
            </template>
            
            <div class="space-y-3 text-sm text-gray-600">
              <div class="flex justify-between">
                <span>Versija:</span>
                <span class="font-medium">1.0.0</span>
              </div>
              <div class="flex justify-between">
                <span>Pēdējā atjaunināšana:</span>
                <span class="font-medium">{{ new Date().toLocaleDateString('lv-LV') }}</span>
              </div>
              <div class="flex justify-between">
                <span>Izstrādātājs:</span>
                <span class="font-medium">Uzdevumu Meistars</span>
              </div>
            </div>
          </GlassCard>
        </div>
      </div>
    </div>

    <!-- Delete Account Modal -->
    <GlassModal 
      v-if="showDeleteAccountModal" 
      @close="showDeleteAccountModal = false"
      title="Dzēst kontu"
    >
      <div class="space-y-4">
        <div class="flex items-center space-x-3 p-4 bg-red-50 rounded-xl">
          <AlertTriangle class="w-6 h-6 text-red-500" />
          <div>
            <h3 class="text-sm font-medium text-red-800">Brīdinājums!</h3>
            <p class="text-sm text-red-600">
              Vai tiešām vēlaties dzēst savu kontu? Visi jūsu dati tiks neatgriezeniski dzēsti.
            </p>
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Ierakstiet "DZĒST" lai apstiprinātu:
          </label>
          <GlassInput 
            v-model="deleteConfirmation" 
            placeholder="DZĒST"
          />
        </div>
        
        <div class="flex justify-end space-x-3">
          <GlassButton 
            variant="secondary"
            @click="showDeleteAccountModal = false"
          >
            Atcelt
          </GlassButton>
          <GlassButton 
            variant="error"
            @click="deleteAccount"
            :disabled="deleteConfirmation !== 'DZĒST' || isDeletingAccount"
          >
            <RefreshCw v-if="isDeletingAccount" class="w-4 h-4 mr-2 animate-spin" />
            {{ isDeletingAccount ? 'Dzēš...' : 'Dzēst kontu' }}
          </GlassButton>
        </div>
      </div>
    </GlassModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { 
  User, 
  Lock, 
  Bell, 
  AlertTriangle, 
  Download, 
  RefreshCw 
} from 'lucide-vue-next'

const store = useStore()
const router = useRouter()

const isUpdatingAccount = ref(false)
const isUpdatingPassword = ref(false)
const isUpdatingNotifications = ref(false)
const isDeletingAccount = ref(false)
const showDeleteAccountModal = ref(false)
const deleteConfirmation = ref('')

const accountForm = ref({
  name: '',
  email: ''
})

const passwordForm = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
})

const notificationSettings = ref({
  email_notifications: true,
  test_reminders: true,
  weekly_progress_emails: true,
  achievement_notifications: true
})

const user = computed(() => store.state.auth.user)

const updateAccount = async () => {
  if (!accountForm.value.name || !accountForm.value.email) {
    store.dispatch('toast/error', {
      title: 'Validācijas kļūda',
      message: 'Lūdzu, aizpildiet visus nepieciešamos laukus'
    })
    return
  }

  isUpdatingAccount.value = true
  try {
    await store.dispatch('settings/updateAccount', accountForm.value)
    store.dispatch('toast/success', {
      title: 'Veiksmīgi',
      message: 'Konta informācija atjaunināta'
    })
  } catch (error) {
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: error.response?.data?.message || 'Neizdevās atjaunināt konta informāciju'
    })
  } finally {
    isUpdatingAccount.value = false
  }
}

const updatePassword = async () => {
  if (!passwordForm.value.current_password || !passwordForm.value.password || !passwordForm.value.password_confirmation) {
    store.dispatch('toast/error', {
      title: 'Validācijas kļūda',
      message: 'Lūdzu, aizpildiet visus paroles laukus'
    })
    return
  }

  if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
    store.dispatch('toast/error', {
      title: 'Validācijas kļūda',
      message: 'Paroles nesakrīt'
    })
    return
  }

  isUpdatingPassword.value = true
  try {
    await store.dispatch('settings/updatePassword', passwordForm.value)
    store.dispatch('toast/success', {
      title: 'Veiksmīgi',
      message: 'Parole atjaunināta'
    })
    passwordForm.value = {
      current_password: '',
      password: '',
      password_confirmation: ''
    }
  } catch (error) {
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: error.response?.data?.message || 'Neizdevās mainīt paroli'
    })
  } finally {
    isUpdatingPassword.value = false
  }
}

const updateNotificationSettings = async () => {
  isUpdatingNotifications.value = true
  try {
    await store.dispatch('settings/updateNotificationSettings', notificationSettings.value)
    store.dispatch('toast/success', {
      title: 'Veiksmīgi',
      message: 'Paziņojumu iestatījumi atjaunināti'
    })
  } catch (error) {
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: error.response?.data?.message || 'Neizdevās atjaunināt paziņojumu iestatījumus'
    })
  } finally {
    isUpdatingNotifications.value = false
  }
}

const deleteAccount = async () => {
  if (deleteConfirmation.value !== 'DZĒST') {
    store.dispatch('toast/error', {
      title: 'Validācijas kļūda',
      message: 'Lūdzu, ierakstiet "DZĒST" lai apstiprinātu'
    })
    return
  }

  isDeletingAccount.value = true
  try {
    await store.dispatch('settings/deleteAccount')
    store.dispatch('toast/success', {
      title: 'Veiksmīgi',
      message: 'Konts dzēsts'
    })
    router.push('/')
  } catch (error) {
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: error.response?.data?.message || 'Neizdevās dzēst kontu'
    })
  } finally {
    isDeletingAccount.value = false
    showDeleteAccountModal.value = false
    deleteConfirmation.value = ''
  }
}

const exportData = async () => {
  try {
    await store.dispatch('settings/exportData')
    store.dispatch('toast/success', {
      title: 'Veiksmīgi',
      message: 'Dati eksportēti'
    })
  } catch (error) {
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: 'Neizdevās eksportēt datus'
    })
  }
}

const clearCache = async () => {
  try {
    await store.dispatch('settings/clearCache')
    localStorage.clear()
    store.dispatch('toast/success', {
      title: 'Veiksmīgi',
      message: 'Kešatmiņa notīrīta'
    })
  } catch (error) {
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: 'Neizdevās notīrīt kešatmiņu'
    })
  }
}

const loadSettings = async () => {
  try {
    await store.dispatch('settings/fetchSettings')
    
    // Set form data from store
    const settings = store.state.settings
    accountForm.value = {
      name: user.value?.name || '',
      email: user.value?.email || ''
    }
    notificationSettings.value = settings.notificationSettings || {
      email_notifications: true,
      test_reminders: true,
      weekly_progress_emails: true,
      achievement_notifications: true
    }
  } catch (error) {
    console.error('Error loading settings:', error)
  }
}

onMounted(() => {
  loadSettings()
})
</script>
