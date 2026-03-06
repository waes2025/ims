@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>Customer List</span>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            <i class="bi bi-plus-lg me-1"></i> Add Customer
        </button>
    </div>
    <div class="card-body"> 
        <div class="table-responsive">
            <table id="customersTable" class="table table-hover align-middle">
                <thead class="table-light">
                <tr>
                    <th style="width: 70px;">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th style="width: 130px;">Created</th>
                    <th class="text-end" style="width: 160px;">Actions</th>
                </tr>
                </thead>
                <tbody id="customersTableBody">
                <!-- Static demo data (design only) -->

                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection


@include('admin.customers.create')
@include('admin.customers.edit')   
@include('admin.customers.delete')

    @push('scripts')
        <script>
            getCustomers();

            async function getCustomers(){
                let URL = '{{ url("/api/v1/customers") }}';
                let token = localStorage.getItem('token');
                let tbody = document.getElementById('customersTableBody');

                try{
                    let response = await axios.get(URL,{
                        headers: { Authorization: 'Bearer ' + token }
                    });

                    let customers = response.data['data'] || [];
                    tbody.innerHTML = '';
                    customers.forEach((item)=>{
                        let created = item['created_at'] ? item['created_at'].substring(0, 10) : '-';
                        tbody.innerHTML += (`
                        <tr>
                            <td>${item['id']}</td>
                            <td>${item['name']}</td>
                            <td>${item['email'] || '-'}</td>
                            <td>${item['mobile'] || '-'}</td>
                            <td>${created}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-info editBtn" onclick="editCustomer(${item['id']})"><i class="bi bi-pencil"></i> Edit</button>
                                <button class="btn btn-sm btn-danger deleteBtn" onclick="deleteCustomer(${item['id']})"><i class="bi bi-trash"></i> Delete</button>
                            </td>
                        </tr>
                        `);
                    });
                }catch(error){
                    console.error('Error fetching customers:', error);
                    tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">Failed to load data</td></tr>`;
                }
            }
        </script>
    @endpush