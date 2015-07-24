// teacherList.ctrl.js created by zhenghua@kidsit.cn on 25/01/2015 
(function () {
    'use strict';
    angular
        .module('teacherApp')
        .controller('teacherListCtrl',teacherListCtrl);

    teacherListCtrl.$inject = ['$scope','khttp','$location','toastr','$q','$window','simplevalidate'];

    function teacherListCtrl($scope,khttp,$location,toastr,$q,$window,simplevalidate) {
        /*jshint validthis: true */
        var vm = this;
        var promise;
        vm.currentPromise = promise = khttp.getAll("http://kidsit.cn/admin/api/system/teacher/");
        promise.then(
            function(teachersdata) {/*success*/
                $scope.teachers = vm.teachersOrginal = teachersdata.resp.data;
                },
            function(status) {console.log("error status code:"+status);}
        );
        vm.deleteTeacher = function(teacher){

            khttp.destroy("http://kidsit.cn/admin/api/system/teacher/",teacher).then(

                function(res){
                    $scope.teachers.splice($scope.teachers.indexOf(teacher),1);
                    toastr.success(res.resp.message);
                },
                function(error){
                    toastr.error(res.resp.message);
                }
            );
            $location.path('/teacher-list');
        };
		vm.checkAndSaveTeacher = function(data,field,teacher,rules) {
			var d = $q.defer();
            var returned = simplevalidate.dovalidate(rules,data,
                                                     'http://kidsit.cn/admin/api/system/teacher/');
            returned.then(
                function (response) {
                    if (response){
                        // already exist
                        d.resolve(response);
                        toastr.error(response);
                    }else{
                        // can use and 0 returned by simplevalidation service
                        teacher[field] = data;
                        vm.currentPromise = promise = khttp.update("http://kidsit.cn/admin/api/system/teacher/"+teacher.id,teacher);
                        promise.then(
                            function(teacherdata) {/*success*/
                                if (teacherdata.resp.code !==0 ){
                                    d.resolve(teacherdata.resp.message);
                                    toastr.error(teacherdata.resp.message);
                                }
                                else{
                                        d.resolve();
                                        toastr.success(data+" 更新成功！");
                                }
                            },
                            function(status) {
                                d.resolve(status);
                                toastr.error(teacher[field]+'操作出错请重试！');
                            }
                        );
                    }
                },
                function (response) {
                    d.resolve(response);
                    toastr.error(response);
                }
            );
                return d.promise;
		};
    }
})();