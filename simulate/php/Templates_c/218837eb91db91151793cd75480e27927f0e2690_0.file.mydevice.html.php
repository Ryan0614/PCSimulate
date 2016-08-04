<?php
/* Smarty version 3.1.29, created on 2016-08-04 08:30:47
  from "/usr/local/apache2/htdocs/simulate/public/mydevice.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57a2fd37ebd573_03579105',
  'file_dependency' => 
  array (
    '218837eb91db91151793cd75480e27927f0e2690' => 
    array (
      0 => '/usr/local/apache2/htdocs/simulate/public/mydevice.html',
      1 => 1470299445,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57a2fd37ebd573_03579105 ($_smarty_tpl) {
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的设备</title>
    <link rel="stylesheet" type="text/css" href="../css/common.css"/>
    <link rel="stylesheet" type="text/css" href="../css/main.css"/>
    <?php echo '<script'; ?>
 type="text/javascript" src="../js/mydevice.js"><?php echo '</script'; ?>
>
</head>
<body>
    <!-- <div style="position: absolute; top: 38.5px; left: 404.5px; z-index:1005; opacity:1; width: 454px; height: 370px;" >
    </div> -->
    <div style="margin-left:500px" id="brandDiv"><img src="../images/fotile.jpg" width="339px" height="55px" />
    </div>

<!--     <div class="topbar-wrap white">
        <div class="topbar-inner clearfix">
            <div class="topbar-logo-wrap clearfix">
                <h1 class="topbar-logo none"><a href="index.html" class="navbar-brand">后台管理</a></h1>
            </div>
        </div>
    </div> -->


    <div class="container clearfix">
        <div class="sidebar-wrap">
            <div class="sidebar-title">
                <h1>菜单</h1>
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
                    <!-- <li>
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
                            <li><a href="system.html"><i class="icon-font">&#xe017;</i>系统设置</a></li>
                        </ul>
                    </li> -->
                </ul>
            </div>
        </div>
        <!--/sidebar-->
        <div class="main-wrap">
            <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a >首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">设备管理</span></div>
            </div>

            
            <div class="result-wrap">
                <form name="myform" id="myform" method="post">
                    <div class="sidebar-title" style="display:inline;">
                        <span>当前用户:&nbsp;&nbsp;&nbsp;</span>
                        <span id="div_user" class="sidebar-title" ></span>
                        <span id="div_username">admin</span>
                    </span>
                    </div>
                   
                    <div class="result-title">
                        <div class="result-list">
                             <!-- <div class="search-wrap">
                                <div class="search-content">
                                    <form action="#" method="post"> -->
                                        <table class="search-tab" style="display:inline;">
                                            <tr>
                                                <th >选择类型:</th>
                                                <td>
                                                    <select name="search-sort" id="tab1">
                                                        <option value="0">全部</option>
                                                        <option value="1">蒸箱</option>
                                                        <option value="2">烤箱</option>
                                                        <option value="3">微波炉</option>
                                                        <option value="4">油烟机</option>
                                                        <option value="5">燃气灶</option>
                                                        <option value="6">消毒柜</option>
                                                    </select>
                                                </td>
                                            </tr>
                                         </table>
                             <!--       </form>
                                </div>
                            </div> -->
                            <a style="display:inline;" href="javascript:void(0)" onclick="addDevice()"><i class="icon-font"></i>新增设备</a>
                            <!-- <a id="batchDel" href="javascript:void(0)"><i class="icon-font"></i>批量删除</a> -->
                        </div>
                    </div>

                    <div class="result-content">
                        <table class="result-tab" width="100%">
                            <tr>
                                <!-- <th class="tc" width="5%"></th> -->
                                <th>序号</th>
                                <th>设备类型</th>
                                <th>设备名称</th>
                                <th>操作</th>
                            </tr>
                            <?php ob_start();
$_from = $_smarty_tpl->tpl_vars['devices']->value;
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
$_tmp1=ob_get_clean();
echo $_tmp1;?>

                                <tr>
                                    <td><?php ob_start();
echo $_smarty_tpl->tpl_vars['k']->value+1;
$_tmp2=ob_get_clean();
echo $_tmp2;?>
</td>  
                                    <td ><?php ob_start();
echo $_smarty_tpl->tpl_vars['v']->value[0];
$_tmp3=ob_get_clean();
echo $_tmp3;?>
</td> <!--设备类型-->
                                    <td><a href="<?php ob_start();
echo $_smarty_tpl->tpl_vars['v']->value[1];
$_tmp4=ob_get_clean();
echo $_tmp4;?>
"><?php ob_start();
echo $_smarty_tpl->tpl_vars['v']->value[2];
$_tmp5=ob_get_clean();
echo $_tmp5;?>
</a>
                                    </td>
                                    <td>
                                        <a id="delete<?php ob_start();
echo $_smarty_tpl->tpl_vars['k']->value+1;
$_tmp6=ob_get_clean();
echo $_tmp6;?>
" class="link-del" href="#" onclick="delDevice(<?php ob_start();
echo $_smarty_tpl->tpl_vars['v']->value[3];
$_tmp7=ob_get_clean();
echo $_tmp7;?>
)" >删除设备</a>
                                    </td>
                                </tr>                                                     
                            <?php ob_start();
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_1_saved_local_item;
}
if ($__foreach_v_1_saved_item) {
$_smarty_tpl->tpl_vars['v'] = $__foreach_v_1_saved_item;
}
if ($__foreach_v_1_saved_key) {
$_smarty_tpl->tpl_vars['k'] = $__foreach_v_1_saved_key;
}
$_tmp8=ob_get_clean();
echo $_tmp8;?>

                        </table>
                       
                    </div>
                    <div class="list-page">
                        2016 © 宁波方太厨具有限公司.
                    </div>
                </form>
            </div>
        </div>
        <!--/main-->
    </div>
</body>
</html><?php }
}
