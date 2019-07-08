function generate($fileOptions) {
  
    // Use http://dopiaza.org/tools/datauri or similar service to convert an image into image data
var headerImgData =  dataLogo; 
  
  var doc = new jsPDF('l', 'pt', 'a2');
  
   var res = doc.autoTableHtmlToJson(document.getElementById($fileOptions.table_id));
  var imgElements = document.querySelectorAll('#'+ $fileOptions.table_id +' tbody img');
   var images = [];
      var i = 0;

  var header = function(data) {
    doc.setFontSize(18);
    doc.setTextColor(40);
    doc.setFontStyle('normal');

   // if(headerImgData){
          
          doc.addImage(headerImgData, $fileOptions.mime, data.settings.margin.left, 20, 50, 50);
  //  }
      

    doc.text($fileOptions.title, data.settings.margin.left + 80 , 50);
    //subtitle
    doc.setFontSize(9);
    doc.setTextColor(40);
    doc.setFontStyle('normal');    
    doc.text($fileOptions.subtitle, data.settings.margin.left + 80 , 65);    
  };




  //doc.autoTable(res.columns, res.data, options);
  
  doc.autoTable(res.columns, res.rows, {
        addPageContent : header,
        margin: {
          top: 80
        },
        bodyStyles: {verticalCellPadding: 30,
          textColor:40,
        },
       /* drawCell: function(cell, opts) {
          if (opts.column.dataKey === 11) {
            images.push({
              url: imgElements[i].src,
              x: cell.textPos.x,
              y: cell.textPos.y
            });
            i++;
          }
        },
        afterPageContent: function() {
          //header();
          for (var i = 0; i < images.length; i++) {
            doc.addImage(images[i].url, images[i].x, images[i].y, 20, 20);
          }
        }
        
        */
      });


  doc.save($fileOptions.filename);
}
