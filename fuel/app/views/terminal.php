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
      <table id="cronnables" class="data">
        <? foreach($group->Cronnables as $key => $cronnable): ?>
          <tr class="<?= $key % 2 == 0 ? "even" : "odd"?>">
            <td><?= $cronnable->url ?></td>
            <td id="interval-<?= $cronnable->id ?>" class="align-right"></td>
          </tr>
        <? endforeach ?>
      </table>
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
      output : function(text){
        $("#output").prepend("<p><span class='datetime'>"+Package.currentTime("HH:mm:ss")+"</span>] "+text+"</p>");
      }, execute : function(cronnable, ix){
        Package.output(cronnables[i].url+" | status: [<span id='i"+ix+"' class='executing'>executing</span>]");

        $.getJSON($("[name=baseurl]").val()+"api/cronnables/execute/"+
              cronnable+".json", function(data){
          // update iterator status on complete
          $("#i"+ix).removeClass("executing").addClass("done").html("complete "+data.duration+"s");
        });
      },
      init : function(){
        Package._moment = moment("<?= date("Y-m-d H:i:s") ?>");
          // .format("YYYY-MM-DD HH:mm:ss");

        Package.initAddNewCronnable();
      },
      initAddNewCronnable : function(){
        $("#frmAddNewCronnable").on("submit", function(e){
          e.preventDefault();

          $.post($(e.currentTarget).attr("action"), $("#frmAddNewCronnable").serialize(), function(data){
//            data = JSON.parse(data);
            intervals[data.cronnable.id] = data.cronnable.interval;
            cronnables.push(data.cronnable);

            // get class
            var row_class = $("#cronnables tr:last").hasClass("odd") ? "even" : "odd";

            var new_cronnable_dom =
                '<tr class="'+row_class+'">'
                  +'<td>'+data.cronnable.url+'</td>'
                  +'<td id="interval-'+data.cronnable.id+'" class="align-right"></td>'
                +'</tr>';
            $("#cronnables").append(new_cronnable_dom);
          });
        });
      },
      tick : function(){
        Package._moment.add("seconds", 1);

        $("#moment").html(Package.currentTime("YYYY-MM-DD HH:mm:ss"));
      },
      currentTime : function(format){
        return Package._moment.format(format);
      }
    };
  </script>

  <script type="text/javascript">
    // setup variables
    var group = <?= json_encode($group->toArray()) ?>;
    var cronnables = group.Cronnables;
    
    Package.init();

    // setup intervals
    var intervals = {};
    var iteration = 1;

    for(i = 0 ; i < cronnables.length ; i ++){
      intervals[cronnables[i].id] = cronnables[i].interval;

      // initial execute...
      Package.execute(cronnables[i].id, iteration);
      iteration++;
    }

    setInterval(function(){
      for(i = 0 ; i < cronnables.length ; i ++){
        if(intervals[cronnables[i].id] == 0){
          Package.execute(cronnables[i].id, iteration);
          
          // reset interval!
          intervals[cronnables[i].id] = cronnables[i].interval;
          iteration++;
        }

        intervals[cronnables[i].id]--;

      }

      for(i = 0 ; i < cronnables.length ; i ++){
        $("#interval-"+cronnables[i].id).html(
                intervals[cronnables[i].id]+"s");
      }

      Package.tick();
    }, 1000);
  </script>
</body>
</html>