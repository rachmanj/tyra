<li class="nav-item dropdown">
    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
        class="nav-link dropdown-toggle">Tyres</a>
    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
        <li><a href="{{ route('tyres.index', ['page' => 'search']) }}" class="dropdown-item">Tyres</a></li>
        <li><a href="{{ route('tyre-sizes.index') }}" class="dropdown-item">List of Tyre Sizes</a></li>
        <li><a href="{{ route('transactions.index') }}" class="dropdown-item">List of Transactions</a></li>
        <li><a href="{{ route('equipments.index') }}" class="dropdown-item">List of Equipments</a></li>
    </ul>
</li>
