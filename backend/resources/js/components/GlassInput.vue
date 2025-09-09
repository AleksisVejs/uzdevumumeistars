<template>
  <div class="w-full">
    <label
      v-if="label"
      :for="inputId"
      class="block text-sm font-medium text-gray-700 mb-2"
    >
      {{ label }}
    </label>
    
    <div class="relative">
      <input
        :id="inputId"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :class="inputClasses"
        @input="$emit('update:modelValue', $event.target.value)"
        @focus="$emit('focus', $event)"
        @blur="$emit('blur', $event)"
      />
      
      <div v-if="icon" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <component
          :is="icon"
          class="w-5 h-5 text-gray-400"
        />
      </div>
    </div>
    
    <p v-if="error" class="mt-1 text-sm text-red-500">
      {{ error }}
    </p>
    
    <p v-if="hint && !error" class="mt-1 text-sm text-gray-500">
      {{ hint }}
    </p>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  type: {
    type: String,
    default: 'text'
  },
  label: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  hint: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  icon: {
    type: [String, Object, Function],
    default: null
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  }
})

const emit = defineEmits(['update:modelValue', 'focus', 'blur'])

const inputId = ref(`input-${Math.random().toString(36).substr(2, 9)}`)

const inputClasses = computed(() => {
  const baseClasses = 'w-full rounded-xl backdrop-blur-md border text-gray-900 placeholder-gray-500 focus:border-emerald-400 focus:ring focus:ring-emerald-100 outline-none transition-all'
  
  const stateClasses = props.error
    ? 'border-red-300 bg-red-50/60'
    : 'border-white/30 bg-white/60'
  
  const sizeClasses = {
    sm: 'px-3 py-2 text-sm',
    md: 'px-4 py-2 text-base',
    lg: 'px-5 py-3 text-lg'
  }
  
  const disabledClasses = props.disabled ? 'opacity-50 cursor-not-allowed' : ''
  
  return `${baseClasses} ${stateClasses} ${sizeClasses[props.size]} ${disabledClasses}`
})
</script>
