<template>
  <div class="min-h-screen">
    <!-- Loading State -->
    <div v-if="loading" class="container-main py-8">
      <div class="flex items-center justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500"></div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="container-main py-8">
      <GlassCard variant="error" class="text-center py-12">
        <AlertCircle class="w-16 h-16 text-red-500 mx-auto mb-4" />
        <h2 class="text-2xl font-semibold text-gray-900 mb-2">Kļūda</h2>
        <p class="text-gray-700 mb-6">{{ error }}</p>
        <GlassButton @click="$router.push('/')" variant="primary" :icon="Home">
          Atgriezties uz sākumu
        </GlassButton>
      </GlassCard>
    </div>

    <!-- Topic Content -->
    <div v-else-if="topic" class="container-main py-8">
      <!-- Header -->
      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-4">
          <GlassButton 
            @click="$router.push('/')" 
            variant="secondary"
            :icon="ChevronLeft"
            size="sm"
          >
            Atpakaļ
          </GlassButton>
          <div>
            <h1 class="text-page-title mb-2">{{ topic.name }}</h1>
            <div class="flex items-center space-x-3">
              <GlassBadge
                :text="`Līmenis ${topic.prerequisite_level}`"
                variant="default"
              />
              <GlassBadge
                :text="getStatusText(topic.id)"
                :variant="getStatusBadgeVariant(topic.id)"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Theory Section -->
      <GlassCard variant="elevated" class="mb-8">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center space-x-3">
            <BookOpen class="w-6 h-6 text-indigo-500" />
            <h2 class="text-section-title">Teorija</h2>
          </div>
          <div class="flex items-center space-x-3">
            <div v-if="theoryPages.length > 1" class="flex items-center space-x-2">
              <span class="text-sm text-gray-600">
                {{ currentTheoryPage + 1 }} no {{ theoryPages.length }}
              </span>
              <span class="text-xs text-gray-500">
                ({{ Math.round(currentTheoryContent.length / 100) * 100 }} rakstzīmes)
              </span>
            </div>
            <GlassButton 
              @click="openDrawingModal"
              variant="secondary"
              size="sm"
              :icon="Edit3"
            >
              Zīmēt
            </GlassButton>
          </div>
        </div>
        
        <div v-if="topic.theory && theoryPages.length > 0">
          <!-- Theory Content -->
          <div class="min-h-[400px]">
            <div v-html="formatTheoryContent(currentTheoryContent)" class="text-gray-700 leading-relaxed prose prose-lg max-w-none theory-content"></div>
          </div>
          
          <!-- Pagination Controls -->
          <div v-if="theoryPages.length > 1" class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200">
            <GlassButton 
              @click="previousTheoryPage"
              :disabled="currentTheoryPage === 0"
              variant="secondary"
              size="sm"
              :icon="ChevronLeft"
            >
              Iepriekšējā
            </GlassButton>
            
            <div class="flex space-x-2">
              <button
                v-for="(page, index) in theoryPages"
                :key="index"
                @click="currentTheoryPage = index"
                class="w-3 h-3 rounded-full transition-all duration-200"
                :class="currentTheoryPage === index 
                  ? 'bg-indigo-500' 
                  : 'bg-gray-300 hover:bg-gray-400'"
              />
            </div>
            
            <GlassButton 
              @click="nextTheoryPage"
              :disabled="currentTheoryPage === theoryPages.length - 1"
              variant="secondary"
              size="sm"
              :icon="ChevronRight"
            >
              Nākamā
            </GlassButton>
          </div>
        </div>
        <div v-else class="text-center py-8 text-gray-500">
          <BookOpen class="w-12 h-12 mx-auto mb-4 opacity-50" />
          <p>Teorijas materiāls nav pieejams šai tēmai.</p>
        </div>
      </GlassCard>

      <!-- Tests Section -->
      <div class="mb-8">
        <div class="flex items-center space-x-3 mb-6">
          <Target class="w-6 h-6 text-emerald-500" />
          <h2 class="text-section-title">Testi</h2>
        </div>

        <!-- Practice Tests -->
        <div class="mb-8">
          <h3 class="text-xl font-semibold text-gray-800 mb-4">Pašpārbaudes testi</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Grade 7 Practice Test -->
            <GlassCard 
              :variant="canStartPracticeTest(7) ? 'elevated' : 'default'"
              :clickable="canStartPracticeTest(7)"
              class="transition-all duration-300"
              :class="canStartPracticeTest(7) ? 'hover:scale-[1.02]' : 'opacity-75'"
            >
              <div class="text-center py-6">
                <BookOpen class="w-12 h-12 mx-auto mb-4 text-indigo-500" />
                <h4 class="text-lg font-semibold text-gray-900 mb-2">7. klase</h4>
                <p class="text-sm text-gray-600 mb-4">Pašpārbaudes tests</p>
                
                <div v-if="!canStartPracticeTest(7)" class="text-center">
                  <div class="flex items-center justify-center space-x-2 text-gray-500 mb-2">
                    <Lock class="w-5 h-5" />
                    <span class="text-sm font-medium">Nav pieejams</span>
                  </div>
                  <p class="text-xs text-gray-400">Nav jautājumu šai klasei</p>
                </div>
                
                <GlassButton 
                  v-else
                  @click="startPracticeTest(7)"
                  variant="primary"
                  :icon="BookOpen"
                  class="w-full"
                >
                  Sākt testu
                </GlassButton>
              </div>
            </GlassCard>

            <!-- Grade 10 Practice Test -->
            <GlassCard 
              :variant="canStartPracticeTest(10) ? 'elevated' : 'default'"
              :clickable="canStartPracticeTest(10)"
              class="transition-all duration-300"
              :class="canStartPracticeTest(10) ? 'hover:scale-[1.02]' : 'opacity-75'"
            >
              <div class="text-center py-6">
                <BookOpen class="w-12 h-12 mx-auto mb-4 text-indigo-500" />
                <h4 class="text-lg font-semibold text-gray-900 mb-2">10. klase</h4>
                <p class="text-sm text-gray-600 mb-4">Pašpārbaudes tests</p>
                
                <div v-if="!canStartPracticeTest(10)" class="text-center">
                  <div class="flex items-center justify-center space-x-2 text-gray-500 mb-2">
                    <Lock class="w-5 h-5" />
                    <span class="text-sm font-medium">Nav pieejams</span>
                  </div>
                  <p class="text-xs text-gray-400">Nav jautājumu šai klasei</p>
                </div>
                
                <GlassButton 
                  v-else
                  @click="startPracticeTest(10)"
                  variant="primary"
                  :icon="BookOpen"
                  class="w-full"
                >
                  Sākt testu
                </GlassButton>
              </div>
            </GlassCard>
          </div>
        </div>

        <!-- Final Tests -->
        <div>
          <h3 class="text-xl font-semibold text-gray-800 mb-4">Gala testi</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Grade 7 Final Test -->
            <GlassCard 
              :variant="canStartFinalTest(7) ? 'elevated' : 'default'"
              :clickable="canStartFinalTest(7)"
              class="transition-all duration-300"
              :class="canStartFinalTest(7) ? 'hover:scale-[1.02]' : 'opacity-75'"
            >
              <div class="text-center py-6">
                <Target class="w-12 h-12 mx-auto mb-4 text-emerald-500" />
                <h4 class="text-lg font-semibold text-gray-900 mb-2">7. klase</h4>
                <p class="text-sm text-gray-600 mb-4">Gala tests</p>
                
                <div v-if="!canStartFinalTest(7)" class="text-center">
                  <div class="flex items-center justify-center space-x-2 text-gray-500 mb-2">
                    <Lock class="w-5 h-5" />
                    <span class="text-sm font-medium">Nav pieejams</span>
                  </div>
                  <p class="text-xs text-gray-400">
                    {{ !hasQuestionsForGrade(7) ? 'Nav jautājumu šai klasei' : 'Vispirms nokārtojiet pašpārbaudes testu' }}
                  </p>
                </div>
                
                <GlassButton 
                  v-else
                  @click="startFinalTest(7)"
                  variant="secondary"
                  :icon="Target"
                  class="w-full"
                >
                  Sākt testu
                </GlassButton>
              </div>
            </GlassCard>

            <!-- Grade 10 Final Test -->
            <GlassCard 
              :variant="canStartFinalTest(10) ? 'elevated' : 'default'"
              :clickable="canStartFinalTest(10)"
              class="transition-all duration-300"
              :class="canStartFinalTest(10) ? 'hover:scale-[1.02]' : 'opacity-75'"
            >
              <div class="text-center py-6">
                <Target class="w-12 h-12 mx-auto mb-4 text-emerald-500" />
                <h4 class="text-lg font-semibold text-gray-900 mb-2">10. klase</h4>
                <p class="text-sm text-gray-600 mb-4">Gala tests</p>
                
                <div v-if="!canStartFinalTest(10)" class="text-center">
                  <div class="flex items-center justify-center space-x-2 text-gray-500 mb-2">
                    <Lock class="w-5 h-5" />
                    <span class="text-sm font-medium">Nav pieejams</span>
                  </div>
                  <p class="text-xs text-gray-400">
                    {{ !hasQuestionsForGrade(10) ? 'Nav jautājumu šai klasei' : 'Vispirms nokārtojiet pašpārbaudes testu' }}
                  </p>
                </div>
                
                <GlassButton 
                  v-else
                  @click="startFinalTest(10)"
                  variant="secondary"
                  :icon="Target"
                  class="w-full"
                >
                  Sākt testu
                </GlassButton>
              </div>
            </GlassCard>
          </div>
        </div>
      </div>
    </div>

    <!-- Drawing Modal -->
    <DrawingModal
      :is-open="showDrawingModal"
      :title="currentDrawingTitle"
      @close="closeDrawingModal"
      @save="saveDrawing"
    />
  </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { 
  BookOpen, 
  Target, 
  Home, 
  Lock, 
  AlertCircle, 
  ChevronLeft,
  Edit3,
  Image
} from 'lucide-vue-next'
import DrawingModal from '../components/DrawingModal.vue'

