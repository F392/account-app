<!-- Modal -->
<div class="modal fade" id="listModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">業者一覧</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="supplier_table">
                    <tr>
                        <th>業者名</th>
                        <th>編集</th>
                    </tr>
                    @foreach ($AllSuppliers as $AllSupplier)
                        <tr>
                            <td>{{ $AllSupplier->name }}</td>
                            <td>
                                <span class="edit_button"><button onclick="ToEdit({{$AllSupplier}})">編集</button></span>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
            </div>
        </div>
    </div>
</div>
