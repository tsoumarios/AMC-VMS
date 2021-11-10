<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Department;

class Departments extends Component
{
    public $name;
    public $old_name;
    public $department_id;
        
    public $modalFormVisible = false;    
    public $modalEditVisible = false;    
    public $modalDeleteVisible = false;    

     /**
     * Shows the form modal 
     * of delete function
     * @return void
     */

    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
    ];
    public function ShowModal()
    {

        $this->modalFormVisible = true;
        $this->old_name;
    }
    
    public function ShowModalEdit($department_id)
    {
        $this->department_id = $department_id;
        $this->old_name = Department::find($department_id)->name;
        $this->modalEditVisible = true;
        
    }
    
    public function ShowModalDelete($department_id)
    {
        $this->department_id = $department_id;
        $this->name = Department::find($department_id)->name;

        $this->modalDeleteVisible = true;
       
    }

    /**
     * Create new department 
     * 
     * @return void
     */

    public function create()
    {
        $this->validate();
        Department::create(['name' => $this->name]);

        $this->modalFormVisible = false;
    }

    public function update()
    {


        $department = Department::find($this->department_id);

        $department->update(['name' => $this->old_name]);

        $this->modalEditVisible = false;
    }

    public function delete()
    {
        $this->validate();

        $department = Department::find($this->department_id);

        $department->delete();

        $this->modalDeleteVisible = false;
    }

    public function render()
    {
        $departments = Department::paginate(10);
        return view('livewire.departments', compact('departments'));
    }
}
