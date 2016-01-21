angular.module("jonphoto")
    .directive("siteHeader", [
        function(){
            return {
                restrict    : "E",
                replace     : true,
                templateUrl : "/wp-content/themes/jonbeechphotography/app/sections/header/header-directive.html",
                scope       : {
                    selectedIndex: "="
                },
                link        : function ($scope, $element, $attrs, $controller, $transcludeFn) {
                    // something something
                    console.log("Building header: ", $scope);
                }
            };
        }
    ])
;