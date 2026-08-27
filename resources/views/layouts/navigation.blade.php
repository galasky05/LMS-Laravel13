<nav x-data="{ open: false }" class="border-b border-[#E4DFD2] sticky top-0 z-20" style="background:#F6F4EF; font-family: 'Inter', sans-serif;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="shrink-0" style="font-family: 'Fraunces', serif;">
                    <span class="text-xl font-semibold text-[#17233F]">GLE <span class="text-[#2F6F62]">Academy</span></span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium border-b-2 transition-colors
                        {{ request()->routeIs('dashboard') ? 'border-[#F2B705] text-[#17233F]' : 'border-transparent text-[#4B5566] hover:text-[#17233F] hover:border-[#E4DFD2]' }}">
                        Dashboard
                    </a>

                    @auth
                        @if(auth()->user()->role === 'instructor')
                            <a href="{{ route('instructor.courses') }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium border-b-2 transition-colors
                                {{ request()->routeIs('instructor.courses') ? 'border-[#F2B705] text-[#17233F]' : 'border-transparent text-[#4B5566] hover:text-[#17233F] hover:border-[#E4DFD2]' }}">
                                Course Saya
                            </a>
                        @elseif(auth()->user()->role === 'student')
                            <a href="{{ route('student.catalog') }}"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium border-b-2 transition-colors
                                {{ request()->routeIs('student.catalog') ? 'border-[#F2B705] text-[#17233F]' : 'border-transparent text-[#4B5566] hover:text-[#17233F] hover:border-[#E4DFD2]' }}">
                                Katalog Course
                            </a>
                        @elseif(auth()->user()->role === 'admin')
    <a href="{{ route('admin.users') }}"
        class="inline-flex items-center px-3 py-2 text-sm font-medium border-b-2 transition-colors
        {{ request()->routeIs('admin.users') ? 'border-[#F2B705] text-[#17233F]' : 'border-transparent text-[#4B5566] hover:text-[#17233F] hover:border-[#E4DFD2]' }}">
        Kelola User
    </a>
    <a href="{{ route('admin.transactions') }}"
        class="inline-flex items-center px-3 py-2 text-sm font-medium border-b-2 transition-colors
        {{ request()->routeIs('admin.transactions') ? 'border-[#F2B705] text-[#17233F]' : 'border-transparent text-[#4B5566] hover:text-[#17233F] hover:border-[#E4DFD2]' }}">
        Transaksi
    </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-[#E4DFD2] text-sm font-medium rounded text-[#17233F] bg-white hover:bg-[#FFFDF9] focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <span class="ms-2 text-[10px] uppercase tracking-wide text-[#2F6F62]" style="font-family:'IBM Plex Mono', monospace;">{{ Auth::user()->role }}</span>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 text-[#8791A6]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#4B5566] hover:text-[#17233F] hover:bg-[#FFFDF9] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-[#E4DFD2]">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="block ps-3 pe-4 py-2 text-sm font-medium border-l-4
                {{ request()->routeIs('dashboard') ? 'border-[#F2B705] text-[#17233F] bg-[#FFFDF9]' : 'border-transparent text-[#4B5566]' }}">
                Dashboard
            </a>

            @auth
                @if(auth()->user()->role === 'instructor')
                    <a href="{{ route('instructor.courses') }}"
                        class="block ps-3 pe-4 py-2 text-sm font-medium border-l-4
                        {{ request()->routeIs('instructor.courses') ? 'border-[#F2B705] text-[#17233F] bg-[#FFFDF9]' : 'border-transparent text-[#4B5566]' }}">
                        Course Saya
                    </a>
                @elseif(auth()->user()->role === 'student')
                    <a href="{{ route('student.catalog') }}"
                        class="block ps-3 pe-4 py-2 text-sm font-medium border-l-4
                        {{ request()->routeIs('student.catalog') ? 'border-[#F2B705] text-[#17233F] bg-[#FFFDF9]' : 'border-transparent text-[#4B5566]' }}">
                        Katalog Course
                    </a>
               @elseif(auth()->user()->role === 'admin')
    <a href="{{ route('admin.users') }}"
        class="block ps-3 pe-4 py-2 text-sm font-medium border-l-4
        {{ request()->routeIs('admin.users') ? 'border-[#F2B705] text-[#17233F] bg-[#FFFDF9]' : 'border-transparent text-[#4B5566]' }}">
        Kelola User
    </a>
    <a href="{{ route('admin.transactions') }}"
        class="block ps-3 pe-4 py-2 text-sm font-medium border-l-4
        {{ request()->routeIs('admin.transactions') ? 'border-[#F2B705] text-[#17233F] bg-[#FFFDF9]' : 'border-transparent text-[#4B5566]' }}">
        Transaksi
    </a>
@endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-[#E4DFD2]">
            <div class="px-4">
                <div class="font-medium text-base text-[#17233F]">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-[#8791A6]">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>