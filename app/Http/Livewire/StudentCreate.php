<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Student;

class StudentCreate extends Component
{
	public $name;

	public function addStundent()
	{
		$student = Student::create(['name' => $this->name]);
		$this->emit('studentAdded');

		$this->name = "";
	}

    public function render()
    {
        return view('livewire.student-create');
    }
}
