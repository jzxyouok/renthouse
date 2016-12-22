(function(c) {
	c.getByteLen = function(val) 
	{
        var len = 0;
        for (var i = 0; i < val.length; i++) {
             var a = val.charAt(i);
             if (a.match(/[^\x00-\xff]/ig) != null) 
            {
                len += 2;
            }
            else
            {
                len += 1;
            }
        }
        return len;
    }
	
	c.isCardNo = function(card)  
	{  
	   // 身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X  
	   var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;  
	   if(reg.test(card) === false)  
	   {  
	       //alert("身份证输入不合法");  
	       return  false;  
	   }
	   return true;
	}
	
	c.isEmail = function(email)
	{
		//email 含有@
		var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		if(!reg.test(email)) {
		    //alert("请输入有效的邮箱地址！");
		    return false;
		}
		return true;
		
	}
	
    
    c.getDayStamp = function() {
		var now = new Date();
		//var timestamp = now.getFullYear() + '' + ((now.getMonth() + 1) >= 10 ? "" + (now.getMonth() + 1) : "0" + (now.getMonth() + 1)) + (now.getDate() >= 10 ? now.getDate() : "0" + now.getDate()) + (now.getHours() >= 10 ? now.getHours() : "0" + now.getHours()) + (now.getMinutes() >= 10 ? now.getMinutes() : "0" + now.getMinutes()) + (now.getSeconds() >= 10 ? now.getSeconds() : "0" + now.getSeconds());
		var timestamp = now.getFullYear() + '' + ((now.getMonth() + 1) >= 10 ? "" + (now.getMonth() + 1) : "0" + (now.getMonth() + 1)) + (now.getDate() >= 10 ? now.getDate() : "0" + now.getDate());
		return timestamp;
	}
    
    c.formatStringyyyyMMddToTimeAgo = function(value){
    	var time = '很久以前';
		if(value && value.length == 14){
			var newTime = value.substring(0, 4) + "/" + value.substring(4, 6) + "/" + value.substring(6, 8) + ' '+value.substring(8, 10)+':'+value.substring(10, 12)+':'+value.substring(12, 14);
			//console.log('newTime1:'+ newTime);
			var date = new Date(newTime);
			//console.log('newTime2:'+ date.getTime());
			time = getTimeAgo(date.getTime());
			//console.log('time:'+time);
		}
		return time;
	}
    
    c.getTimeAgo = function(time){
		var timestamp = Date.parse(new Date());
	
		var timeago = (timestamp - time)/1000;
		// console.log(timeago);
		if (timeago < 20){
			return '刚刚';
		}
		if (timeago < 60){
			return timeago + '秒前';
		}
		if (timeago < 3600){
			return Math.floor(timeago/60) + '分钟前'
		}
		if (timeago < 86400){
			return Math.floor(timeago/3600) + '小时前'
		}
		if (timeago < 604800){
			return Math.floor(timeago/86400) + '天前'
		}
		if (timeago < 31536000){
			return Math.floor(timeago/604800) + '周前'
		}
	}
    
    c.sendSystemMsgToWildog = function(userid,nickname,content,callback)
    {
    	var wilddogioRef = new Wilddog("https://zmzf0001.wilddogio.com/" + userid);
		var wilddogioListRef = new Wilddog("https://zmzf0001.wilddogio.com/" + userid + "/list");
    	var message = {};
		message.sender = 'system'; //消息发送人
		message.sendee = userid; //接收者群组Im消息时，接收者为群组id
	
		// 是否为mcm消息 0普通im消息 1 start消息 2 end消息 3发送mcm消息
		message.content = content;
		message.headUrl = "http://www.lovespace.cc/weixin/img/favicon.ico";
		message.sendernickname = '系统消息';
		message.sendeenickname = nickname;
		message.offlinenum = 0;
		message.num = 1;
		
		message.time = getTimeStamp();
		message.type = "text";

		var childRef = wilddogioRef.child(message.sender + "/" + message.time);
		console.log("1111 childRef=" + childRef.toString());
		console.log("message="+JSON.stringify(message));
		childRef.set(message);
		
		childRef = wilddogioListRef.child(message.sender);
		console.log("2222 childRef=" + childRef.toString());
		console.log("message="+JSON.stringify(message));
		//childRef.set(message);
		
		childRef.once("value", function(data) {
	  		// 执行业务处理，此回调方法只会被调用一次
	  		console.log("data:"+JSON.stringify(data.val()));
	  		var listInfo = data.val();
	  		if(listInfo && listInfo.num)
	  		{
	  			//存在在原值基础上加1
	  			message.num = parseInt(listInfo.num) + 1;
	  			console.log("num:"+message.num);
	  		}
	  		else
	  		{
	  			//不存在设置为1
	  			message.num = 1;
	  		}
	  		childRef.set(message,function(data)
		  		{
		  			if(!data)
		  			{
		  				if(callback)
		  				{
		  					callback();
		  				}
		  			}
		  			else
		  			{
		  				alert('set error:'+data);
		  			}
		  		}
	  		);
	  		
		})
    }
    
    c.getTimeStamp = function() 
	{
		var now = new Date();
		var timestamp = now.getFullYear() + '' + ((now.getMonth() + 1) >= 10 ? "" + (now.getMonth() + 1) : "0" + (now.getMonth() + 1)) + (now.getDate() >= 10 ? now.getDate() : "0" + now.getDate()) + (now.getHours() >= 10 ? now.getHours() : "0" + now.getHours()) + (now.getMinutes() >= 10 ? now.getMinutes() : "0" + now.getMinutes()) + (now.getSeconds() >= 10 ? now.getSeconds() : "0" + now.getSeconds());
		return timestamp;
	}
	
	c.getQueryStrFromUrl = function(str) 
	{
		var urlString = String(window.document.location.href);
		var rs = new RegExp("(^|)" + str + "=([^&]*)(&|$)", "gi").exec(urlString), tmp;
		if (tmp = rs) {
		return tmp[2];
		}
		// parameter cannot be found
		return "";
	}
	
})(window);