<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Επισκέπτες') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex">
            <div class="bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg ">
                <svg class="m-auto my-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-6 w-6 text-grey-darker fill-current xl:mr-2"><path d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z" class="heroicon-ui"></path></svg>
                <p class="card-title text-gray-500">
                    Σύνολο επισκεπτών
                </p>
                <p class="card-number text-xl text-center">{{ $visitors->count() }}</p>
            </div>
            
        </div>

        <div class="mt-10 bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg flex flex-col">
            <p class="text-gray-400 py-3 pl-5 text-3xl">
                Επισκέπτες
            </p>
            @if($visitors->count() > 0)
            <table class="table-auto TFtable">
                <thead>
                    <tr>
                        <th class="">ID</th>
                        <th class="">Ονοματεπώνυμο</th>
                        <th class="">Τηλέφωνο</th>
                        <th class="">E-mail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visitors as $visitor)
                        <tr>
                            <td>{{$visitor->id}}</td>
                            <td>{{$visitor->fullname}}</td>
                            <td>{{$visitor->phone}}</td>
                            <td>{{$visitor->email}}</td>
                        </tr>
                    @endforeach
                    @else    
                    <p class="text-gray-300 py-3 pl-5 text-5xl">
                        Δεν έχετε επισκέπτες ακόμη.
                    </p>
                </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
