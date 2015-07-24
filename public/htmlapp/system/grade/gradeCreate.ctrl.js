// gradeCreateCtrl.ctrl.js created by zhenghua@kidsit.cn on 20/01/2015 
(function () {
    'use strict';
    angular
        .module('gradeApp')
        .controller('gradeCreateCtrl',gradeCreateCtrl);

    gradeCreateCtrl.$inject = ['$scope','$window','khttp','$location','toastr'];

    function gradeCreateCtrl($scope,$window,vhttp,$location,toastr) {
        /*jshint validthis: true */
        var vm = this;
        vm.newGrade = null;
		vm.createGrade = function () {
			var promise;
			vm.newGrade._token = $window._token;
			vm.currentPromise = promise = vhttp.store("http://kidsit.cn/admin/api/system/grade",vm.newGrade);
			promise.then(
				function (gradesdata) {
					if (gradesdata.resp.code!==0){
						toastr.error(vm.newGrade.skillgradetitle+gradesdata.resp.message);
						return;
					}
					else{
						toastr.success(vm.newGrade.skillgradetitle+' 创建成功！');
					}
					
				},
				function () {
					toastr.error(vm.newGrade.skillgradetitle+' 创建出错，请重试！');
				}
			);
			// $location.path('/grade-list');
		};
		
    }
})();