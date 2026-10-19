<template>
  <div ref="root" class="multiselect-container" :class="{ 'is-open': isOpen, 'is-disabled': disabled }">
    <!-- Trigger input box -->
    <div
      class="multiselect-trigger"
      tabindex="0"
      @click="toggleDropdown"
      @keydown.space.prevent="toggleDropdown"
      @keydown.enter.prevent="toggleDropdown"
      @keydown.esc="closeDropdown"
    >
      <div v-if="selectedItems.length === 0" class="multiselect-placeholder">
        {{ placeholder }}
      </div>

      <div v-else class="multiselect-chips">
        <span
          v-for="item in selectedItems"
          :key="item[valueKey]"
          class="multiselect-chip"
          @click.stop
        >
          <span v-if="secondaryKey && item[secondaryKey]" class="chip-code">
            {{ item[secondaryKey] }}
          </span>
          <span class="chip-label">{{ item[labelKey] }}</span>
          <button
            type="button"
            class="chip-remove"
            :disabled="disabled"
            title="Remove"
            @click.stop="removeItem(item)"
          >
            &times;
          </button>
        </span>
      </div>

      <div class="multiselect-indicators">
        <button
          v-if="selectedItems.length > 0 && !disabled"
          type="button"
          class="multiselect-clear-btn"
          title="Clear all"
          @click.stop="clearAll"
        >
          &times;
        </button>
        <span class="multiselect-arrow" :class="{ up: isOpen }">▼</span>
      </div>
    </div>

    <!-- Dropdown menu -->
    <div v-if="isOpen" class="multiselect-dropdown" @click.stop>
      <div class="multiselect-search-wrap">
        <input
          ref="searchInput"
          v-model="searchQuery"
          type="text"
          class="multiselect-search-input"
          :placeholder="searchPlaceholder"
          @keydown.esc="closeDropdown"
        />
        <span v-if="searchQuery" class="search-clear" @click="searchQuery = ''">&times;</span>
      </div>

      <div class="multiselect-toolbar">
        <button type="button" class="toolbar-btn" @click="selectAll">Select All</button>
        <span class="toolbar-sep">|</span>
        <button type="button" class="toolbar-btn" @click="clearAll">Deselect All</button>
        <span class="toolbar-count">{{ selectedItems.length }} of {{ options.length }} selected</span>
      </div>

      <div class="multiselect-options-list">
        <div
          v-for="option in filteredOptions"
          :key="option[valueKey]"
          class="multiselect-option"
          :class="{ 'is-selected': isSelected(option) }"
          @click="toggleOption(option)"
        >
          <div class="option-checkbox">
            <span v-if="isSelected(option)">✓</span>
          </div>

          <div class="option-content">
            <span class="option-name">{{ option[labelKey] }}</span>
            <span v-if="secondaryKey && option[secondaryKey]" class="option-code">
              {{ option[secondaryKey] }}
            </span>
          </div>
        </div>

        <div v-if="filteredOptions.length === 0" class="multiselect-no-results">
          No matching departments found.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MultiSelect',
  props: {
    modelValue: {
      type: Array,
      default: () => [],
    },
    options: {
      type: Array,
      default: () => [],
    },
    valueKey: {
      type: String,
      default: 'id',
    },
    labelKey: {
      type: String,
      default: 'name',
    },
    secondaryKey: {
      type: String,
      default: 'code',
    },
    placeholder: {
      type: String,
      default: 'Select departments...',
    },
    searchPlaceholder: {
      type: String,
      default: 'Search departments...',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['update:modelValue'],
  data() {
    return {
      isOpen: false,
      searchQuery: '',
    }
  },
  computed: {
    selectedIds() {
      return (this.modelValue || []).map((v) => (typeof v === 'object' ? v[this.valueKey] : v))
    },
    selectedItems() {
      return this.options.filter((opt) => this.selectedIds.includes(opt[this.valueKey]))
    },
    filteredOptions() {
      if (!this.searchQuery.trim()) {
        return this.options
      }
      const q = this.searchQuery.toLowerCase().trim()
      return this.options.filter((opt) => {
        const name = String(opt[this.labelKey] || '').toLowerCase()
        const code = String(opt[this.secondaryKey] || '').toLowerCase()
        return name.includes(q) || code.includes(q)
      })
    },
  },
  mounted() {
    document.addEventListener('click', this.handleOutsideClick)
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleOutsideClick)
  },
  methods: {
    toggleDropdown() {
      if (this.disabled) return
      this.isOpen = !this.isOpen
      if (this.isOpen) {
        this.$nextTick(() => {
          this.$refs.searchInput?.focus()
        })
      }
    },
    closeDropdown() {
      this.isOpen = false
      this.searchQuery = ''
    },
    handleOutsideClick(event) {
      if (this.$refs.root && !this.$refs.root.contains(event.target)) {
        this.closeDropdown()
      }
    },
    isSelected(option) {
      return this.selectedIds.includes(option[this.valueKey])
    },
    toggleOption(option) {
      const val = option[this.valueKey]
      let newValues = [...this.selectedIds]
      if (newValues.includes(val)) {
        newValues = newValues.filter((v) => v !== val)
      } else {
        newValues.push(val)
      }
      this.$emit('update:modelValue', newValues)
    },
    removeItem(item) {
      const val = item[this.valueKey]
      const newValues = this.selectedIds.filter((v) => v !== val)
      this.$emit('update:modelValue', newValues)
    },
    selectAll() {
      const allValues = this.options.map((opt) => opt[this.valueKey])
      this.$emit('update:modelValue', allValues)
    },
    clearAll() {
      this.$emit('update:modelValue', [])
    },
  },
}
</script>

