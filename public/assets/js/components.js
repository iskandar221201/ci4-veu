/**
 * components.js — Alpine component definitions for CI4 Kit Views Layer
 * Depends on: api.js, error.js (must be loaded first)
 * Required load order: auth.js → error.js → api.js → components.js
 *
 * All Alpine logic lives here — no inline Alpine logic in PHP views.
 */

/**
 * dataTable(endpoint)
 * Fetches list data from the API with pagination and search support.
 *
 * @param {string} endpoint - API endpoint, e.g. '/api/users'
 */
function dataTable(endpoint) {
  return {
    data:        [],
    meta:        {},
    loading:     true,
    currentPage: 1,
    totalPages:  1,
    search:      '',

    async init() {
      this.$watch('search', () => {
        this.currentPage = 1
      })
      await this.fetch()
    },

    async fetch() {
      this.loading = true
      try {
        const params = new URLSearchParams({
          page:   this.currentPage,
          search: this.search,
          per_page: 10,
        })
        const res      = await api.get(`${endpoint}?${params}`)
        this.data      = res.data ?? []
        this.meta      = res.meta ?? {}
        this.totalPages = res.meta?.total_pages ?? 1
      } catch (err) {
        errorHandler.catch(err)
        this.data = []
        this.meta = {}
      } finally {
        this.loading = false
      }
    },

    async changePage(page) {
      if (page < 1 || page > this.totalPages) return
      this.currentPage = page
      await this.fetch()
    },
  }
}

/**
 * formHandler(endpoint, method, redirectUrl)
 * Submits a form to the API, handles loading state, maps 422 errors per-field,
 * and redirects on success.
 *
 * @param {string}      endpoint    - API endpoint
 * @param {string}      method      - HTTP method (default: 'POST')
 * @param {string|null} redirectUrl - URL to redirect to after success (default: null)
 */
function formHandler(endpoint, method = 'POST', redirectUrl = null) {
  return {
    errors:       {},
    isSubmitting: false,

    async submit(data) {
      this.isSubmitting = true
      this.errors       = {}
      try {
        await api.request(method, endpoint, data)
        if (redirectUrl) {
          window.location.href = redirectUrl
        }
      } catch (err) {
        if (err && err.errors) {
          this.errors = err.errors
        }
      } finally {
        this.isSubmitting = false
      }
    },
  }
}

/**
 * confirmDialog()
 * Reusable confirmation modal before destructive actions (delete, etc.).
 */
function confirmDialog() {
  return {
    visible:   false,
    message:   '',
    onConfirm: null,

    open(message, callback) {
      this.message   = message
      this.onConfirm = callback
      this.visible   = true
    },

    confirm() {
      if (typeof this.onConfirm === 'function') {
        this.onConfirm()
      }
      this.visible = false
    },

    cancel() {
      this.visible = false
    },
  }
}

/**
 * exportPdf(endpoint, params)
 * Hits the export endpoint, streams the response as a blob, and triggers a browser download.
 *
 * @param {string} endpoint - PDF export API endpoint
 * @param {object} params   - Additional query params (optional)
 */
async function exportPdf(endpoint, params = {}) {
  const query = new URLSearchParams(params).toString()
  const url   = query ? `${endpoint}?${query}` : endpoint
  try {
    const res  = await fetch(url, {
      headers: {
        'Authorization': 'Bearer ' + auth.getToken(),
        'Accept':        'application/pdf',
      },
    })
    if (!res.ok) {
      errorHandler.show('Failed to download PDF. Please try again.')
      return
    }
    const blob    = await res.blob()
    const blobUrl = URL.createObjectURL(blob)
    const a       = document.createElement('a')
    a.href        = blobUrl
    a.download    = 'export.pdf'
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    URL.revokeObjectURL(blobUrl)
  } catch (err) {
    errorHandler.catch(err)
  }
}

/**
 * datepicker(fieldName, initialValue)
 * Reusable date picker with calendar overlay, month navigation, and click-outside dismiss.
 * Renders a hidden input for form submission with the selected value (YYYY-MM-DD).
 *
 * @param {string} fieldName    - Input name attribute value
 * @param {string} initialValue - Initial date in YYYY-MM-DD format (optional)
 */
