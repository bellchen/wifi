<?php

require_once (BASE_PATH . '/Request/BaseRequest.php');
require_once (BASE_PATH . '/Response/TextResponse.php');
require_once (BASE_PATH . '/Response/NewsResponse.php');
require_once (BASE_PATH	. '/obj/WifiToken.php');
require_once (BASE_PATH	. '/obj/WifiShop.php');
require_once (BASE_PATH	. '/obj/WifiDevice.php');
require_once (BASE_PATH	. '/common/auth.php');
class TextRequest extends BaseRequest {
	private $content;
	
	public function __construct($postObj) {
		parent::__construct ( $postObj );
		$this->content = $this->postObj->Content;
	}
	function __destruct() {
	}
	private function echoMsg($msg) {
		$textResponse = new TextResponse ();
		$textResponse->setContent ( $msg );
		$textResponse->setFromUserName ( $this->getToUserName () );
		$textResponse->setToUserName ( $this->getFromUserName () );
		$textResponse->send ();
	}
	public function handle() {
		if ($this->msgType != parent::TEXT) {
			if ($this->msgType == parent::EVENT) {
				$event = $this->postObj->Event;
				$eventKey = $this->postObj->EventKey;
				if ($event != "CLICK") {
					return;
				}
				$this->content = $eventKey;
			} else {
				return;
			} 
		}
		$wifiShop=WifiShop::queryByOpenID($this->getFromUserName());
		if ($wifiShop==false) {
			$wifiShop=new WifiShop(array("shopOpenID"=>$this->getFromUserName (),
												"identify"=>$this->content,"adminID"=>"1",
												"name"=>$this->getFromUserName()));
			$shopID=WifiShop::insert($wifiShop);
			if ($shopID==false) {
				$this->content('服务器操作失败，请稍后重试，或者联系坑爹的开发 bellchen 13676127604 ');
			}
			$wifiShop->setShopID($shopID);
			$this->echoMsg('提交成功，等待管理员添加设备，请联系微信号 lidongxin3 联系电话: 18818796337 购买设备!');
		}else{
			$wifiShop->identify=$this->content;
			WifiShop::updateIdentify($wifiShop);
			//if count device is none,echo tip for buy wifi 
			$deviceList=WifiDevice::queryByShopID($wifiShop->shopID);
			if ($deviceList==false) {
				//update identify of shop
				$this->echoMsg('你已经提交成功，请联系管理员微信号:lidongxin3购买设备，联系电话: 18818796337，如果你已经购买成功，请联系管理员添加设备');
				
			}else if(count($deviceList)==1){
				//if count device is one, echo token
				$deviceList[0]->wifiIdentify=$this->getNextidentify();
				$result='连接wifi的验证码是:';
				//get token
				$token=Auth::getNextToken($deviceList[0]->wifiID, $deviceList[0]->wifiIdentify);
				$result.=$token."；";
				$result.='点击下方连接可获取下一个验证码:';
				$result.='<a href="http://115.29.33.65/wifi/token.php?identify='.$deviceList[0]->wifiIdentify.'">'.$deviceList[0]->wifiName.'</a>';
				$deviceList[0]->isUsed=0; 
				$deviceList[0]->cookies="";
				WifiDevice::updateIdentifyIsUsedCookies($deviceList[0]);
				$this->echoMsg($result);
			}else{
				//if count device is great than one ,echo text with link 
				$result='请选择wifi设备:';
				for($i=0;$i<count($deviceList);$i++){
					$device=$deviceList[$i];
					$device->wifiIdentify=$this->getNextidentify();
					$result.=($i!=0)?"或者是：":"";
					$result.='<a href="http://115.29.33.65/wifi/token.php?identify='.$device->wifiIdentify.'">'.$device->wifiName."</a>";
					
					$device->isUsed=0;
					$device->cookies="";
					WifiDevice::updateIdentifyIsUsedCookies($device);
				}
				foreach ($deviceList as $device){
					
				}
				$result.='重复点击上面链接可以生成新token；如果有问题，请联系管理员:lidongxin3；联系电话:18818796337';
				//set cookies 's isused ==false
				$this->echoMsg($result);
			}
		}
	}
	
	public function getNextidentify(){
		$identify="";
		do {
			$identify = md5(StringHelper::generateString ().time());
			$tmp = WifiDevice::queryByIdentify ( $identify );
		} while ( $tmp != false );
		return $identify;
	}
	/**
	 *
	 * @return the $content
	 */
	public final function getContent() {
		return $this->content;
	}
	
	/**
	 *
	 * @param field_type $content        	
	 */
	public final function setContent($content) {
		$this->content = $content;
	}
}
?>