const mutations = {
    //当 search 组件全屏/非全屏时 切换公共头部状态
    toggleHeaderStatus(state, status){
        state.headerStatus = status;
    },
    toggleFooterStatus(state, status){
        state.footerStatus = status;
    },
    //设置当前页面名字
    setPageName(state, name) {
        state.currentPageName = name
    },
    //设置前一页名字 已遗弃
    // setBackPageName(state, name) {
    //     state.backPageName = name
    // },
    //设置当前页面名字
    setWxReady(state, flag) {
        state.wxready = flag
    },
    setWxInstall(state, flag) {
        state.wxInstall = flag
        state.apiready  = true
    },
    setTranslate(state,num){
        state.translate = num
    },
    setincludePage(state,name){
        if(name){
            state.includePage.push(name);
        }else{
            state.includePage.splice(0);
        }
    }
};
export default mutations
