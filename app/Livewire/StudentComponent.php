<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Student;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class StudentComponent extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $identity;
    public $image;
    public $newimage;
    public $edit = false;
    public $student_id;
    public $student_delete_id;

    public function updated($fields) {
        if($this->edit==true) {
            $this->validateOnly($fields,[
                'name'=>'required|max:255',
                'email'=>'required|email',
                'phone'=>'required|numeric',
                'identity'=>'required|numeric'
            ]);

            if($this->newimage) {
                $this->validateOnly($fields,[
                    'newimage'=>'mimes:png,jpg,jpeg'
                ]);
            }
        }
        else {
            $this->validateOnly($fields,[
                'name'=>'required|max:255',
                'email'=>'required|email',
                'phone'=>'required|numeric|unique:students',
                'identity'=>'required|numeric'
            ]);

            if($this->image) {
                $this->validateOnly($fields,[
                    'image'=>'mimes:png,jpg,jpeg'
                ]);
            }
        }
    }

    public function closeStudentModal() {
        $this->edit = false;
        $this->student_id = null;
        $this->resetForm();
    }

    public function resetForm() {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->identity = '';
        $this->image = '';
    }

    public function storeStudent() {
        $this->validate([
            'name'=>'required|max:255',
            'email'=>'required|email',
            'phone'=>'required|numeric|unique:students',
            'identity'=>'required|numeric'
        ]);

        if($this->image) {
            $this->validate([
                'image'=>'mimes:png,jpg,jpeg'
            ]);
        }

        $student = new Student();
        if($this->image) {
            $manager = new ImageManager(new Driver());
            $imageName = Carbon::now()->timestamp.'.'.$this->image->getClientOriginalExtension();
            $image = $manager->read($this->image);
            $image->resize(200,200);
            $image->toPng()->save('assets/images/student/'.$imageName);

            $student->image = $imageName;
        }
        $student->name = $this->name;
        $student->email = $this->email;
        $student->phone = $this->phone;
        $student->identity = $this->identity;

        $student->save();

        $this->closeStudentModal();
        toastr()->success('Student has been created successfully!');
        return $this->redirect('/',navigate:true);

    }

    public function editStudent($id) {
        $this->student_id = $id;
        $this->edit = true;
        if($this->edit==true) {
            $student = Student::where('id',$this->student_id)->first();
            if($student) {
                $this->name = $student->name;
                $this->email = $student->email;
                $this->phone = $student->phone;
                $this->identity = $student->identity;
                $this->image = $student->image;
            }
        }
        else {
            $this->closeStudentModal();
        }
    }

    public function updateStudent() {
        $this->validate([
            'name'=>'required|max:255',
            'email'=>'required|email',
            'phone'=>'required|numeric',
            'identity'=>'required|numeric'
        ]);

        if($this->newimage) {
            $this->validate([
                'newimage'=>'mimes:png,jpg,jpeg'
            ]);
        }

        $student = Student::where('id',$this->student_id)->first();
        if($this->newimage) {
            if($student->image) {
                unlink('assets/images/student/'.$student->image);
            }
            $manager = new ImageManager(new Driver());
            $imageName = Carbon::now()->timestamp.'.'.$this->newimage->getClientOriginalExtension();
            $image = $manager->read($this->newimage);
            $image->resize(200,200);
            $image->toPng()->save('assets/images/student/'.$imageName);

            $student->image = $imageName;
        }
        $student->name = $this->name;
        $student->email = $this->email;
        $student->phone = $this->phone;
        $student->identity = $this->identity;

        $student->save();

        $this->closeStudentModal();
        toastr()->success('Student has been updated successfully!');
        return $this->redirect('/',navigate:true);
    }

    public function deleteStudent($id) {
        $this->student_delete_id = $id;
    }

    public function destroyStudent() {
        $student = Student::findOrfail($this->student_delete_id);
        if($student->image) {
            unlink('assets/images/student/'.$student->image);
        }
        $student->delete();
        $this->closeStudentModal();
        toastr()->success('Student has been deleted successfully!');
        return $this->redirect('/',navigate:true);
    }

    public function render()
    {
        $students = Student::orderBy('id','DESC')->get();
        return view('livewire.student-component',['students'=>$students])->layout('master');
    }
}
