function calen(){
    var currDT;
    var aryDay = new Array("日","一","二","三","四","五","六");//显示星期
    var lastDay;//页面显示的最后一天
    var firstDay;//页面显示的第一天
    var tm=parseInt(getCountDays()/7);//一个月有几个星期
    var timeArr=[];
    initDate();
//初始化日期加载
    function initDate() {
        currDT = new Date();
        var dw = currDT.getDay();//从Date对象返回一周中的某一天(0~6)

        var colnum;

        if(dw >= 6){

            colnum = 6

        }else{

            colnum = 5

        }

        var tdDT;//日期
        //在表格中显示一周的日期
        var objTB = document.getElementById("mytable");//取得表格对象
        var oTr=$("tr");
        var htmls = "";
        for(var i=0;i<colnum;i++){
            htmls += "<tr>";
            // 每行添加 3 个单元格
            for(var c = 0; c < 7; c++) {
                htmls += "<td><span></span><p></p><i></i></td>";
            }
            htmls += "</tr>";
        }
        $("#mytable .ttt").append(htmls);

        //今天的日期的后一天
        var today=new Date();
        var date2 = new Date(today);
        date2.setDate(date2.getDate()+1);

        //获取当前月的下个月的第一天
        var l=getCurrentMonthLast();
        var d = new Date(l);
        d.setDate(d.getDate()+1);

        //下个月第一天

        var cc = d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate();

        //明天   

        var c = date2.getFullYear()+"/"+(date2.getMonth()+1)+"/"+date2.getDate();

        for(var i=0;i<7;i++) {

            //这周的七天的日期

            tdDT = getDays()[i];

            if(tdDT.toLocaleDateString() == currDT.toLocaleDateString()) {
                $("#mytable tr:eq(0) td").eq(i).addClass("pasted");//今天的日期

            }
            if(Date.parse(tdDT.toLocaleDateString()) < Date.parse(currDT.toLocaleDateString())) {
                $("#mytable tr:eq(0) td").eq(i).addClass("pasted");//今天以前的日期
            }

            //显示当前月份角标

            if(tdDT.toLocaleDateString()==c) {
                $("#mytable tr:eq(0) td").eq(i).addClass("at");
            }
            if(tdDT.toLocaleDateString()==cc) {
                $("#mytable tr:eq(0) td").eq(i).addClass("at");
            }
            dw = tdDT.getDay();//星期几

            //表头显示周几,每个日期显示当前月

            objTB.rows[0].cells[i].childNodes[0].innerHTML = '周'+aryDay[dw];//显示
            objTB.rows[0].cells[i].childNodes[1].innerHTML =  tdDT.getDate();//显示
            objTB.rows[0].cells[i].childNodes[2].innerHTML =  (tdDT.getMonth()+1)+'月';//显示
            var str=currDT.getFullYear()+"-"+pad((tdDT.getMonth()+1))+"-"+pad(tdDT.getDate());
            $("#mytable tr:eq(0) td").eq(i).attr("data-calen",str);

            if(!$("#mytable tr:eq(0) td").eq(i).hasClass("pasted")){
                timeArr.push(str);

            }
        }

        //重新赋值
        lastDay = getDays()[6];//本周的最后一天
        firstDay = getDays()[0];//本周的第一天

        nextWeek();

    }
    function pad(num) {
        return num>=10?num:'0'+num;
    }
    function getCountDays() {
        var curDate = new Date();
      /* 获取当前月份 */
        var curMonth = curDate.getMonth();
      /*  生成实际的月份: 由于curMonth会比实际月份小1, 故需加1 */
        curDate.setMonth(curMonth + 1);
      /* 将日期设置为0, 这里为什么要这样设置, 我不知道原因, 这是从网上学来的 */
        curDate.setDate(0);
      /* 返回当月的天数 */
        return curDate.getDate();
    }


//取得当前日期一周内的某一天
    function getWeek(i) {
        var now = new Date();
        var n = now.getDay();
        var start = new Date();
        start.setDate(now.getDate() - n + i);//取得一周内的第一天、第二天、第三天...
        return start;
    }

//取得当前日期一周内的七天
    function getDays() {
        var days = new Array();
        for(var i=1;i<=7;i++) {
            days[i-1] = getWeek(i);
        }
        return days;


    }

//取得下一周的日期数(共七天)
    function getNextWeekDatas(ndt) {
        var days = new Array();
        for(var i=1;i<=35;i++) {
            var dt = new Date(ndt);
            days[i-1] = getNextWeek(dt,i);
        }
        return days;
    }

//指定日期的下一周(后七天)
    function getNextWeek(dt,i) {
        var today = dt;
        today.setDate(today.getDate()+i);
        return today;
    }

//下一周
    function nextWeek() {
        //获取当前月的下个月的第一天
        var l=getCurrentMonthLast();
        var d = new Date(l);
        d.setDate(d.getDate()+1);

        //下个月第一天

        var cc = d.getFullYear()+"/"+(d.getMonth()+1)+"/"+d.getDate();
        // for(var e=1;e<5;e++){
            setCurrDTAfter();//重设时间
            //showdate.innerHTML = currDT.toLocaleDateString(); //显示日期

            //在表格中显示一周的日期
            var objTB = document.getElementById("mytable");//取得表格对象
            //var dw = currDT.getDay();//从Date对象返回一周中的某一天(0~6)
            var tdDT;//日期

            for(var i=7;i<42;i++) {

                //第二周的七天的日期

                tdDT = getNextWeekDatas(lastDay)[i-7];

                //dw = tdDT.getDay();//星期几
                //objTB.rows[e].cells[i].childNodes[0].innerHTML = aryDay[dw];//显示
                $('#mytable').find('td').eq(i).children('p').html(tdDT.getDate())//显示
                // objTB.rows[e].cells[i].childNodes[1].innerHTML =  tdDT.getDate();//显示
                // objTB.rows[e].cells[i].childNodes[2].innerHTML =  (tdDT.getMonth()+1)+'月';//显示
                $('#mytable').find('td').eq(i).children('i').html((tdDT.getMonth()+1)+'月')  ;//显示
                var str=currDT.getFullYear()+"-"+pad((tdDT.getMonth()+1))+"-"+pad(tdDT.getDate());
                // $("#mytable tr:eq("+e+") td").eq(i).attr("data-calen",str);
                $('#mytable').find('td').eq(i).attr("data-calen",str);

                //当前日期后第30天
                if(Date.parse(tdDT.toLocaleDateString()) > setCurrDTAfter14()) {
                    $('#mytable').find('td').eq(i).addClass("pasted");
                }

                if(Date.parse(tdDT.toLocaleDateString()) < setCurrDTAfter14()) {
                    //当前月的下个月的第一天
                    if(tdDT.toLocaleDateString() ==cc ) {
                        $('#mytable').find('td').eq(i).addClass("at");
                    }
                }

                if(!$('#mytable').find('td').eq(i).hasClass("pasted")){
                    timeArr.push(str);
                    // console.log(timeArr)
                }

            }

            //重新赋值
            firstDay = getNextWeekDatas(lastDay)[0];//注意赋值顺序1
            lastDay = getNextWeekDatas(lastDay)[6];//注意赋值顺序2

        // }
    }

//当前日期后第七天
    function setCurrDTAfter() {
        currDT.setDate(currDT.getDate()+7);
    }

//今天日期后第14天
    // function setCurrDTAfter14() {
    //     var today=new Date();
    //     var t14=today.setDate(today.getDate()+14);
    //     return t14
    // }

    //今天后第30天

    function setCurrDTAfter14() {
        var today=new Date();
        var t14=today.setDate(today.getDate()+30);
        return t14
    }

}
//今天日期后第1天

function setCurrDTAfter1() {
    var today=new Date();
    var t14=today.setDate(today.getDate()+1);
    return t14

}


// 获取当前月的最后一天
getCurrentMonthLast();
function getCurrentMonthLast(){
    var date=new Date();
    var currentMonth=date.getMonth();
    var nextMonth=++currentMonth;
    var nextMonthFirstDay=new Date(date.getFullYear(),nextMonth,1);
    var oneDay=1000*60*60*24;
    return new Date(nextMonthFirstDay-oneDay);
}