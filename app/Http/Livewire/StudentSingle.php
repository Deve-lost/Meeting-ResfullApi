<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StudentSingle extends Component
{
	public $jquin;

	public function mount($jquin)
	{
		$this->jquin = $jquin;
	}

    public function render()
    {
        return view('livewire.student-single');
    }
}
