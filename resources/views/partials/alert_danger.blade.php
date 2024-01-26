
@if($errors->any())
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger" role="alert">

                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger" role="alert">

                <ul>
                    <li>{{ session('error') }}</li>
                </ul>
        </div>
    </div>
</div>
@endif

<script>
window.setTimeout(function() {
    $(".alert").fadeTo(10000, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 2000);
</script>
