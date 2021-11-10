<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg ">
            <svg class="m-auto my-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-6 w-6 text-grey-darker fill-current xl:mr-2"><path d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z" class="heroicon-ui"></path></svg>
            <p class="card-title text-gray-500">
                Σύνολο υπαλλήλων
            </p>
            <p class="card-number text-xl text-center">{{$employees->count()}}</p>
        </div>
        
    </div>
    @if( (auth()->user()->permissions->whereIn('pivot.permission_id', 6)->count() < 1 && auth()->user()->permissions->whereIn('pivot.permission_id', 4)->count() > 0) || auth()->user()->permissions->whereIn('pivot.permission_id', 4)->count() > 0 || auth()->user()->superuser === 1)
        <x-jet-button wire:click="ShowModal" class="destroy-button ml-4">
            <i class="far fa-edit"></i>{{ __('Νεο +') }}
        </x-jet-button>
    @endif
    <div class="mt-10 bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg flex flex-col">
        <p class="text-gray-400 py-3 pl-5 text-3xl">
            Υπάλληλοι
        </p>
        @if($employees->count() > 0)
        <table class="table-auto TFtable">
            <thead>
                <tr>
                    <th class="">ID</th>
                    <th class="">Ονοματεπώνυμο</th>
                    <th class="">Τηλέφωνο</th>
                    <th class="">E-mail</th>
                    <th class="">Τμήμα</th>
                    @if( auth()->user()->permissions->whereIn('pivot.permission_id', 4)->count() > 0 || 
                        auth()->user()->permissions->whereIn('pivot.permission_id', 3)->count() > 0 || 
                        auth()->user()->permissions->whereIn('pivot.permission_id', 2)->count() > 0 ||
                        auth()->user()->superuser === 1
                    )
                    <th class="">Ενέργειες</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{$employee->id}}</td>
                            <td>{{$employee->name}}</td>
                            <td>{{$employee->phone}}</td>
                            <td>{{$employee->email}}</td>
                            @if($employee->department()->count() > 0)
                            <td>{{ $employee->department()->pluck('name')[0] }}</td>
                            @else
                            <td>-</td>
                            @endif
                            @if( auth()->user()->permissions->whereIn('pivot.permission_id', 4)->count() > 0 || 
                                auth()->user()->permissions->whereIn('pivot.permission_id', 3)->count() > 0 ||
                                auth()->user()->permissions->whereIn('pivot.permission_id', 2)->count() > 0 ||
                                auth()->user()->superuser === 1
                                ) 
                            <td>
                               
                                @if( auth()->user()->permissions->whereIn('pivot.permission_id', 4)->count() > 0 || auth()->user()->superuser === 1)
                                    <i class="fas fa-user-lock has-tooltip" wire:click="ShowModalPerm({{ $employee->id }})"><span class='tooltip'>Δικαιώματα</span></i>
                                @endif

                                @if( auth()->user()->permissions->whereIn('pivot.permission_id', 2)->count() > 0 || auth()->user()->superuser === 1)
                                    <i class="far fa-edit has-tooltip" wire:click="ShowModalEdit({{ $employee->id }})"><span class='tooltip'>Επεξεργσία</span></i>
                                @endif

                                @if( auth()->user()->permissions->whereIn('pivot.permission_id', 3)->count() > 0 || auth()->user()->superuser === 1)
                                    <i class="fas fa-trash-alt text-red-600 has-tooltip" wire:click="ShowModalDelete({{ $employee->id }})"><span class='tooltip'>Διαγραφή</span></i>
                                @endif
                            </td>
                            @endif
                        </tr>
                    @endforeach
                    @else    
                    <p class="text-gray-300 py-3 pl-5 text-5xl">
                        Δεν έχετε δηλώσει υπαλλήλους ακόμη.
                    </p>
            </tbody>
            </table>
        @endif
    </div>
    <div class="pagination">
        @if($employees->count() > 0)
            {{ $employees->links() }}    
        @endif
    </div>

    <!-- Modal form -->
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Δημιουργία νέου χρήστη') }}
        </x-slot>

        <x-slot name="content">

        </x-slot>

        <x-jet-validation-errors class="mb-4" />    

        
        <x-slot name="footer">
            <form wire:submit.prevent="create">
                <div class="mt-4">
                    <x-jet-label class="text-left" for="name" value="{{ __('Ονοματεπώνυμο') }}" />
                    <x-jet-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
                @error('name') <span class="text-red-400 error">{{ $message }}</span> @enderror
                <div class="mt-4">
                    <x-jet-label class="text-left" for="phone" value="{{ __('Αριθμός τηλεφώνου') }}" />
                    <x-jet-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autofocus autocomplete="phone" />
                </div>
                @error('phone') <span class="text-red-400 error">{{ $message }}</span> @enderror
                <div class="mt-4 flex flex-col">
                    <x-jet-label class="text-left" for="department_id" value="{{ __('Τμήμα') }}" />
                    <select wire:model="department_id"  name="department_id" id="department_id">
                        <option value="">Επιλέξτε Τμήμα</option>
                        @if($departments->count() > 0)
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        @else 
                                <option value="Δεν υπάρχει τμήμα">Δεν υπάρχει τμήμα</option>
                        @endif
                    </select>
                </div>
                @error('department_id') <span class="text-red-400 error">{{ $message }}</span> @enderror
                <div class="mt-4">
                    <x-jet-label class="text-left" for="email" value="{{ __('Διεύθυνση Email') }}" />
                    <x-jet-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>
                @error('email') <span class="text-red-400 error">{{ $message }}</span> @enderror

                <div class="mt-4">
                    <x-jet-label class="text-left" for="password" value="{{ __('Κωδικός πρόσβασης') }}" />
                    <x-jet-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>
                
                <div class="mt-4">
                    <x-jet-label class="text-left" for="password_confirmation" value="{{ __('Επαλήθευση κωδικού πρόσβασης') }}" />
                    <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
                @error('password') <span class="text-red-400 error">{{ $message }}</span> @enderror

                <input type="checkbox" wire:model="superuser" class="hidden" name="superuser">                         
    
                <x-jet-secondary-button class="mt-3" wire:click="create" wire:loading.attr="enabled">
                    {{ __('Δημιουργια') }}
                </x-jet-secondary-button>

                <x-jet-secondary-button class="mt-3" wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                    {{ __('Ακυρο') }}
                </x-jet-secondary-button>
                </form>
        </x-slot>
    </x-jet-dialog-modal>


    <!-- Modal permission form -->
    <x-jet-dialog-modal wire:model="modalPermVisible">
        <x-slot name="title">
            {{ __('Διαχείρηση δικαιωμάτων χρήστη') }}
            {{$user_name}}
        </x-slot>

        <x-slot name="content">

        </x-slot>

        <x-jet-validation-errors class="mb-4" />    

        
        <x-slot name="footer">
        
                <div class="perm-wr">
                   @if($permissions->count() > 0)
                    @foreach($permissions as $permission)
                            <div class="mt-4 flex">
                                <x-jet-label class="text-left" for="{{$permission->name}}" value="{{$permission->name}}" />
                                    @if($user_id !== null)
                                        @if($employees->find($user_id)->permissions()->where('permission_id', $permission->id)->count())
                                            <i class="far text-green-500 fa-check-square" wire:click="removePermission({{$permission->id}})"></i>
                                            @else
                                            <i class="far fa-square" wire:click="givePermission({{$permission->id}})"></i>
                                        @endif
                                    @endif
                            </div>                                        
                        @endforeach
                   @endif
                </div>

                <x-jet-secondary-button class="mt-3" wire:click="$toggle('modalPermVisible')" wire:loading.attr="enabled">
                    {{ __('Αποθήκευση') }}
                </x-jet-secondary-button>
           
        </x-slot>
    </x-jet-dialog-modal>
    
    <!-- Modal edit form -->
    <x-jet-dialog-modal wire:model="modalEditVisible">
        <x-slot name="title">
            {{ __('Επεξεργασία πληροφοριών χρήστη') }}
            {{$user_name}}
        </x-slot>

        <x-slot name="content">

        </x-slot>

        <x-jet-validation-errors class="mb-4" />    

        
        <x-slot name="footer">
            <form wire:submit.prevent="update">
                <div class="mt-4">
                    <x-jet-label class="text-left" for="name" value="{{ __('Ονοματεπώνυμο') }}" />
                    <x-jet-input wire:model="old_name" id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
             
                <div class="mt-4">
                    <x-jet-label class="text-left" for="phone" value="{{ __('Αριθμός τηλεφώνου') }}" />
                    <x-jet-input wire:model="old_phone" id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autofocus autocomplete="phone" />
                </div>

                <div class="mt-4 flex flex-col">
                    <x-jet-label class="text-left" for="department_id" value="{{ __('Τμήμα') }}" />
                    <select wire:model="old_department_id"  name="department_id" id="department_id">
                        <option value="">Επιλέξτε Τμήμα</option>
                        @if($departments->count() > 0)
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        @else 
                                <option value="Δεν υπάρχει τμήμα">Δεν υπάρχει τμήμα</option>
                        @endif
                    </select>
                </div>
        
                <div class="mt-4">
                    <x-jet-label class="text-left" for="email" value="{{ __('Διεύθυνση Email') }}" />
                    <x-jet-input wire:model="old_email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>

                <x-jet-secondary-button class="mt-3" wire:click="update" wire:loading.attr="enabled">
                    {{ __('Αποθήκευση') }}
                </x-jet-secondary-button>
                <x-jet-secondary-button class="mt-3" wire:click="$toggle('modalEditVisible')" wire:loading.attr="enabled">
                    {{ __('Άκυρο') }}
                </x-jet-secondary-button>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Modal deletre form -->
    <x-jet-dialog-modal wire:model="modalDeleteVisible">
        <x-slot name="title">
            {{ __('Διαγραφή υπαλλήλου') }}
            {{$user_name}}
        </x-slot>

        <x-slot name="content">

        </x-slot>

        <x-jet-validation-errors class="mb-4" />    

        
        <x-slot name="footer">
            <form wire:submit.prevent="delete">
                <div class="mt-1 text-left bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg flex flex-col">
                    <p>Θέλετε να διαγράψετε τον χρήστη {{$user_name}};</p>
                </div>
                <x-jet-secondary-button class="mt-3" wire:click="delete" wire:loading.attr="enabled">
                    {{ __('Διαγραφη') }}
                </x-jet-secondary-button>
                <x-jet-secondary-button class="mt-3" wire:click="$toggle('modalDeleteVisible')" wire:loading.attr="enabled">
                    {{ __('Ακυρο') }}
                </x-jet-secondary-button>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <div>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>
</div>
