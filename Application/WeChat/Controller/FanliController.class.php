<?php
namespace WeChat\Controller;
use Think\Controller;
use my_weixin\WechatAuth;
use my_weixin\jssdk;
header("content-type:text/html;charset=utf-8");
class FanliController extends Controller{	 
    /*�����ͨ�����ں�ֱ�ӽ���*/
    public function fanli(){  	      	       
        $jssdk=new jssdk();
        $jssdk=$jssdk->getSignPackage();
        $url="http://www.hailuohao.net/my_weixin/index.php/Home/Play_fanli/fanli_jianjie";
        $jssdk['url']=$url.'?popenid='.$openid;//����ת����url���ӵ�ַ
        $jssdk['imgurl']="http://www.hailuohao.net/my_weixin/public/images/tb.png";       
        $this->jssdk=$jssdk;
        $this->display();	
    }
    
    /*�����ͨ��ת����ӽ���*/
    public function fanli_jianjie(){
    	session_start();
        unset($_SESSION["openid"]);//���popenid�Ļ���        
    	$popenid=$_GET['popenid'];
    	//�����GET������������Ȩҳ�����Ӳ��Զ���ת����ҳ��
    	if(!empty($popenid)){
    		$_SESSION["popenid"]=$popenid;//���洫������popenid  			  		
    		$url_='http://www.hailuohao.net/my_weixin/index.php/Home/Play_fanli/fanli_jianjie';
    		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".$url_.'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
    		header("location:$url");//��ת����Ȩҳ�����ڻ�ȡ�û�openid
    	}else{
    		//�����header��ת��������ֱ�ӻ�ȡ�û���Ϣ
    		$wechat=new WechatAuth();//
    		$data=$wechat->getuserdata();//��ȡ�û���Ϣ
    		$openid=$data["openid"];//��ȡ�û�openid
    		$a=M("wechatfans")->where(array("openid"=>$openid))->find();
    		if(!empty($a)){//�����ע��������session openid
    			$_SESSION["openid"]=$openid;
    			$this->isguanzhu=1;
    		}
    		$jssdk=new jssdk();
	        $jssdk=$jssdk->getSignPackage();
	        $url="http://www.hailuohao.net/my_weixin/index.php/Home/Play_fanli/fanli_jianjie";	
	        $jssdk['url']=$url.'?popenid='.$_SESSION["popenid"];//����ת����url���ӵ�ַ	       
	        $jssdk['imgurl']="http://www.hailuohao.net/my_weixin/public/images/tb.png";
	        /*��ȡת���ķ�˿��Ϣ*/
	        $wechat=new WechatAuth();
	        $fansmesg=$wechat->getuserinformation(array(array('openid'=>$_SESSION["popenid"])));
	        $this->fansmesg=$fansmesg['0']; 
    	    $a=M("wechatfans")->where(array("openid"=>$_SESSION["popenid"]))->find();
    		if(!empty($a)){
    			$this->ispguanzhu=1;//���Ż�ȯҳ������Ƿ��ע(1Ϊ��ע)
    		}    	       
	        $this->jssdk=$jssdk;
	        $url_='http://www.hailuohao.net/my_weixin/index.php/Home/Play_fanli/fanli';
	        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".$url_.'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
	        $this->url=$url;
	        /*�ж��Ƿ����Լ���ҳ��*/
	        if($_SESSION["openid"]==$_SESSION["popenid"]){
	        	$this->iszj=1;
	        }
	        /*����*/
	        $zj=M('Totalfanli')->find();
	        $zj['ylq']=5*$zj['number'];
	        $this->zj= $zj;
    		$this->display('fanli');
    	}    	   	
    }
    
