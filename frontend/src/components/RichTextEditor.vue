<template>
  <div class="rich-editor">
    <div class="rich-toolbar" aria-label="Description formatting tools">
      <button type="button" @mousedown.prevent="run('formatBlock', 'p')">Paragraph</button>
      <button type="button" @mousedown.prevent="run('formatBlock', 'h3')">Heading</button>
      <button type="button" @mousedown.prevent="run('bold')"><strong>B</strong></button>
      <button type="button" @mousedown.prevent="run('italic')"><em>I</em></button>
      <button type="button" @mousedown.prevent="run('underline')"><u>U</u></button>
      <button type="button" @mousedown.prevent="run('insertUnorderedList')">Bullets</button>
      <button type="button" @mousedown.prevent="run('insertOrderedList')">Numbers</button>
      <button type="button" @mousedown.prevent="createLink">Link</button>
      <button type="button" @mousedown.prevent="run('removeFormat')">Clear</button>
    </div>

    <div
      ref="editor"
      class="rich-content"
      contenteditable="true"
      :data-placeholder="placeholder"
      @input="emitValue"
      @blur="emitValue"
      @paste="handlePaste"
    ></div>
  </div>
</template>

<script>
import { isBlankRichText, sanitizeRichText } from '../utils/richText'

export default {
  name: 'RichTextEditor',
  props: {
    modelValue: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      default: 'Add formatted description text...',
    },
  },
  emits: ['update:modelValue'],
  mounted() {
    this.syncEditor()

    try {
      document.execCommand('defaultParagraphSeparator', false, 'p')
    } catch (error) {
      // Browser does not support the command; editing still works.
    }
  },
  watch: {
    modelValue() {
      if (document.activeElement !== this.$refs.editor) {
        this.syncEditor()
      }
    },
  },
  methods: {
    syncEditor() {
      if (!this.$refs.editor) return

      const nextHtml = sanitizeRichText(this.modelValue)
      if (this.$refs.editor.innerHTML !== nextHtml) {
        this.$refs.editor.innerHTML = nextHtml
      }
    },
    emitValue() {
      const html = sanitizeRichText(this.$refs.editor.innerHTML)
      this.$emit('update:modelValue', isBlankRichText(html) ? '' : html)
    },
    run(command, value = null) {
      this.$refs.editor.focus()
      document.execCommand(command, false, value)
      this.emitValue()
    },
    createLink() {
      const href = window.prompt('Enter the link URL')
      if (!href) return

      this.run('createLink', href)
    },
    handlePaste(event) {
      event.preventDefault()
      const text = event.clipboardData?.getData('text/plain') || ''
      document.execCommand('insertText', false, text)
      this.emitValue()
    },
  },
}
</script>

<style scoped>
.rich-editor {
  border: 1px solid #dbe5df;
  border-radius: 14px;
  overflow: hidden;
  background: #ffffff;
}

.rich-toolbar {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem;
  padding: 0.55rem;
  background: #f3f8f5;
  border-bottom: 1px solid #dbe5df;
}

.rich-toolbar button {
  border: 1px solid #dbe5df;
  border-radius: 9px;
  background: #ffffff;
  color: var(--text-dark);
  padding: 0.35rem 0.55rem;
  font-size: 0.75rem;
  font-weight: 800;
}

.rich-toolbar button:hover {
  border-color: var(--primary);
  color: var(--primary);
}

.rich-content {
  min-height: 180px;
  max-height: 360px;
  overflow-y: auto;
  padding: 0.9rem 1rem;
  color: var(--text-dark);
  line-height: 1.7;
  outline: none;
  font-size: 0.9rem;
}

.rich-content:empty::before {
  content: attr(data-placeholder);
  color: var(--text-light);
  font-weight: 600;
}

.rich-content :deep(h3) {
  font-size: 1rem;
  font-weight: 800;
  margin: 0 0 0.75rem;
}

.rich-content :deep(p) {
  margin: 0 0 0.8rem;
}

.rich-content :deep(ul),
.rich-content :deep(ol) {
  margin: 0.6rem 0 0.9rem;
  padding-left: 1.25rem;
}

.rich-content :deep(li) {
  margin: 0.35rem 0;
}
</style>
