<!-- Modal -->
<form action="{{ route('DailyPayment_add') }}" onSubmit="return checkSubmit()">
    <div class="modal fade" id="addShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="min-height: 350px;max-height: 700px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">給引き登録</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal_display">
                        <div>
                            <span class="form_label_requied">必須</span>
                            <b>日付：</b>
                        </div>
                        <div>
                            <input name="date" type="date" id="today">
                            <p id="validation_date" class="err_msg"></p>
                        </div>
                    </div>

                    <div class="modal_display">
                        <div>
                            <span class="form_label_requied">必須</span>
                            <b>従業員：</b>
                        </div>
                        <div>
                            <select name="crew_id">
                                @foreach ($crew as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <p id="validation_crew_id" class="err_msg"></p>
                        </div>
                    </div>

                    <div class="modal_display">
                        <div>
                            <span class="form_label_requied">必須</span>
                            <b>金額：</b>
                        </div>
                        <div>
                            <input name="bill" type="text" id="bill"> 円
                            <p id="validation_bill" class="err_msg"></p>
                        </div>
                    </div>

                    <div class="modal_display">
                        <div>
                            <span class="form_label_requied">必須</span>
                            <b>コメント：</b>
                        </div>
                        <div>
                            <div>
                                <select name="comment_id" onchange="selectChange()">
                                    <option value="0">日払い</option>
                                    <option value="1">その他</option>
                                </select>
                            </div>
                            <div class="comment_container">
                                <textarea name="comment" rows="2" cols="10" class="comment" placeholder="〇〇様プレゼント代"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
                    <button type="submit" class="btn btn_color btn-primary">登録</button>
                </div>
            </div>
        </div>
    </div>
</form>

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

    .comment_container {
        margin: 10px 0 0 0 !important;
        display: none;
    }

    .comment {
        width: 220px!important;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding-left: 1em;
        padding-right: 1em;
        flex: 1;
        background: #eaedf2;
        font-size: 18px;
    }

    .modal_display div:nth-child(2) input {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding-left: 1em;
        padding-right: 1em;
        height: 38px;
        flex: 1;
        width: 100%;
        max-width: 220px;
        background: #eaedf2;
        font-size: 18px;
    }

    .form_label_requied {
        border-radius: 6px;
        margin-right: 8px;
        padding-top: 3px;
        padding-bottom: 3px;
        width: 38px;
        display: inline-block;
        text-align: center;
        background: #e15a5a;
        color: #fff;
        font-size: 14px;
    }

    .modal_display div:nth-child(2) select {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding-left: 1em;
        padding-right: 1em;
        height: 38px;
        flex: 1;
        min-width: 100px;
        background: #eaedf2;
        font-size: 18px;
        text-align: center;
    }

    .modal_display div:nth-child(1) b {
        line-height: 38px;
    }

    .btn_color {
        background-color: rgb(180, 60, 60) !important;
        border-color: rgb(180, 60, 60) !important;
    }

    .btn_color:hover {
        background-color: rgb(160, 48, 48);
        border-color: rgb(160, 48, 48);
    }
</style>
