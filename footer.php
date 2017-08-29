</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
<div class="pull-right hidden-xs"> <b>Version</b> 4.4 </div>
<strong>Copyright &copy;  <?php echo date('Y');?> <a href="http://renters.co.ke" target="_blank">Renters PMS</a></strong> </footer>
<!-- /.control-sidebar -->
<div class='control-sidebar-bg'></div>
</div>
<!-- ./wrapper -->
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo WEB_URL; ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- SlimScroll -->
<script src="<?php echo WEB_URL; ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src='<?php echo WEB_URL; ?>plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="<?php echo WEB_URL; ?>dist/js/app.min.js" type="text/javascript"></script>
<!-- Demo -->
<script src="<?php echo WEB_URL; ?>dist/js/demo.js" type="text/javascript"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo WEB_URL; ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo WEB_URL; ?>plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo WEB_URL; ?>dist/js/jquery.mask.min.js"></script>
<script src="<?php echo WEB_URL; ?>dist/js/common.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>dist/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>dist/js/dataTables.tableTools.min.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<input type="hidden" id="web_url" value="<?php echo WEB_URL; ?>" />
</body></html>

<?php


$d = date('Y-m-d');
$lastdate =date('Y-m-d');

$your_date = strtotime("$lastdate");
$datediff = strtotime($d) - $your_date;
$days = floor($datediff/(60*60*24));

?>

<script type="text/javascript">

$("#updateprofile").submit(function(e)
	{
		var postData = $(this).serializeArray();
		var formURL = $(this).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				if(data == '-99'){
					window.location = $("#web_url").val() + 'logout.php';
				}
				else{
					alert('Update Profile Information Successfully');
					window.location = $("#web_url").val() + 'logout.php';
				}
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				alert(textStatus);
			}
		});
		e.preventDefault();
	});

