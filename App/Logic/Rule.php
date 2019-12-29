<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/28 上午11:35
 * class:Rule.php
 * Project:YoSoos
 *
 */

namespace App\Logic;


use App\Model\Rule as RuleModel;
use App\Util\Common;
use EasySwoole\Http\Request;

final class Rule
{
      public static function findAll( Request $req)
      {
          $limit = Common::limit($req);
          $key = $req->getQueryParam('key');
          $mode = RuleModel::create()->limit($limit['page'],$limit['limit'])->order('auto_id','desc')
              ->alias('r')
              ->withTotalCount()->join('admin u','u.auto_id = r.create_uid','right' )
              ->join('admin b','b.auto_id = r.update_uid','right')
              ->field('r.auto_id,r.title,r.create_time,u.name as create_user,b.name as update_user,r.update_time,r.category,r.body');
          if( $key )
          {
              $mode->where(['title' => ['%'.$key.'%','like']]);
          }
          $data = $mode->all();
          $rows = $mode->lastQueryResult()->getTotalCount();
          return ['data'=>$data, 'rows'=>$rows];
      }

      public static function save( array $data) : bool
      {
          $mode = RuleModel::create()->connection('write');
          $uid = Common::getUid();
          $data['update_uid'] =  $uid;
          if( isset($data['auto_id']) && $data['auto_id'])
          {
              $ret = $mode->update($data);
          }
          else
          {
              unset($data['auto_id']);
              $data['create_uid'] = $uid;
              $ret = $mode->data($data)->save();
          }
          return (bool) $ret  > 0 ;
      }


      public static function findOne(int $id )
      {
          return RuleModel::create()->get($id,true);
      }
}