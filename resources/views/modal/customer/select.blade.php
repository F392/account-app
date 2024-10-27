<!-- Modal -->
<div class="modal fade" id="selectShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 700px">
        <div class="modal-content" style="width: 700px">
            <div class="modal-header">
                <h5 class="modal-title" id="selecteModalLabel">お客様選択</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="select_table">
                    <tr>
                        <th>来店日</th>
                        <th>支払い担当</th>
                        <th>売上(税込)</th>
                        <th>無形</th>
                        <th>編集</th>
                    </tr>
                    <tbody id="select_tbody"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
            </div>
        </div>
    </div>
</div>


<style>
    .select_table{
        width: 100%
    }

    .select_table tr th:nth-child(1),
    .select_table tr th:nth-child(2),
    .select_table tr th:nth-child(3),
    .select_table tr th:nth-child(4) {
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        text-align: center;
        /* テキスト左寄せ */
        background-color: #eee;
        /* グレーの背景色 */
        white-space: nowrap;
    }

    .select_table tr th:nth-child(5) {
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

    .select_table tr td {
        padding: 8px;
        border: 1px solid #ccc;
        text-align: center;
        white-space: nowrap;
    }

    .select_table tr td:nth-child(3),
    .select_table tr td:nth-child(4){
        padding: 8px;
        border: 1px solid #ccc;
        text-align: left;
        white-space: nowrap;
    }

    .select_table tr th {
        position: sticky;
        top: 0;
        left: 0;
        border-top: none;
    }

    .select_table tr th::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .btn_color {
        background-color: rgb(180, 60, 60);
        border-color: rgb(180, 60, 60);
    }

    .btn_color:hover {
        background-color: rgb(160, 48, 48);
        border-color: rgb(180, 60, 60);
    }

    .addition{
        padding: 2px 5px;
    }
</style>
