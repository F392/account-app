function search(){
    var name = $('#customer_name')[0].value;
    var kana = $('#customer_kana')[0].value;
    var company = $('#customer_company')[0].value;
    var crew_id = $('#customer_crew_id')[0].value;
    var date = $('#customer_date')[0].value;
    var url = $('#customer_url')[0].value;
    var newURL = url + '?name=' + name + '&kana=' + kana + '&company=' + company + '&crew_id=' + crew_id + '&date=' + date ;
    $.get(newURL, function (data) {
        $("#searchShowModal").modal("show");
        //初期化
        document.getElementById("search_tbody").innerHTML = '';
            //jsonデータからテーブル
            for (let i = 0; i < data[0].length; i++) {
                //trタグの生成
                const row = document.createElement("tr");
                for (let j = 0; j < 5; j++) {
                    //tdタグの生成
                    const cell = document.createElement("td");
                    //tdの中身
                    if(j === 0){
                        if(data[0][i]['name']){
                            var cellText = document.createTextNode(data[0][i]['name']);
                        }else{
                            var cellText = document.createTextNode('');
                        }
                    }else if (j === 1) {
                        if(data[0][i]['company']){
                            var cellText = document.createTextNode(data[0][i]['company']);
                        }else{
                            var cellText = document.createTextNode('');
                        }
                    }else if(j === 2) {
                        var crew = data[1].find(({ id }) => id == data[0][i]['crew_id']);
                        if(crew["name"]){
                            var cellText = document.createTextNode(crew["name"]);
                        }else{
                            var cellText = document.createTextNode('');
                        }
                    } else if(j === 3){
                        if(data[0][i]['bottle']){
                            var cellText = document.createElement("span");
                            cellText.innerText = data[0][i]['bottle']
                        }else{
                            var cellText = document.createTextNode('');
                        }
                    }else if (j === 4) {
                        var cellText = document.createElement("input");
                        cellText.classList.add("addition");
                        cellText.type = "button";
                        cellText.value = "選択";
                        cellText.id = "select_button";
                        cellText.setAttribute("onclick", "selectbutton("+ data[0][i]["id"] +")");
                    }

                    //ぶち込む
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                }
                document.getElementById("search_tbody").appendChild(row);
            }
    })
    
}

function selectbutton(customer_id){
    $("#searchShowModal").modal("hide");
    $.get('customer/select?customer_id=' + customer_id, function (data) {
        $("#selectShowModal").modal("show");
        //初期化
        document.getElementById("select_tbody").innerHTML = '';
        $('#selecteModalLabel')[0].innerHTML = data[0][0]['name'] + ' 様';
            //jsonデータからテーブル
            for (let i = 0; i < data[0].length; i++) {
                //trタグの生成
                const row = document.createElement("tr");
                for (let j = 0; j < 5; j++) {
                    //tdタグの生成
                    const cell = document.createElement("td");
                    //tdの中身
                    if(j === 0){
                        if(data[0][i]['date']){
                            var cellText = document.createTextNode(data[0][i]['date']);
                        }else{
                            var cellText = document.createTextNode('');
                        }
                    }else if (j === 1) {
                        var crew = data[1].find(({ id }) => id == data[0][i]['crew_id']);
                        if(crew["name"]){
                            var cellText = document.createTextNode(crew["name"]);
                        }else{
                            var cellText = document.createTextNode('');
                        }
                    }else if(j === 2) {
                        if(data[0][i]['cash_bill']){
                            var cash = '現金 : ' + data[0][i]['cash_bill'].toLocaleString() + '円' + '\n';
                        }else{
                            var cash = '現金 : ' + '\n';
                        }
                        if(data[0][i]['credit_bill']){
                            var credit = 'クレジット : ' + data[0][i]['credit_bill'].toLocaleString() + '円';
                        }else{
                            var credit = 'クレジット : ' ;
                        }
                        var cellText = document.createElement("span");
                        cellText.innerText = cash + credit;
                    } else if(j === 3){
                        var mukei = '';
                        for(let k=0;k<data[0][i]['mukei'].length;k++){
                            mukei += data[0][i]['mukei'][k]['mukei_crew_name'] + ' : ' + data[0][i]['mukei'][k]['mukei_bill'].toLocaleString() + '円' + '\n';
                        }
                        var cellText = document.createElement("span");
                        cellText.innerText = mukei;
                    }else if (j === 4) {
                        var cellText = document.createElement("input");
                        cellText.classList.add("addition");
                        cellText.type = "button";
                        cellText.value = "編集";
                        cellText.id = "edit_button";
                        cellText.setAttribute("onclick", "editbutton("+ data[0][i]["id"] +")");
                    }

                    //ぶち込む
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                }
                document.getElementById("select_tbody").appendChild(row);
            }
    })
}

