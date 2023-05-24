@extends('layouts.app')

@section('css')
<style>

body {
  margin: 0;
  color: #000;
  background-color: #fff;
  box-sizing: border-box;
}
    </style>
@endsection
@section('content')

<!-- modal -->

<div class="modal fade" id="add_employee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Employee</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Form -->
      <div class="modal-body">
        <div class="form-group mb-3">
            <label for="names">Employee Name</label>
            <input type="text" class="names form-control">
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="email form-control">
        </div>

        <div class="form-group mb-3">
            <label for="phone">Phone</label>
            <input type="text" class="phone form-control">
        </div>

        <div class="form-group mb-3">
            <label for="role">Role</label>
            <input type="text" class="role form-control">
        </div>
      </div>

      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary add_employee">Save Data</button>
      </div>
    </div>
    
  </div>
</div>
<!-- end of modal -->

<!-- Fetching data in the table -->
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div id="success_message"></div>
            <div class="card">
                <div class="card-header">
                    <h5>
                        Employee Data
                        <a href="" data-bs-toggle="modal" data-bs-target="#add_employee" class="btn btn-success float-end">Add Employee</a>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="error_list"></div>
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="employee_list">
                            
                        @foreach($employees as $employee)

                            <tr data-id="{{ $employee->id }}">
                                <td>{{ $loop -> index+1 }}</td>
                                <td>{{ $employee -> names }}</td>
                                <td>{{ $employee -> email }}</td>
                                <td>{{ $employee -> phone }}</td>
                                <td>{{ $employee -> role }}</td>
                                <td class="display_off"" >
                                  
                                  <a href="" class="btn btn-info edit_employee " data-id="{{ $employee->id }}" data-name="{{ $employee->names }}" data-email="{{ $employee->email }}" data-phone="{{ $employee->phone }}" data-role="{{ $employee->role }}" data-bs-toggle="modal" data-bs-target="#edit_employee">Edit</a>
                                  <a href="" class="btn btn-danger delete_employee " data-id="{{ $employee->id }}">Delete</a>    
                              </td>
                                
                                </tr>
                        @endforeach
                        </tbody>
                               
                            <!-- </tr>
                        
                        </tbody> -->
                    </table>
                </div>
            </div>

            <div class="card-header">

            
    <h5>
        <button onclick="printEssentialData()" class="btn btn-primary float-end me-2">Print</button>
    </h5>
</div>

        </div>
    </div>
</div>

<!--Update Data-->

