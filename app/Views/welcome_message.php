<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CI4 Kit — Production-Grade CodeIgniter 4 Starter</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      background: #fff;
      color: #111827;
    }

    code, pre, .mono {
      font-family: 'JetBrains Mono', monospace;
    }

    /* Signature element: thin top rule in charcoal */
    .top-rule {
      width: 100%;
      height: 3px;
      background: #111827;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: #6B7280;
      border: 1px solid #E5E7EB;
      border-radius: 999px;
      padding: 4px 10px;
    }

    .badge-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #22c55e;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 1px;
      background: #E5E7EB;
      border: 1px solid #E5E7EB;
      border-radius: 8px;
      overflow: hidden;
    }

    .feature-cell {
      background: #fff;
      padding: 24px;
    }

    .feature-label {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #9CA3AF;
      margin-bottom: 6px;
    }

    .feature-title {
      font-size: 14px;
      font-weight: 600;
      color: #111827;
      margin-bottom: 4px;
    }

    .feature-desc {
      font-size: 13px;
      color: #6B7280;
      line-height: 1.5;
    }

    .optional-tag {
      font-size: 10px;
      font-weight: 500;
      color: #9CA3AF;
      background: #F9FAFB;
      border: 1px solid #E5E7EB;
      border-radius: 4px;
      padding: 1px 6px;
      margin-left: 6px;
      vertical-align: middle;
    }

    .code-block {
      background: #111827;
      border-radius: 8px;
      padding: 20px 24px;
      overflow-x: auto;
    }

    .code-block pre {
      font-size: 13px;
      line-height: 1.7;
      color: #D1D5DB;
    }

    .code-block .comment { color: #6B7280; }
    .code-block .cmd { color: #F9FAFB; }
    .code-block .highlight { color: #86efac; }

    .layer-row {
      display: flex;
      align-items: flex-start;
      gap: 16px;
      padding: 16px 0;
      border-bottom: 1px solid #F3F4F6;
    }

    .layer-row:last-child { border-bottom: none; }

    .layer-name {
      font-family: 'JetBrains Mono', monospace;
      font-size: 12px;
      font-weight: 500;
      color: #111827;
      background: #F9FAFB;
      border: 1px solid #E5E7EB;
      border-radius: 4px;
      padding: 3px 8px;
      white-space: nowrap;
      min-width: 140px;
    }

    .layer-desc {
      font-size: 13px;
      color: #6B7280;
      line-height: 1.5;
      padding-top: 2px;
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #111827;
      color: #fff;
      font-size: 14px;
      font-weight: 500;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      transition: background 0.15s;
    }

    .btn-primary:hover { background: #374151; }

    .btn-secondary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: transparent;
      color: #374151;
      font-size: 14px;
      font-weight: 500;
      padding: 10px 20px;
      border-radius: 6px;
      border: 1px solid #D1D5DB;
      text-decoration: none;
      transition: border-color 0.15s, color 0.15s;
    }

    .btn-secondary:hover { border-color: #9CA3AF; color: #111827; }

    .divider {
      border: none;
      border-top: 1px solid #E5E7EB;
    }

    @media (max-width: 640px) {
      .feature-grid { grid-template-columns: 1fr; }
      .layer-row { flex-direction: column; gap: 6px; }
      .layer-name { min-width: auto; }
    }
  </style>
</head>
<body>

  <div class="top-rule"></div>

  <!-- Nav -->
  <nav style="padding: 0 32px; height: 56px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #E5E7EB;">
    <span class="mono" style="font-size: 14px; font-weight: 500; color: #111827; letter-spacing: -0.01em;">ci4-kit</span>
    <div style="display: flex; align-items: center; gap: 16px;">
      <span class="badge"><span class="badge-dot"></span>v3.1.0 stable</span>
      <a href="https://github.com/iskandar221201/codeigniter4-kit" target="_blank" class="btn-primary" style="padding: 7px 16px; font-size: 13px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
        GitHub
      </a>
    </div>
  </nav>

  <!-- Hero -->
  <section style="max-width: 760px; margin: 0 auto; padding: 80px 32px 64px;">
    <p style="font-size: 12px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: #9CA3AF; margin-bottom: 20px;">CodeIgniter 4 · PHP 8.2+</p>
    <h1 style="font-size: clamp(32px, 5vw, 48px); font-weight: 600; line-height: 1.1; letter-spacing: -0.02em; color: #111827; margin-bottom: 20px;">
      Production-grade architecture,<br>ready on day one.
    </h1>
    <p style="font-size: 16px; color: #6B7280; line-height: 1.65; max-width: 560px; margin-bottom: 36px;">
      A batteries-included CI4 starter kit with layered architecture, Shield authentication, structured logging, and a clean token-based web UI — built from a year of production experience.
    </p>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
      <a href="https://github.com/iskandar221201/codeigniter4-kit" target="_blank" class="btn-primary">
        Use this template
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      <a href="#quickstart" class="btn-secondary">Quick start</a>
    </div>
  </section>

  <!-- Architecture -->
  <section style="max-width: 760px; margin: 0 auto; padding: 0 32px 64px;">
    <hr class="divider" style="margin-bottom: 40px;">
    <p class="feature-label" style="margin-bottom: 20px;">Architecture</p>

    <div style="background: #111827; border-radius: 8px; padding: 20px 24px; margin-bottom: 32px;">
      <!-- Desktop: horizontal flow -->
      <div class="arch-desktop mono" style="font-size: 13px; color: #9CA3AF; line-height: 2; white-space: nowrap; overflow-x: auto;">
        <span style="color:#F9FAFB;">Request</span>
        <span style="color:#4B5563;"> → </span><span style="color:#D1D5DB;">Filter Stack</span>
        <span style="color:#4B5563;"> → </span><span style="color:#F9FAFB;">Controller</span>
        <span style="color:#4B5563;"> → </span><span style="color:#F9FAFB;">Service</span>
        <span style="color:#4B5563;"> → </span><span style="color:#F9FAFB;">Model</span>
        <span style="color:#4B5563;"> → </span><span style="color:#D1D5DB;">Database</span>
        <br>
        <span style="visibility:hidden;">Request → Filter Stack → Controller → </span><span style="color:#4B5563;">↓</span>
        <br>
        <span style="visibility:hidden;">Request → Filter Stack → Controller → </span><span style="color:#86efac;">Transformer</span>
      </div>
      <!-- Mobile: vertical flow -->
      <div class="arch-mobile mono" style="display:none; font-size: 12px; color: #9CA3AF; line-height: 1;">
        <div style="display:flex; flex-direction:column; align-items:flex-start; gap: 0;">
          <span style="color:#F9FAFB; padding: 6px 10px; background:#1F2937; border-radius:4px;">Request</span>
          <span style="color:#4B5563; padding: 2px 14px;">↓</span>
          <span style="color:#D1D5DB; padding: 6px 10px; background:#1F2937; border-radius:4px;">Filter Stack</span>
          <span style="color:#4B5563; padding: 2px 14px;">↓</span>
          <span style="color:#F9FAFB; padding: 6px 10px; background:#1F2937; border-radius:4px;">Controller</span>
          <span style="color:#4B5563; padding: 2px 14px;">↓</span>
          <span style="color:#F9FAFB; padding: 6px 10px; background:#1F2937; border-radius:4px;">Service</span>
          <span style="color:#4B5563; padding: 2px 14px;">↓  <span style="color:#86efac; font-size:11px;">→ Transformer</span></span>
          <span style="color:#F9FAFB; padding: 6px 10px; background:#1F2937; border-radius:4px;">Model</span>
          <span style="color:#4B5563; padding: 2px 14px;">↓</span>
          <span style="color:#D1D5DB; padding: 6px 10px; background:#1F2937; border-radius:4px;">Database</span>
        </div>
      </div>
    </div>

    <style>
      @media (max-width: 600px) {
        .arch-desktop { display: none !important; }
        .arch-mobile { display: block !important; }
      }
    </style>

    <div>
      <div class="layer-row">
        <span class="layer-name">Web Controller</span>
        <span class="layer-desc">Renders HTML views. No business logic — just passes data to views.</span>
      </div>
      <div class="layer-row">
        <span class="layer-name">API Controller</span>
        <span class="layer-desc">Receives JSON, delegates to Service, returns JSON. Never touches a Model directly.</span>
      </div>
      <div class="layer-row">
        <span class="layer-name">Service</span>
        <span class="layer-desc">Owns all business logic. Validates input, orchestrates Model calls, fires lifecycle hooks.</span>
      </div>
      <div class="layer-row">
        <span class="layer-name">Transformer</span>
        <span class="layer-desc">Shapes and sanitizes payloads before they leave the API response layer.</span>
      </div>
      <div class="layer-row">
        <span class="layer-name">Model</span>
        <span class="layer-desc">Extends <code class="mono" style="font-size:12px;">BaseModel</code> — soft delete, search, and dateRange scopes included.</span>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section style="max-width: 760px; margin: 0 auto; padding: 0 32px 64px;">
    <hr class="divider" style="margin-bottom: 40px;">
    <p class="feature-label" style="margin-bottom: 20px;">What's included</p>

    <div class="feature-grid">
      <div class="feature-cell">
        <div class="feature-label">Auth</div>
        <div class="feature-title">Shield Token Auth</div>
        <div class="feature-desc">Bearer token login without session conflict. Multi-device support. Admin user creation with password.</div>
      </div>
      <div class="feature-cell">
        <div class="feature-label">Filters</div>
        <div class="feature-title">Layered Filter Stack</div>
        <div class="feature-desc">CORS, JSON body validation, API key guard — wired and ordered correctly out of the box.</div>
      </div>
      <div class="feature-cell">
        <div class="feature-label">Logging</div>
        <div class="feature-title">Structured JSON Logs</div>
        <div class="feature-desc">Every log entry is a JSON line with timestamp, level, action, user ID, IP, and context.</div>
      </div>
      <div class="feature-cell">
        <div class="feature-label">Audit</div>
        <div class="feature-title">Audit Trail</div>
        <div class="feature-desc">Non-blocking create/update/delete logging at the service layer. Records old and new values.</div>
      </div>
      <div class="feature-cell">
        <div class="feature-label">Upload</div>
        <div class="feature-title">File Uploader</div>
        <div class="feature-desc">Streaming upload via <code class="mono" style="font-size:12px;">$file->move()</code>. UUID filenames. Pluggable Local and S3 drivers with retry.</div>
      </div>
      <div class="feature-cell">
        <div class="feature-label">UI</div>
        <div class="feature-title">Web UI Layer</div>
        <div class="feature-desc">Server-rendered PHP shell with Alpine.js. Stateless on the server — all auth in localStorage.</div>
      </div>
      <div class="feature-cell">
        <div class="feature-label">Optional</div>
        <div class="feature-title">SSO Layer <span class="optional-tag">opt-in</span></div>
        <div class="feature-desc">JWT RS256 cross-app authentication. Zero overhead when disabled — complete filter pass-through.</div>
      </div>
      <div class="feature-cell">
        <div class="feature-label">Optional</div>
        <div class="feature-title">PDF Export <span class="optional-tag">opt-in</span></div>
        <div class="feature-desc">Abstract base class for mPDF. One subclass per resource. Templates stay plain HTML.</div>
      </div>
      <div class="feature-cell">
        <div class="feature-label">Optional</div>
        <div class="feature-title">TUS Chunked Upload <span class="optional-tag">opt-in</span></div>
        <div class="feature-desc">Resumable uploads via TUS protocol. Pause, resume, and cleanup expired chunks via Spark command.</div>
      </div>
      <div class="feature-cell">
        <div class="feature-label">Response</div>
        <div class="feature-title">Transformers</div>
        <div class="feature-desc">Shape and sanitize API payloads before they leave the response layer. Strip sensitive fields, rename keys, add computed values.</div>
      </div>
    </div>
  </section>

  <!-- Quick Start -->
  <section id="quickstart" style="max-width: 760px; margin: 0 auto; padding: 0 32px 64px;">
    <hr class="divider" style="margin-bottom: 40px;">
    <p class="feature-label" style="margin-bottom: 20px;">Quick start</p>

    <div class="code-block">
      <pre>
<span class="comment"># 1. Clone and enter the project</span>
<span class="cmd">cd my-project</span>

<span class="comment"># 2. Copy env and fill in DB credentials</span>
<span class="cmd">cp .env.example .env</span>

<span class="comment"># 3. Install dependencies</span>
<span class="cmd">composer install</span>

<span class="comment"># 4. Run migrations</span>
<span class="cmd">php spark migrate --all</span>

<span class="comment"># 5. Seed admin user</span>
<span class="cmd">php spark db:seed AdminSeeder</span>

<span class="comment"># 6. Start dev server</span>
<span class="cmd">php spark serve</span>

<span class="comment"># Open the web UI</span>
<span class="highlight">http://localhost:8080/login</span>
<span class="comment"># admin@example.com  |  password123</span>

<span class="comment"># Verify the API</span>
<span class="cmd">curl http://localhost:8080/api/ping</span>
<span class="highlight">{"status":true,"code":200,"message":"pong","data":null}</span></pre>
    </div>
  </section>

  <!-- Add a resource -->
  <section style="max-width: 760px; margin: 0 auto; padding: 0 32px 64px;">
    <hr class="divider" style="margin-bottom: 40px;">
    <p class="feature-label" style="margin-bottom: 8px;">Extend it</p>
    <p style="font-size: 14px; color: #6B7280; margin-bottom: 24px;">Adding a new resource takes three files and a route registration.</p>

    <div class="code-block">
      <pre>
<span class="comment">// 1. Model</span>
<span class="cmd">class PostModel extends BaseModel {
    protected $table         = <span class="highlight">'posts'</span>;
    protected $allowedFields = [<span class="highlight">'title'</span>, <span class="highlight">'body'</span>];
    protected array $searchableFields = [<span class="highlight">'title'</span>, <span class="highlight">'body'</span>];
}</span>

<span class="comment">// 2. Service</span>
<span class="cmd">class PostService extends BaseService {
    protected string $modelClass = PostModel::class;
}</span>

<span class="comment">// 3. Controller</span>
<span class="cmd">class PostController extends BaseApiController {
    public function index(): ResponseInterface {
        return $this->success(
            (new PostService())->findAll($this->request->getGet())
        );
    }
}</span></pre>
    </div>
  </section>

  <!-- Footer -->
  <footer style="border-top: 1px solid #E5E7EB; padding: 32px; display: flex; align-items: center; justify-content: space-between; flex-wrap: gap; gap: 12px;">
    <span class="mono" style="font-size: 12px; color: #9CA3AF;">ci4-kit · MIT License</span>
    <a href="https://github.com/iskandar221201/codeigniter4-kit" target="_blank" style="font-size: 12px; color: #6B7280; text-decoration: none; display: flex; align-items: center; gap: 6px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
      iskandar221201/codeigniter4-kit
    </a>
  </footer>

</body>
</html>
