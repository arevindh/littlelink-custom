<!DOCTYPE html>
@include('layouts.lang')
<head>
  <meta charset="utf-8">

@include('layouts.analytics')

  @if(config('advanced-config.littlelink_title') != '' and env('HOME_URL') === '')
  <title>{{ $userinfo->name }} {{ config('advanced-config.littlelink_title') }}</title>
  @elseif(env('CUSTOM_META_TAGS') == 'true' and config('advanced-config.title') != '')
  <title>{{ config('advanced-config.title') }}</title>
  @elseif(env('HOME_URL') != '')
  <title>{{ $userinfo->name }}</title>
  @else
  <title>{{ $userinfo->name }} 🔗 {{ config('app.name') }} </title>
  @endif

@if(env('CUSTOM_META_TAGS') == 'true')
  @include('layouts.meta') 
@else
  <meta name="description" content="{{ $userinfo->littlelink_description }}">
  <meta name="author" content="{{ $userinfo->name }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
@endif
  
<!--#### BEGIN Meta Tags social media preview images  ####-->
  <!-- This shows a preview for title, description and avatar image of users profiles if shared on social media sites -->

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{ url('') }}/@littlelink_name">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $userinfo->name }}">
    <meta property="og:description" content="{{ $userinfo->littlelink_description }}">
    @if(file_exists(base_path("img/$littlelink_name" . ".png" )))
    <meta property="og:image" content="{{ asset("img/$littlelink_name" . ".png") }}">
    @else
    <meta property="og:image" content="{{ asset('littlelink/images/logo.svg') }}">
    @endif
    
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="{{ url('') }}/@littlelink_name">
    <meta property="twitter:url" content="{{ url('') }}/@littlelink_name">
    <meta name="twitter:title" content="{{ $userinfo->littlelink_name }}">
    <meta name="twitter:description" content="{{ $userinfo->littlelink_description }}">
    @if(file_exists(base_path("img/$littlelink_name" . ".png" )))
    <meta name="twitter:image" content="{{ asset("img/$littlelink_name" . ".png") }}">
    @else
    <meta name="twitter:image" content="{{ asset('littlelink/images/logo.svg') }}">
    @endif

<!--#### END Meta Tags social media preview images  ####-->

  <!-- Custom icons font-awesome -->
  <script src="https://kit.fontawesome.com/c4a5e06183.js" crossorigin="anonymous"></script>

  <link href="//fonts.googleapis.com/css?family=Open+Sans:400,600,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('littlelink/css/normalize.css') }}">
  <link rel="stylesheet" href="{{ asset('littlelink/css/animate.css') }}">
  @if(file_exists(base_path("littlelink/images/avatar.png" )))
  <link rel="icon" type="image/png" href="{{ asset('littlelink/images/avatar.png') }}">
  @else
  <link rel="icon" type="image/svg+xml" href="{{ asset('littlelink/images/logo.svg') }}">
  @endif

@foreach($information as $info)
@if($info->theme != '' and $info->theme != 'default')

  <!-- LittleLink Custom Theme: "{{$info->theme}}" -->

  <!-- Theme details: -->
  <meta name="designer" href="{{ url('') . "/theme/@" . $littlelink_name}}" content="{{ url('') . "/theme/@" . $littlelink_name}}">

  <link rel="stylesheet" href="themes/{{$info->theme}}/share.button.css">
  <link rel="stylesheet" href="themes/{{$info->theme}}/brands.css">
  <link rel="stylesheet" href="themes/{{$info->theme}}/skeleton-auto.css">
@if(file_exists(base_path('themes/' . $info->theme . '/animations.css')))
  <link rel="stylesheet" href="<?php echo asset('themes/' . $info->theme . '/animations.css') ?>">
@else
  <link rel="stylesheet" href="{{ asset('littlelink/css/animations.css') }}">
@endif

@else
  <?php // override dark/light mode if override cookie is set
  $color_scheme_override = isset($_COOKIE["color_scheme_override"]) ? $_COOKIE["color_scheme_override"] : false; ?>
  <link rel="stylesheet" href="{{ asset('littlelink/css/share.button.css') }}">
  <link rel="stylesheet" href="{{ asset('littlelink/css/animations.css') }}">
  <link rel="stylesheet" href="{{ asset('littlelink/css/brands.css') }}">
  @if ($color_scheme_override == 'dark')
  <link rel="stylesheet" href="{{ asset('littlelink/css/skeleton-dark.css') }}">
  @elseif ($color_scheme_override == 'light')
  <link rel="stylesheet" href="{{ asset('littlelink/css/skeleton-light.css') }}">
  @elseif (config('advanced-config.theme') == 'dark')
  <link rel="stylesheet" href="{{ asset('littlelink/css/skeleton-dark.css') }}">
  @elseif (config('advanced-config.theme') == 'light')
  <link rel="stylesheet" href="{{ asset('littlelink/css/skeleton-light.css') }}">
  @else
  <link rel="stylesheet" href="{{ asset('littlelink/css/skeleton-auto.css') }}">
  @endif
