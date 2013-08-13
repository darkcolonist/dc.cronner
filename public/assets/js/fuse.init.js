var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method

$(document).ready(function () {
  if($('div.mine-layer').length != 0)
    myLayout = $('div.mine-layer').layout({
      applyDefaultStyles: true
  //     , stateManagement__enabled:	true
      , west__size: 300
      , west__initClosed: true
    });

  if($('div.mine-layer-advanced').length != 0){
    myLayout = $('div.mine-layer-advanced').layout({
      applyDefaultStyles: true
      , resizable: false
      , center__applyDefaultStyles: false
  //     , stateManagement__enabled:	true

      // middle layout
      , center__childOptions: {
        applyDefaultStyles: true
        , resizable: false
        
        , center__childOptions: {          
          applyDefaultStyles: true
          , resizable: false
        }
        
      }

    });
  }
});

var fuse = {
  layout : null,
  initBodyLayout : function(custom_options){
    var options_default = {
      applyDefaultStyles: false
    };

    var merged_options = $.extend({}, options_default, custom_options);

    fuse.layout = $("body").layout(merged_options);
  },
  clearLayoutState : function(){
    fuse.layout.deleteCookie();
    fuse.layout.options.stateManagement.autoSave = false;
    window.location.reload(0);
  }
};