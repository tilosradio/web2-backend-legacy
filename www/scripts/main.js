/* global require */
require([], function () {
  'use strict';
  $('#test-button').click(function (event) {
      var jqxhr = $.ajax( "/api/show" )
	  .done(function($msg) {
	      $.each($msg, function($index, $value){
		  $("#test").append($value['name']).append("<br/>");
	      });
	  })
	  .fail(function() {
	      alert( "error" );
	  });
  });
});