@endif
</head>
<body>

@if($info->theme != '' and $info->theme != 'default')
    <!-- Enables parallax background animations -->
    <div class="background-container">
    <section class="parallax-background">
      <div id="object1" class="object1"></div>
      <div id="object2" class="object2"></div>
      <div id="object3" class="object3"></div>
      <div id="object4" class="object4"></div>
      <div id="object5" class="object5"></div>
      <div id="object6" class="object6"></div>
      <div id="object7" class="object7"></div>
      <div id="object8" class="object8"></div>
      <div id="object9" class="object9"></div>
      <div id="object10" class="object10"></div>
      <div id="object11" class="object11"></div>
      <div id="object12" class="object12"></div>
    </section>
    </div>
    <!-- End of parallax background animations -->
@endif

<?php ////begin share button//// ?>

@if(config('advanced-config.display_share_button') != '')

   @if(config('advanced-config.display_share_button') == 'false')
   <?php $ShowShrBtn = 'false'; ?>
   @elseif(config('advanced-config.display_share_button') == 'user')
       @if($littlelink_names = Auth::user()->littlelink_name)
       <?php $ShowShrBtn = 'true'; ?>
       @else
       <?php $ShowShrBtn = 'false'; ?>
       @endif
   @else
   <?php $ShowShrBtn = 'true'; ?>
   @endif

@else
<?php $ShowShrBtn = 'true'; ?>
@endif

@if($ShowShrBtn == 'true')
<?php 
//Get browser type
$arr_browsers = ["Opera", "Edg", "Chrome", "Safari", "Firefox", "MSIE", "Trident"];
 
$agent = $_SERVER['HTTP_USER_AGENT'];
 
$user_browser = '';
foreach ($arr_browsers as $browser) {
    if (strpos($agent, $browser) !== false) {
        $user_browser = $browser;
        break;
    }   
}
  
switch ($user_browser) {
    case 'MSIE':
        $user_browser = 'Internet Explorer';
        break;
  
    case 'Trident':
        $user_browser = 'Internet Explorer';
        break;
  
    case 'Edg':
        $user_browser = 'Microsoft Edge';
        break;
}

function get_operating_system() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $operating_system = 'NULL';

    //get operating-system type
        if (preg_match('/iphone/i', $u_agent)) {
        $operating_system = 'mobile';
    } elseif (preg_match('/ipod/i', $u_agent)) {
        $operating_system = 'mobile';
    } elseif (preg_match('/ipad/i', $u_agent)) {
        $operating_system = 'mobile';
    } elseif (preg_match('/android/i', $u_agent)) {
        $operating_system = 'mobile';
    } elseif (preg_match('/blackberry/i', $u_agent)) {
        $operating_system = 'mobile';
    } elseif (preg_match('/webos/i', $u_agent)) {
        $operating_system = 'mobile';
    }
    
    return $operating_system;
}
?>

@if($user_browser === 'Chrome' or get_operating_system() == 'mobile')
<script src="{{ asset('littlelink/js/jquery.min.js') }}"></script>
<div align="right" class="sharediv"><div><span class="sharebutton button-hover icon-hover" id='share-share-button'><i style="color: black;" class="fa-solid fa-share sharebutton-img share-icon hvr-icon"></i><span class="sharebutton-mb">Share</span></span></div></div>
<span class="copy-icon" role="button">
</span>
@else
<span class="copy-icon" role="button">
<div onclick="alert('URL has been copied to your clipboard!')" align="right" class="sharediv"><div><a class="sharebutton button-hover icon-hover"><img alt="share-icon" class="sharebutton-img share-icon hvr-icon" src="{{ asset('\/littlelink/icons\/')}}share.svg"><span class="sharebutton-mb">Share</span></a></div></div>
</span>
@endif
<script  src="{{ asset('littlelink/js/share.button.js') }}"></script>

@endif
<?php ////end share button//// ?>

  <div class="container">
    <div class="row">
      <div class="column" style="margin-top: 5%">
        <!-- Your Image Here -->
          @if(file_exists(base_path("img/$littlelink_name" . ".png" )))
          <img alt="avatar" class="rounded-avatar fadein" src="{{ asset("img/$littlelink_name" . ".png") }}" width="128px" height="128px" style="object-fit: cover;">
          @elseif(file_exists(base_path("littlelink/images/avatar.png" )))
          <img alt="avatar" class="rounded-avatar fadein" src="{{ asset('littlelink/images/avatar.png') }}" srcset="{{ asset('littlelink/images/avatar@2x.png 2x') }}" width="128px" height="128px" style="object-fit: cover;">
          @else
          <img alt="avatar" class="rounded-avatar fadein" src="{{ asset('littlelink/images/logo.svg') }}" srcset="{{ asset('littlelink/images/avatar@2x.png 2x') }}" width="128px" height="128px" style="object-fit: cover;">
          @endif

        <!-- Your Name -->
        <h1 class="fadein">{{ $info->name }}</h1>

        <!-- Short Bio -->
        <center><p style="width: 50%; min-width: 300px;" class="fadein">@if(env('ALLOW_USER_HTML') === true){!! $info->littlelink_description !!}@else{{ $info->littlelink_description }}@endif</p></center>
        
        @endforeach		
        <!-- Buttons -->
