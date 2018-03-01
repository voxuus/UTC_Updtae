<?php include('header.php'); 

if(isset($_POST['campus_title']))
{

  $campus_title = $_POST['campus_title'];
  $campus_description = $_POST['campus_description'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];

  
   
    //$imgname = $_FILES['primary_image']['tmp_name'];
    //echo "insert into `uwi_campus` (`campus_title`,`campus_description`,`latitude`,`longitude`,`campus_create_date`) values('$campus_title','$campus_description','$latitude','$longitude',NOW())" ;
     mysqli_query($con,"insert into `uwi_campus` (`campus_title`,`campus_description`,`latitude`,`longitude`,`campus_create_date`) values('$campus_title','$campus_description','$latitude','$longitude',NOW())" );  
    $campus_id = mysqli_insert_id($con);

    if(!empty($_FILES['poi_image']['tmp_name']))
    {

      $time = time().".png";
      $path = "../campus_image/".$time;
     
      $thumb = 'tinythumb.php?h=100&w=100&src=/campus_image/'.$time;
      move_uploaded_file($_FILES['poi_image']['tmp_name'],$path);

      mysqli_query($con,"update  `uwi_campus` set campus_image = '$time' where campus_id='$campus_id'");

    }
      /*if(!empty($_FILES['poi_icon']['tmp_name']))
    {

      $time = time().$campus_id."_.png";
      $path = "../campus_image/".$time;
     
      $thumb = 'tinythumb.php?h=100&w=100&src=/campus_image/'.$time;
      move_uploaded_file($_FILES['poi_icon']['tmp_name'],$path);

      mysqli_query($con,"update  `uwi_campus` set campus_icon = '$time' where campus_id='$campus_id'");

    }*/
  //

  //poi_icon

    
}
if(isset($_POST['campus_edit_title']))
{

  $campus_title = $_POST['campus_edit_title'];
  $campus_description = $_POST['campus_description'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $campus_id = $_POST['campus_id'];
  

  mysqli_query($con,"update  `uwi_campus` set campus_title = '$campus_title',campus_description = '$campus_description',latitude = '$latitude',longitude = '$longitude' where campus_id='$campus_id'");
  

}

?>

<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/jquery-multi-select/css/multi-select.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css">
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<!-- END PAGE STYLES -->

<div class="row">
  <div class="col-md-12"> 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">
      <div class="portlet-title">
        <div class="caption"> Campus Map Manager</div>
        <div class="actions"><input type="text" id="univ_address" placeholder="University Address" class="form-control" /> <button class="btn green btn-sm" onclick="add_new();">New</button></div>
      </div>
      <div class="portlet-body">

              <style>
                        #mapCanvas1 {
                        width: 100%;
                        height: 500px;     
                        }
                       
                    </style>
                <div id="infoPanel1" style="display:block;" >
    
                        <div id="markerStatus1"></div>
                        <div id="info1"></div>
                        
                        <!--<div id="address"></div>
                    </div>-->
               
                    <div id="mapCanvas1">

                      <html>
 <head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
 <title>Google Map API V3 with markers</title>
 <style type="text/css">
 body { font: normal 10pt Helvetica, Arial; }
 #map { width: auto; height: 500px; border: 0px; padding: 0px; }
 </style>
 <script src="http://maps.google.com/maps/api/js?v=4&sensor=false" type="text/javascript"></script>
 <script type="text/javascript">
 //Sample code written by August Li
 //var icon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/phone.png",
 
 //var iconsec = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/arts.png",
 var icon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/red.png",
 
 new google.maps.Size(32, 32), new google.maps.Point(0, 0),
 new google.maps.Point(16, 32));
 
 var iconsec = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/blue.png",
 
 new google.maps.Size(32, 32), new google.maps.Point(0, 0),
 new google.maps.Point(16, 32));
 var center = null;
 var map = null;
 var currentPopup;
 var bounds = new google.maps.LatLngBounds();
 function addMarker(lat, lng, info) {
 var pt = new google.maps.LatLng(lat, lng);
 bounds.extend(pt);
 var marker = new google.maps.Marker({
 position: pt,
 icon: icon,
 map: map
 });
 var popup = new google.maps.InfoWindow({
 content: info,
 maxWidth: 300
 });
 google.maps.event.addListener(marker, "mouseover", function() {
 if (currentPopup != null) {
 currentPopup.close();
 currentPopup = null;
 }
 popup.open(map, marker);
 currentPopup = popup;
 });
 google.maps.event.addListener(popup, "closeclick", function() {

 currentPopup = null;
 });
 }
 
  function addMarkersec(lat, lng, info) {
 var pt = new google.maps.LatLng(lat, lng);
 bounds.extend(pt);
 var marker = new google.maps.Marker({
 position: pt,
 icon: iconsec,
 map: map
 });
 var popup = new google.maps.InfoWindow({
 content: info,
 maxWidth: 300
 });
 google.maps.event.addListener(marker, "mouseover", function() {
 if (currentPopup != null) {
 currentPopup.close();
 currentPopup = null;
 }
 popup.open(map, marker);
 currentPopup = popup;
 });
 google.maps.event.addListener(popup, "closeclick", function() {
 
 currentPopup = null;
 });
 }
 function initMap() {
  var cenLatlng = new google.maps.LatLng(000000,111111);
    var myOptions = {
                       zoom: 6,
                       //center: cenLatlng,
                       mapTypeId: google.maps.MapTypeId.ROADMAP
                    }
 map = new google.maps.Map(document.getElementById("map"),myOptions);
 <?php
 $sqlSelect = mysqli_query($con,"SELECT * from uwi_campus ");
                  
                while($results = mysqli_fetch_assoc($sqlSelect)) { 
 $name=$results['campus_title'];
 //$house=$results['house'];
  //$block=$results['block'];
//$vcall=$results['vcall'];
 $lon=$results['longitude'];
 $lat=$results['latitude'];
 //$resident=$results['resident'];
// $admin=$results['admin'];

// $create_date=date( "H:i A", strtotime( $results['create_date'] ) ).'<br>'. date( "d-M-Y", strtotime( $results['create_date'] ) );
$style = 'onClick="edit_campus('.$results['campus_id'].')" class=" btn blue btn-xs "';
$styles = 'onClick="delete_campus('.$results['campus_id'].')" class=" btn red btn-xs "';
 echo ("addMarker($lat, $lon,'<b>$name </b><p><button $style> Edit </button> &nbsp;&nbsp;&nbsp;<button $styles> Delete </button></p>');\n");
 }
 ?>
 
 center = bounds.getCenter();
 map.fitBounds(bounds);
 
 }
 </script>
 
 </head>
 <body onLoad="initMap()" style="margin:0px; border:0px; padding:0px;">
 <div id="map"></div></body>
 </html>

                    </div>
                    
                  </div>
              
            
      </div>
    </div>
    <!-- END Portlet PORTLET--> 
  </div>
  <div class="col-md-12">
 
    <!-- BEGIN Portlet PORTLET-->
    <div class="portlet gren">

      <form class="form-horizontal" role="form" method="post" action="#" enctype="multipart/form-data">
      <div class="portlet-title">
        <div class="caption"><h4 class="block"> Map POI <button class="btn green btn-sm pull-right" type="submit">Save</button></h4></div>
         <div class="actions ">
          
           <!-- <a class="btn green btn-sm" href="javascript:;">Save</a>
             <a class="btn red btn-sm" href="javascript:;">Delete</a>-->
          </div>
        
      </div>
      <div class="portlet-body" id="map_data">

        <div class="row">

          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                 
              </div>
              <div class="portlet-body">


                <div class="form-group">
                  
                  <div class="col-md-12">
                    <input type="text" placeholder="Title" name="campus_title" required class="form-control">
                     
                  </div>
                    </div> 
                  <div class="form-group">
                         
                        <div class="col-md-12">
                            <textarea class="form-control" name="campus_description" required rows="3" placeholder="Description" id="address" aria-required="true"></textarea>
                            
                        </div>
                     </div>

                     <div class="form-group">
                  
                  <div class="col-md-6">
                    <input type="text" placeholder="Lat " name="latitude" required id="latitude" required class="form-control">
                     
                  </div>
                  <div class="col-md-6">
                    <input type="text" placeholder=" Long" name="longitude" required id="longitude" required class="form-control">
                     
                  </div>
                    </div> 
              <!-- End Article Title group -->
                
                  

              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="portlet gren">
              <div class="portlet-title">
                
              </div>
              <div class="portlet-body">
                <div class="form-group">
                  
                  <div class="col-md-9">
                    <input id="exampleInputFile" type="file" required name="poi_image">
                      <p class="help-block"> POI IMAGE </p>
                  </div>
                </div> <!-- End Primary Image Group -->
              <!--  <div class="form-group"> 
                  <div class="col-md-6">
                    <input id="exampleInputFile" type="file" name="poi_icon">
                      <p class="help-block"> ICON </p>
                  </div>
                
                </div> --> <!-- End additional Image Group -->
                <div class="form-group" id="more_additional">
                <input type="hidden" value="1" id="remove_id"/> 
                                    
                </div> <!-- End more additional Image Group -->
              </div>
            </div>

          </div>

        </div>

      </div>

    </form>



    </div>
    <!-- END Portlet PORTLET--> 
  <img src="bluespinner.gif" style="left: 31%;margin: 0;padding: 0;position: absolute;top: 0; display:none;" id="spinn"/>

  </div>
