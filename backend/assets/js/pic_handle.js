/**
 * This plugin makes it simple to convert to and from JSON.
 *
 * var thing = {plugin: 'jquery-json', version: 2.2};
 *
 * var encoded = $.toJSON(thing);              //'{"plugin":"jquery-json","version":2.2}'
 * var name = $.evalJSON(encoded).plugin;      //"jquery-json"
 * var version = $.evalJSON(encoded).version;  // 2.2
 *
 * @cat Plugins/Json
 * @author http://code.google.com/p/jquery-json/
 */
(function($){$.toJSON=function(o){if(typeof(JSON)=='object'&&JSON.stringify)return JSON.stringify(o);var type=typeof(o);if(o===null)return"null";if(type=="undefined")return undefined;if(type=="number"||type=="boolean")return o+"";if(type=="string")return $.quoteString(o);if(type=='object'){if(typeof o.toJSON=="function")return $.toJSON(o.toJSON());if(o.constructor===Date){var month=o.getUTCMonth()+1;if(month<10)month='0'+month;var day=o.getUTCDate();if(day<10)day='0'+day;var year=o.getUTCFullYear();var hours=o.getUTCHours();if(hours<10)hours='0'+hours;var minutes=o.getUTCMinutes();if(minutes<10)minutes='0'+minutes;var seconds=o.getUTCSeconds();if(seconds<10)seconds='0'+seconds;var milli=o.getUTCMilliseconds();if(milli<100)milli='0'+milli;if(milli<10)milli='0'+milli;return'"'+year+'-'+month+'-'+day+'T'+hours+':'+minutes+':'+seconds+'.'+milli+'Z"'}if(o.constructor===Array){var ret=[];for(var i=0;i<o.length;i++)ret.push($.toJSON(o[i])||"null");return"["+ret.join(",")+"]"}var pairs=[];for(var k in o){var name;var type=typeof k;if(type=="number")name='"'+k+'"';else if(type=="string")name=$.quoteString(k);else continue;if(typeof o[k]=="function")continue;var val=$.toJSON(o[k]);pairs.push(name+":"+val)}return"{"+pairs.join(", ")+"}"}};$.evalJSON=function(src){if(typeof(JSON)=='object'&&JSON.parse)return JSON.parse(src);return eval("("+src+")")};$.secureEvalJSON=function(src){if(typeof(JSON)=='object'&&JSON.parse)return JSON.parse(src);var filtered=src;filtered=filtered.replace(/\\["\\\/bfnrtu]/g,'@');filtered=filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']');filtered=filtered.replace(/(?:^|:|,)(?:\s*\[)+/g,'');if(/^[\],:{}\s]*$/.test(filtered))return eval("("+src+")");else throw new SyntaxError("Error parsing JSON, source is not valid.");};$.quoteString=function(string){if(string.match(_escapeable)){return'"'+string.replace(_escapeable,function(a){var c=_meta[a];if(typeof c==='string')return c;c=a.charCodeAt();return'\\u00'+Math.floor(c/16).toString(16)+(c%16).toString(16)})+'"'}return'"'+string+'"'};var _escapeable=/["\\\x00-\x1f\x7f-\x9f]/g;var _meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'}})(jQuery);

var context = {};

context.lang = {
	uploader_queue_limit_exceeded: "\u4e0a\u4f20\u5931\u8d25\uff1a\u4e00\u6b21\u6700\u591a\u9009\u62e9 1 \u5f20\u9700\u8981\u4e0a\u4f20\u7684\u56fe\u7247",
	uploader_queue_zero_byte_file: "\u4e0a\u4f20\u5931\u8d25\uff1a\u9009\u62e9\u7684\u56fe\u7247\u6587\u4ef6\u4e3a\u7a7a\uff0c\u8bf7\u66f4\u6362\u5176\u4ed6\u56fe\u7247",
	uploader_queue_file_exceeds_size_limit: "\u4e0a\u4f20\u5931\u8d25\uff1a\u5355\u5f20\u56fe\u7247\u5927\u5c0f\u4e0d\u5f97\u8d85\u8fc75MB",
	uploader_queue_invalid_filetype: "\u4e0a\u4f20\u5931\u8d25\uff1a\u4e0a\u4f20\u56fe\u7247\u4ec5\u652f\u6301jpg\/jpeg\/gif\/png\u6587\u4ef6\u540e\u7f00",
	uploader_upload_limit_exceeded: "\u4e0a\u4f20\u5931\u8d25\uff1a\u514d\u8d39\u7528\u6237\u6700\u591a\u5141\u8bb8\u53d1\u5e03 6 \u5f20\u56fe\u7247",
	uploader_upload_failed: "\u4e0a\u4f20\u5931\u8d25\uff1a\u7cfb\u7edf\u5904\u4e8e\u5fd9\u788c\u72b6\u6001\uff0c\u8bf7\u5c1d\u8bd5\u91cd\u65b0\u4e0a\u4f20",
	uploader_upload_http_error: "\u4e0a\u4f20\u5931\u8d25\uff1a\u7cfb\u7edf\u5904\u4e8e\u5fd9\u788c\u72b6\u6001\uff0c\u8bf7\u5c1d\u8bd5\u91cd\u65b0\u4e0a\u4f20",
	uploader_upload_unknown_error: "\u4e0a\u4f20\u5931\u8d25\uff1a\u53d1\u751f\u672a\u77e5\u9519\u8bef\uff08Code: -10\uff09\uff01\u8bf7\u5237\u65b0\u9875\u9762\u91cd\u8bd5\uff0c\u5982\u679c\u4ecd\u7136\u4e0a\u4f20\u5931\u8d25\uff0c\u8bf7\u8054\u7cfb\u7ba1\u7406\u5458",
	uploader_upload_success: "\u4e0a\u4f20\u6210\u529f ",
	uploader_remove_preview: "\u5220\u9664\u8fd9\u5f20\u56fe\u7247"
};

/**
* Swfuploade client-side configuration
*
*
* @author Saturn
* @since  1.0.0
*/
var uploader;
uploader = new SWFUpload({
	// Backend Settings
	flash_url : base_url + "assets/js/swfupload/swfupload.swf",
	upload_url: site_url + "/admin/home/news_pic",	
	post_params : {
		// Security consideration:
		// Manually attach hashed IP of the current session to the bloody flash uploader,
		// on the server side, we should test against this value with the IP of the flash player 
		"uploader_sig" : context.uploader_sig
	},

	// File Upload Settings
	file_size_limit : "3 MB",	// 5MB
	file_types : "*.jpg;*.jpeg;*.gif;*.png",
	file_types_description : "Images",
	file_upload_limit : 6,

	// Event Handler Settings
	file_queue_error_handler : uploader_fileQueueError,
	file_dialog_complete_handler : uploader_fileDialogComplete,
	upload_start_handler: uploader_uploadStart,
	upload_error_handler : uploader_uploadError,
	upload_success_handler : uploader_uploadSuccess,
	upload_complete_handler : uploader_uploadComplete,

	// Button Settings
	button_image_url : site_url + "/admin/assets/bootstrap/img/upload_button.jpg",
	button_placeholder_id : "upload_with_flash",
	button_width: 61,
	button_height: 22,
	button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
	button_disabled : false,
	button_cursor : SWFUpload.CURSOR.HAND,
	button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,

	// Debug Settings
	debug: true
});

/**
* Delete populated image from image list
*
*
* @author Saturn
* @since  1.0.0
*/
$('ul#images_list li span.action a').live('click', function(){
	remove_preview(this);
});

/**
* Add loading image when uploading
*
*
* @author Saturn
* @since  1.0.0
*/
function uploader_uploading_img(file) {
	jQuery('#images_table').show();
	jQuery("#images_list").append(
		jQuery('<li />', {id: 'li_img_' + file.id})
				.append(jQuery('<img src="' + context.assets_url + 'imgs/img_loader.gif" class="img_border" width="64" height="64" />'))
	);
}

/**
* Remove a preview node
*
*
* @author Saturn
* @since  1.0.0
*/
function remove_preview(a) {
	// Remove the current li from the DOM
	$(a).parent().parent().remove();
	// Remove the current image from the queue
	var stats = uploader.getStats();
	stats.successful_uploads = stats.successful_uploads -1;
	uploader.setStats(stats);
	if($('#images_list').has('li').length == 0) {
		$('#images_table').hide();
	}
}

/**
* Construct html when uploading completes
*
*
* @author Saturn
* @since  1.0.0
*/
function uploader_uploaded_img(file, imgUrl) {
	jQuery('#images_table').show();

	var li = jQuery("#li_img_" + file.id);
	if(li.length == 0) li = jQuery('<li />', {id:"li_img_" + file.id}).appendTo(jQuery("#images_list"));
	else li.html('');
	li.append(jQuery("<input />", {type:"hidden", name:"images[]", value:imgUrl}))
		.append(jQuery("<img />", {src: imgUrl.replace(/\.\w+$/, '_sq' + imgUrl.match(/\.\w+$/)), width:64, height:64}).addClass("img_border"))
		.append(jQuery("<span />")
			.addClass("action")
			.append(jQuery('<img height="8" width="10" align="absmiddle" src="' + context.assets_url + 'imgs/correct.gif">'))
			.append(context.lang.uploader_upload_success)
			.append(
				jQuery("<a></a>", {href:"javascript:void(0)"})
					.click(function(){
						remove_preview(this);
					})
					.append(jQuery('<img />', {
						src: context.assets_url + "imgs/delete.gif",
						align: "absmiddle",
						width: 10,
						height: 10,
						alt: context.lang.uploader_remove_preview,
						border: 0
					}))
			)
		);
}

/**
* fileQueueError Handler
*
*
* @author Saturn
* @since  1.0.0
*/
function uploader_fileQueueError(file, errorCode, message) {
	try {
		var error = '';

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			error = context.lang.uploader_queue_zero_byte_file;
			break;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			error = context.lang.uploader_queue_file_exceeds_size_limit;
			break;
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			error = context.lang.uploader_queue_limit_exceeded;
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
		default:
			error = context.lang.uploader_queue_invalid_filetype;
			break;
		}

		if(error != '') {
			alert(error);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

/**
* fileDialogComplete Handler
*
*
* @author Saturn
* @since  1.0.0
*/
function uploader_fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
			this.setButtonDisabled(true);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploader_uploadStart(file) {
	try {
		var uploaded = $('#image_list').has('li').length;
		if(uploaded >= 6) {
			alert(context.lang.uploader_upload_limit_exceeded);
			this.stopUpload();
			this.setButtonDisabled(true);
			return false;
		}

		// IF AND ONLY IF there is no duplicate ID should we created the preview img
		if($('#li_img_' + file.id).empty()){
			uploader_uploading_img(file);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

/**
* uploadSuccess Handler
*
*
* @author Saturn
* @since  1.0.0
*/
function uploader_uploadSuccess(file, serverData) {
	try {
		var code = $.evalJSON(serverData).code;
		var data = $.evalJSON(serverData).data;
		if(code > 0) {
			// Notify the user about this error
			alert(data);
			// Remove the uploading image in error
			$("#li_img_" + file.id).remove();
			// Notify swfupload about this error and refresh the queue
			// we should not count this image in the queue
			var stats = this.getStats();
			stats.successful_uploads = stats.successful_uploads - 1;
			this.setStats(stats);
			// Lastly, cancel this upload
			this.cancelUpload(file.id);
			return false;
		} else {
			uploader_uploaded_img(file, data);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

/**
* uploadComplete Handler
*
*
* @author Saturn
* @since  1.0.0
*/
function uploader_uploadComplete(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			this.setButtonDisabled(false);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

/**
* uploadError Handler
*
*
* @author Saturn
* @since  1.0.0
*/
function uploader_uploadError(file, errorCode, message) {
	try {
		var error = '';

		switch (errorCode) {
		case SWFUpload.UPLOAD_LIMIT_EXCEEDED:
			error = context.lang.uploader_upload_limit_exceeded;
			break;
		case SWFUpload.UPLOAD_FAILED:
			error = context.lang.uploader_upload_failed;
			break;
		case SWFUpload.HTTP_ERROR:
			error = context.lang.uploader_upload_http_error;
			break;
		default:
			error = context.lang.uploader_upload_unknown_error;
			break;
		}

		if(error != '') {
			alert(error);
		}
	} catch (ex) {
		this.debug(ex);
	}
}