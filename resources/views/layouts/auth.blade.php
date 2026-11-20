@extends ('layouts.base')
@section ('html-class', 'customizer-hide')
@push ('styles')
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
@endpush
@section ('body')
  <main id="main-content" class="container-xxl" tabindex="-1">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        @include ('partials.feedback')
        @yield ('content')
        <p class="text-center text-muted small mt-4 mb-0">Sistem Informasi Layanan Akademik dan Pendidikan</p>
      </div>
    </div>
  </main>
@endsection
