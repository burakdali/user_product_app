<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="table table-bordered users-table">
                        <thead>
                            <tr>
                                <th>first name</th>
                                <th>last name</th>
                                <th>email</th>
                                <th>phone number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/updateUser" method="post">
                        @csrf
                        <input type="text" class="form-control" id="userId" name="id" hidden>

                        <div class="mb-3">
                            <label for="first_name" class="col-form-label">First Name:</label>
                            <input type="text" class="form-control" id="first_name" name="first_name">
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="col-form-label">Last Name:</label>
                            <input type="text" class="form-control" id="last_name" name="last_name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="col-form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="col-form-label">Phone number:</label>
                            <input type="number" class="form-control" id="phone_number" name="phone_number">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="col-form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit" id="editUserBtn">Update
                                User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="assignProducts" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Assign products</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/assignSave" method="post" id="assignForm">
                        @csrf
                        <input type="text" class="form-control" id="assignModalUserId" name="id" hidden>
                        <div id="formHeader"></div>
                        <div class="modal-footer" id="modalFooter">
                            <button type="button" class="btn btn-secondary text-dark"
                                data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary text-dark" type="submit" id="editUserBtn">Update
                                User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="userProducts">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ol id="productList" class="list-group">

                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.fn.dataTable.ext.errMode = 'throw';
        var table = $('.users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.getUsers') }}",
            columns: [{
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
    $(document).on("click", ".edit", function(event) {
        var id = $(this).attr('id');
        $.ajax({
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/editUser/" + id + "/",
            dataType: "json",
            success: function(response) {
                $('#first_name').val(response.result.first_name);
                $('#last_name').val(response.result.last_name);
                $('#email').val(response.result.email);
                $('#phone_number').val(response.result.phone_number);
                $('#userId').val(response.result.id);
            }
        });
    });

    $(document).on("click", ".delete", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: 'delete',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/deleteUser/" + id + "/",
            dataType: "json",
            success: function(response) {
                swal("Good job!", response.result, "success");
            },
            error: function(error) {
                console.log(error);
            }
        })
    });
    $(document).on("click", ".assign", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: "get",
            url: "{{ route('admin.getProductsToAssign') }}",
            dataType: "json",
            success: function(response) {
                $('#formHeader').empty();
                $('#assignModalUserId').val(id);
                for (var i = 0; i < response['data'].length; i++) {
                    $("#formHeader").append(
                        "<div class='form-check'>" +
                        "<input class='form-check-input' type='checkbox' name='products[]' value=" +
                        response['data'][i]['id'] +
                        " id='flexCheckDefault'/>" +
                        "<label class='form-check-label' for='flexCheckDefault'>" +
                        response['data'][i]['name'] + "</label>" +
                        "<div>"
                    );
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
    $(document).on("click", ".products", function() {
        var id = $(this).attr('id');
        $.ajax({
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/getUserProducts/" + id,
            dataType: "json",
            success: function(response) {
                console.log(response);
                for (var i = 0; i < response['data'].length; i++) {
                    $("#productList").append(
                        "<li class='list-group-item'>" + response['data'][i] +
                        "</li>"
                    );
                }
            },
            error: function(error) {
                console.log(error);
            }
        })
    });
</script>
@if (Session::has('userDeletesuccess'))
    <script>
        swal("Good job!", "user Deleted Successresuly", "success");
    </script>
@endif
@if (Session::has('success'))
    <script>
        swal("Good job!", "User edited successfully", "success");
    </script>
@endif
