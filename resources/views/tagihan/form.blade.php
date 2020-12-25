@extends(Session::get('role') == 'master' ? 'layout.master' : 'layout.admin')
@section('head')
<!-- Tambah Content Pada Head -->
@endsection

@section('content')
<!-- Tambah Content Pada Body Utama -->
<div class = "container-fluid">
    <div class="d-sm-flex align-items-center justify-content-center">
        @yield('title')
    </div>
    <div class="mb-4">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="card shadow col-lg-6">
                    <div class="p-4">
                        @yield('body')
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection

@section('js')
<!-- Tambah Content pada Body JS -->
@yield('jsplus')
@endsection