</div>

<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT --> 
<!-- BEGIN QUICK SIDEBAR --> 
<a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a> 

<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER --> 
<!-- BEGIN FOOTER -->
<?php include('footer.php'); ?>
<!-- END FOOTER --> 
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) --> 
<!-- BEGIN CORE PLUGINS --> 
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]--> 
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script> 
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip --> 
<script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script> 
<!-- END CORE PLUGINS --> 

<!-- BEGIN PAGE LEVEL PLUGINS --> 
<script type="text/javascript" src="assets/global/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/clockface/js/clockface.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="assets/global/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"></script>
<script type="text/javascript" src="assets/global/plugins/bootstrap-markdown/lib/markdown.js"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support --> 

<!-- END PAGE LEVEL PLUGINS --> 

<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/index.js" type="text/javascript"></script> 
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script> 

<script src="assets/admin/pages/scripts/components-pickers.js"></script>
<script src="assets/admin/pages/scripts/form-validation.js"></script>
<script src="assets/admin/pages/scripts/components-dropdowns.js"></script>

<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
 	Index.init();   
   Index.initDashboardDaterange();
   Index.initJQVMAP(); // init index page's custom scripts
   Index.initCalendar(); // init index page's custom scripts
  // Index.initCharts(); // init index page's custom scripts
   Index.initChat();
   Index.initMiniCharts();
   Tasks.initDashboardWidget();
 
   ComponentsPickers.init();
		 FormValidation.init();	
	 ComponentsDropdowns.init();
  
});