<?php $initial=1; // <-- Effectively sets the initial loading time of the buttons. This value should be left at 1. ?>
        @foreach($links as $link)
         @php $linkName = str_replace('default ','',$link->name) @endphp
         @if($link->button_id === 0)
         <div style="--delay: {{ $initial++ }}s" class="button-entrance"><a class="button button-title button button-hover icon-hover" rel="noopener noreferrer nofollow" href="{{ route('clickNumber') . '/' . $link->id . '/' . $link->link}}" target="_blank">{{ $link->title }}</a></div>
         @elseif($link->name === "phone")
         <div style="--delay: {{ $initial++ }}s" class="button-entrance"><a class="button button-default button button-hover icon-hover" rel="noopener noreferrer nofollow" href="{{ $link->link }}"><img alt="button-icon" class="icon hvr-icon" src="{{ asset('\/littlelink/icons\/')}}phone.svg"></i>{{ $link->title }}</a></div>
         @elseif($link->name === "custom" and $link->custom_css === "" or $link->custom_css === "NULL")
         <div style="--delay: {{ $initial++ }}s" class="button-entrance"><a class="button button-{{ $link->name }} button button-hover icon-hover" rel="noopener noreferrer nofollow" href="{{ route('clickNumber') . '/' . $link->id . '/' . $link->link}}" target="_blank"><i style="color: {{$link->custom_icon}}" class="icon hvr-icon fa {{$link->custom_icon}}"></i>{{ $link->title }}</a></div>
         @elseif($link->name === "custom" and $link->custom_css != "")
         <div style="--delay: {{ $initial++ }}s" class="button-entrance"><a class="button button-hover icon-hover" style="{{ $link->custom_css }}" rel="noopener noreferrer nofollow" href="{{ route('clickNumber') . '/' . $link->id . '/' . $link->link}}" target="_blank"><i style="color: {{$link->custom_icon}}" class="icon hvr-icon fa {{$link->custom_icon}}"></i>{{ $link->title }}</a></div>
         @elseif($link->name === "buy me a coffee")
         <div style="--delay: {{ $initial++ }}s" class="button-entrance"><a class="button button-coffee button button-hover icon-hover" rel="noopener noreferrer nofollow" href="{{ route('clickNumber') . '/' . $link->id . '/' . $link->link}}" target="_blank"><img alt="button-icon" class="icon hvr-icon" src="{{ asset('\/littlelink/icons\/')}}coffee.svg">Buy me a Coffee</a></div>
         @elseif($link->name === "custom_website"and $link->custom_css === "" or $link->custom_css === "NULL")
         <div style="--delay: {{ $initial++ }}s" class="button-entrance"><a class="button button-custom_website button button-hover icon-hover" rel="noopener noreferrer nofollow" href="{{ route('clickNumber') . '/' . $link->id . '/' . $link->link}}" target="_blank"><img alt="button-icon" class="icon hvr-icon" src="http://www.google.com/s2/favicons?domain={{$link->link}}">{{ $link->title }}</a></div>
         @elseif($link->name === "custom_website" and $link->custom_css != "")
         <div style="--delay: {{ $initial++ }}s" class="button-entrance"><a class="button button-hover icon-hover" style="{{ $link->custom_css }}" rel="noopener noreferrer nofollow" href="{{ route('clickNumber') . '/' . $link->id . '/' . $link->link}}" target="_blank"><img alt="button-icon" class="icon hvr-icon" src="http://www.google.com/s2/favicons?domain={{$link->link}}">{{ $link->title }}</a></div>
         @elseif($link->name === "space")
         <?php 
          if (is_numeric($link->title) and $link->title < 10)
          echo str_repeat("<br>",$link->title);
          elseif (is_numeric($link->title) and $link->title >= 10)
          echo str_repeat("<br>",10);
          else
          echo "<br><br><br>"
          ?>
         @elseif($link->name === "heading")
         <h2>{{ $link->title }}</h2>
         @else
         <div style="--delay: {{ $initial++ }}s" class="button-entrance"><a class="button button-{{ $link->name }} button button-hover icon-hover" rel="noopener noreferrer nofollow" href="{{ route('clickNumber') . '/' . $link->id . '/' . $link->link}}" target="_blank"><img alt="button-icon" class="icon hvr-icon" src="{{ asset('\/littlelink/icons\/') . $linkName }}.svg">{{ ucfirst($linkName) }}</a></div>
         @endif
        @endforeach

        @include('layouts.footer')
          
      </div>
    </div>
  </div>
</body>
</html>
