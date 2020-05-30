<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Student;

class StudentIndex extends Component
{
	protected $listeners = [
		'studentAdded',
	];

	public function studentAdded()
	{
		# code...
	}

    public function render()
    {
    	$students = Student::latest()->get();

        return view('livewire.student-index', ['students' => $students]);
    }
}
