<template>
<div class="row">
                <div class="col-12">
                    <h2 class="top_title text-center">Canvas</h2>
                </div>
                <div class="col-12">
                  <b-button variant="danger" style="margin-left: 1px;" @click="deleteObjects()">Delete</b-button>
                <b-button variant="primary" @click="printCanvas">Print</b-button>
                <!-- <input class="btn btn-info" @change="bgImageChange" type="file"> -->
                <b-button variant="info" @click.prevent="backgroundModalFunc">Background</b-button>
                <!-- <b-button v-b-modal.modal-xl variant="primary">Background</b-button> -->
                <b-button variant="warning" @click="clearCanvasObjects">Clear canvas</b-button>
                <!-- <b-button variant="warning" @click="duplicateObjects">Duplicate</b-button> -->
                <br>
                <br>
                <div class="row">
                  <div class="col-4">
                    <multiselect v-model="selected_buyer" :options="all_buyer" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Select buyers" label="company_name" track-by="byr_buyer_id"><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>
                  </div>
                  <div class="col-3">
                    <input type="text" v-model="canvas_name" class="form-control" placeholder="Please enter canvas name" style="width:300px !important">
                  </div>
                  <div class="col-4">
                    <b-button variant="info" style="margin-left: 5px;" @click="saveData">{{submit_button}}</b-button>
                  </div>
                </div>
                <div class="row" v-if="obj_setting!=0">
                  <div class="col-1" style="padding-right:0px;">
                    <b-button-group class="mr-1">
                      <b-button title="Align left" variant="outline-secondary justificationButton" size="sm" :class="justifier=='left'?'active':''" @click.prevent="changeJustifier('left')">
                        <b-icon icon="text-left" aria-hidden="true"></b-icon>
                      </b-button>
                      <b-button title="Align center" variant="outline-secondary justificationButton" size="sm" :class="justifier=='center'?'active':''" @click.prevent="changeJustifier('center')">
                        <b-icon icon="text-center" aria-hidden="true"></b-icon>
                      </b-button>
                      <b-button title="Align right" variant="outline-secondary justificationButton" size="sm" :class="justifier=='right'?'active':''" @click.prevent="changeJustifier('right')">
                        <b-icon icon="text-right" aria-hidden="true"></b-icon>
                      </b-button>
                    </b-button-group>
                  </div>
                  <div class="col-1" style="padding:2px;">
                    <b-input-group class="mb-2">
                      <b-input-group-prepend is-text>
                        <b-icon icon="type"></b-icon>
                      </b-input-group-prepend>
                      <b-form-input title="Font Size" type="text" placeholder="Font Size" v-model="fontSize" @keyup="changeFontSize()"></b-form-input>
                    </b-input-group>
                  </div>
                  <div class="col-1" style="padding:2px;">
                    <b-input-group class="mb-2">
                      <b-input-group-prepend is-text>
                        SX
                        <!-- <b-icon icon="fonts"></b-icon> -->
                      </b-input-group-prepend>
                      <b-form-input type="text" title="ScaleX" placeholder="ScaleX" v-model="scaleX" @keyup="changeScaleXSize()"></b-form-input>
                    </b-input-group>
                  </div>
                  <div class="col-1" style="padding:2px;">
                    <b-input-group class="mb-2">
                      <b-input-group-prepend is-text>
                        SY
                        <!-- <b-icon icon="fonts"></b-icon> -->
                      </b-input-group-prepend>
                      <b-form-input type="text" title="ScaleY" placeholder="ScaleY" v-model="scaleY" @keyup="changeScaleYSize()"></b-form-input>
                    </b-input-group>
                  </div>
                  <div class="col-1" style="padding:2px;">
                    <b-input-group class="mb-2">
                      <b-input-group-prepend is-text>
                        X
                        <!-- <b-icon icon="fonts"></b-icon> -->
                      </b-input-group-prepend>
                      <b-form-input type="text" title="Left" placeholder="Left" v-model="leftPos" @keyup="changeObjectLeftPos()"></b-form-input>
                    </b-input-group>
                  </div>
                  <div class="col-1" style="padding:2px;">
                    <b-input-group class="mb-2">
                      <b-input-group-prepend is-text>
                        Y
                        <!-- <b-icon icon="fonts"></b-icon> -->
                      </b-input-group-prepend>
                      <b-form-input type="text" title="Top" placeholder="Top" v-model="topPos" @keyup="changeObjectTopPos()"></b-form-input>
                    </b-input-group>
                  </div>
                  <div class="col-1" style="padding:2px;">
                    <b-input-group class="mb-2">
                      <b-input-group-prepend is-text>
                        <b-icon icon="type-h1"></b-icon>
                      </b-input-group-prepend>
                      <b-form-input type="text" title="Line Height" placeholder="Line Height" v-model="lineHeight" @keyup="changeObjectLineHeight()"></b-form-input>
                    </b-input-group>
                  </div>
                  <div class="col-1" style="padding:2px;">
                    <b-input-group class="mb-2">
                      <b-input-group-prepend is-text>
                        <b-icon icon="crop"></b-icon>
                      </b-input-group-prepend>
                      <b-form-input type="text" title="Width" placeholder="Width" v-model="width" @keyup="changeObjectWidth()"></b-form-input>
                    </b-input-group>
                  </div>
                  <!-- <div class="col-1" style="padding:0px;">
                    <input type="text" placeholder="Height" v-model="height" @keyup="changeObjectSetting()" class="form-control">
                  </div> -->
                </div>
                <div class="row" style="height:47.5px;" v-else></div>
                <br>
                </div>
                <!-- <div class="col-12"> -->
                <!-- <div class="col-8" style="height:500px; overflow:auto"> -->
                <div class="col-12" style="overflow:auto">
                    <canvas id="c">Your browser does not support the canvas element.</canvas>
                </div>
                <!-- <div class="col-4" style="height:500px; overflow:auto">
                    <table class="table table-bordered">
                      <thead>
                        <th>
                          <input type="checkbox">
                        </th>
                        <th><abbr title="Column Name">Col name </abbr></th>
                        <th><abbr title="Justification"><b-icon icon="filter" aria-hidden="true"></b-icon></abbr></th>
                        <th><abbr title="Font Size"><b-icon icon="textarea-t" aria-hidden="true"></b-icon></abbr></th>
                        <th><abbr title="ScaleX"><b-icon icon="layout-sidebar" aria-hidden="true"></b-icon></abbr></th>
                        <th><abbr title="ScaleY"><b-icon icon="fullscreen-exit" aria-hidden="true"></b-icon></abbr></th>
                        <th><abbr title="Left">X</abbr></th>
                        <th><abbr title="Top">Y</abbr></th>
                        <th><abbr title="Width"><b-icon icon="crop" aria-hidden="true"></b-icon></abbr></th>
                        <th><abbr title="Line Height"><b-icon icon="arrow-down-short" aria-hidden="true"></b-icon></abbr></th>
                      </thead>
                      <tbody>
                        <tr>
                          <td><input type="checkbox"></td>
                          <td contenteditable>db_1</td>
                          <td contenteditable v-html="'Left'"></td>
                          <td contenteditable v-html="fontSize"></td>
                          <td contenteditable>1</td>
                          <td contenteditable>1</td>
                          <td contenteditable>300</td>
                          <td contenteditable>20</td>
                          <td contenteditable>150</td>
                          <td contenteditable>1.16</td>
                        </tr>
                      </tbody>
                    </table>
                </div> -->
                <div class="col-12">
                  <!-- <div class="col"> -->
                  <div class="card card-small mb-8" style="margin-top: 25px;">
                      <div class="card-header border-bottom">
                          <h6 class="m-0">Canvas List</h6>
                      </div>
                      <div class="card-body p-0 pb-3" id="canvasList">
                          <table id="" class="table mb-0">
                              <thead class="bg-light">
                                  <tr>
                                      <th>#</th>
                                      <th>Canvas Name</th>
                                      <th>Image</th>
                                      <th>Last Update</th>
                                      <th>Operation</th>
                                  </tr>
                                  </thead>
                              <tbody>
                                  <tr v-for="(canvasData,i) in canvasAllData" :key="i">
                                    <td>{{(i+1)}}</td>
                                    <td>{{canvasData.canvas_name}}</td>
                                    <td><img :src="BASE_URL+'storage/app/public/backend/images/canvas/Canvas_screenshoot/'+canvasData.canvas_image" alt="No image" class="img-responsive img-thumbnail" width="150" height="100" style="border: 1px solid gray;"></td>
                                    <td v-html="formatDate(canvasData.updated_at)"></td>
                                    <td>
                                      <b-button variant="info" @click="editCanvas(canvasData)"><b-icon icon="pencil-square" font-scale="1.2"></b-icon></b-button>
                                      <b-button variant="danger" @click="deleteCanvas(canvasData.cmn_pdf_canvas_id)"><b-icon icon="trash-fill" font-scale="1.2"></b-icon></b-button>
                                    </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
    <!-- </div> -->
                </div>
                <div class="col-12">
                  <b-modal size="lg"  :hide-backdrop="true" title="Canvas Background Image" v-model="backgroundModalShow" @cancel.prevent="cancelImage" ok-title="Crop" :no-enforce-focus="true">
                    <div class="row">
                        <div class="col-md-12">
                          <input type="file" class="btn btn-accent" @change="loadImage($event)" accept="image/*">
                        </div>
                        <div class="col-md-12">
                          <cropper
                            class="cropper"
                            :src="modal_image"
                            @change="change"
                          ></cropper>

                            <!-- @change="change" -->
                          <!-- <img :src="bg_image_path" alt=""> -->
                        </div>
                    </div>
                  </b-modal>
                </div>