<!-- Edit Employee modal -->
<div class="modal fade" id="edit_employee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Employee</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Form -->
      <div class="modal-body">
        <div class="form-group mb-3">
            <label for="edit_names">Employee Name</label>
            <input type="text" id="edit_names" class="names form-control">
        </div>

        <div class="form-group mb-3">
            <label for="edit_email">Email</label>
            <input type="email" id="edit_email" class="edit_email form-control">
        </div>

        <div class="form-group mb-3">
            <label for="edit_phone">Phone</label>
            <input type="text" id="edit_phone" class="edit_phone form-control">
        </div>

        <div class="form-group mb-3">
            <label for="edit_role">Role</label>
            <input type="text" id="edit_role" class="edit_role form-control">
        </div>
      </div>

      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary update_employee">Save Changes</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Add Employee
        $(document).on('click', '.add_employee', function (e) {
            e.preventDefault();
            var data = {
                'name': $(".names").val(),
                'email': $(".email").val(),
                'phone': $(".phone").val(),
                'role': $(".role").val(),
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // For Inserting the data into the database
            $.ajax({
                type: "POST",
                url: "/employees",
                data: data,
                dataType: "json",
                success: function (response) {
                    if (response.status == 400) {
                        $("#error_list").html("");
                        $("#error_list").addClass("alert alert-danger");

                        $.each(response.errors, function (key, err_values) {
                            $("#error_list").append('<li>' + err_values + "</li>");
                        });
                    } else {
                        $("#error_list").html("");
                        $("#error_list").addClass("alert alert-success");
                        $("#error_list").append(response.message);
                        $("#add_employee").modal('hide');
                        $("#add_employee").find('input').val('');

                        var employeeRow = '<tr data-id="' + response.employee.id + '">' +
                            '<td>' + (response.employee.id) + '</td>' +
                            '<td>' + response.employee.names + '</td>' +
                            '<td>' + response.employee.email + '</td>' +
                            '<td>' + response.employee.phone + '</td>' +
                            '<td>' + response.employee.role + '</td>' +
                            '<td>' +
                            '<a href="" class="btn btn-info edit_employee" data-id="' + response.employee.id + '" data-name="' + response.employee.name + '" data-email="' + response.employee.email + '" data-phone="' + response.employee.phone + '" data-role="' + response.employee.role + '" data-bs-toggle="modal" data-bs-target="#edit_employee">Edit</a>' +
                            '<a href="" class="btn btn-danger delete_employee" data-id="' + response.employee.id + '">Delete</a>' +
                            '</td>' +
                            '</tr>';

                        $("#employee_list").append(employeeRow);
                    }
                    setTimeout(function () {
                $("#error_list").removeClass("alert alert-success");
                $("#error_list").html("");
            }, 2000);
                }
            });
        });

        // Edit Employee
        $(document).on('click', '.edit_employee', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var phone = $(this).data('phone');
            var role = $(this).data('role');

            $("#edit_names").val(name);
            $("#edit_email").val(email);
            $("#edit_phone").val(phone);
            $("#edit_role").val(role);

            $(".update_employee").attr('data-id', id);

            $("#edit_employee").modal('show');

            
        });

        // Update Employee
        $(document).on('click', '.update_employee', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var data = {
                'names': $("#edit_names").val(),
                'email': $("#edit_email").val(),
                'phone': $("#edit_phone").val(),
                'role': $("#edit_role").val(),
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            console.log({id})
            // For Updating The Data of the table
            $.ajax({
                type: "PUT",
                url: "/employees/" + id,
                data: data,
                dataType: "json",
                success: function (response) {
                    if (response.status == 400) {
                        $("#error_list").addClass("alert alert-danger");

                        $.each(response.errors, function (key, err_values) {
                            $("#error_list").append('<li>' + err_values + "</li>");
                        });
                    } else {
                        $("#error_list").html("");
                        $("#error_list").addClass("alert alert-success");
                        $("#error_list").append(response.message);
                        $("#edit_employee").modal('hide');
                        $("#edit_employee").find('input').val('');

                        var employeeRow = '<tr data-id="' + id + '">' +
                            '<td>' + id + '</td>' +
                            '<td>' + response.employee.names + '</td>' +
                            '<td>' + response.employee.email + '</td>' +
                            '<td>' + response.employee.phone + '</td>' +
                            '<td>' + response.employee.role + '</td>' +
                            '<td>' +
                            '<a href="" class="btn btn-info edit_employee" data-id="' + id + '" data-name="' + response.employee.name + '" data-email="' + response.employee.email + '" data-phone="' + response.employee.phone + '" data-role="' + response.employee.role + '" data-bs-toggle="modal" data-bs-target="#edit_employee">Edit</a>' +
                            '<a href="" class="btn btn-danger delete_employee" data-id="' + id + '">Delete</a>' +
                            '</td>' +
                            '</tr>';

                        $("tr[data-id='" + id + "']").replaceWith(employeeRow);
                    }
                    setTimeout(function () {
                $("#error_list").removeClass("alert alert-success");
                $("#error_list").html("");
            }, 2000);
                }
            });
        });

        // Delete Employee
        $(document).on('click', '.delete_employee', function (e) {
            e.preventDefault();
            var id = $(this).data('id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // For Deleting The Data
            $.ajax({
                type: "DELETE",
                url: "/employees/" + id,
                dataType: "json",
                success: function (response) {
                    $("#error_list").html("");  
                    $("#error_list").addClass("alert alert-success");
                    $("#error_list").append(response.message);
                    $("tr[data-id='" + id + "']").remove();
                }
            });
            setTimeout(function () {
                $("#error_list").removeClass("alert alert-success");
                $("#error_list").html("");
            }, 2000);
        });
    });
</script>

<!-- Code To print Essential -->    
<script>
    function printEssentialData() {
        var data = document.querySelectorAll('.display_off');

        // Hide elements with the class 'display_off'
        data.forEach((element) => {
            element.style.display = "none";
        });

        var printContents = document.getElementById("employee_list").innerHTML;

        var originalContents = document.body.innerHTML;
        document.body.innerHTML = "<table style='border: 1px solid black;'>" + printContents + "</table>";

        // Add CSS styles to customize the table appearance while printing
        var style = document.createElement('style');
        style.innerHTML = `
            table {
                border-collapse: collapse;
                width: 100%;
            }
            
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            
            th {
                background-color: #f2f2f2;
            }
        `;
        document.head.appendChild(style);

        window.print();

        // Restore the original content and remove the added styles
        document.body.innerHTML = originalContents;
        document.head.removeChild(style);

        // Show the hidden elements after printing
        data.forEach((element) => {
            element.style.display = "";
        });
    }
</script>




<!-- <script>
    $(document).ready(function () {

        // Function to update the timer value
        function updateTimer() {
            var timerElement = $("#timer");
            var currentTimerValue = parseInt(timerElement.text());

            // Increment the timer value
            timerElement.text(currentTimerValue + 1);
        }
        // Update the timer every second (1000 milliseconds)
        setInterval(updateTimer, 1000);

        // Other code...
    });
</script> -->
@endsection