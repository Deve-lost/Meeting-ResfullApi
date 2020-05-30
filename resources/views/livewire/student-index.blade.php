<ul>
	@foreach($students as $val)
	<livewire:student-single :jquin="$val" :key="$val->id">
	@endforeach
</ul>
