(function(l) {
	
	var needLoginDiv;
	var displayAfterLoginDiv;
	var needWxBrowserDiv;

	
	l.initCheckView = function()
    {
    	needLoginDiv = byId("needLoginDiv");
	    displayAfterLoginDiv = byId("displayAfterLoginDiv");
		needWxBrowserDiv = byId("needWxBrowserDiv");
		
		openid = localStorage.getItem('openid');
		var data = localStorage.getItem('zufangLoginUserInfo');
		userinfo = JSON.parse(data);
		
		var homeUrl = arguments[0] ? arguments[0] : './index.html';
  		var regUrl = arguments[1] ? arguments[1] : './Mine/reg.html';
  
		//为返回按钮添加事件
		document.querySelector('.need-wxbrower button').addEventListener('tap', function() {
			mui.openWindow({
				url: homeUrl
			});
		}, false);
		
		//为登录绑定按钮添加事件
		document.querySelector('.need-login button').addEventListener('tap', function() {
			mui.openWindow({
				url: regUrl
			});
		}, false);
    }
	
	l.showLoginDiv = function()
    {
    	var needLoginDiv = byId("needLoginDiv");
	    var displayAfterLoginDiv = byId("displayAfterLoginDiv");
		var needWxBrowserDiv = byId("needWxBrowserDiv");
    	needLoginDiv.classList.remove("mui-hidden");
		needWxBrowserDiv.classList.add("mui-hidden");
		displayAfterLoginDiv.classList.add("mui-hidden");
    }
	        
    l.showNeedWxBrowserDiv = function()
    {
    	var needLoginDiv = byId("needLoginDiv");
	    var displayAfterLoginDiv = byId("displayAfterLoginDiv");
		var needWxBrowserDiv = byId("needWxBrowserDiv");
		
    	needLoginDiv.classList.add("mui-hidden");
		needWxBrowserDiv.classList.remove("mui-hidden");
		displayAfterLoginDiv.classList.add("mui-hidden");
    }


    l.checkLoginUserInfo = function(haveLoginUserInfoJumpUrl,noLoginUserInfoBindUrl)
    {
    	var loginUserInfo = localStorage.getItem('zufangLoginUserInfo');
		if(loginUserInfo)
		{
			if(haveLoginUserInfoJumpUrl)
			{
				mui.openWindow({
				url: haveLoginUserInfoJumpUrl
			});
			}
		}
		else
		{
			//alert('openid null:'+openid);
			//跳转
			mui.openWindow({
				url: noLoginUserInfoBindUrl
			});
		}
    }        
		
	l.checkLoginUserPhone = function(noPhoneRegAfterJumpUrl,hasPhoneJumpUrl,noUserInfoJumpType,noLoginUserInfoBindUrl)
    {
    	//noUserInfoJumpType 没有用户信息的情况下 跳转类型，1 提示未正常登陆（非微信公众号登陆） 2 需要绑定，
    	//如果用户手机号码不存在，则提示需要绑定手机号
    	var loginUserInfo = JSON.parse(localStorage.getItem('zufangLoginUserInfo'));
    	console.log(loginUserInfo);
		if(loginUserInfo)
		{
			//alert('ccc:'+loginUserInfo.phone.length);
			//需要增加更多的手机号判断,可以在输入手机号码时判断
			if(loginUserInfo.phone && loginUserInfo.phone.length != 11 || (!loginUserInfo.phone))
			{
				localStorage.setItem('zufangRegAfterJumpUrl',noPhoneRegAfterJumpUrl);
				//没有绑定手机号码
				showLoginDiv();
			}
			else
			{
				mui.openWindow({
					url: hasPhoneJumpUrl
				});
			}
		}
		else
		{
			if(noUserInfoJumpType == 1)
			{
				showNeedWxBrowserDiv();
			}
			else if(noUserInfoJumpType == 2)
			{
				localStorage.setItem('zufangRegAfterJumpUrl',noPhoneRegAfterJumpUrl);
				mui.openWindow({
					url: noLoginUserInfoBindUrl
				});
			}
		}
    }
    
    l.checkUserPhone = function(noPhoneRegAfterJumpUrl,hasPhoneJumpUrl)
    {
    	//检查用户手机号是否存在
    	//调用函数的前提是用户信息已存在
    	//如果用户手机号码不存在，则提示需要绑定手机号
    	var loginUserInfo = JSON.parse(localStorage.getItem('zufangLoginUserInfo'));
		if(loginUserInfo)
		{
			//需要增加更多的手机号判断,可以在输入手机号码时判断
			if((loginUserInfo.phone && loginUserInfo.phone.length != 11) || (!loginUserInfo.phone))
			{
				localStorage.setItem('zufangRegAfterJumpUrl',noPhoneRegAfterJumpUrl);
				//没有绑定手机号码
				showLoginDiv();
			}
			else
			{
				if(hasPhoneJumpUrl)
				{
					mui.openWindow({
						url: hasPhoneJumpUrl
					});
				}
			}
		}
		
    }
    
})(window);