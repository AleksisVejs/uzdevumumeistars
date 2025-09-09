<template>
  <div class="math-canvas-container">
    <div class="canvas-header">
      <h4 class="canvas-title">{{ title }}</h4>
      <div class="canvas-controls">
        <button @click="clearCanvas" class="control-btn" title="Notīrīt">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
        </button>
        <button @click="undo" class="control-btn" title="Atcelt">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
          </svg>
        </button>
        <button @click="saveDrawing" class="control-btn" title="Saglabāt">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
          </svg>
        </button>
      </div>
    </div>
    
    <div class="toolbar">
      <div class="tool-group">
        <button 
          v-for="tool in tools" 
          :key="tool.name"
          @click="selectTool(tool.name)"
          :class="['tool-btn', { active: currentTool === tool.name }]"
          :title="tool.label"
        >
          <component :is="tool.icon" class="w-5 h-5" />
        </button>
      </div>
      
      <div class="tool-group">
        <input 
          type="color" 
          v-model="strokeColor" 
          class="color-picker"
          title="Krāsa"
        />
        <select v-model="strokeWidth" class="width-select" title="Biezums">
          <option value="1">1px</option>
          <option value="2">2px</option>
          <option value="3">3px</option>
          <option value="4">4px</option>
          <option value="5">5px</option>
        </select>
      </div>
    </div>
    
    <div class="canvas-wrapper">
      <canvas
        ref="canvas"
        @mousedown="startDrawing"
        @mousemove="draw"
        @mouseup="stopDrawing"
        @mouseleave="stopDrawing"
        class="math-canvas"
        :width="canvasWidth"
        :height="canvasHeight"
      ></canvas>
    </div>
    
    <div class="canvas-footer">
      <span class="canvas-info">{{ canvasWidth }} × {{ canvasHeight }}px</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { 
  Pencil, 
  Square, 
  Circle, 
  Triangle, 
  Minus, 
  Type,
  Ruler
} from 'lucide-vue-next'

const props = defineProps({
  title: {
    type: String,
    default: 'Matemātiskais zīmējums'
  },
  width: {
    type: Number,
    default: 400
  },
  height: {
    type: Number,
    default: 300
  }
})

const emit = defineEmits(['save', 'clear'])

const canvas = ref(null)
const canvasWidth = ref(props.width)
const canvasHeight = ref(props.height)
const currentTool = ref('pencil')
const strokeColor = ref('#374151')
const strokeWidth = ref(2)
const isDrawing = ref(false)
const lastX = ref(0)
const lastY = ref(0)
const history = ref([])

const tools = [
  { name: 'pencil', label: 'Zīmulis', icon: Pencil },
  { name: 'rectangle', label: 'Taisnstūris', icon: Square },
  { name: 'circle', label: 'Aplis', icon: Circle },
  { name: 'triangle', label: 'Trijstūris', icon: Triangle },
  { name: 'line', label: 'Līnija', icon: Minus },
  { name: 'text', label: 'Teksts', icon: Type },
  { name: 'ruler', label: 'Lineāls', icon: Ruler }
]

let ctx = null

onMounted(() => {
  ctx = canvas.value.getContext('2d')
  ctx.lineCap = 'round'
  ctx.lineJoin = 'round'
  saveState()
})

const selectTool = (toolName) => {
  currentTool.value = toolName
}

const startDrawing = (e) => {
  if (currentTool.value === 'text') {
    addText(e)
    return
  }
  
  isDrawing.value = true
  const rect = canvas.value.getBoundingClientRect()
  lastX.value = e.clientX - rect.left
  lastY.value = e.clientY - rect.top
  
  ctx.beginPath()
  ctx.moveTo(lastX.value, lastY.value)
}

