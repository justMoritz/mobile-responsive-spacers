var halveSpacersOnMobile = (function($){

  var ratio, breakpoint;

  var _maxMrsElem = {
    blocks: document.querySelectorAll('.wp-block-spacer'),
  };

  console.log(_maxMrsElem.blocks)

  /**
   * Helper Function:
   * if on mobile, make it half the height
   * if on non-mobile, make it full height
   */
  __resizeHelper = function(){

    for(var bi = 0; bi < _maxMrsElem.blocks.length; bi++){
      var $this = _maxMrsElem.blocks[bi];

      if( $(window).width() < breakpoint ){
        calcHeight = $this.getAttribute('data-originalheight')*ratio+'px';
      }
      else{
        calcHeight = $this.getAttribute('data-originalheight')+'px';
      }
      $this.style.setProperty("height", calcHeight, "important");
    }

    // $('.wp-block-spacer').each(function(){
    //   var $this = $(this);

    //   if( $(window).width() < breakpoint ){
    //     calcHeight = $this.attr('data-originalheight')*ratio+'px';
    //     mazMrsHeightString = 'height: '+$this.attr('data-originalheight')*ratio+'px !important;';
    //   }
    //   else{
    //     calcHeight = $this.attr('data-originalheight')+'px';
    //     mazMrsHeightString = 'height: '+$this.attr('data-originalheight')+'px !important;';
    //   }
    //   $this[0].style.setProperty("height", calcHeight, "important");
    //   // $this.css({ 'cssText', mazMrsHeightString });
    // });
  };

  /**
   * Takes the every Gutenberg spacer and halves it on mobile
   */
  var init = function(){

    ratio      = mazMrsVars.ratio      || 0.5;
    breakpoint = mazMrsVars.breakpoint || 768;

    // writes current CSS height sans pixels data-attribute for each wp-block-spacer
    $('.wp-block-spacer').each(function(){
      var currentHeight = parseFloat( $(this).css('height') );
      $(this).attr('data-originalheight', currentHeight);
    });

    // then creates a single resize listener, which triggers the helper function
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