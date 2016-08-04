<?php
/* Smarty version 3.1.29, created on 2016-08-04 08:30:49
  from "/usr/local/apache2/htdocs/simulate/public/ZX001.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57a2fd394bab54_28745640',
  'file_dependency' => 
  array (
    'fe0827770128bf343c8df03950d43e9b8dfbaf2a' => 
    array (
      0 => '/usr/local/apache2/htdocs/simulate/public/ZX001.html',
      1 => 1470293495,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57a2fd394bab54_28745640 ($_smarty_tpl) {
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>厨电设备</title>
    <link rel="stylesheet" type="text/css" href="../css/common.css"/>
    <link rel="stylesheet" type="text/css" href="../css/main.css"/>
    <link rel="stylesheet" type="text/css" href="../css/mui-switch.css"/>
    <?php echo '<script'; ?>
 type="text/javascript" src="../js/ZX.js"><?php echo '</script'; ?>
>
</head>
<body>
    <!-- <meta http-equiv="refresh" content="5"> -->
    <div style="margin-left:500px" id="brandDiv"><img src="../images/fotile.jpg" width="339px" height="55px" />
    </div>

    <div  class="container clearfix">
        <div class="sidebar-wrap">
            <div class="sidebar-title">
                <h1>产品测试</h1>
            </div>
            <div class="sidebar-content">
                <ul class="sidebar-list">
                    <li>
                        <a href="#"><i class="icon-font">&#xe003;</i>常用操作</a>
                        <ul class="sub-menu">
                            <li><a href="../php/mydevice.php"><i class="icon-font">&#xe008;</i>设备管理</a></li>
                            <li><a href="../php/mydevice_ctrl.php"><i class="icon-font">&#xe005;</i>设备操作</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="icon-font">&#xe018;</i>我的设备</a>

                        <ul class="sub-menu">
                        <?php
$_from = $_smarty_tpl->tpl_vars['devices']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_0_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_0_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_0_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
                            <li><a href="../php/<?php echo $_smarty_tpl->tpl_vars['v']->value[1];?>
"><i class="icon-font">&#xe017;</i><?php echo $_smarty_tpl->tpl_vars['v']->value[2];?>
</a></li>
                        <?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_0_saved_local_item;
}
if ($__foreach_v_0_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_0_saved_item;
}
if ($__foreach_v_0_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_0_saved_key;
}
?>                           
                        </ul>
                    </li>
                    
                     <li>
                        <p><h6>TRACELOG:</h6><?php ob_start();
echo $_smarty_tpl->tpl_vars['log']->value;
$_tmp1=ob_get_clean();
echo $_tmp1;?>
</p>
                        <div id ="log1" >
                            <h1>

                            </h1>
                        </div>               
                    </li>
                </ul>
            </div>
        </div>
        <!--/sidebar-->
        <div class="main-wrap">

            <!-- <div class="crumb-wrap">
                <div class="crumb-list"><i class="icon-font"></i><a href="index.html" color="#white">首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">订单查询</span></div>
            </div> -->
            <!-- <div class="search-wrap">
                <div class="search-content">
                    <form action="#" method="post">
                       
                    </form>
                </div>
            </div> -->

            <div class="result-wrap">
                <form name="checkform" action="../php/ZX001.php" method="POST" id="checkform">
                    <div class="sidebar-wrap-1">
                        <h1>当前时间:</h1>
                    </div>

                    <div id="div_time" class="sidebar-title" >
                        <h1>00:00:00</h1>
                    </div>

                    <div class="sidebar-wrap-1">
                        <h1>当前产品:</h1>
                    </div>

                    <div id="div_pro" class="sidebar-title" style="display:inline;">                       
                    </div>
                    
                    <div id="div_save" class="sidebar-title" style="float:right;">
                        <input type="button" value="测试工具下载" onclick="download()">
                        <input type="button" name="button" value="状态上报" onclick="reportStatus(0)">                 
                        <input type="button" name="button" value="mcu校时" onclick="mcuTime()">    
                        <input type="submit" name="subbtn" value="保存设置"/>                      
                    </div>
                    <div class="result-title">
                        <!-- <div class="result-list">
                            <a id="batchDel" href="javascript:void(0)"><i class="icon-font"></i>批量删除</a>
                            <a id="updateOrd" href="javascript:void(0)"><i class="icon-font"></i>更新排序</a>
                        </div> -->
                    </div>
                    <div class="result-content">
                        <table class="result-tab" width="100%">
                            <tr>
                                <!-- <th class="tc" width="5%"></th> -->
                                <th width="10%">序号</th>
                                <th width="25%">内容</th>
                                <th width="25%">数值</th>
                                <th width="40%">操作</th>
                            </tr>
                            <tr>
                                <!-- <td class="tc"><input name="id[]" value="" type="checkbox"></td> -->
                                <td>0</td>
                                <td>运行状态</td>
                                <td id="div0"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data0']->value;
$_tmp2=ob_get_clean();
echo $_tmp2;?>
</td>
                                 <td>
                                    <select name="run_status" id="sel0">                               
                                        <option value="0">开机</option>
                                        <option value="1">关机</option>
                                        <option value="2">待机</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>当前工作状态</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data1']->value;
$_tmp3=ob_get_clean();
echo $_tmp3;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>照明灯</td> 
                                <td id="div2"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data2']->value;
$_tmp4=ob_get_clean();
echo $_tmp4;?>
</td> 
                                <td>
                                    <input class="mui-switch mui-switch-anim" type="checkbox" name="check[]" value=1 id="check1"></input>
                                </td> 
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>工作模式</td> 
                                <td id="div3"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data3_1']->value;
$_tmp5=ob_get_clean();
echo $_tmp5;?>
</td> 
                                <td>
                                    <select name="work_mode" id="sel1">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['data3_2']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_v_1_saved_item = isset($_smarty_tpl->tpl_vars['v']) ? $_smarty_tpl->tpl_vars['v'] : false;
$__foreach_v_1_saved_key = isset($_smarty_tpl->tpl_vars['k']) ? $_smarty_tpl->tpl_vars['k'] : false;
$_smarty_tpl->tpl_vars['v'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['k'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['v']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
$__foreach_v_1_saved_local_item = $_smarty_tpl->tpl_vars['v'];
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>
                                    <?php
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_1_saved_local_item;
}
if ($__foreach_v_1_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_1_saved_item;
}
if ($__foreach_v_1_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_1_saved_key;
}
?>
                                                                 
                                        <!-- <option value="0">自动模式</option>
                                        <option value="1">手动模式1</option>
                                        <option value="2">手动模式2</option> -->
                                    </select>
                                </td> 
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>当前菜谱</td> 
                                <td id="div4"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data4']->value;
$_tmp6=ob_get_clean();
echo $_tmp6;?>
</td> 
                                <td>
                                    <select name="cook_book" id="sel2">                               
                                        <option value="0" selected = "selected">本地菜谱1</option>
                                        <option value="1" >本地菜谱2</option>
                                        <option value="2">网络菜谱1</option>
                                        <option value="3">网络菜谱2</option>
                                    </select>
                                </td> 
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>设定工作总时间(分)</td> 
                                <td id="div5"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data5']->value;
$_tmp7=ob_get_clean();
echo $_tmp7;?>
</td> 
                                <td>
                                    <input type="text" name="text0" id="text0">
                                </td> 
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>预约开始时间</td> 
                                <td id="div6"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data6']->value;
$_tmp8=ob_get_clean();
echo $_tmp8;?>
</td> 
                                <td>
                                    <input type="text" name="text1" id="text1">
                                </td> 
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>设定温度值1</td> 
                                <td id="div7"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data7']->value;
$_tmp9=ob_get_clean();
echo $_tmp9;?>
</td> 
                                <td>
                                    <input type="text" name="text2" id="text2">
                                </td> 
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>设定温度值2</td> 
                                <td id="div8"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data8']->value;
$_tmp10=ob_get_clean();
echo $_tmp10;?>
</td> 
                                <td>
                                    <input type="text" name="text3" id="text3">
                                </td> 
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>设定温度值3</td> 
                                <td id="div9"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data9']->value;
$_tmp11=ob_get_clean();
echo $_tmp11;?>
</td> 
                                <td>
                                    <input type="text" name="text4" id="text4">
                                </td> 
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>加热盘(W)</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data10']->value;
$_tmp12=ob_get_clean();
echo $_tmp12;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>二级加热(W)</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data11']->value;
$_tmp13=ob_get_clean();
echo $_tmp13;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>温度传感器1</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data12']->value;
$_tmp14=ob_get_clean();
echo $_tmp14;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>温度传感器2</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data13']->value;
$_tmp15=ob_get_clean();
echo $_tmp15;?>
</td> 
                                <td></td> 
                            </tr>
                             <tr>
                                <td>14</td>
                                <td>温度传感器3</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data14']->value;
$_tmp16=ob_get_clean();
echo $_tmp16;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>水位传感器1(%)</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data15']->value;
$_tmp17=ob_get_clean();
echo $_tmp17;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>水位传感器2(%)</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data16']->value;
$_tmp18=ob_get_clean();
echo $_tmp18;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>压力传感器</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data17']->value;
$_tmp19=ob_get_clean();
echo $_tmp19;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>加热膜</td> 
                                <td id="div18"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data18']->value;
$_tmp20=ob_get_clean();
echo $_tmp20;?>
</td> 
                                <td>
                                    <input class="mui-switch mui-switch-anim" type="checkbox" name="check[]" value=2 id="check2">
                                </td> 
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>贯穿热风机</td> 
                                <td id="div19"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data19']->value;
$_tmp21=ob_get_clean();
echo $_tmp21;?>
</td> 
                                <td>
                                    <input class="mui-switch mui-switch-anim" type="checkbox" name="check[]" value=3  id="check3">
                                </td> 
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>工作剩余时间(分)</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data20']->value;
$_tmp22=ob_get_clean();
echo $_tmp22;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>门电子锁</td> 
                                <td id="div21"><?php ob_start();
echo $_smarty_tpl->tpl_vars['data21']->value;
$_tmp23=ob_get_clean();
echo $_tmp23;?>
</td> 
                                <td>
                                    <form name="form1" >
                                    <input class="mui-switch mui-switch-anim" type="checkbox" name="check[]" value=4 id="check4">
                                    <input type="checkbox" name="check[]" value=0 checked="checked" style="display: none">
                                    </form>
                                </td> 
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>门控开关状态</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data22']->value;
$_tmp24=ob_get_clean();
echo $_tmp24;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>食物位置</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data23']->value;
$_tmp25=ob_get_clean();
echo $_tmp25;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>蒸箱故障号</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data24']->value;
$_tmp26=ob_get_clean();
echo $_tmp26;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>25</td>
                                <td>OTA(远程升级)</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['data25']->value;
$_tmp27=ob_get_clean();
echo $_tmp27;?>
</td> 
                                <td>
                                    <!-- <input type="button" name="button" value="ajax" onclick="getData(0)"> -->
                                </td> 
                            </tr><tr>
                                <td>26</td>                     
                                <td>-----分割线-----</td> 
                                <td>--------------</td>
                                <td>--------------
                                    <!-- <input type="button" name="button" value="ajax" onclick="getData(0)"> -->
                                </td> 
                            </tr>
                            <tr>
                                <td>27</td>
                                <td>wifi工作模式</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['wifi1']->value;
$_tmp28=ob_get_clean();
echo $_tmp28;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>28</td>
                                <td>wifi连接状态</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['wifi2']->value;
$_tmp29=ob_get_clean();
echo $_tmp29;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>29</td>
                                <td>wifi与ikcc socket状态</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['wifi3']->value;
$_tmp30=ob_get_clean();
echo $_tmp30;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>30</td>
                                <td>wifi信号强度</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['wifi4']->value;
$_tmp31=ob_get_clean();
echo $_tmp31;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>31</td>
                                <td>wifi当前信道</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['wifi5']->value;
$_tmp32=ob_get_clean();
echo $_tmp32;?>
</td> 
                                <td></td> 
                            </tr>
                            <tr>
                                <td>32</td>
                                <td>wifi通信模式</td> 
                                <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['wifi6']->value;
$_tmp33=ob_get_clean();
echo $_tmp33;?>
</td> 
                                <td></td> 
                            </tr>                            
                        </table>                      
                    </div>
                    <div class="list-page">
                        2016 © 宁波方太厨具有限公司.
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html><?php }
}
