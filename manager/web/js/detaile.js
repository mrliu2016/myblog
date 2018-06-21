function getPath(obj, fileQuery, transImg) {

    var imgSrc = '', imgArr = [], strSrc = '';
    var file = fileQuery.files[0];
    var reader = new FileReader();

    if (file.size >= 51200000) {
        layer.msg('您这个"' + file.name + '"文件大小过大', {time: 1000});
    } else {
        // 在这里需要判断当前所有文件中
        var fileExt = file.name.substr(file.name.lastIndexOf(".")).toLowerCase();//获得文件后缀名
        if (fileExt == ".png" || fileExt == ".gif" || fileExt == ".jpg" || fileExt == ".jpeg") {
            reader.onload = function (e) {
                imgSrc = fileQuery.value;
                imgArr = imgSrc.split('.');
                strSrc = imgArr[imgArr.length - 1].toLowerCase();
                obj.setAttribute("src", e.target.result);

            };
            reader.readAsDataURL(file);

        } else {
            layer.msg("文件仅限于 png, gif, jpeg, jpg格式 !", {time: 1000});
        }
    }
}

function changepic() {
    var file_img = document.getElementById("selectImg1");
    var iptfileupload = document.getElementById('profileButton1');
    getPath(file_img, iptfileupload, file_img);
};