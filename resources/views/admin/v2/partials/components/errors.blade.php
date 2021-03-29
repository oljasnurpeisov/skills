@if(isset($errors) && $errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ is_array($error) ? implode('<br>',$error) : $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
