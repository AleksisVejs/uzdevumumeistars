<template>
  <nav class="fixed top-0 left-0 right-0 z-50 bg-white/60 backdrop-blur-md border-b border-white/30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo/Brand -->
        <div class="flex items-center">
          <router-link 
            to="/" 
            class="flex items-center space-x-2 text-gray-900 hover:text-indigo-600 transition-colors duration-200"
          >
            <BookOpen class="w-8 h-8 text-indigo-500" />
            <span class="text-xl font-bold">Uzdevumu Meistars</span>
          </router-link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-8">
          <router-link 
            to="/" 
            :class="navLinkClasses('/')"
            class="flex items-center space-x-2"
          >
            <Home class="w-5 h-5" />
            <span>Pārskats</span>
          </router-link>
          
          <router-link 
            to="/self-test" 
            :class="navLinkClasses('/self-test')"
            class="flex items-center space-x-2"
          >
            <Target class="w-5 h-5" />
            <span>Pašpārbaude</span>
          </router-link>
          
          <router-link 
            to="/final-test" 
            :class="navLinkClasses('/final-test')"
            class="flex items-center space-x-2"
          >
            <Award class="w-5 h-5" />
            <span>Gala tests</span>
          </router-link>
        </div>

        <!-- User Menu / Auth Buttons -->
        <div class="flex items-center space-x-4">
          <!-- User Info (when authenticated) -->
          <div v-if="isAuthenticated" class="flex items-center space-x-4">
            <!-- XP Display -->
            <div class="hidden sm:flex items-center space-x-2 bg-white/40 rounded-full px-3 py-1.5 border border-white/30">
              <Star class="w-4 h-4 text-amber-500" />
              <span class="text-sm font-medium text-gray-700">{{ user?.xp || 0 }} XP</span>
            </div>

            <!-- User Dropdown -->
            <div class="relative" ref="userDropdown">
              <button 
                @click="toggleUserDropdown"
                class="flex items-center space-x-2 bg-white/60 hover:bg-white/80 rounded-full px-3 py-2 border border-white/30 transition-all duration-200 hover:shadow-md"
              >
                <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                  <User class="w-5 h-5 text-white" />
                </div>
                <span class="hidden sm:block text-sm font-medium text-gray-700">{{ user?.name || 'User' }}</span>
                <ChevronRight 
                  :class="['w-4 h-4 text-gray-500 transition-transform duration-200', { 'rotate-90': showUserDropdown }]"
                />
              </button>

              <!-- Dropdown Menu -->
              <div 
                v-if="showUserDropdown"
                class="absolute right-0 mt-2 w-48 bg-white rounded-2xl border border-gray-200 shadow-lg py-2 z-50"
              >
                <router-link 
                  to="/profile" 
                  class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                  @click="closeUserDropdown"
                >
                  <User class="w-4 h-4" />
                  <span>Profils</span>
                </router-link>
                
                <router-link 
                  to="/settings" 
                  class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200"
                  @click="closeUserDropdown"
                >
                  <Settings class="w-4 h-4" />
                  <span>Iestatījumi</span>
                </router-link>
                
                <div class="border-t border-gray-200 my-2"></div>
                
                <button 
                  @click="handleLogout"
                  class="flex items-center space-x-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors duration-200"
                >
                  <LogOut class="w-4 h-4" />
                  <span>Iziet</span>
                </button>
              </div>
            </div>
          </div>

          <!-- Auth Buttons (when not authenticated) -->
          <div v-else class="flex items-center space-x-3">
            <GlassButton 
              variant="secondary" 
              size="sm"
              @click="$emit('show-login')"
            >
              Login
            </GlassButton>
            
            <GlassButton 
              variant="primary" 
              size="sm"
              @click="$emit('show-register')"
            >
              Register
            </GlassButton>
          </div>

          <!-- Mobile Menu Button -->
          <button 
            @click="toggleMobileMenu"
            class="md:hidden p-2 rounded-full bg-white/60 hover:bg-white/80 border border-white/30 transition-all duration-200"
          >
            <svg 
              class="w-6 h-6 text-gray-700" 
              :class="{ 'rotate-90': showMobileMenu }"
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path 
                v-if="!showMobileMenu"
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path 
                v-else
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Navigation Menu -->
      <div 
        v-if="showMobileMenu" 
        class="md:hidden bg-white/80 backdrop-blur-md border-t border-white/30 py-4"
      >
        <div class="space-y-2">
          <router-link 
            to="/" 
            :class="mobileNavLinkClasses('/')"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl mx-2"
            @click="closeMobileMenu"
          >
            <Home class="w-5 h-5" />
            <span>Pārskats</span>
          </router-link>
          
          <router-link 
            to="/self-test" 
            :class="mobileNavLinkClasses('/self-test')"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl mx-2"
            @click="closeMobileMenu"
          >
            <Target class="w-5 h-5" />
            <span>Pašpārbaude</span>
          </router-link>
          
          <router-link 
            to="/final-test" 
            :class="mobileNavLinkClasses('/final-test')"
            class="flex items-center space-x-3 px-4 py-3 rounded-xl mx-2"
            @click="closeMobileMenu"
          >
            <Award class="w-5 h-5" />
            <span>Gala tests</span>
          </router-link>

          <!-- Mobile User Info -->
          <div v-if="isAuthenticated" class="px-4 py-3 border-t border-white/30 mt-4">
            <div class="flex items-center space-x-3 mb-3">
              <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center">
                <User class="w-6 h-6 text-white" />
              </div>
              <div>
                <p class="font-medium text-gray-900">{{ user?.name || 'User' }}</p>
                <p class="text-sm text-gray-500">{{ user?.xp || 0 }} XP</p>
              </div>
            </div>
            
            <div class="space-y-2">
              <button 
                @click="handleLogout"
                class="flex items-center space-x-3 w-full px-4 py-3 text-red-600 hover:bg-red-50/60 rounded-xl transition-colors duration-200"
              >
                <LogOut class="w-5 h-5" />
                <span>Iziet</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useStore } from 'vuex'
