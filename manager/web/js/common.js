/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    // Aajax加载中
    $(document).ajaxStart(function () {
        $(".loading").show();
    });
    $(document).ajaxSuccess(function () {
        $(".loading").hide();
    });
    //全选
    $(".select_all").click(function() {
        if ($(this).hasClass("show1")) {
            $(".card-body").find("input[type='checkbox']").prop("checked", false);
            $(this).removeClass("show1").removeClass("btn-primary").addClass("btn-default");
        } else {
            $(".card-body").find("input[type='checkbox']").prop("checked", true);
            $(this).addClass("show1").removeClass("btn-default").addClass("btn-primary");
        }
    });
});