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

    public $editingID;

    #[Rule("required|min:3|max:50")]
    public $editingName;
    
    public function create(){
        $todo = $this->validateOnly('name');

        Todo::create($todo);

        $this->reset(['name']);

        session()->flash('success','Todo Created');

        $this->resetPage();

    }

    public function delete($id){

        try{

            Todo::findOrFail( $id )->delete();
            
            session()->flash('deleted','Todo deleted');
        }catch(\Exception $e){
            session()->flash('deleted','Todo deleted');
            
        }
        
    }
    
    public function edit($id){
        try{
            
            $this->editingID = $id;
            $this->editingName = Todo::findOrFail($id)->name;
        }catch(\Exception $e){
            session()->flash('deleted','Todo deoes not exist');
            
        }
    }

    public function cancelEdit(){
        $this->editingID = '';
    }

    public function update(){
        
        try{
            
            Todo::findOrFail($this->editingID)->update(['name' => $this->validateOnly('editingName')['editingName']]);
            
            $this->editingID = '';
        }catch(\Exception $e){
            session()->flash('deleted','Todo deoes not exist');
            
        }
    }
    
    public function toggleCompletion($id){
        
        try{
            
            $todo = Todo::findOrFail($id);
            
            $todo->update([
                'completed' => !$todo->completed
            ]);
        }catch(\Exception $e){
            session()->flash('deleted','Todo deoes not exist');

        }


    }

    public function render()
    {

        return view('livewire.todo-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5)
        ]);
    }
}
