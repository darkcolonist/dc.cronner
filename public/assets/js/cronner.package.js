Package = {
  // * inputs must be set before init
  i_baseurl : null,
  i_serverTime : null,
  i_groupData : {},
  i_layout_options : {},

  // local variables
  _layout : null,
  _moment : null,
  _group : null,
  _cronnables : null,
  _intervals : null,
  _iteration : null,
  _max_output : 100,
  _o_exec : 1,
  getUrl : function(segment){
    return Package.i_baseurl + segment;
  },
  output : function(text, cronnable_obj, add_class){
    var curClass = "o-"+cronnable_obj.id;
    
    $("#output").prepend("<p class='"+curClass+ " " +add_class+"'>[<span class='datetime' title='"
        + Package.currentTime("YYYY-MM-DD HH:mm:ss")
        +"'>"+Package.currentTime("HH:mm:ss")+"</span>] "
        +text+"&nbsp;<span class='o-seq'>"+Package._o_exec+"</span></p>");
      
    if(Package.addCronnableRowDomHovering){
      if("."+curClass != Package.addCronnableRowDomHovered){
        $("#output ."+curClass).css("opacity", ".2");
      }
    }
    
    $("#output p:gt("+Package._max_output+")").remove();
    
    Package._o_exec ++;
  }, 
  execute : function(cronnable, ix, forced){
    var cronnable_obj = null;
    
    for(ctr in Package._cronnables){
      var cronnable_tmp = Package._cronnables[ctr];

      if(cronnable_tmp.id == cronnable){
        cronnable_obj = cronnable_tmp;
      }
    }
    
    var add_class = "";
    if(forced)
      add_class = "forced";
      
    
    Package.output(cronnable_obj.f_url+" | status: [<span id='i"+ix+"' class='executing'>executing</span>]", cronnable_obj, add_class);

    $.getJSON(Package.getUrl("api/cronnables/execute/")+
          cronnable+".json", function(data){
      // update iterator status on complete
      $("#i"+ix).removeClass("executing").addClass("done").html("<a href='"+
        Package.getUrl("terminal/output/"+data.log.id)
        +"' class='btn-result' title='["+ data.log.id + "] " + data.timestamp +" - "+ data.cronnable.url +"'>complete</a> "+data.duration+"s");
    });
  },
  fixNotificationPosition : function(){
    return false; // disabled for now

    // reposition the exclamation sign / notification sign
    $("#error-icon").css({
      position: "absolute",
      left: $("[name=interval]").position().left + $("[name=interval]").width() + 20,
      top: $("[name=interval]").position().top - 3
    });
  },
  initLayout : function(mainContainer, options){
    var options_default = {
      applyDefaultStyles: false
    };

    var merged_options = $.extend({}, options_default, options);

    Package._layout = $(mainContainer).layout(merged_options);
  },
  init : function(){
    Package.initLayout("body", Package.i_layout_options);

    $("#cronnable-toolbar").hide();

    Package._moment = moment(Package.i_serverTime);
      // .format("YYYY-MM-DD HH:mm:ss");

    // setup variables
    Package._group = Package.i_groupData;
    Package._cronnables = Package._group.Cronnables;

    // setup intervals
    Package._intervals = [];
    Package._iteration = 1;

    for(i = 0 ; i < Package._cronnables.length ; i ++){
      // render dom
      Package.addCronnableRowDom(Package._cronnables[i]);

      Package._intervals[Package._cronnables[i].id] = Package._cronnables[i].interval;

      // initial execute...
      if(Package._cronnables[i].muted == 0){
        Package.execute(Package._cronnables[i].id, Package._iteration);
        Package._iteration++;
      }
    }

    $("#cronnables .empty").remove();

    $(window).resize(Package.fixNotificationPosition);
    Package.fixNotificationPosition();

    setInterval(Package.iterator, 1000);
    
    // handle delegates
    Package.initAddNewCronnable();
    Package.initFlagCronnable();
    Package.initResultPopup();
    Package.initForceExecute();
  },
  initForceExecute : function(){
    $(document).on("click", ".cronnable-tick", function(e){
      e.preventDefault();
      
      var the_id = $(e.currentTarget).attr("rel");
      
      Package.execute(the_id, Package._iteration, true);
      Package._iteration++;
    });
  },  
  initResultPopup : function(){
    
    var resultPopupCounter = 0;
    
    $(document).on("click", "a.btn-result", function(e){
      e.preventDefault();
      var iframe = $("<iframe></iframe>").attr("src", $(e.currentTarget).attr("href"));
      
      var the_id = "floating-result-"+resultPopupCounter;
      
      $(".floating-container").clone()
        .removeClass("floating-container")
        .addClass("floating-container-clone")
        .attr("id", the_id)
        .append(iframe).dialog({
          title: $(e.currentTarget).attr("title"),
          close: function(event, ui){
            $(this).remove();
          },
          width: 550,
          height: 350
        });
  
      resultPopupCounter++;
  
    });
  },
  initFlagCronnable : function(){
    $(document).on("click", "a.tool-item", function(e){
      e.preventDefault();

      $.getJSON($(e.currentTarget).attr("href"), function(data){
        if(data.status == 0){
          if(data.method == "delete"){
            // delete from table (DOM)
            $("#cronnable-row-"+data.cronnable.id).remove();

            // delete from intervals
            Package._intervals.splice(data.cronnable.id, 1);

            // delete from cronnables
            for(var key in Package._cronnables){
              if(Package._cronnables[key].id == data.cronnable.id){
                Package._cronnables.splice(key, 1);
              }
            }
          }else if(data.method == "mute"){
            // update cronnable
            for(var key in Package._cronnables){
              if(Package._cronnables[key].id == data.cronnable.id){
                Package._cronnables[key] = data.cronnable;
              }
            }
          }
        }
      });
    });
  },
  initAddNewCronnable : function(){
    $("#frmAddNewCronnable").on("submit", function(e){
      e.preventDefault();

      $("#error-icon").hide();

      $.post($(e.currentTarget).attr("action"), $("#frmAddNewCronnable").serialize(), function(data){
//            data = JSON.parse(data);
        if(data.status == 0){
          Package._intervals[data.cronnable.id] = data.cronnable.interval;
          Package._cronnables.push(data.cronnable);

          Package.addCronnableRowDom(data.cronnable);
        }else{
          $("#error-icon").attr("title", data.message);
          $("#error-icon").show();
        }
      });
    });
  },
  addCronnableRowDomHovering : false,
  addCronnableRowDomHovered : "",
  addCronnableRowDom : function(cronnable){
    // get class
    var row_class = $("#cronnables tr:last").hasClass("odd") ? "even" : "odd";

    var new_cronnable_dom =
        '<tr id="cronnable-row-'+cronnable.id+'" class="'+row_class+'">'
          +'<td><span class="cronnable-url" id="cronnable-url-'+cronnable.id+'" title="'+cronnable.url+'">'+cronnable.f_url+'</span></td>'
          +'<td class="align-right"><span id="interval-'+cronnable.id+'" class="cronnable-tick" title="run now" rel="'+cronnable.id+'"></span></td>'
        +'</tr>';
    $("#cronnables").append(new_cronnable_dom);
    $("#cronnable-toolbar").find("a.delete").attr("href", Package.getUrl("api/cronnables/delete/"+cronnable.id+".json"));
    $("#cronnable-toolbar").find("a.mute").attr("href", Package.getUrl("api/cronnables/mute/"+cronnable.id+".json"));
    $("#cronnable-url-"+cronnable.id).toolbar({
      content: '#cronnable-toolbar',
      position: 'top',
      hideOnClick: true
    });
    
    $("#cronnable-url-"+cronnable.id).on("mouseover", function(e){
      Package.addCronnableRowDomHovering = true;
      Package.addCronnableRowDomHovered = ".o-"+cronnable.id;
      $("#output p:not("+Package.addCronnableRowDomHovered+")").css("opacity", ".2");
    }).on("mouseout", function(e){
      Package.addCronnableRowDomHovering = false;
      Package.addCronnableRowDomHovered = "";
      $("#output p").css("opacity", "1");
    });
  },
  tick : function(){
    Package._moment.add("seconds", 1);

    $("#moment").html(Package.currentTime("YYYY-MM-DD HH:mm:ss"));
  },
  currentTime : function(format){
    return Package._moment.format(format);
  },
  iterator : function(){
    for(i = 0 ; i < Package._cronnables.length ; i ++){
      if(Package._cronnables[i].muted == 1){
        $("#interval-"+Package._cronnables[i].id).html("<em class='gray'>muted</em>");
      }else{
        if(Package._intervals[Package._cronnables[i].id] == 0){
          Package.execute(Package._cronnables[i].id, Package._iteration);

          // reset interval!
          Package._intervals[Package._cronnables[i].id] = Package._cronnables[i].interval;
          Package._iteration++;
        }

        Package._intervals[Package._cronnables[i].id]--;

        $("#interval-"+Package._cronnables[i].id).html(
              Package._intervals[Package._cronnables[i].id]+"s");
      }
    }

    Package.tick();
  }
};