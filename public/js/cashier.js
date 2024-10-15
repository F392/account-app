//modal
$(document).ready(function () {
    $("body").on("click", "#daily_cashier", function () {
        var URL = $(this).data("url") + "?date=" + $(this).data("today");
        $.get(URL, function (data) {
            $("#dailyShowModal").modal("show");
            //バリデーション初期化
            $("#validation_ex")[0].textContent = "";
            //モーダルのヘッダーとhidden
            $('#exampleModalLabel')[0].textContent = data[0]['date'];
            $('input[name="date"]')[0].value = data[0]['date'];
            //営業前レジ金
            $('input[name="ex_cashier_bill"]')[0].value = data[0]['ex_cashier_bill'].toLocaleString();
            //現金売上
            $('.cash_bill')[0].innerHTML = Number(data[1]).toLocaleString();
            //提携業社
            $('.supplier_bill')[0].innerHTML = Number(data[2]).toLocaleString();
            //提携業社
            $('.daily_payment')[0].innerHTML = Number(data[3]).toLocaleString();
            //営業後レジ金
            $('.cashier_bill')[0].innerHTML = Number(data[4]).toLocaleString();
        });
    });
});

//営業前レジ金入力時
$('input[name="ex_cashier_bill"]')[0].addEventListener("change", (e) => {
    if (isNaN($('input[name="ex_cashier_bill"]')[0].value.replace(",", ""))) {
        $("#validation_ex")[0].textContent = "※半角数字";
        return;
    }else{
        $("#validation_ex")[0].textContent = "";
    }
    if ($('input[name="ex_cashier_bill"]')[0].value) {
        var new_cashier_sum = Number($('input[name="ex_cashier_bill"]')[0].value.replace(/,/g, ""))
        + Number($('.cash_bill')[0].textContent.replace(/,/g, ""))
        - Number($('.supplier_bill')[0].textContent.replace(/,/g, ""))
        - Number($('.daily_payment')[0].textContent.replace(/,/g, ""));
        //営業後レジ金を書き換える
        $('.cashier_bill')[0].innerHTML = new_cashier_sum.toLocaleString();
        //営業前レジ金にカンマ
        $('input[name="ex_cashier_bill"]')[0].value = Number($('input[name="ex_cashier_bill"]')[0].value.replace(/,/g, "")).toLocaleString();
    }
});

function checkSubmit(){
    let ex_cashier_bill = $('input[name="ex_cashier_bill"]')[0].value;
    let isRight = true;

    console.log(Number($(".cashier_bill")[0].textContent.replace(/,/g, "")));

    //メールアドレス項目のチェック
    if (!ex_cashier_bill) {
        $("#validation_ex")[0].textContent = "※入力必須";
        isRight = false;
    }
    if(Number($(".cashier_bill")[0].textContent.replace(/,/g, "")) < 0){
        $("#validation_cashier")[0].textContent = "※レジ金が不足しています。";
        isRight = false;
    }

    return isRight;
}

//フラッシュメッセージ
setTimeout(function() {
    $(".flash-success,.flash-error")
        .fadeOut(3000)
        .queue(function() {
            this.remove();
        });
}, 1000);

