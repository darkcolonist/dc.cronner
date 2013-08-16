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

  <?= isset($js) ? $js : null ?>
  <?= isset($js_scripts) ? "<script type='text/javascript'>".$js_scripts."</script>" : null ?>

  <script type="text/javascript">
    Package = {
      output : function(text){
        $("#output").prepend("<p>"+text+"</p>");
      }, execute : function(cronnable, ix){
        Package.output(cronnables[i].url+" | status: [<span id='i"+ix+"' class='executing'>executing</span>]");

        $.getJSON($("[name=baseurl]").val()+"api/cronnables/execute/"+
              cronnable+".json", function(data){
          // update iterator status on complete
          $("#i"+ix).removeClass("executing").addClass("done").html("complete "+data.duration+"s");
        });
      }
    };
  </script>

  <script type="text/javascript">
    // setup variables
    var group = <?= json_encode($group->toArray()) ?>;
    var cronnables = group.Cronnables;

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
    }, 1000);
  </script>
</body>
</html>