/**
 * Created by lbbniu on 16/9/18.
 */
export function getcookie(name, nounescape) {
    var cookie_start = document.cookie.indexOf(name);
    var cookie_end = document.cookie.indexOf(";", cookie_start);
    if (cookie_start == -1) {
        return '';
    } else {
        var v = document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length));
        return !nounescape ? unescape(v) : v;
    }
}

export function errorMsg(errors){
    for(var d in errors){
        var msg = errors[d][0];
        layer.msg(msg);
        break;
    }
}
export function responseErrorMsg(response){
    var data = response.data;
    if(data.status_code == 401){
        location.href = "/login";
    }else if(data.status_code == 403){
        layer.msg("没有权限");
    }else{
        layer.msg(data.messsage);
    }
}

/**
 * 顶部条件的筛选功能
 * @param key
 * @param value  当前点击的条件值
 * @param arr    此类添加的总列表
 * @param getArr 加入筛选的数组
 * @param callback 回调函数
 * @param field  判断字段
 */
export function tiaojian(key,value,arr,getArr,callback,field='key_field'){
    setTimeout(function(){ joinTxt("searchList");},1000);
    if (value[field] == -1) {
        if(value.checked == false){
            $.each(arr, function (i, v) {
                v.checked = false;
            });
            value.checked = true;
            getArr.splice(0, getArr.length);
            if(key)
                storage.setStorage(key,getArr);
            if(typeof callback == 'function') {
                callback(1);
                return true;
            }
        }
        return true;
    }
    $.each(arr, function (i, v) {
        if (v[field] == -1)
            v.checked = false;
    });
    var index = getArr.indexOf(value[field]);
    if (index > -1) {
        getArr.splice(index, 1);
        value.checked = false;
        if(getArr.length<1){
            $.each(arr, function (i, v) {
                if (v[field] == -1)
                    v.checked = true;
            });
        }
    } else {
        getArr.push(value[field]);
        value.checked = true;
    }
    if(key)
        storage.setStorage(key,getArr);
    return false;
}
/**
 * 顶部筛选复选框的处理
 * @param value
 * @param arr
 * @returns {boolean}
 */
export function checkBox(key,value,arr){
    var index = arr.indexOf(value);
    if (index> -1) {
        arr.splice(index, 1);
    } else {
        arr.push(value);
    }
    if(key)
        storage.setStorage(key,arr);
    return false;
}

/**
 *
 * @param sArr
 * @param tArr
 * @returns {boolean}
 */
export function initTiaojian(sArr,tArr,field='key_field'){
    setTimeout(function(){ joinTxt("searchList");},1000);
    if(typeof tArr == 'object' && tArr.length>0){
        sArr.forEach(e =>{
            if(e[field] == -1){
                e.checked =false;
            }
            if(tArr.indexOf(e[field])>-1){
                e.checked = true;
            }
        })
    }
}



var storage = {};
var uzStorage = function(){
    return window.localStorage;
};
storage.setStorage = function(key, value){
    if(arguments.length === 2){
        var v = value;
        if(typeof v == 'object'){
            v = JSON.stringify(v);
            v = 'obj-'+ v;
        }else{
            v = 'str-'+ v;
        }
        var ls = uzStorage();
        if(ls){
            ls.setItem(key, v);
        }
    }
};
storage.getStorage = function(key){
    var ls = uzStorage();
    if(ls){
        var v = ls.getItem(key);
        if(!v){return;}
        if(v.indexOf('obj-') === 0){
            v = v.slice(4);
            return JSON.parse(v);
        }else if(v.indexOf('str-') === 0){
            return v.slice(4);
        }
    }
};
storage.rmStorage = function(key){
    var ls = uzStorage();
    if(ls && key){
        ls.removeItem(key);
    }
};
storage.clearStorage = function(){
    var ls = uzStorage();
    if(ls){
        ls.clear();
    }
};

exports.storage = storage;