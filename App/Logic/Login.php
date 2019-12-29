<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/22 下午3:48
 * class:Login.php
 * Project:YoSoos
 *
 */

namespace App\Logic;
use App\Model\Admin;
use App\Model\AdmTemMsg;
use App\Model\Group;
use App\Util\Common;
use App\Util\Constans;
use EasySwoole\Validate\Validate;
use \Swoole\Coroutine\Channel;

class Login
{
    static public function findByPhone( $phone ) : ? array
    {
        return Admin::create()
            ->field('auto_id,phone,password,name,group_id')
            ->findOne(['phone'=>$phone]);

    }

    /**
     * @param array $data
     *
     * @return bool|string
     * 验证登录时提交过来的数据
     */
   static public function vali(array $data)
    {
        $valitor = new Validate();
        $valitor->addColumn('phone')
            ->required('手机号不能为空')->lengthMin(11,'手机号格式不正确')
            ->lengthMax(12,'手机号格式不正确');
        $valitor->addColumn('password')->required('手机号不能为空');
        $bool = $valitor->validate($data);
        return $bool ? true : $valitor->getError()->__toString();
    }

    /**
     * @param array $admin
     * 登录成功后的操作
     *
     * @return array
     */
    static public function loginedSuccess( array $admin) : array
    {
        $chan = new Channel(4);
        $chans = 4;
        $all=[];
        self::getGroup($chan,$admin);
        self::teamMsg($chan,$admin);
        self::getMenu($chan,$admin);
        self::createToken($chan,$admin['auto_id']);
        for($i=0;$i<$chans;$i++)
        {
            $all += $chan->pop(2);
        }
        $token = $all['token'];
        unset($all['token']);
        self::setPri($admin);
        return ['token' => $token,
            'name' => $admin['name'], 'phone' => $admin['phone'],'all'=>$all];
    }

    /**
     * @param int $group_id
     *
     * @return bool|mixed
     * 判断登录用户是否有权在维护模式下登录
     */
    static public function isReadOnly( int $group_id )
    {
        $res = [];
        $chan = new Channel(2);
        go(function() use ($chan,$group_id){
           $r = Group::create()->where(['auto_id'=>$group_id])->field('read_only_login')
                ->findOne();
           $chan->push($r);
        });
        go(function() use ($chan) {
            $ab = System::findAll(['is_read_only','is_read_only_msg']);
            $chan->push( array_column($ab,'v','k'));
        });
        for( $i=0;$i<2;$i++)
        {
            $res += $chan->pop();
        }
       if( $res['is_read_only'] == 'y' )
       {
           if( $res['read_only_login'] == 'y')
           {
                return true;
           }
           else
           {
               return $res['is_read_only_msg'];
           }
       }
       return true;
    }

    /**
     * @param Channel $chan
     * @param array $admin
     *
     * @return void 生成后台前端的左侧菜单
     * 生成后台前端的左侧菜单
     */


    static private function getGroup(Channel $chan,array &$admin) : void
    {
        go(function() use ($chan,$admin){
            Common::redis()->hMSet(Constans::REDIS_ADMIN_USER_KEY . md5($admin['auto_id']),
                $admin );
            $token_life_time = (int) System::findAll('token_life_time')['v'];
            Common::redis()->expire(Constans::REDIS_ADMIN_USER_KEY . md5($admin['auto_id']),$token_life_time * 60);
            $group = Group::create()->field(['name','login_url'])->get($admin['group_id'])->toArray();
            $chan->push( $group );
        });
    }

    static private function teamMsg(Channel $chan ,array &$admin) : void
    {
        go(function() use ($chan,$admin){
            $msg = AdmTemMsg::create()->where(['read_time'=>'0'])
                ->where('target_id',$admin['auto_id'])->count();
            $chan->push(['is_has_notic'=>$msg]);
        });
    }


    static private function getMenu(Channel $chan,array &$admin) : void
    {
        go(function()use($chan,$admin){
            $menu = RoleMenu::findGroupMenu($admin['group_id']);
            $chan->push(['menu'=>Common::create_menu($menu)]);
        });

    }

    static private function setPri(array &$admin) : void
    {
        go(function() use ($admin){
            $role = Common::redis()->sMembers(Constans::REDIS_ADMIN_ROLE . md5($admin['auto_id']));
            if(empty( $role ) )
            {
                $role = Pri::getPrisUrlByGroupId($admin['group_id']);
                Common::redis()->sAdd(Constans::REDIS_ADMIN_ROLE . md5($admin['auto_id']),...$role);
            }
        });
    }


    static private function createToken(Channel $chan, int $uid)
    {
        go(function()use($chan,&$uid){
            $token_life_time = (int) System::findAll('token_life_time')['v'];
            $token = Common::createToken(md5($uid),$token_life_time * 60);
            $chan->push(['token'=>$token]);
        });
    }
}