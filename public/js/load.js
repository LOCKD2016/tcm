function getRolesTree(data) {
    window.obj = {};
    $('#priTree1').data('jstree', false).empty().jstree({
        'plugins': ["wholerow", "checkbox"], 'core': {
            'data': data
        }
    }).on("changed.jstree", function (e, data) {

        window.obj = data.selected;
        // console.log(window.obj);
    });
    return window.obj;
}

    function tabsInd(o,i){
    $(o).parent().attr('show',i)
}
$('.dropdown-menu .valt').click(function(){
    $(this).parents('.dropdown').find('.valf').text($(this).text());
});


function itemTag(i,e){
    //AJAX
    $('#item'+i).removeClass('s_cover').toggleClass('s_tag_'+e);
    if(e=='finance'){
        if($('#item'+i).hasClass('s_tag_finance')){
            $('#finance_dropdown').css('display','inline-block');
        }else{
            $('#finance_dropdown').css('display','none');
        }
    }
}


function itemPop(i,e){
    $('#'+e).attr('for',i).modal('show');
}

function itemPop2(i,e){
    $('#'+e).attr('for',i).modal('show');
    setTimeout(function(){
        $(".clone").delegate('.icon-minus', 'click', function () {
            $(this).parents('.form-group').remove()
        });
        $(".clone").delegate('.icon-plus', 'click', function () {
            $(this).parents('.form-group').clone(false).appendTo($(this).parents('.clone'))
        });
    },1000);
}
function subCover(){
    var i = $('#cover').attr('for');
    //AJAX 处理
    $('#item' + i).addClass('s_cover')
        .removeClass('s_tag_discuss').removeClass('s_tag_follow').removeClass('s_tag_talk')
        .find('.cover_t').text($('#cover textarea').val());
    $('#cover textarea').val('');
}

$(function(){
    $('.clone').delegate('.icon-plus','click',function(){
        $(this).parents('.form-group').clone().appendTo($(this).parents('.clone'))
    });
    $('.clone').delegate('.icon-minus','click',function(){
        $(this).parents('.form-group').remove()
    });
});

$('.check_list').delegate('tr','click',function(){
    $(this).toggleClass('active')
});

//表格选中单行
function checkTr(o){
    $(o).toggleClass('active')
}

function radio(o){
    $(o).addClass('active').siblings("label").removeClass("active");
}

function moreSel(o)
{
    $(o).parent(".sel_box").children("ul").toggleClass("none");
}

function headNav(i){
    $('.header .nav').children('li').removeClass('active').eq(i).addClass('active');

    // $('.search_box').delegate('.icon-ellipsis','click',function(){
    //     $(this).parents('dd').addClass('open');
    // });
    // $('.search_box').delegate('.icon-arrow-up2','click',function(){
    //     var _h = $(this).siblings('.box').height();
    //     console.log(_h);
    //     if(_h < 60){
    //         $(this).parents('dd').addClass('n_over')
    //     }
    //     $(this).parents('dd').removeClass('open');
    // });
}



//列表更多
function showAll(i){
    $(i).parents('.list').siblings('.list').removeClass('active');
    $(i).parents('.list').siblings('.list').find('a').html('更多<i class="icon-arrow-down2">');
    if($(i).parents('.list').hasClass('active')){
        $(i).html('更多<i class="icon-arrow-down2">');
        $(i).parents('.list').removeClass('active');
    }else{
        $(i).html('收起<i class="icon-arrow-up2">');
        $(i).parents('.list').addClass('active');
    }
}

//隐藏新评论框
function fadeOut(){
    $('.com_new').fadeOut();
}
//添加评价
function com_add(){
    $('.com_new').fadeIn();
}
//减少评价
function com_lost(i){
    $(i).prev('span').text('');
}

//确定添加评价
function com_submit(){
    var val=$('.com_new textarea').val();
    if(val==''){
        alert('请填写新评论')
    }else{
        $('.com_old span').text(val);
        $('.com_new textarea').val('');
        fadeOut();
    }
}

//取消新评价
function com_cancel(){
    var val=$('.com_new textarea').val();
    $('.com_new textarea').val('');
    fadeOut();
}


function hChart(e,opt){
    var _dom = document.getElementById(e);
    if(!_dom){
        return false
    }
    var myChart = echarts.init(document.getElementById(e));
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(opt);
}