$(document).on("click",'#suppl_add',function(e){ 

  var remoed = $("#remove_id").val();
   //alert(remoed);
$('#more_additional').append('<div class="col-md-12" id="'+remoed+'"><div class="col-md-9"><input  type="file" name="additional_image[]"></div><div class="col-md-3"><button class="btn btn-xs default" type="button" onclick="remove_img('+remoed+')"><i class="fa fa-remove"></i></button></div></div>');
  
$("#remove_id").val(remoed+1);
 });

function remove_img(x)
{

  $("#"+x).remove();
}
function delete_campus(x)
{
  var va = confirm("Are you sure you want to delete this campus ?");
  if( va==true )
  {
   $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=campus_remove&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
           // $('#featured_user_data').html(obj.data);
            $('#spinn').css("display","none");
            location.reload();
            
                }
          });
 }
}

function edit_campus(x)
{
  $('#spinn').css("display","block");
   $.ajax({
            type:'POST',
            url:'ajax.php',
            //dataType: "json",
            data : 'method=campus_edit&type_id='+x,
            success: function(response) {
            var obj = $.parseJSON(response);
            $('#map_data').html(obj.data);
            $('#spinn').css("display","none");
            //location.reload();
            
                }
          });
}

 

</script> 
<script type="text/javascript">

