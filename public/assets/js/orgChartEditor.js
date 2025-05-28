var resArr_weapons_createria2;
$(document).ready(function(){
    getbranch();
    
    });
   var All_unitArr=[];

    function getbranch(){
        // alert("xxxx")
        $.ajax({
          method: "GET",
          url: "/getallbranch",
          async : false,

          datatype: 'JSON',
          async: false,
          statusCode: {
              404: function() {
                  alert("page not found");
              }
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
              alert("some error");
              console.log(errorThrown);
          },
          success: function(data) {
            console.log(data);
            if (data) {

              resArr_weapons_createria2 = data;
             console.log(resArr_weapons_createria2);
            // draw_critira_table(resArr_weapons_createria2);
              objData={};
              nodeDataArray=[];
              objData["class"]="go.TreeModel";
              var arr1={};

              arr1["key"]=parseInt(0);
              arr1["name"]="الشجرة المحاسبية "+$("#weapon_drp option:selected").text();
            //   arr1["title"]="معايير تقييم "+$("#weapon_drp option:selected").text();;
              arr1["pic"]=1+".jpg";
              nodeDataArray.push(arr1);
                for (let i = 0; i < resArr_weapons_createria2.length; i++) {
                    var arr={};
                arr["key"]=parseInt(resArr_weapons_createria2[i]["id"]);
                arr["name"]=resArr_weapons_createria2[i]["name"];
                arr["nameen"]=resArr_weapons_createria2[i]["en_name"];
                 arr["code"]=resArr_weapons_createria2[i]["accountant_number"];
                // arr["unit_name"]=resArr_weapons_createria2[i]["unit_name"];
                // arr["weight"]=resArr_weapons_createria2[i]["weight"];
                // alert(resArr_weapons_createria2[i]["weight"]);
                // arr["pic"]=i+1+".jpg";
                // arr["up_or_dwown"]=resArr_weapons_createria2[i]["up_down"];

                if(resArr_weapons_createria2[i]["parent_id"]){
                    arr["parent"]=parseInt(resArr_weapons_createria2[i]["parent_id"]);
                }else{
                    arr["parent"]=parseInt(0);
                }

                  nodeDataArray.push(arr);
              }

              objData["nodeDataArray"]=nodeDataArray;
              objData= JSON.stringify(objData)
            //   console.log( JSON.stringify(objData,replace));
            //   objData= JSON.stringify(objData,replace)
            objData=objData.replace(/\\/g, '');
            console.log(objData);

             $("#mySavedModel").val(objData);
            //  window.addEventListener('DOMContentLoaded', init);
             init();

            }else{
                objData={};
                nodeDataArray=[];
                objData["class"]="go.TreeModel";
                var arr1={};

                arr1["key"]=parseInt(0);
                arr1["name"]="الشجرة المحاسبية "+$("#weapon_drp option:selected").text();
              //   arr1["title"]="معايير تقييم "+$("#weapon_drp option:selected").text();;
                arr1["pic"]=1+".jpg";
                nodeDataArray.push(arr1);


              objData["nodeDataArray"]=nodeDataArray;
              objData= JSON.stringify(objData,replace)
            //   console.log( JSON.stringify(objData,replace));
            //   objData= JSON.stringify(objData,replace)
            objData=objData.replace(/\\/g, '');
            console.log(objData);

             $("#mySavedModel").val(objData);
            //  window.addEventListener('DOMContentLoaded', init);
             init();


            }
          }
      });
      }
   function replace(key,value) {

            if (!isNaN(value)) {
                let change=parseInt(value);
                return change
            }
            return value

      }





$("#saveNodeBtn").click(function(){
    var cid = $("#Cid").val()
    // var CComm = $("#CComm").val()
    var Cname = $("#Cname").val()
    // var Ctit = $("#Ctit").val()
    var Cparentid = $("#Cparentid").val()
    var Cweight = $("#Cweight").val()
    var level_drp = $("#level_drp").val()

    if(level_drp ==""){
    level_drp = 0;
    }
    // var weapon_id=$("#weapon_drp").val();
    // var sweapon_id=$("#wepon_type_idc").val();
    // var force_id=$("#force_drp").val();

    if(Cparentid == "0"){
      Cparentid = null;
    }

    // console.log(cid + "-" + Cparentid + "-" + Cweight + "-" +level_drp + "-"+sweapon_id )
    // console.log(`INSERT INTO weapon.critiria(name, weight, up_down, parent_id, weapon_type_id, unit_id) VALUES ('${Cname}', ${Cweight}, ${level_drp}, ${Cparentid}, ${weapon_id}, ${unit_drp})`);
    $.ajax({
      method: "POST",
      url: "save_branch",
      async : false,
      data: $('#critForm').serialize(),
      datatype: 'JSON',
      async: false,
      statusCode: {
          404: function() {
              alert("page not found");
          }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("some error");
        //   console.log(errorThrown);
      },
      success: function(data) {
        if (data) {
          // var resArr_weapons = $.parseJSON(data);
          myDiagram.div=null
          document.getElementById("critiria_info").classList.add('d-none');
        //   get_criateria_edit(force_id,sweapon_id);
        getbranch();
        }
      }
    })

})


