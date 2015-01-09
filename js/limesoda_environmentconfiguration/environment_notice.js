document.observe("dom:loaded", function() {
	try {

		var limesoda_environment = WindowUtilities.getCookie('limesoda_environment');

		var limesoda_environment_notice = document.createElement('div');
		limesoda_environment_notice.className = 'limesoda_admin_environment_notice';
		limesoda_environment_notice.innerHTML = '<span class="limesoda_environment_notice_heading">ENV: </span>' + limesoda_environment;

		var magento_admin_header = document.getElementsByClassName('header')[0];
		magento_admin_header.parentNode.insertBefore(limesoda_environment_notice, magento_admin_header);

	} catch(err) {
		// console.log(err);
	}
});