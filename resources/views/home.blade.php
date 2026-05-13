@extends('layouts.nusagrade')

@section('seo-title', 'Nusagrade - Premium Indonesian Commodities')
@section('seo-description', 'Nusagrade connects global buyers with premium Indonesian spices, coffee, and cocoa. Trusted exporter delivering authentic commodities to Europe and Asia.')
@section('canonical', url('/'))
@section('og-title', 'Nusagrade - Premium Indonesian Commodities')
@section('og-description', 'Trusted Indonesian export company for premium spices, coffee, and cocoa. Connecting Europe and Asia with the best of the archipelago.')
@section('og-image', asset('images/hero.png'))

@push('head')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "Organization",
      "@@id": "{{ url('/') }}/#organization",
      "name": "Nusagrade",
      "url": "{{ url('/') }}",
      "description": "Trusted Indonesian export company connecting the world with authentic spices, coffee, and cocoa from Indonesia's fertile islands.",
      "email": "info@nusagrade.com",
      "foundingLocation": {
        "@@type": "Country",
        "name": "Indonesia"
      },
      "areaServed": [
        { "@@type": "Continent", "name": "Europe" },
        { "@@type": "Continent", "name": "Asia" }
      ],
      "contactPoint": {
        "@@type": "ContactPoint",
        "contactType": "customer service",
        "email": "info@nusagrade.com",
        "availableLanguage": ["English", "Indonesian"]
      }
    },
    {
      "@@type": "WebSite",
      "@@id": "{{ url('/') }}/#website",
      "url": "{{ url('/') }}",
      "name": "Nusagrade",
      "description": "Premium Indonesian commodities export - spices, coffee, and cocoa delivered globally.",
      "publisher": {
        "@@id": "{{ url('/') }}/#organization"
      }
    }
  ]
}
</script>
@endpush

@section('content')

<!-- ===== HERO ===== -->
<section id="hero">
  <div class="hero-bg">
    <img src="{{ asset('images/hero.png') }}" alt="Indonesian spice farm" class="img-real" style="object-position: center;" />
  </div>
  <div class="hero-overlay"></div>
  <div class="hero-content container">
    <p class="hero-eyebrow">Est. Indonesia &nbsp;·&nbsp; Delivering to Europe &amp; Asia</p>
    <h1 class="hero-headline reveal">Your Trusted Source for Indonesian Commodities</h1>
    <p class="hero-sub reveal reveal-delay-1">Premium Cengkeh (Cloves), Kapulaga (Cardanom), Lada (Pepper), Kopi (Coffee) &amp; Kakao (Cocoa) - sustainably sourced and delivered worldwide.</p>
    <div class="hero-actions reveal reveal-delay-2">
      <a href="#products" class="btn-primary">Explore Products</a>
      <a href="#about" class="btn-outline">Our Story</a>
    </div>
  </div>
  <div class="hero-scroll">
    <div class="scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>

<!-- ===== TICKER ===== -->
<div id="ticker">
  <div class="ticker-inner">
    @php $tickerItems = ['Cengkeh (Cloves)', 'Kapulaga (Cardanom)', 'Lada (Pepper)', 'Kopi (Coffee)', 'Kakao (Cocoa)']; @endphp
    @foreach ([0, 1] as $_)
      <span class="ticker-label">We Export</span>
      @foreach ($tickerItems as $item)
        <span class="ticker-item"><span class="ticker-dot"></span>{{ $item }}</span>
      @endforeach
      <span class="ticker-label">Premium Quality</span>
      @foreach ($tickerItems as $item)
        <span class="ticker-item"><span class="ticker-dot"></span>{{ $item }}</span>
      @endforeach
    @endforeach
  </div>
</div>

