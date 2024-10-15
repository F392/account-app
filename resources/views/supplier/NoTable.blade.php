<div class="modal" tabindex="-1">
    <form action="{{ route('supplier_save') }}" method="POST">
      @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">提携業社登録</h5>
                </div>
                <div class="modal-body">
                  <div class="clone_target">
                    <span>業者名：</span>
                    <input name="supplier_name[]" type="text">
                  </div>
                  <div class="clone_target">
                    <span>業者名：</span>
                    <input name="supplier_name[]" type="text">
                  </div>
                  <div class="clone_target">
                    <span>業者名：</span>
                    <input name="supplier_name[]" type="text">
                  </div>
                </div>
                <div class="modal-footer">
                  <div style="text-align: right" onclick="insert_row()">
                    <span class="dli-plus"><span></span></span>
                </div>
                    <button type="submit" class="btn btn-primary">登録</button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>

/*modal*/
.modal {
    display: block !important;
    z-index: 1 !important;
    position: relative !important;
    background-color: #00000013;
}
</style>