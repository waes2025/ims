<!-- Edit Customer Modal -->
<div class="modal fade" id="customerEditModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCustomerForm">
                <div class="modal-body">
                    <input type="hidden" id="editCustomerId">
                    <div class="mb-3">
                        <label for="editCustomerName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editCustomerName" name="name" required>
                    </div> 
                    <div class="mb-3">
                        <label for="editCustomerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editCustomerEmail" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="editCustomerMobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editCustomerMobile" name="mobile">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="customerEditSaveBtn" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
       async function getCustomerInfo(id){
            let URL = '{{ url("/api/v1/customers") }}/' + id;
            let token = localStorage.getItem('token');

            try{
                let response = await axios.get(URL, {
                    headers: { Authorization: 'Bearer ' + token }
                });
                let data = response.data['data'];
                if(data){
                    document.getElementById('editCustomerId').value = data['id'] || id;
                    document.getElementById('editCustomerName').value = data['name'] || '';
                    document.getElementById('editCustomerEmail').value = data['email'] || '';
                    document.getElementById('editCustomerMobile').value = data['mobile'] || '';
                }
            }catch (err) {
                showErrorToast(getErrorMessage(err, 'Failed to load customer info.'));
            }
        }

        async function editCustomer(id){
            document.getElementById('editCustomerId').value = id;
            await getCustomerInfo(id);
            let modalEl = document.getElementById('customerEditModal');
            let modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        async function doEditCustomer(){
            let id = document.getElementById('editCustomerId').value;
            let name = document.getElementById('editCustomerName').value.trim();
            let email = document.getElementById('editCustomerEmail').value.trim();
            let mobile = document.getElementById('editCustomerMobile').value.trim();
            let saveBtn = document.getElementById('customerEditSaveBtn');

            let obj = {
                name: name,
                email: email || null,
                mobile: mobile
            };

            let URL = '{{ url("/api/v1/customers") }}' + '/' + id;
            let token = localStorage.getItem('token');

            saveBtn.disabled = true;

            try{
                let response = await axios.put(URL, obj, { headers: { Authorization: 'Bearer ' + token } });
                
                if(response.data && response.data['success']){
                    showSuccessToast(response.data.message || 'Customer updated successfully.');
                    let modalEl = document.getElementById('customerEditModal');
                    let modal = window.bootstrap.Modal.getInstance(modalEl);
                    if(modal) modal.hide();
                    document.getElementById('editCustomerForm').reset();
                    document.getElementById('editCustomerId').value = '';
                    if(typeof getCustomers === 'function') getCustomers();
                } else {
                    showErrorToast(getErrorMessage(null, 'Failed to update customer.'));
                }   
            }catch (err) {
                showErrorToast(getErrorMessage(err, 'Failed to update customer. Please try again.'));
            } finally {
                saveBtn.disabled = false;
            }
        }

        document.getElementById('editCustomerForm').addEventListener('submit', function(e){
            e.preventDefault();
            doEditCustomer();
        });
    </script>    
@endpush