<style scoped>
.multiselect-container {
  position: relative;
  width: 100%;
  font-family: inherit;
}

.multiselect-trigger {
  min-height: 44px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.35rem 0.65rem;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.15s ease-in-out;
  box-sizing: border-box;
}

.multiselect-trigger:hover {
  border-color: #94a3b8;
}

.multiselect-container.is-open .multiselect-trigger {
  border-color: #0f6b3b;
  box-shadow: 0 0 0 3px rgba(15, 107, 59, 0.12);
}

.multiselect-container.is-disabled .multiselect-trigger {
  background: #f1f5f9;
  cursor: not-allowed;
  opacity: 0.7;
}

.multiselect-placeholder {
  color: #94a3b8;
  font-size: 0.88rem;
  padding: 0.25rem 0.2rem;
  user-select: none;
}

.multiselect-chips {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem;
  flex: 1;
  padding-right: 0.5rem;
}

.multiselect-chip {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  background: #f0fdf4;
  color: #166534;
  border: 1px solid #bbf7d0;
  border-radius: 6px;
  padding: 0.18rem 0.45rem;
  font-size: 0.78rem;
  font-weight: 700;
  line-height: 1.2;
}

.chip-code {
  background: #166534;
  color: #ffffff;
  font-size: 0.68rem;
  font-weight: 800;
  padding: 0.05rem 0.3rem;
  border-radius: 4px;
}

.chip-remove {
  background: none;
  border: none;
  color: #166534;
  font-size: 1rem;
  line-height: 1;
  cursor: pointer;
  padding: 0 0.1rem;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.chip-remove:hover {
  background: rgba(22, 101, 52, 0.15);
  color: #14532d;
}

.multiselect-indicators {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  margin-left: auto;
  color: #64748b;
}

.multiselect-clear-btn {
  background: none;
  border: none;
  color: #94a3b8;
  font-size: 1.1rem;
  line-height: 1;
  cursor: pointer;
  padding: 0.1rem;
}

.multiselect-clear-btn:hover {
  color: #dc2626;
}

.multiselect-arrow {
  font-size: 0.65rem;
  transition: transform 0.2s ease;
}

.multiselect-arrow.up {
  transform: rotate(180deg);
}

.multiselect-dropdown {
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  right: 0;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.06);
  z-index: 1050;
  max-height: 320px;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  animation: slideDown 0.15s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.multiselect-search-wrap {
  position: relative;
  padding: 0.5rem;
  border-bottom: 1px solid #f1f5f9;
}

.multiselect-search-input {
  width: 100%;
  padding: 0.45rem 1.8rem 0.45rem 0.65rem;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 0.82rem;
  outline: none;
  box-sizing: border-box;
}

.multiselect-search-input:focus {
  border-color: #0f6b3b;
}

.search-clear {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  cursor: pointer;
  font-size: 1.1rem;
}

.multiselect-toolbar {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.35rem 0.65rem;
  background: #f8fafc;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.74rem;
}

.toolbar-btn {
  background: none;
  border: none;
  color: #0f6b3b;
  font-weight: 700;
  cursor: pointer;
  padding: 0;
}

.toolbar-btn:hover {
  text-decoration: underline;
}

.toolbar-sep {
  color: #cbd5e1;
}

.toolbar-count {
  margin-left: auto;
  color: #64748b;
  font-weight: 600;
}

.multiselect-options-list {
  overflow-y: auto;
  max-height: 210px;
  padding: 0.25rem 0;
}

.multiselect-option {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  padding: 0.45rem 0.75rem;
  cursor: pointer;
  transition: background 0.1s ease;
  user-select: none;
}

.multiselect-option:hover {
  background: #f1f5f9;
}

.multiselect-option.is-selected {
  background: #f0fdf4;
}

.option-checkbox {
  width: 18px;
  height: 18px;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  background: #ffffff;
  display: grid;
  place-items: center;
  font-size: 0.75rem;
  color: #ffffff;
  font-weight: 800;
}

.multiselect-option.is-selected .option-checkbox {
  background: #0f6b3b;
  border-color: #0f6b3b;
}

.option-content {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex: 1;
}

.option-name {
  font-size: 0.85rem;
  color: #1e293b;
  font-weight: 600;
}

.option-code {
  background: #e2e8f0;
  color: #475569;
  font-size: 0.68rem;
  font-weight: 800;
  padding: 0.1rem 0.35rem;
  border-radius: 4px;
}

.multiselect-no-results {
  padding: 1rem;
  text-align: center;
  color: #94a3b8;
  font-size: 0.82rem;
}
</style>
