
	(function() {

		var url = function(){
			
			var href = window.location.href;

			var url = href.match('/payment_methods/list/');

			return href.replace(url, '/v1/');
		}

		angular
			.module('kartpay_app', [])
			.constant('API', {
				BASE_URL: url(),
				GET_STATUS: url() + 'payment_methods/set-status/'
	        })
	        .factory("ApiService",
            	['$http', '$q', 'API', function ($http, $q, API) {
            		service = {
            			getStatus : getStatus
            		};

            		return service;

    				function getStatus(id, status){
    					var d = $q.defer();
    					
                        $http({
                            method: 'GET',
                            url: API.GET_STATUS  + id + '/' + status
                        }).success(function (response) {
                            d.resolve(response.data);
                        }).error(function (response) {
                            d.reject(response);
                        });

                        return d.promise;
    				}
            			
            }])
			.controller('PaymentMethodsController', ['$scope', '$rootScope','ApiService', function ($scope, $rootScope, ApiService) {

				$scope.setStatus = function(){
					
				}

			}]);

	})();

	function setStatus(element){
		var id = $(element).attr('data-id');

		var href = window.location.href;
		var url = href.match(/\/list\/(debit_card|credit_card|net_banking|wallet)/g);
		
		$.ajax({
			url: href.replace(url, '/status/' + id + '/' + $(element).val()),
			type: 'GET',
			error: function (e){
				console.log(e);
			}
		});
	}