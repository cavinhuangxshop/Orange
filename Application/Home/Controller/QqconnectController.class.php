<?php

namespace Home\Controller;

use Think\Controller;
use Com\Qqconnect;
class QqconnectController extends Controller {
/* 

* Type���ͣ���ʼ��

* QQConnet  WeiboConnect 

*/

    public function index(){
	   
    switch ($_GET['type']) {

    /* QQ������¼ */

    case qq:
        $app_id = C('APP_ID');
        $scope = C('SCOPE');
        $callback = C('CALLBACK');
        $sns = new Qqconnect;

        $login_url = $sns->login($app_id, $callback, $scope);
	    header('Location:'.$login_url);

    break;


    /* Ĭ���޵�¼ */

    default:

    $this->error("��Ч�ĵ�������ʽ",U('Member/login'));

    break;

    }

    }  

      /*    

       * ������¼������Ϣ

       * ��ȡcode �� state״̬����ѯ���ݿ� 

       *  */

 public function callback() {

    switch ($_GET['type']) {

    /* ����QQ������¼����ֵ */

    case qq:

    empty($_GET['code']) && $this->error("��Ч�ĵ�������ʽ",U('Member/login'));

    $app_id = C('APP_ID');

                       $app_key = C('APP_KEY');

                        $callback = C('CALLBACK');

    $qq = new QQConnect;

    /* callback����openid��access_token */

    $back = $qq->callback($app_id , $app_key, $callback);
	

                        //��ֹˢ��

    empty($back) && $this->error("��������Ȩ��¼",U('Member/login'));

    //�˴�ʡ�����ݿ��ѯ����ѯ���ص�$back['openid']


  break;


    default:

    $this->error("��Ч�ĵ�������ʽ",U('Member/login'));

    break;

    }

    }

}  