/**
 * 筛选框
 * @param obj dom id
 * @param url 关键字ajax地址
 */
function initSet(obj,url){
    var availableTags = [];
    if(url){
        $.get(url,function(data){
            availableTags = data;
        })
    }else{
        availableTags = [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
        ]
    }
    setTimeout(function(){
        var _dom = document.getElementById(obj);
        $(_dom).find('.lab').click(function(){
            if($(this).hasClass('active')){
                $(this).removeClass('active');
            }else{

                if($(this).hasClass('all')){
                    //点击全部
                    $(this).siblings('.lab').removeClass('active');
                    $(this).addClass('active');
                }else{
                    $(this).addClass('active');
                    $(this).siblings('.all').removeClass('active');
                }
            }
            joinTxt(obj);
        });
        $(_dom).find('.auto_inp').autocomplete({
            source: availableTags,
            select: function() {
                joinTxt(obj);
            }
        }).blur(function(){
            joinTxt(obj);
        });
    },250);
}


function joinTxt(obj){
    var _dom = document.getElementById(obj);
    var _bst = [];
    $(_dom).find('.lab.active').each(function(){
        _bst.push($(this).text());
    });
    var _txt = $.trim($(_dom).find('.auto_inp').val());
    if(_txt != ''){
        _bst.push(_txt);
    }
    $(_dom).find('.search_txt').text(_bst);
    // 执行搜索
}

function autoInp(obj,url){
    var availableTags = [];
    if(url){
        $.get(url,function(data){
            availableTags = data;
        })
    }else{
        availableTags = [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
        ]
    }
    setTimeout(function(){
        $('#'+obj).autocomplete({
            source: availableTags
        });
    },200)
}


function textEdit(e,id,types) {
    var td = $(e);
    var txt = td.text();
    var input = $("<input type='text'class='form-control' value='" + txt + "'/>");
    td.html(input);
    input.click(function() { return false; });
//获取焦点
    input.trigger("focus");
//文本框失去焦点后提交内容，重新变为文本
    input.blur(function() {
        var newtxt = $(this).val();
//判断文本有没有修改
        if (newtxt != txt) {
            td.html(newtxt);
            var url = 'http://'+window.location.host+'/api/lnquiry/'+id;
            $.ajax({
                url: url,
                type:'put',
                data:{id:id,type:types,content:newtxt},
                success: function(res) {
                    if (res.status) {
                        alert(res.msg);
                    }
                }
            });
        }else{
            td.html(txt);
        }
    });

}
function txtEdit(e,id,types) {
    var td = $(e);
    var txt = td.text();
    var textarea = $("<textarea class='form-control'>"+txt+"</textarea>");
    td.html(textarea);
    textarea.click(function() { return false; });
//获取焦点
    textarea.trigger("focus");
//文本框失去焦点后提交内容，重新变为文本
    textarea.blur(function() {
        var newtxt = $(this).val();
//判断文本有没有修改
        if (newtxt != txt) {
            td.html(newtxt);
            var url = 'http://'+window.location.host+'/api/lnquiry/'+id;
            $.ajax({
                url: url,
                type:'put',
                data:{id:id,type:types,content:newtxt},
                success: function(res){
                    if (res.status) {
                        alert(res.msg);
                    }
                }
            });
        }else{
            td.html(txt);
        }
    });

}

function lnEdit(e,id,types,lid=0) {
    var td = $(e);
    var txt = td.text();
    var input = $("<input type='text'class='form-control' value='" + txt + "'/>");
    td.html(input);
    input.click(function() { return false; });
//获取焦点
    input.trigger("focus");
//文本框失去焦点后提交内容，重新变为文本
    input.blur(function() {
        var newtxt = $(this).val();
//判断文本有没有修改
        if (newtxt != txt) {
            td.html(newtxt);
            var url = 'http://'+window.location.host+'/api/lnquiry/'+id;
            $.ajax({
                url: url,
                type:'put',
                data:{id:id,type:types,content:newtxt,lid:lid},
                success: function(res) {
                    if (res.status==1) {
                        alert(res.msg);
                        location.href = 'http://'+window.location.host+'/admin/lnquiry_detail/'+lid;
                    }else{
                        alert(res.msg);
                    }
                }
            });
        }else{
            td.html(txt);
        }
    });

}
