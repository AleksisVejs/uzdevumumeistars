<template>
  <div class="math-drawing-container">
    <div v-if="type === 'fraction'" class="fraction-display">
      <div class="numerator">{{ numerator }}</div>
      <div class="fraction-line"></div>
      <div class="denominator">{{ denominator }}</div>
    </div>
    
    <div v-else-if="type === 'circle'" class="shape-circle">
      <svg width="120" height="120" viewBox="0 0 120 120">
        <circle cx="60" cy="60" r="50" stroke="#374151" stroke-width="2" fill="none"/>
        <text x="60" y="65" text-anchor="middle" font-size="12" fill="#6b7280">{{ description }}</text>
      </svg>
    </div>
    
    <div v-else-if="type === 'rectangle'" class="shape-rectangle">
      <svg width="160" height="100" viewBox="0 0 160 100">
        <rect x="10" y="10" width="140" height="80" stroke="#374151" stroke-width="2" fill="none"/>
        <text x="80" y="55" text-anchor="middle" font-size="12" fill="#6b7280">{{ description }}</text>
      </svg>
    </div>
    
    <div v-else-if="type === 'triangle'" class="shape-triangle">
      <svg width="120" height="120" viewBox="0 0 120 120">
        <polygon points="60,10 110,90 10,90" stroke="#374151" stroke-width="2" fill="none"/>
        <text x="60" y="70" text-anchor="middle" font-size="12" fill="#6b7280">{{ description }}</text>
      </svg>
    </div>
    
    <div v-else-if="type === 'line'" class="shape-line">
      <svg width="200" height="60" viewBox="0 0 200 60">
        <line x1="20" y1="30" x2="180" y2="30" stroke="#374151" stroke-width="3"/>
        <text x="100" y="50" text-anchor="middle" font-size="12" fill="#6b7280">{{ description }}</text>
      </svg>
    </div>
    
    <div v-else-if="type === 'angle'" class="shape-angle">
      <svg width="120" height="120" viewBox="0 0 120 120">
        <line x1="20" y1="100" x2="100" y2="100" stroke="#374151" stroke-width="2"/>
        <line x1="60" y1="20" x2="60" y2="100" stroke="#374151" stroke-width="2"/>
        <path d="M 60 100 L 70 100 A 10 10 0 0 1 60 90" stroke="#374151" stroke-width="1" fill="none"/>
        <text x="60" y="50" text-anchor="middle" font-size="12" fill="#6b7280">{{ description }}</text>
      </svg>
    </div>
    
    <div v-else class="generic-shape">
      <div class="shape-placeholder">📐 {{ type }}</div>
      <div class="shape-description">{{ description }}</div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    required: true
  },
  description: {
    type: String,
    default: ''
  },
  numerator: {
    type: String,
    default: '1'
  },
  denominator: {
    type: String,
    default: '2'
  }
})
</script>

<style scoped>
.math-drawing-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 1rem 0;
  padding: 1rem;
  background-color: #f9fafb;
  border: 2px dashed #d1d5db;
  border-radius: 0.5rem;
  min-height: 120px;
}

.fraction-display {
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 1.5rem;
  font-weight: 600;
  color: #374151;
}

.numerator {
  border-bottom: 2px solid #374151;
  padding-bottom: 2px;
  margin-bottom: 2px;
}

.denominator {
  padding-top: 2px;
}

.fraction-line {
  width: 100%;
  height: 2px;
  background-color: #374151;
  margin: 2px 0;
}

.shape-circle,
.shape-rectangle,
.shape-triangle,
.shape-line,
.shape-angle {
  display: flex;
  justify-content: center;
  align-items: center;
}

.generic-shape {
  text-align: center;
}

.shape-placeholder {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.shape-description {
  color: #6b7280;
  font-style: italic;
  font-size: 0.9rem;
}
</style>
