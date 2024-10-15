<!-- Modal -->
<form action="{{ route('DailyPayment_delete') }}" method="POST" onSubmit="return checkSubmit_2()">
    @csrf
    <div class="modal fade" id="paymentShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table>
                        <tr>
                            <th>名前</th>
                            <th>金額</th>
                            <th>コメント</th>
                            <th>削除</th>
                        </tr>
                        <tbody id="tbody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>

                </div>
            </div>
        </div>
    </div>
</form>

<style>
    table {
        width: 100%;
    }

    table tr th:nth-child(4) {
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        text-align: center;
        width: 100px;
    }

    table tr th:nth-child(1),
    table tr th:nth-child(2),
    table tr th:nth-child(3) {
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        text-align: center;
    }

    table tr td {
        padding: 8px;
        border: 1px solid #ccc;
        text-align: center;
        white-space: nowrap;
    }

    table tr th {
        background-color: #eee;
    }

    #delete_button {
        font-size: 14px;
        color: #fff;
        background-color: rgb(180, 60, 60);
        border-color: rgb(180, 60, 60);
        padding: 3px 5px;
        margin-left: 4px;
    }

    #edit_button {
        font-size: 14px;
        color: #fff;
        background-color: rgb(60, 60, 180);
        border-color: rgb(60, 60, 180);
        padding: 3px 5px;
        margin-right: 4px;
    }
</style>
