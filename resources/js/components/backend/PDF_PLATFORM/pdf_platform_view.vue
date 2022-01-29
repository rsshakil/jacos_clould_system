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
      <label for="printBg">Print Background</label> <input type="checkbox" v-model="printBg" id="printBg">
      <br />
      <br />
    </div>
    <div class="col-12" style="height:500px;overflow:auto;">
        <center><canvas id="c">Your browser does not support the canvas element.</canvas></center>
    </div>
    <div class="col-12 text-center">
      <span>{{canvasDataLength==0?0:(myItr+1)}} of {{Math.ceil(canvasDataLength/2)}} </span>
      <b-button pill variant="info" :disabled="prev<=0?true:false" v-if="canvasDataLength>1" @click="canvasDesignLeft"><b-icon icon="caret-left" font-scale="3"></b-icon></b-button>
      <b-button pill variant="info" :disabled="next<=0?true:false" v-if="canvasDataLength>1" @click="canvasDesignRight"><b-icon icon="caret-right" font-scale="3"></b-icon></b-button>
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
      myItr:0,
      itr:0,
      prev:0,
      next:0,
      canvas: null,
      bg_image_path: null,
      canvas_name: null,
      canvas_id: null,
      data_order_id: 1,
      canvas_width:790,
      canvas_height:1040,
      // canvas_width: 1219,
      // canvas_height: 510,
      pointerX: 100,
      pointerY: 50,
      line_gap:28,
      printBg:false,
      line_per_page:26,
      scenario_id:14
    };
  },
  methods: {
    loadCanvasData() {

      axios.post(this.BASE_URL + "api/load_pdf_platform_canvas_data", {
          data_order_id: this.data_order_id,
          scenario_id:this.scenario_id,
          line_per_page:this.line_per_page,
        })
        .then(({ data }) => {
          var canvas_data=data.canvas_data;
          if (canvas_data.length>0) {
            this.allName=canvas_data
            this.canvasSelectedName=this.allName[0]
            this.line_gap=Number(this.canvasSelectedName.line_gap);
            if ((this.allName)[0].canvas_bg_image) {
              this.bg_image_path=this.BASE_URL + "storage/app/public/backend/images/canvas/pdf_platform/Background/"+(this.allName)[0].canvas_bg_image;
              this.backgroundImageSet(this.bg_image_path);
            }
          }
          if (data.can_info.length>0) {
            this.canvasAllData=data.can_info
            this.canvasDataLength=this.canvasAllData.length;
            if (this.canvasDataLength>0) {
              this.prev=0;
              this.next=Math.ceil(this.canvasDataLength)-1;
            //   this.next=(this.canvasDataLength-1);
              this.canvasDesign(this.itr)
            }
            // if (this.canvasDataLength>0) {

            // }

          }else{

            //   this.canvasDesign(this.itr)
          }
        })
        .catch(() => {
          this.sweet_advance_alert();
        });
    },
    canvasDesignLeft(){
          this.myItr-=1;
          this.prev-=2;
          this.next+=2;
          this.itr-=2;
          this.canvasDesign(this.itr)
    },
    canvasDesignRight(){
        this.myItr+=1;
      this.prev+=2;
      this.next-=2;
      this.itr+=2;
      this.canvasDesign(this.itr)
    },
    canvasDesign(iteration,loopVal=0){
      var canvas=this.canvas;
      var _this=this;
      if (!this.canvasSelectedName) {
        alert("Please select canvas name");
        return 0;
      }
      this.clearCanvasObjects(loopVal);
    //   if (this.canvasDataLength>0) {

          var position_values= JSON.parse(this.canvasSelectedName.canvas_objects).objects;
          position_values.forEach(element => {
            if (element.type==="textbox") {
              var positionTop=element.top;
              var split_element=(this.splitString(element.text))

            var item='';

             if(!(Array.isArray(split_element))){
               item=split_element;

               this.createObj(element.left,element.top,element.width,element.height,element.fontSize,element.textAlign,element.lineHeight,element.scaleX,element.scaleY,item.toString(),'auto')
             }else{
               if (split_element.length>2) {
                //    console.log(iteration)
                   if (split_element[2]==0) {
                       if (split_element[0]=="mes_lis_ord_tra_ins_goods_classification_code") {
                        //    item = this.getbyrjsonValueBykeyName(
                        //     "mes_lis_ord_tra_ins_goods_classification_code",
                        //     '01',
                        //     "orders"
                        //     )
                        //     console.log("My Item",item);
                           item=this.canvasAllData[iteration][0][split_element[0]];
                       }else{
                           item=this.canvasAllData[iteration][0][split_element[0]];
                       }
                   }else{
                       if (typeof this.canvasAllData[(iteration+1)] !== 'undefined') {
                           if (Object.keys(this.canvasAllData[(iteration+1)])[0]) {
                               if (split_element[0]=="mes_lis_ord_tra_ins_goods_classification_code") {
                                    item=this.canvasAllData[(iteration+1)][0][split_element[0]];
                                }else{
                                    item=this.canvasAllData[(iteration+1)][0][split_element[0]];
                                }
                       }else{
                           item='';
                       }
                       }else{
                           item='';
                       }
                   }

                 this.createObj(element.left,element.top,element.width,element.height,element.fontSize,element.textAlign,element.lineHeight,element.scaleX,element.scaleY,item.toString(),'auto')
               }else{
                //    return 0;
                 for (let i = 0; i < this.canvasAllData[iteration].length; i++) {
                     if (split_element[1]==0) {
                         item=this.canvasAllData[iteration][i][split_element[0]];
                     }else{
                         if (typeof this.canvasAllData[(iteration+1)] !== 'undefined') {
                            if (Object.keys(this.canvasAllData[(iteration+1)])[i]) {
                             item=this.canvasAllData[(iteration+1)][i][split_element[0]];
                         }else{
                             item='';
                         }
                        }else{
                            item='';
                        }

                     }
                 this.createObj(element.left,positionTop,element.width,element.height,element.fontSize,element.textAlign,element.lineHeight,element.scaleX,element.scaleY,item.toString(),'auto')
                  positionTop+=this.line_gap;
               }
               }
             }
            }else{

                if (element.type=="line") {
                  var line = new fabric.Line([Number(element.left), Number(element.top), Number(element.width), Number(element.top)], {
                    stroke: 'black'
                  });
                  canvas.add(line);
                }else if (element.type=="rect") {
                  var rect = new fabric.Rect({
                  left: Number(element.left),
                  top: Number(element.top),
                  width: Number(element.width),
                  height: Number(element.height),
                  fill: element.fill,
                  angle: Number(element.angle),
                  padding: Number(element.padding)
                });
                  canvas.add(rect);
                }else if(element.type=="circle"){
                  var circle = new fabric.Circle({
                  left: element.left,
                  top: element.top,
                  radius: element.radius,
                  fill: element.fill,
                  stroke: element.stroke,
                  strokeWidth: element.strokeWidth
                  });
                  canvas.add(circle);
                }else{
                  // console.log(element);
                }
            }

          });
        // }
        this.emptyObjRemove();
    //   }
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
      var first_part=givenString.substring(0, 6);

      var last_part=givenString.slice(-1);
      var main_part="";
      if (last_part==0) {
        main_part=givenString.slice(6,-2);
      }else{
        main_part=givenString.slice(6);
      }
      var result=[];
      if (first_part=="[db0]_" && last_part==0) {
        result.push(main_part)
        result.push(last_part)
        result.push(0)
      }else if (first_part=="[db0]_" && last_part!=0) {
        result.push(main_part)
        result.push(0)
      }else if (first_part=="[db1]_" && last_part==0) {
        result.push(main_part)
        result.push(last_part)
        result.push(1)
      }else if (first_part=="[db1]_" && last_part!=0) {
        result.push(main_part)
        result.push(1)
      }else{
        result=givenString
      }
       return result;
    },
    showCanvasBg($event) {
      this.canvasSelectedName=$event;
      if ($event.canvas_bg_image) {
        this.bg_image_path = this.BASE_URL + "storage/app/public/backend/images/canvas/pdf_platform/Background/"+$event.canvas_bg_image;
        this.backgroundImageSet(this.bg_image_path);
      }
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
    canvasFieldClear(loopVal=0) {
      var _this=this;
        if (_this.allName.length>0) {
        if ((_this.allName)[0].canvas_bg_image) {
        //   console.log((_this.allName)[0].canvas_bg_image)
          _this.bg_image_path = _this.BASE_URL + "storage/app/public/backend/images/canvas/pdf_platform/Background/"+(_this.allName)[0].canvas_bg_image;
          _this.backgroundImageSet(_this.bg_image_path);
        }else{
          _this.canvas.setBackgroundColor("#fff");
        }
      // }
    }
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
    clearCanvasObjects(loopVal=0) {
      // this.canvasClear();
      var _this=this;
      if (loopVal==1) {
        var obj = _this.canvas.getObjects();
        obj.forEach(function(o){
          _this.canvas.remove(o);
          });
         if (_this.allName.length>0) {
        if ((_this.allName)[0].canvas_bg_image) {
          // console.log((_this.allName)[0].canvas_bg_image)
          _this.bg_image_path = _this.BASE_URL + "storage/app/public/backend/images/canvas/pdf_platform/Background/"+(_this.allName)[0].canvas_bg_image;
          _this.backgroundImageSet(_this.bg_image_path);
        }else{
          _this.canvas.setBackgroundColor("#fff");
        }
      }
      // this.canvas.renderAll();
      }else{
        this.canvas.clear();
        this.canvasFieldClear(loopVal);
      }
    },
    printAllCanvas() {
      this.loader =Vue.$loading.show();
      var img_dym=this.bgImageDymension(this.bg_image_path);
      var doc = new jsPDF({
        orientation: "portrait", //portrait or landscape
        unit: "in",
        // format: [15, 10]
        // format: [((img_dym.width)/96)+1, ((img_dym.height)/96)+1]
      });
      var imgSrc = this.bg_image_path;
      var canvas=this.canvas;

      for (let i = 0; i < (this.canvasDataLength); i++) {
        setTimeout(() => {
          if (this.printBg==false) {
            if (imgSrc) {
              // var imgSrc = canvas.backgroundImage._element.src;
              canvas.backgroundImage = 0;
              canvas.renderAll();
            }
          }
          this.deselectObject()
           this.canvasDesign(i,1);
           var image_data=this.canvas.toDataURL();
           doc.addImage(image_data,"",0,0)
           if (i!=(this.canvasDataLength-1)) {
            doc.addPage();
           }
            // console.log(image_data);
           }, 400);
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
      },(this.canvasDataLength*300))

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
      if (this.printBg==false) {
        if (imgSrc) {
          // var imgSrc = canvas.backgroundImage._element.src;
          canvas.backgroundImage = 0;
          canvas.renderAll();
        }
      }
      // var imgSrc = canvas.backgroundImage._element.src;
      // canvas.backgroundImage = 0;
      // canvas.renderAll();
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
      if (this.printBg==false) {
        // console.log()
         if (imgSrc) {
      setTimeout(function () {
        thisVar.backgroundImageSet(imgSrc);
        thisVar.loader.hide();
      }, 510);
         }
      }
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
      mainCanvas.setBackgroundImage(imgUrl,
        mainCanvas.renderAll.bind(mainCanvas),
        {
          // Optionally add an opacity lvl to the image
          backgroundImageOpacity: 0,
          // should the image be resized to fit the container?
          backgroundImageStretch: false,
          // image size as canvas size
        //   width: this.canvas.width,
        //   height: this.canvas.height
        }
      );
      // console.log(imgUrl);
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
    // console.log("Byter ID",this.$session.get('byr_buyer_id'));
    Fire.$emit('permission_check_for_buyer',this.$session.get('byr_buyer_id'));
    // this.canvasOpen();
  },
  mounted() {
    // this.data_order_id = this.$route.params.data_order_id;
    this.canvas = new fabric.Canvas("c");
    this.canvas.setWidth(this.canvas_width);
    this.canvas.setHeight(this.canvas_height);
    this.canvas.setBackgroundColor("#fff");
    // this.canvas.controlsAboveOverlay = true;
    // this.bg_image_path = this.BASE_URL + "storage/app/public/backend/images/canvas/pdf_platform/Background/bg_image.png";
    // this.backgroundImageSet(this.bg_image_path);
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
