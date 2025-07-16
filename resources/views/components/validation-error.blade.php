@if ($errors->any() || session('error'))
    <div class="alert alert-danger">
        {{session('error') }}
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error}}</li>
            @endforeach
        </ul>
    </div>
@endif
