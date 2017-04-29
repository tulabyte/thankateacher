app.service('FTAFunctions', function (Data, $state) {

	var self = this;

	this.test = function() {
		return "FTAFunctions works!";
	};

	/* ADMINS */
	// get selected admin
	this.getAdmin = function (id) {
		return Data.get('getAdmin?id='+id);
	};

	// get list of admins
	this.getAdminList = function () {
		return Data.get('getAdminList');
	};

	// get admin logs
	this.getAdminLogs = function () {
		return Data.get('getAdminLogs');
	};

	// deleted selected admin
	this.deleteAdmin = function (id) {
		return Data.get('deleteAdmin?id='+id);
	};

	// disable selected admin
	this.disableAdmin = function (id) {
		return Data.get('disableAdmin?id='+id);
	};

	// enable selected admin
	this.enableAdmin = function (id) {
		return Data.get('enableAdmin?id='+id);
	};

	/* USERS */
	// get selected user
	this.getUser = function (id) {
		return Data.get('getUser?id='+id);
	};

	// get list of users
	this.getUserList = function () {
		return Data.get('getUserList');
	};

	// deleted selected user
	this.deleteUser = function (id) {
		return Data.get('deleteUser?id='+id);
	};


	/*Messages*/
	// get selected message
	this.getMessageDetails = function (id) {
		return Data.get('getMessageDetails?id='+id);
	};

	/* CATEGORIES */
	// get selected category
	this.getCategory = function (id) {
		return Data.get('getCategory?id='+id);
	};

	// get list of categories
	this.getCategoryList = function () {
		return Data.get('getCategoryList');
	};

	// deleted selected category
	this.deleteCategory = function (id) {
		return Data.get('deleteCategory?id='+id);
	};

	/* COURSES */
	// get selected course
	this.getCourse = function (id) {
		return Data.get('getCourse?id='+id);
	};

	// get list of courses
	this.getCourseList = function () {
		return Data.get('getCourseList');
	};

	// deleted selected course
	this.deleteCourse = function (id) {
		return Data.get('deleteCourse?id='+id);
	};

	//delete file
	this.deleteFile = function (filename) {
		return Data.get('deleteFile?f='+filename);
	}

	/* MODULES */
	// get list of modules for a course
	this.getModuleList = function (course_id) {
		return Data.get('getModuleList?course_id='+course_id);
	};

	// deleted selected module
	this.deleteModule = function (id) {
		return Data.get('deleteModule?id='+id);
	};

	/* DASHBOARD */

	// get dashboard box stats
	this.getDashStats = function () {
		return Data.get('getDashStats');
	};

	// get latest subs for dash
	this.getLatestSubs = function () {
		return Data.get('getLatestSubs');
	};

	// get top users for dash
	this.getTopUsers = function () {
		return Data.get('getTopUsers');
	};

	// get top courses for dash
	this.getTopCourses = function () {
		return Data.get('getTopCourses');
	};

	// get new users for dash
	this.getNewUsers = function () {
		return Data.get('getNewUsers');
	};

	// get new payments for dash
	this.getNewPayments = function () {
		return Data.get('getNewPayments');
	};

	// get user and sub trends for Dash from API
	this.getDashTrends = function (period) {
		return Data.get('getDashTrends?start_date='+period.start_date+'&end_date='+period.end_date);
	};

	// get subscription list from API
	this.getSubscriptionList = function () {
		return Data.get('getSubscriptionList');
	};


});