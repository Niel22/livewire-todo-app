<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;
    
    #[Rule("required|min:3|max:50")]
    public $name;

    public $search;
    
    public function create(){
        $todo = $this->validateOnly('name');

        Todo::create($todo);

        $this->reset(['name']);

        session()->flash('success','Todo Created');

    }

    public function delete($id){

        Todo::find( $id )->delete();

        session()->flash('deleted','Todo deleted');

    }

    public function toggleCompletion($id){
        $todo = Todo::find($id);

        $todo->update([
            'completed' => !$todo->completed
        ]);

        
    }

    public function render()
    {

        return view('livewire.todo-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5)
        ]);
    }
}
