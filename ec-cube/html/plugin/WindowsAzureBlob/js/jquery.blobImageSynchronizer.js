(function($) {
    var o;

    $.fn.blobImageSynchronizer = function(options) {
        return this.each(function() {
                   if (options) {
                       o = $.fn.extend($.fn.blobImageSynchronizer.defaults, options);
                   } else {
                       o = $.fn.blobImageSynchronizer.defaults;
                   }

                   $this = $(this);
                   var src = $this.attr('src');
                   $this.hide();
                   $.ajax({
                       url : o.blobSynchronizer,
                       cache : false,
                       type : 'post',
                       data : {
                           file_path : src,
                           type : o.type
                       },
                       dataType : 'text',
                       success : function(data, textStatus, jqXHR) {
                           console.log(data);
                           $this.attr('src', data);
                       },
                       error : function(jqXHR, textStatus, errorThrown) {},
                       complete : function() {
                           $this.fadeIn();
                       }
                   });
               });
    };

    $.fn.blobImageSynchronizer.defaults = {
        loadingImage : ''
      , blobSynchronizer : 'blobSynchronizer.php'
      , type : 'local'
    };
})(jQuery);