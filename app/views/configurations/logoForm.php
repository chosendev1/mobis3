       
       <div class="row-fluid">
            
            <div class="span6">                

                <div class="widget">
                     <div class="head">
                        <div class="icon"><i class="icos-pencil3"></i></div>
                        <h2>Upload Institutional Logo</h2>
                    </div>   
                    <div id="divbg">                   
                    <div class="block-fluid">
                    <form action="configuration/editLogo/999911" method="post" enctype='multipart/form-data'>
                       
                            <div class="span3">
                                <img src="logos/<?=$client->logo?>" class="img-polaroid" style="margin-bottom: 5px;"/>
                            </div>
                            <div class="span9">
                                <p class="np nm">&nbsp;</p>
                                <div class="input-append file">
                                    <input type="file" name="logo"/>

                                </div>
                            </div>
                        </div>
			
                        <div class="toolbar bottom TAL">
                            <input type="submit" class="btn btn-primary" value="Upload">
                        </div>
                        </form>
                    </div>
                </div>
               </div> 
            </div> </div>
       
       