$("#RemNodeBtn").click(function(e){

  if(confirm("سيتم مسح بند من الشجرى المحاسبية . هل انت متأكد ؟")){
//     var weapon_id=$("#wepon_type_id").val();
//   var force_id=$("#force_drp").val();
 var cid = $("#Cid").val();
  $.ajax({
    method: "get",
    url: "delete_branch",
    async : false,
    data: {
        // processName: "delete_weapon_cri",
        cid: cid

    },
    datatype: 'JSON',
    async: false,
    statusCode: {
        404: function() {
            alert("page not found");
        }
    },
    error: function(xhr, textStatus, errorThrown) {
        alert("some error");
        console.log(textStatus);
        var err = eval("(" + xhr.responseText + ")");
      console.log(err.message);
    },
    success: function(data) {
      if (data) {
        // var resArr_weapons = $.parseJSON(data);
    
      }
      myDiagram.div=null
        // get_criateria_edit(force_id,weapon_id);
        getbranch();
        document.getElementById("critiria_info").classList.add('d-none');
    }
  })
  }


})

function draw_critira_table(allCritiria){
  for (let i = 0; i < allCritiria.length; i++) {
    console.log(allCritiria[i].nodeDataArray)
    if(allCritiria[i].parent==null){
      var Gweight = 0;
      if(allCritiria[i].weight){
        Gweight =allCritiria[i].weight;
      }
      $("#critiria_tbl tbody").append(`<tr>
      <td style="cursor:pointer;" onclick="getchildNode(this,${allCritiria[i].key})"><i class="fe fe-plus-circle fe-16"></i></td>
      <td>${allCritiria[i].name}</td>
      <td>${Gweight }</td>
    </tr>`);
    }

  }

  $("#critiria_tbl").dataTable();

}

function getchildNode(el,cid){
  // $(el).toggleClass('fe-minus-circle')

  $(el).parent().after(`<tr>
  <td style="cursor:pointer;" onclick="getchildNode(this,"key")"><i class="fe fe-plus-circle fe-16"></i></td>
  <td>name</td>
  <td>weight</td>
</tr>`);
}



dragElement(document.getElementById("critiria_info"));

function dragElement(elmnt) {
  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
  if (document.getElementById( "modal-header2")) {
    // if present, the header is where you move the DIV from:
    document.getElementById("modal-header2").onmousedown = dragMouseDown;
  } else {
    // otherwise, move the DIV from anywhere inside the DIV:
    elmnt.onmousedown = dragMouseDown;
  }

  function dragMouseDown(e) {
    e = e || window.event;
    e.preventDefault();
    // get the mouse cursor position at startup:
    pos3 = e.clientX;
    pos4 = e.clientY;
    document.onmouseup = closeDragElement;
    // call a function whenever the cursor moves:
    document.onmousemove = elementDrag;
  }

  function elementDrag(e) {
    e = e || window.event;
    e.preventDefault();
    // calculate the new cursor position:
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    // set the element's new position:
    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
  }

  function closeDragElement() {
    // stop moving when mouse button is released:
    document.onmouseup = null;
    document.onmousemove = null;
  }
}
function searchDiagram() {  // called by button
    var input = document.getElementById("mySearch");
    if (!input) return;
    myDiagram.focus();

    myDiagram.startTransaction("highlight search");

    if (input.value) {
      // search four different data properties for the string, any of which may match for success
      // create a case insensitive RegExp from what the user typed
      var safe = input.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      var regex = new RegExp(safe, "i");
      var results = myDiagram.findNodesByExample({ name: regex },
        { nameen: regex },
        { code: regex });
      myDiagram.highlightCollection(results);
      // try to center the diagram at the first node that was found
      if (results.count > 0) myDiagram.centerRect(results.first().actualBounds);
    } else {  // empty string only clears highlighteds collection
      myDiagram.clearHighlighteds();
    }

    myDiagram.commitTransaction("highlight search");
}
