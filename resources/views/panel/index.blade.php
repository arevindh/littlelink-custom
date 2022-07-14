@extends('layouts.sidebar')

@section('content')
@if($littlelink_name == '')
        <h2 class="mb-4"> 👋 Hi, stranger</h2>
        <h5>You do not have a Page URL set, yet you can change that on the <a href="{{ url('/studio/page') }}">Page section</a></h5>
	@else
        <h2 class="mb-4"> 👋 Hi, @<?= $littlelink_name ?></h2>
		@endif
        <p>
          Welcome to {{ config('app.name') }}!
        </p>

        <div class="mt-5 row">
          <h5 class="mb-4" title="all links"><span class="bi bi-share-fill"> {{ $siteLinks }} </span></h5>
          <h5 class="mb-4 ml-5" title="all clicks"><span class="bi bi-eye-fill"> {{ $siteClicks }} </span></h5>
          <h5 class="mb-4 ml-5" title="all Users"><span class="bi bi bi-person-fill"> {{ $userNumber }}</span></h5>
        </div>

        <div class="mt-5 row">
        <h5 class="mb-4"><span class="bi bi-link"> link: {{ $links }} </span></h5>
          <h5 class="mb-4 ml-5"><span class="bi bi-eye"> click: {{ $clicks }} </span></h5>
        </div>

@endsection
