<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg ">
            <div class="icon-wr">
                <i class="far fa-building my-3"></i>
            </div>

            <p class="card-title text-gray-500">
                Σύνολο τμημάτων
            </p>
            <p class="card-number text-xl text-center">{{ $departments->count() }}</p>
        </div>
        
    </div>
    @if( (auth()->user()->permissions->whereIn('pivot.permission_id', 6)->count() < 1 && auth()->user()->permissions->whereIn('pivot.permission_id', 1)->count() > 0) || auth()->user()->superuser === 1 || auth()->user()->permissions->whereIn('pivot.permission_id', 1)->count() > 0)
        <x-jet-button wire:click="ShowModal" class="destroy-button ml-4">
            <i class="far fa-edit"></i>{{ __('Νεο +') }}
        </x-jet-button>    
    @endif
    <div class="mt-10 bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg flex flex-col">
        <p class="text-gray-400 py-3 pl-5 text-3xl">
            Τμήματα
        </p>
        @if($departments->count() > 0)
        <table class="table-auto TFtable">
            <thead>
                <tr>
                    <th class="">ID</th>
                    <th class="">Όνομα τμήματος</th>
                    <th class="">Ενέργειες</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td>{{$department->id}}</td>
                        <td>{{$department->name}}</td>
                        <td>
                            @if( auth()->user()->permissions->whereIn('pivot.permission_id', 2)->count() > 0 || auth()->user()->superuser === 1)
                                <i class="far fa-edit has-tooltip" wire:click="ShowModalEdit({{ $department->id }})"><span class='tooltip'>Επεξεργσία</span></i>
                            @endif

                            @if( auth()->user()->permissions->whereIn('pivot.permission_id', 3)->count() > 0 || auth()->user()->superuser === 1)
                                <i class="fas fa-trash-alt text-red-600 has-tooltip" wire:click="ShowModalDelete({{ $department->id }})"><span class='tooltip'>Διαγραφή</span></i>
                            @endif
                        </td>
                 
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

    <!-- Modal form -->
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Δημιουργία τμήματος') }}
        </x-slot>

        <x-slot name="content">

        </x-slot>

        <x-jet-validation-errors class="mb-4" />    

        
        <x-slot name="footer">
            <div>
            
                <div class="mt-4">
                    <x-jet-label class="text-left" for="name" value="{{ __('Ονομα τμήματος') }}" />
                    <x-jet-input id="name" wire:model="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>           
                @error('name') <span class="text-red-400 error">{{ $message }}</span> @enderror                       
    
                <x-jet-secondary-button class="mt-3" wire:click="create" wire:loading.attr="enabled">
                    {{ __('Δημιουργια') }}
                </x-jet-secondary-button>

                <x-jet-secondary-button class="mt-3" wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                    {{ __('Ακυρο') }}
                </x-jet-secondary-button>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

     <!-- Modal edit form -->
     <x-jet-dialog-modal wire:model="modalEditVisible">
        <x-slot name="title">
            {{ __('Επεξεργασία τμήματος') }}
            <span class="pl-1">{{$old_name}}</span>
            
        </x-slot>

        <x-slot name="content">

        </x-slot>

        <x-jet-validation-errors class="mb-4" />    

        
        <x-slot name="footer">
            <form wire:submit.prevent="update">
        
            <!-- Create visitor -->
                <div class="mt-4">
                    <x-jet-label class="text-left" for="name" value="{{ __('Όνομα τμήματος') }}" />
                    <x-jet-input wire:model="old_name" id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
                
                
                <x-jet-secondary-button class="mt-3" wire:click="update" wire:loading.attr="enabled">
                    {{ __('Ενημέρωση') }}
                </x-jet-secondary-button>
                
                <x-jet-secondary-button class="mt-3" wire:click="$toggle('modalEditVisible')" wire:loading.attr="disabled">
                    {{ __('Ακυρο') }}
                </x-jet-secondary-button>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    
    <!-- Modal delete form -->
    <x-jet-dialog-modal wire:model="modalDeleteVisible">
        <x-slot name="title">
            {{ __('Διαγραφή τμήματος') }} <span class="pl-1">{{$name}}</span> 
            
        </x-slot>
        
        <x-slot name="content">
            
            </x-slot>
            
            <x-jet-validation-errors class="mb-4" />    
            
            
        <x-slot name="footer">
            <form wire:submit.prevent="delete">
                <div class="mt-1 text-left bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg flex flex-col">
                    <p>Θέλετε να διαγράψετε το τμήμα;</p>
                </div>
                <x-jet-secondary-button class="mt-3" wire:click="delete" wire:loading.attr="enabled">
                    {{ __('Διαγραφή') }}
                </x-jet-secondary-button>
                <x-jet-secondary-button class="mt-3" wire:click="$toggle('modalDeleteVisible')" wire:loading.attr="enabled">
                    {{ __('Άκυρο') }}
                </x-jet-secondary-button>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

</div>
