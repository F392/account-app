<!-- Modal -->
<form action="{{ route('cashier_save') }}" method="POST" onSubmit="return checkSubmit()">
    @csrf
    <div class="modal fade" id="dailyShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal_display">
                        <div>
                            <span><strong>営業前レジ金 :</strong></span>
                        </div>
                        <div>
                            <input name="ex_cashier_bill"> 円
                            <br><p id="validation_ex" class="err_msg"></p>
                        </div>
                    </div>
    
                    <div class="modal_display">
                        <div>
                            <span><strong>現金売上 :</strong></span>
                        </div>
                        <div>
                            <span class="cash_bill"></span><span> 円</span>
                        </div>
                    </div>
    
                    <div class="modal_display">
                        <div>
                            <span><strong>提携業社 :</strong></span>
                        </div>
                        <div>
                            <span class="supplier_bill"></span><span> 円</span>
                        </div>
                    </div>
    
                    <div class="modal_display">
                        <div>
                            <span><strong>日払い :</strong></span>
                        </div>
                        <div>
                            <span class="daily_payment"></span><span> 円</span>
                        </div>
                    </div>
    
                    <hr>
    
                    <div class="modal_display cashier_bill_container">
                        <div>
                            <span><strong>営業後レジ金 :</strong></span>
                        </div>
                        <div>
                            <span class="cashier_bill"></span><span> 円</span>
                        </div>
                    </div>
                    <p id="validation_cashier" class="err_msg"></p>
                    <input type="hidden" name="date">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
                    <button type="submit" class="btn btn_color btn-primary">保存</button>
                </div>
            </div>
        </div>
    </div>    
</form>

<style>
    .modal_display {
        display: flex;
        margin: 0 auto 20px auto;
        width: 70%;
        position: relative;
        right: 30px;
    }

    .modal_display div {
        width: 50%;
        text-align: right;
        margin: auto;

    }

    /*  .modal_display div:nth-child(2) {
        margin-left: 20px;
    } */

    .modal_display input {
        text-align: right;
        padding-right: 10px;
        width: 100px;
    }

    .btn_color {
        background-color: rgb(180, 60, 60) !important;
        border-color: rgb(180, 60, 60) !important;
    }

    .btn_color:hover {
        background-color: rgb(160, 48, 48);
        border-color: rgb(160, 48, 48);
    }

    hr {
        width: 300px;
        margin-left: auto;
        margin-right: auto;
    }

    hr:not([size]) {
        height: 1.8px;
    }

    .cashier_bill_container {}

    .cashier_bill{
        color: red;
        font-size: large;
        font-weight: bold;
    }

    .err_msg{
        font-size: small;
        color: red;
        text-align: center;
        margin-bottom: 0;
    }
</style>
