<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex">
        <div class="bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg ">
            <svg class="m-auto my-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="h-6 w-6 text-grey-darker fill-current xl:mr-2"><path d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z" class="heroicon-ui"></path></svg>
            <p class="card-title text-gray-500">
                Σύνολο υπαλλήλων
            </p>
            <p class="card-number text-xl text-center">{{$users->count()}}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg">
            <div class="icon-wr">
                <i class="fas fa-users my-3"></i>
            </div>

            <p class="card-title text-gray-500">
                Σύνολο επισκεπτών
            </p>
            <p class="card-number text-xl text-center">{{$visits->count()}}</p>
        </div>
    </div>
    @if( (auth()->user()->permissions->whereIn('pivot.permission_id', 6)->count() < 1 && auth()->user()->permissions->whereIn('pivot.permission_id', 1)->count() > 0) || auth()->user()->permissions->whereIn('pivot.permission_id', 1)->count() > 0 || auth()->user()->superuser === 1)
        <x-jet-button wire:click="ShowModalForm" class="destroy-button ml-4">
            <i class="far fa-edit"></i>{{ __('Νεο +') }}
        </x-jet-button>
    @endif
    <div class="mt-10 bg-white overflow-scroll shadow-xl mx-5 p-5 sm:rounded-lg">
        <div class="flex mb-3">
            <p class="text-gray-400 py-3 pl-5 text-3xl">
                Επισκέψεις
            </p>
            <div class="flex ml-10">
                <div class="flex flex-col my-auto">
                    <input type="date" style="border-radius: 6px;" class="my-auto block mt-1 w-full" name="date" id="date" wire:model="date_search">    
                
                </div>
                <a class="px-6 my-auto refresh" href="javascript:history.go(0)"><svg xmlns="http://www.w3.org/2000/svg" class="h-10 m-auto reloader w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                </a>
                <a class="px-6 my-auto" href="{{ route('pdf', $date_search) }}">
                    <i class="fas fa-print"></i>           
                </a>
            </div>
        </div>
        @if($visits->count() > 0)
            <table class="table-auto TFtable">
                <thead>
                    <tr>
                        <th class="">Όνομα</th>
                        <th class="">Εταιρεία</th>
                        <th class="">Εσωτερικός χώρος επίσκεψης</th>
                        <th class="">Τηλέφωνο</th>
                        <th class="">E-mail</th>
                        <th class="">Εσωτερικός Αποδέκτης</th>
                        <th class="">Ημερομηνία και διάρκεια</th>
                        <th class="">Κατάσταση έγκρισης από HR</th>
                        <th class="">Κατάσταση Επίσκεψης</th>
                        <th class="">Αναγκαιότητα Επίσκεψης</th>
                        <th class="">Λόγος επίσκεψης</th>
                        @if(
                            auth()->user()->permissions->whereIn('pivot.permission_id', 3)->count() > 0 ||
                            auth()->user()->permissions->whereIn('pivot.permission_id', 2)->count() > 0 ||
                            auth()->user()->superuser === 1
                            ) 
                        <th class="">Ενέργειες</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                    @foreach($visits as $visit)
                        <tr>
                            <td>{{ $visit->visitor->fullname }}</td>
                            <td>{{ $visit->visitor->company }}</td>
                            @if($visit->department)
                            <td>{{ $visit->department->name }}</td>
                            @else
                            <td>-</td>
                            @endif
                            <td>{{ $visit->visitor->phone }}</td>
                            <td>{{ $visit->visitor->email }}</td>
                            @if($users->where('id', $visit->recipient_id)->count() > 0)
                            <td>{{ $users->where('id', $visit->recipient_id)->pluck('name')[0] }}</td>
                            @else
                            <td>Δεν έχει οριστεί παραλήπτης ή ο υπάλληλος έχει διαγραφεί.</td>
                            @endif
                            <td>{{ $visit->date }}</br><small>{{ $visit->checkin }}-{{ $visit->checkout }}</small></td>

                            @if( auth()->user()->permissions->whereIn('pivot.permission_id', 6)->count() > 0 )
                                @if($visit->hr_approval)
                                    <td>Εγκεκριμένο από HR</td>
                                @else
                                    <td>Αναμένεται έγκριση από HR</td> 
                                @endif

                            @elseif( auth()->user()->permissions->whereIn('pivot.permission_id', 5)->count() > 0 || auth()->user()->superuser === 1)

                                @if($visit->hr_approval)
                                    <td>Εγκεκριμένο από HR <i class="fas pl-2 fa-check"></i></td>
                                @else
                                    <td>
                                        <small class="form-button" wire:click="hr_approval({{ $visit->id }})">Εγκριση HR</small>
                                        <div wire:loading wire:target="hr_approval({{ $visit->id }})">
                                            <x-loading/>
                                        </div>  
                                    </td> 
                              
                                @endif
                            @else
                                @if($visit->hr_approval)
                                    <td>Εγκεκριμένο από HR <i class="fas pl-2 fa-check"></i></td>
                                @else
                                    <td>Αναμένεται έγκριση από HR</td> 
                                @endif
                            @endif

                            @if($visit->completed)
                                <td>Ολοκληρωμένη <i class="fas pl-2 fa-check"></i></td>
                                @else
                                <td>
                                    <small class="form-button" wire:click="completed({{ $visit->id }})">Ολοκλήρωση</small>
                                    <div wire:loading wire:target="completed({{ $visit->id }})">
                                        <x-loading/>
                                    </div>  
                                </td>
                            @endif
                            @if($visit->necessity)
                                <td>{{ $visit->necessity }}</td>
                            @else
                                <td>-</td>
                            @endif
                            
                            @if($visit->reason)
                                <td>{{ $visit->reason }}</td>
                            @else
                                <td>-</td>
                            @endif

                            @if(
                                auth()->user()->permissions->whereIn('pivot.permission_id', 3)->count() > 0 ||
                                auth()->user()->permissions->whereIn('pivot.permission_id', 2)->count() > 0 ||
                                auth()->user()->superuser === 1
                                ) 
                            <td>
                                @if( auth()->user()->permissions->whereIn('pivot.permission_id', 2)->count() > 0 || auth()->user()->superuser === 1)
                                    <i class="far fa-edit has-tooltip" wire:click="ShowModalEdit({{ $visit->id }})"><span class='tooltip'>Επεξεργσία</span></i>
                                @endif
                                @if( auth()->user()->permissions->whereIn('pivot.permission_id', 3)->count() > 0 || auth()->user()->superuser === 1)
                                    <i class="fas fa-trash-alt text-red-600 has-tooltip" wire:click="ShowModalDelete({{ $visit->id }})"><span class='tooltip'>Διαγραφή</span></i>
                                @endif
                            </td>
                            @endif
                        </tr>
                    @endforeach
                    @else    
                    <p class="text-gray-300 py-3 pl-5 text-5xl">
                        Δεν βρέθηκαν επισκέψεις. :'-(
                    </p>
            </tbody>
            </table>
        @endif
    </div>
    <div class="pagination">
        @if($visits->count() > 0)
            {{ $visits->links() }}    
        @endif
    </div>

    <!-- Modal form -->
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Δημιουργία νέας επίσκεψης') }}
        </x-slot>

        <x-slot name="content">
            <!-- Tabs -->
           <ul id="tabs" class="inline-flex w-full px-5 pt-2 glass-effect" >
                <li class="px-4 py-2 -mb-px font-semibold text-gray-800 border-b-2 border-blue-400 rounded-t opacity-50" wire:ignore.self><a id="default-tab" href="#first">Με υπάρχον επικέπτη</a></li>
                <li class="px-4 py-2 font-semibold text-gray-800 rounded-t opacity-50" wire:ignore.self><a href="#second">Με νέο επικέπτη</a></li>   
            </ul>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />    

        
        <x-slot name="footer">

            <div id="tab-contents">

                <div id="first" class="console_tab p-4" wire:ignore.self >
                    
                    <form wire:submit.prevent="create_current">
                                   
                        <div class="mt-4">
                            <x-jet-label class="text-left" for="phone_search " value="{{ __('Αναζητήστε μέσω αριθμού τηλεφώνου') }}" />
                            <x-jet-input wire:model="phone_search " id="phone_search " class="block mt-1 w-full" type="text" name="phone_search"/>
                        </div>
                        @if($visitor_search->count() > 0)
                       
                            <x-jet-input disabled id="visitor_name" class="block mt-1 w-full" value="{{ $visitor_search->pluck('fullname')[0] }} - {{ $visitor_search->pluck('company')[0] }}" type="text" name="visitor_name"/>
                            
                            <x-jet-input wire:model="visitor_id" value="{{ $visitor_search->pluck('id')[0] }}" id="visitor_id" class="block mt-1 w-full hidden" type="text" name="visitor_id"/>
                        @else
                            <p>Δεν βρέθηκε αποτέλεσμα</p>
                        @endif
   
                        <!-- Create visit -->
                        <div class="mt-4 flex flex-col">
                            <x-jet-label class="text-left" for="recipient_id" value="{{ __('Εσωτερικός Αποδέκτης') }}" />
                            <select wire:model="recipient_id"  name="recipient_id" id="recipient_id">
                                <option style="color:gray;" value="">Επιλέξτε Αποδέκτη</option>
                                @if($users->count() > 0)
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                @else 
                                    <option value="Δεν υπάρχει τμήμα">Δεν βρέθηκε υπάλληλος</option>
                                @endif
                            </select>
                        </div>

                        <div class="mt-4 flex flex-col">
                            <x-jet-label class="text-left" for="department_id" value="{{ __('Τμήμα') }}" />
                            <select wire:model="department_id"  name="department_id" id="department_id">
                                <option style="color:gray;" value="">Επιλέξτε Τμήμα</option>
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
                            <x-jet-label class="text-left" for="checkin" value="{{ __('Εκτιμώμενη ώρα άφιξης') }}" />
                            <x-jet-input wire:model="checkin" id="checkin" class="block mt-1 w-full"  type="time" name="checkin" required/>
                        </div>
                        <div class="mt-4">
                            <x-jet-label class="text-left" for="checkout" value="{{ __('Εκτιμώμενη ώρα αποχώρησης') }}" />
                            <x-jet-input wire:model="checkout" id="checkout" class="block mt-1 w-full"  type="time" name="checkout" required/>
                        </div>

                        <div class="mt-4">
                            <x-jet-label class="text-left" for="date" value="{{ __('Ημερομηνία') }}" />
                            <input type="date" class="block mt-1 w-full" name="date" id="date" wire:model="date">                   
                        </div></br>

                        <div class="mt-4">
                            <x-jet-label class="text-left" for="reason" value="{{ __('Λόγος επίσκεψης') }}" />
                            <x-jet-input wire:model="reason" id="reason" class="block mt-1 w-full" type="text" name="reason" :value="old('reason')" autocomplete="reason" />
                        </div>
                        
                        <div class="mt-4">
                            <x-jet-label class="text-left" for="necessity" value="{{ __('Αναγκαιότητα που δεν μπορεί να ματαιωθεί') }}" />
                            <x-jet-input wire:model="necessity" id="necessity" class="block mt-1 w-full" type="text" name="necessity" :value="old('necessity')" autocomplete="necessity" />
                        </div>

                        <x-jet-secondary-button class="mt-3" wire:click="create_current" wire:loading.attr="enabled">
                            {{ __('Δημιουργια') }}
                        </x-jet-secondary-button>

                        <x-jet-secondary-button class="mt-3" wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                            {{ __('Ακυρο') }}
                        </x-jet-secondary-button>
                    </form>
                </div>

                <div id="second" class="console_tab p-4" wire:ignore.self>

                <form wire:submit.prevent="create">

                    <!-- Create visitor -->
                        <div class="mt-4">
                            <x-jet-label class="text-left" for="name" value="{{ __('Ονοματεπώνυμο') }}" />
                            <x-jet-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        </div> @error('name') <span class="text-red-400 error">{{ $message }}</span> @enderror
                        
                        <div class="mt-4">
                            <x-jet-label class="text-left" for="company" value="{{ __('Εταιρεία') }}" />
                            <x-jet-input wire:model="company" id="company" class="block mt-1 w-full" type="text" name="company" :value="old('company')" autocomplete="company" />
                        </div> @error('company') <span class="text-red-400 error">{{ $message }}</span> @enderror
                                    
                        <div class="mt-4">
                            <x-jet-label class="text-left" for="phone" value="{{ __('Αριθμός τηλεφώνου') }}" />
                            <x-jet-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autocomplete="phone" />
                        </div> @error('phone') <span class="text-red-400 error">{{ $message }}</span> @enderror

                
                        <div class="mt-4">
                            <x-jet-label class="text-left" for="email" value="{{ __('Διεύθυνση Email') }}" />
                            <x-jet-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                        </div>  @error('email') <span class="text-red-400 error">{{ $message }}</span> @enderror
                                    
                    <!-- Create visit -->
                    <div class="mt-4 flex flex-col">
                        <x-jet-label class="text-left" for="recipient_id" value="{{ __('Εσωτερικός Αποδέκτης') }}" />
                        <select wire:model="recipient_id"  name="recipient_id" id="recipient_id">
                            <option style="color:gray;" value="">Επιλέξτε Αποδέκτη</option>
                            @if($users->count() > 0)
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            @else 
                                <option value="Δεν υπάρχει τμήμα">Δεν βρέθηκε υπάλληλος</option>
                            @endif
                        </select>
                    </div>@error('recipient_id') <span class="text-red-400 error">{{ $message }}</span> @enderror
                
                    <div class="mt-4 flex flex-col">
                        <x-jet-label class="text-left" for="department_id" value="{{ __('Τμήμα') }}" />
                        <select wire:model="department_id"  name="department_id" id="department_id">
                            <option style="color:gray;" value="">Επιλέξτε Τμήμα</option>
                            @if($departments->count() > 0)
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            @else 
                                    <option value="Δεν υπάρχει τμήμα">Δεν υπάρχει τμήμα</option>
                            @endif
                        </select>
                    </div>@error('department_id') <span class="text-red-400 error">{{ $message }}</span> @enderror
                
                    <div class="mt-4">
                        <x-jet-label class="text-left" for="checkin" value="{{ __('Εκτιμώμενη ώρα άφιξης') }}" />
                        <x-jet-input wire:model="checkin" id="checkin" class="block mt-1 w-full"  type="time" name="checkin" required/>
                    </div>@error('checkin') <span class="text-red-400 error">{{ $message }}</span> @enderror

                    <div class="mt-4">
                        <x-jet-label class="text-left" for="checkout" value="{{ __('Εκτιμώμενη ώρα αποχώρησης') }}" />
                        <x-jet-input wire:model="checkout" id="checkout" class="block mt-1 w-full"  type="time" name="checkout" required/>
                    </div>@error('checkout') <span class="text-red-400 error">{{ $message }}</span> @enderror

                    <div class="mt-4">
                        <x-jet-label class="text-left" for="date" value="{{ __('Ημερομηνία') }}" />
                        <input type="date" class="block mt-1 w-full" name="date" id="date" wire:model="date">                   
                    </div>@error('date') <span class="text-red-400 error">{{ $message }}</span> @enderror</br>

                    <div class="mt-4">
                        <x-jet-label class="text-left" for="reason" value="{{ __('Λόγος επίσκεψης') }}" />
                        <x-jet-input wire:model="reason" id="reason" class="block mt-1 w-full" type="text" name="reason" :value="old('reason')" autocomplete="reason" />
                    </div>@error('reason') <span class="text-red-400 error">{{ $message }}</span> @enderror
                    
                    <div class="mt-4">
                        <x-jet-label class="text-left" for="necessity" value="{{ __('Αναγκαιότητα που δεν μπορεί να ματαιωθεί') }}" />
                        <x-jet-input wire:model="necessity" id="necessity" class="block mt-1 w-full" type="text" name="necessity" :value="old('necessity')" autocomplete="necessity" />
                    </div>@error('necessity') <span class="text-red-400 error">{{ $message }}</span> @enderror
        
                    <x-jet-secondary-button class="mt-3" wire:click="create" wire:loading.attr="enabled">
                        {{ __('Δημιουργια') }}
                    </x-jet-secondary-button>

                    <x-jet-secondary-button class="mt-3" wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                        {{ __('Ακυρο') }}
                    </x-jet-secondary-button>
                </form>
            </div>
        </div>
        </x-slot>
    </x-jet-dialog-modal>

     <!-- Modal edit form -->
     <x-jet-dialog-modal wire:model="modalEditVisible">
        <x-slot name="title">
            {{ __('Επεξεργασία πληροφοριών κράτησης') }}
            
        </x-slot>

        <x-slot name="content">

        </x-slot>

        <x-jet-validation-errors class="mb-4" />    

        
        <x-slot name="footer">
            <form wire:submit.prevent="update">
        
            <!-- Create visitor -->
                <div class="mt-4">
                    <x-jet-label class="text-left" for="name" value="{{ __('Ονοματεπώνυμο') }}" />
                    <x-jet-input wire:model="old_name" id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
                
                <div class="mt-4">
                    <x-jet-label class="text-left" for="company" value="{{ __('Εταιρεία') }}" />
                    <x-jet-input wire:model="old_company" id="company" class="block mt-1 w-full" type="text" name="company" :value="old('company')" autocomplete="company" />
                </div>
                
                <div class="mt-4">
                    <x-jet-label class="text-left" for="phone" value="{{ __('Αριθμός τηλεφώνου') }}" />
                    <x-jet-input wire:model="old_phone" id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autocomplete="phone" />
                </div>
                
                
                <div class="mt-4">
                    <x-jet-label class="text-left" for="email" value="{{ __('Διεύθυνση Email') }}" />
                    <x-jet-input wire:model="old_email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div> 
                
                <!-- Create visit -->
                <div class="mt-4 flex flex-col">
                    <x-jet-label class="text-left" for="recipient_id" value="{{ __('Εσωτερικός Αποδέκτης') }}" />
                    <select wire:model="old_recipient_id"  name="recipient_id" id="recipient_id">
                        <option style="color:gray;" value="">Επιλέξτε Αποδέκτη</option>
                        @if($users->count() > 0)
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                        @else 
                        <option value="Δεν υπάρχει τμήμα">Δεν βρέθηκε υπάλληλος</option>
                        @endif
                    </select>
                </div>
                
                <div class="mt-4 flex flex-col">
                    <x-jet-label class="text-left" for="department_id" value="{{ __('Τμήμα') }}" />
                    <select wire:model="old_department_id"  name="department_id" id="department_id">
                        <option style="color:gray;" value="">Επιλέξτε Τμήμα</option>
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
                    <x-jet-label class="text-left" for="checkin" value="{{ __('Εκτιμώμενη ώρα άφιξης') }}" />
                    <x-jet-input wire:model="old_checkin" id="checkin" class="block mt-1 w-full"  type="time" name="checkin" required/>
                </div>
                <div class="mt-4">
                    <x-jet-label class="text-left" for="checkout" value="{{ __('Εκτιμώμενη ώρα αποχώρησης') }}" />
                    <x-jet-input wire:model="old_checkout" id="checkout" class="block mt-1 w-full"  type="time" name="checkout" required/>
                </div>
                
                <div class="mt-4">
                    <x-jet-label class="text-left" for="date" value="{{ __('Ημερομηνία') }}" />
                    <input type="date" class="block mt-1 w-full" name="date" id="date" wire:model="old_date">                   
                </div></br>
                
                <div class="mt-4">
                    <x-jet-label class="text-left" for="reason" value="{{ __('Λόγος επίσκεψης') }}" />
                    <x-jet-input wire:model="old_reason" id="reason" class="block mt-1 w-full" type="text" name="reason" :value="old('reason')" autocomplete="reason" />
                </div>
                
                <div class="mt-4">
                    <x-jet-label class="text-left" for="necessity" value="{{ __('Αναγκαιότητα που δεν μπορεί να ματαιωθεί') }}" />
                    <x-jet-input wire:model="old_necessity" id="necessity" class="block mt-1 w-full" type="text" name="necessity" :value="old('necessity')" autocomplete="necessity" />
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
            {{ __('Διαγραφή χρήστη') }}
            
        </x-slot>
        
        <x-slot name="content">
            
            </x-slot>
            
            <x-jet-validation-errors class="mb-4" />    
            
            
        <x-slot name="footer">
            <form wire:submit.prevent="delete">
                <div class="mt-1 text-left bg-white overflow-hidden shadow-xl mx-5 p-5 sm:rounded-lg flex flex-col">
                    <p>Θέλετε να διαγράψετε την κράτηση;</p>
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