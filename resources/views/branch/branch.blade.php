@extends('layouts.master')
@section('css')
 <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
@endsection
@section('title')
    الشجرة المحاسبية
@endsection
    <script src="{{ URL::asset('assets/js/go.js') }}"></script>
    <script id="code">
        function init() {

            // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
            // For details, see https://gojs.net/latest/intro/buildingObjects.html
            const $ = go.GraphObject.make; // for conciseness in defining templates
            var roundedRectangleParams = {
        parameter1: 2,  // set the rounded corner
        spot1: go.Spot.TopLeft, spot2: go.Spot.BottomRight  // make content go all the way to inside edges of rounded corners
      };
            myDiagram =
                $(go.Diagram, "myDiagramDiv", // must be the ID or reference to div
                    {
                        allowCopy: false,
                        allowDelete: false,
                        //initialAutoScale: go.Diagram.Uniform,
                        maxSelectionCount: 1, // users can select only one part at a time
                        validCycle: go.Diagram.CycleDestinationTree, // make sure users can only create trees
                        "clickCreatingTool.archetypeNodeData": { // allow double-click in background to create a new node
                            name: "معيار جديد",
                            eng: "معيار جديد",
                            code: "معيار جديد"
                        },
                        "clickCreatingTool.insertPart": function(loc) { // override to scroll to the new node
                            const node = go.ClickCreatingTool.prototype.insertPart.call(this, loc);
                            if (node !== null) {
                                this.diagram.select(node);
                                this.diagram.commandHandler.scrollToPart(node);
                                this.diagram.commandHandler.editTextBlock(node.findObject("NAMETB"));
                            }
                            return node;
                        },
                        layout: $(go.TreeLayout, {
                            treeStyle: go.TreeLayout.StyleLastParents,
                            arrangement: go.TreeLayout.ArrangementHorizontal,
                            // properties for most of the tree:
                            angle: 90,
                            layerSpacing: 35,
                            // properties for the "last parents":
                            alternateAngle: 90,
                            alternateLayerSpacing: 35,
                            alternateAlignment: go.TreeLayout.AlignmentBus,
                            alternateNodeSpacing: 20
                        }),
                        "undoManager.isEnabled": true // enable undo & redo
                    });

            // when the document is modified, add a "*" to the title and enable the "Save" button
            myDiagram.addDiagramListener("Modified", e => {
                const button = document.getElementById("SaveButton");
                if (button) button.disabled = !myDiagram.isModified;
                const idx = document.title.indexOf("*");
                if (myDiagram.isModified) {
                    if (idx < 0) document.title += "*";
                } else {
                    if (idx >= 0) document.title = document.title.slice(0, idx);
                }
            });

            const levelColors = ["#AC193D", "#2672EC", "#8C0095", "#5133AB",
                "#008299", "#D24726", "#008A00", "#094AB2"
            ];

            // override TreeLayout.commitNodes to also modify the background brush based on the tree depth level
            myDiagram.layout.commitNodes = function() {
                go.TreeLayout.prototype.commitNodes.call(this); // do the standard behavior
                // then go through all of the vertexes and set their corresponding node's Shape.fill
                // to a brush dependent on the TreeVertex.level value
                myDiagram.layout.network.vertexes.each(v => {
                    if (v.node) {

                        const level = v.level % (levelColors.length);
                        const color = levelColors[level];
                        const shape = v.node.findObject("SHAPE");
                        if (shape) shape.stroke = $(go.Brush, "Linear", {
                            0: color,
                            1: go.Brush.lightenBy(color, 0.05),
                            start: go.Spot.Left,
                            end: go.Spot.Right
                        });
                    }
                });
            };

            // this is used to determine feedback during drags
            function mayWorkFor(node1, node2) {
                if (!(node1 instanceof go.Node)) return false; // must be a Node
                if (node1 === node2) return false; // cannot work for yourself
                if (node2.isInTreeOf(node1)) return false; // cannot work for someone who works for you
                return true;
            }

            // This function provides a common style for most of the TextBlocks.
            // Some of these values may be overridden in a particular TextBlock.
            function textStyle() {
                return {
                    font: "9pt  Segoe UI,sans-serif",
                    stroke: "white"
                };
            }

            function displaytextStyle() {
                return {
                    display: "none"
                };
            }
            // This converter is used by the Picture.
            function findHeadShot(pic) {
                if (!pic) return "assets/images/HSnopic.jpg"; // There are only 16 images on the server
                return "assets/images/HSnopic.jpg";

                // return "images/HS" + pic;
            }

            // define the Node template
            myDiagram.nodeTemplate =
                $(go.Node, "Spot", {
                        selectionObjectName: "BODY",
                        click: (e, node) => GnodeClick(node),
                        mouseEnter: (e, node) => node.findObject("BUTTON").opacity = node.findObject("BUTTONX").opacity = 1,
                        mouseLeave: (e, node) => node.findObject("BUTTON").opacity = node.findObject("BUTTONX").opacity = 0,
                        // handle dragging a Node onto a Node to (maybe) change the reporting relationship
                        mouseDragEnter: (e, node, prev) => {
                            const diagram = node.diagram;
                            const selnode = diagram.selection.first();
                            if (!mayWorkFor(selnode, node)) return;
                            const shape = node.findObject("SHAPE");
                            if (shape) {
                                shape._prevFill = shape.fill; // remember the original brush
                                shape.fill = "darkred";
                            }
                        },
                        mouseDragLeave: (e, node, next) => {
                            const shape = node.findObject("SHAPE");
                            if (shape && shape._prevFill) {
                                shape.fill = shape._prevFill; // restore the original brush
                            }
                        },
                        mouseDrop: (e, node) => {
                            const diagram = node.diagram;
                            const selnode = diagram.selection.first(); // assume just one Node in selection
                            if (mayWorkFor(selnode, node)) {
                                // find any existing link into the selected node
                                const link = selnode.findTreeParentLink();
                                if (link !== null) { // reconnect any existing link
                                    link.fromNode = node;
                                } else { // else create a new link
                                    diagram.toolManager.linkingTool.insertLink(node, node.port, selnode, selnode.port);
                                }
                            }
                        }
                    },
                    // for sorting, have the Node.text be the data.name
                    new go.Binding("text", "name"),
                    // bind the Part.layerName to control the Node's layer depending on whether it isSelected
                    new go.Binding("layerName", "isSelected", sel => sel ? "Foreground" : "").ofObject(),
                    $(go.Panel, "Auto", {
                            name: "BODY"
                        },
                        // define the node's outer shape
                        $(go.Shape, "Rectangle", {
                            name: "SHAPE",
                            fill: "#333333",
                            stroke: 'white',
                            strokeWidth: 3.5,
                            portId: ""
                        }, new go.Binding("fill", "isHighlighted", h => h ? "gold" : "#333333").ofObject()),


                      


                        $(go.Panel, "Horizontal",
                            $(go.Picture, {
                                    name: "Picture",
                                    desiredSize: new go.Size(70, 70),
                                    margin: 1.5,
                                    source: "assets/images/HSnopic.jpg" // the default image
                                },
                                new go.Binding("source", "pic", findHeadShot)),
                            // define the panel where the text will appear
                            $(go.Panel, "Table", {
                                    minSize: new go.Size(130, NaN),
                                    maxSize: new go.Size(150, NaN),
                                    margin: new go.Margin(6, 10, 0, 6),
                                    defaultAlignment: go.Spot.Left
                                },
                                $(go.RowColumnDefinition, {
                                    column: 2,
                                    width: 4
                                }),
                                $(go.TextBlock, textStyle(), // the name
                                    {
                                        name: "NAMETB",
                                        row: 0,
                                        column: 0,
                                        columnSpan: 5,
                                        font: "12pt Segoe UI,sans-serif",
                                        editable: true,
                                        isMultiline: false,
                                        minSize: new go.Size(50, 16)
                                    },
                                    new go.Binding("text", "name").makeTwoWay()),


                                $(go.TextBlock, "name", textStyle(), {
                                    row: 1,
                                    column: 0
                                }),
                                $(go.TextBlock, textStyle(), {
                                        row: 1,
                                        column: 1,
                                        columnSpan: 4,
                                        editable: false,
                                        isMultiline: false,

                                        minSize: new go.Size(50, 14),
                                        margin: new go.Margin(0, 0, 0, 3)
                                    },
                                    new go.Binding("text", "name").makeTwoWay()),


                                    $(go.TextBlock, "nameen", textStyle(), {
                                    row: 2,
                                    column: 0
                                }),
                                $(go.TextBlock, textStyle(), {
                                        row: 2,
                                        column: 1,
                                        columnSpan: 4,
                                        editable: false,
                                        isMultiline: false,

                                        minSize: new go.Size(50, 14),
                                        margin: new go.Margin(0, 0, 0, 3)
                                    },
                                    new go.Binding("text", "nameen").makeTwoWay()),

                                    $(go.TextBlock, "code", textStyle(), {
                                    row: 3,
                                    column: 0
                                }),
                                $(go.TextBlock, textStyle(), {
                                        row: 3,
                                        column: 1,
                                        columnSpan: 4,
                                        editable: false,
                                        isMultiline: false,

                                        minSize: new go.Size(50, 14),
                                        margin: new go.Margin(0, 0, 0, 3)
                                    },
                                    new go.Binding("text", "code").makeTwoWay())

                                    //   new go.Binding("text", "title").makeTwoWay()),
                                    // $(go.TextBlock, textStyle(),
                                    //   { row: 2, column: 0 },


                                    // new go.Binding("text", "name")),

                                    // $(go.TextBlock, textStyle(), // the comments
                                    // {
                                    //     row: 3,
                                    //     column: 0,
                                    //     columnSpan: 5,
                                    //     font: "italic 9pt sans-serif",
                                    //     wrap: go.TextBlock.WrapFit,
                                    //     editable: true, // by default newlines are allowed
                                    //     minSize: new go.Size(100, 14)
                                    // },


                            ) // end Table Panel
                        ) // end Horizontal Panel
                    ), // end Auto Panel
                    $("Button",
                        $(go.Shape, "PlusLine", {
                            width: 10,
                            height: 10
                        }), {
                            name: "BUTTON",
                            alignment: go.Spot.Right,
                            opacity: 0, // initially not visible
                            click: (e, button) => addEmployee(button.part)

                        },
                        // button is visible either when node is selected or on mouse-over
                        new go.Binding("opacity", "isSelected", s => s ? 1 : 0).ofObject()
                    ),
                    new go.Binding("isTreeExpanded").makeTwoWay(),
                    $("TreeExpanderButton", {
                            name: "BUTTONX",
                            alignment: go.Spot.Bottom,
                            opacity: 0, // initially not visible
                            "_treeExpandedFigure": "TriangleUp",
                            "_treeCollapsedFigure": "TriangleDown"
                        },
                        // button is visible either when node is selected or on mouse-over
                        new go.Binding("opacity", "isSelected", s => s ? 1 : 0).ofObject()
                    )
                ); // end Node, a Spot Panel




            function GnodeClick(node) {
                if (!node) return;
                const thisemp = node.data;
                console.log(thisemp);
                // $("#critiria_info").toggle();


                var id = thisemp.key;
                var Name = thisemp.name;
                var parentId = thisemp.parent;
                var en_name = thisemp.nameen;
                var code = thisemp.code;
                // var weight = thisemp.weight;
                // var title = thisemp.title;

                if (id == 0) {
                    document.getElementById("critiria_info").classList.add('d-none');
//                     alert("sho");
//                     // $('#exampleModal').modal('show');
//                     $('#exampleModal').modal({
//                         show: true
// });
                } else {

                    document.getElementById("critiria_info").classList.remove('d-none');
                    //  alert("shod");
                    // $('#exampleModal').modal({backdrop: true});$('#exampleModal').modal('toggle');
                                //  $('#exampleModal').modal('show');

                }

                document.getElementById("Cid").value = id;
                // document.getElementById("CComm").value = Name;
                document.getElementById("Cname").value = Name;
                document.getElementById("Cnamen").value = en_name;

                document.getElementById("Cparentid").value = parentId;
                document.getElementById("Cweight").value = code;
                // document.getElementById("Ctit").value = title;
                // document.getElementById("level_drp").value = updown;
                // document.getElementById("unit_drp").value = unitId;


            }

            function addEmployee(node) {

                if (!node) return;
                const thisemp = node.data;
                myDiagram.startTransaction("add employee");

                // const newemp = { name: "معيار جديد", title: "(فرعي)", comments: "", parent: thisemp.key, weight: "" ,unit: "",up_or_dwown:""	};
                const newemp = {
                    name: "حساب جديد",
                    parent: thisemp.key,
                    weight: "",
                    unit: "",
                    up_or_dwown: ""
                };
                myDiagram.model.addNodeData(newemp);
                const newnode = myDiagram.findNodeForData(newemp);
                if (newnode) newnode.location = node.location;
                myDiagram.commitTransaction("add employee");
                myDiagram.commandHandler.scrollToPart(newnode);
            }

            // the context menu allows users to make a position vacant,
            // remove a role and reassign the subtree, or remove a department
            myDiagram.nodeTemplate.contextMenu =
                $("ContextMenu",
                    $("ContextMenuButton",
                        $(go.TextBlock, "Add child"), {
                            click: (e, button) => addEmployee(button.part.adornedPart)
                        }
                    ),
                    $("ContextMenuButton",
                        $(go.TextBlock, "Vacate Position"), {
                            click: (e, button) => {
                                const node = button.part.adornedPart;
                                if (node !== null) {
                                    const thisemp = node.data;
                                    myDiagram.startTransaction("vacate");
                                    // update the key, name, picture, and comments, but leave the title
                                    myDiagram.model.setDataProperty(thisemp, "name", "(Vacant)");
                                    myDiagram.model.setDataProperty(thisemp, "pic", "");
                                    myDiagram.model.setDataProperty(thisemp, "name", "");
                                    myDiagram.commitTransaction("vacate");
                                }
                            }
                        }
                    ),
                    $("ContextMenuButton",
                        $(go.TextBlock, "Remove Role"), {
                            click: (e, button) => {
                                // reparent the subtree to this node's boss, then remove the node

                                const node = button.part.adornedPart;
                                if (node !== null) {
                                    myDiagram.startTransaction("reparent remove");
                                    const chl = node.findTreeChildrenNodes();
                                    // iterate through the children and set their parent key to our selected node's parent key
                                    while (chl.next()) {
                                        const emp = chl.value;
                                        myDiagram.model.setParentKeyForNodeData(emp.data, node.findTreeParentNode().data
                                            .key);
                                    }
                                    // and now remove the selected node itself
                                    myDiagram.model.removeNodeData(node.data);
                                    myDiagram.commitTransaction("reparent remove");
                                }
                            }
                        }
                    ),
                    $("ContextMenuButton",
                        $(go.TextBlock, "Remove Department"), {
                            click: (e, button) => {
                                // remove the whole subtree, including the node itself
                                const node = button.part.adornedPart;
                                if (node !== null) {
                                    myDiagram.startTransaction("remove dept");
                                    myDiagram.removeParts(node.findTreeParts());
                                    myDiagram.commitTransaction("remove dept");
                                }
                            }
                        }
                    )
                );

            // define the Link template
            myDiagram.linkTemplate =
                $(go.Link, go.Link.Orthogonal, {
                        layerName: "Background",
                        corner: 5
                    },
                    $(go.Shape, {
                        strokeWidth: 1.5,
                        stroke: "#F5F5F5"
                    })); // the link shape

            // read in the JSON-format data from the "mySavedModel" element
            load();


            // support editing the properties of the selected person in HTML
            if (window.Inspector) myInspector = new Inspector("myInspector", myDiagram, {
                properties: {
                    "key": {
                        readOnly: true
                    },
                    "name": {},
                    "name": {
                        type: "select"

                    },
                    "name": {
                        type: "select"
                    }
                }
                // console.log(properties)
            });

            // Setup zoom to fit button
            document.getElementById('zoomToFit').addEventListener('click', () => myDiagram.commandHandler.zoomToFit());

            document.getElementById('centerRoot').addEventListener('click', () => {
                myDiagram.scale = 1;
                myDiagram.commandHandler.scrollToPart(myDiagram.findNodeForKey(1));
            });

        } // end init


        // Show the diagram's model in JSON format
        function save() {
            document.getElementById("mySavedModel").value = myDiagram.model.toJson();
            // myDiagram.isModified = false;
            insert_weapon_cri()
        }

        function load() {

            myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
            // make sure new data keys are unique positive integers
            let lastkey = 1;
            myDiagram.model.makeUniqueKeyFunction = (model, data) => {
                let k = data.key || lastkey;
                while (model.findNodeDataForKey(k)) k++;
                data.key = lastkey = k;
                return k;
            };

        }

  // window.addEventListener('DOMContentLoaded', init);
    </script>

    <style>
     #critiria_info{
        display:inline-block;
        width: 25%;
         left: 0px;
        top : 10%;
        position: absolute;
        margin: 0 auto;
        z-index: 1001;
        height: 250p;
    }
    #exampleModal{
        z-index: 1000;
    }
    </style>