function editbutton(customer_bill_id){
    $("#selectShowModal").modal("hide");
    $.get('customer/edit?customer_bill_id=' + customer_bill_id, function (data) {
        $("#editShowModal").modal("show");
        $('#edit_date')[0].value = data[0]['date'];
        for(var i=0;i<$('#edit_crew_id')[0].options.length;i++){
            if($('#edit_crew_id')[0].options[i].value == data[0]['crew_id']){
                var index = i;
            }
        }
        if(index || index==0){
            $('#edit_crew_id')[0].options[index].selected = true;
        }else{
            // optionタグを作成する
            var option = document.createElement("option");
            // optionタグのテキストを設定する
            var crew = data[1].find(({ id }) => id == data[0]['crew_id']);
            option.text = crew['name'];
            // optionタグのvalueを設定する
            option.value = data[0]['crew_id'];
            //選択状態にする
            option.selected = true;
            // selectタグの子要素にoptionタグを追加する
            $('#edit_crew_id')[0].appendChild(option);
        }
        
        if(data[0]['notax_bill']){
            $('#edit_notax_bill')[0].value = data[0]['notax_bill'].toLocaleString();
        }
        if(data[0]['intax_bill']){
            $('#edit_intax_bill')[0].value = data[0]['intax_bill'].toLocaleString();
        }
        if(data[0]['cash_bill']){
            $('#edit_cash_bill')[0].value = data[0]['cash_bill'].toLocaleString();
        }
        if(data[0]['credit_bill']){
            $('#edit_credit_bill')[0].value = data[0]['credit_bill'].toLocaleString();
        }
        //無形初期化
        if($('.mukei_container').length > 1){
            for(var j=1;j <= $('.mukei_container').length;j++){
                $('.mukei_container')[j].remove();
            }
        }
        if(data[0]['mukei'].length == 0){
            $('.edit_mukei_crew_id')[0].options[0].selected = true;
            $('.edit_mukei_bill')[0].value = '';
        }
        for(var k=0;k<data[0]['mukei'].length - 1;k++){
            let copied = $('.mukei_container')[0].cloneNode(true);
            $('.mukei_container')[0].parentNode.insertBefore(copied, $('.mukei_container')[0].nextElementSibling);
        }
        for(var l=0;l < data[0]['mukei'].length;l++){
            for(var i=0;i<$('.edit_mukei_crew_id')[l].options.length;i++){
                if($('.edit_mukei_crew_id')[l].options[i].value == data[0]['mukei'][l]['mukei_crew_id']){
                    var mukei_index = i;
                }else{
                    var mukei_index = '';
                }
            }
            if(mukei_index){
                $('.edit_mukei_crew_id')[l].options[mukei_index].selected = true;
            }else{
                // optionタグを作成する
                var option = document.createElement("option");
                // optionタグのテキストを設定する
                var crew = data[1].find(({ id }) => id == data[0]['mukei'][l]['mukei_crew_id']);
                option.text = crew['name'];
                // optionタグのvalueを設定する
                option.value = data[0]['mukei'][l]['mukei_crew_id'];
                //選択状態にする
                option.selected = true;
                // selectタグの子要素にoptionタグを追加する
                $('.edit_mukei_crew_id')[l].appendChild(option);
            }
            //無形売上
            $('.edit_mukei_bill')[l].value = data[0]['mukei'][l]['mukei_bill'].toLocaleString();
        }
        $('#edit_customer_bill_id')[0].value = data[0]['id'];
    });
}

