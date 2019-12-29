<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/19 下午4:21
 * class:Producer.php
 * Project:YoSoos
 *
 */


namespace App\Rpc\Sync\Queue;

use EasySwoole\Component\Singleton;
use EasySwoole\Queue\Queue;

/**
 * Class Producer
 * @package App\Rpc\Sync
 * 生产者
 */
class Producer extends Queue
{
    use Singleton;
}