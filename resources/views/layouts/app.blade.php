@extends ('layouts.base')
@section ('html-class', 'layout-menu-fixed')
@section ('body')
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      @include ('layouts.sidebar')
      <div class="layout-page">
        @include ('layouts.navbar')
        <div class="content-wrapper">
          <main id="main-content" class="container-xxl flex-grow-1 container-p-y" tabindex="-1">
            @include ('partials.feedback')
            @yield ('content')
          </main>
          @include ('layouts.footer')
          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
    <div class="drag-target"></div>
  </div>
  @include ('partials.confirmation')
@endsection
