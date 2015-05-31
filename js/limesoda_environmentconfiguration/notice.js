document.observe("dom:loaded", function() {
	try {
		var environment_name = WindowUtilities.getCookie('limesoda_environment');

		var limesoda_environment_notice = document.createElement('div');
		limesoda_environment_notice.className = 'limesoda_admin_environment_notice';

        var style = '';
        var background_color = '';
        var color = '';
        if (background_color = WindowUtilities.getCookie('limesoda_environment_background_color')) {
            limesoda_environment_notice.style.backgroundColor = background_color;
        }
        if (color = WindowUtilities.getCookie('limesoda_environment_color')) {
            limesoda_environment_notice.style.color = color;
        }

		limesoda_environment_notice.innerHTML = '<span class="limesoda_environment_notice_heading">ENV: </span>' + environment_name;

		var magento_admin_header = document.getElementsByClassName('header')[0];
		magento_admin_header.parentNode.insertBefore(limesoda_environment_notice, magento_admin_header);

	} catch(err) {
		// console.log(err);
	}
});
