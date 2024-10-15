<!-- Modal -->
<form action="{{ route('supplier_save') }}" method="POST">
    @csrf
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">提携業者追加</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="clone_target">
                      <span>業者名：</span>
                      <input name="supplier_name[]" type="text">
                    </div>
                  </div>
                <div class="modal-footer">
                    <div style="text-align: right" onclick="insert_row()">
                        <span class="dli-plus"><span></span></span>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
                    <button type="submit" class="btn btn-primary">登録</button>
                </div>
            </div>
        </div>
    </div>
</form>
