define('FormData',[],function(){
      var form = function(){
      	   
          this.dataURLtoBlob = function(dataurl){
			    var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
			        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
			    while(n--){
			        u8arr[n] = bstr.charCodeAt(n);
			    }
			    return new Blob([u8arr], {type:mime});
          }

          this.append = function(json){
              var fd = new FormData();
              for(var x in json){
                     if("undefined" != typeof (json[x].type)&&json[x].type=='file'){
                      
		                     for(var w in json[x].data){
		                         json[x].data[w] = this.dataURLtoBlob(json[x].data[w]);
		                     }
                     } 
                     var data = json[x].data;
                     for(var w in data){
                       fd.append(x,data[w]);
                     }
              }

              return fd;

          };
      };
      return new form();

});