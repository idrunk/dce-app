<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-02-16 17:30
 */

/**
 * Cgi版Web应用入口文件，将Nginx、Apache、IIS的默认文档设置为index.php，并将文档根目录设为当前目录
 */

require_once '../vendor/autoload.php';
dce\Dce::boot();