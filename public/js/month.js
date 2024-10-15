//phpの変数を取得(別ファイルにjs描きたいのでので仕方なく面倒だけど)
var $month = $("#month");
var json_crew = JSON.parse($month.attr("data-crew"));
var json_amount = JSON.parse($month.attr("data-amount"));
var json_mukei_amount = JSON.parse($month.attr("data-mukei"));


const canvas = document.getElementById("canvas"); //グラフを描画するcanvasを取得

window.onload = function () {
    //画面が読み込まれたら
    //棒グラフを描画
    new Chart(canvas, {
        type: "bar", //グラフのタイプ、bar -> 棒グラフ
        data: {
            labels: json_crew , //X軸のラベル、PHPで抽出した年月配列をセット
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
                            size: "13px",
                            weight: "bold",
                        },
                        anchor: "end", // 金額データラベルの位置（'end' は上端）
                        align: "end", // 金額データラベルの位置('end'は上記アンカーポイントの後ろ)
                        formatter: function (value, context) {
                            // 金額データラベルを「,」で区切り「円」を付け足す
                            return (
                                '  '+value
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
                            size: "13px",
                            weight: "bold",
                        },
                        anchor: "end", // 金額データラベルの位置（'end' は上端）
                        align: "end", // 金額データラベルの位置('end'は上記アンカーポイントの後ろ)
                        formatter: function (value, context) {
                            // 金額データラベルを「,」で区切り「円」を付け足す
                            return (
                               '  '+ value
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
            indexAxis: 'y',
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
};
