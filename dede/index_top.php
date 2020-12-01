<?php
/**
 * 管理后台顶部
 *
 * @version        $Id: index_top.php 1 8:48 2010年7月13日 $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2004 - 2020, 上海卓卓网络科技有限公司 (DesDev, Inc.)
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require(dirname(__FILE__)."/config.php");
if($cuserLogin->adminStyle=='dedecms')
{
    include DedeInclude('templets/index_top1.htm');
}
else
{
    include DedeInclude('templets/index_top2.htm');
}
