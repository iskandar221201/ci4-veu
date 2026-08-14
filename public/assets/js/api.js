/**
 * api.js — Fetch wrapper for CI4 Kit Views Layer
 * Depends on: auth.js, error.js (must be loaded first)
 *
 * Behavior:
 * - Automatically attaches Authorization: Bearer <token> if token exists
 * - Unwraps response envelope { status, code, message, data, errors }
 * - 401 → auth.logout() → redirect /login
 * - 422 → throw { errors } → caught by formHandler per-field
 * - !json.status (4xx/5xx) → errorHandler.catch() + throw { message }
 * - Success → return json (full envelope)
 */
const api = {
  async request(method, url, body = null) {
    const token = auth.getToken()
    const headers = {
      'Content-Type': 'application/json',
      'Accept':       'application/json',
    }
    if (token) {
      headers['Authorization'] = 'Bearer ' + token
    }

    const options = { method, headers }
    if (body !== null) {
      options.body = JSON.stringify(body)
    }

    let res, json
    try {
      res  = await fetch(url, options)
      json = await res.json()
    } catch (networkErr) {
      errorHandler.catch({ message: 'Unable to connect to the server.' })
      throw networkErr
    }

    if (res.status === 401) {
      // Jangan auto-logout untuk endpoint login itu sendiri
      if (!url.includes('/auth/login')) {
        auth.logout()
        return
      }
      throw { message: json.message || 'Email atau password salah.' }
    }

    if (res.status === 422) {
      throw { errors: json.errors }
    }

    if (!json.status) {
      errorHandler.catch({ message: json.message })
      throw { message: json.message }
    }

    return json
  },

  get(url) {
    return this.request('GET', url)
  },

  post(url, body) {
    return this.request('POST', url, body)
  },

  put(url, body) {
    return this.request('PUT', url, body)
  },

  delete(url) {
    return this.request('DELETE', url)
  },
}
