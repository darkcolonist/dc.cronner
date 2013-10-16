<h1>hello world!</h1>
<?=Form::open(array(
	"id" => "frm_main"
	, "action" => "test/hello/ajaxpost"))?>
	<?=Form::fieldset_open()?>
		<p>
			<?=Form::hidden("developer", "Shannon")?>
		</p>
		<p>
			<?=Form::label("Name", "name")?>
			<?=Form::input("name")?>
		</p>
		<p>
			<?=Form::submit("send")?>
		</p>
	<?=Form::fieldset_close()?>
<?=Form::close()?>

<div id="result"></div>

<?=Asset::js("jquery-1.9.1.min.js")?>

<script type="text/javascript">
	$(document).ready(function(){
		console.log("system ready");

		$("#frm_main").on("submit", function(e){
			e.preventDefault();
			console.log("form about to be sent!");

			$.ajax({
				url : $(e.currentTarget).attr("action"),
				method : "POST",
				type : "JSON",
				data : $(e.currentTarget).serialize(),
				success : function(data){
					console.log(data);

					var json_data = JSON.parse(data);
					var timestamp = json_data.timestamp;
					var name = json_data.name;

					$("#result").html("["+timestamp+"] hello, "+name+"! how are you doing today?");
				}
			});
		});
	});

</script>