const store = useStore()
const router = useRouter()
const route = useRoute()

const topic = ref(null)
const loading = ref(true)
const error = ref(null)
const practiceTestsCompleted = ref({})
const currentTheoryPage = ref(0)
const theoryPages = ref([])
const showDrawingModal = ref(false)
const currentDrawingTitle = ref('')
const drawings = ref({})

// Get topic ID from route params
const topicId = computed(() => parseInt(route.params.id))

// Get gamification data
const progress = computed(() => store.state.gamification.progress)

// Current theory content
const currentTheoryContent = computed(() => {
  if (theoryPages.value.length === 0) return ''
  return theoryPages.value[currentTheoryPage.value] || ''
})

const getTopicStatus = (topicId) => {
  const topicProgress = progress.value.find(p => p.topic_id === topicId)
  if (topicProgress) {
    return topicProgress.status
  }
  
  // No progress record exists - check if this should be unlocked by default
  if (topic.value && topic.value.prerequisite_level === 1) {
    return 'unlocked' // First level topics are unlocked by default
  }
  
  return 'locked'
}

const getStatusBadgeVariant = (topicId) => {
  const status = getTopicStatus(topicId)
  switch (status) {
    case 'passed': return 'success'
    case 'unlocked': return 'info'
    case 'failed': return 'error'
    default: return 'default'
  }
}

