<div class="card">
    <div class="card-header">
        <a href="{{ route('tyres.index', ['page' => 'search']) }}"
            class="{{ request()->query('page') == 'search' ? 'active' : '' }}">Search</a>
        |
        <a href="{{ route('tyres.index', ['page' => 'new']) }}"
            class="{{ request()->query('page') == 'new' ? 'active' : '' }}">New</a>
        |
        <a href="{{ route('tyres.index', ['page' => 'list']) }}"
            class="{{ request()->query('page') == 'list' ? 'active' : '' }}">List</a>
    </div>
</div>
