 <?php
$servername = "fdb15.awardspace.net";
$username = "2481200_att";
$password = "android1234";

// Create connection
$conn = new mysqli($servername, $username, $password,"2481200_att");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
$sql = "SHOW COLUMNS FROM cse_c";
$result = mysqli_query($conn,$sql);


?> 


<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CSE-C Attendance</title>
  <link rel="stylesheet" href="./style.css">
  <link rel="stylesheet" href="./btnstyle.css">
  <script  src="https://code.jquery.com/jquery-3.4.1.min.js"  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="  crossorigin="anonymous"></script>
  
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  
<script src="serializejson.js"></script>
  
  
  <style>
  .bootbox-body{
  overflow-x: auto;
  }
  .hiddenPost{
  display:none;
  }
  </style>
  
  
</head>
<body>
<form id="attform" action="#" method="POST">
<table align="center">

<?php while($row = mysqli_fetch_array($result)){ if($row['Field']=="id"||$row['Field']=="date"||$row['Field']=="comment"){ continue;} ?>

<tr>
<td>
<h1><?php echo $row['Field'];?></h1>
</td>
<td>
<!-- partial:index.partial.html -->
<div class="custom-radios">
  <div>
    <input type="radio" id="<?php echo $row['Field'].'-p'; ?>" class="color-1" name="<?php echo $row['Field']; ?>" value="Present"  >
    <label for="<?php echo $row['Field'].'-p'; ?>">
      <span>
        <img style="width:20px;" src="p.ico" alt="Checked Icon" />
      </span>
    </label>
  </div>
  <div>
    <input type="radio" id="<?php echo $row['Field'].'-a'; ?>" class="color-4" name="<?php echo $row['Field']; ?>" value="Absent" >
    <label for="<?php echo $row['Field'].'-a'; ?>">
      <span>
        <img style="width:20px;" src="a.ico" alt="Checked Icon" />
      </span>
    </label>
  </div>
</div>
</td>
<!-- partial -->
  </tr>
  
<?php }?>
  
  


  
  
  
  
  
  
  
  
  <tr>
  <td>
  <a href="javascript:;" class="button" onclick="sub()">Submit</a>
  </td>
  <td>
  <a href="javascript:;" class="button" onclick="document.getElementById('attform').reset();">Reset</a>
  </td>
  </tr>
  
  
  
  
  
  
  
  
  
  
  
  
</table>
<input type="hidden"id="jsonstthidden"  value="null"/>
</form>
<div name="hiddenPost" class="hiddenPost" id="hiddenPost" style="display: none;"></div>

<script>
//var json="null";
function sub(){
ValidateForm();

}

function ValidateForm() {
var jon = $('#attform').serializeJSON();
window.full_att=jon;
if(Object.keys(jon).length!==(($('#attform').find('input[type=radio]').length))/2){

                     
                      
     
     bootbox.alert({
    message: "one or more students did not get Attendance!",
    size: 'small'
});
                      
}

else{
var present=[];
var absent=[];
var p=0,a=0;


for (var key in jon) {
  if (jon.hasOwnProperty(key)) {
    var val = jon[key];
  if(val==="Present"){
  present[String(key)]=jon[key];
  p++;
  }
  if(val==="Absent"){
  absent[String(key)]=jon[key];
  a++;
  }
  
  }
}
if(a+p==61){

if(p<a){


var sp = new Array(); 
var k=0;
for (var ip in present) {
  sp[k++]=String(ip);
    }
console.log(sp);
bootbox.confirm({
    title: "Do you want to post the Attendance?",
    message: "Presenties ("+sp.length+"):"+'</br>'+sp,
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Edit'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Post Attendance'
        }
    },
    callback: function (result) {
        if(result){
        post(present,p);
        }
    }
});

}


else {

var sa = new Array(); 
var j=0;
for (var ia in absent) {
  sa[j++]=String(ia);
    }
bootbox.confirm({
    title: "Do you want to post the Attendance?",
    message: "Abcentiees ("+sa.length+"):"+'</br>'+'<div style="width:5px;">'+sa.toString()+"</div>", 
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Edit'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Post Attendance'
        }
    },
    callback: function (result) {
        if(result){
        post(absent,a);
        }
    }
});

}


}
}

}

function post(Post_att,Post_nuber){
//console.log(Post_att);
//var pt=Post_att;
//var Post_attF=JSON.parse(JSON.stringify(pt));
//console.log(JSON.parse(pt));
//console.log(JSON.stringify(window.full_att));
if(window.full_att!=null && Post_att!=null){
console.log(Post_att.length); //getting array
console.log(Array.isArray(Post_att));
console.log(Post_att);

var c=0;
for(i in Post_att){
console.log(Post_att[i]);
}

console.log(c);

   $.ajax
    ({
        type: "POST",
        url: "attsend.php",
        data: {"Post_att" : Post_att, "Post_nuber" : Post_nuber,"Full_att" : window.full_att},
        success: function (data) //giving null from response 
        {
            alert(data);
        }
    }); 
}
else{
alert("somthing went wrong");
}

var dialog = bootbox.dialog({
    title: '',
    message: '<p><i class="fa fa-spin fa-spinner"></i> Posting please wait....</p>'
});
            



//console.log("posting");
}
</script>
<script src="bootbox.all.min.js"></script>
</body>
</html>