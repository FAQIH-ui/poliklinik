<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Poliklinik' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body>

    <div class="app-wrapper flex overflow-hidden h-screen bg-gray-50">

    {{-- SIDEBAR --}}
    <div id="appSidebar" class="sidebar-fixed transition-all duration-300">
        @include('components.partials.sidebar')
    </div>

    {{-- OVERLAY (Hanya muncul di Mobile) --}}
    <div class="sidebar-overlay fixed inset-0 bg-black/50 z-40 hidden" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- MAIN CONTENT --}}
    <div class="main-content flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- HEADER --}}
        @include('components.partials.header')

        {{-- SCROLLABLE AREA --}}
        <div class="main-scroll flex-1 overflow-y-auto p-4 md:p-6">

            @if(session('success'))
                <div class="alert alert-success mb-4 rounded-xl shadow-sm bg-green-100 text-green-700 p-4">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error mb-4 rounded-xl shadow-sm bg-red-100 text-red-700 p-4">
                    <i class="fas fa-circle-xmark"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- ISI HALAMAN --}}
            <div class="content-body">
                {{ $slot }}
            </div>

            {{-- FOOTER (Berada di dalam scroll area atau di bawahnya) --}}
            @include('components.partials.footer')
        </div>

    </div>

</div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('appSidebar')
            const overlay = document.getElementById('sidebarOverlay')

            sidebar.classList.toggle('open')

            overlay.style.display =
                sidebar.classList.contains('open') 
                ? 'block' 
                : 'none'
        }

        function toggleFullscreen() {
            const icon = document.getElementById('fsIcon')

            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen()
                icon.className = 'fas fa-compress'
            } else {
                document.exitFullscreen()
                icon.className = 'fas fa-expand'
            }
        }
    </script>

    @stack('scripts')

</body>

</html>