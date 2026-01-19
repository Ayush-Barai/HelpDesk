<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HelpDesk Triage | Smart Support</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-slate-900 font-sans antialiased min-h-screen flex flex-col">
    
    @auth
        @include('layouts.navigation')
    @else
        <nav class="bg-white border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4 mx-auto max-w-7xl lg:px-8">
                <div class="flex items-center gap-2">
                    <div class="bg-indigo-600 p-1.5 rounded-md shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-slate-800">HelpDesk <span class="text-indigo-600">Triage</span></span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition-all shadow-sm">Get Started</a>
                    @endif
                </div>
            </div>
        </nav>
    @endauth

    <header class="bg-white flex-grow flex items-center">
        <div class="px-6 py-20 mx-auto max-w-7xl lg:py-32 lg:px-8 text-center">
            
            @guest
                <h1 class="text-5xl font-extrabold tracking-tight text-slate-900 sm:text-7xl">
                    Internal Support, <br><span class="text-indigo-600">Simplified.</span>
                </h1>
                <p class="mt-8 text-lg leading-8 text-slate-600 max-w-2xl mx-auto">
                    Report issues, track progress, and get back to work faster. Our smart triage system ensures your problems are solved by the right people.
                </p>
                <div class="mt-10 flex justify-center gap-x-6">
                    <a href="{{ route('register') }}" class="rounded-xl bg-indigo-600 px-8 py-4 text-lg font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-500 transition-all">Create Account</a>
                </div>
            @endguest

            @auth
                <div class="space-y-6">
                    @if(auth()->user()->role === 'agent')
                        <div class="inline-flex items-center px-4 py-1.5 text-sm font-bold text-indigo-700 bg-indigo-50 rounded-full ring-1 ring-inset ring-indigo-700/10 uppercase tracking-widest">
                            Agent Command Center
                        </div>
                        <h1 class="text-5xl font-extrabold tracking-tight text-slate-900 sm:text-7xl">
                            Ready to clear the <span class="text-indigo-600">Queue?</span>
                        </h1>
                        <p class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto">
                            Welcome back, <strong>{{ auth()->user()->name }}</strong>. There are tickets waiting for your expertise.
                        </p>
                        <div class="mt-10">
                            <a href="{{ route('tickets.index') }}" class="rounded-xl bg-slate-900 px-8 py-4 text-lg font-bold text-white shadow-xl hover:bg-slate-800 transition-all">Go to Ticket Queue</a>
                        </div>
                    @else
                        <div class="inline-flex items-center px-4 py-1.5 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-full ring-1 ring-inset ring-emerald-700/10 uppercase tracking-widest">
                            Support Portal
                        </div>
                        <h1 class="text-5xl font-extrabold tracking-tight text-slate-900 sm:text-7xl">
                            How can we <span class="text-indigo-600">help today?</span>
                        </h1>
                        <p class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto">
                            Hello <strong>{{ auth()->user()->name }}</strong>. You can check your existing issues or raise a new request below.
                        </p>
                        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                            <a href="{{ route('tickets.create') }}" class="rounded-xl bg-indigo-600 px-8 py-4 text-lg font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-500 transition-all">Raise New Ticket</a>
                            <a href="{{ route('tickets.index') }}" class="rounded-xl bg-white border border-gray-200 px-8 py-4 text-lg font-bold text-slate-700 hover:bg-gray-50 transition-all">View My Tickets</a>
                        </div>
                    @endif
                </div>
            @endauth
        </div>
    </header>

    <footer class="py-10 bg-white text-center border-t border-gray-100">
        <!-- <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">© 2026 HelpDesk Triage • Built with Laravel</p> -->
    </footer>

</body>
</html>