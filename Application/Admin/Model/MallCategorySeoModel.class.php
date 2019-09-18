<?php
/**
 * 商城商品分类SEO模块业务逻辑
 * @author 幸福无期
 * @email  597089187@qq.com
 */

namespace Admin\Model;
use Think\Model;
class MallCategorySeoModel extends Model{
    protected $tableName='mall_category_seo'; 
    public function seoAdd($data_){
        $result = array(
            'status' => 1,
            'msg'    => '修改成功'
        );
        $data = array(
            'cat_id'      => $data_['cat_id'],
            'title'       => $data_['title'],
            'keyword'     => $data_['keyword'],
            'description' => $data_['description']
        );
        $model  = D('Mall_category_seo');
        $rules  = array(
            array('cat_id','/^([1-9]\d*)|0+$/','分类id不能为空'),
            array('title','require','必须输入描述',self::EXISTS_VALIDATE),
            array('keyword','require','必须输入关键字',self::EXISTS_VALIDATE),
            array('description','require','必须输入描述',self::EXISTS_VALIDATE)
        );
        if($model->validate($rules)->create($data) === false){
            $result = array(
                'status' => 0,
                'msg'    => $model->getError()
            );
            return $result;
        }     
        $r = M('Mall_category_seo')->add($data);
        if($r === false){
            $result = array(
                'status' => 1,
                'msg'    => '修改失败'
            );
        }else{
            $result['number'] = $r;
        }       
        return $result;
    }

    public function seoUpdate($data_){
        $result = array(
            'status' => 1,
            'msg'    => '修改成功'
        );
        $data = array(
            'id'          => $data_['id'],
            'title'       => $data_['title'],
            'keyword'     => $data_['keyword'],
            'description' => $data_['description']
        );
        $model  = D('Mall_category_seo');
        $rules  = array(
            array('id','/^([1-9]\d*)|0+$/','id不能为空'),
            array('title','require','必须输入描述',self::EXISTS_VALIDATE),
            array('keyword','require','必须输入关键字',self::EXISTS_VALIDATE),
            array('description','require','必须输入描述',self::EXISTS_VALIDATE)
        );
        if($model->validate($rules)->create($data) === false){
            $result = array(
                'status' => 0,
                'msg'    => $model->getError()
            );
            return $result;
        }     
        $id     = $data['id']; 
        $cat_id = $data['cat_id'];
        unset($data['id']);
        $r = M('Mall_category_seo')->where(array('id'=>$id))->save($data);
        if($r === false){
            $result = array(
                'status' => 1,
                'msg'    => '修改失败'
            );
        }else{
            $result['number'] = $r;
        }       
        return $result;
    }    
}