<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','Panel general') · NexoTask</title><link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="stylesheet" href="{{ asset('vendor/bootstrap.min.css') }}"><link rel="stylesheet" href="{{ asset('base.css') }}"><link rel="stylesheet" href="{{ asset('fresh.css') }}"></head>
<body class="app-body"><div class="mobile-scrim" id="scrim"></div><div class="app-shell">
<aside class="sidebar" id="sidebar">
 <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark">N</span><span class="brand-text">Nexo<span class="brand-accent">Task</span><small>GESTIÓN COLABORATIVA</small></span></a>
 <div class="side-label">ESPACIO DE TRABAJO</div>
 <nav class="side-nav" aria-label="Menú">
 @foreach([['dashboard','Panel general','◫'],['projects.index','Proyectos','▤'],['tasks.index','Tareas','☷'],['users.index','Equipo','◎'],['profile','Mi perfil','⚙']] as [$route,$label,$symbol])
 <a href="{{ route($route) }}" class="side-link {{ request()->routeIs($route) || ($route==='projects.index' && request()->routeIs('projects.*')) ? 'active' : '' }}"><span class="nav-symbol">{{ $symbol }}</span><span>{{ $label }}</span></a>
 @endforeach
 </nav>
 <div class="side-spacer"></div>
 <div class="side-team"><span class="team-mini-icon">G5</span><div><strong>Equipo 05</strong><span>Programación Web Avanzada</span></div></div>
 <div class="sidebar-profile"><span class="avatar avatar-side">{{ mb_substr(auth()->user()->nombre,0,1) }}</span><div class="side-user"><strong>{{ auth()->user()->nombre }}</strong><small>{{ auth()->user()->roleName() }}</small></div><form method="post" action="{{ route('logout') }}">@csrf<button class="icon-button side-logout" title="Cerrar sesión">↪</button></form></div>
</aside>
<main class="main-area"><header class="topbar"><button class="icon-button mobile-menu" id="menuToggle" type="button" >☰</button><div class="crumbs">ESPACIO DE TRABAJO <span>/</span> <strong>@yield('title','Panel general')</strong></div><div class="topbar-right"><span class="live-pill"><span class="live-dot"></span> Sistema activo</span><span class="top-avatar">{{ mb_substr(auth()->user()->nombre,0,1) }}</span></div></header>
<div class="page-content container-fluid"><section class="page-heading"><div><div class="page-eyebrow">NEXOTASK <span class="eyebrow-line"></span> EQUIPO 05</div><h1>@yield('title','Panel general')</h1><p>@yield('subtitle','Seguimiento de proyectos y tareas')</p></div>@yield('heading_action')</section>
@if(session('ok'))<div class="toast-flash success">✓ {{ session('ok') }}</div>@endif
@if($errors->any())<div class="toast-flash error" role="alert"><strong>Revisa los datos:</strong> {{ $errors->first() }}</div>@endif
@yield('content')
</div><footer class="main-footer"><span>© {{ date('Y') }} NexoTask · Grupo 05</span><span>Jorge Toapanta · Wilmer Adriano · Jhoely Dávila · Jhonatan Chávez · Wilmer López</span></footer></main></div>
<script src="{{ asset('vendor/bootstrap.bundle.min.js') }}" defer></script><script src="{{ asset('assets.js') }}" defer></script></body></html>
