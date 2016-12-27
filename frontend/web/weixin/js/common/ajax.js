var baseUrl = "http://www.lovespace.cc/index.php?";
var baseUserUrl = baseUrl + 'r=user/';
var baseRequirementUrl = baseUrl + 'r=requirement/';
var baseSupplymentUrl = baseUrl + 'r=supplyment/';
var baseLoginUrl = "http://www.lovespace.cc/index.php?r=login/";

//公共
//authcode
var getVeriCodeUrl = baseLoginUrl + "get-verification-code";
var verifyverificationCodeUrl = baseLoginUrl + "verify-verification-code";

var addSupplymentUrl = baseSupplymentUrl + 'add-supplyment';
var addRequirementUrl = baseRequirementUrl + 'add-requirement';
var getSupplymentUrl = baseSupplymentUrl + 'get-supplyment';
var getRequirementUrl = baseRequirementUrl + 'get-requirement';
var getRequirementByIdUrl = baseRequirementUrl + 'get-requirement-by-id';
var getSupplymentByIdUrl = baseSupplymentUrl + 'get-supplyment-by-id';
var moidfyUserInfoUrl = baseUserUrl + "modify-user-info";
var getUserInfoUrl = baseUserUrl + 'get-user-info';
var getRequirementListUrl = baseRequirementUrl + "get-requirement-list";
var getSupplymentListUrl = baseSupplymentUrl + "get-supplyment-list";
var getUserByOpenidUrl = baseUserUrl + "get-user-by-openid";
var getRequirementByUserIdUrl = baseRequirementUrl + "get-requirement-byuserid";
var getRequirementByAgentIdUrl = baseRequirementUrl + "get-requirement-by-agentid";
var getHouseListUrl = baseSupplymentUrl + "get-my-house";
var getClientHouseListUrl = baseSupplymentUrl + "get-client-house";
var modifyRequirementUrl = baseRequirementUrl + "modify-requirement";
var modifySupplymentUrl = baseSupplymentUrl + "modify-supplyment";
var deleteSupplymentUrl = baseSupplymentUrl + "delete-supplyment-by-id";
var deleteRequirementUrl = baseRequirementUrl + "delete-requirement-by-id";
var getAgentListUrl = baseUserUrl + "get-agent-list";


function sendCommonAjax(options,url,sucFun,failFun)
{
	console.log('sendCommonAjax options:'+JSON.stringify(options));
	console.log('sendCommonAjax url:'+url);
	//console.log('sendCommonAjax sucFun:'+sucFun);
	//console.log('sendCommonAjax failFun:'+failFun);
	
	jQuery.ajax(url, {
		data: JSON.stringify(options),
		dataType: 'json', //服务器返回json格式数据
		type: 'post', //HTTP请求类型
		timeout: 10000, //超时时间设置为10秒；
		async: false,
		success: function(data) {
			sucFun(data);
		},
		error: function(xhr, type, errorThrown) {
			//addReportFailed(errorThrown);
			failFun(errorThrown);
		}
	});
}


(function(w) {
    //公共
	//authcode
	w.ajax_get_agent_list = function(options,sucFun,failFun)
	{
		console.log('ajax_get_agent_list');
		sendCommonAjax(options,getAgentListUrl,sucFun,failFun);
	}

	w.ajax_get_verification_code = function(options,sucFun,failFun)
	{
		console.log('ajax_get_verification_code');
		sendCommonAjax(options,getVeriCodeUrl,sucFun,failFun);
	}
	
	w.ajax_verify_verification_code = function(options,sucFun,failFun)
	{
		console.log('ajax_verify_verification_code');
		sendCommonAjax(options,verifyverificationCodeUrl,sucFun,failFun);
	}

	w.ajax_add_supplyment = function(options,sucFun,failFun)
	{
		console.log('ajax_add_supplyment');
		sendCommonAjax(options,addSupplymentUrl,sucFun,failFun);
	}

	w.ajax_add_requirement = function(options,sucFun,failFun)
	{
		console.log('ajax_add_requirement');
		sendCommonAjax(options,addRequirementUrl,sucFun,failFun);
	}

	w.ajax_get_supplyment = function(options,sucFun,failFun)
	{
		console.log('ajax_get_supplyment');
		sendCommonAjax(options,getSupplymentUrl,sucFun,failFun);
	}

	w.ajax_get_requirement = function(options,sucFun,failFun)
	{
		console.log('ajax_get_requirement');
		sendCommonAjax(options,getRequirementUrl,sucFun,failFun);
	}

	w.ajax_modify_user_info = function (options,sucFun,failFun)
	{
		console.log(JSON.stringify(options));
		sendCommonAjax(options,moidfyUserInfoUrl,sucFun,failFun);
	}

	w.ajax_get_user_info = function (options,sucFun,failFun) {
		console.log('ajax_get_user_info options:'+JSON.stringify(options));
		sendCommonAjax(options,getUserInfoUrl,sucFun,failFun);
	}

	w.ajax_get_requirement_by_id = function(options,sucFun,failFun)
	{
		console.log('ajax_get_requirement_by_id');
		sendCommonAjax(options,getRequirementByIdUrl,sucFun,failFun);
	}

	w.ajax_get_supplyment_by_id = function(options,sucFun,failFun)
	{
		console.log('ajax_get_supplyment_by_id');
		sendCommonAjax(options,getSupplymentByIdUrl,sucFun,failFun);
	}

	w.ajax_get_supplyment_list = function(options,sucFun,failFun)
	{
		console.log('ajax_get_supplyment_list');
		sendCommonAjax(options,getSupplymentListUrl,sucFun,failFun);
	}

	w.ajax_get_requirement_list = function(options,sucFun,failFun)
	{
		console.log('ajax_get_requirement_list');
		sendCommonAjax(options,getRequirementListUrl,sucFun,failFun);
	}

	w.ajax_get_requirement_byuserid = function(options,sucFun,failFun)
	{
		console.log('ajax_get_requirement_byuserid');
		sendCommonAjax(options,getRequirementByUserIdUrl,sucFun,failFun);
	}

	w.ajax_get_requirement_by_agentid = function(options,sucFun,failFun)
	{
		console.log('ajax_get_requirement_by_agentid');
		sendCommonAjax(options,getRequirementByAgentIdUrl,sucFun,failFun);
	}

	w.ajax_get_houselist = function(options,sucFun,failFun)
	{
		console.log('ajax_get_houselist');
		sendCommonAjax(options,getHouseListUrl,sucFun,failFun);
	}

	w.ajax_get_client_houselist = function(options,sucFun,failFun)
	{
		console.log('ajax_get_client_houselist');
		sendCommonAjax(options,getClientHouseListUrl,sucFun,failFun);
	}

	w.ajax_modify_requirement = function(options,sucFun,failFun)
	{
		console.log('ajax_modify_requirement');
		sendCommonAjax(options,modifyRequirementUrl,sucFun,failFun);
	}

	w.ajax_modify_supplyment = function(options,sucFun,failFun)
	{
		console.log('ajax_modify_supplyment');
		sendCommonAjax(options,modifySupplymentUrl,sucFun,failFun);
	}

	w.ajax_delete_supplyment = function(options,sucFun,failFun)
	{
		console.log('ajax_delete_supplyment');
		sendCommonAjax(options,deleteSupplymentUrl,sucFun,failFun);
	}

	w.ajax_delete_requirement = function(options,sucFun,failFun)
	{
		console.log('ajax_delete_requirement');
		sendCommonAjax(options,deleteRequirementUrl,sucFun,failFun);
	}


})(window);