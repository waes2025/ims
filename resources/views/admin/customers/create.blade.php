<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCustomerForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="customerName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="customerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="customerEmail" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="customerMobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="customerMobile" name="mobile">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Add Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.getElementById('addCustomerForm').addEventListener('submit', async function(e){
            e.preventDefault();
            let name = document.getElementById('customerName').value.trim();
            let email = document.getElementById('customerEmail').value.trim();
            let mobile = document.getElementById('customerMobile').value.trim();
            let saveBtn = this.querySelector('button[type="submit"]');

            if(!name){
                alert('Name is required');
                return;
            }

            if(!mobile){
                alert('Mobile number is required');
                return;
            }

            let obj = { name, email: email || null, mobile };

            let URL = '{{ url("/api/v1/customers") }}';
            let token = localStorage.getItem('token');

            saveBtn.disabled = true;

            try{
                let response = await axios.post(URL, obj, {
                    headers: { Authorization: 'Bearer ' + token }
                });

                if(response.data && response.data.success){
                    showSuccessToast(response.data.message || 'Customer created successfully');
                    let modalEl = document.getElementById('addCustomerModal');
                    let modal = bootstrap.Modal.getInstance(modalEl);
                    if(modal) modal.hide();
                    document.getElementById('addCustomerForm').reset();
                    if(typeof getCustomers === 'function') getCustomers();
                } else {
                    showErrorToast(getErrorMessage(null,'Failed to create customer'));
                }
                
            }catch(error){
                showErrorToast(getErrorMessage(error,'Failed to create customer'));
            } finally {
                saveBtn.disabled = false;
            }
        });
    </script>    
@endpush