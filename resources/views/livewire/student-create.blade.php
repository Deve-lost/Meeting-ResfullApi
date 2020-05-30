<div class="mb-4">
    <form wire:submit.prevent="addStundent">
        <div class="form-group">
            <label for="name">Student Name</label>
            <input type="text" name="name" wire:model="name" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>