import { 
  BookOpen, 
  Home, 
  Target, 
  Award, 
  User, 
  Settings, 
  LogOut, 
  Star, 
  ChevronRight 
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const store = useStore()

const emit = defineEmits(['show-login', 'show-register'])

const showUserDropdown = ref(false)
const showMobileMenu = ref(false)
const userDropdown = ref(null)

const isAuthenticated = computed(() => store.state.auth.isAuthenticated)
const user = computed(() => store.state.auth.user)

const navLinkClasses = (path) => {
  const isActive = route.path === path
  return [
    'px-3 py-2 rounded-full text-sm font-medium transition-all duration-200',
    isActive 
      ? 'bg-indigo-100/60 text-indigo-700 border border-indigo-200/50' 
      : 'text-gray-700 hover:bg-white/60 hover:text-indigo-600'
  ]
}

const mobileNavLinkClasses = (path) => {
  const isActive = route.path === path
  return [
    'transition-all duration-200',
    isActive 
      ? 'bg-indigo-100/60 text-indigo-700 border border-indigo-200/50' 
      : 'text-gray-700 hover:bg-white/60 hover:text-indigo-600'
  ]
}

const toggleUserDropdown = () => {
  showUserDropdown.value = !showUserDropdown.value
  if (showUserDropdown.value) {
    showMobileMenu.value = false
  }
}

const closeUserDropdown = () => {
  showUserDropdown.value = false
}

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
  if (showMobileMenu.value) {
    showUserDropdown.value = false
  }
}

const closeMobileMenu = () => {
  showMobileMenu.value = false
}

const handleLogout = async () => {
  try {
    await store.dispatch('auth/logout')
    closeUserDropdown()
    closeMobileMenu()
    router.push('/')
  } catch (error) {
    console.error('Logout error:', error)
  }
}

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  if (userDropdown.value && !userDropdown.value.contains(event.target)) {
    showUserDropdown.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
