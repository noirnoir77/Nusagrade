@extends('layouts.nusagrade')

@section('content')
<!-- ===== 404 PAGE ===== -->
<section class="min-h-screen flex items-center justify-center error-top-spacing" style="background:var(--cream);">
  <div class="container text-center py-20">
    <!-- 404 Number -->
    <div class="mb-16">
      <p class="serif text-9xl font-bold leading-none opacity-20" style="color:var(--brown-light);">
        404
      </p>
    </div>
    
    <!-- Message -->
    <div class="space-y-6">
      <h1 class="serif text-5xl font-bold mb-2" style="color:var(--ink);">
        Page Not Found
      </h1>
      
      <p class="text-2xl max-w-3xl mx-auto mb-8 leading-relaxed" style="color:var(--ink-light);">
        We're sorry, but the page you're looking for doesn't exist or has been moved.
        Please check the URL or return to our homepage to explore our premium Indonesian commodities.
      </p>
      
      <div>
        <a href="{{ route('home') }}" class="btn-primary btn-xl" style="background:var(--brown);color:var(--cream);border-color:var(--brown);">
          Return to Homepage
        </a>
      </div>
    </div>
  </div>
</section>
@endsection
