<div>
    @include('livewire.inc.create-todo-box')
    @include('livewire.inc.todo-search-bar')
    <div id="todos-list">
        @foreach ($todos as $todo)
            @include('livewire.inc.todo-card')
        @endforeach

        <div class="my-2">
            {{ $todos->links() }}
        </div>
    </div>
</div>