function datepicker(fieldName, initialValue = '') {
  const months = [
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
  ]
  const today = new Date()

  let initMonth = today.getMonth()
  let initYear  = today.getFullYear()
  let initDisplay = ''

  if (initialValue) {
    const d = new Date(initialValue + 'T00:00:00')
    if (!isNaN(d.getTime())) {
      initMonth   = d.getMonth()
      initYear    = d.getFullYear()
      initDisplay = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear()
    }
  }

  return {
    open:          false,
    name:          fieldName,
    selectedValue: initialValue,
    displayText:   initDisplay,
    currentMonth:  initMonth,
    currentYear:   initYear,
    months,
    daysOfWeek: ['Min','Sen','Sel','Rab','Kam','Jum','Sab'],

    openCalendar() {
      this.open = true
    },

    closeCalendar() {
      this.open = false
    },

    prevMonth() {
      if (this.currentMonth === 0) {
        this.currentMonth = 11
        this.currentYear--
      } else {
        this.currentMonth--
      }
    },

    nextMonth() {
      if (this.currentMonth === 11) {
        this.currentMonth = 0
        this.currentYear++
      } else {
        this.currentMonth++
      }
    },

    selectDate(day) {
      const month  = String(this.currentMonth + 1).padStart(2, '0')
      const dayStr = String(day).padStart(2, '0')
      this.selectedValue = this.currentYear + '-' + month + '-' + dayStr
      this.displayText   = day + ' ' + this.months[this.currentMonth] + ' ' + this.currentYear
      this.open          = false
    },

    isSelected(day) {
      if (!this.selectedValue) return false
      const d = new Date(this.selectedValue + 'T00:00:00')
      return d.getDate() === day && d.getMonth() === this.currentMonth && d.getFullYear() === this.currentYear
    },

    isToday(day) {
      const t = new Date()
      return t.getDate() === day && t.getMonth() === this.currentMonth && t.getFullYear() === this.currentYear
    },

    calendarDays() {
      const firstDay    = new Date(this.currentYear, this.currentMonth, 1).getDay()
      const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate()
      const days = []
      for (let i = 0; i < firstDay; i++) days.push(null)
      for (let i = 1; i <= daysInMonth; i++) days.push(i)
      return days
    },
  }
}

/**
 * currencyInput(fieldName, initialValue)
 * Currency/price input with auto-formatting (Indonesian thousand separator — dots).
 * Strips non-digit characters as the user types and formats with dot separators.
 * The raw numeric string is stored in a hidden input for form submission.
 *
 * @param {string} fieldName    - Input name attribute value
 * @param {string} initialValue - Default numeric value as a string (optional)
 */
function currencyInput(fieldName, initialValue = '') {
  const raw = String(initialValue).replace(/\D/g, '')
  const fmt = raw ? raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''

  return {
    name:        fieldName,
    rawValue:    raw,
    displayText: fmt,

    format(numStr) {
      return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.')
    },

    onInput(event) {
      const raw = event.target.value.replace(/\D/g, '')
      this.rawValue    = raw
      this.displayText = raw ? this.format(raw) : ''
    },
  }
}

/**
 * sidebarStore()
 * Manages mobile sidebar open/close state.
 * Used by _layouts/main.php and _partials/sidebar.php, _partials/navbar.php
 */
function sidebarStore() {
  return {
    sidebarOpen: false,
    toggle() {
      this.sidebarOpen = !this.sidebarOpen
    },
    close() {
      this.sidebarOpen = false
    },
  }
}

/**
 * detailFetcher(endpoint)
 * Fetches specific data for detail page.
 */
function detailFetcher(endpoint) {
  return {
    data: {},
    loading: true,
    async init() {
      try {
        const res = await api.get(endpoint)
        this.data = res.data ?? res
      } catch (err) {
        errorHandler.catch(err)
      } finally {
        this.loading = false
      }
    }
  }
}
