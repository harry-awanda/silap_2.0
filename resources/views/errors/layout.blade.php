@extends ('layouts.base')
@section ('html-class', 'customizer-hide')
@section ('title', $code.' — '.$heading)
@section ('body')
  <main id="main-content" class="container-xxl container-p-y text-center" tabindex="-1">
    <div class="row justify-content-center py-5">
      <div class="col-md-8 col-lg-6">
        <span class="badge bg-label-primary mb-3">SILAP · {{ $code }}</span>
        <h1 class="h3">{{ $heading }}</h1>
        <p class="text-muted mb-4">{{ $description }}</p>
        <a class="btn btn-primary" href="{{ route('home') }}">Kembali ke beranda</a>
        <div class="mt-5">
          <img
            class="img-fluid"
            width="360"
            src="{{ asset('assets/img/illustrations/page-misc-error-light.png') }}"
            alt=""
          />
        </div>
      </div>
    </div>
  </main>
@endsection