<!-- ===== ABOUT ===== -->
<section id="about">
  <div class="container">
    <div class="about-grid">
      <div class="about-img-stack reveal">
        <div class="about-img-main">
          <img src="{{ asset('images/about-main.png') }}" alt="Nusagrade Spice Plantation" class="img-real" style="width:100%;height:100%;object-fit:cover;" />
        </div>
        <div class="about-img-accent">
          <img src="{{ asset('images/about-accent.png') }}" alt="Produk Nusagrade" class="img-real" />
        </div>
        <div class="about-stats">
          @foreach ($stats as $stat)
            <div class="stat-item">
              <div class="stat-num">{{ $stat['value'] }}</div>
              <div class="stat-label">{{ $stat['label'] }}</div>
            </div>
          @endforeach
        </div>
      </div>
      <div>
        <div class="tag reveal">About Nusagrade</div>
        <h2 class="about-title reveal reveal-delay-1">Rooted in the Archipelago,<br />Reaching the World</h2>
        <div class="section-divider reveal reveal-delay-2"></div>
        <p class="about-body reveal reveal-delay-2">Nusagrade is a trusted Indonesian export company connecting the world's demand for authentic spices, coffee, and cocoa with the rich harvests of Indonesia's fertile islands. Built on decades of local sourcing expertise, we deliver premium commodities to European and Asian markets with consistency, transparency, and care.</p>
        <div class="vision-mission reveal reveal-delay-3">
          <div class="vm-item">
            <div class="vm-label">Vision</div>
            <div class="vm-text">To be Indonesia's most trusted commodity export partner in global markets.</div>
          </div>
          <div class="vm-item">
            <div class="vm-label">Mission</div>
            <div class="vm-text">Deliver sustainable, premium-grade Indonesian produce to the world.</div>
          </div>
          <div class="vm-item">
            <div class="vm-label">Values</div>
            <div class="vm-text">Quality · Integrity · Sustainability · Community</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== PRODUCTS ===== -->
<section id="products">
  <div class="container">
    <div class="products-header">
      <div>
        <div class="tag" style="background:rgba(196,149,106,0.1);border-color:rgba(196,149,106,0.25);color:var(--brown-light);">Our Products</div>
        <h2 class="products-title reveal">Our Commodity Range</h2>
      </div>
      <p class="products-sub reveal">Premium Indonesian agricultural exports sourced from verified local farmers.</p>
    </div>
    <div class="products-grid reveal">
      @foreach ($products as $key => $product)
        <div class="product-card" onclick="openModal('{{ $key }}')">
          @if(!empty($product['image']))
            <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="product-img-real" />
          @else
            <div class="product-img-ph img-ph dark">{{ $product['name'] }}<br>photo</div>
          @endif
          <div class="product-body">
            <div class="product-name">{{ $product['name'] }}</div>
            <div class="product-desc">{{ $product['shortDesc'] }}</div>
            <span class="product-link">View Specs</span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== VALUES ===== -->