@section('content')

    <main role="main" class="main-content font1">
        <div class="page-content">
            <button class="btn btn-dark float-end" onclick='location.href="branchtable"'> Table View </button>

            <div id="sample" class="pt-4">
                <input type="search" id="mySearch" onkeypress="if (event.keyCode === 13) searchDiagram()">
                <button onclick="searchDiagram()">Search</button>

                <div id="myDiagramDiv"
                    style="background-color: rgb(52, 52, 60); border: 1px solid black; height: 800px; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0); cursor: auto; font: 12pt &quot;Segoe UI&quot;, sans-serif;">
                    <canvas tabindex="0" width="1054" height="551"
                        style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 1054px; height: 551px; cursor: auto;">This
                        text is displayed if your browser does not support the Canvas HTML element.</canvas>
                    <div style="position: absolute; overflow: auto; width: 1054px; height: 568px; z-index: 1;">
                        <div style="position: absolute; width: 1412.65px; height: 1px;"></div>
                    </div>
                </div>
                <div class="d-none">
                    Edit details:<br>
                    <div id="myInspector" class="inspector">
                        <table>
                            <tbody>
                                <tr class="d-none">
                                    <td>key</td>
                                    <td><input type="undefined" tabindex="0" disabled=""></td>
                                </tr>
                                <tr class="d-none">
                                    <td>wepon_type_id</td>
                                    <td><input type="text" name=""  id=''  tabindex="1"></td>
                                </tr>
                                <tr >
                                    <td>الاسم</td>
                                    <td><input tabindex="2"></td>
                                </tr>

                                <tr class="d-none">
                                    <td>title</td>
                                    <td><input tabindex="3"></td>
                                </tr>
                                <tr class="d-none">
                                    <td>parent</td>
                                    <td><input tabindex="4"></td>
                                </tr>
                                <tr>
                                    <td>الوزن</td>
                                    <td><input tabindex="5"></td>
                                </tr>

                                <tr>
                                    <td>وحدة القياس</td>
                                    <td><select tabindex="6"><select></td>
                                </tr>
                                <tr>
                                    <td>تصاعدي / تنازلي</td>
                                    <td><select tabindex="7"></select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <p><button id="zoomToFit">Zoom to Fit</button> <button id="centerRoot">Center on root</button></p>

                <div class="d-none">
                    <div>
                        <button id="SaveButton" onclick="save()">Save</button>
                        <button onclick="load()">Load</button>

                    </div>
                    <textarea id="mySavedModel" value="" style="width:100%; height:270px;">{{$branch }}</textarea>
                </div>

                <hr>
                {{-- <table id="critiria_tbl" class="table table-active table-bordered">
                    <thead>
                        <tr>
                            <td></td>
                            <td>Name</td>
                            <td>Weight</td>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table> --}}

                <hr>

                <!-- <p class="text-xs">GoJS version 2.2.14. Copyright 1998-2022 by Northwoods Software.</p></div> -->
                <!-- <p><a href="https://github.com/NorthwoodsSoftware/GoJS/blob/master/samples/orgChartEditor.html" target="_blank">View this sample page's source on GitHub</a></p></div> -->
            </div>
        </div>
    </main> <!-- main -->


















    <div id="critiria_info" class="modal-body bg-light d-none border border-dark rounded">
        <div class="modal-header p-4" id="modal-header2">
            <h2 class="modal-title h" id="defaultModalLabel">بيانات بند شجرة المحاسبة</h2>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"
                    onclick="document.getElementById('critiria_info').classList.add('d-none');">x</span>
            </button>
        </div>
    {{-- <form action="{{ route('crit.store') }}" method="post" enctype="multipart/form-data"> --}}
    <form id="critForm" class="p-3"> 
        {{ csrf_field() }}
        <div class="pt-4">
            <table class="fs-19 fw-bold table table-sm text-center text-dark">
                <tbody>
                    <tr class="d-none">
                        <td>key</td>
                        <td><input class="form-control" id="Cid" name="critiria_id" type="input" tabindex="0" readonly></td>
                    </tr>
                    {{-- <tr class="d-none">
                        <td>wepon_type_id</td>
                        <td><input type="text" name="wepon_type_idc"  id='wepon_type_idc'  tabindex="1"></td>
                    </tr> --}}
                    <tr>
                        <td>الاسم</td>
                        <td><input class="form-control" id="Cname" name="critiria_name" type="input" tabindex="2"></td>
                    </tr>
                    <tr >
                        <td>الاسم بالانجليزية</td>
                        <td><input class="form-control" id="Cnamen" name="critiria_namen" type="input" tabindex="2"></td>

                    </tr>
                    <tr class="d-none">
                        <td>title</td>
                        <td><input class="form-control" id="Ctit"  name="Ctit" type="input" tabindex="3"></td>
                    </tr>
                    <tr class="d-none">
                        <td>parent</td>
                        <td><input class="form-control" id="Cparentid" value="NULL"  name="parent_id" type="input" tabindex="4"></td>
                    </tr>
                    <tr class="">
                        <td>الكود</td>
                        <td><input class="form-control" id="Cweight"  name="weight" type="input" tabindex="5"></td>
                    </tr>

                    {{-- <tr>
                        <td>الوحدة</td>
                        <td><select class="form-control" id="unit_drp" tabindex="6">
                                <option value="0">Select unit</option><select></td>
                    </tr> --}}
                    {{-- <tr>
                        <td>تصاعدي / تنازلي</td>
                        <td><select class="form-control" id="level_drp" tabindex="7" name="up_down">
                                <option value="0" selected>تصاعدي</option>
                                <option value="1">تنازلي</option>
                            </select>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>


        <div class="justify-content-center modal-footer">
            <button type="button" id="saveNodeBtn" class="btn btn-success btn-block h mx-1"
                data-dismiss="modal">حفـــــظ</button>
            <button type="button" id="RemNodeBtn" class="btn btn-danger btn-block h "
                data-dismiss="modal">مســـــح</button>
        </div>
    </form>

    </div>

@endsection
@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dataTables/buttons.print.min.js') }}"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ URL::asset('assets/js/orgChartEditor.js') }}"></script>

    <script>
//         var nodeDataArray = [
//         { key: 0, name: "George V", gender: "M", birthYear: "1865", deathYear: "1936", reign: "1910-1936" },
//         { key: 1, parent: 0, name: "Edward VIII", gender: "M", birthYear: "1894", deathYear: "1972", reign: "1936" },
//         { key: 2, parent: 0, name: "George VI", gender: "M", birthYear: "1895", deathYear: "1952", reign: "1936-1952" },
//         { key: 7, parent: 2, name: "Elizabeth II", gender: "F", birthYear: "1926", reign: "1952-" },
//         { key: 16, parent: 7, name: "Charles, Prince of Wales", gender: "M", birthYear: "1948" },
//         { key: 38, parent: 16, name: "Prince William", gender: "M", birthYear: "1982" },
//         { key: 39, parent: 16, name: "Prince Harry of Wales", gender: "M", birthYear: "1984" },
//         { key: 17, parent: 7, name: "Anne, Princess Royal", gender: "F", birthYear: "1950" },
//         { key: 40, parent: 17, name: "Peter Phillips", gender: "M", birthYear: "1977" },
//         { key: 82, parent: 40, name: "Savannah Phillips", gender: "F", birthYear: "2010" },
//         { key: 41, parent: 17, name: "Zara Phillips", gender: "F", birthYear: "1981" },
//         { key: 18, parent: 7, name: "Prince Andrew", gender: "M", birthYear: "1960" },
//         { key: 42, parent: 18, name: "Princess Beatrice of York", gender: "F", birthYear: "1988" },
//         { key: 43, parent: 18, name: "Princess Eugenie of York", gender: "F", birthYear: "1990" },
//         { key: 19, parent: 7, name: "Prince Edward", gender: "M", birthYear: "1964" },
//         { key: 44, parent: 19, name: "Lady Louise Windsor", gender: "F", birthYear: "2003" },
//         { key: 45, parent: 19, name: "James, Viscount Severn", gender: "M", birthYear: "2007" },
//         { key: 8, parent: 2, name: "Princess Margaret", gender: "F", birthYear: "1930", deathYear: "2002" },
//         { key: 20, parent: 8, name: "David Armstrong-Jones", gender: "M", birthYear: "1961" },
//         { key: 21, parent: 8, name: "Lady Sarah Chatto", gender: "F", birthYear: "1964" },
//         { key: 46, parent: 21, name: "Samuel Chatto", gender: "M", birthYear: "1996" },
//         { key: 47, parent: 21, name: "Arthur Chatto", gender: "M", birthYear: "1999" },
//         { key: 3, parent: 0, name: "Mary, Princess Royal", gender: "F", birthYear: "1897", deathYear: "1965" },
//         { key: 9, parent: 3, name: "George Lascelles", gender: "M", birthYear: "1923", deathYear: "2011" },
//         { key: 22, parent: 9, name: "David Lascelles", gender: "M", birthYear: "1950" },
//         { key: 48, parent: 22, name: "Emily Shard", gender: "F", birthYear: "1975" },
//         { key: 49, parent: 22, name: "Benjamin Lascelles", gender: "M", birthYear: "1978" },
//         { key: 50, parent: 22, name: "Alexander Lascelles", gender: "M", birthYear: "1980" },
//         { key: 51, parent: 22, name: "Edward Lascelles", gender: "M", birthYear: "1982" },
//         { key: 23, parent: 9, name: "James Lascelles", gender: "M", birthYear: "1953" },
//         { key: 52, parent: 23, name: "Sophie Lascelles", gender: "F", birthYear: "1973" },
//         { key: 53, parent: 23, name: "Rowan Lascelles", gender: "M", birthYear: "1977" },
//         { key: 54, parent: 23, name: "Tanit Lascelles", gender: "F", birthYear: "1981" },
//         { key: 55, parent: 23, name: "Tewa Lascelles", gender: "M", birthYear: "1985" },
//         { key: 24, parent: 9, name: "Jeremy Lascelles", gender: "M", birthYear: "1955" },
//         { key: 56, parent: 24, name: "Thomas Lascelles", gender: "M", birthYear: "1982" },
//         { key: 57, parent: 24, name: "Ellen Lascelles", gender: "F", birthYear: "1984" },
//         { key: 58, parent: 24, name: "Amy Lascelles", gender: "F", birthYear: "1986" },
//         { key: 59, parent: 24, name: "Tallulah Lascelles", gender: "F", birthYear: "2005" },
//         { key: 25, parent: 9, name: "Mark Lascelles", gender: "M", birthYear: "1964" },
//         { key: 60, parent: 25, name: "Charlotte Lascelles", gender: "F", birthYear: "1996" },
//         { key: 61, parent: 25, name: "Imogen Lascelles", gender: "F", birthYear: "1998" },
//         { key: 62, parent: 25, name: "Miranda Lascelles", gender: "F", birthYear: "2000" },
//         { key: 10, parent: 3, name: "Gerald Lascelles", gender: "M", birthYear: "1924", deathYear: "1998" },
//         { key: 26, parent: 10, name: "Henry Lascelles", gender: "M", birthYear: "1953" },
//         { key: 63, parent: 26, name: "Maximilian Lascelles", gender: "M", birthYear: "1991" },
//         { key: 27, parent: 10, name: "Martin David Lascelles", gender: "M", birthYear: "1962" },
//         { key: 64, parent: 27, name: "Alexander Lascelles", gender: "M", birthYear: "2002" },
//         { key: 4, parent: 0, name: "Prince Henry", gender: "M", birthYear: "1900", deathYear: "1974" },
//         { key: 11, parent: 4, name: "Prince William of Gloucester", gender: "M", birthYear: "1941", deathYear: "1972" },
//         { key: 12, parent: 4, name: "Prince Richard", gender: "M", birthYear: "1944" },
//         { key: 28, parent: 12, name: "Alexander Windsor", gender: "M", birthYear: "1974" },
//         { key: 65, parent: 28, name: "Xan Windsor", gender: "M", birthYear: "2007" },
//         { key: 66, parent: 28, name: "Lady Cosima Windsor", gender: "F", birthYear: "2010" },
//         { key: 29, parent: 12, name: "Lady Davina Lewis", gender: "F", birthYear: "1977" },
//         { key: 67, parent: 29, name: "Senna Lewis", gender: "F", birthYear: "2010" },
//         { key: 30, parent: 12, name: "Lady Rose Gilman", gender: "F", birthYear: "1980" },
//         { key: 68, parent: 30, name: "Lyla Gilman", gender: "F", birthYear: "2010" },
//         { key: 5, parent: 0, name: "Prince George", gender: "M", birthYear: "1902", deathYear: "1942" },
//         { key: 13, parent: 5, name: "Prince Edward", gender: "M", birthYear: "1935" },
//         { key: 31, parent: 13, name: "George Windsor", gender: "M", birthYear: "1962" },
//         { key: 69, parent: 31, name: "Edward Windsor", gender: "M", birthYear: "1988" },
//         { key: 70, parent: 31, name: "Lady Marina-Charlotte Windsor", gender: "F", birthYear: "1992" },
//         { key: 71, parent: 31, name: "Lady Amelia Windsor", gender: "F", birthYear: "1995" },
//         { key: 32, parent: 13, name: "Lady Helen Taylor", gender: "F", birthYear: "1964" },
//         { key: 72, parent: 32, name: "Columbus Taylor", gender: "M", birthYear: "1994" },
//         { key: 73, parent: 32, name: "Cassius Taylor", gender: "M", birthYear: "1996" },
//         { key: 74, parent: 32, name: "Eloise Taylor", gender: "F", birthYear: "2003" },
//         { key: 75, parent: 32, name: "Estella Taylor", gender: "F", birthYear: "2004" },
//         { key: 33, parent: 13, name: "Lord Nicholas Windsor", gender: "M", birthYear: "1970" },
//         { key: 76, parent: 33, name: "Albert Windsor", gender: "M", birthYear: "2007" },
//         { key: 77, parent: 33, name: "Leopold Windsor", gender: "M", birthYear: "2009" },
//         { key: 14, parent: 5, name: "Princess Alexandra", gender: "F", birthYear: "1936" },
//         { key: 34, parent: 14, name: "James Ogilvy", gender: "M", birthYear: "1964" },
//         { key: 78, parent: 34, name: "Flora Ogilvy", gender: "F", birthYear: "1994" },
//         { key: 79, parent: 34, name: "Alexander Ogilvy", gender: "M", birthYear: "1996" },
//         { key: 35, parent: 14, name: "Marina Ogilvy", gender: "F", birthYear: "1966" },
//         { key: 80, parent: 35, name: "Zenouska Mowatt", gender: "F", birthYear: "1990" },
//         { key: 81, parent: 35, name: "Christian Mowatt", gender: "M", birthYear: "1993" },
//         { key: 15, parent: 5, name: "Prince Michael of Kent", gender: "M", birthYear: "1942" },
//         { key: 36, parent: 15, name: "Lord Frederick Windsor", gender: "M", birthYear: "1979" },
//         { key: 37, parent: 15, name: "Lady Gabriella Windsor", gender: "F", birthYear: "1981" },
//         { key: 6, parent: 0, name: "Prince John", gender: "M", birthYear: "1905", deathYear: "1919" }
//       ];
// $("#mySavedModel").text(nodeDataArray);
// init();
    </script>
@endsection
