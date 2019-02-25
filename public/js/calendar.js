/**
 * Created by yangshu on 17/1/5.
 */
var djcs= 0;
var toDay = (new Date()).Format("yyyy-MM-dd");
var dayStart = toDay;
var dayEnd = toDay;
var course_data=[];

function calendarInit(){
    var d_x = dayStart,d_d=dayEnd;
    //console.log(dateDiff(dayStart,dayEnd));
    if(dateDiff(dayStart,dayEnd)<0){
        d_d = dayStart;
        d_x = dayEnd;
        dayStart =d_x;
        dayEnd = d_d;
    }
    var couda=[];
    course_data=['2016-12-01','2017-05-03','2017-05-09','2017-05-19'];
    for(i in course_data){
        if(strY(toDay)==strY(course_data[i])&&strM(toDay)==strM(course_data[i])){
            couda.push(course_data[i]);
        }
    }
    $('.calendar-list span').removeClass('select');
    $('.calendar-list span').each(function(){
        var jt = $(this).find('a').data('calen');
        if(jt==d_x){
            $('.calendar-list span').removeClass('start');
            $(this).addClass('start');
        }
        if(jt==d_d){
            $('.calendar-list span').removeClass('end');
            $(this).addClass('end');
        }
        if(dateDiff(jt,d_x)<0&&dateDiff(jt,d_d)>0){
            //console.log(dateDiff(jt,d_x));
            //console.log(jt);
            //console.log(dateDiff(jt,d_d));
            $(this).addClass('select');
        }
        for(i in couda){
            if(jt==couda[i]){
                $(this).addClass('youke');
            }
        }
    });
}
//calendarInit();