//コメントにその他が入力されたら
function selectChange(){
    if($('select[name="comment_id"]')[0].value == 1){
        $('.comment_container')[0].style.display = 'block';
    }else{
        $('.comment_container')[0].style.display = 'none';
    }
}

function EditSelectChange(){
    if($('select[name="edit_comment_id"]')[0].value == 1){
        $('.edit_comment_container')[0].style.display = 'block';
    }else{
        $('.edit_comment_container')[0].style.display = 'none';
    }
}

//submit時の確認(バリデーション)
function checkSubmit() {
    let crew_id = $('select[name="crew_id"]')[0].value;
    let bill = $('input[name="bill"]')[0].value;
    let date = $('input[name="date"]')[0].value;
    let isRight = true;

    //メールアドレス項目のチェック
    if (!date) {
        $("#validation_date")[0].textContent = "※入力必須項目です。";
        isRight = false;
    }
    if (isNaN(bill.replace(",", ""))) {
        $("#validation_bill")[0].textContent = "※半角数字";
        isRight = false;
    }
    if (!bill) {
        $("#validation_bill")[0].textContent = "※入力必須項目です。";
        isRight = false;
    }

    return isRight;
}

function checkSubmit_2() {
    if (window.confirm("削除してよろしいですか？")) {
        return true;
    } else {
        return false;
    }
}

//submit時の確認(バリデーション)
function checkSubmit_3() {
    let edit_crew_id = $('select[name="edit_crew_id"]')[0].value;
    let edit_bill = $('input[name="edit_bill"]')[0].value;
    let edit_date = $('input[name="edit_date"]')[0].value;
    let isRight_2 = true;

    //メールアドレス項目のチェック
    if (!edit_date) {
        $("#validation_edit_date")[0].textContent = "※入力必須項目です。";
        isRight_2 = false;
    }
    if (isNaN(edit_bill.replace(",", ""))) {
        $("#validation_edit_bill")[0].textContent = "※半角数字";
        isRight_2 = false;
    }
    if (!edit_bill) {
        $("#validation_edit_bill")[0].textContent = "※入力必須項目です。";
        isRight_2 = false;
    }

    return isRight_2;
}

//modal
$(document).ready(function () {
    $("body").on("click", "#show-add", function () {
        var userURL = $(this).data("url");
        $.get(userURL, function () {
            $("#addShowModal").modal("show");
        });
    });
});

$(document).ready(function () {
    var today = new Date();
    today.setDate(today.getDate());
    var yyyy = today.getFullYear();
    var mm = ("0" + (today.getMonth() + 1)).slice(-2);
    var dd = ("0" + today.getDate()).slice(-2);
    document.getElementById("today").value = yyyy + "-" + mm + "-" + dd;
});

//金額が入力されてフォーカスが外れた後
$("#bill")[0].addEventListener("blur", (e) => {
    if ($("#bill")[0].value) {
        if (!isNaN($("#bill")[0].value)) {
            $("#bill")[0].value = parseInt(
                $("#bill")[0].value.replace(/,/g, "")
            ).toLocaleString();
        } else {
            $("#bill")[0].value = $("#bill")[0].value.toLocaleString();
        }
    }
});

//フラッシュメッセージ
setTimeout(function () {
    $(".flash-success,.flash-error")
        .fadeOut(3000)
        .queue(function () {
            this.remove();
        });
}, 1000);

//カレンダーの日にちクリック時にmodalm出現
$(document).ready(function () {
    $("body").on("click", "#daily_payment", function () {
        document.getElementById("tbody").innerHTML = "";
        var URL = $(this).data("url") + "?date=" + $(this).data("today");
        let colunms = ["crew_id", "bill", "comment"];
        $.get(URL, function (data) {
            $("#paymentShowModal").modal("show");
            //jsonデータからテーブル
            for (let i = 0; i < data[0].length; i++) {
                //trタグの生成
                const row = document.createElement("tr");
                //tdタグの生成
                const cell_1 = document.createElement("td");
                const cell_2 = document.createElement("td");
                const cell_3 = document.createElement("td");
                const cell_4 = document.createElement("td");
                //編集ボタンの生成
                var edit_button_cell = document.createElement("button");
                edit_button_cell.type = "button";
                edit_button_cell.value = data[0][i]["id"];
                edit_button_cell.innerHTML = "編集";
                edit_button_cell.id = "edit_button";
                edit_button_cell.setAttribute("onclick", "editbutton(this)");
                //削除ボタンの生成
                var delete_button_cell = document.createElement("button");
                delete_button_cell.type = "submit";
                delete_button_cell.value = data[0][i]["id"];
                delete_button_cell.innerHTML = "削除";
                delete_button_cell.id = "delete_button";
                delete_button_cell.name = "id";
                //名前の生成
                var crew_name = data[1].find(
                    ({ id }) => id == data[0][i]["crew_id"]
                );
                var crew_cell = document.createTextNode(crew_name["name"]);
                //金額の生成
                var bill_cell = document.createTextNode(data[0][i]["bill"].toLocaleString()+' 円');
                //コメントの生成
                if (data[0][i]["comment"]['comment_id'] == 0) {
                    var comment_cell = document.createTextNode('日払い');
                } else {
                    if(data[0][i]["comment"]['comment'] == null){
                        var comment_cell = document.createTextNode('');
                    }else{
                        var comment_cell = document.createTextNode(data[0][i]["comment"]['comment']);
                    }
                }
                //ぶち込む
                cell_1.appendChild(crew_cell);
                cell_2.appendChild(bill_cell);
                cell_3.appendChild(comment_cell);
                cell_4.appendChild(edit_button_cell);
                cell_4.appendChild(delete_button_cell);
                row.appendChild(cell_1);
                row.appendChild(cell_2);
                row.appendChild(cell_3);
                row.appendChild(cell_4);

                document.getElementById("tbody").appendChild(row);
            }
            document.getElementById("paymentModalLabel").textContent =
                data[0][0]["date"];
        });
    });
});

function editbutton(data){
    $.get('DailyPayment/EditList?id='+data.value, function (edit_list) {
        $("#editShowModal").modal("show");
        $('input[name="edit_date"]')[0].value = edit_list[0]['date'];
        $('input[name="edit_bill"]')[0].value = edit_list[0]['bill'].toLocaleString();
        for(let i=0;i<$('select[name="edit_crew_id"]')[0].getElementsByTagName('option').length;i++){
            if($('select[name="edit_crew_id"]')[0].getElementsByTagName('option')[i].value == edit_list[0]['crew_id']){
                $('select[name="edit_crew_id"]')[0].getElementsByTagName('option')[i].selected = true;
            }
        }
        for(let j=0;j<$('select[name="edit_comment_id"]')[0].getElementsByTagName('option').length;j++){
            if($('select[name="edit_comment_id"]')[0].getElementsByTagName('option')[j].value == edit_list[0]['comment']['comment_id']){
                $('select[name="edit_comment_id"]')[0].getElementsByTagName('option')[j].selected = true;
            }
        }
        //選択されているコメントがその他だった場合
        if(edit_list[0]['comment']['comment_id']==1){
            $('.edit_comment_container')[0].style.display = 'block';
        }else{
            $('.edit_comment_container')[0].style.display = 'none';   
        }
        $('textarea[name="edit_comment"]')[0].value = edit_list[0]['comment']['comment'];

        var input_current_id = document.createElement("input");
        input_current_id.type = "hidden";
        input_current_id.value = edit_list[0]['id'];
        input_current_id.setAttribute("name", "current_id");
        $('#edit_date')[0].appendChild(input_current_id);
    });
}