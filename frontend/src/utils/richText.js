const allowedTags = new Set([
  'A',
  'B',
  'BR',
  'DIV',
  'EM',
  'H3',
  'H4',
  'I',
  'LI',
  'OL',
  'P',
  'SPAN',
  'STRONG',
  'U',
  'UL',
])

const blockTags = new Set(['DIV', 'H3', 'H4', 'LI', 'OL', 'P', 'UL'])

function escapeHtml(value) {
  const textarea = document.createElement('textarea')
  textarea.textContent = value
  return textarea.innerHTML
}

function plainTextToHtml(value) {
  const blocks = String(value)
    .trim()
    .split(/\n{2,}/)
    .map((block) => block.trim())
    .filter(Boolean)

  return blocks
    .map((block) => {
      const lines = block.split('\n').map((line) => line.trim()).filter(Boolean)
      const bulletLines = lines.filter((line) => /^[-*•]\s+/.test(line))

      if (lines.length && bulletLines.length === lines.length) {
        return `<ul>${lines.map((line) => `<li>${escapeHtml(line.replace(/^[-*•]\s+/, ''))}</li>`).join('')}</ul>`
      }

      return `<p>${lines.map((line) => escapeHtml(line)).join('<br>')}</p>`
    })
    .join('')
}

function cleanNode(node) {
  if (node.nodeType === Node.TEXT_NODE) {
    return document.createTextNode(node.textContent || '')
  }

  if (node.nodeType !== Node.ELEMENT_NODE) {
    return document.createTextNode('')
  }

  const tagName = node.tagName.toUpperCase()
  const output = allowedTags.has(tagName)
    ? document.createElement(tagName.toLowerCase())
    : document.createDocumentFragment()

  if (tagName === 'A' && output.nodeType === Node.ELEMENT_NODE) {
    const href = node.getAttribute('href') || ''
    if (/^(https?:|mailto:|\/)/i.test(href)) {
      output.setAttribute('href', href)
      output.setAttribute('target', '_blank')
      output.setAttribute('rel', 'noopener')
    }
  }

  if (blockTags.has(tagName) && node.getAttribute('style')?.includes('text-align')) {
    const textAlign = node.style.textAlign
    if (['left', 'center', 'right'].includes(textAlign)) {
      output.setAttribute('style', `text-align: ${textAlign}`)
    }
  }

  node.childNodes.forEach((child) => {
    output.appendChild(cleanNode(child))
  })

  return output
}

export function sanitizeRichText(value) {
  if (!value) return ''

  const source = /<[a-z][\s\S]*>/i.test(value) ? value : plainTextToHtml(value)
  const template = document.createElement('template')
  template.innerHTML = source
  const fragment = document.createDocumentFragment()

  template.content.childNodes.forEach((node) => {
    fragment.appendChild(cleanNode(node))
  })

  const wrapper = document.createElement('div')
  wrapper.appendChild(fragment)
  return wrapper.innerHTML
}

export function stripRichText(value, fallback = '') {
  if (!value) return fallback

  const wrapper = document.createElement('div')
  wrapper.innerHTML = sanitizeRichText(value)
  return wrapper.textContent?.trim() || fallback
}

export function isBlankRichText(value) {
  return stripRichText(value, '') === ''
}
