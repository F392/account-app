<!-- Modal -->
    <div class="modal fade" id="searchShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">検索条件</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal_display">
                        <div>
                            <span><strong>名前(カナ):</strong></span>
                        </div>
                        <div>
                            <input id="add_kana" type="text">
                        </div>
                    </div>
                    <div class="modal_display">
                        <div>
                            <span><strong>会社名:</strong></span>
                        </div>
                        <div>
                            <input id="add_company" type="text">
                        </div>
                    </div>
                    <div class="modal_display">
                        <div>
                            <span><strong>担当:</strong></span>
                        </div>
                        <div>
                            <select id="add_crew">
                                <option></option>
                                <option value='0'>店</option>
                                @foreach ($crew_names as $crew_name)
                                    <option value="{{ $crew_name->id }}">{{ $crew_name->名前 }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
                    <button
                    class="btn btn-secondary btn_color" 
                    href="javascript:void(0)"
                    id="show-search-list"
                    data-url="{{ route('bill_add') }}"
                    >
                    検索</button>
                </div>
            </div>
        </div>
    </div>
    
<style>
    .modal_display {
        display: flex;
        margin-bottom: 20px;
    }

    .modal_display div:nth-child(1) {
        width: 35%;
        text-align: right
    }

    .modal_display div:nth-child(2) {
        margin-left: 20px;
    }

    select {
        padding: 4px;
        text-align-last: center;
    }

    .btn_color {
        background-color: rgb(180, 60, 60);
        border-color: rgb(180, 60, 60);
    }

    .btn_color:hover {
        background-color: rgb(160, 48, 48);
        border-color: rgb(180, 60, 60);
    }
</style>
