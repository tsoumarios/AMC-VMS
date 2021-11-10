<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Permission;
use App\Models\Department;
use App\Models\User;

use App\Mail\Credentials;

use Illuminate\Support\Facades\Mail;

class Employees extends Component
{
    public $name, $phone, $email, $department_id, $password;

    // Variables to hold old values
    public $old_name, $old_phone, $old_email, $old_department_id;

    // permission variables
    public $user_id, $user_name;
 

    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required','min:8'],
        'phone' => ['required', 'string', 'max:10', 'unique:users']
    ];
    
    public $modalFormVisible = false;    
    public $modalPermVisible = false;    
    public $modalEditVisible = false;    
    public $modalDeleteVisible = false;    
    

    /**
     * Shows the form modal 
     * of permission function
     * @return void
     */

    public function ShowModalPerm($user_id)
    {
        $this->modalPermVisible = true;
        $this->user_id = $user_id;

        $this->user_name = User::find($user_id)->name;

    }
     /**
     * Shows the form modal 
     * of edit function
     * @return void
     */

    public function ShowModalEdit($user_id)
    {
        $this->modalEditVisible = true;
        $this->user_id = $user_id;
        $this->old_name = User::find($user_id)->name;
        $this->old_phone = User::find($user_id)->phone;
        $this->old_email = User::find($user_id)->email;
        $this->old_department_id= User::find($user_id)->department_id;

        $this->user_name = User::find($user_id)->name;

    }
    
     /**
     * Shows the form modal 
     * of delete function
     * @return void
     */

    public function ShowModalDelete($user_id)
    {
        $this->modalDeleteVisible = true;
        $this->user_id = $user_id;

        $this->user_name = User::find($user_id)->name;

    }

     /**
     * Shows the form modal 
     * of create function
     * @return void
     */

    public function ShowModal()
    {
        $this->modalFormVisible = true;
    }

    /**
     * Create new department 
     * 
     * @return void
     */

    public function create()
    {

        $this->validate();

        User::create([
           'name' => $this->name,
           'phone' => $this->phone,
           'email' => $this->email,
           'department_id' => $this->department_id ? $this->department_id : null,
           'password' =>  Hash::make($this->password),
           'superuser' => false
        ]);

        Mail::to( $this->email )->send(new Credentials($this->email, $this->password));

        $this->modalFormVisible = false;
    }

    /**
     * Update user info 
     * 
     * @return void
     */

    public function update()
    {


        User::find($this->user_id)->update([
           'name' => $this->old_name,
           'phone' => $this->old_phone,
           'email' => $this->old_email,
           'department_id' => $this->old_department_id ? $this->old_department_id : null,
        ]);

        $this->modalEditVisible = false;
    }
    
    /**
     * Delete user 
     * 
     * @return void
     */

    public function delete()
    {

        User::find($this->user_id)->delete();

        $this->modalEditVisible = false;

        return redirect(route('employees'));
    }

    // Remove user permissions
    public function removePermission($permission_id) { 

        $user = User::find($this->user_id);

        $user->permissions()->detach($permission_id);

    }


    // Give user permission
    public function givePermission($permission_id) {

        $user = User::find($this->user_id);

        $user->permissions()->attach($permission_id);
    }

    public function render()
    {
        $employees = User::where('superuser', false)->paginate(10);
        $departments = Department::all();
        $permissions = Permission::all();
           
        return view('livewire.employees', compact(
            'employees', 'departments', 'permissions'
        ));
    }
}
