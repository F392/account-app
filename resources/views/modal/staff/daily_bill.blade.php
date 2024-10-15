<!-- Modal -->
<div class="modal fade" id="StaffDailyBillShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><span></span>日別売上</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="daily_table">
                    <tr>
                        <th>日付</th>
                        <th>売上</th>
                        <th>無形売上</th>
                        <th>給引き</th>
                        <th>給引き理由</th>
                    </tr>
                    <tbody id="daily_tbody"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
            </div>
        </div>
    </div>
</div>

<style>

    .daily_table{
        width: 100%
    }

    .daily_table tr th:nth-child(1){
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        text-align: center;
        /* テキスト左寄せ */
        background-color: #eee;
        /* グレーの背景色 */
        white-space: nowrap;
    }
    
    .daily_table tr th:nth-child(2),
    .daily_table tr th:nth-child(3),
    .daily_table tr th:nth-child(4){
        width: 20%;
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        text-align: center;
        /* テキスト左寄せ */
        background-color: #eee;
        /* グレーの背景色 */
        white-space: nowrap;
    }

    .daily_table tr th:nth-child(5){
        width: 40%;
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        text-align: center;
        /* テキスト左寄せ */
        background-color: #eee;
        /* グレーの背景色 */
        white-space: nowrap;
    }

    .daily_table tr td {
        padding: 8px;
        border: 1px solid #ccc;
        text-align: center;
        white-space: nowrap;
    }

    .daily_table tr td:nth-child(5) {
        padding: 8px;
        border: 1px solid #ccc;
        text-align: left;
        white-space: nowrap;
    }
</style>
