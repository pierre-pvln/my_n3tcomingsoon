var BackgroundImage=new Class({Implements:[Events,Options],options:{src:null,alt:"","class":"background-image"},initialize:function(a){this.setOptions(a);if(this.options.src){this.image=new Element("img",{src:this.options.src,alt:this.options.alt,"class":this.options["class"],styles:{position:"absolute"}}).addEvent("load",this.loaded.bind(this));window.addEvents({resize:this.resize.bind(this)})}},loaded:function(){this.wrapper=new Element("div",{"class":this.options["class"],styles:{position:"absolute",overflow:"hidden","z-index":-1}}).inject(document.body,"top");this.image.inject(this.wrapper);this.imageSize=this.image.getSize();this.imageRatio=this.imageSize.x/this.imageSize.y;this.resize()},resize:function(){if(this.imageSize){var a=window.getSize();var b=a.x/a.y;var c={width:0,height:0,left:0,top:0};if(this.imageRatio<b){c.width=a.x;c.height=Math.round(c.width/this.imageRatio);c.top=Math.round((a.y-c.height)/2)}else{if(this.imageRatio>b){c.height=a.y;c.width=Math.round(c.height*this.imageRatio);c.left=Math.round((a.x-c.width)/2)}else{c.width=a.x;c.height=a.y}}this.wrapper.setStyles({width:a.x,height:a.y});this.image.setStyles(c)}},});