function insert_row(){
    length = $('.mukei_container').length;
    let copied = $('.mukei_container')[length -1].cloneNode(true);
    $('.mukei_container')[length -1].parentNode.insertBefore(copied, $('.mukei_container')[length-1].nextElementSibling);
}

function checkSubmit(){
    if (window.confirm("更新してよろしいですか？")) {
        var isRihgt = true;
        //来店日
        if(!$('#edit_date')[0].value){
            $('.err_date')[0].innerHTML = '※入力必須項目です。';
            isRihgt = false;
        }
        //支払い担当
        if(!$('#edit_crew_id')[0].value){
            $('.err_crew_id')[0].innerHTML = '※入力必須項目です。';
            isRihgt = false;
        }
        //売上(税抜き)
        if(!$('#edit_notax_bill')[0].value){
            $('.err_notax_bill')[0].innerHTML = '※入力必須項目です。';
            isRihgt = false;
        }
        if(isNaN(removeComma($('#edit_notax_bill')[0].value))){
            $('.err_notax_bill')[0].innerHTML = '※入力形式が間違っています。';
            isRihgt = false;
        }
        //売上(税込)
        if(!$('#edit_intax_bill')[0].value){
            $('.err_intax_bill')[0].innerHTML = '※入力必須項目です。';
            isRihgt = false;
        }
        if(isNaN(removeComma($('#edit_intax_bill')[0].value))){
            $('.err_intax_bill')[0].innerHTML = '※入力形式が間違っています。';
            isRihgt = false;
        }
        //現金とクレジット
        if(!$('#edit_cash_bill')[0].value && !$('#edit_credit_bill')[0].value){
            $('.err_cash_bill')[0].innerHTML = '※現金、クレジットのどちらかは入力してください。';
            $('.err_credit_bill')[0].innerHTML = '※現金、クレジットのどちらかは入力してください。';
            isRihgt = false;
        }
        if(isNaN(removeComma($('#edit_cash_bill')[0].value))){
            $('.err_cash_bill')[0].innerHTML = '※入力形式が間違っています。';
            isRihgt = false;
        }
        if(isNaN(removeComma($('#edit_credit_bill')[0].value))){
            $('.err_credit_bill')[0].innerHTML = '※入力形式が間違っています。';
            isRihgt = false;
        }
        if(!isNaN(removeComma($('#edit_credit_bill')[0].value)) && !isNaN(removeComma($('#edit_cash_bill')[0].value))){
            var sum = removeComma($('#edit_credit_bill')[0].value) + removeComma($('#edit_cash_bill')[0].value);
            if(sum != removeComma($('#edit_intax_bill')[0].value)){
                $('.err_cash_bill')[0].innerHTML = '※合計金額が売上(税抜)と一致しません。';
                $('.err_credit_bill')[0].innerHTML = '※合計金額が売上(税抜)と一致しません。';
                isRihgt = false;
            }
        }
        for(var i=0;i<$('.edit_mukei_bill').length;i++){
            if(isNaN(removeComma($('.edit_mukei_bill')[i].value))){
                $('.err_mukei_bill')[i].innerHTML = '※入力形式が間違っています。';
                isRihgt = false;
            }
            if(!$('.edit_mukei_crew_id')[i].value && $('.edit_mukei_bill')[i].value){
                $('.err_mukei_crew_id')[i].innerHTML = '※従業員、金額両方を入力してください。';
                isRihgt = false;
            }else if($('.edit_mukei_crew_id')[i].value && !$('.edit_mukei_bill')[i].value){
                $('.err_mukei_crew_id')[i].innerHTML = '※従業員、金額両方を入力してください。';
                isRihgt = false;
            }
        }
        //カンマとってintで送る
        if(isRihgt){
            $('#edit_notax_bill')[0].value = removeComma( $('#edit_notax_bill')[0].value);
            $('#edit_intax_bill')[0].value = removeComma( $('#edit_intax_bill')[0].value);
            $('#edit_cash_bill')[0].value = removeComma( $('#edit_cash_bill')[0].value);
            $('#edit_credit_bill')[0].value = removeComma( $('#edit_credit_bill')[0].value);
            for(var j=0;j<$('.edit_mukei_bill').length;j++){
                if($('.edit_mukei_bill')[j].value){
                    $('.edit_mukei_bill')[j].value = removeComma( $('.edit_mukei_bill')[j].value);
                }
            }
        }
        
        return isRihgt;
    } else {
        return false;
    }
}