const getStatusText = (topicId) => {
  const status = getTopicStatus(topicId)
  switch (status) {
    case 'passed': return 'Nokārtots'
    case 'unlocked': return 'Atvērts'
    case 'failed': return 'Neizdevās'
    default: return 'Bloķēts'
  }
}

const hasQuestionsForGrade = (grade) => {
  return topic.value && topic.value.questions && 
         topic.value.questions.some(q => q.grade === grade)
}

const canStartPracticeTest = (grade) => {
  if (!topic.value) return false
  const status = getTopicStatus(topicId.value)
  return status !== 'locked' && hasQuestionsForGrade(grade)
}

const canStartFinalTest = (grade) => {
  if (!topic.value) return false
  const status = getTopicStatus(topicId.value)
  const hasQuestions = hasQuestionsForGrade(grade)
  const practiceCompleted = practiceTestsCompleted.value[grade] || false
  
  return status !== 'locked' && hasQuestions && practiceCompleted
}

const parseTheoryContent = (theoryText) => {
  if (!theoryText) return []
  
  const pages = []
  const minPageLength = 800  // Minimum characters per page
  const maxPageLength = 2500 // Maximum characters per page
  
  // First, split by major headings (##) but keep the heading with its content
  const majorSections = theoryText.split(/(?=^## )/m).filter(section => section.trim())
  
  majorSections.forEach(section => {
    const trimmedSection = section.trim()
    if (!trimmedSection) return
    
    // If section is small enough, use as is
    if (trimmedSection.length <= maxPageLength) {
      pages.push(trimmedSection)
      return
    }
    
    // Split large sections by subheadings (###) but keep headings with content
    const subSections = trimmedSection.split(/(?=^### )/m).filter(sub => sub.trim())
    
    subSections.forEach(subSection => {
      const trimmedSub = subSection.trim()
      if (!trimmedSub) return
      
      // If subsection is small enough, use as is
      if (trimmedSub.length <= maxPageLength) {
        pages.push(trimmedSub)
        return
      }
      
      // For very large subsections, split by paragraphs but ensure headings stay with content
      const lines = trimmedSub.split('\n')
      let currentPage = ''
      let currentHeading = ''
      
      for (let i = 0; i < lines.length; i++) {
        const line = lines[i].trim()
        if (!line) {
          if (currentPage) {
            currentPage += '\n'
          }
          continue
        }
        
        // Check if this is a heading
        const isHeading = line.startsWith('### ') || line.startsWith('## ') || line.startsWith('# ')
        
        if (isHeading) {
          // If we have content in current page and adding this heading would exceed limit
          if (currentPage && (currentPage + '\n\n' + line).length > maxPageLength) {
            if (currentPage.length >= minPageLength) {
              pages.push(currentPage.trim())
              currentPage = line
              currentHeading = line
            } else {
              // Current page is too short, add the heading anyway
              currentPage += '\n\n' + line
              currentHeading = line
            }
          } else {
            // Add heading to current page
            currentPage += (currentPage ? '\n\n' : '') + line
            currentHeading = line
          }
        } else {
          // This is content, add it to current page
          const wouldBeLength = currentPage ? (currentPage + '\n\n' + line).length : line.length
          
          if (currentPage && wouldBeLength > maxPageLength) {
            // If current page is long enough, start a new page
            if (currentPage.length >= minPageLength) {
              pages.push(currentPage.trim())
              // Start new page with the heading if we have one
              currentPage = currentHeading ? currentHeading + '\n\n' + line : line
            } else {
              // Current page is too short, add the content anyway
              currentPage += '\n\n' + line
            }
          } else {
            currentPage += (currentPage ? '\n\n' : '') + line
          }
        }
      }
      
      // Add the last page if it has content
      if (currentPage.trim()) {
        pages.push(currentPage.trim())
      }
    })
  })
  
  // Post-process to merge very short pages with the next page
  const finalPages = []
  for (let i = 0; i < pages.length; i++) {
    const currentPage = pages[i]
    
    // If this page is too short and there's a next page, merge them
    if (currentPage.length < minPageLength && i < pages.length - 1) {
      const nextPage = pages[i + 1]
      const merged = currentPage + '\n\n' + nextPage
      
      // Only merge if the result isn't too long
      if (merged.length <= maxPageLength) {
        finalPages.push(merged)
        i++ // Skip the next page since we merged it
        continue
      }
    }
    
    finalPages.push(currentPage)
  }
  
  return finalPages
}

const formatTheoryContent = (content) => {
  if (!content) return ''
  
  // Clean up the content first
  let formatted = content
    .trim()
    // Normalize line breaks
    .replace(/\r\n/g, '\n')
    .replace(/\r/g, '\n')
    // Remove excessive whitespace
    .replace(/\n{3,}/g, '\n\n')
  
  // Convert markdown-like formatting to HTML
  formatted = formatted
    // Convert # main title to h1 (big header)
    .replace(/^# (.+)$/gm, '<h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">$1</h1>')
    // Convert ## headings to h2
    .replace(/^## (.+)$/gm, '<h2 class="text-2xl font-bold text-gray-900 mt-6 mb-4">$1</h2>')
    // Convert ### headings to h3
    .replace(/^### (.+)$/gm, '<h3 class="text-xl font-semibold text-gray-800 mt-5 mb-3">$1</h3>')
    // Convert **bold** to <strong>
    .replace(/\*\*(.+?)\*\*/g, '<strong class="font-semibold text-gray-900">$1</strong>')
    // Convert *italic* to <em>
    .replace(/\*(.+?)\*/g, '<em class="italic">$1</em>')
    // Convert fractions to proper stacked format
    .replace(/(\d+)\/(\d+)/g, '<span class="fraction"><span class="numerator">$1</span><span class="denominator">$2</span></span>')
    // Convert bullet points
    .replace(/^- (.+)$/gm, '<li class="ml-4 mb-2">$1</li>')
    // Convert numbered lists
    .replace(/^(\d+)\. (.+)$/gm, '<li class="ml-4 mb-2">$1. $2</li>')
    // Convert mathematical symbols
    .replace(/π/g, '<span class="math-symbol">π</span>')
    .replace(/√/g, '<span class="math-symbol">√</span>')
    .replace(/×/g, '<span class="math-symbol">×</span>')
    .replace(/÷/g, '<span class="math-symbol">÷</span>')
    .replace(/±/g, '<span class="math-symbol">±</span>')
    .replace(/∞/g, '<span class="math-symbol">∞</span>')
    // Convert mathematical expressions in backticks
    .replace(/`([^`]+)`/g, '<span class="math-expression">$1</span>')
    // Convert drawing syntax [DRAWING: description]
    .replace(/\[DRAWING:\s*([^\]]+)\]/g, '<div class="drawing"><div class="drawing-placeholder">📐 $1</div><button class="create-drawing-btn" onclick="window.openDrawingModal(\'$1\')">Izveidot zīmējumu</button></div>')
    // Convert shape syntax [SHAPE: type, description]
    .replace(/\[SHAPE:\s*([^,]+),\s*([^\]]+)\]/g, '<div class="math-diagram"><div class="shape-container">$1: $2</div></div>')
    // Convert diagram syntax [DIAGRAM: description]
    .replace(/\[DIAGRAM:\s*([^\]]+)\]/g, '<div class="math-diagram"><div class="diagram-placeholder">📊 $1</div></div>')
    // Convert fraction syntax [FRACTION: num/den]
    .replace(/\[FRACTION:\s*(\d+)\/(\d+)\]/g, '<div class="fraction-display"><div class="numerator">$1</div><div class="fraction-line"></div><div class="denominator">$2</div></div>')
    // Convert geometry shapes [GEOMETRY: type, description]
    .replace(/\[GEOMETRY:\s*([^,]+),\s*([^\]]+)\]/g, '<div class="math-diagram"><div class="geometry-shape">$1: $2</div></div>')
    // Convert drawing ID syntax [DRAWING_ID: id]
    .replace(/\[DRAWING_ID:\s*([^\]]+)\]/g, (match, id) => {
      const drawing = drawings.value[id]
      if (drawing) {
        return `<div class="saved-drawing"><img src="${drawing.dataURL}" alt="${drawing.title}" class="drawing-image" /></div>`
      }
      return `<div class="drawing-not-found">Zīmējums nav atrasts</div>`
    })
  
  // Handle lists properly
  formatted = formatted
    // Wrap consecutive bullet list items in ul
    .replace(/(<li class="ml-4 mb-2">[^<]*<\/li>(?:\s*<li class="ml-4 mb-2">[^<]*<\/li>)*)/g, (match) => {
      return '<ul class="list-disc list-inside space-y-2 my-4">' + match + '</ul>'
    })
    // Wrap consecutive numbered list items in ol
    .replace(/(<li class="ml-4 mb-2">\d+\.[^<]*<\/li>(?:\s*<li class="ml-4 mb-2">\d+\.[^<]*<\/li>)*)/g, (match) => {
      return '<ol class="list-decimal list-inside space-y-2 my-4">' + match + '</ol>'
    })
  
  // Handle paragraphs
  formatted = formatted
    // Split into paragraphs by double line breaks
    .split(/\n\s*\n/)
    .map(paragraph => {
      const trimmed = paragraph.trim()
      if (!trimmed) return ''
      
      // If it's already HTML (heading or list), return as is
      if (trimmed.startsWith('<h') || trimmed.startsWith('<ul') || trimmed.startsWith('<ol')) {
        return trimmed
      }
      
      // Otherwise wrap in paragraph tags
      return `<p class="mb-4">${trimmed}</p>`
    })
    .join('')
  
  // Clean up
  formatted = formatted
    // Remove empty paragraphs
    .replace(/<p class="mb-4"><\/p>/g, '')
    .replace(/<p class="mb-4">\s*<\/p>/g, '')
    // Remove empty list items
    .replace(/<li class="ml-4 mb-2">\s*<\/li>/g, '')
  
  return formatted
}

const loadTopic = async () => {
  try {
    loading.value = true
    error.value = null
    
    const { data } = await axios.get(`/topics/${topicId.value}`)
    topic.value = data
    
    // Parse theory content into pages
    if (data.theory) {
      theoryPages.value = parseTheoryContent(data.theory)
      currentTheoryPage.value = 0
      
      // Debug: Log page lengths
      console.log('Theory pages created:', theoryPages.value.length)
      theoryPages.value.forEach((page, index) => {
        console.log(`Page ${index + 1}: ${page.length} characters`)
      })
    }
  } catch (err) {
    console.error('Error loading topic:', err)
    error.value = err.response?.data?.message || 'Neizdevās ielādēt tēmu'
  } finally {
    loading.value = false
  }
}

const loadPracticeTestStatus = async () => {
  try {
    const { data } = await axios.get(`/topics/${topicId.value}/practice-tests-status`)
    practiceTestsCompleted.value = data
  } catch (err) {
    console.error('Error loading practice test status:', err)
    // Don't show error to user, just assume no tests completed
    practiceTestsCompleted.value = {}
  }
}

const startPracticeTest = async (grade) => {
  if (!canStartPracticeTest(grade)) return

  try {
    const test = await store.dispatch('tests/startSelf', { 
      topic_id: topicId.value, 
      question_count: 10,
      grade: grade
    })
    
    if (test) {
      store.dispatch('toast/success', {
        title: 'Pašpārbaudes tests sākts',
        message: 'Veiksmi testā!'
      })
      router.push({ name: 'self-test', query: { test_id: test.id } })
    }
  } catch (error) {
    console.error('Error starting practice test:', error)
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: 'Neizdevās sākt pašpārbaudes testu.'
    })
  }
}

const startFinalTest = async (grade) => {
  if (!canStartFinalTest(grade)) return

  try {
    const test = await store.dispatch('tests/startFinal', { 
      topic_id: topicId.value, 
      question_count: 20,
      time_limit_seconds: 1800, // 30 minutes
      grade: grade
    })
    
    if (test) {
      store.dispatch('toast/success', {
        title: 'Gala tests sākts',
        message: 'Veiksmi testā!'
      })
      router.push({ name: 'final-test', query: { test_id: test.id } })
    }
  } catch (error) {
    console.error('Error starting final test:', error)
    store.dispatch('toast/error', {
      title: 'Kļūda',
      message: 'Neizdevās sākt gala testu.'
    })
  }
}

const nextTheoryPage = () => {
  if (currentTheoryPage.value < theoryPages.value.length - 1) {
    currentTheoryPage.value++
  }
}

const previousTheoryPage = () => {
  if (currentTheoryPage.value > 0) {
    currentTheoryPage.value--
  }
}

const openDrawingModal = () => {
  currentDrawingTitle.value = `Zīmējums - ${topic.value?.name || 'Tēma'}`
  showDrawingModal.value = true
}

const closeDrawingModal = () => {
  showDrawingModal.value = false
  currentDrawingTitle.value = ''
}

const saveDrawing = (drawingData) => {
  const drawingId = Date.now().toString()
  drawings.value[drawingId] = {
    id: drawingId,
    title: drawingData.title,
    dataURL: drawingData.dataURL,
    createdAt: new Date().toISOString()
  }
  
  store.dispatch('toast/success', {
    title: 'Zīmējums saglabāts',
    message: 'Jūsu zīmējums ir veiksmīgi saglabāts!'
  })
  
  // You can also save to backend here
  // await saveDrawingToBackend(drawingData)
}

onMounted(async () => {
  await Promise.all([
    loadTopic(),
    loadPracticeTestStatus(),
    store.dispatch('gamification/fetch')
  ])
  
  // Make drawing modal accessible globally
  window.openDrawingModal = (title) => {
    currentDrawingTitle.value = title || 'Zīmējums'
    showDrawingModal.value = true
  }
})
</script>

<style scoped>
.theory-content :deep(h1) {
  font-size: 2.25rem;
  font-weight: 700;
  color: #111827;
  margin-bottom: 2rem;
  text-align: center;
  line-height: 1.2;
}

.theory-content :deep(h2) {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
  margin-top: 1.5rem;
  margin-bottom: 1rem;
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 0.5rem;
}

.theory-content :deep(h3) {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
  margin-top: 1.25rem;
  margin-bottom: 0.75rem;
}

.theory-content :deep(p) {
  margin-bottom: 1rem;
  line-height: 1.625;
}

.theory-content :deep(strong) {
  font-weight: 600;
  color: #111827;
}

.theory-content :deep(em) {
  font-style: italic;
  color: #374151;
}

.theory-content :deep(ul) {
  list-style-type: disc;
  list-style-position: inside;
  margin: 1rem 0;
  margin-left: 1rem;
}

.theory-content :deep(ul li) {
  margin-bottom: 0.5rem;
}

.theory-content :deep(ol) {
  list-style-type: decimal;
  list-style-position: inside;
  margin: 1rem 0;
  margin-left: 1rem;
}

.theory-content :deep(ol li) {
  margin-bottom: 0.5rem;
}

.theory-content :deep(blockquote) {
  border-left: 4px solid #6366f1;
  padding-left: 1rem;
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
  margin: 1rem 0;
  background-color: #eef2ff;
  border-radius: 0 0.5rem 0.5rem 0;
}

/* Mathematical elements */
.theory-content :deep(.fraction) {
  display: inline-block;
  text-align: center;
  vertical-align: middle;
  margin: 0 0.25rem;
}

.theory-content :deep(.fraction .numerator) {
  display: block;
  font-size: 0.9em;
  line-height: 1;
  border-bottom: 1px solid #374151;
  padding-bottom: 1px;
}

.theory-content :deep(.fraction .denominator) {
  display: block;
  font-size: 0.9em;
  line-height: 1;
  padding-top: 1px;
}

.theory-content :deep(.math-symbol) {
  font-family: 'Times New Roman', serif;
  font-style: italic;
  font-weight: 500;
  color: #1f2937;
}

.theory-content :deep(.math-expression) {
  font-family: 'Courier New', monospace;
  background-color: #f3f4f6;
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
  font-size: 0.9em;
  color: #1f2937;
  border: 1px solid #d1d5db;
}

/* Drawing and shape containers */
.theory-content :deep(.drawing) {
  display: block;
  margin: 1.5rem auto;
  text-align: center;
  padding: 1rem;
  background-color: #f9fafb;
  border: 2px dashed #d1d5db;
  border-radius: 0.5rem;
  min-height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.theory-content :deep(.drawing svg) {
  max-width: 100%;
  height: auto;
}

/* Mathematical diagrams */
.theory-content :deep(.math-diagram) {
  display: block;
  margin: 1.5rem auto;
  text-align: center;
  padding: 1rem;
  background-color: #fefefe;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.theory-content :deep(.drawing-placeholder) {
  color: #6b7280;
  font-style: italic;
  font-size: 0.9rem;
}

.theory-content :deep(.shape-container) {
  font-weight: 500;
  color: #374151;
}

.theory-content :deep(.diagram-placeholder) {
  color: #6b7280;
  font-style: italic;
  font-size: 0.9rem;
}

/* Enhanced fraction display */
.theory-content :deep(.fraction-display) {
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  font-size: 1.2rem;
  font-weight: 600;
  color: #374151;
  margin: 0 0.5rem;
  vertical-align: middle;
}

.theory-content :deep(.fraction-display .numerator) {
  border-bottom: 2px solid #374151;
  padding-bottom: 2px;
  margin-bottom: 2px;
  line-height: 1;
}

.theory-content :deep(.fraction-display .denominator) {
  padding-top: 2px;
  line-height: 1;
}

.theory-content :deep(.fraction-display .fraction-line) {
  width: 100%;
  height: 2px;
  background-color: #374151;
  margin: 2px 0;
}

.theory-content :deep(.geometry-shape) {
  font-weight: 500;
  color: #374151;
  text-align: center;
}

/* Drawing elements */
.theory-content :deep(.create-drawing-btn) {
  margin-top: 0.5rem;
  padding: 0.5rem 1rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.theory-content :deep(.create-drawing-btn:hover) {
  background: #2563eb;
}

.theory-content :deep(.saved-drawing) {
  margin: 1rem 0;
  text-align: center;
  padding: 1rem;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
}

.theory-content :deep(.drawing-image) {
  max-width: 100%;
  height: auto;
  border-radius: 0.375rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.theory-content :deep(.drawing-not-found) {
  padding: 1rem;
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 0.375rem;
  color: #dc2626;
  text-align: center;
  font-style: italic;
}
</style>