    //��ȡ�Żݾ�
    public function getyouhui(){
    	if(IS_POST){
    		if(empty($_SESSION["openid"])){
    			$result='���ע����ִ�д˲���';
    		}else{
    			$a=M('Fansbeifen')->where(array('openid'=>$_SESSION["openid"]))->find();
    			if(empty($a)){
    				M('Fansbeifen')->add(array('openid'=>$_SESSION["openid"],'islq'=>1));
    				$result='��ȡ���ֹ���ȯ�ɹ�,�뽫����ҳ���������';
    				//��ȡ��˿��Ϣ
    				$wechat=new WechatAuth();
    				$fansmesg=$wechat->getuserinformation(array(array('openid'=>$_SESSION["popenid"])));
    				$nickname=$fansmesg['0']['nickname'];
    				$headimgurl=$fansmesg['0']['headimgurl'];
    				$data['html']="<li class='headerimg'><p><img src='$headimgurl'/></p></li>
    				               <li>{$nickname}�Ļ��ֹ���ȯ</li>";
    				
    			}else{
    				if($a['islq']==1){
    					$result='���Ѿ���ȡ�ˣ������ظ���ȡ';
    				}else{
    					M('Fansbeifen')->save(array('id'=>$a["id"],'islq'=>1));
    					$result='��ȡ���ֹ���ȯ�ɹ�,�뽫����ҳ���������';
    					//��ȡ��˿��Ϣ
    					$wechat=new WechatAuth();
    					$fansmesg=$wechat->getuserinformation(array(array('openid'=>$_SESSION["popenid"])));   					
    					$nickname=$fansmesg['0']['nickname'];
    					$headimgurl=$fansmesg['0']['headimgurl'];    					
    					$data['html']="<li class='headerimg'><p><img src='$headimgurl'/></p></li>
		                                 <li>$nickname�Ļ��ֹ���ȯ</li>"; 
    				}
    			}   			
    		}
    		$data['result']=$result;
    		$this->ajaxReturn($data);
    	}
    }
    
    //�ж��Ƿ���ȡ�Ż�ȯ
    public function panduanislq(){
    	if(IS_POST){
    		$a=M('Fansbeifen')->where(array('openid'=>$_SESSION["openid"]))->find();
    		if(empty($a)){
    			$result='����δ��ȯ,�����ҳ����Ч';
    		}
    	}
    }
    
    //��֤������
    public function yanzheng(){
    	if(IS_POST){
    		session_start(); 
    		$data['code']=$_POST['code'];
    		$data['openid']=$_SESSION["openid"];
    		$data['popenid']=$_SESSION["popenid"];
    		$a=M("wechatfans")->where(array("openid"=>$data['popenid']))->find();
    		if(empty($a)){//�����ע��������session openid
    			$data['popenid']='';
    		}
    		$result='';    		    		
    		$result=$data['openid']==$data['popenid']?'�������Լ���ҳ��ִ�д˲���':$result;//�жϸ��û��Ƿ����Լ���ҳ��
    		$result=empty($data['openid'])?'����δ��ע����,���ע������ִ�д˲���':$result;//�жϸ��û��Ƿ��ע
    		$result=empty($data['popenid'])?'ת��������΢�ź�δ��ע����,�����ѶԷ���ע������ִ�д˲���':$result;//�ж��Ƽ��û��Ƿ��ע
    		/*�ж�ת�������Ƿ���ȡ���ֹ���ȯ*/
    		$a=M('Fansbeifen')->where(array('openid'=>$_SESSION["popenid"]))->find();
    		$result=$a['islq']!=1?'ת��������΢�ź�δ��ȡ���ֹ���ȯ,�����ѶԷ���ȡ������ִ�д˲���':$result;//�ж�ת�������Ƿ���ȡ���ֹ���ȯ
    		if(!$result){
    			$a=M('Fanli')->where(array('code'=>$data['code']))->find();
    			$result=empty($a)?'�ü����벻����':(time()-$a['sctime']>180?'�ü������Թ���':(!empty($a['openid'])?'�ü�������ʹ��':$result));
    		}
    		if(!$result){
    			$data['id']=$a['id'];
    			$data['time']=time();
    			$a=M('Fanli')->save($data);
    			if($a){
    				$wechat=new WechatAuth();
    				/*����֪ͨ���ϼ�*/
    				$nickname=M('Wechatfans')->where(array('openid'=>$data['openid']))->getField('nickname');
    				$result="��ϲ���Ƽ����û�:$nickname �ڱ�������,�������5Ԫ����(�뵽�з��ŵ���ȡ,��ַ:ܽ��������¥��������ó��A����һ¥,��һƽ���ú�ࡣ���ս���Ȩ�鱾��˾����),�绰:84431535";
    				$wechat->send_text($data['popenid'],$result);//΢��֪ͨ�û�
    				/*����֪ͨ�����뼤�������*/
    				$nickname=M('Wechatfans')->where(array('openid'=>$data['popenid']))->getField('nickname');
    				$result="��ϲ���ɹ����5Ԫ�Ż�ȯ:�ڱ�������,ת������ҳ����û�($nickname)�����5Ԫ����(����:".$a['id'].")";
    				$wechat->send_text($data['openid'],$result);//΢��֪ͨ�û�
    				$result='�����ɹ�';
    				$a=M('Totalfanli')->find();
    				$a['number']++;
    				M('Totalfanli')->save($a);
    			}else{
    				$result='�������ִ���';
    			}
    		}
    		echo $result;
    		//$this->ajaxReturn($result);
    	}
    }
}
