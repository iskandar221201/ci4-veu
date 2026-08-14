/**
 * auth.js — Token management for CI4 Kit Views Layer
 * Token key: _ck_token (distinct from _fk_token to avoid conflicts)
 */
const auth = {
  TOKEN_KEY:    '_ck_token',
  USERNAME_KEY: '_ck_username',

  getToken() {
    return localStorage.getItem(this.TOKEN_KEY)
  },

  setToken(token) {
    localStorage.setItem(this.TOKEN_KEY, token)
  },

  getUsername() {
    return localStorage.getItem(this.USERNAME_KEY) || 'User'
  },

  setUsername(username) {
    localStorage.setItem(this.USERNAME_KEY, username)
  },

  clearToken() {
    localStorage.removeItem(this.TOKEN_KEY)
    localStorage.removeItem(this.USERNAME_KEY)
  },

  isAuthenticated() {
    return !!this.getToken()
  },

  logout() {
    this.clearToken()
    window.location.href = '/login'
  },

  checkAuthRoute() {
    const publicPaths = ['/', '/login', '/register', '/forgot-password']
    const currentPath = window.location.pathname
    
    if (this.isAuthenticated()) {
      // Jika sudah login, cegah akses ke halaman login
      if (currentPath === '/login') {
        window.location.replace('/dashboard')
      }
    } else {
      // Jika belum login, cegah akses ke halaman protected
      if (!publicPaths.includes(currentPath)) {
        window.location.replace('/login')
      }
    }
  }
}

// Run auth check automatically on page load
auth.checkAuthRoute()
