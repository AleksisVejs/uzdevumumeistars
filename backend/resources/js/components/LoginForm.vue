<template>
  <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h1 class="text-4xl font-semibold text-gray-900 mb-2">Uzdevumu Meistars</h1>
        <p class="text-base text-gray-700 leading-relaxed">Latvijas matemātikas mācību platforma</p>
      </div>
      
      <GlassCard variant="gradient" padding="lg">
        <template #header>
          <h2 class="text-2xl font-semibold text-gray-900 text-center">Pieteikties</h2>
        </template>
        
        <form @submit.prevent="handleLogin" class="space-y-6">
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
            placeholder="Jūsu parole"
            :error="getErrorMessage('password')"
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
            <span v-if="loading">Pieteikšanās...</span>
            <span v-else>Pieteikties</span>
          </GlassButton>
        </form>
        
        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Nav konta?
            <button
              @click="$emit('switch-to-register')"
              class="text-indigo-600 hover:text-indigo-500 font-medium"
            >
              Reģistrējies šeit
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

const emit = defineEmits(['switch-to-register'])

const store = useStore()

const form = reactive({
  email: '',
  password: ''
})

const errors = ref({})
const errorMessage = ref('')
const loading = ref(false)

const getErrorMessage = (field) => {
  if (!errors.value[field]) return ''
  
  const error = errors.value[field]
  return Array.isArray(error) ? error[0] : error
}

const handleLogin = async () => {
  errors.value = {}
  errorMessage.value = ''
  loading.value = true

  try {
    await store.dispatch('auth/login', {
      email: form.email,
      password: form.password
    })
    
    // Login successful
    store.dispatch('toast/success', {
      title: 'Pieteikšanās veiksmīga!',
      message: 'Laipni lūdzam atpakaļ!'
    })
    
    // Small delay to show success message
    setTimeout(() => {
      window.location.reload()
    }, 1000)
  } catch (error) {
    if (error.response?.status === 422) {
      // Check if it's a validation error or a login error
      if (error.response.data.errors) {
        // Validation errors (form validation)
        const validationErrors = error.response.data.errors
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
      } else if (error.response.data.message) {
        // Login error (invalid credentials, user not found, etc.)
        const message = error.response.data.message
        errorMessage.value = message
        
        store.dispatch('toast/error', {
          title: 'Pieteikšanās neizdevās',
          message: message
        })
      }
    } else {
      // General error
      let message = 'Pieteikšanās neizdevās'
      
      if (error.response?.data?.message) {
        message = error.response.data.message
      } else if (error.code === 'NETWORK_ERROR' || !error.response) {
        message = 'Nav interneta savienojuma. Pārbaudiet savienojumu un mēģiniet vēlreiz.'
      } else if (error.response?.status === 500) {
        message = 'Servera kļūda. Lūdzu, mēģiniet vēlāk.'
      } else if (error.response?.status === 429) {
        message = 'Pārāk daudz mēģinājumu. Lūdzu, gaidiet un mēģiniet vēlāk.'
      } else if (error.response?.status === 401) {
        message = 'Pieteikšanās neizdevās. Pārbaudiet savus datus.'
      } else if (error.response?.status === 403) {
        message = 'Piekļuve liegta. Lūdzu, sazinieties ar administratoru.'
      } else if (error.response?.status === 404) {
        message = 'Serviss nav pieejams. Lūdzu, mēģiniet vēlāk.'
      }
      
      errorMessage.value = message
      
      store.dispatch('toast/error', {
        title: 'Pieteikšanās neizdevās',
        message: message
      })
    }
  } finally {
    loading.value = false
  }
}
</script>
