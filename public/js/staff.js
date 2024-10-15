//phpの変数を取得(別ファイルにjs描きたいのでので仕方なく面倒だけど)
var $staff = $("#staff");
var json_year_month = JSON.parse($staff.attr("data-month"));
var json_amount = JSON.parse($staff.attr("data-amount"));
var json_mukei_amount = JSON.parse($staff.attr("data-mukei"));
var json_mukei_amount = JSON.parse($staff.attr("data-mukei"));
var json_selected_crew_id = JSON.parse($staff.attr("data-selected_crew_id"));
var json_selected_crew_name = JSON.parse($staff.attr("data-selected_crew_name"));
var json_daily_payment = JSON.parse($staff.attr("data-daily_payment"));
var json_click_URL = JSON.parse($staff.attr("data-click-URL"));

const canvas = document.getElementById("canvas"); //グラフを描画するcanvasを取得

window.onload = function () {
    //画面が読み込まれたら
    //棒グラフを描画
    var myChart = new Chart(canvas, {
        type: "bar", //グラフのタイプ、bar -> 棒グラフ
        data: {
            labels: json_year_month, //X軸のラベル、PHPで抽出した年月配列をセット
            datasets: [
                {
                    label: "売上",
                    data: json_amount, //棒グラフにするデータ、PHPで抽出した金額配列をセット
                    backgroundColor: ["#FF0000"], //棒グラフの色
                    datalabels: {
                        //金額データラベル設定
                        color: "#FF0000", //文字色
                        font: {
                            //フォント設定
                            size: "11px",
                            weight: "bold",
                        },
                        anchor: "end", // 金額データラベルの位置（'end' は上端）
                        align: "end", // 金額データラベルの位置('end'は上記アンカーポイントの後ろ)
                        formatter: function (value, context) {
                            // 金額データラベルを「,」で区切り「円」を付け足す
                            return (
                                value
                                    .toString()
                                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                                " 円"
                            );
                        },
                    },
                },
                {
                    label: "無形",
                    data: json_mukei_amount, //棒グラフにするデータ、PHPで抽出した金額配列をセット
                    backgroundColor: ["#0000FF"], //棒グラフの色
                    datalabels: {
                        //金額データラベル設定
                        color: "#0000FF", //文字色
                        font: {
                            //フォント設定
                            size: "11px",
                            weight: "bold",
                        },
                        anchor: "end", // 金額データラベルの位置（'end' は上端）
                        align: "end", // 金額データラベルの位置('end'は上記アンカーポイントの後ろ)
                        formatter: function (value, context) {
                            // 金額データラベルを「,」で区切り「円」を付け足す
                            return (
                                value
                                    .toString()
                                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                                " 円"
                            );
                        },
                    },
                },
                {
                    label: "日払い",
                    data: json_daily_payment, //棒グラフにするデータ、PHPで抽出した金額配列をセット
                    backgroundColor: ["green"], //棒グラフの色
                    datalabels: {
                        //金額データラベル設定
                        color: "green", //文字色
                        font: {
                            //フォント設定
                            size: "11px",
                            weight: "bold",
                        },
                        anchor: "end", // 金額データラベルの位置（'end' は上端）
                        align: "end", // 金額データラベルの位置('end'は上記アンカーポイントの後ろ)
                        formatter: function (value, context) {
                            // 金額データラベルを「,」で区切り「円」を付け足す
                            return (
                                value
                                    .toString()
                                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                                " 円"
                            );
                        },
                    },
                },
            ],
        },

        plugins: [ChartDataLabels], //データラベルのプラグインを読み込む

        options: {
            //グラフのオプション
            maintainAspectRatio: false,
            legend: {
                display: true, //グラフの説明を表示
            },
            tooltips: {
                //グラフへカーソルを合わせた際のツールチップ詳細表示の設定
                callbacks: {
                    label: function (tooltipItems, data) {
                        if (tooltipItems.xLabel == "0") {
                            return "";
                        }
                        return data.datasets[tooltipItems.datasetIndex].label;
                    },
                },
            },
        },
    });

    //クリックしたらモーダルだすよー
    canvas.addEventListener("click", function (evt) {
        const res = myChart.getElementsAtEventForMode(
            evt,
            "nearest",
            { intersect: true },
            true
        );
        if (res.length === 0) {
            return;
        }
        showmodal(myChart.data.labels[res[0].index], json_selected_crew_id);
    });
};

//クリックされたらモーダルを表示する
function showmodal(date, selected_crew_id) {
    //URLに情報をパラメータとして渡す
    var URL = json_click_URL + "?date=" + date + "&selected_crew_id=" + selected_crew_id;
    //再検索に備えてtbodyの中を空にする
    document.getElementById("daily_tbody").innerHTML = "";
    let colunms = ["day", "daily_amount", "daily_mukei_amount","daily_payment_detail","daily_payment_comment"];
    $.get(URL, function (daily_data) {
        $("#StaffDailyBillShowModal").modal("show");
        document.getElementById("exampleModalLabel").getElementsByTagName('span')[0].innerHTML = date + json_selected_crew_name+'の';
        //tableを作る
        for (let i = 0; i < daily_data.length; i++) {
            //trタグの生成
            const row = document.createElement("tr");
            for (let j = 0; j < 5; j++) {
                //tdタグの生成
                const cell = document.createElement("td");
                //tdの中身
                if(j == 0){
                    var cellText = document.createTextNode(
                        daily_data[i][colunms[j]]+'日'
                    );
                }else if(j == 4){
                    var cellText = document.createTextNode(
                        daily_data[i][colunms[j]]
                    );
                }
                else{
                    var cellText = document.createTextNode(
                        Number(daily_data[i][colunms[j]]).toLocaleString()
                    );
                }

                //ぶち込む
                cell.appendChild(cellText);
                row.appendChild(cell);
            }
            document.getElementById("daily_tbody").appendChild(row);
        }
    });
}