const draw = (e) => {
  if (!isDrawing.value) return
  
  const rect = canvas.value.getBoundingClientRect()
  const currentX = e.clientX - rect.left
  const currentY = e.clientY - rect.top
  
  ctx.strokeStyle = strokeColor.value
  ctx.lineWidth = strokeWidth.value
  
  switch (currentTool.value) {
    case 'pencil':
      ctx.lineTo(currentX, currentY)
      ctx.stroke()
      break
    case 'line':
      ctx.clearRect(0, 0, canvasWidth.value, canvasHeight.value)
      restoreState()
      ctx.beginPath()
      ctx.moveTo(lastX.value, lastY.value)
      ctx.lineTo(currentX, currentY)
      ctx.stroke()
      break
    case 'rectangle':
      ctx.clearRect(0, 0, canvasWidth.value, canvasHeight.value)
      restoreState()
      ctx.strokeRect(lastX.value, lastY.value, currentX - lastX.value, currentY - lastY.value)
      break
    case 'circle':
      ctx.clearRect(0, 0, canvasWidth.value, canvasHeight.value)
      restoreState()
      const radius = Math.sqrt(Math.pow(currentX - lastX.value, 2) + Math.pow(currentY - lastY.value, 2))
      ctx.beginPath()
      ctx.arc(lastX.value, lastY.value, radius, 0, 2 * Math.PI)
      ctx.stroke()
      break
    case 'triangle':
      ctx.clearRect(0, 0, canvasWidth.value, canvasHeight.value)
      restoreState()
      ctx.beginPath()
      ctx.moveTo(lastX.value, lastY.value)
      ctx.lineTo(currentX, currentY)
      ctx.lineTo(lastX.value, currentY)
      ctx.closePath()
      ctx.stroke()
      break
  }
  
  lastX.value = currentX
  lastY.value = currentY
}

const stopDrawing = () => {
  if (isDrawing.value) {
    isDrawing.value = false
    saveState()
  }
}

const addText = (e) => {
  const text = prompt('Ievadiet tekstu:')
  if (text) {
    const rect = canvas.value.getBoundingClientRect()
    const x = e.clientX - rect.left
    const y = e.clientY - rect.top
    
    ctx.fillStyle = strokeColor.value
    ctx.font = `${strokeWidth.value * 4}px Arial`
    ctx.fillText(text, x, y)
    saveState()
  }
}

const saveState = () => {
  history.value.push(ctx.getImageData(0, 0, canvasWidth.value, canvasHeight.value))
  if (history.value.length > 20) {
    history.value.shift()
  }
}

const restoreState = () => {
  if (history.value.length > 0) {
    ctx.putImageData(history.value[history.value.length - 1], 0, 0)
  }
}

const undo = () => {
  if (history.value.length > 1) {
    history.value.pop()
    restoreState()
  }
}

const clearCanvas = () => {
  ctx.clearRect(0, 0, canvasWidth.value, canvasHeight.value)
  history.value = []
  saveState()
  emit('clear')
}

const saveDrawing = () => {
  const dataURL = canvas.value.toDataURL('image/png')
  emit('save', dataURL)
}

watch([strokeColor, strokeWidth], () => {
  ctx.strokeStyle = strokeColor.value
  ctx.lineWidth = strokeWidth.value
})
</script>

<style scoped>
.math-canvas-container {
  background: white;
  border: 2px solid #e5e7eb;
  border-radius: 0.75rem;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.canvas-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.canvas-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #374151;
  margin: 0;
}

.canvas-controls {
  display: flex;
  gap: 0.5rem;
}

.control-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  background: white;
  color: #6b7280;
  cursor: pointer;
  transition: all 0.2s;
}

.control-btn:hover {
  background: #f3f4f6;
  color: #374151;
  border-color: #9ca3af;
}

.toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 1rem;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.tool-group {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.tool-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2.5rem;
  height: 2.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  background: white;
  color: #6b7280;
  cursor: pointer;
  transition: all 0.2s;
}

.tool-btn:hover {
  background: #f3f4f6;
  color: #374151;
  border-color: #9ca3af;
}

.tool-btn.active {
  background: #3b82f6;
  color: white;
  border-color: #3b82f6;
}

.color-picker {
  width: 2.5rem;
  height: 2.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  cursor: pointer;
}

.width-select {
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  background: white;
  color: #374151;
  font-size: 0.875rem;
}

.canvas-wrapper {
  padding: 1rem;
  background: white;
}

.math-canvas {
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  cursor: crosshair;
  background: white;
}

.canvas-footer {
  padding: 0.75rem 1rem;
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
  text-align: center;
}

.canvas-info {
  font-size: 0.875rem;
  color: #6b7280;
}
</style>
