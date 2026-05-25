{{-- Tab Navigation --}}
<div class="flex gap-1 mb-6 border-b border-gray-200">
    <button type="button" onclick="switchTab('content')" id="tab-content"
        class="tab-btn px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors">
        Content
    </button>
    <button type="button" onclick="switchTab('seo')" id="tab-seo"
        class="tab-btn px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors">
        SEO
    </button>
    <button type="button" onclick="switchTab('schema')" id="tab-schema"
        class="tab-btn px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors">
        Schema Markup
    </button>
</div>

{{-- ==================== CONTENT TAB ==================== --}}
<div id="panel-content" class="tab-panel space-y-5">

    {{-- Title --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" id="title-input"
               value="{{ old('title', $article->title ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
               placeholder="Article title" required>
    </div>

    {{-- Slug --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-400 whitespace-nowrap">/articles/</span>
            <input type="text" name="slug" id="slug-input"
                   value="{{ old('slug', $article->slug ?? '') }}"
                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                   placeholder="auto-generated-from-title">
        </div>
        <p class="text-xs text-gray-400 mt-1">Leave blank to auto-generate from title. Only lowercase letters, numbers, and hyphens.</p>
    </div>

    {{-- Excerpt --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
        <textarea name="excerpt" rows="3"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                  placeholder="Short summary shown in article listings...">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
    </div>

    {{-- Body --}}
    <div>
        <div class="flex items-center justify-between mb-1">
            <label class="block text-sm font-medium text-gray-700">Body</label>
            <label for="html-upload" class="cursor-pointer inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Upload HTML
            </label>
        </div>
        <input type="file" id="html-upload" accept=".html,.htm" class="hidden">
        <p id="html-upload-status" class="text-xs text-gray-400 mb-1 hidden"></p>
        <textarea name="body" id="article-body" rows="20"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('body', $article->body ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-5">
        {{-- Thumbnail --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
            @if(!empty($article->thumbnail ?? null))
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$article->thumbnail) }}" class="h-24 w-auto rounded-lg object-cover border border-gray-200">
                    <p class="text-xs text-gray-400 mt-1">Upload a new image to replace</p>
                </div>
            @endif
            <input type="file" name="thumbnail" accept="image/*"
                   class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            <p class="text-xs text-gray-400 mt-1">Max 4MB. JPG, PNG, WebP.</p>
        </div>

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                <option value="draft" {{ old('status', $article->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $article->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
            </select>
            <p class="text-xs text-gray-400 mt-1">Published articles are visible on the site.</p>
        </div>
    </div>
</div>

{{-- ==================== SEO TAB ==================== --}}
<div id="panel-seo" class="tab-panel space-y-5 hidden">

    <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-700">
        Fields here override the defaults derived from title and excerpt. Leave blank to use defaults.
    </div>

    {{-- SEO Title + live counter --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">SEO Title <span class="text-xs text-gray-400">(max 60 chars)</span></label>
        <input type="text" name="seo_title" maxlength="255"
               value="{{ old('seo_title', $article->seo_title ?? '') }}"
               oninput="updateCounter(this, 'cnt-seo-title', 60)"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
               placeholder="Defaults to article title">
        <p class="text-xs text-gray-400 mt-1"><span id="cnt-seo-title">0</span>/60 chars</p>
    </div>

    {{-- Meta Description --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description <span class="text-xs text-gray-400">(max 160 chars)</span></label>
        <textarea name="seo_description" maxlength="320" rows="3"
                  oninput="updateCounter(this, 'cnt-seo-desc', 160)"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                  placeholder="Defaults to excerpt">{{ old('seo_description', $article->seo_description ?? '') }}</textarea>
        <p class="text-xs text-gray-400 mt-1"><span id="cnt-seo-desc">0</span>/160 chars</p>
    </div>

    {{-- Keywords --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
        <input type="text" name="seo_keywords" maxlength="500"
               value="{{ old('seo_keywords', $article->seo_keywords ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
               placeholder="spices, Indonesia, export (comma-separated)">
    </div>

    {{-- Canonical URL --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Canonical URL</label>
        <input type="url" name="canonical_url"
               value="{{ old('canonical_url', $article->canonical_url ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
               placeholder="https://nusagrade.com/articles/slug — leave blank to auto-set">
    </div>

    <hr class="border-gray-200">
    <p class="text-sm font-semibold text-gray-700">Open Graph (Social Sharing)</p>

    {{-- OG Title --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">OG Title</label>
        <input type="text" name="og_title" maxlength="255"
               value="{{ old('og_title', $article->og_title ?? '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
               placeholder="Defaults to SEO title or article title">
    </div>

    {{-- OG Description --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">OG Description</label>
        <textarea name="og_description" maxlength="320" rows="2"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"
                  placeholder="Defaults to meta description or excerpt">{{ old('og_description', $article->og_description ?? '') }}</textarea>
    </div>

    {{-- OG Image --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">OG Image</label>
        @if(!empty($article->og_image ?? null))
            <div class="mb-2">
                <img src="{{ asset('storage/'.$article->og_image) }}" class="h-24 w-auto rounded-lg object-cover border border-gray-200">
                <p class="text-xs text-gray-400 mt-1">Upload a new image to replace</p>
            </div>
        @endif
        <input type="file" name="og_image" accept="image/*"
               class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
        <p class="text-xs text-gray-400 mt-1">Recommended: 1200×630px. Max 4MB.</p>
    </div>
</div>

{{-- ==================== SCHEMA TAB ==================== --}}
<div id="panel-schema" class="tab-panel space-y-5 hidden">

    <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-lg text-sm text-yellow-800">
        Enter valid JSON-LD here. It will be injected as <code class="font-mono bg-yellow-100 px-1 rounded">&lt;script type="application/ld+json"&gt;</code> in the page head.
        Leave blank to skip.
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Schema Markup (JSON-LD)</label>
        <textarea name="schema_markup" id="schema-input" rows="20"
                  oninput="validateJson(this)"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-gray-900"
                  placeholder='{
  "@@context": "https://schema.org",
  "@@type": "Article",
  "headline": "Your article title",
  "author": {
    "@@type": "Organization",
    "name": "Nusagrade"
  }
}'>{{ old('schema_markup', isset($article->schema_markup) ? json_encode($article->schema_markup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
        <p id="json-status" class="text-xs mt-1 text-gray-400">Enter JSON to validate.</p>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/wk4w74o9ch1lqkd7giaq1a2pvhdqfpuhx0qtoc8zzxdq12jf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // TinyMCE
    tinymce.init({
        selector: '#article-body',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontsize | bold italic underline strikethrough | link image media table | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 500,
        promotion: false,
        branding: false,
    });

    // Auto-generate slug from title
    const titleInput = document.getElementById('title-input');
    const slugInput  = document.getElementById('slug-input');
    let slugManuallyEdited = slugInput.value !== '';

    slugInput.addEventListener('input', () => { slugManuallyEdited = true; });

    titleInput.addEventListener('input', () => {
        if (slugManuallyEdited) return;
        slugInput.value = titleInput.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    });

    // Tab switching
    function switchTab(name) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('border-gray-900', 'text-gray-900');
            b.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('panel-' + name).classList.remove('hidden');
        const btn = document.getElementById('tab-' + name);
        btn.classList.add('border-gray-900', 'text-gray-900');
        btn.classList.remove('border-transparent', 'text-gray-500');
    }
    switchTab('content');

    // Character counters
    function updateCounter(el, counterId, soft) {
        const len = el.value.length;
        const span = document.getElementById(counterId);
        span.textContent = len;
        span.className = len > soft ? 'text-red-500' : '';
    }
    // Init counters on load
    document.querySelectorAll('[oninput*="updateCounter"]').forEach(el => el.dispatchEvent(new Event('input')));

    // JSON-LD validator
    function validateJson(el) {
        const status = document.getElementById('json-status');
        if (!el.value.trim()) { status.textContent = 'Enter JSON to validate.'; status.className = 'text-xs mt-1 text-gray-400'; return; }
        try {
            JSON.parse(el.value);
            status.textContent = 'Valid JSON.';
            status.className = 'text-xs mt-1 text-green-600';
        } catch(e) {
            status.textContent = 'Invalid JSON: ' + e.message;
            status.className = 'text-xs mt-1 text-red-500';
        }
    }
    const schemaEl = document.getElementById('schema-input');
    if (schemaEl && schemaEl.value.trim()) validateJson(schemaEl);

    // HTML file upload → populate TinyMCE body
    document.getElementById('html-upload').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const status = document.getElementById('html-upload-status');
        const reader = new FileReader();
        reader.onload = function (e) {
            let html = e.target.result;
            const bodyMatch = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
            if (bodyMatch) html = bodyMatch[1].trim();
            const editor = tinymce.get('article-body');
            if (editor) {
                editor.setContent(html);
                status.textContent = 'Loaded: ' + file.name;
                status.className = 'text-xs text-green-600 mb-1';
            } else {
                document.getElementById('article-body').value = html;
                status.textContent = 'Loaded: ' + file.name + ' (editor not ready — save to apply)';
                status.className = 'text-xs text-yellow-600 mb-1';
            }
            status.classList.remove('hidden');
        };
        reader.onerror = function () {
            status.textContent = 'Failed to read file.';
            status.className = 'text-xs text-red-500 mb-1';
            status.classList.remove('hidden');
        };
        reader.readAsText(file);
        this.value = '';
    });
</script>
