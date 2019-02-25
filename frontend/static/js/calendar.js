/**
 * Created by yangshu on 17/1/5. 这个日历组件是全局的，此方法是获取指定医生往后两周的可预约时间标记
 */
var toDay = (new Date()).Format("yyyy-MM-dd");
var orderDate=[];
var searchDate=[];

function getDoctorDate(id){
    //$.ajax
    orderDate=['2017-6-01','2017-05-03','2017-05-09','2017-05-19'];
    calendarClear();
}
function calendarClear(){
    $('.calendar-list span').removeClass('select').removeClass('youke');
    $('.calendar-list span').each(function(){
        var jt = $(this).find('a').data('calen');
        for(var i in orderDate){
            if(jt==orderDate[i]){
                $(this).addClass('youke');
            }
        }
        for(var i in searchDate){
            if(jt==searchDate[i]){
                $(this).addClass('select');
            }
        }
    });

}
Array.prototype.indexOf = function(val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) return i;
    }
    return -1;
};
Array.prototype.remove = function(val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};