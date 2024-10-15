<!-- Modal -->
<form action="{{ route('supplier_edit') }}" method="POST">
    @csrf
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">提携業者編集</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ModalBody">
                    <div>
                        <span>業者名：</span>
                        <input id="supplier_name" name="supplier_name" type="text">
                    </div>
                    <div>
                        <span>表示可否：</span>
                        <select name="delete_flag" id="delete_flag">
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>
        </div>
    </div>
</form>
