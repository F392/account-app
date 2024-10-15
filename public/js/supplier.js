//phpの変数を取得(別ファイルにjs描きたいのでので仕方なく面倒だけど)
var $staff = $("#supplier");
var json_days = JSON.parse($staff.attr("data-allDays"));
var json_suppliers = JSON.parse($staff.attr("data-suppliers"));
var url = $staff.attr("data-url");

//半角文字かどうか判定する関数
function isHalfWidth(str) {
    // 半角文字のみを許可する正規表現
    const halfWidthPattern = /^[\x20-\x7E]*$/;
    return halfWidthPattern.test(str);
}


window.onload = function () {
    //tableの幅に合わせてヘッダーの幅も変更
    let width = document.getElementById('supplier_table').clientWidth;
    if(width>1200){
        document.getElementsByClassName('header')[0].style.width = width + 260 + 'px';
        document.getElementsByClassName('checkbox_alert')[0].style.width = width + 260 + 'px';
        document.getElementById('supplier_table').style.width = '100%';
    }
    //セルのフォーカスが外れたら
    for (let i = 0; i < json_days.length; i++) {
        for (let j = 0; j < json_suppliers.length; j++) {
            let input = document.getElementById("indvBill_" + json_days[i].slice(-2).trim() + "_" + $.trim(json_suppliers[j]["id"]));
            let cash_flag = document.getElementById("cash_flag_" + json_days[i].slice(-2).trim() + "_" + $.trim(json_suppliers[j]["id"]));
            let td = document.getElementById(json_days[i].slice(-2).trim() + "_" + $.trim(json_suppliers[j]["id"]));
            let checkbox;
            //inputの値が半角じゃなかったらreturn
            //セルのファーカスが外れたら
            input.addEventListener("blur", (e) => {
                if (cash_flag.checked) {
                    checkbox = 1;
                } else {
                    checkbox = 2;
                }
                //全角だったらreturnする
                if (!isHalfWidth(input.value)) {
                    if(td.getElementsByTagName('span').length >= 3){
                        return;
                    }
                    var alart = document.createElement("span");
                    alart.textContent = "※半角";
                    alart.style.color = "red";
                    alart.style.fontSize = "small";
                    td.appendChild(alart);
                    return;
                }
                $.ajaxSetup({
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });
                $.ajax({
                    url: $.trim(url),
                    method: "POST",
                    data: {
                        id: $.trim(json_suppliers[j]["id"]),
                        date: $.trim(json_days[i]),
                        value: $.trim(input.value),
                        cash_flag: checkbox,
                    },
                    dataType: "text",
                })
                    .done(function () {
                        location.reload(); /* ajaxの通信に成功した場合の処理 */
                    })
                    .fail(function (XMLHttpRequest, textStatus, errorThrown) {
                        /* ajaxの通信に失敗した場合エラー表示 */ console.log(
                            XMLHttpRequest.status
                        );
                        console.log(textStatus);
                        console.log(errorThrown);
                    });
            });
            //テェックボックスの値が変わったら
            cash_flag.addEventListener("change", (e) => {
                if (cash_flag.checked) {
                    checkbox = 1;
                } else {
                    checkbox = 2;
                }
                //全角だったらreturnする
                if (!isHalfWidth(input.value)) {
                    if(td.getElementsByTagName('span').length >= 3){
                        return;
                    }
                    var alart = document.createElement("span");
                    alart.textContent = "※半角";
                    alart.style.color = "red";
                    alart.style.fontSize = "small";
                    td.appendChild(alart);
                    return;
                }
                //inputに値があれば送信
                if (input.value) {
                    $.ajaxSetup({
                        headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                    });
                    $.ajax({
                        url: $.trim(url),
                        method: "POST",
                        data: {
                            id: $.trim(json_suppliers[j]["id"]),
                            date: $.trim(json_days[i]),
                            value: $.trim(input.value),
                            cash_flag: checkbox,
                        },
                        dataType: "text",
                    })
                        .done(function () {
                            location.reload(); /* ajaxの通信に成功した場合の処理 */
                        })
                        .fail(function (
                            XMLHttpRequest,
                            textStatus,
                            errorThrown
                        ) {
                            /* ajaxの通信に失敗した場合エラー表示 */
                            console.log(XMLHttpRequest.status);
                            console.log(textStatus);
                            console.log(errorThrown);
                        });
                }
            });
        }
    }

    //最下層に合計を表示する
    let total_sum = 0;
    for (let i = 0; i < json_suppliers.length; i++) {
        let each_indvBill = document.getElementsByClassName("indvBill_" + $.trim(json_suppliers[i]["id"]));
        let array = [];
        for (let j = 0; j < each_indvBill.length; j++) {
            array.push(parseFloat(each_indvBill[j].value.replace(/,/g, "")));
        }
        let total = array.reduce(function (sum, element) {
            return sum + element;
        }, 0);
        let each_sum = document.getElementById(
            "each_sum_" + $.trim(json_suppliers[i]["id"])
        );
        each_sum.innerHTML = total.toLocaleString();
        total_sum += total;
    }
    let total = document.getElementById("total_sum");
    total.innerHTML = total_sum.toLocaleString();
};

