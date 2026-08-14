/**
 * error.js — Global error handler for CI4 Kit Views Layer
 * Must be loaded before api.js. Used by api.js and components.js.
 *
 * Responsibility breakdown:
 * - 401 Unauthorized  → handled by api.js → auth.logout() → redirect /login
 * - 422 Validation    → handled by formHandler → displayed per-field
 * - Other 4xx/5xx     → errorHandler.catch() → global toast
 * - Network error     → errorHandler.catch() → global toast
 */
const errorHandler = {
  message: '',
  visible: false,
  type: 'error', // 'error' | 'warning' | 'info'

  show(message, type = 'error') {
    this.message = message
    this.type    = type
    this.visible = true
    setTimeout(() => { this.visible = false }, 4000)
  },

  catch(err) {
    // 422 errors are handled per-field by formHandler — do not show in toast
    if (err && err.errors) return
    if (err && err.message) {
      this.show(err.message)
    } else {
      this.show('Something went wrong. Please try again.')
    }
  },
}
