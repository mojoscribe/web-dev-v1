var ContactCtrl=["$scope","$http",function(e,t){t.defaults.headers.common["XSRF_TOKEN"]=$("#csrf").val();e.form={name:"",email:"",phone:"",message:""};e.error=false;e.submitForm=function(){if(""!=(e.form.name&&e.form.email&&e.form.message)){showLoading("Loading","Submitting your form");var n=baseUrl+"settings/contactSubmit";var r=e.form;t.post(n,r).success(function(e){if(e.success==true){hideLoading()}})}else{e.error=true;$("#error").addClass("error-class");$("#error").focus()}}}]