//提携企業追加
function insert_row() {
    //今、何人の無形があるか
    let count = document.getElementsByClassName("clone_target").length;

    //複製する
    let elements = document.getElementsByClassName("clone_target")[count - 1];
    let copied = elements.cloneNode(true);
    copied.getElementsByTagName("input")[0].value = "";

    //兄弟要素として追加
    elements.parentNode.insertBefore(copied, elements.nextElementSibling);
}

//modal(add)
$(document).ready(function () {
    $("body").on("click", "#addsupplier", function () {
        $("#addModal").modal("show");
    });
});

//modal(list)
$(document).ready(function () {
    $("body").on("click", "#listsupplier", function () {
        $("#listModal").modal("show");
    });
});

function ToEdit(supplier){
    document.getElementById('supplier_name').value = supplier['name'];
    //初期化
    document.getElementById('delete_flag').innerHTML = '';
    if(supplier['delete_flag'] == 1){
        let option = document.createElement("option");
        let option_2 = document.createElement("option");
        option.value = '';
        option.innerHTML = '表示';
        option_2.value = 1;
        option_2.innerHTML = '非表示';
        option_2.selected = true;
        document.getElementById('delete_flag').appendChild(option);
        document.getElementById('delete_flag').appendChild(option_2);
    }else{
        let option = document.createElement("option");
        let option_2 = document.createElement("option");
        option.value = '';
        option.innerHTML = '表示';
        option_2.value = 1;
        option_2.innerHTML = '非表示';
        option.selected = true;
        document.getElementById('delete_flag').appendChild(option);
        document.getElementById('delete_flag').appendChild(option_2);
    }
    //idを追加
    let input_id = document.createElement("input");
    input_id.value = supplier['id'];
    input_id.setAttribute("type", "hidden");
    input_id.setAttribute("name", "supplier_id");
    document.getElementsByClassName('ModalBody')[0].appendChild(input_id);
    //保存後そのページに留まりたいのでパラメータも一緒に送る
    // URLを取得
    let url = new URL(window.location.href);
    // URLSearchParamsオブジェクトを取得
    let params = url.searchParams;
    let year = document.createElement("input");
    year.value = params.get('year');
    year.setAttribute("type", "hidden");
    year.setAttribute("name", "year");
    document.getElementsByClassName('ModalBody')[0].appendChild(year);
    let month = document.createElement("input");
    month.value = params.get('month');
    month.setAttribute("type", "hidden");
    month.setAttribute("name", "month");
    document.getElementsByClassName('ModalBody')[0].appendChild(month);
    $("#listModal").modal('hide');
    $("#editModal").modal("show");
}