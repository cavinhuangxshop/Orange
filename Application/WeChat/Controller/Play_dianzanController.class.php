<?php
namespace Home\Controller;
use Think\Controller;
use my_weixin\WechatAuth;
use my_weixin\jssdk;
header("content-type:text/html;charset=utf-8");
class Play_dianzanController extends Controller{		
    /*ֱ��ͨ�����ںŽ���*/
    public function dianzan(){
    	if(IS_POST){//�μӻ
	    		$a=M('Dianzan')->where(array('openid'=>$_SESSION['openid']))->find();
    			$error='';
    			/*�ж��û��Ƿ��ע*/
    	 		$b = M("wechatfans")->where(array("openid"=>$_SESSION['openid']))->find();
    			$error=empty($_SESSION['openid'])?'�޷���ȡ������Ϣ':(!empty($a)?'���Ѿ��μӻ��!�����ظ��μ�':(empty($b)?'������ע������':$error)); 
    			if(!$error){
    				$data['openid']=$_SESSION['openid'];
    				$id=M('Dianzan')->add($data);
    				if($id){
    					$error="�μӳɹ�";
    					session_start();
    					$_SESSION['openid']=$data['openid'];//�����û�openid
    					$_SESSION['popenid']=$data['openid'];//�����û�openid
    				}else{
    					$error="�μ�ʧ��";
    				}
    			}   
    			echo $error;
    	}else{
    		$wechat=new WechatAuth();//ʵ��������
    		$data=$wechat->getuserdata();//��ȡ�û���Ϣ
    		session_start();
    		if($data['openid']){
    			unset($_SESSION["openid"]);
    			unset($_SESSION["popenid"]);
    			$_SESSION['openid']=$data['openid'];//�����û�openid
    			$_SESSION['popenid']=$data['openid'];//�����û�popenid
    		} 
    		$a=M('Dianzan')->where(array('openid'=>$_SESSION['openid']))->find();//�鿴�÷�˿�Ƿ�μӻ
    		if($a){
    			$this->is_showbtn='y';//y��ʾ��ʾ�Ѿ��μ�
    			/*��ȡ��˿��Ϣ*/
    			$fansmesg=$wechat->getuserinformation(array(array('openid'=>$a['openid'])));
    			$this->fansmesg=$fansmesg['0'];
    			$this->number=$a['number'];
    			$a['sy_number']=$a['number']-$a['number_1'];
    			$this->data=$a;//��ȡ��˿������Ϣ
    		}else{
    			$this->is_showbtn='n';//n��ʾδ�μ�
    		}
    		$this->is_me='y';
    		/*jssdk����ת����Ϣ*/
    		$jssdk=new jssdk();
    		$jssdk=$jssdk->getSignPackage();
    		$url="http://www.hailuohao.net/my_weixin/index.php/Home/Play_dianzan/dianzan_";
    		$jssdk['url']=$url.'?popenid='.$_SESSION['openid'];//����ת����url���ӵ�ַ
    		$jssdk['imgurl']="http://www.hailuohao.net/my_weixin/public/images/header.png";//ת����ͼ��
    		$jssdk['desc']="hi!����".$this->fansmesg['nickname'].",���������,лл!";//ת������������
    		$this->jssdk=$jssdk;		
    		/*�жϷ�˿�Ƿ��ע*/
    		$b = M("wechatfans")->where(array("openid"=>$_SESSION['popenid']))->find();
    		if($b){
    			$this->is_guanzhu='y';
    		} 
    		/*�жϱ��������û��Ƿ��ע*/
    		$b = M("wechatfans")->where(array("openid"=>$_SESSION['popenid']))->find();
    		if($b){
    			$this->is_pguanzhu='y';
    		}   	
    		$this->display();
    	}
    }
    
    /*ͨ��ת������*/
   public function dianzan_(){
   	    $popenid=$_GET['popenid'];
   	    //�����GET������������Ȩҳ�����Ӳ��Զ���ת����ҳ��
    	if(!empty($popenid)){
    		unset($_SESSION["popenid"]);
    		$_SESSION["popenid"]=$popenid;//���洫������popenid
    		$url_='http://www.hailuohao.net/my_weixin/index.php/Home/Play_dianzan/dianzan_';
    		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".$url_.'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
    		header("location:$url");//��ת����Ȩҳ�����ڻ�ȡ�û�openid
    	}else{
    		//�����header��ת��������ֱ�ӻ�ȡ�û���Ϣ
    		$wechat=new WechatAuth();//
    		$data=$wechat->getuserdata();//��ȡ�û���Ϣ
    	    if($data['openid']){
    	     	$_SESSION["openid"]=$data["openid"];//���浱ǰ��˿��openid 
    		}  
    		/*��ȡת���˵���Ϣ*/   		
    		$a=M('Dianzan')->where(array('openid'=>$_SESSION['popenid']))->find();//�鿴�÷�˿�Ƿ�μӻ
    		if($a){
    			$fansmesg=$wechat->getuserinformation(array(array('openid'=>$_SESSION["popenid"])));
    			$this->fansmesg=$fansmesg['0'];
    			$this->is_showbtn='y';//y��ʾ��ʾ�Ѿ��μ�
    			$a['sy_number']=$a['number']-$a['number_1'];
    			$this->number=$a['number'];/*��ȡͶƱ��*/
    			$this->data=$a;//��ȡ��˿������Ϣ
    		}else{
    			$this->is_showbtn='n';//n��ʾδ�μ�
    		}   
    		/*jssdk����ת����Ϣ*/
    		$jssdk=new jssdk();
    		$jssdk=$jssdk->getSignPackage();
    		$url="http://www.hailuohao.net/my_weixin/index.php/Home/Play_dianzan/dianzan_";
    		$jssdk['url']=$url.'?popenid='.$_SESSION['popenid'];//����ת����url���ӵ�ַ
    		$jssdk['imgurl']="http://www.hailuohao.net/my_weixin/public/images/header.png";//ת����ͼ��
    		$jssdk['desc']="hi!����".$this->fansmesg['nickname'].",���������,лл!";//ת������������
    		$this->jssdk=$jssdk;
    		/*�ж��Ƿ�Ϊ�Լ���ҳ��*/
    		if($_SESSION["popenid"]==$_SESSION["openid"]){
    			$this->is_me='y';
    		}
    		/*�жϱ��������û��Ƿ��ע*/
    		$b = M("wechatfans")->where(array("openid"=>$_SESSION['popenid']))->find();
    		if($b){
    			$this->is_pguanzhu='y';
    		}
    		$this->display('dianzan');
    	}  	
   }

