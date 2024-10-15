//submit時の確認
function checkSubmit(key) {
    if (window.confirm("売掛の回収は完了しましたか？")) {
        return true;
    } else {
        return false;
    }
}