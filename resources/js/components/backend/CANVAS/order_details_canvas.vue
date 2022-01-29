<template>
  <div class="row">
    <div class="col-12">
      <h2 class="top_title text-center">Canvas</h2>
    </div>
    <div class="col-12">
      <div class="input-group mb-1" style="margin-left: 10px;max-width: 250px; float: left;">
        <div class="input-group-prepend">
          <button class="btn btn-outline-primary" type="button">小売選択</button>
        </div>
        <div class="form-control">Name</div>
      </div>
      <div
        class="active-pink-3 active-pink-4 mb-1"
        style="margin-left: 10px;max-width: 100%; float: left;"
      >
        <!-- <input class="form-control" type="text" placeholder="Search" aria-label="Search"> -->
        <b-button pill variant="info">Button</b-button>
      </div>
    </div>
    <div class="col-12">
      <div class="row">
        <!-- <div class="col"></div> -->
        <div class="col-2 text-center">
          <div class="card mb-4 box-shadow">
            <div class="card-header">
              <p class="my-0 font-weight-normal">発注日</p>
            </div>
            <div class="card-body p-0 d-flex flex-column justify-content-between">
              <div>
                <div class="form-group mb-0 form-control">2020-07-02</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-2 text-center">
          <div class="card mb-4 box-shadow">
            <div class="card-header">
              <p class="my-0 font-weight-normal">納品日</p>
            </div>
            <div class="card-body p-0 d-flex flex-column justify-content-between">
              <div>
                <div class="form-group mb-0 form-control">2020-07-02</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-2 text-center">
          <div class="card mb-4 box-shadow">
            <div class="card-header">
              <p class="my-0 font-weight-normal">Canvas name</p>
            </div>
            <div class="card-body p-0 d-flex flex-column justify-content-between">
              <div>
                <div class="form-group mb-0">
                  <multiselect
                    v-model="canvasSelectedName"
                    :options="allName"
                    :searchable="true"
                    :close-on-select="true"
                    :show-labels="false"
                    :selected-label="'選択中'"
                    placeholder="Canvas name"
                    label="canvas_name"
                    track-by="cmn_pdf_canvas_id"
                    @select="showCanvasBg($event)"
                  ><span slot="noOptions">候補がありません</span> <span slot="noResult">候補がありません</span></multiselect>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col"></div>
      </div>
    </div>
    <div class="col-12">
      <b-button variant="danger" style="margin-left: 1px;" @click="deleteObjects()">Delete</b-button>
      <b-button variant="primary" @click="printSingleCanvas">Print This Page</b-button>
      <b-button variant="primary" @click="printAllCanvas">Print All Page</b-button>
      <!-- <router-link :to="{name: 'UserProfileView', params: {id: 1} }" class="btn btn-primary" target='_blank'>Print</router-link>  -->
      <!-- <input class="btn btn-info" @change="bgImageChange" type="file" /> -->
      <b-button variant="warning" @click="clearCanvasObjects">Clear canvas</b-button>
      <b-button variant="warning" style="margin-left: 1px;" @click="canvasDesign(itr)">Reset Canvas</b-button>
      <br />
      <canvas id="c">Your browser does not support the canvas element.</canvas>
    </div>
    <div class="col-12 text-center">
      <span>{{(itr+1)}} of {{canvasDataLength}} </span>
      <b-button pill variant="info" :disabled="prev==0?true:false" v-if="canvasDataLength>1" @click="canvasDesignLeft"><b-icon icon="caret-left" font-scale="3"></b-icon></b-button>
      <b-button pill variant="info" :disabled="next==0?true:false" v-if="canvasDataLength>1" @click="canvasDesignRight"><b-icon icon="caret-right" font-scale="3"></b-icon></b-button>
    </div>

  </div>
  <!-- <div class="row"> -->

  <!-- </div> -->
</template>

