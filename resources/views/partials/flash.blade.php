@if(session('status'))
    <div class="alert alert-success" role="alert" style="margin:15px 0;padding:12px;background:#dff0d8;border:1px solid #d0e9c6;color:#3c763d;">
        {{ session('status') }}
    </div>
@endif
@if(session('warning'))
    <div class="alert alert-warning" role="alert" style="margin:15px 0;padding:12px;background:#fcf8e3;border:1px solid #faebcc;color:#8a6d3b;">
        {{ session('warning') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger" role="alert" style="margin:15px 0;padding:12px;background:#f2dede;border:1px solid #ebccd1;color:#a94442;">
        <ul style="margin:0;padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