<section id="values">
  <div class="container">
    <div class="values-header">
      <div class="tag reveal">Why Nusagrade</div>
      <h2 class="values-title reveal">Built on Quality &amp; Trust</h2>
      <p class="values-body reveal">We go beyond sourcing - we are your end-to-end partner in Indonesian commodity export, from farm to final delivery.</p>
    </div>
    <div class="values-grid">
      @foreach ($values as $i => $value)
        <div class="value-card reveal {{ $i > 0 ? 'reveal-delay-' . $i : '' }}">
          <div class="value-icon">{{ $value['icon'] }}</div>
          <h3 class="value-title">{{ $value['title'] }}</h3>
          <p class="value-text">{{ $value['text'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== CTA ===== -->
<section id="cta">
  <div class="cta-bg-text">Nusagrade</div>
  <div class="container">
    <div class="cta-inner">
      <div>
        <div class="tag reveal" style="background:rgba(196,149,106,0.1);border-color:rgba(196,149,106,0.25);color:var(--brown-light);">Partner With Us</div>
        <h2 class="cta-headline reveal">Let's Source Indonesia's Best for Your Market</h2>
        <p class="cta-sub reveal reveal-delay-1">Join 50+ global buyers who trust Nusagrade for consistent, premium-quality Indonesian commodities - delivered reliably to Europe and Asia.</p>
      </div>
      <div class="cta-right reveal reveal-delay-2">
        <div class="cta-actions">
          <a href="#contact" class="btn-cream">Contact Us Now</a>
          <a href="#products" class="btn-outline-cream">View Products</a>
        </div>
        <p class="cta-meta">We respond within 1 business day &nbsp;·&nbsp; No commitment required</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== GALLERY ===== -->
<section id="gallery">
  <div class="container">
    <div class="gallery-header">
      <div>
        <div class="tag reveal">Gallery</div>
        <h2 class="gallery-title reveal">From Farm to Port</h2>
      </div>
      <a href="#" class="gallery-link reveal">View All</a>
    </div>
    <div class="gallery-grid reveal">
      @foreach ($gallery as $item)
        <div class="gallery-item">
          @if(!empty($item['image']))
            <img src="{{ asset($item['image']) }}" alt="{{ $item['caption'] }}" class="img-real" />
          @else
            <div class="img-ph" style="width:100%;height:100%;">{{ $item['label'] }}</div>
          @endif
          <div class="gallery-item-overlay"><span class="gallery-item-label">{{ $item['caption'] }}</span></div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== FAQ ===== -->
<section id="faq">
  <div class="container">
    <div class="faq-grid">
      <div>
        <div class="tag reveal">FAQ</div>
        <h2 class="faq-left-title reveal">Common Questions</h2>
      </div>
      <div class="faq-list reveal">
        @foreach ($faqs as $faq)
          <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
              <span>{{ $faq['question'] }}</span>
              <span class="faq-icon">+</span>
            </div>
            <div class="faq-answer">{{ $faq['answer'] }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- ===== BLOG ===== -->
<section id="blog">
  <div class="container">
    <div class="blog-header">
      <div>
        <div class="tag reveal">Blog</div>
        <h2 class="blog-title reveal">Latest Insights</h2>
      </div>
      <a href="{{ route('articles.index') }}" class="gallery-link reveal">All Articles</a>
    </div>
    <div class="blog-grid">
      @forelse ($articles as $i => $article)
        <a href="{{ route('articles.show', $article->slug) }}" class="blog-card reveal {{ $i > 0 ? 'reveal-delay-' . $i : '' }}" style="text-decoration:none;color:inherit;">
          <div class="blog-img">
            @if($article->thumbnail)
              <img src="{{ asset('storage/'.$article->thumbnail) }}" alt="{{ $article->title }}" class="blog-img-real" />
            @else
              <div class="img-ph" style="width:100%;height:100%;">{{ $i === 0 ? 'featured article' : 'article' }}<br>image</div>
            @endif
          </div>
          <div class="blog-date">{{ $article->published_at ? $article->published_at->format('M Y') : '' }}</div>
          <h3 class="blog-name">{{ $article->title }}</h3>
          <p class="blog-excerpt">{{ $article->excerpt }}</p>
        </a>
      @empty
        <p style="color:var(--ink-light);font-size:0.9rem;">No articles published yet.</p>
      @endforelse
    </div>
  </div>
</section>

<!-- ===== CONTACT ===== -->
<section id="contact">
  <div class="contact-grid">
    <div class="contact-left reveal">
      <div class="tag">Get in Touch</div>
      <h2 class="contact-title">Start a Conversation</h2>
      <p class="contact-sub">Tell us about your sourcing needs and we'll get back to you within one business day. Whether it's a quote, a sample request, or a general inquiry - we're here.</p>
      <div class="contact-info">
        <div class="contact-info-item">
          <span class="contact-info-label">Address</span>
          <span class="contact-info-value">Indonesia</span>
        </div>
        <div class="contact-info-item">
          <span class="contact-info-label">Email</span>
          <span class="contact-info-value">info@nusagrade.com</span>
        </div>
        <div class="contact-info-item">
          <span class="contact-info-label">Phone / WhatsApp</span>
          <span class="contact-info-value">+62 xxx-xxxx-xxxx</span>
        </div>
      </div>
    </div>
    <div class="contact-right reveal reveal-delay-1">

      @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
      @endif

      <form class="contact-form" method="POST" action="{{ route('contact.submit') }}">
        @csrf
        <div class="form-row">
          <div class="form-field">
            <label class="form-label" for="name">Full Name</label>
            <input class="form-input" id="name" name="name" type="text" placeholder="Your name" value="{{ old('name') }}" />
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-field">
            <label class="form-label" for="company">Company</label>
            <input class="form-input" id="company" name="company" type="text" placeholder="Company name" value="{{ old('company') }}" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-field">
            <label class="form-label" for="email">Email</label>
            <input class="form-input" id="email" name="email" type="email" placeholder="you@company.com" value="{{ old('email') }}" />
            @error('email')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-field">
            <label class="form-label" for="phone">Phone</label>
            <input class="form-input" id="phone" name="phone" type="tel" placeholder="+xx xxx-xxxx" value="{{ old('phone') }}" />
          </div>
        </div>
        <div class="form-field">
          <label class="form-label" for="message">Message</label>
          <textarea class="form-textarea" id="message" name="message" placeholder="Tell us about your sourcing requirements…">{{ old('message') }}</textarea>
          @error('message')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <button class="form-submit" type="submit">Send Message</button>
      </form>
    </div>
  </div>
</section>

@endsection
