
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">权限设置</div>
      <div class="pull-right"><a class="btn btn-primary btn-sm"><i class="icon-checkmark"></i><span>保存并修改</span></a><a class="btn btn-primary btn-sm"><i class="icon-cross"></i><span>取消</span></a><a class="btn btn-primary btn-sm"><i class="icon-bin"></i><span>删除组</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="bg_cor">
      <form role="form">
        <div class="form-group">
          <label for="">用户组</label>
          <input type="text" placeholder="投资团队" class="form-control"/>
        </div>
        <div class="form-group">
          <label for="">描述</label>
          <textarea placeholder="除了描述之外的其他功能" rows="3" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label for="">权限设置</label>
          <div class="set_box">
            <ul class="list-unstyled">
              <li>
                <label>
                  <input type="checkbox"/><span>首页</span>
                </label>
                <ul>
                  <li>
                    <input type="checkbox"/><span>桌面</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>任务看板</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>动态</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>待办</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>通讯录</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>首页</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>日志</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>帮助</span>
                  </li>
                </ul>
              </li>
            </ul>
            <ul class="list-unstyled">
              <li>
                <label>
                  <input type="checkbox"/><span>事件</span>
                </label>
                <ul>
                  <li>
                    <input type="checkbox"/><span>最新</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>工作周报</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>检索</span>
                  </li>
                </ul>
              </li>
            </ul>
            <ul class="list-unstyled">
              <li>
                <label>
                  <input type="checkbox"/><span>项目</span>
                </label>
                <ul>
                  <li>
                    <input type="checkbox"/><span>浏览</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>新增</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>导入</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>导出</span>
                  </li>
                  <li>
                    <label>
                      <input type="checkbox"/><span>查看</span>
                    </label>
                    <ul>
                      <li>
                        <input type="checkbox"/><span>概况</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>详情</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>投资</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>财务</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>股本</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>通讯录</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>权限</span>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
            <ul class="list-unstyled">
              <li>
                <label>
                  <input type="checkbox"/><span>投资</span>
                </label>
                <ul>
                  <li>
                    <input type="checkbox"/><span>浏览</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>新增</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>导入</span>
                  </li>
                  <li>
                    <input type="checkbox"/><span>导出</span>
                  </li>
                  <li>
                    <label>
                      <input type="checkbox"/><span>查看</span>
                    </label>
                    <ul>
                      <li>
                        <input type="checkbox"/><span>概况</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>详情</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>投资</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>财务</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>股本</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>通讯录</span>
                      </li>
                      <li>
                        <input type="checkbox"/><span>权限</span>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>