<template>
  <div class="min-h-screen bg-gradient-to-b from-white via-gray-50 to-white">
    <GlassNavbar 
      v-if="isAuthenticated" 
      @show-login="showLoginModal = true" 
      @show-register="showRegisterModal = true" 
    />
    <main :class="{ 'pt-16': isAuthenticated }">
      <router-view />
    </main>
    <ToastContainer />
    
    <!-- Login Modal -->
    <GlassModal 
      v-if="showLoginModal" 
      @close="showLoginModal = false"
      title="Pieteikties"
    >
      <LoginForm @success="showLoginModal = false" />
    </GlassModal>
    
    <!-- Register Modal -->
    <GlassModal 
      v-if="showRegisterModal" 
      @close="showRegisterModal = false"
      title="Reģistrēties"
    >
      <RegisterForm @success="showRegisterModal = false" />
    </GlassModal>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useStore } from 'vuex'
import ToastContainer from './components/ToastContainer.vue'
import LoginForm from './components/LoginForm.vue'
import RegisterForm from './components/RegisterForm.vue'

const store = useStore()
const showLoginModal = ref(false)
const showRegisterModal = ref(false)

const isAuthenticated = computed(() => store.state.auth.isAuthenticated)
</script>
