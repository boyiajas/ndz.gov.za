<template>
  <div class="file-uploader-wrap">
    <input
      ref="fileInput"
      type="file"
      :accept="accept"
      class="d-none"
      @change="handleFileChange"
    />

    <button
      type="button"
      class="upload-btn"
      :class="[buttonClass, { 'is-uploading': uploading, 'compact': compact }]"
      :disabled="uploading"
      @click="triggerPicker"
    >
      <span v-if="uploading" class="spinner"></span>
      <span v-else class="btn-icon">{{ icon }}</span>
      <span class="btn-text">{{ uploading ? 'Uploading...' : label }}</span>
    </button>

    <span v-if="uploadError" class="upload-error-text">{{ uploadError }}</span>
    <span v-if="uploadSuccess" class="upload-success-text">✓ Uploaded</span>
  </div>
</template>

<script>
import api from '../api/axios'

export default {
  name: 'FileUploadButton',
  props: {
    folder: {
      type: String,
      default: 'uploads',
    },
    accept: {
      type: String,
      default: 'image/*,.pdf,.doc,.docx,.xls,.xlsx',
    },
    label: {
      type: String,
      default: 'Upload File',
    },
    icon: {
      type: String,
      default: '☁',
    },
    buttonClass: {
      type: String,
      default: 'secondary',
    },
    compact: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['uploaded', 'error'],
  data() {
    return {
      uploading: false,
      uploadError: null,
      uploadSuccess: false,
    }
  },
  methods: {
    triggerPicker() {
      this.uploadError = null
      this.uploadSuccess = false
      this.$refs.fileInput.click()
    },
    async handleFileChange(event) {
      const file = event.target.files?.[0]
      if (!file) return

      this.uploading = true
      this.uploadError = null
      this.uploadSuccess = false

      const formData = new FormData()
      formData.append('file', file)
      formData.append('folder', this.folder)

      try {
        const { data } = await api.post('/api/admin/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })

        this.uploadSuccess = true
        this.$emit('uploaded', data.url, data)
        setTimeout(() => {
          this.uploadSuccess = false
        }, 3500)
      } catch (err) {
        this.uploadError = err.response?.data?.message || 'Upload failed. Check file type and size.'
        this.$emit('error', this.uploadError)
      } finally {
        this.uploading = false
        // Reset input value so same file can be selected again if needed
        event.target.value = ''
      }
    },
  },
}
</script>

<style scoped>
.file-uploader-wrap {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  position: relative;
}

.upload-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  padding: 0.55rem 1rem;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  border: 1px solid #c2dbcd;
  background: #f0f7f3;
  color: #0f6b3b;
  white-space: nowrap;
}

.upload-btn:hover:not(:disabled) {
  background: #e2f1e8;
  border-color: #0f6b3b;
  transform: translateY(-1px);
}

.upload-btn.primary {
  background: #0f6b3b;
  color: #ffffff;
  border-color: #0f6b3b;
}

.upload-btn.primary:hover:not(:disabled) {
  background: #0c562f;
}

.upload-btn.compact {
  padding: 0.35rem 0.65rem;
  font-size: 0.78rem;
  border-radius: 8px;
}

.upload-btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.btn-icon {
  font-size: 1rem;
  line-height: 1;
}

.spinner {
  width: 14px;
  height: 14px;
  border: 2px solid rgba(15, 107, 59, 0.3);
  border-top-color: #0f6b3b;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

.upload-btn.primary .spinner {
  border-color: rgba(255, 255, 255, 0.3);
  border-top-color: #ffffff;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.upload-error-text {
  color: #d9383a;
  font-size: 0.78rem;
  font-weight: 600;
}

.upload-success-text {
  color: #0f6b3b;
  font-size: 0.78rem;
  font-weight: 700;
}
</style>
