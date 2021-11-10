<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Mail\Visit_mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

use PDF;

use App\Models\Department;
use App\Models\User;
use App\Models\Visit;
use App\Models\Visitor;

class Visits extends Component
{
    public $recipient_id,
            $checkin,
            $checkout,
            $date,
            $name,
            $phone,
            $company,
            $necessity,
            $reason,
            $email,
            $department_id,
            $password,
            $phone_search,
            $visitor_id      
    ;

    public 
            $old_recipient_id,
            $old_checkin,
            $old_checkout,
            $old_date,
            $old_name,
            $old_phone,
            $old_company,
            $old_necessity,
            $old_reason,
            $old_email,
            $old_department_id,
            $old_password,
            $old_phone_search,
            $old_visitor_id      
    ;

    public $visit_id, $date_search;

    public function mount() {
        $this->date_search = Carbon::today()->toDateString();
    }
    
    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'max:10', 'unique:users'],
        'checkin' => ['required'],
        'checkout' => ['required'],
        'date' => ['required'],
    ];
    
    public $modalFormVisible = false;    
    public $modalEditVisible = false;    
    public $modalDeleteVisible = false;    

     /**
     * Shows the form modal 
     * of delete function
     * @return void
     */

    public function ShowModalForm()
    {

        $this->modalFormVisible = true;
       
    }

     /**
     * Shows the form modal 
     * of edit function
     * @return void
     */

    public function ShowModalEdit($visit_id)
    {
        $this->modalEditVisible = true;
        
        $this->visit_id = $visit_id;
        
        $visit = Visit::find($visit_id);
        
        $this->old_name =  $visit->visitor->fullname;
        $this->old_phone = $visit->visitor->phone;
        $this->old_email = $visit->visitor->email;
        $this->old_department_id = $visit->department_id;
        $this->old_recipient_id = $visit->recipient_id;
        $this->old_company = $visit->visitor->company;
        $this->old_checkin = $visit->checkin;
        $this->old_checkout = $visit->checkout;
        $this->old_date = $visit->date;
        $this->old_reason = $visit->reason;
        $this->old_necessity = $visit->necessity;

    }
    
     /**
     * Shows the form modal 
     * of delete function
     * @return void
     */

    public function ShowModalDelete($visit_id)
    {
        $this->modalDeleteVisible = true;
        $this->visit_id = $visit_id;
    }

    /**
     * Create new department 
     * 
     * @return void
     */

     public function create_visitor() {
        Visitor::create([
            'fullname' => $this->name,
            'company' => $this->company,
            'phone' => $this->phone,
            'email' => $this->email,
         ]);
     }

    public function create()
    {
        
        $this->create_visitor();
        
        $visitor_id = Visitor::where('phone', $this->phone)->get()->pluck('id')[0];
        
        $this->validate();        
        
        Visit::create([
           'creator_id' => auth()->user()->id,
           'recipient_id' => $this->recipient_id,
           'visitor_id' => $visitor_id,
           'department_id' => $this->department_id ? $this->department_id : null,

           'checkin' => $this->checkin,
           'checkout' => $this->checkout,
           'date' => $this->date,           

           'necessity' => $this->necessity,
           'reason' => $this->reason,

        ]);

        $this->modalFormVisible = false;
    }

    public function create_current()
    {
       

        $phone = Visitor::where('phone', $this->phone_search)->get()->pluck('id')[0];

        Visit::create([

           'creator_id' => auth()->user()->id,
           'recipient_id' => $this->recipient_id,
           'visitor_id' => $phone,
           'department_id' => $this->department_id ? $this->department_id : null,

           'checkin' => $this->checkin,
           'checkout' => $this->checkout,
           'date' => $this->date,           

           'necessity' => $this->necessity,
           'reason' => $this->reason,

        ]);

        $this->modalFormVisible = false;
    }


    /**
     * Approve visit
     * 
     * @return void
     */
    
    public function hr_approval( $visit_id )
    {
        // Find the visit
        $visit = Visit::where('id', $visit_id);

        $visit->update(['hr_approval' => 1]);

        $visit = $visit->get()[0];
        $checkin = $visit->checkin;
        $checkout = $visit->checkout;
        $date = $visit->date;
        
        // Find the recipient
        $recipient_id = $visit->recipient_id;
        if(User::find($recipient_id)) {
            $recipient = User::find($recipient_id)->get()[0];
            $recipient_name = User::find($recipient_id)->get()[0]->name;
        }
        
        
        // Find the department
        $department_id = $visit->department_id;
        if( Department::find($department_id) && $visit->department_id) {
            $department = Department::find($department_id)->get()[0]->name;
        }
        
        // Find the visitor
        $visitor_id = $visit->visitor_id;
        $visitor = Visitor::find($visitor_id)->get()[0];
        $visitor_name = Visitor::find($visitor_id)->get()[0]->fullname;

        // Send the emails
        // Recipient email
        if(User::find($recipient_id)) {
            if(Department::find($department_id) && $visit->department_id) {
                Mail::to( $recipient->email )->send(new Visit_mail($date, $checkin, $checkout, $visitor_name, $recipient_name, $department));
            }
            else{
                $department = "-";
                Mail::to( $recipient->email )->send(new Visit_mail($date, $checkin, $checkout, $visitor_name, $recipient_name, $department));
            }
        }

        // Visitor email
        if(Department::find($department_id) && User::find($recipient_id) ) {
            Mail::to( $visitor->email )->send(new Visit_mail($date, $checkin, $checkout, $visitor_name, $recipient_name, $department));
        }
        else if(!(Department::find($department_id)) && User::find($recipient_id)) {
            $department = "-";
            Mail::to( $visitor->email )->send(new Visit_mail($date, $checkin, $checkout, $visitor_name, $recipient_name, $department));
        }
        else if(!(User::find($recipient_id)) && Department::find($department_id)) {
            $recipient_name = "-";
            Mail::to( $visitor->email )->send(new Visit_mail($date, $checkin, $checkout, $visitor_name, $recipient_name, $department));
        }else {
            $department = "-";
            $recipient_name = "-";
            Mail::to( $visitor->email )->send(new Visit_mail($date, $checkin, $checkout, $visitor_name, $recipient_name, $department));
        }

        $this->modalFormVisible = false;
    }

    /**
     * Complete visit
     * 
     * @return void
     */

    public function completed( $visit_id )
    {

        Visit::where('id', $visit_id)->update(['completed' => 1]);

        $this->modalFormVisible = false;
    }

        /**
     * Update visit info 
     * 
     * @return void
     */

    public function update()
    {        
        $visitor = Visit::find($this->visit_id)->visitor()->update([
            'fullname' => $this->old_name,
            'phone' => $this->old_phone,
            'email' => $this->old_email,
            'company' => $this->old_company,
        ]);
        
        $visit = Visit::find($this->visit_id);


        Visit::find($this->visit_id)->update([
    
           'department_id' => $this->old_department_id ? $this->old_department_id : null,
           'recipient_id' => $this->old_recipient_id ? $this->old_recipient_id : null,
           'checkin' => $this->old_checkin,
           'checkout' => $this->old_checkout,
           'date' => $this->old_date,
           'necessity' => $this->old_necessity,
           'reason' => $this->old_reason,
        ]);

        $this->modalEditVisible = false;
    }
    
    /**
     * Delete visit 
     * 
     * @return void
     */

    public function delete()
    {

        Visit::find($this->visit)->delete();

        $this->modalEditVisible = false;

        return redirect(route('visits'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function createPDF($date_search) {
        // retreive all records from db
        if($date_search) {
            $visits = Visit::where('date', $date_search)->get();
        }
        else {
            $visits = Visit::paginate(10);
        }
        $recipients = User::all();

        // share data to view
        view()->share('visits', $visits);
        $pdf = PDF::loadView('reportPDF', ['visits' => $visits, 'date_search' =>  $date_search, 'recipients' => $recipients])->setPaper('a4', 'landscape');
  
        // download PDF file with download method
        return $pdf->download('reportPDF.pdf');
    }


    public function render()
    {
        $users = User::paginate(10);
   
        // Visitor search
        $visitor_search = Visitor::where('phone', $this->phone_search)->get();
        $departments = Department::all();
        $date_search = $this->date_search;

        if(!($this->date_search)) {
            $visits = Visit::paginate(10);
        } else {
            $visits = Visit::where('date', $this->date_search)->paginate(10);
        }
        
        return view('livewire.visits', compact(
            'users', 'visits', 'departments', 'visitor_search', 'date_search'
        ));
    }

}


