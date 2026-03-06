<!-- Delete Customer Modal -->
<div class="modal fade" id="customerDeleteModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCustomerModalLabel">Delete Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteCustomerForm">
                <div class="modal-body">
                    <input type="hidden" id="deleteCustomerId">
                    <p>Are you sure you want to delete this customer?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="customerDeleteConfirmBtn" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Delete Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function deleteCustomer(id){
            document.getElementById('deleteCustomerId').value = id;
            let modalEl = document.getElementById('customerDeleteModal');
            let modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
        async function doDeleteCustomer(){
            let id = document.getElementById('deleteCustomerId').value.trim();
            let deleteBtn = document.getElementById('customerDeleteConfirmBtn');

            let URL = '{{ url("/api/v1/customers") }}/' + id;
            let token = localStorage.getItem('token');

            deleteBtn.disabled = true;

            try {
                let response = await axios.delete(URL, { headers: { Authorization: 'Bearer ' + token } });

                if (response.data && response.data.success) {
                    showSuccessToast(response.data.message || 'Customer deleted successfully.');
                    let modalEl = document.getElementById('customerDeleteModal');
                    let modal = window.bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    document.getElementById('deleteCustomerId').value = '';
                    if (typeof getCustomers === 'function') getCustomers();
                } else {
                    showErrorToast(getErrorMessage(null, 'Failed to delete customer.'));
                }
            } catch (err) {
                showErrorToast(getErrorMessage(err, 'Failed to delete customer. Please try again.'));
            } finally {
                deleteBtn.disabled = false;
            }
        }

        document.getElementById('deleteCustomerForm').addEventListener('submit', function(e){
            e.preventDefault();
            doDeleteCustomer();
        });
    </script>
@endpush