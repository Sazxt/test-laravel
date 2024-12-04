@extends('template')
@section('title')
    Dashboard Categories
@endsection

@section('css')
<link href="/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dashboard</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Data Page Crud Categori</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Data</h6>
    <hr />
    <div class="card">
        <div class="card-body">
                @if(session('success'))
                <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-success"><i class="bx bxs-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-success">Successfuly</h6>
                            <div>{{ session('success') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
            <div class="mb-3" style="display: none;" id="errors">
                <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-danger">
                            <i class="bx bxs-message-square-x"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-danger">Error</h6>
                            <div>

                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addmodal">Tambah Data</button>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name Categori</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($catgeories as $catgeorie)
                        <tr>
                            <td class="text-center">{{ $catgeorie->name }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    {{-- Edit Button --}}
                                    <button data-id="{{ $catgeorie->id }}" class="btn btn-sm btn-outline-primary editButoon">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    
                                    {{-- Delete Button --}}
                                    <form action="{{ route('categories.destroy', $catgeorie->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this employee?')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="addmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addmodalLabel">Tambah Data Categori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="categoriname" class="form-label">Categori Name</label>
                                <input type="text" class="form-control" id="categoriname" name="categoriname" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('categories.edit') }}" method="POST" id="editForm">
                @csrf
                <div class="modal-content">
                    <input type="hidden" id="id" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Update Data Categori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="categorinameedit" class="form-label">Categori Name</label>
                                <input type="text" class="form-control" id="categorinameedit" name="categoriname" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
@endsection

@section('js')
<script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true
        });

        $("#example").on("click", ".editButoon", function() {
            let id = $(this).data("id");
            $.ajax({
                url: `/categories-detail/${id}`,
                type: "GET",
                dataType: "JSON",
                success: function(response) {
                    $("#editForm").find("input[name='id']").val(response.id);
                    $("#editForm").find("input[name='categoriname']").val(response.name);
                    $("#editModal").modal("show");
                }
            });
        })

        $("#editForm").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    location.reload();
                },
                complete: function()
                {
                    $("#editModal").modal("hide");
                },
                error: function(xhr) {
                    $("#errors").show();
                    
                    $("#errors .alert-dismissible .ms-3 div:last-child").empty();
                    
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '';
                        
                        Object.values(errors).forEach(function(errorArray) {
                            errorArray.forEach(function(errorMessage) {
                                errorHtml += `<p class="mb-0">${errorMessage}</p>`;
                            });
                        });
                        
                        $("#errors .alert-dismissible .ms-3 div:last-child").html(errorHtml);
                    } else {
                        $("#errors .alert-dismissible .ms-3 div:last-child")
                            .html('<p class="mb-0">An unexpected error occurred. Please try again.</p>');
                    }
                }
            });
        })

    });
</script>
@endsection
