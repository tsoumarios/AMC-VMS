
<div class="bg-white relative h-full min-h-screen">
      <div class="xl:py-2 pr-5" style="width:200px; position: sticky; top: 0;">
        <div class="hidden xl:block uppercase font-bold text-grey-darker text-xs px-4 py-2">
          ΜΕΝΟΥ
        </div>
        <!-- Navigation Links -->
        <div class="hidden sm:ml-5 sm:flex block xl:flex shadow-light xl:shadow-none py-6 xl:py-2 xl:px-4 border-transparent hover:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-6 w-6 text-grey-darker fill-current xl:mr-2"><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zM5.68 7.1A7.96 7.96 0 0 0 4.06 11H5a1 1 0 0 1 0 2h-.94a7.95 7.95 0 0 0 1.32 3.5A9.96 9.96 0 0 1 11 14.05V9a1 1 0 0 1 2 0v5.05a9.96 9.96 0 0 1 5.62 2.45 7.95 7.95 0 0 0 1.32-3.5H19a1 1 0 0 1 0-2h.94a7.96 7.96 0 0 0-1.62-3.9l-.66.66a1 1 0 1 1-1.42-1.42l.67-.66A7.96 7.96 0 0 0 13 4.06V5a1 1 0 0 1-2 0v-.94c-1.46.18-2.8.76-3.9 1.62l.66.66a1 1 0 0 1-1.42 1.42l-.66-.67zM6.71 18a7.97 7.97 0 0 0 10.58 0 7.97 7.97 0 0 0-10.58 0z" class="heroicon-ui"></path></svg>
            <x-jet-nav-link href="{{ route('visits') }}" :active="request()->routeIs('visits')">
                {{ __('Επισκέψεις') }}
            </x-jet-nav-link>
        </div>
        @if( auth()->user()->permissions->whereIn('pivot.permission_id', 6)->count() < 1 || auth()->user()->superuser === 1)
            <div class="hidden sm:ml-5 sm:flex block xl:flex shadow-light xl:shadow-none py-6 xl:py-2 xl:px-4 border-transparent hover:bg-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-6 w-6 text-grey-darker fill-current xl:mr-2"><path d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z" class="heroicon-ui"></path></svg>
                <x-jet-nav-link href="{{ route('employees') }}" :active="request()->routeIs('employees')">
                    {{ __('Υπάλληλοι') }}
                </x-jet-nav-link>
            </div>
            <div class="hidden sm:ml-5 sm:flex block xl:flex shadow-light xl:shadow-none py-6 xl:py-2 xl:px-4 border-transparent hover:bg-gray-200">
                <i class="fas fa-users py-1 pr-3"></i>
                <x-jet-nav-link href="{{ route('visitors') }}" :active="request()->routeIs('visitors')">
                    {{ __('Επισκέπτες') }}
                </x-jet-nav-link>
            </div>
            <div class="hidden sm:ml-5 sm:flex block xl:flex shadow-light xl:shadow-none py-6 xl:py-2 xl:px-4 border-transparent hover:bg-gray-200">
                <i class="far fa-building py-1 pl-1 pr-3" style="font-size: 18px;"></i>
                <x-jet-nav-link href="{{ route('departments') }}" :active="request()->routeIs('departments')">
                    {{ __('Τμήματα') }}
                </x-jet-nav-link>
            </div>
        @endif
    </div>
</div>
