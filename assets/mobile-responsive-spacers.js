var halveSpacersOnMobile = (function(){

  var ratio, breakpoint;

  var __maxMrsElem = {
    blocks: document.querySelectorAll(".wp-block-spacer"),
  };


  /**
   * Helper Function:
   * if on mobile, make it half the height
   * if on non-mobile, make it full height
   */
  __resizeHelper = function(){

    for(var bi = 0; bi < __maxMrsElem.blocks.length; bi++){
      var $this = __maxMrsElem.blocks[bi];

      if( window.innerWidth < breakpoint ){
        calcHeight = $this.getAttribute("data-originalheight")*ratio+"px";
      }
      else{
        calcHeight = $this.getAttribute("data-originalheight")+"px";
      }
      $this.style.setProperty("height", calcHeight, "important");
    }
  };

  /**
   * Takes the every Gutenberg spacer and halves it on mobile
   */
  var init = function(){

    ratio      = mazMrsVars.ratio      || 0.5;
    breakpoint = mazMrsVars.breakpoint || 768;

    // writes current CSS height sans pixels data-attribute for each wp-block-spacer
    for(var si = 0; si < __maxMrsElem.blocks.length; si++){
      var $this = __maxMrsElem.blocks[si],
          currentHeight = parseFloat( $this.offsetHeight );
      $this.setAttribute('data-originalheight', currentHeight);
    }

    // then creates a single resize listener, which triggers the helper function
    window.addEventListener("resize", function(){
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

})();