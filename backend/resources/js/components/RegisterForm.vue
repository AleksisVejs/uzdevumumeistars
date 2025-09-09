<template>
  <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h1 class="text-4xl font-semibold text-gray-900 mb-2">Uzdevumu Meistars</h1>
        <p class="text-base text-gray-700 leading-relaxed">Latvijas matemātikas mācību platforma</p>
      </div>
      
      <GlassCard variant="gradient" padding="lg">
        <template #header>
          <h2 class="text-2xl font-semibold text-gray-900 text-center">Reģistrēties</h2>
        </template>
        
        <form @submit.prevent="handleRegister" class="space-y-6">
          <GlassInput
            v-model="form.name"
            type="text"
            label="Vārds"
            placeholder="Jūsu vārds"
            :error="getErrorMessage('name')"
            required
          />
          
          <GlassInput
            v-model="form.email"
            type="email"
            label="E-pasts"
            placeholder="jūsu@epasts.lv"
            :error="getErrorMessage('email')"
            required
          />
          
          <GlassInput
            v-model="form.password"
            type="password"
            label="Parole"
            placeholder="Vismaz 8 rakstzīmes"
            :error="getErrorMessage('password')"
            required
          />
          
          <GlassInput
            v-model="form.password_confirmation"
            type="password"
            label="Apstiprināt paroli"
            placeholder="Atkārtojiet paroli"
            :error="getErrorMessage('password_confirmation')"
            required
          />
          
          <div v-if="errorMessage" class="text-red-500 text-sm text-center">
            {{ errorMessage }}
          </div>
          
          <GlassButton
            type="submit"
            variant="primary"
            size="lg"
            class="w-full"
            :disabled="loading"
          >
            <span v-if="loading">Reģistrēšana...</span>
            <span v-else>Reģistrēties</span>
          </GlassButton>
        </form>
        
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Jau ir konts?
            <button
              @click="$emit('switch-to-login')"
              class="text-indigo-600 hover:text-indigo-500 font-medium"
            >
              Pieteikties šeit
            </button>
          </p>
        </div>
      </GlassCard>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useStore } from 'vuex'

const emit = defineEmits(['switch-to-login'])

const store = useStore()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const errors = ref({})
const errorMessage = ref('')
const loading = ref(false)

const getErrorMessage = (field) => {
  if (!errors.value[field]) return ''
  
  const error = errors.value[field]
  return Array.isArray(error) ? error[0] : error
}

const handleRegister = async () => {
  errors.value = {}
  errorMessage.value = ''
  loading.value = true

  try {
    await store.dispatch('auth/register', {
      name: form.name,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation
    })
    
    // Registration successful
    store.dispatch('toast/success', {
      title: 'Reģistrācija veiksmīga!',
      message: 'Laipni lūdzam Uzdevumu Meistars!'
    })
    
    // Small delay to show success message
    setTimeout(() => {
      window.location.reload()
    }, 1000)
  } catch (error) {
    if (error.response?.status === 422) {
      // Validation errors
      const validationErrors = error.response.data.errors || {}
      errors.value = validationErrors
      
      // Show validation errors as toast
      Object.keys(validationErrors).forEach(field => {
        const errorMessage = Array.isArray(validationErrors[field]) 
          ? validationErrors[field][0] 
          : validationErrors[field]
        store.dispatch('toast/error', {
          title: 'Validācijas kļūda',
          message: errorMessage
        })
      })
    } else {
      // General error
      let message = 'Reģistrācija neizdevās'
      
      if (error.response?.data?.message) {
        message = error.response.data.message
      } else if (error.code === 'NETWORK_ERROR' || !error.response) {
        message = 'Nav interneta savienojuma. Pārbaudiet savienojumu un mēģiniet vēlreiz.'
      } else if (error.response?.status === 500) {
        message = 'Servera kļūda. Lūdzu, mēģiniet vēlāk.'
      } else if (error.response?.status === 429) {
        message = 'Pārāk daudz mēģinājumu. Lūdzu, gaidiet un mēģiniet vēlāk.'
      } else if (error.response?.status === 401) {
        message = 'Reģistrācija neizdevās. Pārbaudiet savus datus.'
      } else if (error.response?.status === 403) {
        message = 'Piekļuve liegta. Lūdzu, sazinieties ar administratoru.'
      } else if (error.response?.status === 404) {
        message = 'Serviss nav pieejams. Lūdzu, mēģiniet vēlāk.'
      }
      
      errorMessage.value = message
      
      store.dispatch('toast/error', {
        title: 'Reģistrācija neizdevās',
        message: message
      })
    }
  } finally {
    loading.value = false
  }
}
</script>