//for floor and unit retrive
function getUnit(){
   var floor_no = $("#ddlFloorNo").val();
   
   if(floor_no != ''){
	   $.ajax({
		  url: '../showunits.php',
		  type: 'POST',
		  data: '&floor_no=' + floor_no + '&token=getunitinfo',
		  dataType: 'html',
		  success: function(data) {
		  	 if(data != '-99'){
			 	$("#ddlUnitNo").html(data);
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }
}

function getVacantt(){
   var floor_no = $("#ddlFloorNo").val();
   
   if(floor_no != ''){
	   $.ajax({
		  url: '../showunits.php',
		  type: 'POST',
		  data: '&floor_no=' + floor_no + '&token=getunitinfo',
		  dataType: 'html',
		  success: function(data) {
		  	 if(data != '-99'){
			 	$("#ddlUnitNo").html(data);
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }
}


//getLandLord

function getLandLord(){
   var unit_no = $("#ddlUnitNo").val();
   
   if(unit_no != ''){
	   $.ajax({
		  url: '../showunits.php',
		  type: 'POST',
		  data: '&unit_no=' + unit_no + '&token=getlandlordinfo',
		  dataType: 'html',
		  success: function(data) {
		  	 if(data != '-99'){

			 	$("#landlord").html(data);
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }
}

//for floor and unit retrive
function getActiveUnit(floor_no){
   if(floor_no != ''){
	   $.ajax({
		  url: '../showunits.php',
		  type: 'POST',
		  data: '&floor_no=' + floor_no + '&token=getunitinforeport',
		  dataType: 'html',
		  success: function(data) {
			 if(data != '-99'){
			 	$("#ddlUnitNo").html(data);
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }
}

//Rent information
function getRentInfo(unit_id){
   var floor_no = $("#ddlFloorNo").val();
   if(floor_no != ''){
	   $.ajax({
		  url: '../showunits.php',
		  type: 'POST',
		  data: 'floor_id=' + floor_no + '&unit_id=' + unit_id + '&token=getRentInfo',
		  dataType: 'json',
		  success: function(data) {
			 if(data != '-99'){
			 	$("#txtRent").val(data.fair);
				$("#hdnFair").val(data.fair);
				$("#txtRentName").val(data.name);
				$("#hdnRentedId").val(data.rid);

				$("#txtWaterBill").val(data.water);
				$("#txtElectricBill").val(data.electric);
				$("#txtGasBill").val(data.gas);

				$("#txtSecurityBill").val(data.security);
				$("#txtUtilityBill").val(data.utility);
				$("#txtOtherBill").val(data.other_bill);

				calculateFairTotal();
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }
}

//for owner info
function getOwnerInfo(unit_id){
   if(unit_id != ''){
	   $.ajax({
		  url: '../showunits.php',
		  type: 'POST',
		  data: 'unit_id=' + unit_id + '&token=getOwnerInfo',
		  dataType: 'json',
		  success: function(data) {
			 if(data != '-99'){
				$("#txtOwnerName").val(data.name);
				$("#hdnOwnerdId").val(data.ownid);
				calculateFairTotal1();
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }
}
//getVisitorTime

function getVisitorTime(unit_id){

	 var floor_no = $("#txtInTime").val();


   if(floor_no != ''){
	   $.ajax({
		  url: '../showunits.php',
		  type: 'POST',
		  data: 'floor_no=' + floor_no + '&token=getgetvistortime',
		  dataType: 'html',
		  success: function(data) {

			 if(data != '-99'){
				$("#txtOutTime").html(data);
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }
}

//for floor and unit retrive
function getUnitReport(){
   var floor_no = $("#ddlFloorNo").val();
   if(floor_no != ''){
	   $.ajax({
		  url: '../showunits.php',
		  type: 'POST',
		  //data: 'category_type=' + category_type + '&cabin_type=' + cabin_type '&floor_type=' + floor_type + '&token=opu',
		  data: '&floor_no=' + floor_no + '&token=getunitinforeport',
		  dataType: 'html',
		  success: function(data) {
			 if(data != '-99'){
			 	$("#ddlUnitNo").html(data);
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }
}

$('#ddlFloorNoo').change(function(){

var floor_no = $("#ddlFloorNoo").val();

   if(floor_no != ''){
	   $.ajax({
		  url: '../showunits.php',
		  type: 'POST',
		  //data: 'category_type=' + category_type + '&cabin_type=' + cabin_type '&floor_type=' + floor_type + '&token=opu',
		  data: '&floor_no=' + floor_no + '&token=getunitinforeport',
		  dataType: 'html',
		  success: function(data) {
			 if(data != '-99'){
			 	$("#ddlUnitNo").html(data);
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   }

});

//date picker


$('#add').click(function(){

var inp = $('#box');

var i = $('input').size() + 1;

$('<br><div id="box' + i +'"><input type="text" id="name" class="name form-control " name="txtFloorUnit[]" placeholder="Unit"/><br><input type="number" id="name" class="name form-control" name="rent_per_month[]" placeholder="Rent Per Month"/><br><a href="#"  class=" btn btn-danger add" id="remove" >Remove</a> </div>').appendTo(inp);

i++;

});



$('body').on('click','#remove',function(){

$(this).parent('div').remove();


});

$("#rec").click(function () {
var contents = $("#pata").html();
var frame1 = $('<iframe />');
frame1[0].name = "frame1";
frame1.css({ "position": "absolute", "top": "-1000000px" });
$("body").append(frame1);
var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
frameDoc.document.open();
//Create a new HTML document.
frameDoc.document.write('<html moznomarginboxes mozdisallowselectionprint><head><title></title>');
frameDoc.document.write('</head><body>');
//Append the external CSS file.
frameDoc.document.write('<link href="<?php echo WEB_URL; ?>dist/css/style.css" rel="stylesheet" type="text/css" />');
//Append the DIV contents.
frameDoc.document.write(contents);
frameDoc.document.write('</body></html>');
frameDoc.document.close();
setTimeout(function () {
window.frames["frame1"].focus();
window.frames["frame1"].print();
frame1.remove();
}, 500);


});


//add utilities

$('#addutility').click(function(){

var inp = $('#utility');

var i = $('input').size() + 1;

$('<br><div id="utility' + i +'" style="margin-left:-1%"><div class="col-lg-4"><input class="form-control" name="utility[]"></div><div class="col-lg-2"><input type="number"  class="form-control col-lg-2" name="uamount[]" /></div><a href="#"  class=" btn btn-danger add" id="removeutility" >Remove</a> </div><br>').appendTo(inp);

i++;

});

$('body').on('click','#removeutility',function(){

$(this).parent('div').remove();


});

//end

//rent items

$('#addrentitems').click(function(){

var inp = $('#rentitems');

var i = $('input').size() + 1;

$('<br><div id="rentitems' + i +'" style="margin-left:-1%"><div class="col-lg-4"><input class="form-control" name="utility[]"></div><div class="col-lg-2"><input type="number"  class="form-control col-lg-2" name="uamount[]" /></div><a href="#"  class=" btn btn-danger add" id="removerent" >Remove</a> </div><br>').appendTo(inp);

i++;

});

$('body').on('click','#removerent',function(){

$(this).parent('div').remove();


});

//end
//pay items

$('#addpayitems').click(function(){

var inp = $('#payitems');

var i = $('input').size() + 1;

$('<br><div id="payitems' + i +'" style="margin-left:-1%"><div class="col-lg-4"><input class="form-control" name="pay[]" placeholder="Description"></div><div class="col-lg-2"><input type="number"  class="form-control col-lg-2" name="amount[]" placeholder="Amount" /></div><a href="#"  class=" btn btn-danger add" id="removepay" >Remove</a> </div><br>').appendTo(inp);

i++;

});

$('body').on('click','#removepay',function(){

$(this).parent('div').remove();


});

//end
</script>