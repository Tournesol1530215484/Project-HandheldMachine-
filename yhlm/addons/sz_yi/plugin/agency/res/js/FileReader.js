define(function(){
      return {
         read:function(file,action){
             var reader = new FileReader( );
             reader.readAsDataURL(file);
             reader.onload = function(e)
             {
             	action(this.result);
              //  $("#select-box").parent().before(baidu.template('tpl-img-box',{data:{img:this.result}}  ));
             };
         } 
      };
});