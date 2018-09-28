<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/04
 * Time: 22:04
 */

namespace app\api\controller\validate;


use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 检测所有客户端发来的参数是否符合验证类规则
     * 基类定义了很多自定义验证方法
     * 这些自定义验证方法其实，也可以直接调用
     * @throws ParameterException
     * @return true
     */
    public function goCheck()
    {
        $request = Request::instance(); //获取所有传入参数
        $param = $request->param();
        $result = $this->batch()->check($param); //参数验证
        if (!$result) {
            $e= new ParameterException([
                'msg'=>$this->error
            ]); //抛出异常
            throw $e;
        } else {
            return true;
        }
    }
    /**
     * 自定义验证规则,写在基类里继承
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool|string
     */
    //自定义验证规则
    protected function isPositiveInteger($value)
    {
        //此处使用正则最好了
        if (preg_match('/^[0-9]*[1-9][0-9]*$/', $value)) {
            return true;
        } else {
            return false; //为false时,message自动报错
        }
    }
    //自定义验证规则,非空验证
    protected function isNotEmpty($value)
    {
        if (!empty($value)) {
            return true;
        } else {
            return false; //为false时,message自动报错
        }
    }
    //验证是否为手机号码
    protected function isMobile($value){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    //filter 根据$rule规则只返回限定的参数
    public function getDataByRule($params){
        if(array_key_exists('user_id',$params)|array_key_exists('uid',$params)){
            throw new ParameterException([
                'msg'=>'参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $arrays=[];
        //$this->rule 当前类访问$rule属性
        foreach($this->rule as $key=>$val){
            $arrays[$key]=$params[$key];
        }
        return $arrays;
    }


}