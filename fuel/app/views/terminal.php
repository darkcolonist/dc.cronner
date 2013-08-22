<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=$title?> - Test Mode: <?=$module?></title>
  <?= isset($css) ? $css : null ?>
</head>
<body>
  <div class="row-fluid">
    <div class="span4 pane">
      <?= Fuel\Core\Form::hidden("baseurl", \Fuel\Core\Uri::base()) ?>
      <?= Fuel\Core\Form::open(array(
          "action" => "api/cronnables/add.json",
          "class" => "singleline",
          "id" => "frmAddNewCronnable"
      )) ?>
      <?= Fuel\Core\Form::fieldset_open() ?>
      <?= Fuel\Core\Form::hidden("group_name", $group->name) ?>
      <?= Fuel\Core\Form::input("url", null, array(
          "placeholder" => "type url to add",
          "class" => "span-8"
      )) ?>
      <?= Fuel\Core\Form::input("interval", 60, array(
          "placeholder" => "specify interval",
          "class" => "span-2"
      )) ?>
      <?= Fuel\Core\Form::submit("submit", "Submit",
              array("class" => "hidden")) ?>
      <?= Fuel\Core\Form::fieldset_close() ?>
      <?= Fuel\Core\Form::close() ?>
      <i class="icon-exclamation-sign" id="error-icon"></i>
      <table id="cronnables" class="data">
        <tr class="empty"><td>please wait while we load your cronnables...</td></tr>
      </table>

      <div id="cronnable-toolbar">
        <a href="#mute" title="mute (toggle)" class="mute"><i class="icon-ban-circle"></i></a>
        <a href="#delete" title="delete" class="delete"><i class="icon-trash"></i></a>
      </div>
    </div>
    <div class="span8 pane">
      <div id="output"></div>
    </div>
  </div>

  <div class="row-fluid">
    <div class="span12"><span id="moment"></span></div>
  </div>

  <?= isset($js) ? $js : null ?>
  <?= isset($js_scripts) ? "<script type='text/javascript'>".$js_scripts."</script>" : null ?>

  <script type="text/javascript">
    Package = {
      _moment : null,
      _group : null,
      _cronnables : null,
      _intervals : null,
      _iteration : null,
      output : function(text){
        $("#output").prepend("<p><span class='datetime'>"+Package.currentTime("HH:mm:ss")+"</span>] "+text+"</p>");
      }, execute : function(cronnable, ix){
        Package.output(Package._cronnables[i].url+" | status: [<span id='i"+ix+"' class='executing'>executing</span>]");

        $.getJSON($("[name=baseurl]").val()+"api/cronnables/execute/"+
              cronnable+".json", function(data){
          // update iterator status on complete
          $("#i"+ix).removeClass("executing").addClass("done").html("complete "+data.duration+"s");
        });
      },
      fixNotificationPosition : function(){
        // reposition the exclamation sign / notification sign
        $("#error-icon").css({
          position: "absolute",
          left: $("[name=interval]").position().left + $("[name=interval]").width() + 20,
          top: $("[name=interval]").position().top - 3
        });
      },
      init : function(){
        $("#cronnable-toolbar").hide();

        Package._moment = moment("<?= date("Y-m-d H:i:s") ?>");
          // .format("YYYY-MM-DD HH:mm:ss");

        // handle delegates
        Package.initAddNewCronnable();
        Package.initFlagCronnable();

        // setup variables
        Package._group = <?= json_encode($group->toArray()) ?>;
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
      addCronnableRowDom : function(cronnable){
        // get class
        var row_class = $("#cronnables tr:last").hasClass("odd") ? "even" : "odd";

        var new_cronnable_dom =
            '<tr id="cronnable-row-'+cronnable.id+'" class="'+row_class+'">'
              +'<td><span class="cronnable-url" id="cronnable-url-'+cronnable.id+'">'+cronnable.url+'</span></td>'
              +'<td id="interval-'+cronnable.id+'" class="align-right"></td>'
            +'</tr>';
        $("#cronnables").append(new_cronnable_dom);
        $("#cronnable-toolbar").find("a.delete").attr("href", "<?= \Fuel\Core\Uri::base() ?>api/cronnables/delete/"+cronnable.id+".json");
        $("#cronnable-toolbar").find("a.mute").attr("href", "<?= \Fuel\Core\Uri::base() ?>api/cronnables/mute/"+cronnable.id+".json");
        $("#cronnable-url-"+cronnable.id).toolbar({
          content: '#cronnable-toolbar',
          position: 'top',
          hideOnClick: true
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
  </script>

  <script type="text/javascript">
    Package.init();
  </script>
</body>
</html>