function getTimeAgo(time){
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
	}else{
		return '1年前';
	}
	
}
(function(dom) {
	//创建需求列表li元素
	dom.fillRequirementListLi = function(data) {
		console.log(JSON.stringify(data));
		var updatetime = data.updatetime;
		updatetime = updatetime.replace(/-/g,'/'); // 将-替换成/，因为下面这个构造函数只支持/分隔的日期字符串
		var date = new Date(updatetime);
		var time = getTimeAgo(date.getTime());

		var liContent = '';

		liContent += '<li class="mui-table-view-cell mui-media" id="requirementid-' + data.id+'">';
		liContent += 	'<div id="requirementBrief" class="requirement-brief" contactor="'+data.contactor+'" requirement-id="'+data.id+'" publishuser-id="'+data.userid+'">';
		liContent += 		'<div class="col-xs-2 mui-text-center">';
		liContent += 			'<div style="width:60px; height:60px;  overflow:hidden;">';
		liContent += 				'<img style="width:45px; height:45px; border-radius:100%;" src="'+data.picture+'" alt="个人头像">';
		liContent += 				'<div style="text-overflow: ellipsis;white-space: nowrap;text-align:center;margin-top:-4px;font-size:.5rem;overflow: hidden;">'+data.contactor+'</div>';
		liContent += 			'</div>';
		liContent += 		'</div>';				
		liContent += 		'<div class="col-xs-10">';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-8 mui-text-left">';
		liContent += 					'<p><span>求租'+data.court+'小区</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-4 mui-text-right">';
		liContent += 					'<p class="publish-date" style="">'+time+'</p>';
		liContent += 				'</div>';			
		liContent += 			'</div>';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-10 mui-text-left">';
		liContent += 					'<p><span>'+data.renttype+data.room+'室（'+data.apartmenttype+data.decorate+'）</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-2 mui-text-right">';
		liContent += 					'<p class="publish-date" style=""></p>';
		liContent += 				'</div>';
		liContent += 			'</div>';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-6 mui-text-left">';
		liContent += 					'<p><span>预算：'+data.price+'元/月</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-6 mui-text-right">';
		liContent += 					'<p class="publish-date" style="">电话:'+data.tel+'</p>';
		liContent += 				'</div>';
		liContent += 			'</div>';
		liContent += 		'</div>';	
		liContent += 	'</div>';
		liContent += '</li>';
						
		return liContent;
	}



	//创建房源列表li元素
	dom.fillSupplymentListLi = function(data) {
		console.log(JSON.stringify(data));
		var updatetime = data.updatetime;
		updatetime = updatetime.replace(/-/g,'/'); // 将-替换成/，因为下面这个构造函数只支持/分隔的日期字符串
		var date = new Date(updatetime);
		var time = getTimeAgo(date.getTime());

		var liContent = '';


		liContent += '<li class="mui-table-view-cell mui-media" id="supplymentid-' + data.id+'">';
		liContent += 	'<div id="supplymentBrief" class="supplyment-brief" contactor="'+data.contactor+'" supplyment-id="'+data.id+'" publishuser-id="'+data.userid+'">';
		liContent += 		'<div class="col-xs-2 mui-text-center">';
		liContent += 			'<div style="width:60px; height:60px;  overflow:hidden;">';
		liContent += 				'<img style="width:45px; height:45px; border-radius:100%;" src="'+data.picture+'" alt="个人头像">';
		liContent += 				'<div style="text-overflow: ellipsis;white-space: nowrap;text-align:center;margin-top:-4px;font-size:.5rem;overflow: hidden;">'+data.contactor+'</div>';
		liContent += 			'</div>';
		liContent += 		'</div>';				
		liContent += 		'<div class="col-xs-10">';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-8 mui-text-left">';
		liContent += 					'<p><span>出租'+data.court+'小区</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-4 mui-text-right">';
		liContent += 					'<p class="publish-date" style="">'+time+'</p>';
		liContent += 				'</div>';			
		liContent += 			'</div>';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-10 mui-text-left">';
		liContent += 					'<p><span>'+data.renttype+data.room+'室（'+data.apartmenttype+data.decorate+'）</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-2 mui-text-right">';
		liContent += 					'<p class="publish-date" style=""></p>';
		liContent += 				'</div>';
		liContent += 			'</div>';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-6 mui-text-left">';
		liContent += 					'<p><span>租金：'+data.price+'元/月</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-6 mui-text-right">';
		liContent += 					'<p class="publish-date" style="">电话:'+data.tel+'</p>';
		liContent += 				'</div>';
		liContent += 			'</div>';
		liContent += 		'</div>';	
		liContent += 	'</div>';
		liContent += '</li>';
						
		return liContent;
	}


	//创建我的需求列表li元素
	dom.fillMyRequirementListLi = function(data) {
		
		console.log(JSON.stringify(data));

		var updatetime = data.updatetime;
		updatetime = updatetime.replace(/-/g,'/'); // 将-替换成/，因为下面这个构造函数只支持/分隔的日期字符串
		var date = new Date(updatetime);
		var time = getTimeAgo(date.getTime());
		
		var liContent = '';
		
		liContent += '<li class="mui-table-view-cell mui-media" id="requirementid-' + data.id+'">';
		liContent += 	'<div id="requirementBrief" class="requirement-brief" contactor="'+data.contactor+'" requirement-id="'+data.id+'" publishuser-id="'+data.userid+'">';
		liContent += 		'<div class="col-xs-2 mui-text-center">';
		liContent += 			'<div style="width:60px; height:60px;  overflow:hidden;">';
		liContent += 				'<img style="width:45px; height:45px; border-radius:100%;" src="'+data.picture+'" alt="个人头像">';
		liContent += 				'<div style="text-overflow: ellipsis;white-space: nowrap;text-align:center;margin-top:-4px;font-size:.5rem;overflow: hidden;">'+data.contactor+'</div>';
		liContent += 			'</div>';
		liContent += 		'</div>';				
		liContent += 		'<div class="col-xs-10">';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-8 mui-text-left">';
		liContent += 					'<p><span>求租'+data.court+'小区</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-4 mui-text-right">';
		liContent += 					'<p class="publish-date" style="">'+time+'</p>';
		liContent += 				'</div>';			
		liContent += 			'</div>';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-10 mui-text-left">';
		liContent += 					'<p><span>'+data.renttype+data.room+'室（'+data.apartmenttype+data.decorate+'）</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-2 mui-text-right">';
		liContent += 					'<p class="publish-date" style=""></p>';
		liContent += 				'</div>';
		liContent += 			'</div>';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-6 mui-text-left">';
		liContent += 					'<p><span>预算：'+data.price+'元/月</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-6 mui-text-right">';
		liContent += 					'<p class="publish-date" style="">电话:'+data.tel+'</p>';
		liContent += 				'</div>';
		liContent += 			'</div>';
		liContent += 		'</div>';	
		liContent += 	'</div>';
		liContent += '</li>';

		return liContent;
	}




	//创建我的房源列表li元素
	dom.fillMySupplymentListLi = function(data) {
		console.log(JSON.stringify(data));
	
		console.log('date:'+data.updatetime);
		var updatetime = data.updatetime;
		updatetime = updatetime.replace(/-/g,'/'); // 将-替换成/，因为下面这个构造函数只支持/分隔的日期字符串
		var date = new Date(updatetime);
		var time = getTimeAgo(date.getTime());
		
		var liContent = '';


		liContent += '<li class="mui-table-view-cell mui-media" id="supplymentid-' + data.id+'">';
		liContent += 	'<div id="supplymentBrief" class="supplyment-brief" contactor="'+data.contactor+'" supplyment-id="'+data.id+'" publishuser-id="'+data.userid+'">';
		liContent += 		'<div class="col-xs-2 mui-text-center">';
		liContent += 			'<div style="width:60px; height:60px;  overflow:hidden;">';
		liContent += 				'<img style="width:45px; height:45px; border-radius:100%;" src="'+data.picture+'" alt="个人头像">';
		liContent += 				'<div style="text-overflow: ellipsis;white-space: nowrap;text-align:center;margin-top:-4px;font-size:.5rem;overflow: hidden;">'+data.contactor+'</div>';
		liContent += 			'</div>';
		liContent += 		'</div>';				
		liContent += 		'<div class="col-xs-10">';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-8 mui-text-left">';
		liContent += 					'<p><span>出租'+data.court+'小区</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-4 mui-text-right">';
		liContent += 					'<p class="publish-date" style="">'+time+'</p>';
		liContent += 				'</div>';			
		liContent += 			'</div>';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-10 mui-text-left">';
		liContent += 					'<p><span>'+data.renttype+data.room+'室（'+data.apartmenttype+data.decorate+'）</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-2 mui-text-right">';
		liContent += 					'<p class="publish-date" style=""></p>';
		liContent += 				'</div>';
		liContent += 			'</div>';
		liContent += 			'<div>';
		liContent += 				'<div class="col-xs-6 mui-text-left">';
		liContent += 					'<p><span>租金：'+data.price+'元/月</span></p>';
		liContent += 				'</div>';
		liContent += 				'<div class="col-xs-6 mui-text-right">';
		liContent += 					'<p class="publish-date" style="">电话:'+data.tel+'</p>';
		liContent += 				'</div>';
		liContent += 			'</div>';
		liContent += 		'</div>';	
		liContent += 	'</div>';
		liContent += '</li>';

		return liContent;
	}



	//创建中介列表li元素
	dom.fillAgentListLi = function(data) {
		console.log(JSON.stringify(data));
		
		var liContent = '';

		liContent += '<li class="mui-table-view-cell">';
		liContent += 	'<div class="col-xs-10 mui-text-left">';
		liContent += 		'<div class="col-xs-12 mui-text-left">';
		liContent += 			'<img style="width:45px; height:45px; border-radius:100%;" src="'+data.picture+'" alt="个人头像">';;
		liContent += 		'</div>';
		liContent += 		'<div class="col-xs-12 mui-text-left">';
		liContent += 			'<p><span id="contactor-name">'+data.nickname+'</span></p>';
		liContent += 		'</div>';
		liContent += 		'<div class="col-xs-12 mui-text-left">';				
		liContent += 			'<p id="contactor-tel" class="publish-date" style="">'+data.phone+'</p>';
		liContent += 		'</div>';
		liContent += 	'</div>';
		liContent += 	'<div class="col-xs-2 mui-text-right">';
		liContent += 		'<input type="radio" name="agent" id="'+data.id+'" onclick="getValue(this)">';
		liContent += 	'</div>';
		liContent +=  '</li>';
		console.log('fillAgentListLi  liContent:  '+liContent);
		
		return liContent;
	}
	

})(window);