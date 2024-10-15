<!-- Modal -->
<form action="{{ route('bill_add') }}">
    <div class="modal fade" id="addShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 1000px;">
            <div class="modal-content" style="min-height: 350px;max-height: 700px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">売上計上に追加</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="date_container">
                        <p>来店日を入力してください。</p>
                        <input class="date" type='date'>
                    </div>
                <div class="modal-body" style="overflow-y: auto;-webkit-overflow-scrolling: touch;padding-top:0;">
                    <table class="add_table">
                        <tr>
                            <th>名前</th>
                            <th>会社名</th>
                            <th>担当</th>
                            <th>ボトル</th>
                            <th>追加</th>
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
    .date_container {
        text-align: center;
        margin: 15px 0;
        border-collapse: collapse;
    }

    .date_container p {
        margin-bottom: 5px;
    }

    .date_container input {
        padding: 3px 10px;
        border-radius: 3px;
        border: 1px solid rgb(129, 129, 129);
        user-select: none;
    }

    .add_table tr th:nth-child(1),
    .add_table tr th:nth-child(2),
    .add_table tr th:nth-child(3),
    .add_table tr th:nth-child(4) {
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        text-align: center;
        /* テキスト左寄せ */
        background-color: #eee;
        /* グレーの背景色 */
        white-space: nowrap;
    }

    .add_table tr th:nth-child(5) {
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        text-align: center;
        /* テキスト左寄せ */
        width: 55px;
        background-color: #eee;
        /* グレーの背景色 */
        white-space: nowrap;
    }

    .add_table tr td {
        padding: 8px;
        border: 1px solid #ccc;
        text-align: center;
        white-space: nowrap;
    }

    .add_table tr th {
        position: sticky;
        top: 0;
        left: 0;
        border-top: none;
    }

    .add_table tr th::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
</style>