</div>

</template>
<style>
.input-group{
  min-width: 5rem
}
.justificationButton{
      width: 30px;
    padding: 0;
    height: 30px;
    margin-top: 4px;
}
</style>
<script>
export default {
data(){
  return {
    justifier:"left",
    fontSize:0,
    scaleX:0,
    scaleY:0,
    leftPos:0,
    topPos:0,
    width:0,
    height:0,
    lineHeight:0,
    obj_setting:0,
    all_buyer:[],
    selected_buyer:[],
    canvasAllData:[],
    canvas : null,
    bg_image_path:null,
    canvas_name:null,
    canvas_id:null,
    update_image_info:null,
    submit_button:'Save',
    canvas_width:1219,
    canvas_height:510,
    pointerX:100,
    pointerY:50,
    copiedObjects:[],
    copiedObject:[],
    backgroundModalShow:false,
    modal_image:null,
    tmp_modal_image:null,
    tmp_update_image_info:null,
    activeObjects:[],
  }
},
methods:{
          backgroundModalFunc(){
            this.backgroundModalShow=true;
            this.modal_image=this.bg_image_path
            this.tmp_modal_image=this.bg_image_path
            this.tmp_update_image_info=this.update_image_info
          },
          loadImage($event){
            var input = event.target;
            // Ensure that you have a file before attempting to read it
            if (input.files && input.files[0]) {
              // create a new FileReader to read this image and convert to base64 format
              var reader = new FileReader();
              // Define a callback function to run, when FileReader finishes its job
              reader.onload = (e) => {
                // Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
                // Read image as base64 and set to imageData
                this.modal_image = e.target.result;
              };
              // Start the reader job - read file as a data url (base64 format)
              reader.readAsDataURL(input.files[0]);
            }
          },
          change({coordinates, canvas}) {
            this.backgroundImageSet(canvas.toDataURL());
            this.update_image_info=1;
          },
          cancelImage(){
            this.backgroundModalShow=false;
            this.backgroundImageSet(this.tmp_modal_image);
            this.update_image_info=this.tmp_update_image_info
          },
          loadCanvasData() {
            axios.post(this.BASE_URL+"api/load_canvas_setting_data")
                .then(({ data }) => {
                  this.canvasAllData=data.canvas_info;
                  this.all_buyer=data.all_buyer;
                  // this.loader.hide();
                  // Vue.$loading.hide()
                })
                .catch(() => {
                this.sweet_advance_alert();
                });
          },
          editCanvas(canvasData){
            this.selected_buyer={byr_buyer_id:canvasData.byr_buyer_id,company_name:canvasData.company_name};
            // this.canvas.clear();
            this.canvasClear();
            this.canvas_name=canvasData.canvas_name;
            this.canvas_id=canvasData.cmn_pdf_canvas_id;
            this.submit_button='Update'
            this.bg_image_path=this.BASE_URL + 'storage/app/public/backend/images/canvas/Background/'+canvasData.canvas_bg_image;
            this.backgroundImageSet(this.bg_image_path);
            $("html, body").animate({ scrollTop: 0 }, "slow");
            this.canvasDataView(canvasData.canvas_objects);
          },
          canvasDataView(text_data) {
            var c= this.canvas;
              c.loadFromJSON(text_data, function() {
              c.renderAll();
              });
          },
          deleteObjects(){
            var canvas=this.canvas;
            var activeObjects = canvas.getActiveObjects();
            if (activeObjects.length) {
                // if (confirm('Do you want to delete the selected item??')) {
                    activeObjects.forEach(function(object) {
                        canvas.remove(object);
                    });
                }
                this.obj_setting=0;
            // } else {
            //     alert('Please select the drawing to delete')
            // }
          },
          deleteCanvas(cmn_pdf_canvas_id){
              this.delete_sweet().then((result) => {
              if (result.value) {
                //Send Request to server
                axios.post(this.BASE_URL+'api/delete_canvas',{cmn_pdf_canvas_id:cmn_pdf_canvas_id})
                    .then(({data})=> {
                      if (data.message=='success') {
                        this.alert_text="Canvas deleted"
                      }else if(data.message=='faild'){
                        this.alert_text="Canvas not deleted"
                      }
                      this.alert_icon=data.class_name
                      this.alert_title=data.title
                      this.sweet_normal_alert();
                      this.loadCanvasData()
                    }).catch(() => {
                        this.sweet_advance_alert();
                    })
                }

            })
            },
          bgImageChange(e) {
              let file = e.target.files[0];
              let reader = new FileReader();
              if(file.size < 2111775){
                  if (file.type =="image/png" ||file.type =="image/jpeg") {
                    var mainThis=this;
                     reader.onload = function(e) {
                          var base64_var = e.target.result;
                          mainThis.bgImageProcess(base64_var);
                          mainThis.update_image_info=1;
                      };
                      reader.readAsDataURL(file);
                      reader.onerror = function() {

                      };
                  }else{
                      this.alert_text='File must me jpg or png'
                      this.alert_title="Warning!",
                      this.alert_icon="warning"
                      this.sweet_normal_alert();
                      $('#image').val('');
                  }
              }else{
                  this.alert_text='File size can not be bigger than 2 MB'
                  this.alert_title="Warning!",
                  this.alert_icon="warning"
                  this.sweet_normal_alert();
                $('#image').val('');
              }
          //   this.form.image_url = URL.createObjectURL(file);
          },
          canvasClear(){
            var obj = this.canvas.getObjects();
            this.canvas.remove(obj)
          },
          canvasFieldClear(){
            this.canvas_name=null;
            this.canvas_id=null;
            this.submit_button='Save'
            this.update_image_info=null
            this.obj_setting=0;
            this.selected_buyer=[]
            this.bg_image_path=this.BASE_URL + 'storage/app/public/backend/images/canvas/Background/bg_image.jpg'
            this.backgroundImageSet(this.bg_image_path);
          },
          bgImageProcess(bg_image) {
              var img = new Image();
              var mainCanvas=this.canvas;
              img.onload = function() {
                  var f_img = new fabric.Image(img);
                  mainCanvas.setBackgroundImage(f_img, mainCanvas.renderAll.bind(mainCanvas), {
                      // width: mainCanvas.width,
                      // height: mainCanvas.height,
                      originX: 'left',
                      originY: 'top'
                  });
                  mainCanvas.setWidth(img.width);
                  mainCanvas.setHeight(img.height);
              };
              img.src = bg_image;
          },
          clearCanvasObjects(){
            this.canvas.clear();
            this.canvasFieldClear();
          },
          printCanvas(){
            this.deselectObject()
            this.printData('.canvas-container');
          },
          deselectObject(){
            var canvas= this.canvas;
            var activeObjects = canvas.getActiveObjects();
            if (activeObjects.length) {
                // if (confirm('Do you want to delete the selected item??')) {
                activeObjects.forEach(function(object) {
                    canvas.discardActiveObject(object);
                    canvas.renderAll();
                });
                // }
            }
          },

          duplicateObjects(){
            var _this=this;
            var activeObjects = this.canvas.getActiveObjects();
             var obj_copy=activeObjects;
                obj_copy.forEach(element => {
                  this.createObj(element.left,(element.top)+29,element.width,element.height,element.fontSize,element.textAlign,element.lineHeight,element.scaleX,element.scaleY,element.text.toString(),'auto')
                });
          },
          printData(divVar) {
            var canvas=this.canvas;
            var thisVar=this;
              var imgSrc = canvas.backgroundImage._element.src;
              canvas.backgroundImage = 0;
              canvas.renderAll();
              var ppp = $(divVar).printThis({
                  debug: false, // show the iframe for debugging
                  importCSS: false, // import parent page css
                  importStyle: false, // import style tags
                  printContainer: true, // print outer container/$.selector
                  loadCSS: Globals.base_url + "/public/css/pdf_css.css", // path to additional css file - use an array [] for multiple
                  pageTitle: "0", // add title to print page
                  removeInline: false, // remove inline styles from print elements
                  removeInlineSelector: "*", // custom selectors to filter inline styles. removeInline must be true
                  printDelay: 500, // variable print delay EX: 333
                  header: null, // prefix to html or null
                  footer: null, // postfix to html or null
                  base: false, // preserve the BASE tag or accept a string for the URL
                  formValues: true, // preserve input/form values
                  canvas: true, // copy canvas content
                  doctypeString: '', // enter a different doctype for older markup
                  removeScripts: false, // remove script tags from print content
                  copyTagClasses: false, // copy classes from the html & body tag
                  beforePrintEvent: null, // function for printEvent in iframe
                  beforePrint: null, // function called before iframe is filled
                  afterPrint: null // function called before iframe is removed
              });
              // console.log(ppp);
              setTimeout(function() {
                  thisVar.backgroundImageSet(imgSrc);
              }, 510);


          },
          saveData(){
            if (this.canvas_name==null) {
                alert("Please fill canvas name");
                return false;
            }
            if (this.selected_buyer.length<=0) {
              alert("Please select buyer name");
                return false;
            }
            var canData = this.canvasData();
            if (!canData['objects'].length) {
                alert("No canvas drown");
                return false;
            }
            var buyer_id=this.selected_buyer.byr_buyer_id
            // this.selected_buyer.forEach(element => {
            //   buyer_id.push(element.byr_buyer_id)
            // });
            var canvas_data= { canvas_id: this.canvas_id, update_image_info: this.update_image_info,byr_id:buyer_id, canvas_name: this.canvas_name, canData: canData, canvasImage: this.getCanvasBgImage() }
            // console.log(canvas_data);
            // return 0;
            axios.post(this.BASE_URL+"api/canvas_data_save",canvas_data)
                .then(({ data }) => {
                    if (data.message=='created') {
                          this.alert_text="Canvas Created"
                          this.loadCanvasData()
                          this.canvas.clear();
                          this.canvasFieldClear();
                        }else if(data.message=='updated'){
                          this.alert_text="Canvas Updated"
                          this.loadCanvasData()
                        }else if(data.message=='duplicated'){
                          this.alert_text="Canvas Buyer is duplicated"
                        }
                        this.alert_title=data.title
                        this.alert_icon=data.class_name
                        this.sweet_normal_alert();
                })
                .catch(() => {
                this.sweet_advance_alert();
                });

          },
          // createReactObj(){
            createObj(left=100,top=50,width=150,height=22,fontSize=20,textAlign="left",lineHeight=1.16,scaleX=1,scaleY=1,text="Created by default",createdBy='auto'){
            var activeObject = this.canvas.getActiveObject();
        var text_data = [{
            originX: "left",
            originY: "top",
            left: left,
            top: top,
            width: width,
            height: height,
            fill: "black",
            stroke: null,
            strokeWidth: 0,
            strokeDashArray: null,
            strokeLineCap: "butt",
            strokeDashOffset: 0,
            strokeLineJoin: "miter",
            strokeMiterLimit: 4,
            scaleX: scaleX,
            scaleY: scaleY,
            angle: 0,
            flipX: 0, //false
            flipY: 0, //false
            opacity: 1,
            shadow: null,
            visible: 1, //true
            clipTo: null,
            backgroundColor: "",
            fillRule: "nonzero",
            paintFirst: "fill",
            globalCompositeOperation: "source-over",
            transformMatrix: null,
            skewX: 0,
            skewY: 0,
            text: text,
            fontSize: fontSize,
            fontWeight: "normal",
            fontFamily: "Times New Roman", //Arial, Times New Roman, Helvetica, sans-serif
            fontStyle: "normal",
            lineHeight: lineHeight,
            underline: 0, //False
            overline: 0, //False
            linethrough: 0, //False
            textAlign: textAlign,
            textBackgroundColor: "",
            charSpacing: 0,
            minWidth: 20,
            splitByGrapheme: 1, //False
            objectCaching: false,
        }];

        if (createdBy!='auto') {
          if (!activeObject) {
            this.addText(text_data);
          }
        }else{
          this.addText(text_data);
        }
          },
          doubleClick(option){
            // console.log(option);
            this.pointerX=option.pointer.x
            this.pointerY=option.pointer.y
            this.createObj(this.pointerX-50,this.pointerY,150,22,20,"left",1.16,1,1,"Created by Click",'clicked');
            this.obj_setting=1;
            this.getActiveObjectAttr();
          },
          getCanvasBgImage() {
              var can_image = this.canvas.toDataURL({
                  format: 'png',
                  quality: 0.8
              });
              return can_image;
          },
          canvasData() {
              return this.canvas.toJSON();
          },
          backgroundImageSet(imgUrl) {
            var mainCanvas=this.canvas;
            this.canvas.setBackgroundImage(imgUrl, this.canvas.renderAll.bind(this.canvas), {
            // Optionally add an opacity lvl to the image
            backgroundImageOpacity: 0,
            // should the image be resized to fit the container?
            backgroundImageStretch: false,
            // image size as canvas size
            // width: this.canvas.width,
            // height: this.canvas.height
        });
        // canvas size by image size
        this.bgImageWH(imgUrl);
      },
      bgImageWH(imgUrl){
        var mainCanvas=this.canvas;
        const img = new Image();
        img.src = imgUrl;
        img.onload = function() {
          mainCanvas.setWidth(img.naturalWidth);
          mainCanvas.setHeight(img.naturalHeight);
        };
      },
      mouseUp(e){
        this.getActiveObjectAttr();
      },
      getActiveObjectAttr(){
        var canvas=this.canvas
        var _this=this;
        var activeObjects = canvas.getActiveObjects();
        if (activeObjects.length>0) {
          this.obj_setting=1;
          activeObjects.forEach(function(object) {
            _this.justifier=object.textAlign=="left"?"left":(object.textAlign=="center"?"center":(object.textAlign=="right"?"right":''))
            _this.fontSize=object.fontSize
            _this.scaleX=object.scaleX
            _this.scaleY=object.scaleY
            _this.width=object.width
            _this.height=object.height
            _this.lineHeight=object.lineHeight
            // _this.leftPos=activeObjects.length>1?object.group.left:object.left;
            // _this.topPos=activeObjects.length>1?object.group.top:object.top;
            _this.leftPos=activeObjects.length>1?object.group.left.toFixed(2):object.left.toFixed(2);
            _this.topPos=activeObjects.length>1?object.group.top.toFixed(2):object.top.toFixed(2);
                });
        }else{
          this.obj_setting=0;
        }
      },
      getActiveObject(){
        this.activeObjects = this.canvas.getActiveObjects();
      },
      // changeObjectSetting($event=null){
      //   var canvas=this.canvas;
      //   var _this=this;
      //   this.getActiveObject()
      //   if (this.activeObjects.length) {
      //           this.activeObjects.forEach(function(object) {
      //             object.set({
      //             // textAlign:$event==null?_this.selectedJustifier.name:$event.name,
      //             width:Number(_this.width),
      //             height:Number(_this.height),
      //             // fontSize:Number(_this.fontSize),
      //             // strokeWidth: val,
      //             // scaleX: Number(_this.scaleX),
      //             // scaleY: Number(_this.scaleY),
      //             left: Number(_this.leftPos),
      //             top: Number(_this.topPos),
      //             lineHeight:Number(_this.lineHeight),
      //             evented: true,
      //         });
      //         // object.setCoords();
      //           });
      //           // canvas.setActiveObject(activeObjects);
      //           // canvas.requestRenderAll();
      //       }
      //       canvas.renderAll();
      // },
      changeJustifier(justifierVal){
        var canvas=this.canvas;
        // var _this=this;
        this.getActiveObject()
        if (this.activeObjects.length) {
                this.activeObjects.forEach(function(object) {
                  object.textAlign=justifierVal;
                  canvas.renderAll();
                });
                this.justifier=justifierVal
            }
      },
      changeFontSize(){
        var canvas=this.canvas;
        var _this=this;
        this.getActiveObject()
        if (this.activeObjects.length) {
                this.activeObjects.forEach(function(object) {
                  object.fontSize=Number(_this.fontSize);
                  canvas.renderAll();
                });
            }
      },
      changeScaleXSize(){
        var canvas=this.canvas;
        var _this=this;
        this.getActiveObject()
        if (_this.activeObjects.length) {
                _this.activeObjects.forEach(function(object) {
                  object.scaleX=Number(_this.scaleX);
                  canvas.renderAll();
                });
            }
      },
      changeScaleYSize(){
        var canvas=this.canvas;
        var _this=this;
        this.getActiveObject()
        if (_this.activeObjects.length) {
                _this.activeObjects.forEach(function(object) {
                  object.scaleY=Number(_this.scaleY);
                  canvas.renderAll();
                });
            }
      },
      changeObjectLeftPos(){
        var canvas=this.canvas;
        var _this=this;
        this.getActiveObject()
        if (_this.activeObjects.length) {
                canvas.discardActiveObject();
          _this.activeObjects.forEach(function(object) {
                  object.left=Number(_this.leftPos);
                  // object.evented= true,
                  // object.setCoords();
                  // object.set('active', true);
                  // canvas.setActiveObject(object);
                });
                var selection = new fabric.ActiveSelection(_this.activeObjects, {
                  canvas: canvas
                });
                canvas.setActiveObject(selection).renderAll();
            }
      },
      changeObjectTopPos(){
        var canvas=this.canvas;
        var _this=this;
        this.getActiveObject()
        if (_this.activeObjects.length) {
          canvas.discardActiveObject();
          _this.activeObjects.forEach(function(object) {
                  object.top=Number(_this.topPos);
                });
                var selection = new fabric.ActiveSelection(_this.activeObjects, {
                  canvas: canvas
                });
                canvas.setActiveObject(selection).renderAll();
        }
      },
      changeObjectLineHeight(){
        var canvas=this.canvas;
        var _this=this;
        this.getActiveObject()
        if (_this.activeObjects.length) {
                _this.activeObjects.forEach(function(object) {
                  object.lineHeight=Number(_this.lineHeight);
                });
                canvas.renderAll();
            }
      },
      changeObjectWidth(){
        var canvas=this.canvas;
        var _this=this;
        this.getActiveObject()
        if (_this.activeObjects.length) {
                _this.activeObjects.forEach(function(object) {
                  object.width=Number(_this.width);
                });
                canvas.renderAll();
            }
      },
      addText(text_data) {
          for (let i = 0; i < text_data.length; i++) {
              var oText = new fabric.Textbox(text_data[i]['text'], {
                  originX: text_data[i]['originX'],
                  originY: text_data[i]['originY'],
                  left: text_data[i]['left'],
                  top: text_data[i]['top'],
                  width: text_data[i]['width'],
                  height: text_data[i]['height'],
                  fill: text_data[i]['fill'],
                  stroke: text_data[i]['stroke'],
                  strokeWidth: text_data[i]['strokeWidth'],
                  strokeDashArray: text_data[i]['strokeDashArray'],
                  strokeLineCap: text_data[i]['strokeLineCap'],
                  strokeDashOffset: text_data[i]['strokeDashOffset'],
                  strokeLineJoin: text_data[i]['strokeLineJoin'],
                  strokeMiterLimit: text_data[i]['strokeMiterLimit'],
                  scaleX: text_data[i]['scaleX'],
                  scaleY: text_data[i]['scaleY'],
                  angle: text_data[i]['angle'],
                  flipX: text_data[i]['flipX'],
                  flipY: text_data[i]['flipY'],
                  opacity: text_data[i]['opacity'],
                  shadow: text_data[i]['shadow'],
                  visible: text_data[i]['visible'],
                  clipTo: text_data[i]['clipTo'],
                  backgroundColor: text_data[i]['backgroundColor'],
                  fillRule: text_data[i]['fillRule'],
                  paintFirst: text_data[i]['paintFirst'],
                  globalCompositeOperation: text_data[i]['globalCompositeOperation'],
                  transformMatrix: text_data[i]['transformMatrix'],
                  skewX: text_data[i]['skewX'],
                  skewY: text_data[i]['skewY'],
                  fontSize: text_data[i]['fontSize'],
                  fontWeight: text_data[i]['fontWeight'],
                  fontFamily: text_data[i]['fontFamily'], //Arial, Times New Roman, Helvetica, sans-serif
                  fontStyle: text_data[i]['fontStyle'],
                  lineHeight: text_data[i]['lineHeight'],
                  underline: text_data[i]['underline'],
                  overline: text_data[i]['overline'],
                  linethrough: text_data[i]['linethrough'],
                  textAlign: text_data[i]['textAlign'],
                  textBackgroundColor: text_data[i]['textBackgroundColor'],
                  charSpacing: text_data[i]['charSpacing'],
                  minWidth: text_data[i]['minWidth'],
                  splitByGrapheme: text_data[i]['splitByGrapheme'],
                  objectCaching: false,
                  // hasControls: false,
              });
              this.canvas.add(oText).setActiveObject(oText);
              this.canvas.renderAll();
          }
      },
      keyEventFunc(e){
        // console.log(e);
        // if (e.keyCode == 46 || (e.ctrlKey && e.keyCode == 8)) {
        if (e.keyCode == 46 || (e.ctrlKey && e.keyCode == 8)) {
            this.deleteObjects();
        } else if (e.ctrlKey && e.shiftKey && e.keyCode == 65) {
            this.selectAllObjects()
        }else if (e.ctrlKey && e.keyCode == 67) {
            this.copyObject()
        }else if (e.ctrlKey && e.keyCode == 86) {
            this.pasteObject()
        }else if (e.keyCode == 37) {
            this.leftButton()
        }else if (e.keyCode == 38) {
            this.upButton()
        }else if (e.keyCode == 39) {
            this.rightButton()
        }else if (e.keyCode == 40) {
            this.downButton()
        }else if (e.ctrlKey && e.keyCode == 90) {
            this.undo();
        }
      },
      leftButton(){
        var activeObjects=this.canvas.getActiveObjects();
        activeObjects.forEach(element => {
          element.set({
          left: element.left - 1,
          evented: true,
        });
        element.setCoords();
        });
        this.canvas.requestRenderAll();
        this.getActiveObjectAttr()
      },
      upButton(){
        var activeObjects=this.canvas.getActiveObjects();
        activeObjects.forEach(element => {
          element.set({
          top: element.top - 1,
          evented: true,
        });
        element.setCoords();
        });
        this.canvas.requestRenderAll();
        this.getActiveObjectAttr()
      },
      rightButton(){
        var activeObjects=this.canvas.getActiveObjects();
        activeObjects.forEach(element => {
          element.set({
          left: element.left + 1,
          evented: true,
        });
        element.setCoords();
        });
        this.canvas.requestRenderAll();
        this.getActiveObjectAttr()
      },
      downButton(){
        var activeObjects=this.canvas.getActiveObjects();
        activeObjects.forEach(element => {
          element.set({
          top: element.top + 1,
          evented: true,
        });
        element.setCoords();
        });
        this.canvas.requestRenderAll();
        this.getActiveObjectAttr()
      },
      undo() {
        var canvas=this.canvas;
        var _this=this;
        // https://bountify.co/adding-keyboard-functions-to-undo-redo-functionality-on-fabric-js
        // https://jsfiddle.net/gableroux/tv29xfkg/10/
        // if(canvas.mementoConfig.undoFinishedStatus){
        //     if(canvas.mementoConfig.currentStateIndex === -1){
        //         canvas.mementoConfig.undoStatus = false;
        //     }
        //     else{
        //         if (canvas.mementoConfig.canvasState.length >= 1) {
        //             canvas.mementoConfig.undoFinishedStatus = 0;
        //             if(canvas.mementoConfig.currentStateIndex != 0){
        //                 canvas.mementoConfig.undoStatus = true;
        //                 loadJsonIntoCanvas(JSON.parse(canvas.mementoConfig.canvasState[canvas.mementoConfig.currentStateIndex-1]).objects, canvas, true);
        //                 canvas.mementoConfig.undoStatus = false;
        //                 canvas.mementoConfig.currentStateIndex -= 1;
        //                 // $('#undo').prop('disabled', false);
        //                 if(canvas.mementoConfig.currentStateIndex !== canvas.mementoConfig.canvasState.length-1){
        //                     // $('#redo').prop('disabled', false);
        //                 }
        //                 canvas.mementoConfig.undoFinishedStatus = 1;
        //             }
        //             else if(canvas.mementoConfig.currentStateIndex === 0){
        //                 clearCanvas(canvas);
        //                 canvas.mementoConfig.undoFinishedStatus = 1;
        //                 // $('#undo').prop('disabled', true);
        //                 // $('#redo').prop('disabled', false);
        //                 canvas.mementoConfig.currentStateIndex -= 1;
        //             }
        //         }
        //     }
        // }
    },
      copyObject(){

        // copy function start
        var canvas=this.canvas;
        var _this=this;
        var activeObject=canvas.getActiveObject();
        if (activeObject) {
          activeObject.clone(function(cloned) {
          _this.copiedObjects = cloned;
        });
        }
        // Copy function End
      },
      pasteObject(){
        var canvas=this.canvas;
        var _this=this;
        _this.copiedObjects.clone(function(clonedObj) {
        canvas.discardActiveObject();
        clonedObj.set({
          // left: clonedObj.left + 10,
          top: clonedObj.top + 30,
          evented: true,
        });
        if (clonedObj.type === 'activeSelection') {
          // active selection needs a reference to the canvas.
          clonedObj.canvas = canvas;
          clonedObj.forEachObject(function(obj) {
            canvas.add(obj);
          });
          // this should solve the unselectability
          clonedObj.setCoords();
        } else {
          canvas.add(clonedObj);
        }
        _this.copiedObjects.top += 30;
        // _this.copiedObjects.left += 10;
        canvas.setActiveObject(clonedObj);
        canvas.requestRenderAll();
      });
      },
      selectAllObjects() {
        var _this=this;
          var selection = new fabric.ActiveSelection(this.canvas.getObjects(), { canvas: this.canvas });
          _this.canvas.setActiveObject(selection);
          _this.canvas.renderAll();
          if (selection._objects.length) {
            this.obj_setting=1;
          }
      },
      },
      created(){
        Fire.$emit('permission_check_for_buyer',this.$session.get('byr_buyer_id'));
        // this.canvasOpen();
      },
      mounted(){
          this.canvas = new fabric.Canvas("c");
          this.canvas.setWidth(this.canvas_width);
          this.canvas.setHeight(this.canvas_height);
          // this.canvas.controlsAboveOverlay = true;
          this.bg_image_path=this.BASE_URL + 'storage/app/public/backend/images/canvas/Background/bg_image.jpg'
          this.backgroundImageSet(this.bg_image_path);
          this.loadCanvasData();
          this.canvas.on('mouse:dblclick', (e) => {
            this.doubleClick(e)
          })
          this.canvas.on('mouse:up', (e) => {
            this.mouseUp(e)
          })

          document.addEventListener('keyup', e => {
            this.keyEventFunc(e);
          })
          document.addEventListener('keydown', function(e) {
              if (e.keyCode == 46 || (e.ctrlKey && e.keyCode == 8) ||
              (e.ctrlKey && e.shiftKey && e.keyCode == 65) || (e.ctrlKey && e.keyCode == 67) ||
              (e.ctrlKey && e.keyCode == 86) || e.keyCode == 37 || e.keyCode == 38 ||
              e.keyCode == 39 || e.keyCode == 40 || (e.ctrlKey && e.keyCode == 90)) {
                  e.preventDefault();
              }
          });
    }
}
</script>

<style>

</style>