   /*ajax����*/
   public function dianzan_1(){
    	if(IS_POST){//�μӻ
    		$result='';
    		/*�ж��������û��Ƿ��ע*/
    		$a = M("wechatfans")->where(array("openid"=>$_SESSION['openid']))->find();
    		/*�жϱ��������û��Ƿ��ע*/
    		$b = M("wechatfans")->where(array("openid"=>$_SESSION['popenid']))->find();
    		/*�ж��Ƿ��ظ�����*/
    		$c=M('Dianzandata')->where(array('openid'=>$_SESSION['openid'],'toopenid'=>$_SESSION["popenid"]))->find();
    		$result=empty($a)?'����δ��ע,�޷��μ�����':(empty($b)?'�������ĸ÷�˿�Ѿ�ȡ����ע,�޷�����':(empty($c)?'':($_SESSION['openid']==$_SESSION['popenid']?'���Ѿ�������,�����ظ�������':'���Ѿ�ΪTA��������,�����ظ����� ��')));  
    		 if(!$result){ 
    			$id=M('Dianzandata')->add(array('openid'=>$_SESSION['openid'],'toopenid'=>$_SESSION["popenid"]));
    			if($id){
    				M('Dianzan')->where(array('openid'=>$_SESSION["popenid"]))->setInc('number',1);//������+1
    				$result='�����ɹ�';
    			}
    		}
    		echo $result;
    	}
    }
    
    /*ajax�һ��Żݾ�*/
    public function youhui_add(){
        if(IS_POST){
    	    $data=M('Dianzan')->where(array('openid'=>$_SESSION['openid']))->find();
    	    $sy_number=$data['number']-$data['number_1'];
    	    $result=$sy_number<10?'�����������޷��һ�':'';
    	    if(empty($result)){
    	    	$youhui_number=floor($sy_number/10);
    	    	$number_1=$youhui_number*10;
    	    	M('Dianzan')->id=$data['id'];
    	    	M('Dianzan')->youhui_number = array('exp',"youhui_number+$youhui_number");//�����Żݾ�����
    	    	M('Dianzan')->number_1 = array('exp',"number_1+$number_1");//�������õ���
    	    	$a=M('Dianzan')->save();
                if($a){
                	$result='�һ��ɹ�';
                }
    	    } 
    	    echo $result; 	    
    	}
    }
    
    /*��ȡ�Ż�ȯ*/
    public function get_youhui(){
    	if($_GET['flag']==1){
    		$url_='http://www.hailuohao.net/my_weixin/index.php/Home/Play_dianzan/get_youhui';
    		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".$url_.'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
    		header("location:$url");//��ת����Ȩҳ�����ڻ�ȡ�û�openid
    	}   	
    	$wechat=new WechatAuth();//ʵ��������
    	$data=$wechat->getuserdata();//��ȡ�û���Ϣ
    	/*�ж��û��Ƿ��ע*/
    	$a = M("wechatfans")->where(array("openid"=>$data['openid']))->find();
    	/*�ж��û��Ƿ�μ�*/
    	$b = M('Dianzan')->where(array('openid'=>$data['openid']))->find();
    	$result=empty($data['openid'])?'�޷���ȡ������΢����Ϣ':(empty($a)?'��δ��ע�����޷���ȡ':(empty($b)?'����δ�μӻ':''));
    	if(empty($result)){
    		$get_num=$b['youhui_number'];
    		if($get_num>0){
    			$a=M('Dianzan')->where(array("openid"=>$data['openid']))->save(array('youhui_number'=>0));//�Żݾ�������0
    			if($a){
    				$result="��ϲ�����ɹ���ȡ��Ʒȯ{$get_num}";
    				/*��ȡ�û�������Ϣ*/
    				$fansmesg=$wechat->getuserinformation(array(array('openid'=>$data['openid'])));
    				$fansmesg=$fansmesg['0'];
    				$content=$fansmesg['nickname']."�ɹ���ȡ��Ʒȯ".$get_num."��";
    				$wechat->send_text("oj0eNwmzIRwRVBblXS_EZFbq1TGU",$content);
    			}
    		}else{
    			$result="��Ʒȯ����Ϊ0���޷���ȡ";
    		}   		
    	}     	
    	echo "<script language='javascript'>alert('".$result."')</script>";
    }
}