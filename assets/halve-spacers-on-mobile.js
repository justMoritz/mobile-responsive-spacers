var halveSpacersOnMobile = (function($){

  /**
   * Takes the every Gutenberg spacer and halves it on mobile
   */
  var init = function(){

    var ratio      = mazHspmVars.ratio      || 0.5;
    var breakpoint = mazHspmVars.breakpoint || 768;

    /**
     * Helper Function:
     * if on mobile, make it half the height
     * if on non-mobile, make it full height
     */
    __resizeHelper = function(){
      $('.wp-block-spacer').each(function(){
        var $this = $(this);

        if( $(window).width() < breakpoint ){
          $this.css( 'height', $this.attr('data-originalheight')*ratio+'px' );
        }
        else{
          $this.css( 'height', $this.attr('data-originalheight')+'px' );
        }
      });
    };

    // writes current CSS height sans pixels data-attribute for each wp-block-spacer
    $('.wp-block-spacer').each(function(){
      var currentHeight = parseFloat( $(this).css('height') );
      $(this).attr('data-originalheight', currentHeight);
    });

    // the creates a single resize listener, which triggers the helper function
    $(window).on('resize', function(){
      __resizeHelper();
    });

    // intial run of the helper function
    __resizeHelper();
  };


  // calls function on load
  init();

 /**
  * Also returns function for manual usage
  */
  return{
    init: init,
  };

})(jQuery);