<script>
import { jsPDF } from "jspdf";
export default {
  data() {
    return {
      loader:"",
      allName: [],
      canvasSelectedName: [],
      canvasDataLength:0,
      canvasAllData:[],
      positionObjects:[],
      itr:0,
      prev:0,
      next:0,
      canvas: null,
      bg_image_path: null,
      canvas_name: null,
      canvas_id: null,
      byr_order_id: null,
      canvas_width: 1219,
      canvas_height: 510,
      pointerX: 100,
      pointerY: 50,
    };
  },
  methods: {
    loadCanvasData() {
      axios.post(this.BASE_URL + "api/load_canvas_data", {
          byr_order_id: this.byr_order_id,
          cmn_scenario_id:2,
        }).then(({ data }) => {
          if (data.canvas_data.length>0) {
            this.allName=data.canvas_data
            this.canvasSelectedName=this.allName[0]
            this.bg_image_path=this.BASE_URL + "storage/app/public/backend/images/canvas/Background/"+(this.allName)[0].canvas_bg_image;
            this.backgroundImageSet(this.bg_image_path);
          }
          if (data.can_info) {
            this.canvasAllData=data.can_info
            this.canvasDataLength=this.canvasAllData.length;
            if (this.canvasDataLength>1) {
              this.prev=0;
              this.next=(this.canvasDataLength-1);
            }
            if (this.canvasDataLength>0) {
              this.canvasDesign(this.itr)
            }

          }
          this.loader.hide();
        })
        .catch(() => {
          this.sweet_advance_alert();
          this.loader.hide();
        });
    },
    canvasDesignLeft(){
          this.prev-=1;
          this.next+=1;
          this.itr-=1;
          this.canvasDesign(this.itr)
    },
    canvasDesignRight(){
      this.prev+=1;
      this.next-=1;
      this.itr+=1;
      this.canvasDesign(this.itr)
    },
    canvasDesign(iteration){
      if (!this.canvasSelectedName) {
        alert("Please select canvas name");
        return 0;
      }
      this.clearCanvasObjects();
      if (this.canvasDataLength>0) {
        var canvasAllDataArray=this.canvasAllData[iteration];
        if (canvasAllDataArray.length) {
          // var position_values= JSON.parse(this.canvasSelectedName.position_values);
          var position_values= JSON.parse(this.canvasSelectedName.canvas_objects).objects;
          position_values.forEach(element => {
            // console.log(element.text);
            var split_element=(this.splitString(element.text))
            var item="";
            if (split_element[1]<canvasAllDataArray.length) {
                // item=canvasAllDataArray[split_element[1]][split_element[0]];

                if (canvasAllDataArray[split_element[1]][split_element[0]]!=null) {
                  item=canvasAllDataArray[split_element[1]][split_element[0]];
                }else if(canvasAllDataArray[split_element[1]][split_element[0]]!=null){
                  item=canvasAllDataArray[split_element[1]][split_element[0]];
                }else{
                  item=split_element[0];
                }
                // "order_lot_inputs" not in database
            }else{
                if(!(Array.isArray(split_element))){
                  if (split_element=="total_order_qty") {
                    item=canvasAllDataArray[0]['total_order_qty']
                  }else if(split_element=="total_selling_price"){
                    item=canvasAllDataArray[0]['total_selling_price']
                  }else if(split_element=="total_cost_price"){
                    item=canvasAllDataArray[0]['total_cost_price']
                  }else if(split_element=="total_confirm_quantity"){
                    item=(canvasAllDataArray[0]['total_confirm_quantity']==0)?"":canvasAllDataArray[0]['total_confirm_quantity']
                  }else if(split_element=="center_code"){
                    item=canvasAllDataArray[0]['center_code']
                  }else if(split_element=="center_name"){
                    item=canvasAllDataArray[0]['center_name']
                  }else{
                    item=split_element;
                  }
                  // console.log(item);
                }
              }
              // console.log(item);
            this.createObj(element.left,element.top,element.width,element.height,element.fontSize,element.textAlign,element.lineHeight,element.scaleX,element.scaleY,item.toString(),'auto')
          });
        }
        this.emptyObjRemove();
      }
    },
    emptyObjRemove(){
      var canvasAllObj = this.canvas.getObjects();
        if (canvasAllObj) {
          canvasAllObj.forEach(obj => {
            if (obj.text=="") {
              this.canvas.remove(obj);
            }
          });
        }
    },
    splitString(givenString){
      var first_part=givenString.substring(0, 5);
      var main_part=givenString.slice(5,-2);
      var last_part=givenString.slice(-1);
      var result=[];
      if (first_part=="[db]_") {
        result.push(main_part)
        result.push(last_part)
      }else{
        result=givenString
      }
       return result;
    },
    showCanvasBg($event) {
      this.canvasSelectedName=$event;
      this.bg_image_path = this.BASE_URL + "storage/app/public/backend/images/canvas/Background/"+$event.canvas_bg_image;
      this.backgroundImageSet(this.bg_image_path);
      this.canvasDesign(this.itr);
    },
    canvasDataView(text_data) {
      var c = this.canvas;
      c.loadFromJSON(text_data, function () {
        c.renderAll();
      });
    },
    deleteObjects() {
      var canvas = this.canvas;
      var activeObjects = canvas.getActiveObjects();
      if (activeObjects.length) {
        if (confirm("Do you want to delete the selected item??")) {
          activeObjects.forEach(function (object) {
            canvas.remove(object);
          });
        }
      } else {
        alert("Please select the drawing to delete");
      }
    },
    canvasClear() {
      var obj = this.canvas.getObjects();
      this.canvas.remove(obj);
    },
    canvasFieldClear() {
      this.canvas_name = null;
      this.canvas_id = null;
      this.submit_button = "Save";
      this.update_image_info = null;
      if (this.allName.length>0) {
        this.bg_image_path = this.BASE_URL + "storage/app/public/backend/images/canvas/Background/"+(this.allName)[0].canvas_bg_image;
      }else{
        this.bg_image_path = this.BASE_URL + "storage/app/public/backend/images/canvas/Background/bg_image.jpg";
      }
      this.backgroundImageSet(this.bg_image_path);
    },
    bgImageProcess(bg_image) {
      var img = new Image();
      var mainCanvas = this.canvas;
      img.onload = function () {
        var f_img = new fabric.Image(img);
        mainCanvas.setBackgroundImage(
          f_img,
          mainCanvas.renderAll.bind(mainCanvas),
          {
            // width: mainCanvas.width,
            // height: mainCanvas.height,
            originX: "left",
            originY: "top",
          }
        );
        mainCanvas.setWidth(img.width);
        mainCanvas.setHeight(img.height);
      };
      img.src = bg_image;
    },
    clearCanvasObjects() {
      this.canvas.clear();
      this.canvasFieldClear();
    },
    printAllCanvas() {
      this.loader =Vue.$loading.show();
      var img_dym=this.bgImageDymension(this.bg_image_path);
      var doc = new jsPDF({
        orientation: "landscape",
        unit: "in",
        // format: [15, 10]
        // format: [((img_dym.width)/96)+1, ((img_dym.height)/96)+1]
      });
      var imgSrc = this.bg_image_path;
      var canvas=this.canvas;
      canvas.backgroundImage = 0;
      canvas.renderAll();
      var all_image="";
      for (let i = 0; i < (this.canvasDataLength); i++) {
        setTimeout(() => {
          this.deselectObject()
           this.canvasDesign(i);
           var image_data=this.canvas.toDataURL();
           doc.addImage(image_data,"",0,0)
           if (i!=(this.canvasDataLength-1)) {
            doc.addPage();
           }
            // console.log(image_data);
           }, 500);
      }
      setTimeout(()=>{
        doc.autoPrint();
        var oHiddFrame = document.createElement("iframe");
        oHiddFrame.style.position = "fixed";
        oHiddFrame.style.visibility = "hidden";
        oHiddFrame.src = doc.output('bloburl');
        document.body.appendChild(oHiddFrame);
        // window.open(doc.output('bloburl'), '_blank');
        this.canvasDesign(this.itr);
        this.loader.hide();
      },(this.canvasDataLength*500))

      // this.canvasDesign(this.itr);
    },
    printSingleCanvas(){
      this.loader =Vue.$loading.show();
      this.deselectObject();
      this.printData(".canvas-container");
      setTimeout(()=>{
        this.loader.hide();
      },510)
    },
    printData(divVar) {
      var canvas = this.canvas;
      var thisVar = this;
      var imgSrc = this.bg_image_path;
      // var imgSrc = canvas.backgroundImage._element.src;
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
        doctypeString: "", // enter a different doctype for older markup
        removeScripts: false, // remove script tags from print content
        copyTagClasses: false, // copy classes from the html & body tag
        beforePrintEvent: null, // function for printEvent in iframe
        beforePrint: null, // function called before iframe is filled
        afterPrint: null, // function called before iframe is removed
      });
      // console.log(ppp);
      setTimeout(function () {
        thisVar.backgroundImageSet(imgSrc);
        // this.loader.hide();
      }, 510);
    },
    deselectObject() {
      var canvas = this.canvas;
      var activeObjects = canvas.getActiveObjects();
      if (activeObjects.length) {
        // if (confirm('Do you want to delete the selected item??')) {
        activeObjects.forEach(function (object) {
          canvas.discardActiveObject(object);
          canvas.renderAll();
        });
        // }
      }
    },
    createObj(left=100,top=50,width=150,height=22,fontSize=20,textAlign="left",lineHeight=1.16,scaleX=1,scaleY=1,text="Created by default",createdBy='auto') {
      var activeObject = this.canvas.getActiveObject();
      var text_data = [
        {
          originX: 'left',
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
          textAlign:textAlign,
          textBackgroundColor: "",
          charSpacing: 0,
          minWidth: 20,
          splitByGrapheme: 1, //False
          objectCaching: false,
        },
      ];
        if (createdBy!='auto') {
          if (!activeObject) {
            this.addText(text_data);
          }
        }else{
          this.addText(text_data);
        }
    },
    addText(text_data) {
      for (let i = 0; i < text_data.length; i++) {
        var oText = new fabric.Textbox(text_data[i]["text"], {
          originX: text_data[i]["originX"],
          originY: text_data[i]["originY"],
          left: text_data[i]["left"],
          top: text_data[i]["top"],
          width: text_data[i]["width"],
          height: text_data[i]["height"],
          fill: text_data[i]["fill"],
          stroke: text_data[i]["stroke"],
          strokeWidth: text_data[i]["strokeWidth"],
          strokeDashArray: text_data[i]["strokeDashArray"],
          strokeLineCap: text_data[i]["strokeLineCap"],
          strokeDashOffset: text_data[i]["strokeDashOffset"],
          strokeLineJoin: text_data[i]["strokeLineJoin"],
          strokeMiterLimit: text_data[i]["strokeMiterLimit"],
          scaleX: text_data[i]["scaleX"],
          scaleY: text_data[i]["scaleY"],
          angle: text_data[i]["angle"],
          flipX: text_data[i]["flipX"],
          flipY: text_data[i]["flipY"],
          opacity: text_data[i]["opacity"],
          shadow: text_data[i]["shadow"],
          visible: text_data[i]["visible"],
          clipTo: text_data[i]["clipTo"],
          backgroundColor: text_data[i]["backgroundColor"],
          fillRule: text_data[i]["fillRule"],
          paintFirst: text_data[i]["paintFirst"],
          globalCompositeOperation: text_data[i]["globalCompositeOperation"],
          transformMatrix: text_data[i]["transformMatrix"],
          skewX: text_data[i]["skewX"],
          skewY: text_data[i]["skewY"],
          fontSize: text_data[i]["fontSize"],
          fontWeight: text_data[i]["fontWeight"],
          fontFamily: text_data[i]["fontFamily"], //Arial, Times New Roman, Helvetica, sans-serif
          fontStyle: text_data[i]["fontStyle"],
          lineHeight: text_data[i]["lineHeight"],
          underline: text_data[i]["underline"],
          overline: text_data[i]["overline"],
          linethrough: text_data[i]["linethrough"],
          textAlign: text_data[i]["textAlign"],
          textBackgroundColor: text_data[i]["textBackgroundColor"],
          charSpacing: text_data[i]["charSpacing"],
          minWidth: text_data[i]["minWidth"],
          splitByGrapheme: text_data[i]["splitByGrapheme"],
          objectCaching: false,
          // hasControls: false,
        });
        this.canvas.add(oText).setActiveObject(oText);
        this.canvas.renderAll();
      }
    },
    doubleClick(option) {
      // console.log(option);
      this.pointerX = option.pointer.x;
      this.pointerY = option.pointer.y;
      this.createObj(this.pointerX-50,this.pointerY,150,22,20,"left",1.16,1,1,"Created by Click",'clicked');
    },
    getCanvasBgImage() {
      var can_image = this.canvas.toDataURL({
        format: "png",
        quality: 0.8,
      });
      return can_image;
    },
    canvasData() {
      return this.canvas.toJSON();
    },
    backgroundImageSet(imgUrl) {
      var mainCanvas = this.canvas;
      this.canvas.setBackgroundImage(
        imgUrl,
        this.canvas.renderAll.bind(this.canvas),
        {
          // Optionally add an opacity lvl to the image
          backgroundImageOpacity: 0,
          // should the image be resized to fit the container?
          backgroundImageStretch: false,
          // image size as canvas size
          // width: this.canvas.width,
          // height: this.canvas.height
        }
      );
      // canvas size by image size
      this.bgImageWH(imgUrl);
      // var img_dym=this.bgImageDymension(imgUrl);
      // mainCanvas.setWidth(img_dym.width);
      // mainCanvas.setHeight(img_dym.height);
    },
    bgImageWH(imgUrl) {
      var mainCanvas = this.canvas;
      const img = new Image();
      img.src = imgUrl;
      img.onload = function () {
        mainCanvas.setWidth(img.naturalWidth);
        mainCanvas.setHeight(img.naturalHeight);
      };
    },
    bgImageDymension(imgUrl){
      var img = new Image();
      img.src = imgUrl;
      // img.onload = function () {
         var img_W=img.naturalWidth;
         var img_H=img.naturalHeight;
      // };
      var imageDymension={width:img_W,height:img_H}
      return imageDymension;
    },
    // }
  },
  created() {
    // this.canvasOpen();
  },
  mounted() {
    this.loader =Vue.$loading.show();
    this.byr_order_id = this.$route.params.byr_order_id;
    this.canvas = new fabric.Canvas("c");
    this.canvas.setWidth(this.canvas_width);
    this.canvas.setHeight(this.canvas_height);
    // this.canvas.controlsAboveOverlay = true;
    this.bg_image_path = this.BASE_URL + "storage/app/public/backend/images/canvas/Background/bg_image.jpg";
    this.backgroundImageSet(this.bg_image_path);
    // initAligningGuidelines(this.canvas);
    // initCenteringGuidelines(this.canvas);
    this.loadCanvasData();
    this.canvas.on("mouse:dblclick", (e) => {
      this.doubleClick(e);
    });
  },
};
</script>

<style>
</style>
