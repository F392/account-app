<!-- Modal -->
<form action="{{ route('customer_save') }}" method="POST" onSubmit="return checkSubmit()">
    @csrf
    <div class="modal fade" id="editShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">売上計上に追加</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="edit-modal-body"
                    style="overflow-y: auto;-webkit-overflow-scrolling: touch;padding-top:0;">
                    <div class="edit_container">
                        <span class="form_label_requied">必須</span>
                        <b>来店日&nbsp;:</b>
                        <input id="edit_date" name="date" type="date">
                        <p class="err_date"></p>
                    </div>
                    <div class="edit_container">
                        <span class="form_label_requied">必須</span>
                        <b>支払い担当&nbsp;:</b>
                        <select id="edit_crew_id" name="crew_id">
                            <option value="0">店</option>
                            @foreach ($crew as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <p class="err_crew_id"></p>
                    </div>
                    <div class="edit_container" style="justify-content: left; display:flex;">
                        <div style="margin-right: 10px;">
                            <span class="form_label_requied">必須</span>
                            <b>売上(税抜)</b>
                            <div style="margin-top: 10px;">
                                <input id="edit_notax_bill" name="notax_bill" type="text">
                                <p class="err_notax_bill"></p>
                            </div>
                        </div>
                        <div>
                            <span class="form_label_requied">必須</span>
                            <b>売上(税込)</b>
                            <div style="margin-top: 10px;">
                                <input id="edit_intax_bill" name="intax_bill" type="text">
                                <p class="err_intax_bill"></p>
                            </div>
                        </div>
                    </div>
                    <div class="edit_container" style="justify-content: center; display:flex;">
                        <div style="margin-right: 10px;">
                            <span class="form_label_requied">必須</span>
                            <b>現金</b>
                            <div style="margin-top: 10px;">
                                <input id="edit_cash_bill" name="cash_bill" type="text">
                                <p class="err_cash_bill"></p>
                            </div>
                        </div>
                        <div>
                            <span class="form_label_requied">必須</span>
                            <b>クレジット</b>
                            <div style="margin-top: 10px;">
                                <input id="edit_credit_bill" name="credit_bill" type="text">
                                <p class="err_credit_bill"></p>
                            </div>
                        </div>
                    </div>
                    <div class="edit_container mukei_container" style="justify-content: center; display:flex;">
                        <div style="margin-right: 10px;">
                            <span class="form_label_elective">任意</span>
                            <b>無形従業員</b>
                            <div style="margin-top: 10px;">
                                <select class="edit_mukei_crew_id" name="mukei_crew_id[]">
                                    <option value="">-----</option>
                                    @foreach ($crew as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <p class="err_mukei_crew_id"></p>
                            </div>
                        </div>
                        <div>
                            <span class="form_label_elective">任意</span>
                            <b>無形売上</b>
                            <div style="margin-top: 10px;">
                                <input class="edit_mukei_bill" name="mukei_bill[]" type="text">
                                <p class="err_mukei_bill"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="customer_bill_id" id="edit_customer_bill_id">
                <div class="modal-footer">
                    <div style="text-align: right" onclick="insert_row()">
                        <span class="dli-plus"><span></span></span>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
                    <button type="submit" class="btn btn_color btn-primary">保存</button>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    #edit-modal-body {
        width: 90%;
        margin: auto;
    }

    .edit_container {
        padding-top: 20px;
        padding-bottom: 20px;
        border-bottom: rgb(130, 130, 130, 0.4) solid 1px;
    }

    .edit_container b {
        letter-spacing: 0.05em;
        font-size: 18px;
    }

    .edit_container input[name="date"] {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding-left: 1em;
        padding-right: 1em;
        height: 38px;
        flex: 1;
        width: 100%;
        max-width: 200px;
        background: #eaedf2;
        font-size: 18px;
        text-align: center
    }

    .edit_container select[name="crew_id"] {
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

    .edit_container input[name="notax_bill"],
    .edit_container input[name="intax_bill"],
    .edit_container input[name="cash_bill"],
    .edit_container input[name="credit_bill"] {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding-left: 1em;
        padding-right: 1em;
        height: 38px;
        flex: 1;
        width: 100%;
        max-width: 200px;
        background: #eaedf2;
        font-size: 18px;
    }

    .edit_container input[name="mukei_bill[]"] {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding-left: 1em;
        padding-right: 1em;
        height: 38px;
        flex: 1;
        width: 100%;
        max-width: 200px;
        background: #eaedf2;
        font-size: 18px;
    }

    .edit_container select[name="mukei_crew_id[]"]{
        border: 1px solid #ddd;
        border-radius: 6px;
        padding-left: 1em;
        padding-right: 1em;
        height: 38px;
        flex: 1;
        width: 100%;
        width: 200px;
        background: #eaedf2;
        font-size: 18px;
        text-align: center
    }

    .err_date,
    .err_crew_id,
    .err_notax_bill,
    .err_intax_bill,
    .err_cash_bill,
    .err_credit_bill,
    .err_mukei_bill,
    .err_mukei_crew_id,
    .err_mukei_bill{
        color: red;
        text-align: center;
        font-size: small;
        margin-bottom: 0;
        width: 200px;
    }
</style>
