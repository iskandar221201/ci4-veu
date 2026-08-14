/**
 * tus-client.js — Optional TUS chunked upload helper for CI4 Kit Views Layer
 * Depends on: auth.js (for getToken), error.js (for errorHandler)
 * Requires: tus-js-client loaded before this script
 *   <script src="https://cdn.jsdelivr.net/npm/tus-js-client@latest/dist/tus.min.js"></script>
 *
 * This file is NOT loaded in layouts — include it only in views that need chunked upload.
 *
 * Usage in an Alpine component:
 *   <div x-data="tusUploader({ endpoint: '/api/upload/tus', onSuccess: (url) => console.log(url) })">
 *     <input type="file" @change="start($event.target.files[0])">
 *     <template x-if="isUploading">
 *       <progress :value="progress" max="100"></progress>
 *     </template>
 *     <template x-if="isComplete">
 *       <a :href="result" x-text="result"></a>
 *     </template>
 *   </div>
 */
function tusUploader(options) {
  return {
    file:        null,
    upload:      null,
    progress:    0,
    isUploading: false,
    isComplete:  false,
    error:       null,
    result:      null,

    start(file) {
      this.file        = file
      this.progress    = 0
      this.isUploading = true
      this.isComplete  = false
      this.error       = null
      this.result      = null

      this.upload = new tus.Upload(file, {
        endpoint:    options.endpoint || '/api/upload/tus',
        chunkSize:   options.chunkSize || 5 * 1024 * 1024,
        retryDelays: options.retryDelays || [0, 1000, 3000, 5000],
        headers:     {
          'Authorization': 'Bearer ' + auth.getToken(),
        },
        metadata: options.metadata || {},
        onError: (err) => {
          this.isUploading = false
          this.error       = err
          errorHandler.catch(err)
          if (options.onError) options.onError(err)
        },
        onProgress: (bytesSent, bytesTotal) => {
          this.progress = bytesTotal > 0 ? (bytesSent / bytesTotal) * 100 : 0
          if (options.onProgress) options.onProgress(bytesSent, bytesTotal, this.progress)
        },
        onSuccess: () => {
          this.isUploading = false
          this.isComplete  = true
          this.result      = this.upload.url
          if (options.onSuccess) options.onSuccess(this.upload.url)
        },
      })

      this.upload.start()
    },

    abort() {
      if (this.upload) {
        this.upload.abort()
        this.isUploading = false
      }
    },
  }
}
