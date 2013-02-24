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
                   var src = $this.attr(o.targetAttribute);
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
                           $this.attr(o.targetAttribute, data);
                       },
                       error : function(jqXHR, textStatus, errorThrown) {
                           alert(textStatus);
                       },
                       complete : function() {
                           $this.fadeIn();
                       }
                   });
               });
    };

    $.fn.blobImageSynchronizer.defaults = {
        loadingImage : ''
      , blobSynchronizer : 'blobSynchronizer.php'
      , type : 'blob'
      , targetAttribute : 'src'
    };
})(jQuery);