function add_new()
{

var univ_address = $('#univ_address').val();

if(univ_address=="")
{
  alert("Please Enter University Address.");
  return false;
}
  $.ajax({
  url:'https://maps.googleapis.com/maps/api/geocode/json?address='+univ_address+'&key=AIzaSyBl_2FVYhur0mVBFAPQNGPeLf1O-Wvr_xg',
  success:function(x)
  {
  console.log(x);
  var lat = x.results[0].geometry.location.lat;
  var lng = x.results[0].geometry.location.lng;
  console.log('---' + lat + '---' + lng + '---');
  
                        var latLng = new google.maps.LatLng(lat,lng);  
                        var map = new google.maps.Map(document.getElementById('mapCanvas1'), {
                            zoom: 10,
                            center: latLng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });
                        var marker = new google.maps.Marker({
                            position: latLng,
                            title: 'Point A',
                            map: map,
                            draggable: true
                            
                        });
                        
                        
                        
                        /* 
                        marker.metadata = {type: "point", id: 1};
                        marker.setValues({type: "point", id: 1});
                        var val = marker.get("id");
                        
                        var marker = new google.maps.Marker(markerOptions);
                        marker.metadata = {type: "point",
                        id: 1
                        position: latLng,
                        title: 'Point A',
                        map: map,
                        draggable: true};*/
                        
                        // Update current position info.
                        updateMarkerPosition(latLng);
                        geocodePosition(latLng);
                        
                        // Add dragging event listeners.
                        google.maps.event.addListener(marker, 'dragstart', function() {
                            updateMarkerAddress('Dragging...');
                        });
                        
                        google.maps.event.addListener(marker, 'drag', function() {
                            updateMarkerStatus1('Dragging...');
                            updateMarkerPosition(marker.getPosition());
                        });
                        
                        google.maps.event.addListener(marker, 'dragend', function() {
                            updateMarkerStatus1('Drag ended');
                            geocodePosition(marker.getPosition());
                        });
                    
   
  }
  
  }
  
  );
}

</script>

<script type="text/javascript">
                
                var lastItem1;
                    var geocoder = new google.maps.Geocoder();
                    
                    function geocodePosition(pos) {
                        geocoder.geocode({
                            latLng: pos
                            }, function(responses) {
                            console.log(responses);
                            if (responses && responses.length > 0) {
                                updateMarkerAddress(responses[0].formatted_address,responses,responses.length);
                            } else {
                                updateMarkerAddress('Cannot determine address at this location.');
                            }
                        });
                    }
                    
                    function updateMarkerStatus1(str) {
                        document.getElementById('markerStatus1').innerHTML = str;
                    }
                    
                    function updateMarkerPosition(latLng) {
                        document.getElementById('info1').innerHTML = [
                        latLng.lat(),
                        latLng.lng()
                        ].join(', ');
                        document.getElementById('latitude').value = latLng.lat();
                        document.getElementById('longitude').value = latLng.lng();
                        
                    }
                    
                    
                    
                    function updateMarkerAddress(str,results,lengthsss) {
                       // document.getElementById('address').innerHTML = str;
                        
                       /* var item = str;
                        
                        var lastItem1 = item.split(",").pop(-1);

                        document.getElementById('country').innerHTML = lastItem1;
                        var item = str;
                        var secondlastItem1 = item.split(",").pop(-2);
       
                        document.getElementById('city').innerHTML = secondlastItem1;*/
            
            console.log(results);
            for (var j=0; j<lengthsss; j++)
            {
              if (results[j].types[0]=='locality')
              {
                indice=j;
                break;
              }
              
              for (var i=0; i<results[j].address_components.length; i++)
              {
                 
              }
            }
                        
            
                        
                        
                    }
                    
                    function initialize(lat,lng) {}
                    
                    // Onload handler to fire off the app.
                    google.maps.event.addDomListener(window, 'load', initialize);
                </script>
<!-- END JAVASCRIPTS -->
</body><!-- END BODY -->
</html>