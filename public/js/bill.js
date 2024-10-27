//submit時の確認
function checkSubmit(key) {
    if (window.confirm("更新してよろしいですか？")) {
        return true;
    } else {
        return false;
    }
}

//無形追加機能
function insert_row(key) {
    //今、何人の無形があるか
    let count = $("." + "submit" + key)[0].getElementsByClassName(
        "clone_target"
    ).length;

    //複製する
    let elements = $("." + "submit" + key)[0].getElementsByClassName(
        "clone_target"
    )[count - 1];
    let copied = elements.cloneNode(true);

    //兄弟要素として追加
    elements.parentNode.insertBefore(copied, elements.nextElementSibling);
}

//税抜きのフォーカスが外れたら
for (let i = 0; i < document.getElementsByClassName("button").length; i++) {
    var notax_input = {};

    document.getElementById("notax_" + i).addEventListener("blur", (e) => {
        if (document.getElementById("notax_" + i).value && !document.getElementById("notax_" + i).value.match(/[^\x01-\x7E\xA1-\xDF]+/)) {
            //現金、クレジット、掛けの値を取得(int型で)
            notax_input[i] = parseInt(
                document.getElementById("notax_" + i).value.replace(/,/g, "")
            );
            //課税して繰り上げ
            var intax_input = Math.ceil((notax_input[i] * 1.25) / 1000) * 1000;
            //売上にぶち込む(カンマ付きのstring型で)
            document.getElementById("intax_" + i).value =
                intax_input.toLocaleString();
            //現金もカンマ付きに変換
            document.getElementById("notax_" + i).value =
                notax_input[i].toLocaleString();
        }
    });
}

//現金、クレジット、掛け、税込、掛けのフォーカスが外れたら
for (let i = 0; i < document.getElementsByClassName("button").length; i++) {
    var input_array = ["cash_", "credit_", "kake_","mukei_bill_","intax_"];

    for (let j = 0; j < input_array.length; j++) {
        document
            .getElementById(input_array[j] + i)
            .addEventListener("blur", (e) => {
                if (document.getElementById(input_array[j] + i).value) {
                    //カンマ付きに変換(複数回やられた時のためにカンマを取り除く処理が必要)
                    //半角チェック
                    if(!document.getElementById(input_array[j] + i).value.match(/^[^\x01-\x7E\uFF61-\uFF9F]+$/)){
                        document.getElementById(input_array[j] + i).value =
                        parseInt(
                            document.getElementById(input_array[j] + i).value.replace(/,/g, '')
                        ).toLocaleString();
                    }
                }
            });
    }
}

//modal
$(document).ready(function () {
    $("body").on("click", "#show-search", function () {
        var userURL = $(this).data("url");
        $.get(userURL, function () {
            $("#searchShowModal").modal("show");
        });
    });
});

//modal2
$(document).ready(function () {
    $("body").on("click", "#show-search-list", function () {
        //バリデーションがあれば削除
        if (document.getElementById("add_varidate")) {
            document.getElementById("add_varidate").remove();
        }
        //再検索に備えてtbodyの中を空にする
        document.getElementById("tbody").innerHTML = "";
        let input_kana = document.getElementById("add_kana").value;
        let input_company = document.getElementById("add_company").value;
        let input_crew = document.getElementById("add_crew").value;
        let colunms = ["name", "company", "crew_id", "bottle"];
        var URL =
            $(this).data("url") +
            "?kana=" +
            input_kana +
            "&company=" +
            input_company +
            "&crew=" +
            input_crew;
        $.get(URL, function (data) {
            //crewデータに店を追加
            data[1].push({ id: 0, name: "店" });
            $("#addShowModal").modal("show");
            //jsonデータからテーブル
            for (let i = 0; i < data[0].length; i++) {
                //trタグの生成
                const row = document.createElement("tr");
                for (let j = 0; j < 5; j++) {
                    //tdタグの生成
                    const cell = document.createElement("td");
                    //tdの中身
                    if (j === 2) {
                        var crew = data[1].find(
                            ({ id }) => id == data[0][i][colunms[j]]
                        );
                        var cellText = document.createTextNode(crew["name"]);
                    } else if (j === 4) {
                        var cellText = document.createElement("input");
                        cellText.classList.add("addition");
                        cellText.type = "button";
                        cellText.value = "追加";
                        cellText.id = "add_button";
                        cellText.setAttribute("data-value", data[0][i]["id"]);
                        cellText.setAttribute("onclick", "pushbutton(this)");
                    } else {
                        if(data[0][i][colunms[j]]){
                            var cellText = document.createTextNode(
                                data[0][i][colunms[j]]
                            );
                        }else{
                            var cellText = document.createTextNode('');
                        }
                    }

                    //ぶち込む
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                }
                document.getElementById("tbody").appendChild(row);
            }
        });
    });
});

function pushbutton(id) {
    isRight = true;
    //来店日を取得
    if(!$('.date')[0].value){
        $('.err_date')[0].innerHTML = '来店日を入力してください。';
        isRight = false;
    }
    if(!$('.people_number')[0].value){
        $('.err_people_number')[0].innerHTML = '来店人数を入力してくだいさい。';
        isRight = false;
    }
    if(!isRight){
        return;
    }
    //idを取得
    const customer_id = id.dataset.value;
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "bill/save_new_bill",
        method: "POST",
        data: { id: $.trim(customer_id), date: $.trim($('.date')[0].value), people_number: $.trim($('.people_number')[0].value)},
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
}
//フラッシュメッセージ
setTimeout(function() {
    $(".flash-success,.flash-error")
        .fadeOut(3000)
        .queue(function() {
            this.remove();
        });
}, 1000);

