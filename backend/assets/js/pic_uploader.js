var avatar_swfu;

$(window).load(function() {
	var settings = {
		flash_url : base_url + "assets/swfupload/Flash/swfupload.swf",
		upload_url: site_url + "/admin/home/news_pic",

		file_post_name : "Filedata",

		// File Upload Settings
		file_size_limit : "5 MB",
		file_types : "*.jpg;*.jpeg;*.gif;*.png",
		file_types_description : "Images",
		file_upload_limit : 0,
		file_queue_limit : 1,

		// Button settings
		button_text : "上传图片",
		button_text_top_padding : 5,
		button_placeholder_id : "pic_upload",
		button_width: 80,
		button_height: 22,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_disabled : false,
		button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,

		// Event Handler Settings
		file_queue_error_handler : avatar_fileQueueError,
		file_dialog_complete_handler : avatar_fileDialogComplete,
		upload_start_handler : avatar_uploadStart,
		upload_error_handler : avatar_uploadError,
		upload_success_handler : avatar_uploadSuccess,
		upload_complete_handler : avatar_uploadComplete,
		
		// Debug Settings
		debug: false	
	};

	avatar_swfu = new SWFUpload(settings);
 });