//カンマを取り除いてintで返す
function removeComma(number) {
    if(number){
        var removed = number.replace(/,/g, '');
        return parseInt(removed, 10);
    }
    return 0;
}

//カンマ
document.getElementById('edit_notax_bill').addEventListener("blur", (e) => {
    $('.err_notax_bill')[0].innerHTML = '';
    if($('#edit_notax_bill')[0].value && !$('#edit_notax_bill')[0].value.match(/^[^\x01-\x7E\uFF61-\uFF9F]+$/)){
        $('#edit_notax_bill')[0].value = parseInt($('#edit_notax_bill')[0].value.replace(/,/g, '')).toLocaleString();
    }
    $('#edit_intax_bill')[0].value = (Math.ceil((parseInt($('#edit_notax_bill')[0].value.replace(/,/g, '')) * 1.25) / 1000) * 1000).toLocaleString();
    if($('#edit_notax_bill')[0].value == 'NaN'){
        $('#edit_notax_bill')[0].value = '';
        $('#edit_intax_bill')[0].value = '';
        $('.err_notax_bill')[0].innerHTML = '※入力形式が間違っています。';
    }
});
document.getElementById('edit_intax_bill').addEventListener("blur", (e) => {
    $('.err_intax_bill')[0].innerHTML = '';
    if($('#edit_intax_bill')[0].value && !$('#edit_intax_bill')[0].value.match(/^[^\x01-\x7E\uFF61-\uFF9F]+$/)){
        $('#edit_intax_bill')[0].value = parseInt($('#edit_intax_bill')[0].value.replace(/,/g, '')).toLocaleString();
    }
    if($('#edit_intax_bill')[0].value == 'NaN'){
        $('#edit_intax_bill')[0].value = '';
        $('.err_intax_bill')[0].innerHTML = '※入力形式が間違っています。';
    }
});
document.getElementById('edit_cash_bill').addEventListener("blur", (e) => {
    $('.err_cash_bill')[0].innerHTML = '';
    if($('#edit_cash_bill')[0].value && !$('#edit_cash_bill')[0].value.match(/^[^\x01-\x7E\uFF61-\uFF9F]+$/)){
        $('#edit_cash_bill')[0].value = parseInt($('#edit_cash_bill')[0].value.replace(/,/g, '')).toLocaleString();
    }
    if($('#edit_cash_bill')[0].value == 'NaN'){
        $('#edit_cash_bill')[0].value = '';
        $('.err_cash_bill')[0].innerHTML = '※入力形式が間違っています。';
    }
});
document.getElementById('edit_credit_bill').addEventListener("blur", (e) => {
    $('.err_credit_bill')[0].innerHTML = '';
    if($('#edit_credit_bill')[0].value && !$('#edit_credit_bill')[0].value.match(/^[^\x01-\x7E\uFF61-\uFF9F]+$/)){
        $('#edit_credit_bill')[0].value = parseInt($('#edit_credit_bill')[0].value.replace(/,/g, '')).toLocaleString();
    }
    if($('#edit_credit_bill')[0].value == 'NaN'){
        $('#edit_credit_bill')[0].value = '';
        $('.err_credit_bill')[0].innerHTML = '※入力形式が間違っています。';
    }
});

//フラッシュメッセージ
setTimeout(function() {
    $(".flash-success,.flash-error")
        .fadeOut(3000)
        .queue(function() {
            this.remove();
        });
}, 1000);
